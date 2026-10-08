@extends('layouts.master')

@section('pageTitle', 'Doubt Allocation')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/doubt-allocation.css') }}?v={{ time() }}">
@endpush

@section('content')
    <div class="da-page">

        {{-- Header --}}
        <div class="da-header">
            <div class="da-header-left">
                <h2>Doubt allocation grid &mdash;
                    {{ $dates[0]['full'] === $selectedDate ? 'today' : \Carbon\Carbon::parse($selectedDate)->format('D, j M Y') }}
                </h2>
                <p>Click any empty slot to allocate &middot; filled slots are locked</p>
            </div>
            <div class="da-date-badge">
                <i class="fa-regular fa-clock"></i>
                {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
            </div>
        </div>

        {{-- Date tabs --}}
        <div style="display:flex; gap:0.5rem; margin-bottom:1.25rem; flex-wrap:wrap;">
            @foreach($dates as $dateItem)
                <a href="{{ route('doubt-allocation.index', ['date' => $dateItem['full']]) }}"
                    class="da-date-tab {{ $selectedDate === $dateItem['full'] ? 'da-date-tab-active' : 'da-date-tab-inactive' }}">
                    {{ $dateItem['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Stats Bar --}}
        <div class="da-stats-bar">
            <div class="da-stat-pill">
                <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>
                <span>Staff:</span> <span class="stat-num">{{ $staffList->count() }}</span>
            </div>
            <div class="da-stat-pill used">
                <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>
                <span>Allocated:</span> <span class="stat-num">{{ $allocatedCount }}</span>
            </div>
            <div class="da-stat-pill free">
                <span style="width:8px;height:8px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
                <span>Available:</span> <span class="stat-num">{{ $totalSlots - $allocatedCount }}</span>
            </div>
            <div class="da-stat-pill">
                <span style="width:8px;height:8px;border-radius:50%;background:#94a3b8;display:inline-block;"></span>
                <span>Total Slots:</span> <span class="stat-num">{{ $totalSlots }}</span>
            </div>
        </div>

        @if($staffList->isEmpty())
            <div class="da-empty-state">
                <i class="fa-solid fa-users-slash"></i>
                <p>No technical staff with schedules found for {{ $dayOfWeek }}.</p>
            </div>
        @else

            {{-- Grid --}}
            <div class="da-grid-wrapper">

                {{-- Staff header row --}}
                @php
                    $colCount = $staffList->count();
                    $gridTemplate = '110px repeat(' . $colCount . ', 1fr)';
                    $avatarColors = ['#60a5fa', '#34d399', '#fb923c', '#c084fc', '#f472b6', '#38bdf8', '#a78bfa'];
                @endphp

                <div class="da-staff-header" style="grid-template-columns: {{ $gridTemplate }}">
                    <div class="da-time-label-head"></div>
                    @foreach($staffList as $si => $staff)
                        @php
                            $initials = collect(explode(' ', $staff->name))->map(fn($w) => strtoupper(substr($w, 0, 1)))->take(2)->join('');
                            $bgColor = $avatarColors[$si % count($avatarColors)];
                        @endphp
                        <div class="da-staff-col-head">
                            <div class="da-staff-avatar" style="background: {{ $bgColor }};">
                                {{ $initials }}
                            </div>
                            <div class="da-staff-name">{{ $staff->name }}</div>
                        </div>
                    @endforeach
                </div>

                {{-- Slot rows --}}
                <div class="da-grid-body">
                    @forelse($timeSlots as $slot)
                        <div class="da-grid-row" style="grid-template-columns: {{ $gridTemplate }}">

                            {{-- Time label --}}
                            <div class="da-time-cell">
                                {{ date('g:i A', strtotime($slot)) }}
                            </div>

                            {{-- Staff cells --}}
                            @foreach($staffList as $si => $staff)
                                @php
                                    $bgColor = $avatarColors[$si % count($avatarColors)];
                                    $isPast = \Carbon\Carbon::parse($selectedDate . ' ' . $slot)->isPast();

                                    // Check if this slot is already allocated
                                    $allocated = $allocatedSlots->first(
                                        fn($a) =>
                                            $a->staff_id == $staff->id &&
                                            \Carbon\Carbon::parse($a->allocated_slot)->format('H:i') === \Carbon\Carbon::parse($slot)->format('H:i')
                                    );

                                    // Check if this slot is already booked for Demo
                                    $demoAllocated = $demoBookings->first(
                                        fn($d) =>
                                            ($d->assigned_staff_id == $staff->id || $d->staff_id == $staff->id) &&
                                            \Carbon\Carbon::parse($d->demo_time)->format('H:i') === \Carbon\Carbon::parse($slot)->format('H:i')
                                    );
                                    
                                    // Check if this slot is booked via SessionBooking
                                    $sessionAllocated = $sessionBookings->first(
                                        fn($b) =>
                                            $b->staff_id == $staff->id &&
                                            \Carbon\Carbon::parse($b->time)->format('H:i') === \Carbon\Carbon::parse($slot)->format('H:i')
                                    );
                                @endphp

                                <div class="da-slot-cell">
                                    @if($allocated)
                                        {{-- Allocated slot --}}
                                        <div class="da-slot-filled {{ $allocated->status === 'Completed' ? 'is-completed' : '' }}">
                                            <div class="da-slot-header">
                                                <div class="da-slot-student" title="{{ $allocated->ticket->user->name ?? 'Unknown' }}">
                                                    {{ $allocated->ticket->user->name ?? 'Unknown' }}</div>
                                                <div class="da-slot-actions">
                                                    @if($allocated->status === 'Scheduled')
                                                        <button type="button" class="btn-action btn-complete" title="Mark as Completed"
                                                            onclick="completeSlot('{{ $allocated->ticket->id }}', '{{ $staff->id }}', '{{ $selectedDate }}', '{{ $slot }}')">
                                                            <i class="fa-solid fa-check"></i>
                                                        </button>
                                                    @endif
                                                    @if(!$isPast)
                                                        <button type="button" class="btn-action btn-deallocate" title="Deallocate"
                                                            onclick="deallocateSlot('{{ $allocated->ticket->id }}', '{{ $staff->id }}', '{{ $selectedDate }}', '{{ $slot }}')">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="da-slot-subject"
                                                title="{{ $allocated->ticket->title ?? $allocated->ticket->subject ?? '—' }}">
                                                {{ \Illuminate\Support\Str::limit($allocated->ticket->title ?? $allocated->ticket->subject ?? '—', 35) }}
                                            </div>
                                        </div>
                                    @elseif($demoAllocated)
                                        {{-- Demo booked slot (CallLog) --}}
                                        <div class="da-slot-filled" style="background:#e0e7ff;border:1.5px solid #818cf8;color:#3730a3;">
                                            <div class="da-slot-header">
                                                <div class="da-slot-student" title="{{ $demoAllocated->contact->name ?? 'Unknown' }}" style="color:#3730a3;">
                                                    {{ $demoAllocated->contact->name ?? 'Unknown' }}</div>
                                            </div>
                                            <div class="da-slot-subject" style="color:#4338ca;">
                                                <i class="fa-solid fa-video" style="font-size:0.75rem;margin-right:4px;"></i> Demo Booked
                                            </div>
                                        </div>
                                    @elseif($sessionAllocated)
                                        {{-- Session Booking slot (Demo or Doubt) --}}
                                        <div class="da-slot-filled" style="background:#e0e7ff;border:1.5px solid #818cf8;color:#3730a3;">
                                            <div class="da-slot-header">
                                                <div class="da-slot-student" title="{{ $sessionAllocated->admission->contact->name ?? 'Unknown' }}" style="color:#3730a3;">
                                                    {{ $sessionAllocated->admission->contact->name ?? 'Unknown' }}</div>
                                            </div>
                                            <div class="da-slot-subject" style="color:#4338ca;">
                                                @if($sessionAllocated->type === 'demo')
                                                    <i class="fa-solid fa-video" style="font-size:0.75rem;margin-right:4px;"></i> Demo Session
                                                @else
                                                    <i class="fa-solid fa-question-circle" style="font-size:0.75rem;margin-right:4px;"></i> Doubt Session
                                                @endif
                                                @if($sessionAllocated->topic)
                                                    <br><span style="font-size: 0.65rem;">&bull; {{ $sessionAllocated->topic }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- Available empty slot --}}
                                        @if($isPast)
                                            <div class="da-slot-btn"
                                                style="cursor: not-allowed; opacity: 0.5; border: 1.5px solid #e2e8f0; background: #f8fafc;">
                                                <span
                                                    style="font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Passed</span>
                                            </div>
                                        @else
                                            <button type="button" class="da-slot-btn"
                                                onclick="openAllocateModal('{{ $staff->id }}', '{{ addslashes($staff->name) }}', '{{ $slot }}', '{{ date('g:i A', strtotime($slot)) }}', '{{ $selectedDate }}')">
                                                <span class="slot-plus">+</span>
                                                <span>Allocate</span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    @empty
                        <div class="da-empty-state" style="padding:3rem 2rem;">
                            <i class="fa-solid fa-calendar-xmark"></i>
                            <p>No time slots configured for {{ $dayOfWeek }}. Please set up staff schedules first.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Legend --}}
                <div class="da-legend">
                    <div class="da-legend-items">
                        <div class="da-legend-item">
                            <span class="da-legend-box empty"></span>
                            Empty &mdash; click to allocate
                        </div>
                        <div class="da-legend-item">
                            <span class="da-legend-box filled"></span>
                            Allocated &mdash; locked
                        </div>
                        <div class="da-legend-item">
                            <span class="da-legend-box" style="background:#f0fdf4;border:1.5px solid #86efac;"></span>
                            Completed
                        </div>
                    </div>
                    <div class="da-slots-counter">
                        {{ $allocatedCount }} / {{ $totalSlots }} slots used
                    </div>
                </div>

            </div>
        @endif

    </div>

    {{-- Allocate Modal --}}
    <div class="da-modal-overlay" id="allocateModal">
        <div class="da-modal">
            <h5>
                Allocate doubt session
                <span class="close-modal" onclick="closeAllocateModal()">&times;</span>
            </h5>
            <div class="da-modal-subtitle" id="modalSubtitle">Staff Name &middot; 00:00 AM</div>

            <form id="allocateForm" onsubmit="submitAllocate(event)">
                @csrf
                <input type="hidden" name="ticket_id" id="modalTicketId">

                <div class="row">
                    <div class="col-12">
                        <label>Student Name</label>
                        <div class="da-search-wrapper">
                            <input type="text" id="ticketSearch" placeholder="Search by name, phone or ticket ref…"
                                autocomplete="off" oninput="filterTickets(this.value)">
                            <div class="da-suggestions" id="ticketSuggestions" style="display:none;"></div>
                        </div>
                    </div>
                </div>

                <div style="display:flex; gap:1rem; margin-bottom:0;">
                    <div style="flex:1;">
                        <label>Date</label>
                        <select name="allocated_date" id="modalAllocatedDate">
                            @foreach($dates as $dateItem)
                                <option value="{{ $dateItem['full'] }}">
                                    {{ \Carbon\Carbon::parse($dateItem['full'])->format('d M Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="flex:1;">
                        <label>Time Slot</label>
                        <select name="allocated_slot" id="modalAllocatedSlot">
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ date('g:i A', strtotime($slot)) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label>Assign to Staff</label>
                        <select name="staff_id" id="modalStaffId">
                            @foreach($staffList as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label>Notes <span
                                style="font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></label>
                        <textarea name="notes" id="modalNotes" rows="3"
                            placeholder="Doubt subject, prep notes, any context…"></textarea>
                    </div>
                </div>

                <div class="da-modal-footer">
                    <button type="button" class="btn-da-cancel" onclick="closeAllocateModal()">Cancel</button>
                    <button type="submit" class="btn-da-confirm" id="allocateSubmitBtn" disabled>
                        <i class="fa-solid fa-check"></i> Allocate slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Confirm Modal --}}
    <div class="da-modal-overlay" id="confirmModal">
        <div class="da-modal" style="max-width: 400px; text-align: center; padding: 2rem;">
            <div style="font-size: 3rem; color: #ef4444; margin-bottom: 1rem;">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <h5 style="margin-bottom: 0.5rem; font-size: 1.25rem;">Remove Allocation?</h5>
            <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.5;">
                Are you sure you want to remove this schedule? This will also cancel the generated Zoom meeting.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button type="button" class="btn-da-cancel" style="margin:0; width: 120px;" onclick="closeConfirmModal()">Cancel</button>
                <button type="button" class="btn-da-confirm" id="confirmActionBtn" style="margin:0; width: 140px; background: #ef4444; border-color: #ef4444;">
                    Yes, Remove
                </button>
            </div>
        </div>
    </div>

    @php
        $ticketsJson = $pendingTickets->map(function ($t) {
            return [
                'id' => $t->id,
                'name' => $t->user->name ?? 'Unknown',
                'phone' => $t->user->phonenumber ?? '',
                'title' => $t->title ?? $t->subject ?? '—',
                'ref' => $t->reference_id,
                'status' => $t->status,
            ];
        })->values();
    @endphp

    @push('script')
        <script>
            const pendingTickets = @json($ticketsJson);

            function openAllocateModal(staffId, staffName, slot, slotLabel, date) {
                document.getElementById('modalStaffId').value = staffId;
                document.getElementById('modalAllocatedDate').value = date;
                document.getElementById('modalAllocatedSlot').value = slot;
                document.getElementById('modalSubtitle').textContent = `${staffName} \u00B7 ${slotLabel}`;

                document.getElementById('modalTicketId').value = '';
                document.getElementById('ticketSearch').value = '';
                document.getElementById('modalNotes').value = '';
                document.getElementById('allocateSubmitBtn').disabled = true;
                document.getElementById('ticketSuggestions').style.display = 'none';

                const urlParams = new URLSearchParams(window.location.search);
                const urlTicketId = urlParams.get('ticket_id');
                if (urlTicketId) {
                    const ticket = pendingTickets.find(t => t.id == urlTicketId);
                    if (ticket) selectTicket(ticket.id, ticket.name, ticket.title);
                }

                document.getElementById('allocateModal').classList.add('show');
            }

            function closeAllocateModal() {
                document.getElementById('allocateModal').classList.remove('show');
            }

            function filterTickets(query) {
                const box = document.getElementById('ticketSuggestions');
                if (!query.trim()) { box.style.display = 'none'; return; }
                const q = query.toLowerCase();
                const matches = pendingTickets.filter(t =>
                    t.name.toLowerCase().includes(q) ||
                    t.phone.toLowerCase().includes(q) ||
                    t.title.toLowerCase().includes(q) ||
                    t.ref.toLowerCase().includes(q)
                ).slice(0, 8);

                if (matches.length === 0) { box.style.display = 'none'; return; }
                box.innerHTML = matches.map(t => `
                    <div class="da-suggestion-item" onclick="selectTicket(${t.id}, '${t.name.replace(/'/g, "\\'")}', '${t.title.replace(/'/g, "\\'")}')">
                        <strong>${t.name}</strong>
                        <span class="sug-phone">${t.phone}</span>
                        <div style="font-size:0.68rem; color:#64748b; margin-top:2px;">${t.title} &middot; <em>${t.ref}</em></div>
                    </div>
                `).join('');
                box.style.display = 'block';
            }

            function selectTicket(id, name, title) {
                document.getElementById('modalTicketId').value = id;
                document.getElementById('ticketSearch').value = name + ' — ' + title;
                document.getElementById('ticketSuggestions').style.display = 'none';
                document.getElementById('allocateSubmitBtn').disabled = false;
            }

            function submitAllocate(e) {
                e.preventDefault();
                const form = document.getElementById('allocateForm');
                const data = new FormData(form);
                const btn = document.getElementById('allocateSubmitBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving…';

                fetch('{{ route("doubt-allocation.allocate") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: data
                })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status) {
                            closeAllocateModal();
                            location.reload();
                        } else {
                            alert(res.message || 'Allocation failed');
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fa-solid fa-check"></i> Allocate slot';
                        }
                    })
                    .catch(() => {
                        alert('Network error. Please try again.');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa-solid fa-check"></i> Allocate slot';
                    });
            }

            function completeSlot(ticketId, staffId, date, slot) {
                if (!confirm('Mark this session as completed?')) return;
                fetch('{{ route("doubt-allocation.complete") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ ticket_id: ticketId, staff_id: staffId, allocated_date: date, allocated_slot: slot })
                })
                    .then(r => r.json())
                    .then(res => { if (res.status) location.reload(); else alert(res.message || 'Action failed'); })
                    .catch(() => alert('Network error. Please try again.'));
            }

            let confirmActionCallback = null;

            function openConfirmModal(callback) {
                confirmActionCallback = callback;
                document.getElementById('confirmModal').classList.add('show');
            }

            function closeConfirmModal() {
                document.getElementById('confirmModal').classList.remove('show');
                confirmActionCallback = null;
            }

            document.getElementById('confirmActionBtn').addEventListener('click', function() {
                if (confirmActionCallback) {
                    const btn = this;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Removing...';
                    confirmActionCallback();
                }
            });

            function deallocateSlot(ticketId, staffId, date, slot) {
                openConfirmModal(() => {
                    fetch('{{ route("doubt-allocation.deallocate") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'Content-Type': 'application/json' },
                        body: JSON.stringify({ ticket_id: ticketId, staff_id: staffId, allocated_date: date, allocated_slot: slot })
                    })
                        .then(r => r.json())
                        .then(res => { 
                            if (res.status) {
                                location.reload();
                            } else {
                                alert(res.message || 'Deallocation failed');
                                closeConfirmModal();
                                const btn = document.getElementById('confirmActionBtn');
                                btn.disabled = false;
                                btn.innerHTML = 'Yes, Remove';
                            }
                        })
                        .catch(() => {
                            alert('Network error. Please try again.');
                            closeConfirmModal();
                            const btn = document.getElementById('confirmActionBtn');
                            btn.disabled = false;
                            btn.innerHTML = 'Yes, Remove';
                        });
                });
            }

            document.getElementById('allocateModal').addEventListener('click', function (e) {
                if (e.target === this) closeAllocateModal();
            });

            document.getElementById('confirmModal').addEventListener('click', function (e) {
                if (e.target === this) closeConfirmModal();
            });

            document.addEventListener('click', function (e) {
                if (!e.target.closest('#ticketSearch, #ticketSuggestions')) {
                    document.getElementById('ticketSuggestions').style.display = 'none';
                }
            });
        </script>
    @endpush
@endsection