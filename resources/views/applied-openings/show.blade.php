@extends('layouts.header')
@section('pageTitle', ($opening->job_role ?? 'Opening') . ' — Applied Students | ' . config('app.name'))

@push('css')
<style>
    :root {
        --ao-border: #e2e8f0;
        --ao-border-light: #f1f5f9;
        --ao-text-primary: #0f172a;
        --ao-text-secondary: #64748b;
        --ao-text-muted: #94a3b8;
        --ao-primary: #2563eb;
        --ao-success: #10b981;
        --ao-warning: #f59e0b;
        --ao-danger: #ef4444;
        --ao-purple: #8b5cf6;
        --ao-orange: #ea580c;
        --ao-teal: #06b6d4;
    }

    .ao-page-wrapper {
        padding: 0;
        margin-bottom: 2.5rem;
    }

    .ao-header-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--ao-text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ao-badge {
        background: rgba(37, 99, 235, 0.08);
        color: var(--ao-primary);
        border: 1px solid rgba(37, 99, 235, 0.2);
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }

    /* Stat Cards */
    .ao-stat-card {
        background: #ffffff;
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        padding: 16px 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .ao-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .ao-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--ao-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .ao-stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        line-height: 1;
        color: var(--ao-text-primary);
    }

    /* Opening Info Banner */
    .ao-info-panel {
        background: #ffffff;
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .ao-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 16px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid var(--ao-border-light);
    }

    .ao-meta-item .lbl {
        font-size: 0.72rem;
        color: var(--ao-text-muted);
        text-transform: uppercase;
        font-weight: 600;
    }

    .ao-meta-item .val {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--ao-text-primary);
        margin-top: 2px;
    }

    /* Pipeline Nav Tabs */
    .ao-pipeline-tabs {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        background: #f8fafc;
        padding: 6px;
        border-radius: 10px;
        border: 1px solid var(--ao-border);
    }

    .ao-pipeline-tab-btn {
        background: transparent;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--ao-text-secondary);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
    }

    .ao-pipeline-tab-btn:hover {
        color: var(--ao-text-primary);
        background: rgba(0, 0, 0, 0.03);
    }

    .ao-pipeline-tab-btn.active {
        background: #ffffff;
        color: var(--ao-primary);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    }

    .ao-tab-count {
        background: rgba(100, 116, 139, 0.12);
        color: var(--ao-text-secondary);
        font-size: 0.72rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
    }

    .ao-pipeline-tab-btn.active .ao-tab-count {
        background: rgba(37, 99, 235, 0.12);
        color: var(--ao-primary);
    }

    /* Table */
    .ao-table-wrapper {
        background: #ffffff;
        border: 1px solid var(--ao-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .ao-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .ao-table th {
        background: #f8fafc;
        padding: 12px 18px;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--ao-text-secondary);
        font-weight: 700;
        border-bottom: 1px solid var(--ao-border);
    }

    .ao-table td {
        padding: 14px 18px;
        border-bottom: 1px solid var(--ao-border-light);
        vertical-align: middle;
        font-size: 0.88rem;
    }

    .ao-table tr:hover {
        background: #fbfdff;
    }

    .ao-table tr:last-child td {
        border-bottom: none;
    }
</style>
@endpush

@section('section')
<div class="ao-page-wrapper">
    <!-- Breadcrumb & Actions -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('applied-openings.index') }}" class="text-decoration-none text-muted" style="font-size:0.85rem;">
                    <i class="fas fa-arrow-left me-1"></i> Applied Openings
                </a>
                <span class="text-muted">&bull;</span>
                <span class="text-muted" style="font-size:0.85rem;">Workspace</span>
            </div>
            <h1 class="ao-header-title">
                {{ $opening->company->company_name ?? 'Company' }} &mdash; {{ $opening->job_role ?? 'Opening' }}
                <span class="ao-badge">{{ $opening->opening_status ?? 'Active' }}</span>
            </h1>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('eligible-students.index') }}?opening={{ encrypt($opening->id) }}" class="btn btn-outline-primary btn-sm px-3 fw-bold">
                <i class="fas fa-paper-plane me-1"></i> Push to More Students
            </a>
            <a href="{{ route('current-openings.show', $opening->id) }}" class="btn btn-outline-secondary btn-sm px-3">
                <i class="fas fa-eye me-1"></i> View JD & Details
            </a>
        </div>
    </div>



    <!-- Stat Counters -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3 col-lg">
            <div class="ao-stat-card" style="cursor:pointer;" onclick="switchPipelineTab('applied')">
                <div class="ao-stat-label">Total Applied</div>
                <div class="ao-stat-value" style="color:var(--ao-orange)">{{ $tabCounts['all'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="ao-stat-card" style="cursor:pointer;" onclick="switchPipelineTab('applied')">
                <div class="ao-stat-label">Pending Forward</div>
                <div class="ao-stat-value" style="color:var(--ao-primary)">{{ $tabCounts['applied'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="ao-stat-card" style="cursor:pointer;" onclick="switchPipelineTab('sent_hr')">
                <div class="ao-stat-label">Sent to HR</div>
                <div class="ao-stat-value" style="color:var(--ao-teal)">{{ $tabCounts['sent_hr'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="ao-stat-card" style="cursor:pointer;" onclick="switchPipelineTab('scheduled')">
                <div class="ao-stat-label">Scheduled</div>
                <div class="ao-stat-value" style="color:var(--ao-purple)">{{ $tabCounts['scheduled'] }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 col-lg">
            <div class="ao-stat-card" style="cursor:pointer;" onclick="switchPipelineTab('selected')">
                <div class="ao-stat-label">Selected</div>
                <div class="ao-stat-value" style="color:var(--ao-success)">{{ $tabCounts['selected'] }}</div>
            </div>
        </div>
    </div>

    <!-- Opening Details Strip -->
    <div class="ao-info-panel">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h5 class="fw-bold mb-1 text-dark">{{ $opening->job_role ?? 'N/A' }}</h5>
                <p class="text-muted mb-0" style="font-size:0.85rem;">
                    <i class="fas fa-building me-1 text-primary"></i> {{ $opening->company->company_name ?? '—' }} &bull;
                    <i class="fas fa-map-marker-alt ms-2 me-1 text-danger"></i> {{ $opening->job_location ?? 'Any' }} &bull;
                    <i class="fas fa-briefcase ms-2 me-1 text-secondary"></i> {{ $opening->experience_required ?? 'Fresher' }}
                </p>
            </div>
            <div class="text-end">
                <div class="fw-bold text-dark fs-5">{{ $opening->ctc_salary ?? 'Not Disclosed' }}</div>
                <div class="text-muted" style="font-size:0.75rem;">Deadline: {{ $opening->application_deadline ? \Carbon\Carbon::parse($opening->application_deadline)->format('d M, Y') : 'Open' }}</div>
            </div>
        </div>
        <div class="ao-meta-grid">
            <div class="ao-meta-item">
                <div class="lbl">HR Contact Name</div>
                <div class="val">{{ $opening->hr_name ?? ($opening->company->hr_name ?? 'N/A') }}</div>
            </div>
            <div class="ao-meta-item">
                <div class="lbl">HR Email</div>
                <div class="val">{{ $opening->hr_email_id ?? ($opening->company->hr_email ?? 'N/A') }}</div>
            </div>
            <div class="ao-meta-item">
                <div class="lbl">HR Phone Number</div>
                <div class="val">{{ $opening->hr_contact_number ?? ($opening->company->hr_contact_number ?? 'N/A') }}</div>
            </div>
            <div class="ao-meta-item">
                <div class="lbl">Slots / Vacancies</div>
                <div class="val">{{ $opening->number_of_openings ?? 1 }} openings</div>
            </div>
        </div>
    </div>

    <!-- Pipeline Tabs -->
    <div class="ao-pipeline-tabs">
        <button type="button" class="ao-pipeline-tab-btn active" data-tab="applied" onclick="switchPipelineTab('applied')">
            <i class="fas fa-inbox text-warning"></i> Applied (Pending Review)
            <span class="ao-tab-count">{{ $tabCounts['applied'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="sent_hr" onclick="switchPipelineTab('sent_hr')">
            <i class="fas fa-paper-plane text-info"></i> Profile Sent to HR
            <span class="ao-tab-count">{{ $tabCounts['sent_hr'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="scheduled" onclick="switchPipelineTab('scheduled')">
            <i class="fas fa-calendar-check text-primary"></i> Interview Scheduled
            <span class="ao-tab-count">{{ $tabCounts['scheduled'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="attended" onclick="switchPipelineTab('attended')">
            <i class="fas fa-user-check text-secondary"></i> Attended
            <span class="ao-tab-count">{{ $tabCounts['attended'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="selected" onclick="switchPipelineTab('selected')">
            <i class="fas fa-award text-success"></i> Selected
            <span class="ao-tab-count">{{ $tabCounts['selected'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="rejected" onclick="switchPipelineTab('rejected')">
            <i class="fas fa-times-circle text-danger"></i> Rejected
            <span class="ao-tab-count">{{ $tabCounts['rejected'] }}</span>
        </button>
        <button type="button" class="ao-pipeline-tab-btn" data-tab="hold" onclick="switchPipelineTab('hold')">
            <i class="fas fa-pause-circle text-warning"></i> On Hold
            <span class="ao-tab-count">{{ $tabCounts['hold'] }}</span>
        </button>
    </div>

    <!-- Tab Content: Applied (Pending Forward) -->
    <div class="pipeline-tab-pane" id="tab-applied">
        <div class="ao-table-wrapper">
            <table class="ao-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Phone / Email</th>
                        <th>Course & Batch</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingApplicants as $idx => $app)
                        @php
                            $user = $app->user;
                            $cu = $app->courseUser;
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $user->name ?? 'Student #'.$app->user_id }}</div>
                                <span class="badge bg-light text-muted border" style="font-size:0.65rem;">ID: V{{ str_pad($app->user_id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>
                                <div>{{ $user->phonenumber ?? '—' }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $user->email ?? '—' }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $cu->course->title ?? '—' }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">Batch: {{ $cu->batch->batch_code ?? '—' }}</div>
                            </td>
                            <td>
                                <div>{{ $app->created_at ? $app->created_at->format('d M, Y') : '—' }}</div>
                                <div class="text-muted" style="font-size:0.75rem;">{{ $app->created_at ? $app->created_at->diffForHumans() : '' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-warning text-dark px-2.5 py-1">Pending Forward</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($cu)
                                        <a href="{{ route('eligible-students.show', $cu->id) }}" class="btn btn-sm btn-outline-secondary">
                                            View Profile
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-primary fw-bold"
                                        onclick="openSendToHrModal({{ $app->id }}, '{{ addslashes($user->name ?? 'Student') }}', '{{ addslashes($opening->hr_email_id ?? $opening->company->hr_email ?? '') }}')">
                                        <i class="fas fa-paper-plane me-1"></i> Send to HR
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-check-circle fs-3 d-block mb-2 text-success opacity-50"></i>
                                All applicants have been forwarded to HR or no new applications yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tab Content: Sent to HR -->
    <div class="pipeline-tab-pane d-none" id="tab-sent_hr">
        <div class="ao-table-wrapper">
            <table class="ao-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Contact</th>
                        <th>Course</th>
                        <th>Sent to HR At</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sentHrApplicants as $app)
                        @php
                            $user = $app->user;
                            $cu = $app->courseUser;
                        @endphp
                        <tr>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $user->name ?? 'Student #'.$app->user_id }}</div>
                                <span class="badge bg-light text-muted border" style="font-size:0.65rem;">ID: V{{ str_pad($app->user_id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td>{{ $user->phonenumber ?? '—' }}</td>
                            <td>{{ $cu->course->title ?? '—' }} ({{ $cu->batch->batch_code ?? '—' }})</td>
                            <td>{{ $app->sent_to_hr_at ? \Carbon\Carbon::parse($app->sent_to_hr_at)->format('d M, Y h:i A') : $app->updated_at->format('d M, Y') }}</td>
                            <td>
                                <span class="badge bg-info text-white px-2.5 py-1">Profile Sent to HR</span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($cu)
                                        <a href="{{ route('eligible-students.show', $cu->id) }}" class="btn btn-sm btn-outline-secondary">
                                            View Profile
                                        </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-success fw-bold"
                                        onclick="prefillScheduleInterview('{{ addslashes($user->name ?? '') }}', '{{ addslashes($user->phonenumber ?? '') }}', '{{ addslashes($user->email ?? '') }}', '{{ addslashes($cu->course->title ?? '') }}', '{{ addslashes($cu->batch->batch_code ?? '') }}')">
                                        <i class="fas fa-calendar-plus me-1"></i> Schedule Interview
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No profiles sent to HR yet for this opening.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tag based Tabs: Scheduled, Attended, Selected, Rejected, Hold -->
    @foreach(['scheduled', 'attended', 'selected', 'rejected', 'hold'] as $tag)
        <div class="pipeline-tab-pane d-none" id="tab-{{ $tag }}">
            <div class="ao-table-wrapper">
                <table class="ao-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Phone</th>
                            <th>Date & Time</th>
                            <th>Interview Mode</th>
                            <th>Result / Status</th>
                            <th>Feedback / Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($byTag[$tag] as $intv)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $intv->name }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $intv->course ?? '—' }}</div>
                                </td>
                                <td>{{ $intv->phone }}</td>
                                <td>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($intv->date)->format('d M, Y') }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ $intv->time ?? '—' }}</div>
                                </td>
                                <td>{{ $intv->interview_mode ?? 'Online' }}</td>
                                <td>
                                    @php
                                        $badgeColor = match(strtolower($intv->status)) {
                                            'selected' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            'hold', 'on hold' => 'bg-warning text-dark',
                                            'attended' => 'bg-info',
                                            default => 'bg-primary'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeColor }} px-2.5 py-1 text-capitalize">
                                        {{ $intv->status }}
                                    </span>
                                </td>
                                <td style="max-width:200px;">
                                    <span class="text-muted" style="font-size:0.85rem;">{{ $intv->hr_feedback ?? $intv->reason ?? '—' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    No interviews found in "{{ ucfirst(str_replace('_', ' ', $tag)) }}" stage.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal: Send to HR via Mailchimp -->
<div class="modal fade" id="sendToHrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-paper-plane text-primary me-2"></i> Forward Applicant to HR
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendToHrForm">
                @csrf
                <input type="hidden" name="application_id" id="forwardAppId">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Candidate Name</label>
                        <input type="text" id="forwardStudentName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">HR Email ID *</label>
                        <input type="email" name="hr_email" id="forwardHrEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Profile Link Type *</label>
                        <select name="link_type" class="form-select" required>
                            <option value="combined" selected>Combined Profile (Resume + Portfolio + GitHub)</option>
                            <option value="resume">Resume PDF Link</option>
                            <option value="portfolio">Portfolio Website</option>
                            <option value="project">Project / GitHub URL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Email Subject *</label>
                        <input type="text" name="subject" id="forwardSubject" class="form-control" value="Candidate Profile for {{ $opening->job_role ?? 'Position' }} &mdash; {{ $opening->company->company_name ?? '' }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold" style="font-size:0.85rem;">Email Body / Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">Dear HR,

Please find attached the shortlisted candidate profile for your kind review for the position of {{ $opening->job_role ?? 'Job Role' }}.

Best regards,
Placement Cell</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSubmitSendHr">
                        <i class="fas fa-check me-1"></i> Send to HR
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Schedule Interview -->
<div class="modal fade" id="quickScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">
                    <i class="fas fa-calendar-check text-success me-2"></i> Schedule Interview
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="quickScheduleForm" action="{{ route('schedule-interviews.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Student Name *</label>
                            <input type="text" name="name" id="sch_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Phone *</label>
                            <input type="text" name="phone" id="sch_phone" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Email *</label>
                            <input type="email" name="email" id="sch_email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Course *</label>
                            <input type="text" name="course" id="sch_course" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Batch Code *</label>
                            <input type="text" name="batchCode" id="sch_batch" class="form-control" required>
                        </div>
                        <input type="hidden" name="duration" value="6 Months">
                        <input type="hidden" name="batchType" value="Regular">
                        <input type="hidden" name="company" value="{{ $opening->company->company_name ?? '' }}">
                        <input type="hidden" name="role" value="{{ $opening->job_role ?? '' }}">
                        <input type="hidden" name="location" value="{{ $opening->job_location ?? '' }}">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Interview Date *</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Interview Time *</label>
                            <input type="time" name="time" class="form-control" value="10:00" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Interview Mode *</label>
                            <select name="interviewMode" class="form-select" required>
                                <option value="Online" selected>Online</option>
                                <option value="Offline">Offline</option>
                                <option value="Walk-in">Walk-in</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold small">Meeting Link (Optional)</label>
                            <input type="url" name="meetingLink" class="form-control" placeholder="https://meet.google.com/...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="fas fa-check me-1"></i> Save Interview
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchPipelineTab(tabName) {
        // Toggle tab buttons active state
        var buttons = document.querySelectorAll('.ao-pipeline-tab-btn');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].classList.remove('active');
            if (buttons[i].getAttribute('data-tab') === tabName) {
                buttons[i].classList.add('active');
            }
        }

        // Hide all panes
        var panes = document.querySelectorAll('.pipeline-tab-pane');
        for (var j = 0; j < panes.length; j++) {
            panes[j].classList.add('d-none');
            panes[j].style.setProperty('display', 'none', 'important');
        }

        // Show selected pane
        var activePane = document.getElementById('tab-' + tabName);
        if (activePane) {
            activePane.classList.remove('d-none');
            activePane.style.setProperty('display', 'block', 'important');
        }
    }
</script>
@endsection

@push('script')
<script>
    // Tab switching event listener delegation
    $(document).ready(function() {
        $(document).on('click', '.ao-pipeline-tab-btn', function() {
            var tab = $(this).attr('data-tab');
            if (tab) {
                switchPipelineTab(tab);
            }
        });
    });

    // Send to HR Modal
    function openSendToHrModal(appId, studentName, hrEmail) {
        document.getElementById('forwardAppId').value = appId;
        document.getElementById('forwardStudentName').value = studentName;
        document.getElementById('forwardHrEmail').value = hrEmail;
        var modal = new bootstrap.Modal(document.getElementById('sendToHrModal'));
        modal.show();
    }

    document.getElementById('sendToHrForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSubmitSendHr');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending Email...';

        const appId = document.getElementById('forwardAppId').value;
        const hrEmail = document.getElementById('forwardHrEmail').value;
        const linkType = document.querySelector('select[name="link_type"]').value;
        const subject = document.getElementById('forwardSubject').value;
        const remarks = document.querySelector('textarea[name="remarks"]').value;

        fetch('{{ route("applied-openings.forward-to-hr") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                application_id: appId,
                hr_email: hrEmail,
                link_type: linkType,
                subject: subject,
                remarks: remarks
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                bootstrap.Modal.getInstance(document.getElementById('sendToHrModal')).hide();
                alert(data.message || 'Profile forwarded to HR successfully!');
                window.location.reload();
            } else {
                alert(data.message || 'Error forwarding to HR');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Send to HR';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error while sending to HR.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-1"></i> Send to HR';
        });
    });

    // Schedule Interview Prefill
    function prefillScheduleInterview(name, phone, email, course, batch) {
        document.getElementById('sch_name').value = name;
        document.getElementById('sch_phone').value = phone;
        document.getElementById('sch_email').value = email;
        document.getElementById('sch_course').value = course;
        document.getElementById('sch_batch').value = batch;
        var modal = new bootstrap.Modal(document.getElementById('quickScheduleModal'));
        modal.show();
    }

    document.getElementById('quickScheduleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status) {
                bootstrap.Modal.getInstance(document.getElementById('quickScheduleModal')).hide();
                alert('Interview scheduled successfully!');
                window.location.reload();
            } else {
                alert(data.message || 'Could not schedule interview.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred.');
        });
    });
</script>
@endpush
