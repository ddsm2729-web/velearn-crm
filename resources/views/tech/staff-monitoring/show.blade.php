@extends('layouts.master')

@section('content')
    <div class="container-fluid py-2">
        <div class="mb-3">
            <a href="{{ route('tech.staff-monitoring.index') }}" class="text-decoration-none text-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Staff Monitor
            </a>
        </div>

        @php
            $name = $user->name ?? ($staff ? $staff->name : 'Unknown');
            $initials = collect(explode(' ', $name))->map(function ($segment) {
                return strtoupper(substr($segment, 0, 1));
            })->join('');
            $initials = substr($initials, 0, 2);

            $courseName = 'N/A';
            if ($batches->isNotEmpty()) {
                $courseName = $batches->first()->course->title ?? 'N/A';
            }

            $batchesCount = $batches->count();
            $studentsCount = $batches->sum('joined_student_count');
            $demosCount = $pendingDemos->count();
            $doubtsCount = $pendingDoubts->count();

            $isActive = ($staff && $staff->status == 1);
            if ($staff && !is_numeric($staff->status)) {
                $isActive = strtolower($staff->status) == 'active' || $staff->status === null;
            }
            $status_text = $isActive ? 'Active' : 'Inactive';
            $status_color = $isActive ? 'success' : 'danger';
        @endphp

        <div class="row g-3">
            <!-- Left Column: Profile -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body p-4 d-flex flex-column align-items-center text-center">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white mb-3 shadow-sm"
                            style="width: 110px; height: 110px; font-size: 2.8rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-weight: bold;">
                            {{ $initials }}
                        </div>

                        <h4 class="fw-bold mb-1">{{ $name }}</h4>
                        <p class="text-muted mb-3">{{ $user->roles->first()->name ?? 'Technical Staff' }}</p>

                        <div class="badge bg-primary bg-opacity-10 text-primary border border-primary mb-4 px-3 py-2 rounded-pill text-truncate" style="max-width: 100%;">
                            <i class="fas fa-bolt me-1"></i> {{ $courseName }}
                        </div>

                        <div class="row w-100 mb-4 g-2">
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-primary mb-1">{{ $batchesCount }}</h3>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Batches</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-primary mb-1">{{ $studentsCount }}</h3>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Students</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-warning mb-1">{{ $demosCount }}</h3>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Demos</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded-3 border h-100 d-flex flex-column justify-content-center">
                                    <h3 class="fw-bold text-success mb-1">{{ $doubtsCount }}</h3>
                                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Doubts</small>
                                </div>
                            </div>
                        </div>

                        <div class="w-100 text-start border-top pt-4 mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded text-center me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Email Address</small>
                                    <div class="fw-medium text-dark text-truncate" title="{{ $user->email }}">{{ $user->email }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded text-center me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-phone-alt text-primary"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Phone Number</small>
                                    <div class="fw-medium text-dark text-truncate">{{ $staff->primary_phone ?? $user->phonenumber ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-light rounded text-center me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-calendar-alt text-primary"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Joined Date</small>
                                    <div class="fw-medium text-dark text-truncate">{{ $staff && $staff->created_at ? $staff->created_at->format('j M Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded text-center me-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    <i class="fas fa-check-circle text-primary"></i>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <small class="d-block text-muted text-uppercase fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">Account Status</small>
                                    <div class="fw-bold text-{{ $status_color }} text-truncate">{{ $status_text }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="w-100 d-grid gap-2 mt-auto">
                            <button onclick="openScheduleMeetingModal()"
                                class="btn btn-primary rounded-3 py-2 fw-bold text-decoration-none shadow-sm"><i class="fas fa-plus me-1"></i>
                                Schedule 1:1</button>
                            <a href="tel:{{ $staff->primary_phone ?? $user->phonenumber ?? '' }}"
                                class="btn btn-outline-dark rounded-3 py-2 fw-bold text-decoration-none"><i
                                    class="fas fa-phone-alt me-1"></i> Connect Staff</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="col-lg-8">
                <div class="d-flex flex-column gap-3">

                    <!-- Active Batches -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div
                            class="card-header bg-white border-bottom-0 pt-2 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-layer-group text-warning me-2"></i> Active Batches
                            </h5>
                            <span class="badge bg-light text-dark border rounded-pill">{{ $batchesCount }}</span>
                        </div>
                        <div class="card-body">
                            @forelse($batches as $batch)
                                <div
                                    class="d-flex align-items-center justify-content-between p-2 border rounded-3 mb-2 bg-light">
                                    <div>
                                        <h6 class="fw-bold mb-1">{{ $batch->batch_code }} • <span
                                                class="text-primary">{{ $batch->course->title ?? 'Unknown' }}</span></h6>
                                        <p class="text-muted small mb-0">{{ $batch->batch_track ?? 'Normal' }} •
                                            {{ $batch->start_date ? \Carbon\Carbon::parse($batch->start_date)->format('d M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="text-end" style="width: 150px;">
                                        @php 
                                            $totalDays = \Carbon\Carbon::parse($batch->start_date)->diffInDays(\Carbon\Carbon::parse($batch->end_date)) ?: 1;
                                            $passedDays = \Carbon\Carbon::parse($batch->start_date)->diffInDays(now(), false);
                                            $progress = max(0, min(100, round(($passedDays / $totalDays) * 100)));
                                        @endphp
                                        <div class="d-flex justify-content-between mb-1 small fw-bold">
                                            <span>Progress</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $progress }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center my-3">No active batches assigned.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pending Demos -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div
                            class="card-header bg-white border-bottom-0 pt-2 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-bullseye text-danger me-2"></i> Pending Demos</h5>
                            <span class="badge bg-light text-dark border rounded-pill">{{ $demosCount }}</span>
                        </div>
                        <div class="card-body">
                            @forelse($pendingDemos as $demo)
                                @php
                                    $demoName = $demo->admission->contact->name ?? 'Unknown';
                                    $demoInitials = strtoupper(substr($demoName, 0, 2));
                                @endphp
                                <div
                                    class="d-flex align-items-center justify-content-between p-2 border rounded-3 mb-2 bg-white shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-primary me-3"
                                            style="width: 45px; height: 45px; font-weight: bold;">
                                            {{ $demoInitials }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $demoName }}</h6>
                                            <p class="text-muted small mb-0">
                                                {{ $demo->admission->contact->phone ?? 'No Phone' }} •
                                                {{ $demo->admission->course->title ?? 'N/A' }} •
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($demo->time)->format('h:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3"><i
                                                class="fas fa-calendar-check me-1"></i> Demo Booked</button>
                                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3"><i
                                                class="fas fa-phone-alt me-1"></i> Connect</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center my-3">No pending demos found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Pending Doubts -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div
                            class="card-header bg-white border-bottom-0 pt-2 pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-comment-dots text-purple me-2"
                                    style="color: #8b5cf6;"></i> Pending Doubts</h5>
                            <span class="badge bg-light text-dark border rounded-pill">{{ $doubtsCount }}</span>
                        </div>
                        <div class="card-body">
                            @forelse($pendingDoubts as $doubt)
                                @php
                                    $doubtName = $doubt->admission->contact->name ?? 'Unknown';
                                    $doubtInitials = strtoupper(substr($doubtName, 0, 2));
                                @endphp
                                <div
                                    class="d-flex align-items-center justify-content-between p-2 border rounded-3 mb-2 bg-white shadow-sm">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3"
                                            style="width: 45px; height: 45px; background-color: #8b5cf6; font-weight: bold;">
                                            {{ $doubtInitials }}
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0">{{ $doubtName }}</h6>
                                            <p class="text-muted small mb-0">
                                                {{ $doubt->admission->contact->phone ?? 'No Phone' }} •
                                                {{ $doubt->admission->course->title ?? 'N/A' }} •
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($doubt->time)->format('h:i A') }}
                                            </p>
                                            @if($doubt->topic)
                                                <p class="text-muted small mb-0 mt-1 fst-italic">{{ $doubt->topic }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-purple rounded-pill px-3"
                                            style="color: #8b5cf6; border-color: #8b5cf6;"><i class="fas fa-comment me-1"></i>
                                            Doubt Booked</button>
                                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3"><i
                                                class="fas fa-phone-alt me-1"></i> Connect</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center my-3">No pending doubts found.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Performance Progress -->
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-header bg-white border-bottom-0 pt-2 pb-0">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line text-success me-2"></i> Performance
                                Progress</h5>
                        </div>
                        <div class="card-body pb-4">
                            @php
                                $sylCov = $batchesCount > 0 ? collect($batches)->map(function($b) {
                                    $t = \Carbon\Carbon::parse($b->start_date)->diffInDays(\Carbon\Carbon::parse($b->end_date)) ?: 1;
                                    $p = \Carbon\Carbon::parse($b->start_date)->diffInDays(now(), false);
                                    return max(0, min(100, round(($p / $t) * 100)));
                                })->avg() : 0;
                                $sylCov = round($sylCov);
                                
                                // Dynamic placeholder logic if real scores are missing
                                $studentIds = \App\Models\Admission::whereIn('batch_id', $batches->pluck('id'))->pluck('contact_id')->filter()->toArray();
                                $avgScore = 0;
                                if (!empty($studentIds)) {
                                    $avgScore = round(\App\Models\MiniProjectSubmission::whereIn('user_id', $studentIds)->avg('score') ?? 0);
                                }
                                
                                $avgAtt = 0; // Real attendance requires attendance table
                            @endphp

                            <!-- Metric 1 -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-medium text-dark"><i class="fas fa-book-open text-primary me-2"></i>
                                        Syllabus Coverage</span>
                                    <span class="fw-bold">{{ $sylCov }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary rounded-pill" role="progressbar"
                                        style="width: {{ $sylCov }}%;"></div>
                                </div>
                            </div>

                            <!-- Metric 2 -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-medium text-dark"><i class="fas fa-star text-warning me-2"></i> Avg
                                        Student Score</span>
                                    <span class="fw-bold">{{ $avgScore }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning rounded-pill" role="progressbar"
                                        style="width: {{ $avgScore }}%;"></div>
                                </div>
                            </div>

                            <!-- Metric 3 -->
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-medium text-dark"><i class="fas fa-user-check text-success me-2"></i>
                                        Avg Attendance</span>
                                    <span class="fw-bold">{{ $avgAtt }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-success rounded-pill" role="progressbar"
                                        style="width: {{ $avgAtt }}%;"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .swal-custom-popup {
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
    }
    .swal2-close {
        color: #64748b !important;
    }
    .swal2-close:hover {
        color: #0f172a !important;
    }
    .swal2-actions {
        margin-top: 1.5em !important;
    }
</style>
@endpush

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openScheduleMeetingModal() {
        Swal.fire({
            title: '<div style="text-align: left; font-weight: bold; font-family: \'Outfit\', sans-serif; color: #1e293b;">Schedule Staff Meeting<div style="font-size: 0.8rem; color: #64748b; font-weight: normal; margin-top: 5px;">Connect with staff or group</div></div>',
            html: `
                <div style="text-align: left; font-family: 'Inter', sans-serif;">
                    <div class="mb-3">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Meeting Type</label>
                        <div class="d-flex gap-2">
                            <input type="hidden" id="meetingTypeInput" value="Staff Meeting">
                            <button type="button" id="btnStaffMeeting" onclick="document.getElementById('meetingTypeInput').value='Staff Meeting'; this.style.background='#eff6ff'; this.style.color='#2563eb'; this.style.border='1px solid #3b82f6'; document.getElementById('btnGroupMeeting').style.background='#f8fafc'; document.getElementById('btnGroupMeeting').style.color='#64748b'; document.getElementById('btnGroupMeeting').style.border='1px solid #e2e8f0'; document.getElementById('staffSelectContainer').style.display='block'; document.getElementById('groupSelectContainer').style.display='none';" class="btn flex-fill text-start" style="background: #eff6ff; border: 1px solid #3b82f6; color: #2563eb; font-weight: 600; border-radius: 6px;"><i class="fas fa-user-friends me-2"></i> Staff Meeting</button>
                            <button type="button" id="btnGroupMeeting" onclick="document.getElementById('meetingTypeInput').value='Group Meeting'; this.style.background='#eff6ff'; this.style.color='#2563eb'; this.style.border='1px solid #3b82f6'; document.getElementById('btnStaffMeeting').style.background='#f8fafc'; document.getElementById('btnStaffMeeting').style.color='#64748b'; document.getElementById('btnStaffMeeting').style.border='1px solid #e2e8f0'; document.getElementById('staffSelectContainer').style.display='none'; document.getElementById('groupSelectContainer').style.display='block';" class="btn flex-fill text-start" style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; font-weight: 600; border-radius: 6px;"><i class="fas fa-users me-2"></i> Group Meeting</button>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Heading / Title <span class="text-danger">*</span></label>
                        <input type="text" id="meetingTitle" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" placeholder="e.g. Weekly Curriculum Sync &middot; Mid-Course Review">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Date <span class="text-danger">*</span></label>
                            <input type="date" id="meetingDate" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-6">
                            <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Time <span class="text-danger">*</span></label>
                            <input type="time" id="meetingTime" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" value="10:00">
                        </div>
                    </div>

                    <div class="mb-3" id="staffSelectContainer">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Staff Member</label>
                        <select id="meetingStaff" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" disabled>
                            <option value="{{ $user->id }}" selected>{{ addslashes($name) }} ({{ addslashes($courseName) }})</option>
                        </select>
                    </div>

                    <div class="mb-3" id="groupSelectContainer" style="display: none;">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Select Batch / Group</label>
                        <select id="meetingGroup" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;">
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}">{{ addslashes($batch->batch_code ?? 'Batch #'.$batch->id) }} &bull; {{ addslashes($batch->course->title ?? '') }}</option>
                            @endforeach
                            @if($batches->count() == 0)
                                <option value="" disabled selected>No active batches assigned</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Agenda / Notes</label>
                        <textarea id="meetingAgenda" class="form-control" rows="3" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" placeholder="What will be discussed? Preparation required?"></textarea>
                    </div>
                    
                    <div class="mb-1">
                        <label style="font-size: 0.75rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px;">Meeting Link (Optional)</label>
                        <input type="text" id="meetingLink" class="form-control" style="background: #ffffff; border: 1px solid #cbd5e1; color: #334155; padding: 10px; border-radius: 6px;" placeholder="Auto-generates if blank">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Schedule &rarr;',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'swal-custom-popup',
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
                const title = document.getElementById('meetingTitle').value;
                const date = document.getElementById('meetingDate').value;
                const time = document.getElementById('meetingTime').value;
                const meetingType = document.getElementById('meetingTypeInput').value;
                const agenda = document.getElementById('meetingAgenda').value;
                const meetLink = document.getElementById('meetingLink').value;
                
                if (!title || !date || !time) {
                    Swal.showValidationMessage('Please fill in all required fields');
                    return false;
                }
                
                return fetch(`{{ route('tech.staff-monitoring.schedule', $user->id) }}`, {
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
                        meet_link: meetLink
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
            if (result.isConfirmed) {
                Swal.fire({
                    icon: 'success',
                    title: 'Scheduled!',
                    text: 'The meeting has been successfully scheduled.',
                    background: '#ffffff',
                    color: '#1e293b',
                    confirmButtonColor: '#3b82f6',
                    customClass: {
                        popup: 'swal-custom-popup'
                    }
                });
            }
        });
    }
</script>
@endpush