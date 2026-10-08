@extends('layouts.master')

@section('pageTitle', 'Doubt Sessions')

@section('content')
    <div class="session-wrapper">
        <!-- Top Nav Tabs -->
        <div class="top-nav-tabs">
            <div class="top-nav-tab active" data-target="#doubt-slots-view" onclick="switchTab(this)">
                <i class="fas fa-calendar-alt"></i> Doubt Slots
            </div>
            <div class="top-nav-tab" data-target="#assigned-doubts-view" onclick="switchTab(this)">
                <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> Assigned Doubts
            </div>
            <div class="top-nav-tab" data-target="#completed-doubts-view" onclick="switchTab(this)">
                <i class="fas fa-check-circle" style="color: var(--success-green);"></i> Completed Doubts
            </div>
            <div class="top-nav-tab" data-target="#missed-doubts-view" onclick="switchTab(this)">
                <i class="fas fa-times-circle" style="color: #f59e0b;"></i> Doubt Missed
            </div>
        </div>

        <!-- Doubt Slots View -->
        <div id="doubt-slots-view" class="tab-view" style="display: block;">
            <div class="row g-4">
            <!-- Left Column: Slots Grid -->
            <div class="col-lg-8">
                <div class="slots-container">
                    <div class="slots-header">
                        @php
                            $totalSlots = count($slots);
                            $bookedSlotsCount = collect($slots)->where('status', 'booked')->count();
                            $availableSlotsCount = collect($slots)->where('status', 'available')->count();
                        @endphp
                        <div class="slots-title">
                            <h3>My Doubt Slots</h3>
                            <p>{{ $bookedSlotsCount }}/{{ $totalSlots }} slots booked</p>
                        </div>
                        <div class="badge-available">
                            {{ $availableSlotsCount }} Available
                        </div>
                    </div>

                    <div class="date-tabs">
                        @foreach($dateTabs as $index => $date)
                            <a href="{{ route('tech.doubt-sessions.index', ['date' => $date->format('Y-m-d')]) }}"
                                style="text-decoration: none;">
                                <div class="date-tab {{ $targetDate == $date->format('Y-m-d') ? 'active' : '' }}">
                                    {{ $date->format('d M Y') }}
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="row g-3">
                        @foreach($slots as $slot)
                            <div class="col-12 col-sm-6">
                                <div class="slot-card {{ $slot['status'] === 'booked' ? 'booked' : '' }} {{ $slot['is_passed'] ? 'opacity-50 bg-light' : '' }}"
                                     style="{{ $slot['is_passed'] ? 'pointer-events: none;' : '' }}">
                                    <div class="slot-num">SLOT {{ $slot['id'] }}</div>
                                    <div class="slot-time">{{ $slot['time'] }}</div>

                                    @if($slot['status'] === 'available')
                                        <div class="slot-status-available" style="cursor: pointer;"
                                            onclick="openBookingModal('{{ $slot['time'] }}', '{{ $targetDate }}', '{{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}')">
                                            + Available</div>
                                    @elseif($slot['status'] === 'passed')
                                        <div class="slot-status-passed" style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 500; margin-top: 0.15rem;">
                                            Passed
                                        </div>
                                    @else
                                        <div class="slot-booked-name">{{ $slot['name'] }}</div>
                                        <div class="slot-booked-course">{{ $slot['course'] }}@if($slot['topic']) &bull; {{ $slot['topic'] }}@endif</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Column: Booked List -->
            <div class="col-lg-4">
                <div class="booked-list-container">
                    <div class="booked-list-header">
                        <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> All Booked Doubt Slots
                    </div>

                    @foreach($bookedSlots as $booked)
                        <div class="booked-item-card">
                            <div class="booked-item-name">{{ $booked['name'] }}</div>
                            @if(!empty($booked['phone']))
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">
                                    <i class="fas fa-mobile-alt me-1"></i>{{ $booked['phone'] }}
                                </div>
                            @endif
                            <div class="booked-item-course">{{ $booked['course'] }}@if($booked['topic']) &bull; {{ $booked['topic'] }}@endif</div>
                            <div class="booked-item-time">
                                <span>{{ $booked['date'] }}</span>
                                <span>{{ $booked['time'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>

        <!-- Assigned Doubts View -->
        <div id="assigned-doubts-view" class="tab-view" style="display: none;">
            @include('tech.doubt-sessions.doubt-list', [
                'doubts'      => $assignedDoubts,
                'idPrefix'    => 'assigned',
                'title'       => 'Assigned Doubts',
                'icon'        => '<i class="fas fa-clipboard-list" style="color: #f59e0b;"></i>',
                'badgeColor'  => 'primary',
                'statusText'  => 'Doubt Booked',
                'showActions' => true
            ])
        </div>

        <!-- Completed Doubts View -->
        <div id="completed-doubts-view" class="tab-view" style="display: none;">
            @include('tech.doubt-sessions.doubt-list', [
                'doubts'     => $completedDoubts,
                'idPrefix'   => 'completed',
                'title'      => 'Completed Doubts',
                'icon'       => '<i class="fas fa-check-circle" style="color: var(--success-green);"></i>',
                'badgeColor' => 'success',
                'statusText' => 'Doubt Resolved'
            ])
        </div>

        <!-- Missed Doubts View -->
        <div id="missed-doubts-view" class="tab-view" style="display: none;">
            @include('tech.doubt-sessions.doubt-list', [
                'doubts'     => $missedDoubts,
                'idPrefix'   => 'missed',
                'title'      => 'Doubt Missed',
                'icon'       => '<i class="fas fa-times-circle" style="color: #f59e0b;"></i>',
                'badgeColor' => 'warning',
                'statusText' => 'Doubt Missed'
            ])
        </div>

    </div>

    <!-- Booking Modal -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border: none; padding: 1rem;">
                <div class="modal-header" style="border-bottom: none; padding-bottom: 0;">
                    <div>
                        <h5 class="modal-title" style="font-weight: 700; font-family: 'Lora', serif; font-size: 1.25rem;">
                            Book Doubt Slot</h5>
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0;" id="modalDisplayDate"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tech.doubt-sessions.book') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="doubt">
                    <input type="hidden" name="date" id="modalInputDate">
                    <input type="hidden" name="time" id="modalInputTime">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Time Slot</label>
                            <input type="text" class="form-control" id="modalDisplayTimeSlot" readonly
                                style="background: #f8fafc;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Student *</label>
                            <select class="form-select" name="admission_id" required>
                                <option value="">— Select student —</option>
                                @foreach($admissions as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->contact->name ?? 'Unknown' }}
                                        ({{ $admin->course->title ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Doubt Topic</label>
                            <input type="text" class="form-control" name="topic"
                                placeholder="e.g. React Hooks, CSS Grid...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Notes</label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none; padding-top: 0;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="flex: 1; border-radius: 8px; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="flex: 1; border-radius: 8px; font-weight: 600; background: var(--brand-purple); border-color: var(--brand-purple);">Confirm
                            Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function switchTab(element) {
            document.querySelectorAll('.top-nav-tab').forEach(tab => tab.classList.remove('active'));
            element.classList.add('active');

            var target = element.getAttribute('data-target');
            document.querySelectorAll('.tab-view').forEach(view => view.style.display = 'none');
            document.querySelector(target).style.display = 'block';

            localStorage.setItem('doubtActiveTab', target);
        }

        function openBookingModal(time, dateValue, dateDisplay) {
            document.getElementById('modalInputTime').value = time;
            document.getElementById('modalInputDate').value = dateValue;
            document.getElementById('modalDisplayDate').innerText = dateDisplay + ' • ' + time;
            document.getElementById('modalDisplayTimeSlot').value = dateDisplay + ' • ' + time;

            var modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {

            // --- Restore last active tab ---
            var savedTab = localStorage.getItem('doubtActiveTab');
            if (savedTab) {
                var tabEl = document.querySelector(`.top-nav-tab[data-target="${savedTab}"]`);
                var viewEl = document.querySelector(savedTab);
                if (tabEl && viewEl) {
                    document.querySelectorAll('.top-nav-tab').forEach(t => t.classList.remove('active'));
                    document.querySelectorAll('.tab-view').forEach(v => v.style.display = 'none');
                    tabEl.classList.add('active');
                    viewEl.style.display = 'block';
                }
            }

            // --- Search Input ---
            document.querySelectorAll('.doubt-search-input').forEach(input => {
                input.addEventListener('input', function() { filterList(this.dataset.target); });
            });

            // --- Date Filter ---
            document.querySelectorAll('.doubt-date-filter').forEach(input => {
                input.addEventListener('change', function() { filterList(this.dataset.target); });
            });

            // --- Today / All Buttons ---
            document.querySelectorAll('.doubt-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let target = this.dataset.target;
                    let filterType = this.dataset.filter;

                    document.querySelectorAll(`.doubt-filter-btn[data-target="${target}"]`).forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    let dateInput = document.querySelector(`.doubt-date-filter[data-target="${target}"]`);
                    dateInput.value = (filterType === 'today') ? new Date().toISOString().split('T')[0] : '';
                    filterList(target);
                });
            });

            function filterList(prefix) {
                let searchVal = document.querySelector(`.doubt-search-input[data-target="${prefix}"]`).value.toLowerCase();
                let dateVal   = document.querySelector(`.doubt-date-filter[data-target="${prefix}"]`).value;
                let activeBtn = document.querySelector(`.doubt-filter-btn.active[data-target="${prefix}"]`);
                let isToday   = activeBtn && activeBtn.dataset.filter === 'today';
                let todayStr  = new Date().toISOString().split('T')[0];

                let listWrapper = document.getElementById(`${prefix}-doubt-list`);
                if (!listWrapper) return;

                listWrapper.querySelectorAll('.doubt-card-item').forEach(item => {
                    let name   = item.dataset.name || '';
                    let course = item.dataset.course || '';
                    let date   = item.dataset.date || '';

                    let matchSearch = name.includes(searchVal) || course.includes(searchVal);
                    let matchDate   = !dateVal ? (!isToday || date === todayStr) : (date === dateVal);

                    item.style.display = (matchSearch && matchDate) ? 'block' : 'none';
                });
            }
        });
    </script>
@endpush