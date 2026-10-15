@extends('layouts.master')

@section('pageTitle', 'Staff Monitoring')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/tech_staff_monitoring.css') }}">
    <style>
        .staff-row-clickable:hover {
            background-color: #f8fafc !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        .staff-row-clickable {
            transition: all 0.2s ease;
        }
    </style>
@endpush

@section('content')
    <div class="tech-batch-wrapper" style="padding: 1rem;">
        <!-- Header -->
        <div class="page-header">
            <h1 class="page-title">Staff <span>Monitor</span></h1>

            <div class="header-actions">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="staffSearchInput" placeholder="Search batches, students, staff">
                </div>

                <button class="btn-quick-action" onclick="window.location.reload();">
                    <i class="fas fa-bolt text-warning"></i> Quick Action
                </button>
            </div>
        </div>

        <!-- Main Card -->
        <div class="main-card">
            <div class="card-header-flex">
                <h2 class="section-heading">
                    <i class="fas fa-user-tie"></i> Staff — Click name for detail view
                </h2>
                <button class="btn-staff-meeting" onclick="openGeneralStaffMeetingModal()">
                    <i class="fas fa-plus"></i> Staff Meeting
                </button>
            </div>

            <div class="table-responsive">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>Staff Member</th>
                            <th>Course</th>
                            <th>Batches</th>
                            <th>Syllabus Done</th>
                            <th>Performance</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staffMembers as $index => $user)
                            @php
                                $staff = $user->staffProfile;
                                $name = $user->name ?? ($staff ? trim($staff->first_name . ' ' . $staff->last_name) : 'Unknown');
                                $email = $user->email;
                                $initials = collect(explode(' ', $name))->map(function ($segment) {
                                    return strtoupper(substr($segment, 0, 1));
                                })->join('');
                                $initials = substr($initials, 0, 2);

                                $metrics = $user->metrics ?? [];
                                $batchesCount = $metrics['batchesCount'] ?? 0;
                                $courseName = $metrics['primaryCourse'] ?? 'N/A';
                                $syllabusDone = $metrics['syllabusDone'] ?? 0;
                                $performance = $metrics['performance'] ?? 0;
                                $status_perc = $metrics['statusPerc'] ?? 0;

                                // Dynamic styling based on course
                                $course_class = 'fs';
                                $course_icon = 'fa-bolt';
                                $syllabus_color = '#6366f1';

                                $courseLower = strtolower($courseName);
                                if (str_contains($courseLower, 'ui') || str_contains($courseLower, 'ux')) {
                                    $course_class = 'ux';
                                    $course_icon = 'fa-palette';
                                    $syllabus_color = '#ec4899';
                                } elseif (str_contains($courseLower, 'data')) {
                                    $course_class = 'ds';
                                    $course_icon = 'fa-chart-bar';
                                    $syllabus_color = '#10b981';
                                } elseif (str_contains($courseLower, 'marketing')) {
                                    $course_class = 'dm';
                                    $course_icon = 'fa-bullhorn';
                                    $syllabus_color = '#f59e0b';
                                }

                                // Define color palettes for avatars
                                $bgColors = ['#6366f1', '#818cf8', '#f472b6', '#fbbf24', '#34d399'];
                                $avatar_bg = $bgColors[$index % count($bgColors)];
                                $perf_color = '#a855f7';

                                $isActive = ($staff && $staff->status == 1);
                                if ($staff && !is_numeric($staff->status)) {
                                    $isActive = strtolower($staff->status) == 'active' || $staff->status === null;
                                }

                                $status_text = $isActive ? 'Active' : 'Inactive/Leave';
                                $status_dot = $isActive ? 'active' : 'leave';

                                if ($batchesCount == 0) {
                                    $syllabus_color = '#cbd5e1';
                                    $perf_color = '#cbd5e1';
                                }
                            @endphp

                            <tr onclick="window.location='{{ route('tech.staff-monitoring.show', $user->id) }}';" style="cursor: pointer;" class="staff-row-clickable">
                                <td>
                                    <div class="user-profile">
                                        <div class="user-avatar" style="background-color: {{ $avatar_bg }}">
                                            {{ $initials }}
                                        </div>
                                        <div class="user-info">
                                            <span class="user-name" style="color: inherit; font-weight: 600;">{{ $name }}</span>
                                            <span class="user-email">{{ $email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="course-pill {{ $course_class }}">
                                        <i class="fas {{ $course_icon }}"></i> {{ $courseName }}
                                    </div>
                                </td>
                                <td>
                                    <span class="batch-count">{{ $batchesCount }}</span>
                                </td>
                                <td>
                                    <div class="progress-cell">
                                        <div class="progress-track">
                                            <div class="progress-fill"
                                                style="width: {{ $syllabusDone }}%; background-color: {{ $syllabus_color }};">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress-cell">
                                        <span class="progress-text">{{ $performance }}%</span>
                                        <div class="progress-track">
                                            <div class="progress-fill"
                                                style="width: {{ $performance }}%; background-color: {{ $perf_color }};"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="status-cell">
                                        <span>{{ $status_perc }}%</span>
                                        <div class="status-dot {{ $status_dot }}"></div>
                                        <span
                                            style="color: {{ $status_dot == 'active' ? 'var(--accent-green)' : 'var(--accent-orange)' }}">{{ $status_text }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No technical staff found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Live Search Filter for Staff Table
        document.getElementById('staffSearchInput')?.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.staff-row-clickable');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });

        function setGeneralModalMeetingType(type) {
            document.getElementById('meetingTypeInput').value = type;
            const isStaff = (type === 'Staff Meeting');
            
            const btnStaff = document.getElementById('btnStaffMeeting');
            const btnGroup = document.getElementById('btnGroupMeeting');
            
            if (btnStaff && btnGroup) {
                btnStaff.style.background = isStaff ? '#eff6ff' : '#f8fafc';
                btnStaff.style.color = isStaff ? '#2563eb' : '#64748b';
                btnStaff.style.border = isStaff ? '1px solid #3b82f6' : '1px solid #cbd5e1';
                
                btnGroup.style.background = !isStaff ? '#eff6ff' : '#f8fafc';
                btnGroup.style.color = !isStaff ? '#2563eb' : '#64748b';
                btnGroup.style.border = !isStaff ? '1px solid #3b82f6' : '1px solid #cbd5e1';
            }

            const staffCont = document.getElementById('staffSelectContainer');
            const groupCont = document.getElementById('groupSelectContainer');
            if (staffCont) staffCont.style.display = isStaff ? 'block' : 'none';
            if (groupCont) groupCont.style.display = !isStaff ? 'block' : 'none';

            const titleInput = document.getElementById('meetingTitle');
            if (titleInput) {
                if (isStaff) {
                    titleInput.placeholder = 'e.g. 1:1 — Performance Review, Curriculum Sync';
                    if (!titleInput.value || titleInput.value.includes('Technical Staff Group Sync')) {
                        titleInput.value = '1:1 Staff Performance Sync';
                    }
                } else {
                    titleInput.placeholder = 'e.g. Weekly Curriculum Sync, Tech Stack Discussion';
                    if (!titleInput.value || titleInput.value.includes('1:1 Staff Performance Sync')) {
                        titleInput.value = 'Technical Staff Group Sync & Review';
                    }
                }
            }
        }

        function generateZoomLinkForIndexModal() {
            const titleInput = document.getElementById('meetingTitle');
            const dateInput = document.getElementById('meetingDate');
            const timeInput = document.getElementById('meetingTime');
            const linkInput = document.getElementById('meetingLink');
            const btnText = document.getElementById('btnGenZoomTextIndex');
            const statusBadge = document.getElementById('zoomLinkStatusBadgeIndex');

            const title = (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : 'Staff Meeting';
            const date = (dateInput && dateInput.value) ? dateInput.value : '{{ date("Y-m-d") }}';
            const time = (timeInput && timeInput.value) ? timeInput.value : '10:00';

            btnText.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';

            fetch("{{ route('meetings.generate-zoom-link') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: title,
                    date: date,
                    time: time
                })
            })
            .then(res => res.json())
            .then(data => {
                btnText.innerHTML = '<i class="fas fa-magic me-1"></i> Auto-Generate Zoom Link';
                if (data.link) {
                    linkInput.value = data.link;
                    if (data.zoom_meeting_id) {
                        document.getElementById('zoomMeetingIdIndex').value = data.zoom_meeting_id;
                        document.getElementById('zoomPasswordIndex').value = data.zoom_password || '';
                        document.getElementById('zoomStartUrlIndex').value = data.start_url || '';
                    }
                    statusBadge.style.display = 'block';
                    statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> ' + (data.message || 'Zoom meeting link generated!');
                }
            })
            .catch(err => {
                btnText.innerHTML = '<i class="fas fa-magic me-1"></i> Auto-Generate Zoom Link';
                console.error('Zoom link gen error:', err);
                const fallback = 'https://meet.jit.si/velearn-staff-' + Math.random().toString(36).substring(2, 9);
                linkInput.value = fallback;
                statusBadge.style.display = 'block';
                statusBadge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Live meeting room generated!';
            });
        }

        // Modal for Scheduling Staff Meeting from Index
        function openGeneralStaffMeetingModal() {
            Swal.fire({
                title: '<div style="text-align: left; font-weight: bold; font-family: \'Outfit\', sans-serif; color: #1e293b;">Schedule Staff Meeting<div style="font-size: 0.8rem; color: #64748b; font-weight: normal; margin-top: 5px;">Connect with staff via 1:1 or group meeting</div></div>',
                html: `
                    <div style="text-align: left; font-family: 'Inter', sans-serif;">
                        <div class="mb-3">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Meeting Type</label>
                            <div class="d-flex gap-2">
                                <input type="hidden" id="meetingTypeInput" value="Staff Meeting">
                                <button type="button" id="btnStaffMeeting" onclick="setGeneralModalMeetingType('Staff Meeting')" class="btn flex-fill text-start" style="background: #eff6ff; border: 1px solid #3b82f6; color: #2563eb; font-weight: 600; border-radius: 6px;"><i class="fas fa-user-friends me-2"></i> 1:1 Staff Meeting</button>
                                <button type="button" id="btnGroupMeeting" onclick="setGeneralModalMeetingType('Group Meeting')" class="btn flex-fill text-start" style="background: #f8fafc; border: 1px solid #cbd5e1; color: #64748b; font-weight: 600; border-radius: 6px;"><i class="fas fa-users me-2"></i> Group Meeting</button>
                            </div>
                        </div>

                        <div class="mb-3" id="staffSelectContainer">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Select Staff Member <span class="text-danger">*</span></label>
                            <select id="meetingStaffSelect" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;">
                                @foreach($staffMembers as $s)
                                    <option value="{{ $s->id }}">{{ addslashes($s->name) }} ({{ addslashes($s->metrics['primaryCourse'] ?? 'Technical Staff') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="groupSelectContainer" style="display: none;">
                            <div class="p-2 px-3 bg-light rounded border text-muted small">
                                <i class="fas fa-info-circle text-primary me-1"></i> Invites <strong>All Technical Staff ({{ count($staffMembers) }} members)</strong> automatically to this meeting.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Heading / Title <span class="text-danger">*</span></label>
                            <input type="text" id="meetingTitle" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" value="1:1 Staff Performance Sync" placeholder="e.g. Curriculum Review &middot; 1:1 Performance Sync">
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Date <span class="text-danger">*</span></label>
                                <input type="date" id="meetingDate" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-6">
                                <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Time <span class="text-danger">*</span></label>
                                <input type="time" id="meetingTime" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" value="{{ date('H:i') }}">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Agenda / Notes</label>
                            <textarea id="meetingAgenda" class="form-control" rows="2" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" placeholder="Topics to cover, review notes..."></textarea>
                        </div>
                        
                        <div class="mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0;">Meeting Link</label>
                                <button type="button" class="btn btn-sm btn-link text-primary p-0 text-decoration-none fw-bold" style="font-size: 0.75rem;" onclick="generateZoomLinkForIndexModal()">
                                    <i class="fas fa-magic me-1"></i> <span id="btnGenZoomTextIndex">Auto-Generate Zoom Link</span>
                                </button>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class="fas fa-video"></i></span>
                                <input type="url" id="meetingLink" class="form-control border-start-0" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px;" placeholder="Auto-generates Zoom link if left blank">
                            </div>
                            <div id="zoomLinkStatusBadgeIndex" style="display: none; margin-top: 6px;" class="small text-success fw-bold">
                                <i class="fas fa-check-circle me-1"></i> Zoom meeting link generated successfully!
                            </div>
                            <input type="hidden" id="zoomMeetingIdIndex">
                            <input type="hidden" id="zoomPasswordIndex">
                            <input type="hidden" id="zoomStartUrlIndex">
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Schedule &rarr;',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn px-4 py-2 w-50 text-white fw-bold',
                    cancelButton: 'btn px-4 py-2 w-50 text-dark fw-bold border',
                    actions: 'w-100 d-flex gap-2'
                },
                background: '#ffffff',
                color: '#1e293b',
                width: '600px',
                showCloseButton: true,
                focusConfirm: false,
                didOpen: () => {
                    const confirmBtn = Swal.getConfirmButton();
                    confirmBtn.style.background = 'linear-gradient(to right, #3b82f6, #4f46e5)';
                    confirmBtn.style.border = 'none';
                    
                    const cancelBtn = Swal.getCancelButton();
                    cancelBtn.style.background = '#f8fafc';
                    cancelBtn.style.border = '1px solid #cbd5e1';
                    cancelBtn.style.color = '#334155';
                },
                preConfirm: () => {
                    const meetingType = document.getElementById('meetingTypeInput').value;
                    const staffId = document.getElementById('meetingStaffSelect')?.value;
                    const title = document.getElementById('meetingTitle').value;
                    const date = document.getElementById('meetingDate').value;
                    const time = document.getElementById('meetingTime').value;
                    const agenda = document.getElementById('meetingAgenda').value;
                    const meetLink = document.getElementById('meetingLink').value;
                    const zoomMeetingId = document.getElementById('zoomMeetingIdIndex')?.value;
                    const zoomPassword = document.getElementById('zoomPasswordIndex')?.value;
                    const zoomStartUrl = document.getElementById('zoomStartUrlIndex')?.value;
                    
                    if ((meetingType === 'Staff Meeting' && !staffId) || !title || !date || !time) {
                        Swal.showValidationMessage('Please fill in all required fields');
                        return false;
                    }

                    const targetStaffId = meetingType === 'Staff Meeting' ? staffId : (document.getElementById('meetingStaffSelect')?.value || '1');
                    
                    return fetch(`/tech/staff-monitoring/${targetStaffId}/schedule`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            meeting_type: meetingType,
                            title: title,
                            date: date,
                            time: time,
                            agenda: agenda,
                            meet_link: meetLink,
                            zoom_meeting_id: zoomMeetingId,
                            zoom_password: zoomPassword,
                            zoom_start_url: zoomStartUrl
                        })
                    }).then(response => {
                        if (!response.ok) {
                            throw new Error(response.statusText)
                        }
                        return response.json()
                    }).catch(error => {
                        Swal.showValidationMessage(`Request failed: ${error}`)
                    });
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    const res = result.value;
                    const link = res.meet_link || '';
                    const startUrl = res.start_url || link;
                    const mt = res.meeting || {};

                    Swal.fire({
                        icon: 'success',
                        title: `<div style="font-size: 1.3rem; font-weight: bold; color: #1e293b;">${mt.meeting_type || 'Meeting'} Scheduled!</div>`,
                        html: `
                            <div style="text-align: left; font-size: 0.9rem; color: #475569; margin-top: 10px;">
                                <p class="mb-2"><strong>Title:</strong> ${mt.title || ''}</p>
                                <p class="mb-2"><strong>Date & Time:</strong> ${mt.date || ''} at ${mt.time || ''}</p>
                                ${res.zoom_meeting_id ? `<p class="mb-2"><strong>Zoom Meeting ID:</strong> ${res.zoom_meeting_id}</p>` : ''}
                                ${res.zoom_password ? `<p class="mb-2"><strong>Passcode:</strong> ${res.zoom_password}</p>` : ''}
                                <div class="p-3 bg-light rounded-3 border mt-3">
                                    <div class="fw-bold small text-muted text-uppercase mb-1">Meeting Link</div>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="text" readonly value="${link}" class="form-control form-control-sm bg-white" id="successMeetLinkInputIndex">
                                        <button type="button" class="btn btn-sm btn-primary text-nowrap" onclick="navigator.clipboard.writeText('${link}'); this.innerText='Copied!';"><i class="fas fa-copy me-1"></i> Copy</button>
                                    </div>
                                </div>
                            </div>
                        `,
                        background: '#ffffff',
                        color: '#1e293b',
                        confirmButtonText: '<i class="fas fa-video me-1"></i> Start / Join Meeting',
                        showCancelButton: true,
                        cancelButtonText: 'Done',
                        confirmButtonColor: '#3b82f6',
                        customClass: {
                            popup: 'swal-custom-popup'
                        }
                    }).then((action) => {
                        if (action.isConfirmed && (startUrl || link)) {
                            window.open(startUrl || link, '_blank');
                        }
                    });
                }
            });
        }
    </script>
@endpush