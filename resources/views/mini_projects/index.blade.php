@extends('layouts.header')

@section('pageTitle', 'Mini Project Centre')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
@endpush

@section('section')
    <div class="container-fluid p-0">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-0 text-success">Mini Project <span class="text-dark">Centre</span></h3>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute text-muted"
                        style="top: 50%; left: 16px; transform: translateY(-50%);"></i>
                    <input type="text" class="form-control rounded-pill border-0 shadow-sm py-2"
                        placeholder="Search students, batches..."
                        style="padding-left: 40px; width: 280px; background-color: #fff;">
                </div>
                <div class="position-relative">
                    <form method="GET" class="d-flex gap-2">
                        <select name="batch_id" class="form-select rounded-pill shadow-sm" onchange="this.form.submit()"
                            style="min-width: 200px;">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                                <option value="{{ $batch->id }}" {{ request("batch_id") == $batch->id ? "selected" : "" }}>
                                    {{ $batch->batch_name }} ({{ $batch->batch_code }})
                                </option>
                            @endforeach
                        </select>
                        <noscript><button type="submit" class="btn btn-primary rounded-pill">Filter</button></noscript>
                    </form>
                </div>

                @can('Mini Project Create')
                    <a href="{{ route('mini_projects.create') }}"
                        class="btn btn-primary rounded-pill px-4 py-2 shadow-sm fw-bold text-nowrap d-flex align-items-center">
                        <i class="fas fa-plus me-2"></i> Add Mini Project
                    </a>
                @endcan
            </div>
        </div>

        <!-- Custom Styling for exact match -->
        <link rel="stylesheet" href="{{ asset('assets/css/mini_projects-index.css') }}">

        <!-- Course Tabs Navigation -->
        @if ($liveCourses->count() > 0)
            <div class="mb-4">
                <ul class="nav nav-pills gap-3" id="courseTabs" role="tablist">
                    @foreach ($liveCourses as $index => $course)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link course-tab {{ $index == 0 ? 'active' : '' }}" id="tab-{{ $course->id }}"
                                data-bs-toggle="pill" data-bs-target="#course-{{ $course->id }}" type="button" role="tab">
                                <i class="fas fa-bolt text-warning me-1"></i> {{ $course->title }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Tabs Content -->
            <div class="tab-content" id="courseTabsContent">
                @foreach ($liveCourses as $index => $course)
                    <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="course-{{ $course->id }}" role="tabpanel">

                        @if(auth()->user()->can('Mini Project Evaluate') || auth()->user()->can('Mini Project Complete'))
                            <div class="mb-4">
                                <div class="segmented-control" id="control-{{ $course->id }}">
                                    <button class="btn btn-mini_project active"
                                        onclick="switchSection({{ $course->id }}, 'mini_projects')">📋 Mini Project</button>
                                    @can('Mini Project Evaluate')
                                        <button class="btn btn-evaluation" onclick="switchSection({{ $course->id }}, 'evaluations')">🎯
                                            Evaluation</button>
                                    @endcan
                                    @can('Mini Project Complete')
                                        <button class="btn btn-completed" onclick="switchSection({{ $course->id }}, 'completed')">✓
                                            Complete</button>
                                    @endcan
                                </div>
                            </div>
                        @endif

                        @php
                            $courseMiniProjects = $mini_projects->where('course_id', $course->id);

                            $studentIds = null;
                            if (auth()->user()->hasRole('Technical Staff')) {
                                $staffId = auth()->user()->staffProfile->id ?? 0;
                                $staffBatches = \App\Models\Batch::where('instructor_id', $staffId)->pluck('id')->toArray();
                                $studentIds = \App\Models\CourseUser::whereIn('batch_id', $staffBatches)->pluck('auth_id')->toArray();
                            }

                            $pendingSubmissions = collect();
                            if (auth()->user()->can('Mini Project Evaluate')) {
                                $pendingSubmissionsQuery = \App\Models\MiniProjectSubmission::whereIn('mini_project_id', $courseMiniProjects->pluck('id'));
                                if ($studentIds !== null) {
                                    $pendingSubmissionsQuery->whereIn('user_id', $studentIds);
                                }

                                // Fetch pending submissions for mini_projects in this course
                                $pendingSubmissions = $pendingSubmissionsQuery->where(function ($query) {
                                    $query->whereIn('status', ['pending', 'late'])
                                        ->orWhere(function ($q) {
                                            $q->whereIn('status', ['evaluated', 'graded'])
                                                ->where('reupload_approved', 1);
                                        });
                                })
                                    ->with(['mini_project.criteria', 'mini_project.batch', 'student', 'scoreBreakdowns'])
                                    ->latest()
                                    ->get();
                            }

                            $completedSubmissions = collect();
                            if (auth()->user()->can('Mini Project Complete')) {
                                $completedSubmissionsQuery = \App\Models\MiniProjectSubmission::whereIn('mini_project_id', $courseMiniProjects->pluck('id'));
                                if ($studentIds !== null) {
                                    $completedSubmissionsQuery->whereIn('user_id', $studentIds);
                                }

                                // Fetch evaluated/completed submissions for mini_projects in this course
                                $completedSubmissions = $completedSubmissionsQuery->whereIn('status', ['evaluated', 'graded'])
                                    ->where('reupload_approved', 0)
                                    ->with(['mini_project.criteria', 'mini_project.batch', 'student', 'evaluator', 'scoreBreakdowns'])
                                    ->latest()
                                    ->get();
                            }
                        @endphp

                        <!-- Mini Project Section Content -->
                        <div id="section-mini_projects-{{ $course->id }}" class="section-group-{{ $course->id }}">
                            @forelse($courseMiniProjects as $mini_project)
                                <div class="mini_project-card p-4 mb-3 border-0 shadow-sm rounded-4 bg-white"
                                    style="transition: all 0.3s ease; border-left: 4px solid {{ $mini_project->status == 'active' ? '#198754' : '#ffc107' }} !important;">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <!-- Left Side: Title, Status, Description, Metadata -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <h5 class="fw-bold text-dark mb-0" style="font-size: 1.1rem;">{{ $mini_project->title }}
                                                </h5>
                                                @if($mini_project->status == 'pending')
                                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-1 fw-medium"
                                                        style="font-size: 0.75rem; border: 1px solid #ffda6a;">
                                                        <i class="fas fa-clock me-1"></i> Pending LC Approval
                                                    </span>
                                                @elseif($mini_project->status == 'active')
                                                    <span class="badge rounded-pill bg-success text-white px-3 py-1 fw-medium"
                                                        style="font-size: 0.75rem;">
                                                        <i class="fas fa-check-circle me-1"></i> Approved
                                                    </span>
                                                @else
                                                    <span class="badge rounded-pill bg-secondary text-white px-3 py-1 fw-medium"
                                                        style="font-size: 0.75rem;">
                                                        {{ ucfirst($mini_project->status) }}
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-muted mb-3" style="font-size: 0.9rem; max-width: 800px; line-height: 1.5;">
                                                {{ $mini_project->description }}
                                            </p>

                                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted"
                                                style="font-size: 0.85rem;">
                                                <span class="badge bg-light text-dark border px-2 py-1 fw-normal rounded">
                                                    <i class="fas fa-box text-warning me-1"></i>
                                                    {{ $mini_project->course->title ?? 'Course' }}
                                                </span>
                                                <span class="badge bg-light text-dark border px-2 py-1 fw-normal rounded">
                                                    <i class="fas fa-square text-info me-1"></i> {{ $mini_project->module }}
                                                </span>
                                                <span class="badge bg-light text-dark border px-2 py-1 fw-normal rounded">
                                                    <i class="far fa-calendar-alt text-primary me-1"></i> Uploaded:
                                                    {{ $mini_project->created_at->format('d M Y') }}
                                                </span>
                                                <span class="badge bg-light text-dark border px-2 py-1 fw-normal rounded">
                                                    <i class="fas fa-users text-danger me-1"></i> Submissions:
                                                    {{ $mini_project->submissions->count() }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Right Side: Dates, Actions -->
                                        <div class="d-flex flex-column align-items-end" style="min-width: 160px;">
                                            <div class="d-flex flex-column align-items-end gap-2 mb-3 w-100">
                                                <span
                                                    class="badge bg-danger text-white rounded-pill px-3 py-2 w-100 text-start shadow-sm"
                                                    style="font-size: 0.8rem; background-color: #dc3545 !important;">
                                                    <i class="fas fa-stopwatch me-1"></i> Due:
                                                    {{ $mini_project->due_date ? \Carbon\Carbon::parse($mini_project->due_date)->format('d M Y') : 'N/A' }}
                                                </span>
                                                @if($mini_project->reveal_date)
                                                    <span class="badge bg-info text-white rounded-pill px-3 py-2 w-100 text-start shadow-sm"
                                                        style="font-size: 0.8rem; background-color: #0dcaf0 !important;">
                                                        <i class="fas fa-eye me-1"></i> Reveals:
                                                        {{ \Carbon\Carbon::parse($mini_project->reveal_date)->format('d M Y') }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="d-flex align-items-center gap-2 mt-auto">
                                                @can('Mini Project Status')
                                                    <span class="status-toggle cursor-pointer d-flex align-items-center"
                                                        data-url="{{ route('mini_projects.update-status', $mini_project) }}"
                                                        data-change-status="{{ $mini_project->status == 'active' ? 'pending' : 'active' }}"
                                                        title="Toggle Status">
                                                        <img src="{{ asset($mini_project->status == 'active' ? 'assets/images/icon-toggle-on.png' : 'assets/images/icon-toggle-off.png') }}"
                                                            alt="Toggle Status"
                                                            style="width: 38px; height: auto; transition: transform 0.2s;">
                                                    </span>
                                                @endcan

                                                @can('Mini Project Edit')
                                                    <a href="{{ route('mini_projects.edit', $mini_project) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-circle shadow-sm"
                                                        style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;"
                                                        title="Edit Mini Project">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('Mini Project Delete')
                                                    <form action="{{ route('mini_projects.destroy', $mini_project) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm"
                                                            style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;"
                                                            onclick="return confirm('Are you sure you want to delete this mini project?')"
                                                            title="Delete Mini Project">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5 text-muted">
                                    No mini_projects found for this course.
                                </div>
                            @endforelse
                        </div>

                        <!-- Evaluations Section Content -->
                        @can('Mini Project Evaluate')
                        <div id="section-evaluations-{{ $course->id }}" class="section-group-{{ $course->id }} d-none">
                            <div class="d-flex flex-column gap-3">
                                @forelse($pendingSubmissions as $submission)
                                    <div class="submission-row-card">
                                        <div class="p-3 d-flex align-items-center justify-content-between cursor-pointer"
                                            onclick="toggleDetails({{ $submission->id }})">
                                            <!-- Student Name -->
                                            <div style="flex: 2;">
                                                <div class="fw-bold text-dark fs-6">{{ $submission->student->name ?? 'N/A' }}</div>
                                                <div class="text-muted small">{{ $submission->mini_project->course->title ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Timing -->
                                            <div style="flex: 1;" class="text-muted small">
                                                Weekday
                                            </div>

                                            <!-- Attempts Badge -->
                                            <div style="flex: 1.5;">
                                                @if(in_array($submission->status, ['evaluated', 'graded']) && $submission->reupload_approved == 1)
                                                    <span
                                                        class="badge bg-info bg-opacity-25 text-info-emphasis border border-info border-opacity-50 px-3 py-2 fw-semibold rounded-pill"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fas fa-hourglass-half me-1"></i> Waiting for Reupload
                                                    </span>
                                                @elseif($submission->attempts > 1)
                                                    <span
                                                        class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning border-opacity-50 px-3 py-2 fw-semibold rounded-pill"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fas fa-redo me-1"></i> Reuploaded ({{ $submission->attempts }}/3)
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-primary bg-opacity-25 text-primary-emphasis border border-primary border-opacity-50 px-3 py-2 fw-semibold rounded-pill"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fas fa-upload me-1"></i> Uploaded
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Module -->
                                            <div style="flex: 1;" class="text-muted small">
                                                {{ $submission->mini_project->module ?? 'Module' }}
                                            </div>

                                            <!-- Date -->
                                            <div style="flex: 1.5;" class="text-muted small">
                                                {{ $submission->created_at->format('d M Y') }}
                                            </div>

                                            <!-- Download File Link -->
                                            <div style="flex: 0.5;">
                                                @if($submission->file_path)
                                                    <a href="{{ strpos($submission->file_path, 'mini_project_submissions') === 0 ? asset($submission->file_path) : asset('uploads/mini_projects/' . $submission->file_path) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary border shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px;" onclick="event.stopPropagation();">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Evaluate action -->
                                            <div style="flex: 2;" class="text-end">
                                                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm"
                                                    style="background-color: #6366f1; border: none;"
                                                    onclick="event.stopPropagation(); openEvaluationModal({{ json_encode($submission) }})">
                                                    🎯 Evaluate
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Accordion Details panel -->
                                        <div id="details-{{ $submission->id }}" class="bg-light p-3 border-top d-none">
                                            @if(in_array($submission->status, ['evaluated', 'graded']) || count(is_string($submission->attempts_history) ? json_decode($submission->attempts_history, true) : ($submission->attempts_history ?? [])) > 0)
                                                @php
                                                    $history = is_string($submission->attempts_history) ? json_decode($submission->attempts_history, true) : ($submission->attempts_history ?? []);
                                                    $lastAttempt = end($history);
                                                    $previousScore = $lastAttempt ? $lastAttempt['score'] : null;
                                                @endphp
                                                <div class="row align-items-stretch">
                                                    <!-- Score -->
                                                    <div class="col-md-3 mb-2 mb-md-0">
                                                        <div
                                                            class="score-box rounded-3 p-3 text-center h-100 shadow-sm d-flex flex-column justify-content-center align-items-center">
                                                            <div class="fs-2 fw-bold text-success"
                                                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                                {{ $submission->score }}</div>
                                                            <div class="text-muted small fw-bold uppercase-tracking mt-1">Score</div>

                                                            <!-- Dynamic Points Increased Indicator from history -->
                                                            @if($previousScore !== null)
                                                                @php
                                                                    $diff = $submission->score - $previousScore;
                                                                @endphp
                                                                <div class="mt-2 small">
                                                                    @if($diff > 0)
                                                                        <span class="text-success fw-bold"><i class="fas fa-arrow-up"></i>
                                                                            +{{ $diff }} pts Increase</span>
                                                                    @elseif($diff < 0)
                                                                        <span class="text-danger fw-bold"><i class="fas fa-arrow-down"></i>
                                                                            {{ $diff }} pts Decrease</span>
                                                                    @else
                                                                        <span class="text-muted fw-bold">No Change</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Grade -->
                                                    <div class="col-md-3 mb-2 mb-md-0">
                                                        <div
                                                            class="grade-box rounded-3 p-3 text-center h-100 shadow-sm d-flex flex-column justify-content-center align-items-center">
                                                            <div class="fs-2 fw-bold text-primary"
                                                                style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                                {{ $submission->grade }}</div>
                                                            <div class="text-muted small fw-bold uppercase-tracking mt-1">Grade</div>
                                                        </div>
                                                    </div>

                                                    <!-- Feedback -->
                                                    <div class="col-md-6">
                                                        <div
                                                            class="feedback-box rounded-3 p-3 h-100 shadow-sm d-flex flex-column justify-content-center">
                                                            <div class="text-muted small fw-bold uppercase-tracking mb-2"><i
                                                                    class="far fa-comment-dots me-1 text-primary"></i> Feedback</div>
                                                            <div class="text-dark" style="line-height: 1.5; font-size: 0.95rem;">
                                                                {{ $submission->feedback ?? 'No feedback recorded.' }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- 3 Attempts Full Details Display -->
                                                    @if(count($history) > 0)
                                                        <div class="col-12 mt-3 pt-3 border-top">
                                                            <h6 class="fw-bold text-muted uppercase-tracking mb-3" style="font-size: 0.8rem;"><i
                                                                    class="fas fa-history me-1 text-warning"></i> Previous Attempts History</h6>
                                                            <div class="d-flex flex-column gap-2">
                                                                @foreach($history as $attempt)
                                                                    <div class="bg-white p-3 rounded-3 border shadow-xs">
                                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                                            <div>
                                                                                <span
                                                                                    class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold rounded-pill">Attempt
                                                                                    {{ $attempt['attempt'] }}</span>
                                                                                <span class="text-muted small ms-2"><i
                                                                                        class="far fa-calendar-alt"></i> Graded:
                                                                                    {{ \Carbon\Carbon::parse($attempt['evaluated_at'])->format('d M Y, h:i A') }}</span>
                                                                            </div>
                                                                            <div class="text-end">
                                                                                <span class="fw-bold text-dark fs-6">{{ $attempt['score'] }}%</span>
                                                                                <span
                                                                                    class="badge bg-light text-dark border ms-1 fw-bold">{{ $attempt['grade'] }}</span>
                                                                            </div>
                                                                        </div>

                                                                        <!-- Criterion Score breakdown -->
                                                                        @if(isset($attempt['breakdowns']) && count($attempt['breakdowns']) > 0)
                                                                            <div class="mb-2 bg-light p-2 rounded-2" style="font-size: 0.85rem;">
                                                                                <div class="row g-2">
                                                                                    @foreach($attempt['breakdowns'] as $bd)
                                                                                        <div class="col-6 col-md-3">
                                                                                            <span class="text-muted">{{ $bd['criterion'] }}:</span>
                                                                                            <strong
                                                                                                class="text-dark">{{ $bd['obtained_score'] }}/{{ $bd['max_score'] }}</strong>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        @endif

                                                                        <!-- Attempt Feedback -->
                                                                        <div class="text-muted small" style="line-height: 1.4;">
                                                                            <strong>Feedback:</strong>
                                                                            {{ $attempt['feedback'] ?? 'No feedback left.' }}
                                                                        </div>

                                                                        <!-- Attempt Download -->
                                                                        @if(isset($attempt['file_path']) && $attempt['file_path'])
                                                                            <div class="mt-2 text-end">
                                                                                <a href="{{ strpos($attempt['file_path'], 'mini_project_submissions') === 0 ? asset($attempt['file_path']) : asset('uploads/mini_projects/' . $attempt['file_path']) }}" target="_blank"
                                                                                    class="btn btn-xs btn-link text-decoration-none p-0 fw-semibold text-primary"
                                                                                    style="font-size: 0.8rem;">
                                                                                    <i class="fas fa-download me-1"></i> View Submitted File
                                                                                </a>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-muted small"><i class="far fa-clock me-1"></i> Not yet evaluated</div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted bg-white rounded border">
                                        No submissions pending evaluation.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @endcan

                        <!-- Completed Section Content -->
                        @can('Mini Project Complete')
                        <div id="section-completed-{{ $course->id }}" class="section-group-{{ $course->id }} d-none">
                            <div class="d-flex flex-column gap-3">
                                @forelse($completedSubmissions as $submission)
                                    <div class="submission-row-card">
                                        <div class="p-3 d-flex align-items-center justify-content-between cursor-pointer"
                                            onclick="toggleDetails({{ $submission->id }})">
                                            <!-- Student Name -->
                                            <div style="flex: 2;">
                                                <div class="fw-bold text-dark fs-6">{{ $submission->student->name ?? 'N/A' }}</div>
                                                <div class="text-muted small">{{ $submission->mini_project->course->title ?? 'N/A' }}
                                                </div>
                                            </div>

                                            <!-- Timing -->
                                            <div style="flex: 1;" class="text-muted small">
                                                Weekday
                                            </div>

                                            <!-- Attempts Badge -->
                                            <div style="flex: 1.5;">
                                                @if($submission->attempts > 1)
                                                    <span
                                                        class="badge bg-warning bg-opacity-25 text-warning-emphasis border border-warning border-opacity-50 px-3 py-2 fw-semibold rounded-pill"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fas fa-redo me-1"></i> Reuploaded ({{ $submission->attempts }}/3)
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-primary bg-opacity-25 text-primary-emphasis border border-primary border-opacity-50 px-3 py-2 fw-semibold rounded-pill"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fas fa-upload me-1"></i> Uploaded
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Module -->
                                            <div style="flex: 1;" class="text-muted small">
                                                {{ $submission->mini_project->module ?? 'Module' }}
                                            </div>

                                            <!-- Date -->
                                            <div style="flex: 1.5;" class="text-muted small">
                                                {{ $submission->updated_at->format('d M Y') }}
                                            </div>

                                            <!-- Download File Link -->
                                            <div style="flex: 0.5;">
                                                @if($submission->file_path)
                                                    <a href="{{ strpos($submission->file_path, 'mini_project_submissions') === 0 ? asset($submission->file_path) : asset('uploads/mini_projects/' . $submission->file_path) }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-outline-primary border shadow-sm rounded-circle d-inline-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px;" onclick="event.stopPropagation();">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Edit action -->
                                            <div style="flex: 2;" class="text-end">
                                                <span
                                                    class="badge bg-success bg-opacity-25 text-success-emphasis border border-success border-opacity-50 px-3 py-2 fw-semibold rounded-pill me-2"
                                                    style="font-size: 0.8rem;">
                                                    ✓ Evaluated
                                                </span>
                                                <button class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm"
                                                    style="border-color: #cbd5e1; color: #475569;"
                                                    onclick="event.stopPropagation(); openEvaluationModal({{ json_encode($submission) }})">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Accordion Details panel -->
                                        <div id="details-{{ $submission->id }}" class="bg-light p-3 border-top d-none">
                                            @php
                                                $history = is_string($submission->attempts_history) ? json_decode($submission->attempts_history, true) : ($submission->attempts_history ?? []);
                                                $lastAttempt = end($history);
                                                $previousScore = $lastAttempt ? $lastAttempt['score'] : null;
                                            @endphp
                                            <div class="row align-items-stretch">
                                                <!-- Score -->
                                                <div class="col-md-3 mb-2 mb-md-0">
                                                    <div
                                                        class="score-box rounded-3 p-3 text-center h-100 shadow-sm d-flex flex-column justify-content-center align-items-center">
                                                        <div class="fs-2 fw-bold text-success"
                                                            style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                            {{ $submission->score }}</div>
                                                        <div class="text-muted small fw-bold uppercase-tracking mt-1">Score</div>

                                                        <!-- Dynamic Points Increased Indicator from history -->
                                                        @if($previousScore !== null)
                                                            @php
                                                                $diff = $submission->score - $previousScore;
                                                            @endphp
                                                            <div class="mt-2 small">
                                                                @if($diff > 0)
                                                                    <span class="text-success fw-bold"><i class="fas fa-arrow-up"></i>
                                                                        +{{ $diff }} pts Increase</span>
                                                                @elseif($diff < 0)
                                                                    <span class="text-danger fw-bold"><i class="fas fa-arrow-down"></i>
                                                                        {{ $diff }} pts Decrease</span>
                                                                @else
                                                                    <span class="text-muted fw-bold">No Change</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Grade -->
                                                <div class="col-md-3 mb-2 mb-md-0">
                                                    <div
                                                        class="grade-box rounded-3 p-3 text-center h-100 shadow-sm d-flex flex-column justify-content-center align-items-center">
                                                        <div class="fs-2 fw-bold text-primary"
                                                            style="font-family: 'Plus Jakarta Sans', sans-serif;">
                                                            {{ $submission->grade }}</div>
                                                        <div class="text-muted small fw-bold uppercase-tracking mt-1">Grade</div>
                                                    </div>
                                                </div>

                                                <!-- Feedback -->
                                                <div class="col-md-6">
                                                    <div
                                                        class="feedback-box rounded-3 p-3 h-100 shadow-sm d-flex flex-column justify-content-center">
                                                        <div class="text-muted small fw-bold uppercase-tracking mb-2"><i
                                                                class="far fa-comment-dots me-1 text-primary"></i> Feedback</div>
                                                        <div class="text-dark" style="line-height: 1.5; font-size: 0.95rem;">
                                                            {{ $submission->feedback ?? 'No feedback recorded.' }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- 3 Attempts Full Details Display -->
                                                @if(count($history) > 0)
                                                    <div class="col-12 mt-3 pt-3 border-top">
                                                        <h6 class="fw-bold text-muted uppercase-tracking mb-3" style="font-size: 0.8rem;"><i
                                                                class="fas fa-history me-1 text-warning"></i> Previous Attempts History</h6>
                                                        <div class="d-flex flex-column gap-2">
                                                            @foreach($history as $attempt)
                                                                <div class="bg-white p-3 rounded-3 border shadow-xs">
                                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                                        <div>
                                                                            <span
                                                                                class="badge bg-warning bg-opacity-25 text-warning-emphasis fw-bold rounded-pill">Attempt
                                                                                {{ $attempt['attempt'] }}</span>
                                                                            <span class="text-muted small ms-2"><i
                                                                                    class="far fa-calendar-alt"></i> Graded:
                                                                                {{ \Carbon\Carbon::parse($attempt['evaluated_at'])->format('d M Y, h:i A') }}</span>
                                                                        </div>
                                                                        <div class="text-end">
                                                                            <span class="fw-bold text-dark fs-6">{{ $attempt['score'] }}%</span>
                                                                            <span
                                                                                class="badge bg-light text-dark border ms-1 fw-bold">{{ $attempt['grade'] }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Criterion Score breakdown -->
                                                                    @if(isset($attempt['breakdowns']) && count($attempt['breakdowns']) > 0)
                                                                        <div class="mb-2 bg-light p-2 rounded-2" style="font-size: 0.85rem;">
                                                                            <div class="row g-2">
                                                                                @foreach($attempt['breakdowns'] as $bd)
                                                                                    <div class="col-6 col-md-3">
                                                                                        <span class="text-muted">{{ $bd['criterion'] }}:</span>
                                                                                        <strong
                                                                                            class="text-dark">{{ $bd['obtained_score'] }}/{{ $bd['max_score'] }}</strong>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    <!-- Attempt Feedback -->
                                                                    <div class="text-muted small" style="line-height: 1.4;">
                                                                        <strong>Feedback:</strong>
                                                                        {{ $attempt['feedback'] ?? 'No feedback left.' }}
                                                                    </div>

                                                                    <!-- Attempt Download -->
                                                                    @if(isset($attempt['file_path']) && $attempt['file_path'])
                                                                        <div class="mt-2 text-end">
                                                                            <a href="{{ strpos($attempt['file_path'], 'mini_project_submissions') === 0 ? asset($attempt['file_path']) : asset('uploads/mini_projects/' . $attempt['file_path']) }}" target="_blank"
                                                                                class="btn btn-xs btn-link text-decoration-none p-0 fw-semibold text-primary"
                                                                                style="font-size: 0.8rem;">
                                                                                <i class="fas fa-download me-1"></i> View Submitted File
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted bg-white rounded border">
                                        No evaluated mini_projects found.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        @endcan

                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 mt-4" role="alert"
                style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="fas fa-exclamation-triangle text-warning fs-4"></i>
                    </div>
                    <div>
                        <h5 class="alert-heading fw-bold mb-1 text-dark">No Live Courses Available</h5>
                        <p class="mb-0 text-muted">There are currently no active live courses found in the system. Please
                            ensure live courses are added to manage mini_projects.</p>
                    </div>
                </div>
                <button type="button" class="btn-close mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    @can('Mini Project Evaluate')
    <!-- Evaluation Modal -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" aria-labelledby="evaluationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-0" id="modalTitle"
                            style="font-family: 'Plus Jakarta Sans', sans-serif;">Evaluate Submission</h4>
                        <div class="text-muted small" id="modalSubtitle">Student Details</div>
                    </div>
                    <button type="button" class="btn-close bg-light rounded-circle p-2 border" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <form id="evaluationForm" action="/mini-projects/submissions/0/evaluate" method="POST">
                    @csrf
                    <div class="modal-body px-4 pb-4">
                        <!-- Alert Box with Details -->
                        <div class="alert border-0 p-3 mb-4 d-flex flex-column"
                            style="border-radius: 12px; background-color: #f0f9ff; color: #0369a1;">
                            <span class="fw-bold fs-5" id="alertStudentName">Student Name</span>
                            <span class="small opacity-75 mt-1" id="alertSubmissionMeta">FS-2024-A · Module 9 · Submitted:
                                05 May 2024</span>
                        </div>

                        <!-- Score Breakdown Header -->
                        <h6 class="fw-bold text-muted small uppercase-tracking mb-3" style="font-size: 0.8rem;">Score
                            Breakdown</h6>

                        <!-- Dynamic Breakdown Container -->
                        <div id="criteriaContainer" class="mb-4">
                            <!-- Populated dynamically via JS -->
                        </div>

                        <!-- Total Score Display -->
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded mb-4"
                            style="border-radius: 12px !important;">
                            <span class="fw-bold text-dark"
                                style="font-size: 1.1rem; color: #1e293b; font-family: 'Plus Jakarta Sans', sans-serif;">Total
                                Score</span>
                            <span class="fs-3 fw-bold text-success" id="modalTotalScore">0 / 100</span>
                        </div>

                        <!-- Grade Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small uppercase-tracking mb-2"
                                style="font-size: 0.8rem;">Grade</label>
                            <select name="grade" id="modalGradeSelect" class="form-select border shadow-sm py-2 px-3"
                                style="border-radius: 8px;" required>
                                <option value="">Select Grade</option>
                                <option value="A+">A+ (90-100) — Excellent</option>
                                <option value="A">A (80-89) — Very Good</option>
                                <option value="B">B (70-79) — Good</option>
                                <option value="C">C (60-69) — Satisfactory</option>
                                <option value="D">D (50-59) — Pass</option>
                                <option value="F">F (0-49) — Fail</option>
                            </select>
                        </div>

                        <!-- Feedback Comments -->
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small uppercase-tracking mb-2"
                                style="font-size: 0.8rem;">Feedback to Student</label>
                            <textarea name="feedback" id="modalFeedbackTextarea" class="form-control border shadow-sm p-3"
                                rows="4" style="border-radius: 8px;"
                                placeholder="Write detailed, constructive feedback..."></textarea>
                        </div>

                        <!-- Allow Reupload Setting -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small uppercase-tracking mb-2"
                                style="font-size: 0.8rem;">Allow Reupload After Evaluation</label>
                            <div class="d-flex gap-3 mt-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reupload_approved"
                                        id="modal_reupload_disable" value="0" checked>
                                    <label class="form-check-label text-dark fw-semibold" for="modal_reupload_disable">
                                        <i class="fas fa-ban text-danger me-1"></i> Disable Reupload
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reupload_approved"
                                        id="modal_reupload_enable" value="1">
                                    <label class="form-check-label text-dark fw-semibold" for="modal_reupload_enable">
                                        <i class="fas fa-check-circle text-success me-1"></i> Enable Reupload
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 0.75rem;">If enabled, the student will be permitted
                                to upload a new attempt for this mini_project.</small>
                        </div>
                    </div>

                    <div class="modal-footer border-0 bg-light p-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border px-4 py-2 fw-semibold rounded-pill shadow-sm"
                            data-bs-dismiss="modal"
                            style="background-color: #fff; border-color: #cbd5e1 !important;">Cancel</button>
                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold rounded-pill text-white shadow-sm"
                            style="background-color: #10b981; border: none;" id="modalSubmitBtn">Submit Evaluation
                            ✓</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
@endsection

@push('script')
    <script>
        let evaluationModalInstance = null;

        function switchSection(courseId, sectionName) {
            $(`.section-group-${courseId}`).addClass('d-none');
            $(`#section-${sectionName}-${courseId}`).removeClass('d-none');
            $(`#control-${courseId} .btn`).removeClass('active');
            $(`#control-${courseId} .btn-${sectionName === 'mini_projects' ? 'mini_project' : (sectionName === 'evaluations' ? 'evaluation' : 'completed')}`).addClass('active');
        }

        function toggleDetails(submissionId) {
            let detailsBox = $(`#details-${submissionId}`);
            if (detailsBox.hasClass('d-none')) {
                detailsBox.removeClass('d-none');
            } else {
                detailsBox.addClass('d-none');
            }
        }

        function openEvaluationModal(submission) {
            if (!evaluationModalInstance) {
                evaluationModalInstance = new bootstrap.Modal(document.getElementById('evaluationModal'));
            }

            // Set action url
            let urlTemplate = "{{ route('mini_projects.storeEvaluation', ['submission' => ':id']) }}";
            let actionUrl = urlTemplate.replace(':id', submission.id);
            $('#evaluationForm').attr('action', actionUrl);

            // Prep data
            let courseName = submission.mini_project && submission.mini_project.course ? submission.mini_project.course.title : 'N/A';
            let studentName = submission.student ? submission.student.name : 'Unknown Student';
            let moduleName = submission.mini_project ? submission.mini_project.module : 'Unknown Module';
            let submittedDate = new Date(submission.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });

            $('#modalSubtitle').text(`${studentName} · ${courseName}`);
            $('#alertStudentName').text(studentName);
            $('#alertSubmissionMeta').text(`${courseName} · ${moduleName} · Submitted: ${submittedDate}`);

            let criteriaContainer = $('#criteriaContainer');
            criteriaContainer.empty();

            let criteriaList = (submission.mini_project && submission.mini_project.criteria) ? submission.mini_project.criteria : [];
            let scoreBreakdowns = submission.score_breakdowns ? submission.score_breakdowns : [];

            if (criteriaList.length > 0) {
                criteriaList.forEach((criterion, idx) => {
                    let maxMark = Math.round(criterion.maximum_mark || 100);

                    // Find previous score breakdown if editing
                    let prevScore = 0;
                    let existingBreakdown = scoreBreakdowns.find(sb => sb.criterion === criterion.criterion);
                    if (existingBreakdown) {
                        prevScore = existingBreakdown.obtained_score || 0;
                    }

                    let html = `
                        <div class="row align-items-center mb-3">
                            <div class="col-8">
                                <span class="fw-semibold text-dark" style="font-size: 1rem;">${criterion.criterion}</span>
                                <input type="hidden" name="breakdowns[${idx}][criterion]" value="${criterion.criterion}">
                            </div>
                            <div class="col-2 text-end text-muted small">
                                / ${maxMark} pts
                                <input type="hidden" name="breakdowns[${idx}][max_score]" value="${maxMark}" class="modal-max-input">
                            </div>
                            <div class="col-2">
                                <input type="number" 
                                       name="breakdowns[${idx}][obtained_score]" 
                                       class="form-control modal-obtained-input text-center py-2 border shadow-sm" 
                                       style="border-radius: 8px; width: 80px;" 
                                       min="0" 
                                       max="${maxMark}" 
                                       step="any"
                                       value="${prevScore}" 
                                       required>
                            </div>
                        </div>
                    `;
                    criteriaContainer.append(html);
                });
            } else {
                let prevScore = submission.score || 0;
                let html = `
                    <div class="row align-items-center mb-3">
                        <div class="col-8">
                            <span class="fw-semibold text-dark" style="font-size: 1rem;">General Submission</span>
                            <input type="hidden" name="breakdowns[0][criterion]" value="General Submission">
                        </div>
                        <div class="col-2 text-end text-muted small">
                            / 100 pts
                            <input type="hidden" name="breakdowns[0][max_score]" value="100" class="modal-max-input">
                        </div>
                        <div class="col-2">
                            <input type="number" 
                                   name="breakdowns[0][obtained_score]" 
                                   class="form-control modal-obtained-input text-center py-2 border shadow-sm" 
                                   style="border-radius: 8px; width: 80px;" 
                                   min="0" 
                                   max="100" 
                                   step="any"
                                   value="${prevScore}" 
                                   required>
                        </div>
                    </div>
                `;
                criteriaContainer.append(html);
            }

            // Set values
            $('#modalGradeSelect').val(submission.grade || '');
            $('#modalFeedbackTextarea').val(submission.feedback || '');

            if (submission.reupload_approved == 1) {
                $('#modal_reupload_enable').prop('checked', true);
            } else {
                $('#modal_reupload_disable').prop('checked', true);
            }

            if (submission.status === 'evaluated' || submission.status === 'graded') {
                $('#modalTitle').text('Edit Evaluation');
                $('#modalSubmitBtn').text('Save Changes ✓');
            } else {
                $('#modalTitle').text('Evaluate Submission');
                $('#modalSubmitBtn').text('Submit Evaluation ✓');
            }

            $('.modal-obtained-input').off('input change').on('input change', calculateModalScores);
            calculateModalScores();
            evaluationModalInstance.show();
        }

        function calculateModalScores() {
            let totalObtained = 0;
            let totalMax = 0;

            $('.modal-max-input').each(function () {
                totalMax += parseFloat($(this).val()) || 0;
            });

            $('.modal-obtained-input').each(function () {
                totalObtained += parseFloat($(this).val()) || 0;
            });

            $('#modalTotalScore').html(`${totalObtained} / ${totalMax}`);
        }
    </script>
    <script>
        $(document).ready(function () {
            // Setup CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.status-toggle', function () {
                var btn = $(this);
                var url = btn.data('url');
                var newStatus = btn.data('change-status');
                var actionText = newStatus === 'active' ? 'Approve (Active)' : 'Set to Pending';
                var confirmColor = newStatus === 'active' ? '#198754' : '#ffc107';

                Swal.fire({
                    title: 'Update Status',
                    text: 'Do you want to change this status to ' + (newStatus === 'active' ? 'Approved' : 'Pending') + '?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, ' + actionText,
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: confirmColor,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                status: newStatus
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('Updated!', response.message, 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('Error!', 'Could not update status.', 'error');
                                }
                            },
                            error: function (xhr) {
                                Swal.fire('Error!', 'An error occurred while updating status.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush