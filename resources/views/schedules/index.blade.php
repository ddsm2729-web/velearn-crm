@extends('layouts.header')

@section('pageTitle', 'Schedule Demo')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-schedules.css') }}">
    <style>
        .staff-selector-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8edf2;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            margin-bottom: 24px;
        }
        .staff-select-wrapper {
            position: relative;
        }
        .staff-select-wrapper select {
            appearance: none;
            -webkit-appearance: none;
            background: #f8fafc;
            border: 1.5px solid #e0e7ef;
            border-radius: 10px;
            padding: 12px 44px 12px 16px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1a2942;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
            max-width: 360px;
        }
        .staff-select-wrapper select:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,.12);
        }
        .staff-select-wrapper .select-arrow {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6c757d;
            max-width: 360px;
        }
        .staff-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .schedule-panel {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8edf2;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            min-height: 300px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #adb5bd;
        }
        .slot-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        @media (max-width: 767px) {
            .slot-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('section')
    <div class="demo-page p-4">
        <div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <h3 class="fw-bold mb-1 text-dark">Schedule Demo</h3>
                <p class="text-muted small mb-0">Select a technical staff member to view and book demo slots</p>
            </div>
        </div>

        {{-- Staff Selector --}}
        <div class="staff-selector-card">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div>
                    <label class="form-label fw-semibold text-muted small mb-1 text-uppercase" style="letter-spacing:.05em;">Select Technical Staff</label>
                    <div class="staff-select-wrapper">
                        <select id="staffDropdown" onchange="onStaffChange(this)">
                            <option value="">— Choose a staff member —</option>
                            @foreach($staffs as $staff)
                                <option value="{{ $staff->id }}"
                                    data-name="{{ $staff->first_name }} {{ $staff->last_name }}"
                                    data-initial="{{ strtoupper(substr($staff->first_name, 0, 1)) }}">
                                    {{ $staff->first_name }} {{ $staff->last_name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="select-arrow"><i class="fas fa-chevron-down"></i></span>
                    </div>
                </div>

                {{-- Selected staff info badge (shown after selection) --}}
                <div id="selectedStaffBadge" class="d-none align-items-center gap-3 mt-3 mt-md-0" style="padding-top:18px;">
                    <div class="staff-avatar" id="badgeAvatar">T</div>
                    <div>
                        <div class="fw-bold text-dark" id="badgeName" style="font-size:1rem;"></div>
                        <small class="text-muted booked-count" id="badgeCount">0/0 slots booked</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Schedule Panel --}}
        <div class="schedule-panel">

            {{-- Empty state (default) --}}
            <div id="noStaffSelected" class="empty-state">
                <i class="fas fa-user-clock"></i>
                <p>Please select a technical staff member from the dropdown above to view their schedule.</p>
            </div>

            {{-- Schedule content (hidden until staff is selected) --}}
            <div id="scheduleContent" class="d-none">

                {{-- Day tabs --}}
                <div class="tabs-wrapper mb-3" id="dayTabs">
                    @foreach($dates as $index => $date)
                    <div class="day-tab {{ $index == 0 ? 'active' : '' }}"
                        onclick="switchTabSingle(this, '{{ $date['full'] }}')"
                        data-day="{{ $date['day_of_week'] }}"
                        data-full="{{ $date['full'] }}">
                        {{ $date['day_name'] }}
                    </div>
                    @endforeach
                </div>

                {{-- Slots area --}}
                <div id="slotsArea" class="w-100"></div>
            </div>
        </div>
    </div>

    {{-- Booking Modal --}}
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-2 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-calendar-check text-primary me-2"></i>Book Demo Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="booking-details-box mb-4">
                        <div class="row text-center mb-3">
                            <div class="col-6 border-end">
                                <div class="small text-muted fw-bold mb-1">STAFF</div>
                                <div class="fw-bold text-dark" id="display_staff"></div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted fw-bold mb-1">TIME SLOT</div>
                                <div class="fw-bold text-primary" id="display_time"></div>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-6 border-end">
                                <div class="small text-muted fw-bold mb-1">DAY</div>
                                <div class="fw-bold text-dark" id="display_day"></div>
                            </div>
                            <div class="col-6">
                                <div class="small text-muted fw-bold mb-1">SLOT #</div>
                                <div class="fw-bold text-dark" id="display_slot_num"></div>
                            </div>
                        </div>
                    </div>

                    <form id="bookingForm" onsubmit="event.preventDefault(); confirmBooking();">
                        <input type="hidden" id="selected_staff_id">
                        <input type="hidden" id="selected_demo_date">
                        <input type="hidden" id="selected_demo_time">
                        <input type="hidden" id="selected_contact_id">
                        <input type="hidden" id="selected_lead_id">

                        <div class="mb-3 position-relative">
                            <label class="form-label small fw-bold text-muted">Student Name / Phone</label>
                            <input type="text" id="studentSearch" class="form-control-light" placeholder="Search by name or phone...">
                            <div id="suggestions" class="suggestions-list"></div>
                        </div>

                    </form>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-outline-secondary rounded-pill px-4 flex-fill" data-bs-dismiss="modal">Cancel</button>
                        <button id="confirmBookingBtn" class="btn btn-primary rounded-pill px-4 flex-fill fw-bold" onclick="confirmBooking()">
                            <i class="fas fa-check me-2"></i>Confirm Booking
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
    <script>
        const masterSchedules = @json($staffs->map(fn($s) => ['id' => $s->id, 'schedules' => $s->schedules]));
        const bookedDemos     = @json($bookedDemos);
        const preFilledContact = @json($preFilledContact ?? null);

        let activeStaffId   = null;
        let activeStaffName = '';
        let activeFullDate  = '{{ count($dates) > 0 ? $dates[0]["full"] : now()->toDateString() }}';
        let activeDay       = '{{ count($dates) > 0 ? $dates[0]["day_of_week"] : now()->format("l") }}';

        function normalizeTime(time) {
            if (!time) return '';
            let parts = time.split(':');
            let h = parts[0].padStart(2, '0');
            let m = parts[1] ? parts[1].padStart(2, '0') : '00';
            let s = parts[2] ? parts[2].padStart(2, '0') : '00';
            return `${h}:${m}:${s}`;
        }

        function formatTime(time) {
            let [hours, minutes] = time.split(':');
            let ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12;
            return hours + ':' + minutes + ' ' + ampm;
        }

        function onStaffChange(sel) {
            const staffId = sel.value;
            const selectedOpt = sel.options[sel.selectedIndex];

            if (!staffId) {
                // Reset
                activeStaffId = null;
                $('#selectedStaffBadge').addClass('d-none').removeClass('d-flex');
                $('#scheduleContent').addClass('d-none');
                $('#noStaffSelected').removeClass('d-none');
                return;
            }

            activeStaffId   = staffId;
            activeStaffName = selectedOpt.dataset.name;
            const initial   = selectedOpt.dataset.initial;

            // Update badge
            $('#badgeAvatar').text(initial);
            $('#badgeName').text(activeStaffName);
            $('#selectedStaffBadge').removeClass('d-none').addClass('d-flex');

            // Show schedule panel
            $('#noStaffSelected').addClass('d-none');
            $('#scheduleContent').removeClass('d-none');

            // Reset tabs to first
            $('#dayTabs .day-tab').removeClass('active');
            $('#dayTabs .day-tab:first').addClass('active');
            activeFullDate = $('#dayTabs .day-tab:first').data('full');
            activeDay      = $('#dayTabs .day-tab:first').data('day');

            renderSlots();
        }

        function switchTabSingle(el, fullDate) {
            $('#dayTabs .day-tab').removeClass('active');
            $(el).addClass('active');
            activeFullDate = fullDate;
            activeDay      = $(el).data('day');
            renderSlots();
        }

        function renderSlots() {
            if (!activeStaffId) return;

            const staff           = masterSchedules.find(s => s.id == activeStaffId);
            const availableSlots  = staff ? staff.schedules.filter(s => s.day === activeDay && s.status === 'available') : [];
            const staffBooked     = bookedDemos.filter(d => String(d.assigned_staff_id) === String(activeStaffId) && d.demo_date === activeFullDate);

            const availableTimes  = availableSlots.map(s => normalizeTime(s.schedule_time));
            const bookedTimes     = staffBooked.map(b => normalizeTime(b.demo_time));
            const allTimesSet     = [...new Set([...availableTimes, ...bookedTimes])].sort();

            const now           = new Date();
            const todayDateStr  = now.getFullYear() + '-' + String(now.getMonth()+1).padStart(2,'0') + '-' + String(now.getDate()).padStart(2,'0');
            const currentTime   = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0') + ':00';

            // Update booked count badge
            $('#badgeCount').text(`${staffBooked.length}/${allTimesSet.length} slots booked`);

            let html = '';
            if (allTimesSet.length > 0) {
                let slots = '';
                allTimesSet.forEach((time, index) => {
                    const timeStr  = formatTime(time);
                    const isBooked = bookedTimes.includes(time);
                    const isPast   = (activeFullDate < todayDateStr) || (activeFullDate === todayDateStr && time < currentTime);

                    if (isBooked) {
                        slots += `<button class="slot-btn booked" disabled>
                            <span class="slot-num">S${index+1} ${timeStr}</span>
                            <span class="slot-status text-danger"><i class="fas fa-lock me-1"></i>Booked</span>
                        </button>`;
                    } else if (isPast) {
                        slots += `<button class="slot-btn passed" disabled>
                            <span class="slot-num">S${index+1} ${timeStr}</span>
                            <span class="slot-status text-muted"><i class="fas fa-history me-1"></i>Passed</span>
                        </button>`;
                    } else {
                        slots += `<button class="slot-btn"
                            onclick="openBookingModal('${activeStaffId}','${activeStaffName}','${time}','${activeDay}','Slot ${index+1}','${activeFullDate}')">
                            <span class="slot-num">S${index+1} ${timeStr}</span>
                            <span class="slot-status text-success"><i class="fas fa-circle-check me-1"></i>Available</span>
                        </button>`;
                    }
                });
                html = `<div class="slot-grid">${slots}</div>`;
            } else {
                html = '<div class="text-center py-5 text-muted small"><i class="fas fa-calendar-times fa-2x mb-3 d-block"></i>No available slots for this day</div>';
            }

            $('#slotsArea').html(html);
        }

        function openBookingModal(staffId, staffName, time, day, slotNum, fullDate) {
            $('#display_staff').text(staffName);
            $('#display_time').text(formatTime(time));
            $('#display_day').text(day);
            $('#display_slot_num').text(slotNum);

            $('#selected_staff_id').val(staffId);
            $('#selected_demo_date').val(fullDate);
            $('#selected_demo_time').val(time);

            if (preFilledContact) {
                if (preFilledContact.type === 'lead') {
                    $('#selected_lead_id').val(preFilledContact.id);
                    $('#selected_contact_id').val('');
                } else {
                    $('#selected_contact_id').val(preFilledContact.id);
                    $('#selected_lead_id').val('');
                }
                const pName = preFilledContact.first_name + (preFilledContact.last_name ? ' ' + preFilledContact.last_name : '');
                $('#studentSearch').val(pName + ' (' + preFilledContact.caller_number + ')').prop('readonly', true);
            } else {
                $('#selected_contact_id').val('');
                $('#selected_lead_id').val('');
                $('#studentSearch').val('').prop('readonly', false);
            }

            $('#bookingModal').modal('show');
        }

        // Autocomplete
        $('#studentSearch').on('input', function() {
            const query = $(this).val();
            if (query.length < 2) { $('#suggestions').hide(); return; }

            $.get("{{ route('contacts.search') }}", { q: query }, function(data) {
                let html = '';
                data.forEach(item => {
                    let fullName = item.first_name + ' ' + (item.last_name || '');
                    html += `<div class="suggestion-item" onclick="selectStudent('${item.id}', '${item.type}', '${fullName}','${item.caller_number}')">
                        <div class="fw-bold">${fullName} <span class="badge bg-secondary ms-2" style="font-size:0.6rem;">${item.type.toUpperCase()}</span></div>
                        <div class="sub">${item.caller_number}</div>
                    </div>`;
                });
                $('#suggestions').html(html).show();
            });
        });

        function selectStudent(id, type, name, phone) {
            $('#selected_contact_id').val('');
            $('#selected_lead_id').val('');

            if (type === 'lead') {
                $('#selected_lead_id').val(id);
            } else {
                $('#selected_contact_id').val(id);
            }
            
            $('#studentSearch').val(name + ' (' + phone + ')');
            $('#suggestions').hide();
        }

        function confirmBooking() {
            const contactId = $('#selected_contact_id').val();
            const leadId    = $('#selected_lead_id').val();
            const staffId   = $('#selected_staff_id').val();
            const demoDate  = $('#selected_demo_date').val();
            const demoTime  = $('#selected_demo_time').val();

            if (!contactId && !leadId) {
                toastr.error('Please select a student/lead from the suggestions');
                return;
            }

            const btn = $('#confirmBookingBtn');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Booking...');

            $.ajax({
                url: "{{ route('demo.book') }}",
                type: 'POST',
                data: {
                    _token:     "{{ csrf_token() }}",
                    contact_id: contactId,
                    lead_id:    leadId,
                    staff_id:   staffId,
                    demo_date:  demoDate,
                    demo_time:  demoTime,
                },
                success(response) {
                    if (response.status) {
                        toastr.success('Demo booked successfully!');
                        $('#bookingModal').modal('hide');
                        setTimeout(() => {
                            window.location.href = "{{ route('demo.tracking') }}";
                        }, 1000);
                    } else {
                        btn.prop('disabled', false).html(originalText);
                        toastr.error(response.message);
                    }
                },
                error() { 
                    btn.prop('disabled', false).html(originalText);
                    toastr.error('Something went wrong. Please try again.'); 
                }
            });
        }

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#studentSearch, #suggestions').length) {
                $('#suggestions').hide();
            }
        });

        // Auto-select if only one staff
        @if($staffs->count() === 1)
        $(function() {
            $('#staffDropdown').val('{{ $staffs->first()->id }}').trigger('change');
        });
        @endif
    </script>
    @endpush
@endsection
