@extends('layouts.master')

@section('title', 'Student Profile')

@push('css')
<style>
    body {
        background-color: #f8fafc;
        font-family: 'Inter', sans-serif;
    }
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        overflow: hidden;
    }
    
    .profile-header {
        height: 100px;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
    }
    
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: #1e1b4b;
        color: white;
        font-size: 2rem;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: -40px auto 10px;
        border: 4px solid white;
    }
    
    .status-badge {
        background: #ecfdf5;
        color: #10b981;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .info-row {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #64748b;
        width: 100px;
        display: flex;
        align-items: center;
        font-weight: 500;
    }
    
    .info-label i {
        width: 20px;
        color: #94a3b8;
    }
    
    .info-value {
        font-weight: 600;
        color: #1e293b;
        margin-left: auto;
        text-align: right;
    }
    
    .btn-assign {
        background: #ef4444;
        color: white;
        font-weight: 600;
        border: none;
        padding: 10px;
        border-radius: 8px;
        width: calc(100% - 40px);
        margin: 0 20px 20px;
    }
    
    .btn-assign:hover {
        background: #dc2626;
        color: white;
    }

    .lms-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 24px;
        margin-bottom: 20px;
    }
    
    .nav-tabs-custom {
        border-bottom: none;
        background: #f8fafc;
        border-radius: 8px;
        padding: 4px;
        display: flex;
        gap: 4px;
    }
    
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 10px 20px;
        border-radius: 6px;
        flex: 1;
        text-align: center;
    }
    
    .nav-tabs-custom .nav-link.active {
        background: #1e1b4b;
        color: white;
    }

    .summary-box {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        flex: 1;
    }
    
    .summary-box h6 {
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .summary-box .val {
        font-size: 1.8rem;
        font-weight: 800;
    }

    .module-item {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .module-item:last-child {
        border-bottom: none;
    }
    
    .module-icon {
        width: 24px;
        height: 24px;
        background: #3b82f6;
        color: white;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        margin-right: 12px;
    }
    
    .module-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
    }
    
    .module-status {
        margin-left: auto;
        color: #10b981;
        font-weight: 700;
        font-size: 0.8rem;
    }

    .metric-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        padding: 15px;
        height: 100%;
        border: 1px solid #f1f5f9;
    }
    
    .metric-card .icon {
        color: #3b82f6;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }
    
    .metric-card h6 {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: uppercase;
        font-weight: 700;
        margin-bottom: 2px;
    }
    
    .metric-card .val {
        font-size: 1.4rem;
        font-weight: 800;
        color: #6366f1;
    }

    /* New Submission Card Styles */
    .submission-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        border-left: 4px solid #cbd5e1;
    }
    .submission-card.success { border-left-color: #10b981; }
    .submission-card.warning { border-left-color: #f59e0b; }
    .submission-card.danger { border-left-color: #ef4444; }
    
    .score-large { font-size: 1.8rem; font-weight: 800; }
    .score-max { font-size: 1rem; color: #64748b; font-weight: 600; }
    
    .feedback-box {
        background: #f0fdf4;
        color: #166534;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 0.85rem;
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .feedback-box.danger { background: #fef2f2; color: #991b1b; }
    .feedback-box.warning { background: #fffbeb; color: #b45309; }
    .feedback-box.pending { background: #f8fafc; color: #475569; }
    
    .progress-thin {
        height: 6px;
        background: #e2e8f0;
        border-radius: 10px;
        margin-top: 10px;
    }
    .review-row {
        margin-bottom: 20px;
    }
    .review-row:last-child {
        margin-bottom: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="javascript:history.back()" class="text-decoration-none text-muted fw-bold" style="font-size: 0.9rem;">
            <i class="fas fa-arrow-left me-2"></i> Back to Students
        </a>
    </div>

    @php
        $name = $admission->contact->name ?? 'Unknown';
        $email = $admission->contact->email ?? $admission->contact->caller_number ?? 'No Email';
        $phone = $admission->contact->phone ?? $admission->contact->caller_number ?? 'No Phone';
        $initials = strtoupper(substr($name, 0, 2));
        $batchCode = $admission->batch->batch_code ?? 'Unassigned';
        $courseTitle = $admission->course->title ?? 'Unknown Course';
        $joined = $admission->created_at ? $admission->created_at->format('d M Y') : 'N/A';
        
        $status = strtolower($admission->status);
        $feeStatus = $feeDetails['fee_status'] ?? (($status == 'pending_fee') ? 'Pending' : 'Paid');
        $courseFees = $feeDetails['course_fees'] ?? (float) ($admission->course_fees ?? 0);
        $paidAmount = $feeDetails['paid_amount'] ?? 0;
        $dueAmount = $feeDetails['due_amount'] ?? 0;
        $paymentsList = $feeDetails['payments'] ?? collect();
    @endphp

    <div class="row">
        <!-- Left Column: Profile -->
        <div class="col-lg-3">
            <div class="profile-card mb-4">
                <div class="profile-header"></div>
                <div class="profile-avatar">{{ $initials }}</div>
                
                <div class="text-center mb-3 px-3">
                    <h5 class="fw-bold mb-1" style="color: #1e293b;">{{ $name }}</h5>
                    <p class="text-muted small mb-2 fw-semibold">STU{{ str_pad($admission->id, 3, '0', STR_PAD_LEFT) }} • <span style="color: #f59e0b;">{{ strtok($courseTitle, ' ') }}</span></p>
                    <span class="status-badge">Active</span>
                </div>
                
                <div class="info-list pb-3">
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                        <div class="info-value text-truncate" style="max-width: 140px;" title="{{ $email }}">{{ $email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-phone-alt"></i> Phone</div>
                        <div class="info-value">{{ $phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-layer-group"></i> Batch</div>
                        <div class="info-value">{{ $batchCode }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="far fa-calendar-alt"></i> Joined</div>
                        <div class="info-value">{{ $joined }}</div>
                    </div>
                    <div class="info-row" style="cursor: pointer; transition: background 0.15s;" onclick="switchStudentTab('fees')" title="Click to view fee breakdown and receipts">
                        <div class="info-label"><i class="fas fa-wallet"></i> Fee</div>
                        <div class="info-value d-flex align-items-center justify-content-end gap-1">
                            @if($feeStatus == 'Paid')
                                <span class="badge rounded-pill bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-check-circle me-1"></i> Paid
                                </span>
                            @elseif($feeStatus == 'Partial')
                                <span class="badge rounded-pill bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-clock me-1"></i> Partial
                                </span>
                            @else
                                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-exclamation-circle me-1"></i> Pending
                                </span>
                            @endif
                            <i class="fas fa-arrow-right small opacity-50 ms-1" style="font-size: 0.7rem;"></i>
                        </div>
                    </div>
                    <div class="info-row" style="cursor: pointer; transition: background 0.15s;" onclick="switchStudentTab('attendance')" title="Click to view detailed attendance history">
                        <div class="info-label"><i class="fas fa-chart-line"></i> Attendance</div>
                        <div class="info-value d-flex align-items-center justify-content-end gap-1" style="color: {{ $attendance >= 70 ? '#10b981' : ($attendance >= 50 ? '#f59e0b' : ($attendance > 0 ? '#ef4444' : '#64748b')) }};">
                            <span>{{ $attendance }}%</span>
                            <i class="fas fa-arrow-right small opacity-50 ms-1" style="font-size: 0.7rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column: LMS History -->
        <div class="col-lg-9">
            <div class="d-flex align-items-center mb-3">
                <h4 class="fw-bold mb-0 me-3" style="color: #1e293b;"><i class="fas fa-book-reader text-primary me-2"></i> LMS History</h4>
                <span class="badge rounded-pill bg-light text-primary border" style="font-size: 0.8rem; font-weight: 600;">{{ strtok($name, ' ') }}'s Progress</span>
            </div>
            
            <div class="lms-card">
                <!-- Nav Tabs -->
                <ul class="nav nav-tabs nav-tabs-custom mb-4" id="studentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">Overview</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="attendance-tab" data-bs-toggle="tab" data-bs-target="#attendance" type="button" role="tab">Attendance</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="fees-tab" data-bs-toggle="tab" data-bs-target="#fees" type="button" role="tab">Fees & Invoices</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">Assignments</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="miniprojects-tab" data-bs-toggle="tab" data-bs-target="#miniprojects" type="button" role="tab">Mini Projects</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="mainproject-tab" data-bs-toggle="tab" data-bs-target="#mainproject" type="button" role="tab">Main Project</button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="studentTabsContent">
                    <!-- OVERVIEW TAB -->
                    <div class="tab-pane fade show active" id="overview" role="tabpanel">
                        <!-- Top Summary Boxes -->
                        <div class="d-flex gap-3 mb-4 flex-wrap">
                            <div class="summary-box">
                                <h6>Course</h6>
                                <div class="val" style="color: #6366f1;">{{ $progress }}%</div>
                            </div>
                            <div class="summary-box" style="cursor: pointer; transition: all 0.2s;" onclick="switchStudentTab('attendance')" title="Click to view detailed attendance breakdown">
                                <h6>Attendance</h6>
                                <div class="val" style="color: {{ $attendance >= 70 ? '#10b981' : ($attendance >= 50 ? '#f59e0b' : ($attendance > 0 ? '#ef4444' : '#64748b')) }};">{{ $attendance }}%</div>
                                <div class="small mt-1 text-primary fw-semibold" style="font-size: 0.72rem;">View Breakdown <i class="fas fa-chevron-right ms-1"></i></div>
                            </div>
                            <div class="summary-box">
                                <h6>Modules</h6>
                                <div class="val" style="color: #0ea5e9;">{{ $modulesDone }}/{{ $modulesTotal }}</div>
                            </div>
                            <div class="summary-box" style="cursor: pointer; transition: all 0.2s;" onclick="switchStudentTab('fees')" title="Click to view fees and invoice breakdown">
                                <h6>Fee Status</h6>
                                <div class="val" style="color: {{ $feeStatus == 'Paid' ? '#10b981' : ($feeStatus == 'Partial' ? '#f59e0b' : '#ef4444') }};">{{ $feeStatus }}</div>
                                <div class="small mt-1 text-primary fw-semibold" style="font-size: 0.72rem;">₹{{ number_format($paidAmount) }} Paid <i class="fas fa-chevron-right ms-1"></i></div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-4 rounded-pill" style="height: 6px; background: #e2e8f0;">
                            <div class="progress-bar rounded-pill" style="width: {{ $progress }}%; background: linear-gradient(90deg, #6366f1, #a855f7);"></div>
                        </div>

                        <!-- Module Progress List -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3" style="color: #3b82f6;"><i class="fas fa-square me-2" style="font-size: 0.6rem; vertical-align: middle;"></i> Module Progress</h6>
                            
                            @foreach($modules as $index => $mod)
                                <div class="module-item">
                                    <div class="module-icon bg-{{ $index < $modulesDone ? 'primary' : 'secondary' }}"><i class="fas fa-{{ $index < $modulesDone ? 'check' : 'clock' }}"></i></div>
                                    <div class="module-name">{{ $mod->title ?? 'Module ' . ($index + 1) }}</div>
                                    <div class="module-status text-{{ $index < $modulesDone ? 'success' : 'muted' }}">{{ $index < $modulesDone ? 'Done' : 'Pending' }}</div>
                                </div>
                            @endforeach
                            @if($modulesTotal == 0)
                                <p class="text-muted small">No curriculum modules found.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- ATTENDANCE TAB -->
                    <div class="tab-pane fade" id="attendance" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 mt-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check text-success me-2 fs-5"></i> 
                                <h6 class="mb-0 fw-bold" style="color: #1e293b;">
                                    Batch Attendance History
                                    <span class="text-muted fw-normal small ms-2">({{ $batchCode }} • {{ $courseTitle }})</span>
                                </h6>
                            </div>
                            <span class="badge rounded-pill bg-light text-dark border px-3 py-1">
                                Overall Attendance: <strong style="color: {{ $attendance >= 70 ? '#10b981' : ($attendance >= 50 ? '#f59e0b' : ($attendance > 0 ? '#ef4444' : '#64748b')) }}">{{ $attendance }}%</strong>
                            </span>
                        </div>

                        <!-- Metric cards row -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-primary"><i class="fas fa-calendar-day"></i></div>
                                    <div class="val">{{ $attendanceTotalClasses }}</div>
                                    <div class="lbl">Classes Held</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-success"><i class="fas fa-user-check"></i></div>
                                    <div class="val">{{ $attendancePresentCount }}</div>
                                    <div class="lbl">Full Present</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-warning"><i class="fas fa-adjust"></i></div>
                                    <div class="val">{{ $attendanceHalfDayCount }}</div>
                                    <div class="lbl">Half Days</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-danger"><i class="fas fa-user-times"></i></div>
                                    <div class="val">{{ $attendanceLeaveCount }}</div>
                                    <div class="lbl">Leaves / Missed</div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Attendance Breakdown (Calendar Grid) -->
                        @if(isset($attendanceCalendar) && count($attendanceCalendar['days']) > 0)
                            <div class="card border rounded-3 p-3 mb-4 bg-white shadow-sm">
                                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                    <div class="fw-bold text-dark d-flex align-items-center" style="font-size: 0.9rem;">
                                        <i class="far fa-calendar-alt text-primary me-2"></i> {{ $attendanceCalendar['month_name'] }} Attendance Calendar
                                    </div>
                                    <div class="d-flex align-items-center gap-3 small flex-wrap" style="font-size: 0.75rem;">
                                        <span class="d-flex align-items-center gap-1"><span class="badge bg-success p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Present</span>
                                        <span class="d-flex align-items-center gap-1"><span class="badge bg-warning p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Half Day</span>
                                        <span class="d-flex align-items-center gap-1"><span class="badge bg-danger p-1 rounded-circle" style="width: 8px; height: 8px;"></span> Leave/Missed</span>
                                        <span class="d-flex align-items-center gap-1"><span class="badge bg-light border p-1 rounded-circle" style="width: 8px; height: 8px;"></span> No Session</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($attendanceCalendar['days'] as $cDay)
                                        @php
                                            $dayStatus = $cDay['status'];
                                            $dayPerc = (float) $cDay['percentage'];
                                            $bg = '#f8fafc';
                                            $textColor = '#94a3b8';
                                            $border = '#e2e8f0';
                                            $title = $cDay['date'] . ': No Class Scheduled';

                                            if ($dayStatus == 'Present') {
                                                $bg = '#ecfdf5';
                                                $textColor = '#059669';
                                                $border = '#a7f3d0';
                                                $title = $cDay['date'] . ': Marked Present (' . round($dayPerc) . '%)';
                                            } elseif ($dayStatus == 'Half Day') {
                                                $bg = '#fffbeb';
                                                $textColor = '#d97706';
                                                $border = '#fde68a';
                                                $title = $cDay['date'] . ': Half Day (' . round($dayPerc) . '%)';
                                            } elseif ($dayStatus == 'Leave') {
                                                $bg = '#fef2f2';
                                                $textColor = '#dc2626';
                                                $border = '#fecaca';
                                                $title = $cDay['date'] . ': Leave / Absent (' . round($dayPerc) . '%)';
                                            } elseif ($dayStatus == 'Future') {
                                                $bg = '#ffffff';
                                                $textColor = '#cbd5e1';
                                                $border = '#f1f5f9';
                                                $title = $cDay['date'] . ': Upcoming';
                                            }
                                        @endphp
                                        <div class="text-center rounded-2 p-1" 
                                            title="{{ $title }}"
                                            style="flex: 1 0 38px; min-width: 38px; height: 48px; background: {{ $bg }}; border: 1px solid {{ $border }}; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.15s;">
                                            <div style="font-size: 0.72rem; font-weight: 700; color: {{ $textColor }};">{{ $cDay['day'] }}</div>
                                            @if($dayStatus == 'Present')
                                                <i class="fas fa-check" style="font-size: 0.65rem; color: #059669;"></i>
                                            @elseif($dayStatus == 'Half Day')
                                                <i class="fas fa-adjust" style="font-size: 0.65rem; color: #d97706;"></i>
                                            @elseif($dayStatus == 'Leave')
                                                <i class="fas fa-times" style="font-size: 0.65rem; color: #dc2626;"></i>
                                            @else
                                                <span style="font-size: 0.6rem; color: {{ $textColor }};">•</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Attendance Table -->
                        @if($attendanceRecords->count() > 0)
                            <div class="table-responsive border rounded-3 overflow-hidden">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        <tr>
                                            <th class="ps-3 py-3">Date</th>
                                            <th class="py-3">Session / Schedule</th>
                                            <th class="py-3 text-center">Class Duration</th>
                                            <th class="py-3 text-center">Attended Time</th>
                                            <th class="py-3 text-center">Attendance %</th>
                                            <th class="py-3 text-end pe-3">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendanceRecords as $att)
                                            @php
                                                $attPerc = (float) $att->attendance_percentage;
                                                $statusColor = 'success';
                                                $statusIcon = 'check-circle';
                                                if ($att->status == 'Half Day') {
                                                    $statusColor = 'warning';
                                                    $statusIcon = 'adjust';
                                                } elseif ($att->status == 'Leave' || $attPerc < 45) {
                                                    $statusColor = 'danger';
                                                    $statusIcon = 'times-circle';
                                                }
                                                $schedTime = $att->batchSchedule ? $att->batchSchedule->start_time . ' - ' . $att->batchSchedule->end_time : 'Live Class';
                                            @endphp
                                            <tr>
                                                <td class="ps-3 fw-bold text-dark">
                                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                                    {{ \Carbon\Carbon::parse($att->date)->format('d M Y') }}
                                                </td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $schedTime }}</div>
                                                    @if($att->remarks)
                                                        <div class="text-muted small" style="font-size: 0.75rem;">{{ $att->remarks }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $att->meeting_duration_minutes > 0 ? $att->meeting_duration_minutes . ' mins' : '—' }}
                                                </td>
                                                <td class="text-center fw-semibold text-dark">
                                                    {{ $att->attended_duration_minutes > 0 ? $att->attended_duration_minutes . ' mins' : '0 mins' }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold" style="color: {{ $attPerc >= 70 ? '#10b981' : ($attPerc >= 45 ? '#f59e0b' : '#ef4444') }}">
                                                        {{ round($attPerc) }}%
                                                    </span>
                                                </td>
                                                <td class="text-end pe-3">
                                                    <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25 px-2 py-1 rounded-pill">
                                                        <i class="fas fa-{{ $statusIcon }} me-1"></i> {{ $att->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted border rounded-3 bg-light bg-opacity-50">
                                <i class="fas fa-calendar-times fa-3x mb-3 text-secondary opacity-40"></i>
                                <h6 class="fw-bold text-dark mb-1">No attendance records logged yet</h6>
                                <p class="small text-muted mb-0">Live Zoom classes and attendance records for batch {{ $batchCode }} will automatically appear here once sessions conclude.</p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- FEES & INVOICES TAB -->
                    <div class="tab-pane fade" id="fees" role="tabpanel">
                        <div class="d-flex align-items-center justify-content-between mb-3 mt-3 flex-wrap gap-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-wallet text-success me-2 fs-5"></i> 
                                <h6 class="mb-0 fw-bold" style="color: #1e293b;">
                                    Course Fee & Payment Breakdown
                                    <span class="text-muted fw-normal small ms-2">({{ $courseTitle }})</span>
                                </h6>
                            </div>
                            <span class="badge rounded-pill bg-{{ $feeStatus == 'Paid' ? 'success' : ($feeStatus == 'Partial' ? 'warning' : 'danger') }} bg-opacity-10 text-{{ $feeStatus == 'Paid' ? 'success' : ($feeStatus == 'Partial' ? 'warning' : 'danger') }} border px-3 py-1 fw-bold">
                                Status: {{ $feeStatus }}
                            </span>
                        </div>

                        <!-- Metric cards row -->
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-primary"><i class="fas fa-receipt"></i></div>
                                    <div class="val text-primary" style="font-size: 1.25rem;">₹{{ number_format($courseFees) }}</div>
                                    <div class="lbl">Total Course Fee</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-success"><i class="fas fa-check-circle"></i></div>
                                    <div class="val text-success" style="font-size: 1.25rem;">₹{{ number_format($paidAmount) }}</div>
                                    <div class="lbl">Total Paid</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon {{ $dueAmount > 0 ? 'text-danger' : 'text-muted' }}"><i class="fas fa-hourglass-half"></i></div>
                                    <div class="val {{ $dueAmount > 0 ? 'text-danger' : 'text-muted' }}" style="font-size: 1.25rem;">₹{{ number_format($dueAmount) }}</div>
                                    <div class="lbl">Due Balance</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="metric-card">
                                    <div class="icon text-info"><i class="fas fa-credit-card"></i></div>
                                    <div class="val text-dark" style="font-size: 1rem; font-weight: 700;">{{ $admission->payment_mode ?? 'Full Payment' }}</div>
                                    <div class="lbl">Payment Mode</div>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Table -->
                        @if($paymentsList->count() > 0)
                            <div class="table-responsive border rounded-3 overflow-hidden">
                                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                                    <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">
                                        <tr>
                                            <th class="ps-3 py-3">Receipt / Payment ID</th>
                                            <th class="py-3">Payment Date</th>
                                            <th class="py-3">Payment Method</th>
                                            <th class="py-3">Transaction Reference</th>
                                            <th class="py-3 text-center">Amount</th>
                                            <th class="py-3 text-center">Status</th>
                                            <th class="py-3 text-end pe-3">Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paymentsList as $p)
                                            @php
                                                $pStatus = strtolower($p->status);
                                                $isPaid = in_array($pStatus, ['paid', 'completed', 'success']);
                                            @endphp
                                            <tr>
                                                <td class="ps-3 fw-bold text-dark">
                                                    <i class="fas fa-file-invoice-dollar text-muted me-1"></i>
                                                    PAY-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}
                                                </td>
                                                <td>
                                                    <span class="text-dark">{{ \Carbon\Carbon::parse($p->payment_date ?? $p->created_at)->format('d M Y') }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1">{{ ucfirst($p->payment_method ?? 'Online') }}</span>
                                                </td>
                                                <td>
                                                    <code class="text-muted small" style="font-size: 0.75rem;">{{ $p->razorpay_payment_id ?? $p->razorpay_payment_link_id ?? 'Direct / Manual' }}</code>
                                                </td>
                                                <td class="text-center fw-bold text-dark">
                                                    ₹{{ number_format($p->amount, 2) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-{{ $isPaid ? 'success' : 'warning' }} bg-opacity-10 text-{{ $isPaid ? 'success' : 'warning' }} border border-{{ $isPaid ? 'success' : 'warning' }} border-opacity-25 px-2 py-1 rounded-pill">
                                                        <i class="fas fa-{{ $isPaid ? 'check-circle' : 'clock' }} me-1"></i> {{ ucfirst($p->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-end pe-3">
                                                    @if($isPaid)
                                                        <a href="{{ route('payments.invoice', $p->id) }}" class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill fw-semibold shadow-sm" target="_blank" title="Download Tax Invoice / Receipt">
                                                            <i class="fas fa-download me-1"></i> Invoice
                                                        </a>
                                                    @else
                                                        <span class="text-muted small">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted border rounded-3 bg-light bg-opacity-50">
                                <i class="fas fa-receipt fa-3x mb-3 text-secondary opacity-40"></i>
                                <h6 class="fw-bold text-dark mb-1">No payment transactions recorded</h6>
                                <p class="small text-muted mb-0">Payments recorded for this admission will appear here with downloadable invoices.</p>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade" id="assignments" role="tabpanel">
                        <div class="d-flex align-items-center mb-3 mt-3">
                            <i class="fas fa-clipboard-list text-primary me-2"></i> <h6 class="mb-0 fw-bold" style="color: #1e293b;">Assignment Submissions</h6>
                        </div>
                        @if($assignmentsList->count() > 0)
                            @foreach($assignmentsList as $item)
                                @php
                                    $sub = $assignmentSubmissions->get($item->id);
                                    $score = $sub && $sub->status == 'evaluated' ? (float)$sub->score : 0;
                                    $max = $item->max_score ?? 100;
                                    $perc = $max > 0 ? ($score / $max) * 100 : 0;
                                    $cardClass = 'pending';
                                    $subTitle = 'Pending';
                                    if ($sub) {
                                        if ($sub->status == 'evaluated') {
                                            $cardClass = $perc >= 70 ? 'success' : ($perc >= 50 ? 'warning' : 'danger');
                                            $subTitle = 'Submitted: ' . $sub->created_at->format('d M Y');
                                        } else {
                                            $cardClass = 'warning';
                                            $subTitle = 'Submitted: Pending Review';
                                        }
                                    }
                                @endphp
                                <div class="submission-card {{ $cardClass }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #1e293b;">{{ $item->title }}</h6>
                                            <p class="text-muted small mb-0">{{ $subTitle }}</p>
                                        </div>
                                        <div class="text-end">
                                            @if($sub && $sub->status == 'evaluated')
                                                <span class="score-large text-{{ $cardClass }}">{{ $score }}</span><span class="score-max">/{{ $max }}</span>
                                            @else
                                                <span class="badge bg-{{ $sub ? 'warning' : 'secondary' }} text-{{ $sub ? 'dark' : 'light' }}"><i class="fas fa-{{ $sub ? 'hourglass-half' : 'hourglass' }} me-1"></i> {{ $sub ? 'Pending Evaluation' : 'Pending' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($sub && $sub->status == 'evaluated')
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-{{ $cardClass }}" style="width: {{ $perc }}%"></div>
                                        </div>
                                        @if($sub->feedback)
                                            <div class="feedback-box {{ $cardClass == 'success' ? '' : $cardClass }}">
                                                <i class="far fa-comment-dots"></i> {{ $sub->feedback }}
                                            </div>
                                        @endif
                                    @endif
                                    
                                    @if($sub && $sub->attempts > 1)
                                        <div class="text-muted small mt-2"><i class="fas fa-history me-1"></i> Attempts: {{ $sub->attempts }}</div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-5">No assignments found for this course.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="miniprojects" role="tabpanel">
                        <div class="d-flex align-items-center mb-3 mt-3">
                            <i class="fas fa-project-diagram text-primary me-2"></i> <h6 class="mb-0 fw-bold" style="color: #1e293b;">Mini Projects</h6>
                        </div>
                        @if($miniProjectsList->count() > 0)
                            @foreach($miniProjectsList as $item)
                                @php
                                    $sub = $miniProjectSubmissions->get($item->id);
                                    $score = $sub && $sub->status == 'evaluated' ? (float)$sub->score : 0;
                                    $max = $item->max_score ?? 100;
                                    $perc = $max > 0 ? ($score / $max) * 100 : 0;
                                    $cardClass = 'pending';
                                    $subTitle = 'Pending';
                                    if ($sub) {
                                        if ($sub->status == 'evaluated') {
                                            $cardClass = $perc >= 70 ? 'success' : ($perc >= 50 ? 'warning' : 'danger');
                                            $subTitle = 'Submitted: ' . $sub->created_at->format('d M Y');
                                        } else {
                                            $cardClass = 'warning';
                                            $subTitle = 'Submitted: Pending Review';
                                        }
                                    }
                                @endphp
                                <div class="submission-card {{ $cardClass }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #1e293b;">{{ $item->title }}</h6>
                                            <p class="text-muted small mb-0">{{ $subTitle }}</p>
                                        </div>
                                        <div class="text-end">
                                            @if($sub && $sub->status == 'evaluated')
                                                <div style="line-height: 1.1;">
                                                    <span class="score-large text-{{ $cardClass }}">{{ $perc }}%</span><br>
                                                    <span class="text-{{ $cardClass }} fw-bold" style="font-size: 0.8rem;"><i class="fas fa-{{ $perc >= 70 ? 'check' : 'exclamation-triangle' }}"></i> {{ $perc >= 70 ? 'Completed' : 'Changes Needed' }}</span>
                                                </div>
                                            @else
                                                <span class="badge bg-{{ $sub ? 'warning' : 'secondary' }} text-{{ $sub ? 'dark' : 'light' }}"><i class="fas fa-{{ $sub ? 'hourglass-half' : 'hourglass' }} me-1"></i> {{ $sub ? 'Pending Evaluation' : 'Pending' }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($sub && $sub->status == 'evaluated')
                                        <div class="progress progress-thin">
                                            <div class="progress-bar bg-{{ $cardClass }}" style="width: {{ $perc }}%"></div>
                                        </div>
                                        @if($sub->feedback)
                                            <div class="feedback-box {{ $cardClass == 'success' ? '' : $cardClass }}">
                                                <i class="far fa-comment-dots"></i> {{ $sub->feedback }}
                                            </div>
                                        @endif
                                    @endif
                                    
                                    @if($sub && $sub->attempts > 1)
                                        <div class="text-muted small mt-2"><i class="fas fa-history me-1"></i> Attempts: {{ $sub->attempts }}</div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-5">No mini projects found for this course.</p>
                        @endif
                    </div>
                    <div class="tab-pane fade" id="mainproject" role="tabpanel">
                        <div class="d-flex align-items-center mb-3 mt-3">
                            <i class="fas fa-laptop-code text-primary me-2"></i> <h6 class="mb-0 fw-bold" style="color: #1e293b;">Main Project</h6>
                        </div>
                        @if($mainProjectsList->count() > 0)
                            @foreach($mainProjectsList as $item)
                                @php
                                    $subs = $mainProjectSubmissions->get($item->id, collect());
                                    $totalReviews = $item->required_reviews ?? 3;
                                    $completedReviews = $subs->where('status', 'evaluated')->count();
                                @endphp
                                <div class="submission-card">
                                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                                        <h5 class="fw-bold mb-0" style="color: #1e293b;"><i class="fas fa-boxes me-2"></i>{{ $item->title }}</h5>
                                        <span class="badge bg-primary rounded-pill px-3 py-2 text-white">Review {{ $completedReviews }} / {{ $totalReviews }}</span>
                                    </div>
                                    
                                    @for($i = 1; $i <= $totalReviews; $i++)
                                        @php
                                            $attempt = $subs->where('review_number', $i)->first();
                                            $revScore = $attempt && $attempt->status == 'evaluated' ? (float)$attempt->score : null;
                                            $revMax = $item->max_score ?? 100;
                                            $revPerc = ($revScore !== null && $revMax > 0) ? ($revScore / $revMax) * 100 : 0;
                                            $revClass = 'secondary';
                                            $revStatus = 'Pending';
                                            
                                            if ($attempt) {
                                                if ($attempt->status == 'evaluated') {
                                                    $revClass = $revPerc >= 70 ? 'success' : ($revPerc >= 50 ? 'warning' : 'danger');
                                                    $revStatus = 'Completed';
                                                } else {
                                                    $revClass = 'warning';
                                                    $revStatus = 'Pending Evaluation';
                                                }
                                            }
                                            
                                            $revDate = $attempt ? \Carbon\Carbon::parse($attempt->updated_at)->format('M j') : '';
                                        @endphp
                                        <div class="review-row mb-4">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-bold" style="font-size: 0.9rem; color: #334155;">Review {{ $i }} 
                                                    @if($revDate) <span class="text-muted fw-normal">· {{ $revDate }} · </span> @endif 
                                                    <span class="text-{{ $revClass }}"><i class="fas fa-{{ $revClass == 'secondary' ? 'hourglass-half' : ($revClass == 'warning' ? 'hourglass-half' : 'check') }}"></i> {{ $revStatus }}</span>
                                                </span>
                                                @if($revClass != 'secondary' && $revClass != 'warning')
                                                    <span class="fw-bold text-{{ $revClass }}">{{ (float)$revPerc }}%</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                            <div class="progress progress-thin" style="height: 10px;">
                                                <div class="progress-bar bg-{{ $revClass }}" style="width: {{ ($revClass == 'secondary' || $revClass == 'warning') ? 0 : $revPerc }}%"></div>
                                            </div>
                                            @if($attempt && $attempt->feedback)
                                                <p class="text-muted small mt-2 mb-0">{{ $attempt->feedback }}</p>
                                            @endif
                                        </div>
                                    @endfor
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-5">No main projects found for this course.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bottom Metrics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md">
                    <div class="metric-card">
                        <div class="icon"><i class="fas fa-chart-bar" style="color: #10b981;"></i></div>
                        <h6>Course Progress</h6>
                        <div class="val">{{ $progress }}%</div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="metric-card" style="cursor: pointer; transition: all 0.2s;" onclick="switchStudentTab('attendance')" title="Click to view detailed attendance history">
                        <div class="icon"><i class="fas fa-calendar-alt" style="color: #3b82f6;"></i></div>
                        <h6>Attendance</h6>
                        <div class="val" style="color: {{ $attendance >= 70 ? '#10b981' : ($attendance >= 50 ? '#f59e0b' : ($attendance > 0 ? '#ef4444' : '#64748b')) }};">{{ $attendance }}%</div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="metric-card">
                        <div class="icon"><i class="fas fa-book" style="color: #0ea5e9;"></i></div>
                        <h6>Modules Done</h6>
                        <div class="val" style="color: #0ea5e9;">{{ $modulesDone }}/{{ $modulesTotal }}</div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="metric-card">
                        <div class="icon"><i class="fas fa-clipboard-list" style="color: #f59e0b;"></i></div>
                        <h6>Assignments</h6>
                        <div class="val" style="color: #a855f7;">{{ $assignmentsSubmitted }}/{{ $assignmentsCount }}</div>
                    </div>
                </div>
                <div class="col-md">
                    <div class="metric-card">
                        <div class="icon"><i class="fas fa-project-diagram" style="color: #ef4444;"></i></div>
                        <h6>Mini Projects</h6>
                        <div class="val" style="color: #ef4444;">{{ $miniProjectsSubmitted }}/{{ $miniProjectsCount }}</div>
                    </div>
                </div>
            </div>

            <!-- Sessions Bottom -->
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="metric-card">
                        <h6 class="text-dark fw-bold mb-3 text-capitalize"><i class="fas fa-bullseye text-danger me-2"></i> Demo Session</h6>
                        @if($demos->count() > 0)
                            @foreach($demos->take(2) as $demo)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($demo->date . ' ' . $demo->time)->format('d M Y — g:i A') }}</span>
                                    <span class="badge bg-{{ $demo->status == 'completed' ? 'success' : 'primary' }}">{{ ucfirst($demo->status) }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small mb-0">No demo scheduled</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric-card">
                        <h6 class="text-dark fw-bold mb-3 text-capitalize"><i class="far fa-comment-dots text-primary me-2"></i> Doubt Session</h6>
                        @if($doubts->count() > 0)
                            @foreach($doubts->take(2) as $doubt)
                                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                    <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($doubt->date . ' ' . $doubt->time)->format('d M Y — g:i A') }}</span>
                                    <span class="badge bg-{{ $doubt->status == 'completed' ? 'success' : 'info' }}">{{ ucfirst($doubt->status) }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted small mb-0">No doubt sessions scheduled</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchStudentTab(tabId) {
        const triggerEl = document.querySelector('#studentTabs button[data-bs-target="#' + tabId + '"]');
        if (triggerEl) {
            const tab = bootstrap.Tab.getOrCreateInstance(triggerEl);
            tab.show();
            triggerEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Check URL parameter to switch tab automatically
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab');
        if (activeTab) {
            switchStudentTab(activeTab);
        }
    });
</script>
@endpush
