@extends('layouts.header')

@section('pageTitle', 'Student Profile')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-show.css') }}">
    <style>
        .profile-avatar {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #6366f1, #a855f7);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }
    </style>
@endpush

@section('section')
    @php
        $u = $courseUser->user;
        $c = $courseUser->course;
        $b = $courseUser->batch;
        
        $initials = strtoupper(substr($u->first_name ?? $u->name ?? 'A', 0, 1) . substr($u->last_name ?? '', 0, 1));
        if(strlen($initials) < 2) $initials = substr(strtoupper($u->name ?? 'AK'), 0, 2);
    @endphp

    <div class="container-fluid p-0 lead-profile-container mb-5">
        {{-- Header / Back Button --}}
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('eligible-students.index') }}"
                    class="text-primary text-decoration-none d-inline-flex align-items-center back-btn">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Eligible Students
                </a>
            </div>
        </div>

        {{-- Main Profile Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar me-3" style="overflow: hidden;">
                            {{ $initials }}
                        </div>
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $u->name ?? ($u->first_name . ' ' . $u->last_name) }}</h2>
                            <div class="d-flex align-items-center mt-1">
                                <span class="badge text-uppercase px-2 py-1 me-2" style="background-color: #10b981;">PROFILE SENT</span>
                                <span class="text-muted small">#{{ str_pad($u->id ?? 0, 3, '0', STR_PAD_LEFT) }}</span>
                                <span class="text-muted small ms-2">Course: <span class="text-primary fw-bold">{{ $c->title ?? 'N/A' }}</span></span>
                                <span class="text-muted small ms-2">Batch: <span class="text-primary fw-bold">{{ $b->batch_code ?? 'N/A' }}</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm px-3 rounded-pill">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                        <button class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                            <i class="fas fa-calendar-check me-1"></i> Schedule Interview
                        </button>
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Detail Boxes --}}
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">PHONE</label>
                            <div class="fw-bold">{{ $u->phonenumber ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">EMAIL</label>
                            <div class="fw-bold" style="word-break: break-all;">{{ $u->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">ADDRESS</label>
                            <div class="fw-bold text-truncate">{{ $u->city ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">DATE OF BIRTH</label>
                            <div class="fw-bold">{{ !empty($u->date_of_birth) ? \Carbon\Carbon::parse($u->date_of_birth)->format('Y-m-d') : 'N/A' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">BATCH TYPE</label>
                            <div class="fw-bold">{{ $b->batch_type ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">START DATE</label>
                            <div class="fw-bold">{{ $b->start_date ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">END DATE</label>
                            <div class="fw-bold">{{ $b->end_date ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">DURATION</label>
                            <div class="fw-bold">{{ $c->duration ?? '6 months' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">ASSIGNMENTS</label>
                            <div class="fw-bold text-success">28 / 30</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">ATTENDANCE</label>
                            <div class="fw-bold text-success">95%</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">MID-TERM</label>
                            <div class="fw-bold text-success">80%</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">FINAL EXAM</label>
                            <div class="fw-bold text-success">88%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Progress Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="mb-4 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i> Course Progress</h5>
                
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div style="font-size: 0.85rem; color: #64748b;">Overall progress</div>
                    <div style="font-weight: 700; color: #10b981;">{{ $courseUser->calc_overall ?? 0 }}%</div>
                </div>
                <div class="progress mb-4" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $courseUser->calc_overall ?? 0 }}%;" aria-valuenow="{{ $courseUser->calc_overall ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <h6 class="text-muted text-uppercase smaller fw-bold mb-3">Module-wise Assessment Scores</h6>
                
                <ul class="list-group list-group-flush">
                    @forelse($courseUser->course->curricula as $curriculum)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span>{{ $curriculum->title }}</span>
                            <span class="badge bg-success rounded-pill">0%</span>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">No modules defined.</li>
                    @endforelse
                </ul>
            </div>
        </div>
        
        {{-- Profile Links & Projects Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-link me-2 text-primary"></i> Project Details & Profile Links</h5>
                    <button class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#reviewProfileModal"><i class="fas fa-edit me-1"></i> Review Student Profile</button>
                </div>
                
                @php
                    function getStatusBadge($status) {
                        if ($status === 'verified' || $status === 'approved') return '<span class="text-success fw-bold">Approved <i class="fas fa-check-circle ms-1"></i></span>';
                        if ($status === 'rejected' || $status === 're-upload') return '<span class="text-danger fw-bold">Re-upload <i class="fas fa-times-circle ms-1"></i></span>';
                        if ($status === 'pending') return '<span class="text-warning fw-bold">Pending <i class="fas fa-clock ms-1"></i></span>';
                        return '<span class="text-muted fw-bold">Not Submitted</span>';
                    }
                @endphp
                
                <h6 class="fw-bold mb-3">Project Details</h6>
                <div class="row g-3 mb-5">
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100 position-relative">
                            <label class="text-muted smaller d-block mb-1">MAIN PROJECT TITLE</label>
                            <div class="fw-bold">{{ $mainProject ? $mainProject->title : 'N/A' }}</div>
                            <div class="text-muted small mt-1 text-truncate">{{ $c->title ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100 position-relative">
                            <label class="text-muted smaller d-block mb-1">SUBMISSION STATUS</label>
                            <div class="fw-bold {{ $mainProjectSubmission ? 'text-success' : 'text-warning' }}">
                                {{ $mainProjectSubmission ? 'Submitted' : 'Pending' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100 position-relative">
                            <label class="text-muted smaller d-block mb-1">FINAL SCORE</label>
                            <div class="fw-bold">
                                {{ $mainProjectSubmission && $mainProjectSubmission->score !== null ? $mainProjectSubmission->score . '%' : 'Not Evaluated' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100 position-relative">
                            <label class="text-muted smaller d-block mb-1">PROJECT DOCUMENT</label>
                            @if($mainProjectSubmission && $mainProjectSubmission->file_path)
                                <a href="{{ asset('uploads/main_projects/' . $mainProjectSubmission->file_path) }}" target="_blank" class="text-primary fw-bold text-truncate d-block text-decoration-none"><i class="fas fa-file-pdf me-1"></i> View Document</a>
                            @else
                                <div class="fw-bold text-muted">No Document</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="p-4 rounded-4 mt-2 border position-relative">
                    <h6 class="fw-bold mb-4 pb-3 border-bottom">Profile Links</h6>
                    <button type="button" class="btn btn-link p-0 position-absolute top-0 end-0 m-4 text-muted" style="z-index: 10; text-decoration: none;" title="Review Profile" data-bs-toggle="modal" data-bs-target="#reviewProfileModal">
                        <i class="fas fa-edit"></i>
                    </button>
                    <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-4">
                        <div class="col">
                            <div class="p-3 rounded-3 h-100 border">
                                <label class="text-muted text-uppercase smaller d-flex justify-content-between align-items-center mb-2 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span>RESUME</span>
                                    @if($placementProfile && $placementProfile->upload_resume)
                                        @if($placementProfile->resume_status == 'verified')
                                            <span class="badge bg-success" style="font-size: 0.6rem;">Verified</span>
                                        @elseif($placementProfile->resume_status == 'changes_needed')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">Changes Needed</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">Pending</span>
                                        @endif
                                    @endif
                                </label>
                                @if($placementProfile && $placementProfile->upload_resume)
                                    <a href="{{ asset('storage/' . $placementProfile->upload_resume) }}" target="_blank" class="text-primary text-truncate d-block text-decoration-none">Open resume</a>
                                @else
                                    <div class="text-muted small">Not Uploaded</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col">
                            <div class="p-3 rounded-3 h-100 border">
                                <label class="text-muted text-uppercase smaller d-flex justify-content-between align-items-center mb-2 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span>PORTFOLIO</span>
                                    @if($placementProfile && $placementProfile->portfolio_url)
                                        @if($placementProfile->portfolio_status == 'verified')
                                            <span class="badge bg-success" style="font-size: 0.6rem;">Verified</span>
                                        @elseif($placementProfile->portfolio_status == 'changes_needed')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">Changes Needed</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">Pending</span>
                                        @endif
                                    @endif
                                </label>
                                @if($placementProfile && $placementProfile->portfolio_url)
                                    <a href="{{ $placementProfile->portfolio_url }}" target="_blank" class="text-primary text-truncate d-block text-decoration-none">{{ $placementProfile->portfolio_url }}</a>
                                @else
                                    <div class="text-muted small">Not Provided</div>
                                @endif
                            </div>
                        </div>

                        <div class="col">
                            <div class="p-3 rounded-3 h-100 border">
                                <label class="text-muted text-uppercase smaller d-flex justify-content-between align-items-center mb-2 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span>PROJECT DOCS</span>
                                    @if($placementProfile && $placementProfile->upload_project_docs)
                                        @if($placementProfile->project_docs_status == 'verified')
                                            <span class="badge bg-success" style="font-size: 0.6rem;">Verified</span>
                                        @elseif($placementProfile->project_docs_status == 'changes_needed')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">Changes Needed</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">Pending</span>
                                        @endif
                                    @endif
                                </label>
                                @if($placementProfile && $placementProfile->upload_project_docs)
                                    <a href="{{ asset('storage/' . $placementProfile->upload_project_docs) }}" target="_blank" class="text-primary text-truncate d-block text-decoration-none">View Docs</a>
                                @else
                                    <div class="text-muted small">Not Uploaded</div>
                                @endif
                            </div>
                        </div>

                        <div class="col">
                            <div class="p-3 rounded-3 h-100 border">
                                <label class="text-muted text-uppercase smaller d-flex justify-content-between align-items-center mb-2 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    <span>GITHUB</span>
                                    @if($placementProfile && $placementProfile->github_url)
                                        @if($placementProfile->github_status == 'verified')
                                            <span class="badge bg-success" style="font-size: 0.6rem;">Verified</span>
                                        @elseif($placementProfile->github_status == 'changes_needed')
                                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem;">Changes Needed</span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">Pending</span>
                                        @endif
                                    @endif
                                </label>
                                @if($placementProfile && $placementProfile->github_url)
                                    <a href="{{ $placementProfile->github_url }}" target="_blank" class="text-primary text-truncate d-block text-decoration-none">{{ $placementProfile->github_url }}</a>
                                @else
                                    <div class="text-muted small">Not Provided</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Job Feed Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-star me-2 text-warning"></i> Student's LMS Feed</h5>
                </div>
                
                <div class="list-group">
                    @forelse($lmsFeedOpenings as $opening)
                    <div class="list-group-item py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $opening->company ? $opening->company->company_name : 'Unknown Company' }} <span class="text-muted fw-normal mx-1">-</span> {{ $opening->job_role }} <span class="badge bg-success ms-2" style="font-size: 0.65rem;">AVAILABLE</span></h6>
                                <div class="small text-muted d-flex gap-3 mt-2">
                                    <span><i class="fas fa-map-marker-alt"></i> {{ $opening->job_location ?? 'N/A' }}</span>
                                    <span><i class="fas fa-rupee-sign"></i> {{ $opening->ctc_salary ?? 'N/A' }}</span>
                                    <span><i class="fas fa-briefcase"></i> {{ $opening->experience_required ?? 'N/A' }}</span>
                                    <span><i class="far fa-calendar-times"></i> Deadline {{ $opening->application_deadline ? \Carbon\Carbon::parse($opening->application_deadline)->format('d M Y') : 'N/A' }}</span>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-3">View Opening</button>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item py-4 text-center text-muted">
                        No active job openings available at the moment.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Scheduled Interviews Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2 text-primary"></i> Scheduled Interviews
                        <span class="badge bg-primary-subtle text-primary ms-2" style="font-size: 0.7rem;">{{ $scheduledInterviews->count() }}</span>
                    </h5>
                    <button class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#scheduleInterviewModal">
                        <i class="fas fa-plus me-1"></i> Schedule
                    </button>
                </div>

                <div class="list-group">
                    @forelse($scheduledInterviews as $interview)
                        @php
                            $statusColors = [
                                'scheduled' => 'primary',
                                'attended' => 'info',
                                'selected' => 'success',
                                'next round' => 'info',
                                'on hold' => 'warning',
                                'rejected' => 'danger',
                                'not attended' => 'secondary',
                                'no show' => 'dark',
                            ];
                            $statusColor = $statusColors[strtolower($interview->status)] ?? 'info';
                        @endphp
                        <div class="list-group-item py-3">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <h6 class="mb-1 fw-bold">
                                        {{ $interview->company }}
                                        @if($interview->role)
                                            <span class="text-muted fw-normal mx-1">-</span> {{ $interview->role }}
                                        @endif
                                        <span class="badge bg-{{ $statusColor }} ms-2 text-uppercase" style="font-size: 0.65rem;">{{ $interview->status }}</span>
                                    </h6>
                                    <div class="small text-muted d-flex flex-wrap gap-3 mt-2">
                                        <span><i class="far fa-calendar-alt"></i> {{ $interview->date?->format('d M Y') }}</span>
                                        <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($interview->time)->format('h:i A') }}</span>
                                        <span><i class="fas fa-video"></i> {{ $interview->interview_mode }}</span>
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $interview->location ?? 'N/A' }}</span>
                                        <span><i class="fas fa-users"></i> {{ $interview->batch_code }} ({{ $interview->batch_type }})</span>
                                    </div>
                                    @if($interview->attendance)
                                        <div class="small mt-2">
                                            <span class="fw-semibold">Attendance:</span> {{ $interview->attendance }}
                                            @if($interview->result)
                                                <span class="mx-2 text-muted">|</span><span class="fw-semibold">Result:</span> {{ $interview->result }}
                                            @endif
                                        </div>
                                        @if($interview->reason)
                                            <div class="small text-muted mt-1"><span class="fw-semibold">Reason:</span> {{ $interview->reason }}</div>
                                        @endif
                                        @if($interview->hr_feedback)
                                            <div class="small text-muted mt-1"><span class="fw-semibold">HR Feedback:</span> {{ $interview->hr_feedback }}</div>
                                        @endif
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    @if($interview->meeting_link)
                                        <a href="{{ $interview->meeting_link }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-external-link-alt me-1"></i> Join Meeting
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3 update-outcome-btn"
                                        data-bs-toggle="modal" data-bs-target="#interviewOutcomeModal"
                                        data-url="{{ route('schedule-interviews.outcome', $interview) }}"
                                        data-name="{{ $interview->name }}"
                                        data-company="{{ $interview->company }}"
                                        data-attendance="{{ $interview->attendance ?? 'Attended' }}"
                                        data-reason="{{ $interview->reason }}"
                                        data-result="{{ $interview->result }}"
                                        data-feedback="{{ $interview->hr_feedback }}">
                                        <i class="fas fa-edit me-1"></i> Update Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item py-4 text-center text-muted">
                            No interviews scheduled yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        {{-- Call History Card --}}
        <div class="card profile-card border-0 shadow-sm mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-phone-alt me-2 text-primary"></i> Call History</h5>
                    @if($lead)
                    <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#conversationModal">
                        <i class="fas fa-plus me-1"></i> Add Entry
                    </button>
                    @endif
                </div>
                
                @if($lead)
                    @php
                        $currentTimelineItem = null;
                        $previousTimelineItems = collect();
                        
                        if ($timeline && count($timeline) > 0) {
                            $currentTimelineItem = $timeline->first();
                            $previousTimelineItems = $timeline->slice(1);
                        }
                    @endphp
                    
                    {{-- PREVIOUS SECTION --}}
                    <div class="timeline-section mb-5">
                        <div class="accordion-header-custom p-3 rounded-3 d-flex align-items-center justify-content-between mb-2"
                            style="cursor: pointer; background: #f1f3f5; transition: all 0.2s;"
                            data-bs-toggle="collapse" data-bs-target="#previousTimeline" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <span class="text-uppercase fw-bold text-muted smaller me-2">PREVIOUS</span>
                                <span class="text-muted small">({{ count($previousTimelineItems) }} records)</span>
                            </div>
                            <i class="fas fa-chevron-down text-muted transition-icon"></i>
                        </div>

                        <div class="collapse" id="previousTimeline">
                            <div class="table-responsive mt-3">
                                <table class="table timeline-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>TIME</th>
                                            <th>DIRECTION/TYPE</th>
                                            <th>RECORDING</th>
                                            <th>DURATION</th>
                                            <th>OWNED BY</th>
                                            <th>STATUS/TAG</th>
                                            <th>NOTES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($previousTimelineItems) > 0)
                                            @foreach($previousTimelineItems as $index => $item)
                                                @include('leads.partials.timeline_row', ['item' => $item, 'latestActivityId' => $latestActivityId])
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted small">No previous call history found.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- CURRENT SECTION --}}
                    @if($currentTimelineItem)
                    <div class="timeline-section mb-5">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-circle text-primary smaller me-2" style="font-size: 0.5rem;"></i>
                            <span class="text-uppercase fw-bold text-primary smaller me-2">CURRENT</span>
                            <span class="text-muted small">(Latest activity)</span>
                        </div>
                        
                        <div class="table-responsive mt-3">
                            <table class="table timeline-table mb-0">
                                <thead>
                                    <tr>
                                        <th>TIME</th>
                                        <th>DIRECTION/TYPE</th>
                                        <th>RECORDING</th>
                                        <th>DURATION</th>
                                        <th>OWNED BY</th>
                                        <th>STATUS/TAG</th>
                                        <th>NOTES + ASSIGN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @include('leads.partials.timeline_row', ['item' => $currentTimelineItem, 'latestActivityId' => $latestActivityId, 'isCurrent' => true])
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-4 text-muted small border rounded">
                        No lead profile linked to this student. Call history is unavailable.
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="reviewProfileModal" tabindex="-1" aria-labelledby="reviewProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('eligible-students.review', $courseUser->id) }}" method="POST">
            @csrf
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-bold text-dark" id="reviewProfileModalLabel"><i class="fas fa-user-check me-2 text-primary"></i> Review Student Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <p class="text-muted small mb-4">Review the student's uploaded documents. You can set them to verified, or request a re-upload by providing specific feedback.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Resume Verification <span class="text-danger">*</span></label>
                            <select name="resume_status" class="form-select form-control" required>
                                <option value="pending" {{ ($placementProfile->resume_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="verified" {{ ($placementProfile->resume_status ?? '') == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                                <option value="changes_needed" {{ ($placementProfile->resume_status ?? '') == 'changes_needed' ? 'selected' : '' }}>Changes Needed (Request Re-upload)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Resume Feedback</label>
                            <textarea name="resume_feedback" class="form-control" rows="1" placeholder="Why was it rejected?">{{ $placementProfile->resume_feedback ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Portfolio Verification <span class="text-danger">*</span></label>
                            <select name="portfolio_status" class="form-select form-control" required>
                                <option value="pending" {{ ($placementProfile->portfolio_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="verified" {{ ($placementProfile->portfolio_status ?? '') == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                                <option value="changes_needed" {{ ($placementProfile->portfolio_status ?? '') == 'changes_needed' ? 'selected' : '' }}>Changes Needed (Request Re-upload)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Portfolio Feedback</label>
                            <textarea name="portfolio_feedback" class="form-control" rows="1" placeholder="Why was it rejected?">{{ $placementProfile->portfolio_feedback ?? '' }}</textarea>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Project Documents Verification <span class="text-danger">*</span></label>
                            <select name="project_docs_status" class="form-select form-control" required>
                                <option value="pending" {{ ($placementProfile->project_docs_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="verified" {{ ($placementProfile->project_docs_status ?? '') == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                                <option value="changes_needed" {{ ($placementProfile->project_docs_status ?? '') == 'changes_needed' ? 'selected' : '' }}>Changes Needed (Request Re-upload)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">Project Docs Feedback</label>
                            <textarea name="project_docs_feedback" class="form-control" rows="1" placeholder="Why was it rejected?">{{ $placementProfile->project_docs_feedback ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">GitHub Verification <span class="text-danger">*</span></label>
                            <select name="github_status" class="form-select form-control" required>
                                <option value="pending" {{ ($placementProfile->github_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending Verification</option>
                                <option value="verified" {{ ($placementProfile->github_status ?? '') == 'verified' ? 'selected' : '' }}>Verified (Approved)</option>
                                <option value="changes_needed" {{ ($placementProfile->github_status ?? '') == 'changes_needed' ? 'selected' : '' }}>Changes Needed (Request Re-upload)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold">GitHub Feedback</label>
                            <textarea name="github_feedback" class="form-control" rows="1" placeholder="Why was it rejected?">{{ $placementProfile->github_feedback ?? '' }}</textarea>
                        </div>
                    </div>
                    
        <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold fs-5 text-primary">Final Profile Status <span class="text-danger">*</span></label>
                            <p class="text-muted small">Update this to 'Approved' once all documents are verified to push the student to the active job board feed.</p>
                            <select name="overall_status" class="form-select form-select-lg form-control" required>
                                <option value="pending" {{ ($placementProfile->overall_status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending Final Review</option>
                                <option value="approved" {{ ($placementProfile->overall_status ?? '') == 'approved' ? 'selected' : '' }}>Approved (Ready for Job Placements)</option>
                                <option value="rejected" {{ ($placementProfile->overall_status ?? '') == 'rejected' ? 'selected' : '' }}>Rejected / Incomplete</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4"><i class="fas fa-save me-1"></i> Save Review</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($lead)
<!-- Log Activity Modal -->
<div class="modal fade" id="conversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Complete Conversation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="log-activity-form" action="{{ route('leads.activity.store', encrypt($lead->id)) }}" method="POST" onsubmit="return false;">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <input type="hidden" name="type" value="Call">
                        <div class="col-12">
                            <label class="form-label fw-bold">Conversation Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="note" rows="4" placeholder="Enter conversation details..." required></textarea>
                            <div class="invalid-feedback">Conversation note is strictly required.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Next Follow-up</label>
                            <input type="datetime-local" class="form-control" name="next_followup_date">
                        </div>
                        <hr class="my-4">
                        
                        <h6 class="fw-bold mb-2">Update Lead Pipeline</h6>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Update Status</label>
                            <select class="form-select" name="new_status_id" id="new_status_id">
                                @foreach($statuses as $sts)
                                    <option value="{{ $sts->id }}" {{ $lead->lead_status_id == $sts->id ? 'selected' : '' }} data-name="{{ strtolower($sts->name) }}">{{ $sts->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Update Tag</label>
                            @php $selectedTagId = $lead->tags->first()?->id; @endphp
                            <select name="tag_id" id="new_tag_id" class="form-select">
                                <option value="" data-name="">Select Tag</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $selectedTagId == $tag->id ? 'selected' : '' }} data-name="{{ strtolower($tag->name) }}">{{ ucfirst($tag->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 d-none" id="assign_admission_officer_wrapper">
                            <label class="form-label text-muted small">Assign Admission Officer <span class="text-danger">*</span></label>
                            <select name="admission_officer_id" id="new_admission_officer_id" class="form-select">
                                <option value="">Select Admission Officer</option>
                                @if(isset($admissionOfficers))
                                    @foreach($admissionOfficers as $officer)
                                        <option value="{{ $officer->id }}" {{ $lead->assigned_to == $officer->id ? 'selected' : '' }}>{{ $officer->first_name }} {{ $officer->last_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="log-activity-submit">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="activity-loader"></span>
                        Complete Conversation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Activity Modal -->
<div class="modal fade" id="editActivityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Activity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-activity-form" action="" method="POST" onsubmit="return false;">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">
                        <input type="hidden" name="type" id="edit_type" value="Call">

                        <div class="col-12">
                            <label class="form-label fw-bold">Conversation Note <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="note" id="edit_note" rows="4" required></textarea>
                            <div class="invalid-feedback">Conversation note is strictly required.</div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Next Follow-up</label>
                            <input type="datetime-local" class="form-control" name="next_followup_date" id="edit_next_followup_date">
                        </div>

                        <hr class="my-4">
                        
                        <h6 class="fw-bold mb-2">Update Lead Pipeline</h6>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Update Status</label>
                            <select class="form-select" name="new_status_id" id="edit_status_id">
                                @foreach($statuses as $sts)
                                    <option value="{{ $sts->id }}" {{ $lead->lead_status_id == $sts->id ? 'selected' : '' }} data-name="{{ strtolower($sts->name) }}">{{ $sts->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Update Tag</label>
                            @php $selectedTagId = $lead->tags->first()?->id; @endphp
                            <select name="tag_id" id="edit_tag_id" class="form-select">
                                <option value="" data-name="">Select Tag</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $selectedTagId == $tag->id ? 'selected' : '' }} data-name="{{ strtolower($tag->name) }}">{{ ucfirst($tag->name) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 d-none" id="edit_assign_admission_officer_wrapper">
                            <label class="form-label text-muted small">Assign Admission Officer <span class="text-danger">*</span></label>
                            <select name="admission_officer_id" class="form-select" id="edit_admission_officer_id">
                                <option value="">Select Admission Officer</option>
                                @if(isset($admissionOfficers))
                                    @foreach($admissionOfficers as $officer)
                                        <option value="{{ $officer->id }}" {{ $lead->assigned_to == $officer->id ? 'selected' : '' }}>{{ $officer->first_name }} {{ $officer->last_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="edit-activity-submit">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="edit-activity-loader"></span>
                        Update Activity
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
<!-- Schedule Interview Modal -->
<div class="modal fade" id="scheduleInterviewModal" tabindex="-1" aria-labelledby="scheduleInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="scheduleInterviewModalLabel">Schedule Interview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('schedule-interviews.store') }}" method="POST" id="scheduleInterviewForm">
                @csrf
                <input type="hidden" name="user_id" value="{{ $u->id ?? '' }}">
                <div class="modal-body pb-0">
                    <div class="row g-3">
                        <!-- Student Name -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Student Name *</label>
                            <input type="text" name="name" class="form-control" value="{{ $u->name ?? ($u->first_name . ' ' . $u->last_name) }}" required readonly>
                        </div>
                        <!-- Phone and Email -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Phone Number *</label>
                            <input type="tel" name="phone" class="form-control" value="{{ $u->phonenumber ?? '' }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ $u->email ?? '' }}" required readonly>
                        </div>
                        <!-- Course and Duration -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Course *</label>
                            <input type="text" name="course" class="form-control" value="{{ $c->title ?? '' }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Course Duration *</label>
                            <input type="text" name="duration" class="form-control" value="{{ $c->duration ?? '' }}" required>
                        </div>
                        <!-- Batch Code and Batch Type -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Batch Code *</label>
                            <input type="text" name="batchCode" class="form-control" value="{{ $b->batch_code ?? '' }}" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Batch Type *</label>
                            <select name="batchType" class="form-select" required>
                                <option value="Weekday" {{ isset($b->batch_type) && $b->batch_type == 'Weekday' ? 'selected' : '' }}>Weekday</option>
                                <option value="Weekend" {{ isset($b->batch_type) && $b->batch_type == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                                <option value="Bootcamp" {{ isset($b->batch_type) && $b->batch_type == 'Bootcamp' ? 'selected' : '' }}>Bootcamp</option>
                                <option value="Online" {{ isset($b->batch_type) && $b->batch_type == 'Online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>
                        <!-- Company Name and Job Role -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Company Name *</label>
                            <select name="company" id="scheduleCompanySelect" class="form-select" required>
                                <option value="">-- Select Company --</option>
                                @foreach($interviewOpenings as $opening)
                                    <option value="{{ $opening->company->company_name ?? 'Unknown Company' }}"
                                        data-role="{{ $opening->job_role }}"
                                        data-location="{{ $opening->job_location }}"
                                        data-mode="{{ $opening->interview_mode }}">
                                        {{ $opening->company->company_name ?? 'Unknown Company' }} - {{ $opening->job_role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Job Role</label>
                            <input type="text" name="role" class="form-control" placeholder="">
                        </div>
                        <!-- Location and Scheduled Date -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Location</label>
                            <input type="text" name="location" class="form-control" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Scheduled Date *</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <!-- Scheduled Time and Interview Mode -->
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Scheduled Time *</label>
                            <input type="time" name="time" class="form-control" value="10:00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Interview Mode *</label>
                            <select name="interviewMode" class="form-select" required>
                                <option value="Online">Online</option>
                                <option value="Offline">Offline</option>
                                <option value="Walk-in">Walk-in</option>
                                <option value="Hybrid">Hybrid</option>
                            </select>
                        </div>
                        <!-- Meeting Link -->
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Meeting Link (If Online)</label>
                            <input type="url" name="meetingLink" class="form-control" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 mt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="scheduleInterviewSubmit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-check me-1"></i> Done
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Interview Outcome Modal -->
<div class="modal fade" id="interviewOutcomeModal" tabindex="-1" aria-labelledby="interviewOutcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="interviewOutcomeModalLabel">Update Interview Outcome</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="#" method="POST" id="interviewOutcomeForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Student *</label>
                            <input type="text" id="outcomeStudent" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Company *</label>
                            <input type="text" id="outcomeCompany" class="form-control" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Attendance *</label>
                            <select name="attendance" id="outcomeAttendance" class="form-select" required>
                                <option value="Attended">Attended</option>
                                <option value="Not Attended">Not Attended</option>
                                <option value="No Show">No Show</option>
                            </select>
                        </div>
                        <div class="col-12" id="outcomeReasonWrapper">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Reason (If Not Attended)</label>
                            <textarea name="reason" id="outcomeReason" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="col-md-6" id="outcomeResultWrapper">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">Result</label>
                            <select name="result" id="outcomeResult" class="form-select">
                                <option value="">—</option>
                                <option value="Selected">Selected</option>
                                <option value="Rejected">Rejected</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Next Round">Next Round</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted text-uppercase mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">HR Feedback</label>
                            <textarea name="hr_feedback" id="outcomeFeedback" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="interviewOutcomeSubmit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        <i class="fas fa-check me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('script')
<script>
$(document).ready(function () {
    function toggleOutcomeFields() {
        let isAttended = $('#outcomeAttendance').val() === 'Attended';
        $('#outcomeReasonWrapper').toggleClass('d-none', isAttended);
        $('#outcomeReason').prop('required', !isAttended);
        $('#outcomeResultWrapper').toggleClass('d-none', !isAttended);
    }

    $('#outcomeAttendance').on('change', toggleOutcomeFields);

    $(document).on('click', '.update-outcome-btn', function () {
        let $btn = $(this);
        $('#interviewOutcomeForm').attr('action', $btn.data('url'));
        $('#outcomeStudent').val($btn.data('name'));
        $('#outcomeCompany').val($btn.data('company'));
        $('#outcomeAttendance').val($btn.data('attendance') || 'Attended');
        $('#outcomeReason').val($btn.data('reason') || '');
        $('#outcomeResult').val($btn.data('result') || '');
        $('#outcomeFeedback').val($btn.data('feedback') || '');
        toggleOutcomeFields();
    });

    $('#interviewOutcomeForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $('#interviewOutcomeSubmit');
        let originalHtml = $btn.html();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    $('#interviewOutcomeModal').modal('hide');
                    setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $btn.attr('disabled', false).html(originalHtml);
            }
        });
    });

    $('#scheduleCompanySelect').on('change', function () {
        let $selected = $(this).find('option:selected');
        let $form = $('#scheduleInterviewForm');
        $form.find('input[name="role"]').val($selected.data('role') || '');
        $form.find('input[name="location"]').val($selected.data('location') || '');

        let mode = $selected.data('mode');
        let $modeSelect = $form.find('select[name="interviewMode"]');
        if (mode) {
            let $match = $modeSelect.find('option').filter(function () {
                return $(this).val().toLowerCase() === String(mode).toLowerCase();
            });
            if ($match.length) {
                $modeSelect.val($match.val());
            }
        }
    });

    $('#scheduleInterviewForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $('#scheduleInterviewSubmit');
        let originalHtml = $btn.html();

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    $('#scheduleInterviewModal').modal('hide');
                    setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        toastr.error(messages[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $btn.attr('disabled', false).html(originalHtml);
            }
        });
    });

    function toggleAdmissionOfficerDropdown(dropdownId, wrapperId, selectId) {
        var selectedOption = $('#' + dropdownId).find('option:selected');
        var statusName = selectedOption.data('name');
        if (statusName === 'converted') {
            $('#' + wrapperId).removeClass('d-none');
            $('#' + selectId).prop('required', true);
        } else {
            $('#' + wrapperId).addClass('d-none');
            $('#' + selectId).prop('required', false);
        }
    }

    $('#new_status_id').on('change', function() {
        toggleAdmissionOfficerDropdown('new_status_id', 'assign_admission_officer_wrapper', 'new_admission_officer_id');
    });

    $('#edit_status_id').on('change', function() {
        toggleAdmissionOfficerDropdown('edit_status_id', 'edit_assign_admission_officer_wrapper', 'edit_admission_officer_id');
    });

    if($('#new_status_id').length > 0) {
        toggleAdmissionOfficerDropdown('new_status_id', 'assign_admission_officer_wrapper', 'new_admission_officer_id');
        toggleAdmissionOfficerDropdown('edit_status_id', 'edit_assign_admission_officer_wrapper', 'edit_admission_officer_id');
    }

    $('#log-activity-submit').on('click', function () {
        let $btn = $(this);
        let $loader = $btn.find('#activity-loader');
        let $form = $('#log-activity-form');

        $form.find('.invalid-feedback').text('').hide();
        $form.find('.form-control').removeClass('is-invalid');

        if(!$form.find('textarea[name="note"]').val().trim()) {
            $form.find('textarea[name="note"]').addClass('is-invalid').siblings('.invalid-feedback').show();
            return;
        }

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
                $btn.attr('disabled', true);
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                    $btn.attr('disabled', false);
                }
            },
            error: function (xhr) {
                $btn.attr('disabled', false);
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    $(document).on('submit', '.inline-activity-form', function(e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                    $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                }
            },
            error: function () {
                $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                toastr.error('Something went wrong. Please try again.');
            }
        });
    });

    $('.edit-activity-btn').on('click', function () {
        let id = $(this).data('id');
        $('#edit_type').val($(this).data('type'));
        $('#edit_note').val($(this).data('note'));
        $('#edit_next_followup_date').val($(this).data('next-followup'));
        let actionUrl = "{{ url('leads/activity/update') }}/" + id;
        $('#edit-activity-form').attr('action', actionUrl);
        $('#editActivityModal').modal('show');
    });

    $('#edit-activity-submit').on('click', function () {
        let $btn = $(this);
        let $loader = $btn.find('#edit-activity-loader');
        let $form = $('#edit-activity-form');
        if(!$form.find('textarea[name="note"]').val().trim()) {
            $form.find('textarea[name="note"]').addClass('is-invalid').siblings('.invalid-feedback').show();
            return;
        }
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
                $btn.attr('disabled', true);
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload(); }, 1000);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                    $btn.attr('disabled', false);
                }
            },
            error: function () {
                $btn.attr('disabled', false);
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });
});
</script>
@endpush
