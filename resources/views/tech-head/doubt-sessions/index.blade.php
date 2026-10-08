@extends('layouts.master')

@section('pageTitle', 'Technical Head Doubt Sessions')

@section('content')


    <div class="session-wrapper">
        <!-- Technical Staff Filter Dropdown -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: white;">
            <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1 text-dark" style="font-weight: 700; font-family: 'Lora', serif;">Staff Doubt Board</h4>
                    <p class="text-muted mb-0 small">Select a technical staff member to view and manage their doubt
                        sessions.</p>
                </div>
                <div style="min-width: 280px;">
                    <label class="form-label fw-bold text-secondary small text-uppercase mb-1">Technical Staff</label>
                    <select class="form-select" style="border-radius: 8px; font-weight: 500;"
                        onchange="filterByStaff(this.value)">
                        @if($techStaffMembers->isEmpty())
                            <option value="">No technical staff found</option>
                        @else
                            @foreach($techStaffMembers as $member)
                                <option value="{{ $member->id }}" {{ $selectedStaffId == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>

        <!-- Top Nav Tabs -->
        <div class="top-nav-tabs">
            <div class="top-nav-tab active" data-tab="slots">
                <i class="fas fa-calendar-alt"></i> Doubt Slots
            </div>
            <div class="top-nav-tab" data-tab="assigned">
                <i class="fas fa-clipboard-list" style="color: #f59e0b;"></i> Assigned Doubts
            </div>
            <div class="top-nav-tab" data-tab="completed">
                <i class="fas fa-check-circle" style="color: var(--success-green);"></i> Completed Doubts
            </div>
        </div>

        <!-- Tab 1: Slots Grid and Booked List -->
        <div id="tab-content-slots" class="tab-content-section">
            <div class="row g-4">
                <!-- Left Column: Slots Grid -->
                <div class="col-lg-8">
                    <div class="slots-container">
                        <div class="slots-header">
                            <div class="slots-title">
                                <h3>Doubt Slots</h3>
                                <p>3/10 slots booked</p>
                            </div>
                            <div class="badge-available">
                                7 Available
                            </div>
                        </div>

                        <div class="date-tabs">
                            @foreach($dateTabs as $index => $date)
                                <a href="{{ route('tech-head.doubt-sessions.index', ['date' => $date->format('Y-m-d'), 'staff_id' => $selectedStaffId]) }}"
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
                                    <div class="slot-card {{ $slot['status'] === 'booked' ? 'booked' : '' }}">
                                        <div class="slot-num">SLOT {{ $slot['id'] }}</div>
                                        <div class="slot-time">{{ $slot['time'] }}</div>

                                        @if($slot['status'] === 'available')
                                            <div class="slot-status-available" style="cursor: pointer;"
                                                onclick="openBookingModal('{{ $slot['time'] }}', '{{ $targetDate }}', '{{ \Carbon\Carbon::parse($targetDate)->format('d M Y') }}')">
                                                + Available</div>
                                        @else
                                            <div class="slot-booked-name">{{ $slot['name'] }}</div>
                                            <div class="slot-booked-course">{{ $slot['course'] }} &bull; {{ $slot['topic'] }}</div>
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
                            <i class="fas fa-clipboard-list"></i> All Booked Doubt Slots
                        </div>

                        @foreach($bookedSlots as $booked)
                            <div class="booked-item-card">
                                <div class="booked-item-name">{{ $booked['name'] }}</div>
                                <div class="booked-item-course">{{ $booked['course'] }} &bull; {{ $booked['topic'] }}</div>
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

        <!-- Tab 2: Assigned Doubts -->
        <div id="tab-content-assigned" class="tab-content-section d-none">
            <div class="slots-container mt-4">

                <!-- Search & Filters Header -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-0" style="font-weight: 700; font-family: 'Lora', serif; font-size: 1.5rem;">Assigned
                            Doubts</h3>
                        <span class="badge rounded-pill bg-primary px-3 py-1"
                            style="font-size: 0.85rem;">{{ $assignedDoubts->count() }}</span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="search-input-wrapper" style="width: 240px;">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control search-input" placeholder="Search name or course...">
                        </div>

                        <div class="date-input-wrapper" style="width: 160px;">
                            <input type="date" class="form-control date-input">
                        </div>

                        <button class="filter-btn btn-filter-today active">Today</button>
                        <button class="filter-btn btn-filter-all">All</button>
                    </div>
                </div>

                <!-- List Container -->
                <div class="session-list-wrapper">
                    @if($assignedDoubts->isEmpty())
                        <div class="text-center py-5 text-muted empty-state-message">
                            <i class="fas fa-calendar-times mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p class="mb-0">No assigned doubt sessions found.</p>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted empty-state-message d-none">
                            <i class="fas fa-search mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p class="mb-0">No matching doubt sessions found.</p>
                        </div>

                        @foreach($assignedDoubts as $index => $booking)
                            @php
                                $name = $booking->admission->contact->name ?? 'Unknown';
                                $words = explode(' ', $name);
                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                $avatarIndex = ($index % 5) + 1;
                            @endphp
                            <div class="session-row-item session-card-item" data-date="{{ $booking->date }}" data-name="{{ $name }}"
                                data-phone="{{ $booking->admission->contact->phone ?? '' }}"
                                data-course="{{ $booking->admission->course->title ?? 'N/A' }}"
                                data-topic="{{ $booking->topic ?? '' }}">

                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-avatar-{{ $avatarIndex }}">{{ $initials }}</div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $name }}</div>
                                        <div class="session-details-list">
                                            <div class="session-details-item">
                                                <i class="fas fa-phone-alt" style="color: #ec4899;"></i>
                                                <span>{{ $booking->admission->contact->phone ?? '—' }}</span>
                                            </div>
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <span>{{ $booking->admission->course->title ?? 'N/A' }}</span>
                                            </div>
                                            @if($booking->topic)
                                                <div class="session-details-item">
                                                    <span style="color: #94a3b8;">&bull;</span>
                                                    <span
                                                        class="badge bg-purple-subtle text-purple px-2 py-0.5 border border-purple-subtle"
                                                        style="font-size: 0.75rem; border-radius: 4px;">
                                                        {{ $booking->topic }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <i class="far fa-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <i class="far fa-clock"></i>
                                                <span>{{ $booking->time }}</span>
                                            </div>
                                            @if($booking->staff)
                                                <div class="session-details-item">
                                                    <span style="color: #94a3b8;">&bull;</span>
                                                    <i class="fas fa-user-tie" style="color: #10b981;"></i>
                                                    <span>{{ $booking->staff->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <span class="status-badge-outline">
                                        <i class="fas fa-comment-dots" style="font-size: 0.75rem;"></i> Doubt Booked
                                    </span>

                                    <a href="tel:{{ $booking->admission->contact->phone ?? '' }}" class="btn-connect">
                                        <i class="fas fa-phone-alt"></i> Connect
                                    </a>

                                    <form action="{{ route('sessions.complete', $booking->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-complete-action">
                                            <i class="fas fa-check"></i> Complete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <!-- Tab 3: Completed Doubts -->
        <div id="tab-content-completed" class="tab-content-section d-none">
            <div class="slots-container mt-4">

                <!-- Search & Filters Header -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <h3 class="mb-0" style="font-weight: 700; font-family: 'Lora', serif; font-size: 1.5rem;">Completed
                            Doubts</h3>
                        <span class="badge rounded-pill bg-success px-3 py-1"
                            style="font-size: 0.85rem;">{{ $completedDoubts->count() }}</span>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="search-input-wrapper" style="width: 240px;">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control search-input" placeholder="Search name or course...">
                        </div>

                        <div class="date-input-wrapper" style="width: 160px;">
                            <input type="date" class="form-control date-input">
                        </div>

                        <button class="filter-btn btn-filter-today active">Today</button>
                        <button class="filter-btn btn-filter-all">All</button>
                    </div>
                </div>

                <!-- List Container -->
                <div class="session-list-wrapper">
                    @if($completedDoubts->isEmpty())
                        <div class="text-center py-5 text-muted empty-state-message">
                            <i class="fas fa-check-double mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p class="mb-0">No completed doubt sessions found.</p>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted empty-state-message d-none">
                            <i class="fas fa-search mb-3" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <p class="mb-0">No matching doubt sessions found.</p>
                        </div>

                        @foreach($completedDoubts as $index => $booking)
                            @php
                                $name = $booking->admission->contact->name ?? 'Unknown';
                                $words = explode(' ', $name);
                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                $avatarIndex = ($index % 5) + 1;
                            @endphp
                            <div class="session-row-item session-card-item" data-date="{{ $booking->date }}" data-name="{{ $name }}"
                                data-phone="{{ $booking->admission->contact->phone ?? '' }}"
                                data-course="{{ $booking->admission->course->title ?? 'N/A' }}"
                                data-topic="{{ $booking->topic ?? '' }}">

                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-circle bg-avatar-{{ $avatarIndex }}">{{ $initials }}</div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $name }}</div>
                                        <div class="session-details-list">
                                            <div class="session-details-item">
                                                <i class="fas fa-phone-alt" style="color: #ec4899;"></i>
                                                <span>{{ $booking->admission->contact->phone ?? '—' }}</span>
                                            </div>
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <span>{{ $booking->admission->course->title ?? 'N/A' }}</span>
                                            </div>
                                            @if($booking->topic)
                                                <div class="session-details-item">
                                                    <span style="color: #94a3b8;">&bull;</span>
                                                    <span
                                                        class="badge bg-purple-subtle text-purple px-2 py-0.5 border border-purple-subtle"
                                                        style="font-size: 0.75rem; border-radius: 4px;">
                                                        {{ $booking->topic }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <i class="far fa-calendar"></i>
                                                <span>{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="session-details-item">
                                                <span style="color: #94a3b8;">&bull;</span>
                                                <i class="far fa-clock"></i>
                                                <span>{{ $booking->time }}</span>
                                            </div>
                                            @if($booking->staff)
                                                <div class="session-details-item">
                                                    <span style="color: #94a3b8;">&bull;</span>
                                                    <i class="fas fa-user-tie" style="color: #10b981;"></i>
                                                    <span>{{ $booking->staff->name }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2"
                                        style="border-radius: 20px; font-size: 0.85rem; font-weight: 600;">
                                        <i class="fas fa-check-circle me-1"></i> Completed
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
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
                <form action="{{ route('tech-head.doubt-sessions.book') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="doubt">
                    <input type="hidden" name="date" id="modalInputDate">
                    <input type="hidden" name="time" id="modalInputTime">
                    <input type="hidden" name="staff_id" value="{{ $selectedStaffId }}">

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
                                style="font-weight: 600; font-size: 0.8rem; text-transform: uppercase; color: var(--text-secondary);">Doubt
                                Topic</label>
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
        function filterByStaff(staffId) {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('staff_id', staffId);
            window.location.search = urlParams.toString();
        }

        function openBookingModal(time, dateValue, dateDisplay) {
            document.getElementById('modalInputTime').value = time;
            document.getElementById('modalInputDate').value = dateValue;
            document.getElementById('modalDisplayDate').innerText = dateDisplay + ' • ' + time;
            document.getElementById('modalDisplayTimeSlot').value = dateDisplay + ' • ' + time;

            var modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Tab switching
            const tabs = document.querySelectorAll('.top-nav-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const target = this.getAttribute('data-tab');

                    document.querySelectorAll('.tab-content-section').forEach(section => {
                        section.classList.add('d-none');
                    });

                    const targetSection = document.getElementById('tab-content-' + target);
                    if (targetSection) {
                        targetSection.classList.remove('d-none');
                    }
                });
            });

            // Initialize JavaScript local filters
            initFilters('tab-content-assigned');
            initFilters('tab-content-completed');
        });

        function initFilters(tabId) {
            const container = document.getElementById(tabId);
            if (!container) return;

            const searchInput = container.querySelector('.search-input');
            const dateInput = container.querySelector('.date-input');
            const btnToday = container.querySelector('.btn-filter-today');
            const btnAll = container.querySelector('.btn-filter-all');
            const rows = container.querySelectorAll('.session-row-item');

            let activeFilters = {
                search: '',
                date: 'today' // Default selected today
            };

            function applyFilters() {
                const d = new Date();
                const year = d.getFullYear();
                const month = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                const todayStr = `${year}-${month}-${day}`;

                rows.forEach(row => {
                    const name = row.getAttribute('data-name').toLowerCase();
                    const course = row.getAttribute('data-course').toLowerCase();
                    const phone = row.getAttribute('data-phone').toLowerCase();
                    const topic = row.getAttribute('data-topic') ? row.getAttribute('data-topic').toLowerCase() : '';
                    const dateVal = row.getAttribute('data-date'); // 'YYYY-MM-DD'

                    let show = true;

                    // 1. Search Filter
                    if (activeFilters.search) {
                        const q = activeFilters.search.toLowerCase();
                        if (!name.includes(q) && !course.includes(q) && !phone.includes(q) && !topic.includes(q)) {
                            show = false;
                        }
                    }

                    // 2. Date Filter
                    if (activeFilters.date) {
                        if (activeFilters.date === 'today') {
                            if (dateVal !== todayStr) {
                                show = false;
                            }
                        } else {
                            if (dateVal !== activeFilters.date) {
                                show = false;
                            }
                        }
                    } else {
                        // "All" filter selected: show current and future records (dateVal >= todayStr)
                        if (dateVal < todayStr) {
                            show = false;
                        }
                    }

                    if (show) {
                        row.classList.remove('d-none');
                    } else {
                        row.classList.add('d-none');
                    }
                });

                // Show/hide empty state message
                const visibleRows = container.querySelectorAll('.session-row-item:not(.d-none)');
                const defaultEmptyMsg = container.querySelector('.empty-state-message:not(.d-none)');
                const searchEmptyMsg = container.querySelectorAll('.empty-state-message')[1];

                if (rows.length === 0) {
                    return;
                }

                if (visibleRows.length === 0) {
                    searchEmptyMsg.classList.remove('d-none');
                } else {
                    searchEmptyMsg.classList.add('d-none');
                }
            }

            // Apply default filter on page load
            applyFilters();

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    activeFilters.search = this.value;
                    applyFilters();
                });
            }

            if (dateInput) {
                dateInput.addEventListener('change', function () {
                    activeFilters.date = this.value;
                    if (btnToday) btnToday.classList.remove('active');
                    if (btnAll) btnAll.classList.remove('active');
                    applyFilters();
                });
            }

            if (btnToday) {
                btnToday.addEventListener('click', function () {
                    btnToday.classList.add('active');
                    if (btnAll) btnAll.classList.remove('active');
                    if (dateInput) dateInput.value = '';
                    activeFilters.date = 'today';
                    applyFilters();
                });
            }

            if (btnAll) {
                btnAll.addEventListener('click', function () {
                    btnAll.classList.add('active');
                    if (btnToday) btnToday.classList.remove('active');
                    if (dateInput) dateInput.value = '';
                    activeFilters.date = '';
                    applyFilters();
                });
            }
        }
    </script>
@endpush