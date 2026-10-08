@extends('layouts.master')

@section('pageTitle', 'Doubt Query Leads')

@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/doubt-query-leads.css') }}">
    @endpush

    <div class="dql-page">

        {{-- Header --}}
        <div class="dql-breadcrumb">SUPPORT CRM / LEADS</div>
        <div class="dql-header">
            <div class="dql-header-left">
                <h2>Leads <span>— Doubt &amp; query raised students</span></h2>
            </div>
            <div class="dql-header-right">
                <div class="dql-search-wrapper">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" class="dql-search" id="dqlSearch" placeholder="Search leads, students..."
                        oninput="filterTable()">
                </div>
                <div class="dql-bell">
                    <i class="fa-solid fa-bell"></i>
                    <span class="badge-dot"></span>
                </div>
                {{-- <button class="btn-add-lead" onclick="openAddLeadModal()">
                    <i class="fa-solid fa-plus"></i> Add Lead
                </button> --}}
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" style="margin: 15px 25px 0 25px; border-radius: 8px;">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Type Tabs --}}
        <div class="dql-type-tabs">
            <button class="type-tab tech {{ request('type', 'technical') === 'technical' ? 'active' : '' }}" id="tab-tech"
                onclick="switchType('technical')">
                <i class="fa-solid fa-gear"></i> Technical Queries
                <span class="cnt" id="cnt-tech">{{ $technicalCount }}</span>
            </button>
            <button class="type-tab nontech {{ request('type') === 'non-technical' ? 'active' : '' }}" id="tab-nontech"
                onclick="switchType('non-technical')">
                <i class="fa-solid fa-pen"></i> Non-Technical Queries
                <span class="cnt" id="cnt-nontech">{{ $nonTechnicalCount }}</span>
            </button>
        </div>

        {{-- Status Tabs --}}
        <div class="dql-status-tabs" id="statusTabs">
            @php
                $statuses = ['New', 'Opened', 'Pending', 'In Review', 'Resolved'];
            @endphp
            @foreach ($statuses as $st)
                <button class="status-tab {{ request('status', 'New') === $st ? 'active' : '' }}" data-status="{{ $st }}"
                    onclick="switchStatus(this)">
                    {{ $st }}
                    <span class="scnt" id="scnt-{{ Str::slug($st) }}">
                        {{ $statusCounts[$st] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </div>

        {{-- Table Card --}}
        <div class="dql-card">
            <div class="table-responsive">

                {{-- TECHNICAL TABLE --}}
                <table class="dql-table" id="dqlTableTech"
                    style="{{ request('type', 'technical') === 'technical' ? '' : 'display: none;' }}">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>NUMBER</th>
                            <th>SUBJECT / DESCRIPTION</th>
                            <th>SLOT TIME</th>
                            <th>DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="dqlTableBodyTech">
                        @forelse($tickets->where('type', 'technical') as $ticket)
                            @php
                                $initials = collect(explode(' ', $ticket->user->name ?? 'U K'))
                                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                    ->take(2)
                                    ->join('');
                                $colors = ['#1f6feb', '#238636', '#8957e5', '#f0883e', '#da3633'];
                                $color = $colors[crc32($ticket->user_id ?? 0) % 5];
                            @endphp
                            <tr data-url="{{ $ticket->user?->phonenumber ? route('support.calls.profile', $ticket->user->phonenumber) : '' }}"
                                style="cursor: pointer;" class="clickable-row" data-status="{{ $ticket->status }}"
                                data-search="{{ strtolower(($ticket->user->name ?? '') . ' ' . ($ticket->title ?? ($ticket->subject ?? '')) . ' ' . ($ticket->user->student_id ?? '')) }}">
                                <td>
                                    <div class="dql-name-cell">
                                        <div class="dql-avatar" style="background: {{ $color }}; color: #fff;">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="dql-name">{{ $ticket->user->name ?? 'Unknown' }}</div>
                                            <div class="dql-id">{{ $ticket->reference_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ticket->user->phonenumber ?? '+91 — — — — — —' }}</td>
                                <td>
                                    {{ \Illuminate\Support\Str::limit($ticket->title ?? $ticket->subject, 50) }}
                                </td>
                                <td>
                                    @if ($ticket->preferred_slot)
                                        <div style="display:flex; align-items:center; gap:0.5rem;">
                                            <a href="#" class="view-link" data-id="{{ $ticket->id }}"
                                                data-title="{{ $ticket->title ?? $ticket->subject }}"
                                                data-description="{{ $ticket->description }}"
                                                data-slot="{{ $ticket->preferred_slot }}" data-date="{{ $ticket->preferred_date }}"
                                                data-status="{{ $ticket->status }}" data-reference="{{ $ticket->reference_id }}"
                                                onclick="openDetailModal(this); return false;">View</a>
                                            <span class="slot-time">
                                                {{ $ticket->preferred_slot }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($ticket->created_at)->format('d M') }}</td>
                                <td>
                                    <div class="dql-action-btns">
                                        <button class="btn-edit-status"
                                            onclick="event.stopPropagation(); openStatusEdit({{ $ticket->id }}, '{{ $ticket->status }}')">
                                            <i class="fa-solid fa-pen"></i> Edit
                                        </button>
                                        <button class="btn-schedule"
                                            onclick="event.stopPropagation(); scheduleDoubt({{ $ticket->id }})">
                                            <i class="fa-regular fa-clock"></i> Schedule
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="emptyRow">
                                <td colspan="6">
                                    <div class="dql-empty">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No technical queries found for this filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- NON-TECHNICAL TABLE --}}
                <table class="dql-table" id="dqlTableNonTech"
                    style="{{ request('type') === 'non-technical' ? '' : 'display: none;' }}">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th>NUMBER</th>
                            <th>CATEGORY</th>
                            <th>DESCRIPTION</th>
                            <th>ATTACHMENT</th>
                        </tr>
                    </thead>
                    <tbody id="dqlTableBodyNonTech">
                        @forelse($tickets->where('type', 'non-technical') as $ticket)
                            @php
                                $initials = collect(explode(' ', $ticket->user->name ?? 'U K'))
                                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                                    ->take(2)
                                    ->join('');
                                $colors = ['#1f6feb', '#238636', '#8957e5', '#f0883e', '#da3633'];
                                $color = $colors[crc32($ticket->user_id ?? 0) % 5];
                            @endphp
                            <tr data-url="{{ $ticket->user?->phonenumber ? route('support.calls.profile', $ticket->user->phonenumber) : '' }}"
                                style="cursor: pointer;" class="clickable-row" data-status="{{ $ticket->status }}"
                                data-search="{{ strtolower(($ticket->user->name ?? '') . ' ' . ($ticket->category ?? '') . ' ' . ($ticket->user->student_id ?? '')) }}">
                                <td>
                                    <div class="dql-name-cell">
                                        <div class="dql-avatar" style="background: {{ $color }}; color: #fff;">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="dql-name">{{ $ticket->user->name ?? 'Unknown' }}</div>
                                            <div class="dql-id">{{ $ticket->reference_id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $ticket->user->phonenumber ?? '+91 — — — — — —' }}</td>
                                <td>
                                    <span class="dql-cat-badge">{{ $ticket->category ?? 'General' }}</span>
                                </td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <span>{{ \Illuminate\Support\Str::limit($ticket->description ?? '', 45) }}</span>
                                        <a href="#" class="view-link"
                                            onclick="viewDetails({{ $ticket->id }}); return false;">Read</a>
                                    </div>
                                </td>
                                <td>
                                    @if ($ticket->attachment)
                                        <a href="{{ url('uploads/support_attachments/' . $ticket->attachment) }}" target="_blank"
                                            class="btn-view-att">
                                            <i class="fa-solid fa-paperclip"></i>
                                            {{ \Illuminate\Support\Str::limit($ticket->attachment, 15) }}
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size:0.8rem;">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr class="emptyRow">
                                <td colspan="5">
                                    <div class="dql-empty">
                                        <i class="fa-solid fa-inbox"></i>
                                        <p>No non-technical queries found for this filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $tickets->links() }}
        </div>
    </div>

    {{-- Detail Modal (Technical) --}}
    <div class="dql-modal-overlay" id="detailModal">
        <div class="dql-modal dql-modal-lg">
            <div class="dql-modal-header">
                <h5><i class="fa-solid fa-comment-dots me-2"></i>Technical Query Details</h5>
                <button type="button" class="btn-close-icon" onclick="closeModal('detailModal')"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="dql-modal-body">
                <div class="query-info-grid">
                    <div class="info-group">
                        <label>Reference</label>
                        <p id="detailReference" class="fw-bold"></p>
                    </div>
                    <div class="info-group">
                        <label>Status</label>
                        <p><span id="detailStatus" class="dql-status"></span></p>
                    </div>
                    <div class="info-group full-width">
                        <label>Title</label>
                        <p id="detailTitle" class="fw-bold"></p>
                    </div>
                    <div class="info-group full-width">
                        <label>Description</label>
                        <p id="detailDescription" class="text-muted"></p>
                    </div>
                    <div class="info-group">
                        <label>Preferred Date</label>
                        <p id="detailDate"></p>
                    </div>
                    <div class="info-group">
                        <label>Slot</label>
                        <p id="detailSlot"></p>
                    </div>
                </div>
                {{-- <div class="conversation-section" id="detailConversation">
                    <h6>Conversation</h6>
                    <div id="conversationMessages" class="chat-container"></div>
                    <div class="chat-input-wrapper">
                        <textarea id="newMessage" placeholder="Write a reply..."></textarea>
                        <button class="btn-send" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    {{-- Detail Modal (Non-Technical) --}}
    <div class="dql-modal-overlay" id="nonTechDetailModal">
        <div class="dql-modal">
            <div class="dql-modal-header">
                <h5><i class="fa-solid fa-clipboard-list me-2"></i>Query Details</h5>
                <button type="button" class="btn-close-icon" onclick="closeModal('nonTechDetailModal')"><i
                        class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="dql-modal-body" id="nonTechDetailBody">
                <!-- Populated via JS -->
            </div>
        </div>
    </div>

    {{-- Add Lead Modal --}}
    <div class="dql-modal-overlay" id="addLeadModal">
        <div class="dql-modal">
            <h5><i class="fa-solid fa-user-plus me-2"></i>Add Manual Lead</h5>
            <form id="addLeadForm" onsubmit="submitAddLead(event)">
                @csrf
                <label>Student Name</label>
                <input type="text" name="name" placeholder="e.g. Arjun Karthikeyan" required>
                <label>Phone Number</label>
                <input type="text" name="phone" placeholder="+91 98400 12345" required>
                <label>Type</label>
                <select name="type">
                    <option value="technical">Technical Query</option>
                    <option value="non-technical">Non-Technical Query</option>
                </select>
                <label>Subject / Title</label>
                <input type="text" name="title" placeholder="e.g. Thermodynamics — entropy in irreversible process"
                    required>
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Describe the query..."></textarea>
                <div class="dql-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('addLeadModal')">Cancel</button>
                    <button type="submit" class="btn-modal-save">Add Lead</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Schedule Modal --}}
    <div class="dql-modal-overlay" id="scheduleModal">
        <div class="dql-modal">
            <h5><i class="fa-solid fa-calendar-check me-2"></i>Schedule Doubt Session</h5>
            <form id="scheduleForm" onsubmit="submitSchedule(event)">
                @csrf
                <input type="hidden" name="ticket_id" id="scheduleTicketId">
                <label>Preferred Date</label>
                <input type="date" name="date" id="scheduleDate" required min="{{ date('Y-m-d') }}">
                <label>Time Slot</label>
                <select name="slot" id="scheduleSlot">
                    <option value="9:00 AM">🌅 Morning Slot (9:00 AM)</option>
                    <option value="10:30 AM">🌅 Morning Slot (10:30 AM)</option>
                    <option value="6:00 PM">🌆 Evening Slot (6:00 PM)</option>
                    <option value="7:30 PM">🌆 Evening Slot (7:30 PM)</option>
                </select>
                <label>Status</label>
                <select name="status" id="scheduleStatus">
                    <option value="Opened">Opened</option>
                    <option value="Pending">Pending</option>
                    <option value="In Review">In Review</option>
                    <option value="Resolved">Resolved</option>
                </select>
                <div class="dql-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('scheduleModal')">Cancel</button>
                    <button type="submit" class="btn-modal-save"><i class="fa-solid fa-check"></i> Confirm</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Status Modal --}}
    <div class="dql-modal-overlay" id="editStatusModal">
        <div class="dql-modal">
            <h5><i class="fa-solid fa-tags me-2"></i>Update Ticket Status</h5>
            <form id="editStatusForm" onsubmit="submitStatusUpdate(event)">
                @csrf
                <input type="hidden" name="ticket_id" id="editStatusTicketId">
                <label>SELECT STATUS</label>
                <div id="statusOptionsContainer">
                    <div class="dql-status-option" data-value="Opened" onclick="selectStatus(this)">
                        <span class="status-dot dot-opened"></span>
                        <span class="status-label">Opened</span>
                        <span class="status-desc">Ticket is being reviewed</span>
                    </div>
                    <div class="dql-status-option" data-value="Pending" onclick="selectStatus(this)">
                        <span class="status-dot dot-pending"></span>
                        <span class="status-label">Pending</span>
                        <span class="status-desc">Awaiting response</span>
                    </div>
                    <div class="dql-status-option" data-value="In Review" onclick="selectStatus(this)">
                        <span class="status-dot dot-in-review"></span>
                        <span class="status-label">In Review</span>
                        <span class="status-desc">Under detailed review</span>
                    </div>
                    <div class="dql-status-option" data-value="Resolved" onclick="selectStatus(this)">
                        <span class="status-dot dot-resolved"></span>
                        <span class="status-label">Resolved</span>
                        <span class="status-desc">Issue resolved</span>
                    </div>
                </div>
                <input type="hidden" name="status" id="editStatusValue">
                <div class="dql-modal-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('editStatusModal')">Cancel</button>
                    <button type="submit" class="btn-modal-save"><i class="fa-solid fa-check"></i> Update
                        Status</button>
                </div>
            </form>
        </div>
    </div>

    @push('script')
        <script>
            // Helper to open the detail modal and fill it with data from the clicked row
            function openDetailModal(el) {
                const ref = el.dataset.reference || '';
                const title = el.dataset.title || '';
                const desc = el.dataset.description || '';
                const date = el.dataset.date || '';
                const slot = el.dataset.slot || '';
                const status = el.dataset.status || '';
                const ticketId = el.dataset.id || '';

                document.getElementById('detailReference').textContent = ref;
                document.getElementById('detailTitle').textContent = title;
                document.getElementById('detailDescription').textContent = desc;
                document.getElementById('detailDate').textContent = date;
                document.getElementById('detailSlot').textContent = slot;
                const statusEl = document.getElementById('detailStatus');
                statusEl.textContent = status;
                statusEl.className = 'status_badge ' + status.toLowerCase().replace(' ', '_');
                document.getElementById('detailModal').classList.add('show');
                // Load conversation
                loadConversation(ticketId);
            }

            async function loadConversation(ticketId) {
                try {
                    const token = localStorage.getItem('token');
                    const resp = await axios.get(`${BASE_API_URL}support/conversation?ticket_id=${ticketId}`, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    if (resp.data.status) {
                        const messagesDiv = document.getElementById('conversationMessages');
                        messagesDiv.innerHTML = '';
                        resp.data.data.forEach(msg => {
                            const div = document.createElement('div');
                            div.className = 'chat-msg';
                            div.innerHTML = `<div class="chat-author">${msg.author}</div><div class="chat-text">${msg.message}</div>`;
                            messagesDiv.appendChild(div);
                        });
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    }
                } catch (e) {
                    console.error('Failed to load conversation', e);
                }
            }

            async function sendMessage() {
                const msg = document.getElementById('newMessage').value.trim();
                if (!msg) return;
                const ticketId = document.getElementById('detailReference').textContent.replace('TQ-', '')
                    .trim(); // just example
                try {
                    const token = localStorage.getItem('token');
                    const resp = await axios.post(`${BASE_API_URL}support/message`, {
                        ticket_id: ticketId,
                        message: msg
                    }, {
                        headers: {
                            Authorization: `Bearer ${token}`
                        }
                    });
                    if (resp.data.status) {
                        document.getElementById('newMessage').value = '';
                        loadConversation(ticketId);
                    }
                } catch (e) {
                    console.error('Send message failed', e);
                }
            }

            let currentType = '{{ request('type', 'technical') }}';
            let currentStatus = '{{ request('status', 'New') }}';

            // All tickets data for client-side filtering
            const allTickets = @json($allTicketsJson);

            function switchType(type) {
                window.location.href = `{{ route('doubt-query-leads.index') }}?type=${type}&status=${currentStatus}`;
            }

            function switchStatus(el) {
                const status = el.dataset.status;
                window.location.href =
                    `{{ route('doubt-query-leads.index') }}?type={{ request('type', 'technical') }}&status=${status}`;
            }

            function filterTable() {
                renderTable();
            }

            function renderTable() {
                const search = document.getElementById('dqlSearch').value.toLowerCase();
                const tbodyId = currentType === 'technical' ? 'dqlTableBodyTech' : 'dqlTableBodyNonTech';
                const tbody = document.getElementById(tbodyId);
                if (!tbody) return;

                const rows = tbody.querySelectorAll('tr[data-status]');
                let visible = 0;

                rows.forEach(row => {
                    const matchStatus = row.dataset.status === currentStatus;
                    const matchSearch = !search || row.dataset.search.includes(search);
                    const show = matchStatus && matchSearch;
                    row.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                // Show / hide empty row
                let emptyRow = tbody.querySelector('.emptyRow');
                if (emptyRow) {
                    emptyRow.style.display = visible === 0 ? '' : 'none';
                }
            }

            function openAddLeadModal() {
                document.getElementById('addLeadModal').classList.add('show');
            }

            function viewDetails(id) {
                const ticket = allTickets.find(t => t.id == id);
                if (!ticket) return;
                document.getElementById('nonTechDetailBody').innerHTML = `
                <div class="query-info-grid">
                    <div class="info-group">
                        <label>Reference</label>
                        <p class="fw-bold">${ticket.reference_id}</p>
                    </div>
                    <div class="info-group">
                        <label>Type</label>
                        <p>${ticket.type === 'technical' ? '⚙️ Technical' : '📋 Non-Technical'}</p>
                    </div>
                    <div class="info-group full-width">
                        <label>Title / Subject</label>
                        <p class="fw-bold">${ticket.title || ticket.subject || '—'}</p>
                    </div>
                    <div class="info-group full-width">
                        <label>Description</label>
                        <p class="text-muted">${ticket.description || '—'}</p>
                    </div>
                    <div class="info-group">
                        <label>Preferred Date</label>
                        <p>${ticket.preferred_date || '—'}</p>
                    </div>
                    <div class="info-group">
                        <label>Slot</label>
                        <p>${ticket.preferred_slot || '—'}</p>
                    </div>
                    <div class="info-group">
                        <label>Status</label>
                        <p><span class="dql-status ${(ticket.status || '').toLowerCase().replace(' ', '-')}">${ticket.status}</span></p>
                    </div>
                </div>`;
                document.getElementById('nonTechDetailModal').classList.add('show');
            }

            function scheduleDoubt(id) {
                window.location.href = `{{ route('doubt-allocation.index') }}?ticket_id=${id}`;
            }

            function closeModal(id) {
                document.getElementById(id).classList.remove('show');
            }

            function submitSchedule(e) {
                e.preventDefault();
                const form = document.getElementById('scheduleForm');
                const data = new FormData(form);
                fetch('{{ route('doubt-query-leads.schedule') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: data
                }).then(r => r.json()).then(res => {
                    if (res.status) {
                        closeModal('scheduleModal');
                        location.reload();
                    } else {
                        alert(res.message || 'Failed');
                    }
                }).catch(() => alert('Network error'));
            }

            function submitAddLead(e) {
                e.preventDefault();
                const form = document.getElementById('addLeadForm');
                const data = new FormData(form);
                fetch('{{ route('doubt-query-leads.manual-store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: data
                }).then(r => r.json()).then(res => {
                    if (res.status) {
                        closeModal('addLeadModal');
                        location.reload();
                    } else {
                        alert(res.message || 'Failed');
                    }
                }).catch(() => alert('Network error'));
            }

            // Close modals on overlay click
            document.querySelectorAll('.dql-modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function (e) {
                    if (e.target === this) this.classList.remove('show');
                });
            });

            // ---- Edit Status Modal Logic ----
            function openStatusEdit(ticketId, currentStatus) {
                document.getElementById('editStatusTicketId').value = ticketId;
                document.getElementById('editStatusValue').value = '';
                // Clear previous selection
                document.querySelectorAll('#statusOptionsContainer .dql-status-option').forEach(opt => {
                    opt.classList.remove('selected');
                    if (opt.dataset.value === currentStatus) {
                        opt.classList.add('selected');
                        document.getElementById('editStatusValue').value = currentStatus;
                    }
                });
                document.getElementById('editStatusModal').classList.add('show');
            }

            function selectStatus(el) {
                document.querySelectorAll('#statusOptionsContainer .dql-status-option').forEach(opt => opt.classList.remove(
                    'selected'));
                el.classList.add('selected');
                document.getElementById('editStatusValue').value = el.dataset.value;
            }

            function submitStatusUpdate(e) {
                e.preventDefault();
                const ticketId = document.getElementById('editStatusTicketId').value;
                const status = document.getElementById('editStatusValue').value;
                if (!status) {
                    alert('Please select a status');
                    return;
                }
                const data = new FormData();
                data.append('ticket_id', ticketId);
                data.append('status', status);
                fetch('{{ route('doubt-query-leads.update-status') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: data
                }).then(r => r.json()).then(res => {
                    if (res.status) {
                        closeModal('editStatusModal');
                        location.reload();
                    } else {
                        alert(res.message || 'Failed to update status');
                    }
                }).catch(() => alert('Network error'));
            }

            // Init render
            document.addEventListener('DOMContentLoaded', function () {
                renderTable();
            });

            // Handle row clicks via event delegation (Vanilla JS)
            document.addEventListener('click', function (e) {
                // Find if we clicked inside a clickable row
                const row = e.target.closest('.clickable-row');
                if (!row) return;

                // Ignore clicks on buttons, links, or avatars
                if (e.target.closest('button') || e.target.closest('a') || e.target.closest('.dql-avatar')) {
                    return;
                }

                const url = row.getAttribute('data-url') || row.dataset.url;
                if (url && url.trim() !== '') {
                    window.location.href = url;
                }
            });
        </script>
    @endpush
@endsection