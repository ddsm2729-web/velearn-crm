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
        <a href="{{ route('tech.students.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 0.9rem;">
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
        $feeStatus = ($status == 'pending_fee') ? 'Pending' : 'Paid';
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
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-wallet"></i> Fee</div>
                        <div class="info-value">{{ $feeStatus }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label"><i class="fas fa-chart-line"></i> Attendance</div>
                        <div class="info-value" style="color: #10b981;">{{ $attendance }}%</div>
                    </div>
                </div>
                
                <button class="btn btn-assign shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i> Assign Lead to Staff</button>
                <div class="text-center text-muted small mb-3 px-3" style="font-size: 0.7rem;">Assign to Telecaller or Support Executive</div>
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
                        <div class="d-flex gap-3 mb-4">
                            <div class="summary-box">
                                <h6>Course</h6>
                                <div class="val" style="color: #6366f1;">{{ $progress }}%</div>
                            </div>
                            <div class="summary-box">
                                <h6>Attendance</h6>
                                <div class="val" style="color: #10b981;">{{ $attendance }}%</div>
                            </div>
                            <div class="summary-box">
                                <h6>Modules</h6>
                                <div class="val" style="color: #0ea5e9;">{{ $modulesDone }}/{{ $modulesTotal }}</div>
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
                    
                    <!-- OTHER TABS -->
                    <div class="tab-pane fade" id="attendance" role="tabpanel">
                        <p class="text-muted text-center py-5">Attendance details will be displayed here.</p>
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
                    <div class="metric-card">
                        <div class="icon"><i class="fas fa-calendar-alt" style="color: #3b82f6;"></i></div>
                        <h6>Attendance</h6>
                        <div class="val" style="color: #10b981;">{{ $attendance }}%</div>
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
