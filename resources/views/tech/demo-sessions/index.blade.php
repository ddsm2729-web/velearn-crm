@extends('layouts.master')

@section('pageTitle', 'Demo Sessions')



@section('content')
    <div class="session-wrapper">
        <!-- Top Nav Tabs -->
        <div class="top-nav-tabs">
            <div class="top-nav-tab active" data-target="#demo-slots-view" onclick="switchTab(this)">
                <i class="fas fa-calendar-alt"></i> Demo Slots
            </div>
            <div class="top-nav-tab" data-target="#assigned-demos-view" onclick="switchTab(this)">
                <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> Assigned Demos
            </div>
            <div class="top-nav-tab" data-target="#completed-demos-view" onclick="switchTab(this)">
                <i class="fas fa-check-circle" style="color: var(--success-green);"></i> Completed Demos
            </div>
            <div class="top-nav-tab" data-target="#missed-demos-view" onclick="switchTab(this)">
                <i class="fas fa-times-circle" style="color: #f59e0b;"></i> Demo Missed
            </div>
            <div class="top-nav-tab" data-target="#lost-demos-view" onclick="switchTab(this)">
                <i class="fas fa-ban" style="color: var(--danger-red);"></i> Demo lost
            </div>
        </div>

        <div id="demo-slots-view" class="tab-view" style="display: block;">
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
                            <h3>My Demo Slots</h3>
                            <p>{{ $bookedSlotsCount }}/{{ $totalSlots }} slots booked</p>
                        </div>
                        <div class="badge-available">
                            {{ $availableSlotsCount }} Available
                        </div>
                    </div>

                    <div class="date-tabs">
                        @foreach($dateTabs as $index => $date)
                            <a href="{{ route('tech.demo-sessions.index', ['date' => $date->format('Y-m-d')]) }}"
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
                        <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> All Booked Demo Slots
                    </div>

                    @foreach($bookedSlots as $booked)
                        <div class="booked-item-card">
                            <div class="booked-item-name">{{ $booked['name'] }}</div>
                            @if(!empty($booked['phone']))
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 2px;">
                                    <i class="fas fa-mobile-alt me-1"></i>{{ $booked['phone'] }}
                                </div>
                            @endif
                            <div class="booked-item-course">{{ $booked['course'] }}</div>
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

        <!-- Assigned Demos View -->
        <div id="assigned-demos-view" class="tab-view" style="display: none;">
            @include('tech.demo-sessions.demo-list', [
                'demos' => $assignedDemos,
                'idPrefix' => 'assigned',
                'title' => 'Assigned Demos',
                'icon' => '<i class="fas fa-clipboard-list" style="color: #f59e0b;"></i>',
                'badgeColor' => 'primary',
                'statusText' => 'Demo Booked',
                'showActions' => true
            ])
        </div>

        <!-- Completed Demos View -->
        <div id="completed-demos-view" class="tab-view" style="display: none;">
            @include('tech.demo-sessions.demo-list', [
                'demos' => $completedDemos,
                'idPrefix' => 'completed',
                'title' => 'Completed Demos',
                'icon' => '<i class="fas fa-check-circle" style="color: var(--success-green);"></i>',
                'badgeColor' => 'success',
                'statusText' => 'Demo Completed'
            ])
        </div>

        <!-- Missed Demos View -->
        <div id="missed-demos-view" class="tab-view" style="display: none;">
            @include('tech.demo-sessions.demo-list', [
                'demos' => $missedDemos,
                'idPrefix' => 'missed',
                'title' => 'Demo Missed',
                'icon' => '<i class="fas fa-times-circle" style="color: #f59e0b;"></i>',
                'badgeColor' => 'warning',
                'statusText' => 'Demo Missed'
            ])
        </div>

        <!-- Lost Demos View -->
        <div id="lost-demos-view" class="tab-view" style="display: none;">
            @include('tech.demo-sessions.demo-list', [
                'demos' => $lostDemos,
                'idPrefix' => 'lost',
                'title' => 'Demo lost',
                'icon' => '<i class="fas fa-ban" style="color: var(--danger-red);"></i>',
                'badgeColor' => 'danger',
                'statusText' => 'Demo lost'
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
                            Book Demo Slot</h5>
                        <p class="text-muted" style="font-size: 0.85rem; margin-bottom: 0;" id="modalDisplayDate"></p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tech.demo-sessions.book') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="demo">
                    <input type="hidden" name="date" id="modalInputDate">
                    <input type="hidden" name="time" id="modalInputTime">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Time
                                Slot</label>
                            <input type="text" class="form-control" id="modalDisplayTimeSlot" readonly
                                style="background: #f8fafc;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label"
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Student
                                / Lead *</label>
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
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Notes</label>
                            <textarea class="form-control" name="notes" rows="3"
                                placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: none; padding-top: 0;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="flex: 1; border-radius: 8px; font-weight: 600;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="flex: 1; border-radius: 8px; font-weight: 600; background: var(--brand-blue); border-color: var(--brand-blue);">Confirm
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
            document.querySelectorAll('.top-nav-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            element.classList.add('active');

            var target = element.getAttribute('data-target');
            document.querySelectorAll('.tab-view').forEach(view => {
                view.style.display = 'none';
            });
            document.querySelector(target).style.display = 'block';

            // Persist active tab in localStorage
            localStorage.setItem('demoActiveTab', target);
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
            var savedTab = localStorage.getItem('demoActiveTab');
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

            // --- Search Input Logic ---
            document.querySelectorAll('.demo-search-input').forEach(input => {
                input.addEventListener('input', function() {
                    filterList(this.getAttribute('data-target'));
                });
            });

            // --- Date Filter Logic ---
            document.querySelectorAll('.demo-date-filter').forEach(input => {
                input.addEventListener('change', function() {
                    filterList(this.getAttribute('data-target'));
                });
            });

            // --- Today / All Button Logic ---
            document.querySelectorAll('.demo-filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let target = this.getAttribute('data-target');
                    let filterType = this.getAttribute('data-filter');

                    document.querySelectorAll(`.demo-filter-btn[data-target="${target}"]`).forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    let dateInput = document.querySelector(`.demo-date-filter[data-target="${target}"]`);
                    if (filterType === 'today') {
                        dateInput.value = new Date().toISOString().split('T')[0];
                    } else {
                        dateInput.value = '';
                    }

                    filterList(target);
                });
            });

            function filterList(prefix) {
                let searchVal = document.querySelector(`.demo-search-input[data-target="${prefix}"]`).value.toLowerCase();
                let dateVal = document.querySelector(`.demo-date-filter[data-target="${prefix}"]`).value;
                let activeBtn = document.querySelector(`.demo-filter-btn.active[data-target="${prefix}"]`);
                let isToday = activeBtn && activeBtn.getAttribute('data-filter') === 'today';

                let listWrapper = document.getElementById(`${prefix}-list`);
                if (!listWrapper) return;

                let items = listWrapper.querySelectorAll('.demo-card-item');
                let todayStr = new Date().toISOString().split('T')[0];

                items.forEach(item => {
                    let name = item.getAttribute('data-name') || '';
                    let course = item.getAttribute('data-course') || '';
                    let date = item.getAttribute('data-date') || '';

                    let matchSearch = (name.includes(searchVal) || course.includes(searchVal));
                    let matchDate = true;

                    if (dateVal) {
                        matchDate = (date === dateVal);
                    } else if (isToday) {
                        matchDate = (date === todayStr);
                    }

                    item.style.display = (matchSearch && matchDate) ? 'block' : 'none';
                });
            }
        });
    </script>
@endpush