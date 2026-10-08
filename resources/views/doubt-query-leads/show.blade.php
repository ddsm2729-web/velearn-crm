@extends('layouts.master')

@section('pageTitle', 'Lead Profile - ' . ($call->contact->name ?? 'Unknown'))

@section('content')
    <div class="container-fluid p-0 lead-profile-container">
        {{-- Header / Back Button --}}
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ session('support_back_url', route('doubt-query-leads.index')) }}"
                    class="text-primary text-decoration-none d-inline-flex align-items-center back-btn">
                    <i class="fas fa-arrow-left me-2"></i> Back to
                    {{ str_contains(session('support_back_url', ''), 'outbound') ? 'Outbound' : 'Inbound' }} Leads
                </a>
            </div>
        </div>

        {{-- Main Profile Card --}}
        <div class="card profile-card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-avatar me-3">
                            {{ strtoupper(substr($call->contact->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $call->contact->name ?? 'Unknown Caller' }}</h2>
                            <div class="d-flex align-items-center mt-1">
                                <span
                                    class="badge bg-danger text-uppercase px-2 py-1 me-2">{{ $call->lead_tag ?? 'NEW' }}</span>
                                <span class="text-muted small">{{ $call->contact->register_number ?? 'N/A' }}
                                    Assigned → <span class="text-primary fw-bold">
                                        @if ($call->assigned_staff_id == auth()->id())
                                            Self
                                        @else
                                            {{ $call->assignedStaff->name ?? 'None' }}
                                        @endif
                                    </span></span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm px-3 rounded-pill" data-bs-toggle="modal"
                            data-bs-target="#editStudentModal">
                            <i class="fas fa-edit me-1"></i> Edit
                        </button>
                        @can('Schedule Demo')
                            <a href="{{ route('schedules.index', ['contact_id' => $call->contact->id]) }}"
                                class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
                                <i class="fas fa-calendar-check me-1"></i> Schedule Demo
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="row g-3">
                    {{-- Detail Boxes --}}
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">PHONE</label>
                            <div class="fw-bold">{{ $call->caller_number }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">EMAIL</label>
                            <div class="fw-bold">{{ $call->contact->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">SOURCE</label>
                            <div class="fw-bold">{{ $call->contact->source ?? 'MyOperator' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LOCATION</label>
                            <div class="fw-bold">{{ $call->contact->location ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">SECONDARY</label>
                            <div class="fw-bold">{{ $call->contact->secondary_number ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LEAD DATE</label>
                            <div class="fw-bold">
                                {{ $call->contact && $call->contact->created_at ? $call->contact->created_at->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">ASSIGNMENT DETAILS</label>
                            <div class="fw-bold">
                                @if ($call->assigned_staff_id == auth()->id())
                                    Self
                                @else
                                    {{ $call->assignedStaff->name ?? 'None' }}
                                @endif
                            </div>
                            @if($call->followup_date)
                                <div class="small text-info mt-1" style="font-size: 0.8rem;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $call->followup_date->format('d M Y') }}
                                    {{ $call->followup_time ? \Carbon\Carbon::parse($call->followup_time)->format('h:i A') : '' }}
                                </div>
                            @else
                                <div class="small text-muted mt-1" style="font-size: 0.8rem;">
                                    No follow-up scheduled
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="detail-box p-3 rounded-3 h-100">
                            <label class="text-muted smaller d-block mb-1">LEAD TYPE</label>
                            <div class="fw-bold">{{ $call->contact->source == 'MyOperator' ? 'Inbound' : 'Outbound' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Timeline Section --}}
        <div class="card timeline-card border-0 shadow-sm">
            <div class="card-header border-0 bg-white p-0">
                {{-- Navigation Tabs --}}
                <ul class="nav nav-tabs nav-tabs-custom border-bottom px-4" id="timelineTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active fw-bold py-3" id="call-history-tab" data-bs-toggle="tab"
                            data-bs-target="#call-history" type="button" role="tab">
                            <i class="fas fa-phone-alt me-2"></i> Call History
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link fw-bold py-3" id="lms-tab" data-bs-toggle="tab" data-bs-target="#lms"
                            type="button" role="tab">
                            <i class="fas fa-layer-group me-2"></i> LMS
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content" id="timelineTabsContent">
                    <div class="tab-pane fade show active" id="call-history" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i> Timeline <span
                                    class="text-muted small fw-normal ms-2">(Previous calls + current active call)</span>
                            </h5>
                            <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal"
                                data-bs-target="#addEntryModal">
                                <i class="fas fa-plus me-1"></i> Add Entry
                            </button>
                        </div>
                        {{-- PREVIOUS SECTION --}}
                        <div class="timeline-section mb-5">
                            <div class="accordion-header-custom p-3 rounded-3 d-flex align-items-center justify-content-between mb-2"
                                style="cursor: pointer; background: #f1f3f5; transition: all 0.2s;"
                                data-bs-toggle="collapse" data-bs-target="#previousTimeline" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <span class="text-uppercase fw-bold text-muted smaller me-2">PREVIOUS</span>
                                    <span class="text-muted small">({{ $previousLogs->count() }} calls — already
                                        spoken)</span>
                                </div>
                                <i class="fas fa-chevron-down text-muted transition-icon"></i>
                            </div>

                            <div class="collapse" id="previousTimeline">
                                <div class="table-responsive mt-3">
                                    <table class="table timeline-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>CALL TIME</th>
                                                <th>DIRECTION</th>
                                                <th>RECORDING</th>
                                                <th>DURATION</th>
                                                <th>OWNED BY</th>
                                                <th>TAG</th>
                                                <th>NOTES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($previousLogs as $log)
                                                                                <tr>
                                                                                    <td>
                                                                                        <div class="fw-bold">
                                                                                            {{ $log->created_at ? $log->created_at->format('h:i A') : 'N/A' }}
                                                                                        </div>
                                                                                        <div class="text-muted smaller">
                                                                                            {{ $log->created_at ? $log->created_at->format('d M') : '' }}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        @php
                                                                                            $dir = $log->direction;
                                                                                            if (!$dir && $log->full_log) {
                                                                                                $fl = $log->full_log;
                                                                                                $type = (int) ($fl['_ty'] ?? 1);
                                                                                                $dir = $type == 2 ? 'Outgoing' : 'Incoming';
                                                                                                $agentAction = $fl['_ld'][0]['_ac'] ?? '';
                                                                                                if (
                                                                                                    $agentAction == 'missed' ||
                                                                                                    ($fl['_su'] ?? 0) == 2
                                                                                                ) {
                                                                                                    $dir = 'Missed';
                                                                                                }
                                                                                            }
                                                                                            if (!$dir) {
                                                                                                $dir = $log->contact->source;
                                                                                            }
                                                                                        @endphp
                                                                                        @if ($dir == 'Outgoing' || $dir == 'Outbound')
                                                                                            <span
                                                                                                class="badge bg-soft-warning text-warning px-3 rounded-pill border border-warning border-opacity-25">
                                                                                                <i class="fas fa-arrow-up me-1"></i> OUTGOING
                                                                                            </span>
                                                                                        @elseif($dir == 'Missed')
                                                                                            <span
                                                                                                class="badge bg-soft-danger text-danger px-3 rounded-pill border border-danger border-opacity-25">
                                                                                                <i class="fas fa-phone-slash me-1"></i> MISSED
                                                                                            </span>
                                                                                        @else
                                                                                            <span
                                                                                                class="badge bg-soft-success text-success px-3 rounded-pill border border-success border-opacity-25">
                                                                                                <i class="fas fa-arrow-down me-1"></i> INCOMING
                                                                                            </span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        @if ($log->recording_url)
                                                                                            <button class="btn btn-soft-primary btn-sm rounded px-3 py-1 play-btn"
                                                                                                data-id="{{ $log->id }}">
                                                                                                <i class="fas fa-play me-1"></i> Play <span
                                                                                                    class="text-muted small ms-1">{{ $log->call_duration }}</span>
                                                                                            </button>
                                                                                        @else
                                                                                            <span class="text-muted">—</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-dark">{{ $log->call_duration ?? '0s' }}</div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <div class="text-dark">{{ $log->assignedStaff->name ?? 'Self' }}
                                                                                        </div>
                                                                                    </td>
                                                                                    <td>
                                                                                        <span
                                                                                            class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold"
                                                                                            style="font-size: 0.6rem;">
                                                                                            {{ $log->lead_tag ?? 'NONE' }}
                                                                                        </span>
                                                                                    </td>
                                                                                    <td>

                                                                                        {{-- Main Note --}}
                                                                                        <div class="mb-2 p-3 rounded border bg-white shadow-sm" style="
                                                    max-width: 280px;
                                                    white-space: normal;
                                                    word-break: break-word;
                                                    overflow-wrap: break-word;
                                                    line-height: 1.5;
                                                ">
                                                                                            <span class="text-muted small">
                                                                                                {{ $log->note ?: 'No notes recorded.' }}
                                                                                            </span>
                                                                                        </div>

                                                                                        {{-- Followup Note --}}
                                                                                        @if ($log->followup_note)
                                                                                                                                <div class="mb-2 p-3 rounded border bg-light-info shadow-sm" style="
                                                                                                max-width: 280px;
                                                                                                white-space: normal;
                                                                                                word-break: break-word;
                                                                                                overflow-wrap: break-word;
                                                                                                line-height: 1.5;
                                                                                                background: #eaf7ff;
                                                                                                border-color: #b6e0fe !important;
                                                                                            ">

                                                                                                                                    <div class="d-flex align-items-start">
                                                                                                                                        <i class="fas fa-reply text-info me-2 mt-1"></i>

                                                                                                                                        <span class="text-info small">
                                                                                                                                            {{ $log->followup_note }}
                                                                                                                                        </span>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                        @endif

                                                                                        {{-- Demo Schedule --}}
                                                                                        @if ($log->demo_date)
                                                                                                                                <div class="mb-2 p-3 rounded border shadow-sm" style="
                                                                                                max-width: 280px;
                                                                                                background: #eef4ff;
                                                                                                border-color: #cfe0ff !important;
                                                                                            ">

                                                                                                                                    <div class="fw-bold text-primary small mb-2">
                                                                                                                                        <i class="fas fa-video me-1"></i>
                                                                                                                                        Demo Scheduled
                                                                                                                                    </div>

                                                                                                                                    <div class="small text-muted">
                                                                                                                                        <div class="mb-1">
                                                                                                                                            Date:
                                                                                                                                            <span class="fw-semibold text-dark">
                                                                                                                                                {{ $log->demo_date->format('d M Y') }}
                                                                                                                                            </span>
                                                                                                                                        </div>

                                                                                                                                        <div>
                                                                                                                                            Time:
                                                                                                                                            <span class="fw-semibold text-dark">
                                                                                                                                                {{ $log->demo_time ?? 'Not set' }}
                                                                                                                                            </span>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                        @endif

                                                                                        {{-- Followup Schedule --}}
                                                                                        @if ($log->followup_date)
                                                                                                                                <div class="p-3 rounded border shadow-sm" style="
                                                                                                max-width: 280px;
                                                                                                background: #edf9ff;
                                                                                                border-color: #bfe7ff !important;
                                                                                            ">

                                                                                                                                    <div class="fw-bold text-info small mb-2">
                                                                                                                                        <i class="fas fa-calendar-alt me-1"></i>
                                                                                                                                        Follow-up Scheduled
                                                                                                                                    </div>

                                                                                                                                    <div class="small text-muted">
                                                                                                                                        <div class="mb-1">
                                                                                                                                            Date:
                                                                                                                                            <span class="fw-semibold text-dark">
                                                                                                                                                {{ $log->followup_date->format('d M Y') }}
                                                                                                                                            </span>
                                                                                                                                        </div>

                                                                                                                                        <div>
                                                                                                                                            Time:
                                                                                                                                            <span class="fw-semibold text-dark">
                                                                                                                                                {{ $log->followup_time ?? 'Not set' }}
                                                                                                                                            </span>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                        @endif

                                                                                    </td>
                                                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-3 text-muted small">No
                                                        previous call
                                                        history found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- CURRENT SECTION --}}
                        <div class="timeline-section">
                            <div class="timeline-label mb-3 d-flex align-items-center">
                                <span class="text-uppercase fw-bold text-primary smaller me-2">
                                    <i class="fas fa-circle me-1" style="font-size: 0.5rem; vertical-align: middle;"></i>
                                    CURRENT
                                </span>
                                <span class="text-muted small">(Active call being handled)</span>
                                <hr class="flex-grow-1 ms-3 border-primary opacity-25">
                            </div>

                            <div class="table-responsive">
                                <table class="table timeline-table mb-0">
                                    <thead>
                                        <tr>
                                            <th>CALL TIME</th>
                                            <th>DIRECTION</th>
                                            <th>RECORDING</th>
                                            <th>DURATION</th>
                                            <th>OWNED BY</th>
                                            <th>TAG</th>
                                            <th>NOTES + ASSIGN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="active-call-row">
                                            <td>
                                                <div class="fw-bold">
                                                    {{ $currentLog->created_at ? $currentLog->created_at->format('h:i A') : 'N/A' }}
                                                </div>
                                                <div class="text-muted smaller">
                                                    {{ $currentLog->created_at ? $currentLog->created_at->format('d M') : '' }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $currDir = $currentLog->direction;
                                                    if (!$currDir && $currentLog->full_log) {
                                                        $fl = $currentLog->full_log;
                                                        $type = (int) ($fl['_ty'] ?? 1);
                                                        $currDir = $type == 2 ? 'Outgoing' : 'Incoming';
                                                        $agentAction = $fl['_ld'][0]['_ac'] ?? '';
                                                        if ($agentAction == 'missed' || ($fl['_su'] ?? 0) == 2) {
                                                            $currDir = 'Missed';
                                                        }
                                                    }
                                                    if (!$currDir) {
                                                        $currDir = $currentLog->contact->source;
                                                    }
                                                @endphp
                                                @if ($currDir == 'Outgoing' || $currDir == 'Outbound')
                                                    <span
                                                        class="badge bg-soft-warning text-warning px-3 rounded-pill border border-warning border-opacity-25">
                                                        <i class="fas fa-arrow-up me-1"></i> OUTGOING
                                                    </span>
                                                @elseif($currDir == 'Missed')
                                                    <span
                                                        class="badge bg-soft-danger text-danger px-3 rounded-pill border border-danger border-opacity-25">
                                                        <i class="fas fa-phone-slash me-1"></i> MISSED
                                                    </span>
                                                @else
                                                    <span
                                                        class="badge bg-soft-success text-success px-3 rounded-pill border border-success border-opacity-25">
                                                        <i class="fas fa-arrow-down me-1"></i> INCOMING
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($currentLog->recording_url)
                                                    <button class="btn btn-soft-primary btn-sm rounded px-3 py-1 play-btn"
                                                        data-id="{{ $currentLog->id }}">
                                                        <i class="fas fa-play me-1"></i> Play <span
                                                            class="text-muted small ms-1">{{ $currentLog->call_duration }}</span>
                                                    </button>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $currentLog->call_duration ?? '0s' }}</div>
                                            </td>
                                            <td>
                                                <div class="text-dark">{{ $currentLog->assignedStaff->name ?? 'Self' }}
                                                </div>
                                            </td>
                                            <td>
                                                <select
                                                    class="form-select form-select-sm border-secondary rounded-pill px-3 tag-select"
                                                    style="width: 140px;" data-id="{{ $currentLog->id }}">
                                                    <option value="">Select tag</option>
                                                    @foreach ($leadTags as $tag)
                                                        <option value="{{ $tag->name }}" {{ $currentLog->lead_tag == $tag->name ? 'selected' : '' }}>
                                                            {{ $tag->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <textarea
                                                        class="form-control form-control-sm border-secondary border-opacity-25 small"
                                                        rows="2" placeholder="Add notes here..."
                                                        data-id="{{ $currentLog->id }}"
                                                        id="current-note-textarea">{{ $currentLog->note }}</textarea>
                                                    <div class="text-end">
                                                        <button class="btn btn-primary btn-xs-custom"
                                                            id="save-current-note-btn" data-id="{{ $currentLog->id }}">
                                                            <i class="fas fa-save me-1"></i> Save Note
                                                        </button>
                                                    </div>
                                                    @if ($currentLog->followup_note)
                                                        <div
                                                            class="p-2 rounded small bg-soft-info border border-info border-opacity-25">
                                                            <i class="fas fa-reply me-1"></i>
                                                            {{ $currentLog->followup_note }}
                                                        </div>
                                                    @endif
                                                    @if ($currentLog->demo_date)
                                                        <div
                                                            class="p-2 rounded small bg-soft-primary border border-primary border-opacity-10">
                                                            <div class="text-primary fw-bold mb-1 small"><i
                                                                    class="fas fa-video me-1"></i> Demo Scheduled</div>
                                                            <div class="text-muted smaller">
                                                                Date: <span
                                                                    class="text-dark fw-bold">{{ $currentLog->demo_date->format('d M Y') }}</span><br>
                                                                Time: <span
                                                                    class="text-dark fw-bold">{{ $currentLog->demo_time ?? 'Not set' }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($currentLog->followup_date)
                                                        <div
                                                            class="p-2 rounded small bg-soft-info border border-info border-opacity-10">
                                                            <div class="text-info fw-bold mb-1 small"><i
                                                                    class="fas fa-calendar-alt me-1"></i> Follow-up
                                                                Scheduled</div>
                                                            <div class="text-muted smaller">
                                                                Date: <span
                                                                    class="text-dark fw-bold">{{ $currentLog->followup_date->format('d M Y') }}</span><br>
                                                                Time: <span
                                                                    class="text-dark fw-bold">{{ $currentLog->followup_time ?? 'Not set' }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <button class="btn btn-primary btn-sm px-3 rounded shadow-sm w-100"
                                                        data-bs-toggle="modal" data-bs-target="#assignLeadModal">
                                                        <i class="fas fa-user-check me-1"></i> Assign
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="lms" role="tabpanel">
                        <div class="p-4 bg-white">
                            <p class="text-muted small mb-4">Click each section to expand • read-only mirror of student
                                dashboard</p>

                            {{-- Course Progress --}}
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded bg-light border border-secondary border-opacity-10"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded me-3 bg-primary bg-opacity-10"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-chart-bar text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Course Progress</h6>
                                        <div class="small text-muted">Overall completion across modules</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-3 text-dark">62<span class="small text-muted">%</span></span>
                                    <i class="fas fa-caret-down text-muted"></i>
                                </div>
                            </div>

                            {{-- Attendance --}}
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded bg-light border border-secondary border-opacity-10"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded me-3 bg-info bg-opacity-10"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-calendar-alt text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Attendance</h6>
                                        <div class="small text-muted">Class attendance · Apr 2026</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-3 text-success">79<span class="small text-muted">%</span></span>
                                    <i class="fas fa-caret-down text-muted"></i>
                                </div>
                            </div>

                            {{-- Assignments --}}
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded bg-light border border-secondary border-opacity-10"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded me-3 bg-warning bg-opacity-10"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-pencil-alt text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Assignments</h6>
                                        <div class="small text-muted">Submitted vs pending · avg score</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-3 text-warning">12<span
                                            class="small text-muted">/18</span></span>
                                    <i class="fas fa-caret-down text-muted"></i>
                                </div>
                            </div>

                            {{-- Recent Classes --}}
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded bg-light border border-secondary border-opacity-10"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded me-3"
                                        style="width: 40px; height: 40px; background-color: rgba(139, 92, 246, 0.1);">
                                        <i class="fas fa-video" style="color: #8b5cf6;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Recent Classes</h6>
                                        <div class="small text-muted">Last 4 sessions · attendance log</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-caret-down text-muted"></i>
                                </div>
                            </div>

                            {{-- Mini Projects --}}
                            <div class="d-flex align-items-center justify-content-between p-3 mb-3 rounded bg-light border border-secondary border-opacity-10"
                                style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center justify-content-center rounded me-3 bg-success bg-opacity-10"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-layer-group text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark">Mini Projects</h6>
                                        <div class="small text-muted">Module-end projects with scores</div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold me-3 text-success">2<span class="small text-muted">/3</span></span>
                                    <i class="fas fa-caret-down text-muted"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Entry Modal -->
    <div class="modal fade" id="addEntryModal" tabindex="-1" aria-labelledby="addEntryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold" id="addEntryModalLabel"><i
                            class="fas fa-plus-circle text-primary me-2"></i> Add New Call Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addEntryForm">
                    @csrf
                    <input type="hidden" name="contact_id" value="{{ $call->contact_id }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Date</label>
                                <input type="date" name="call_date" class="form-control rounded-3"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Notes*</label>
                                <textarea name="notes" class="form-control rounded-3" rows="3"
                                    placeholder="Enter call notes, discussion points..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light px-4 rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm" id="saveEntryBtn">Add
                            Entry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Assign Lead Modal -->
    <div class="modal fade" id="assignLeadModal" tabindex="-1" aria-labelledby="assignLeadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold" id="assignLeadModalLabel"><i
                            class="fas fa-user-plus me-2 text-primary"></i> Assign Lead</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="assignLeadForm">
                    @csrf
                    <input type="hidden" name="call_log_id" value="{{ $currentLog->id }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Assign To</label>
                                <select name="assigned_staff_id" id="assignStaffSelect" class="form-select rounded-3"
                                    required>
                                    <option value="self" {{ $currentLog->assigned_staff_id == auth()->id() ? 'selected' : '' }}>Self (Me)
                                    </option>
                                    <optgroup label="Technical Staff">
                                        @foreach ($demoStaff as $staff)
                                            <option value="{{ $staff->id }}" {{ $currentLog->assigned_staff_id == $staff->id ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                    {{-- <optgroup label="Admission Officers">
                                        @foreach ($admissionOfficers as $staff)
                                        <option value="{{ $staff->id }}" {{ $currentLog->assigned_staff_id == $staff->id ?
                                            'selected' : '' }}>
                                            {{ $staff->name }}</option>
                                        @endforeach
                                    </optgroup> --}}
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><i
                                        class="fas fa-calendar-alt me-1 text-primary"></i> Follow-up Date</label>
                                <input type="date" name="followup_date" class="form-control rounded-3"
                                    value="{{ $currentLog->followup_date ? $currentLog->followup_date->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted"><i
                                        class="fas fa-clock me-1 text-primary"></i> Follow-up Time</label>
                                <input type="time" name="followup_time" class="form-control rounded-3"
                                    value="{{ $currentLog->followup_time ? \Carbon\Carbon::parse($currentLog->followup_time)->format('H:i') : '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Follow-up Note * (Required before
                                    assigning)</label>
                                <textarea name="assign_notes" class="form-control rounded-3" rows="3"
                                    placeholder="Enter followup instructions, discussion points..."
                                    required>{{ $currentLog->followup_note }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Update Tag</label>
                                <select class="form-select rounded-3 bg-light" disabled id="assignUpdateTagSelect">
                                    <option value="contacted" selected>contacted</option>
                                    <option value="converted">converted</option>
                                </select>
                                <input type="hidden" name="update_tag" value="contacted" id="assignUpdateTagInput">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 justify-content-end">
                        <button type="button" class="btn btn-light px-4 rounded-pill me-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded shadow-sm" id="pushLeadBtn">Push
                            Lead</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Student Profile Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1" aria-labelledby="editStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 1.5rem;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold" id="editStudentModalLabel"><i
                            class="fas fa-user-edit me-2 text-primary"></i> Edit Student Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudentForm">
                    @csrf
                    <input type="hidden" name="contact_id" value="{{ $call->contact_id }}">
                    <input type="hidden" name="call_log_id" value="{{ $currentLog->id }}">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Full Name</label>
                                <input type="text" name="name" class="form-control rounded-3"
                                    value="{{ $call->contact->name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Phone</label>
                                <input type="text" name="caller_number" class="form-control rounded-3"
                                    value="{{ $call->caller_number }}" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" name="email" class="form-control rounded-3"
                                    value="{{ $call->contact->email ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Secondary Number</label>
                                <input type="text" name="secondary_number" class="form-control rounded-3"
                                    value="{{ $call->contact->secondary_number ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Location</label>
                                <input type="text" name="location" class="form-control rounded-3"
                                    value="{{ $call->contact->location ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Tag</label>
                                <select name="tag" class="form-select rounded-3">
                                    @foreach ($leadTags as $tag)
                                        <option value="{{ $tag->name }}" {{ strtolower($currentLog->lead_tag) == strtolower($tag->name) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Source</label>
                                <select name="source" class="form-select rounded-3">
                                    @foreach ($leadSources as $source)
                                        <option value="{{ $source->name }}" {{ $currentLog->contact->source == $source->name ? 'selected' : '' }}>
                                            {{ $source->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0 justify-content-end">
                        <button type="button" class="btn btn-light px-4 rounded-pill me-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 rounded shadow-sm" id="saveProfileBtn">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-show.css') }}">
    @endpush
    <audio id="globalAudioPlayer" style="display: none;"></audio>
@endsection
@push('script')
    <script>
        $('#addEntryForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $('#saveEntryBtn');
            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

            $.ajax({
                url: "{{ route('calls.store-entry') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Add Entry');
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });

        $('#assignStaffSelect').on('change', function () {
            if ($(this).val() === 'self') {
                $('#assignUpdateTagSelect').val('contacted');
                $('#assignUpdateTagInput').val('contacted');
            } else {
                $('#assignUpdateTagSelect').val('converted');
                $('#assignUpdateTagInput').val('converted');
            }
        }).trigger('change');

        $('#assignLeadForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $('#pushLeadBtn');
            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Pushing...');

            $.ajax({
                url: "{{ route('doubt-query-leads.assign-lead') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Push Lead');
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });



        $(document).on('change', '.tag-select', function () {
            const select = $(this);
            const id = select.data('id');
            const tag = select.val();

            select.prop('disabled', true).css('opacity', '0.5');

            $.ajax({
                url: "{{ route('calls.update-tag') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    tag: tag
                },
                success: function (response) {
                    select.prop('disabled', false).css('opacity', '1');
                    if (response.status) {
                        toastr.success(response.message);

                        // Dynamic Registration Tab Update
                        if (tag === 'confirmed') {
                            $('#registration-status-badge').html(
                                '<span class="badge bg-soft-success text-success ms-2" style="font-size: 0.6rem;">Unlocked</span>'
                            );
                            $('#registration-locked-view').addClass('d-none');
                            $('#registration-unlocked-view').removeClass('d-none');
                        } else {
                            $('#registration-status-badge').html(
                                '<span class="badge bg-soft-danger text-danger ms-2" style="font-size: 0.6rem;">Locked</span>'
                            );
                            $('#registration-locked-view').removeClass('d-none');
                            $('#registration-unlocked-view').addClass('d-none');
                        }
                    }
                },
                error: function () {
                    select.prop('disabled', false).css('opacity', '1');
                    toastr.error('Failed to update tag.');
                }
            });
        });

        $('#editStudentForm').on('submit', function (e) {
            e.preventDefault();
            const btn = $('#saveProfileBtn');
            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            $.ajax({
                url: "{{ route('calls.update-profile') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Save Changes');
                    toastr.error('Something went wrong. Please try again.');
                }
            });
        });

        // Audio Playback Logic
        const audioPlayer = document.getElementById('globalAudioPlayer');
        let currentPlayingBtn = null;



        $('.play-btn').on('click', function () {
            const btn = $(this);
            const id = btn.data('id');
            const icon = btn.find('i');

            // If clicking the same button that's playing, pause it
            if (currentPlayingBtn && currentPlayingBtn[0] === btn[0]) {
                if (!audioPlayer.paused) {
                    audioPlayer.pause();
                    icon.removeClass('fa-pause').addClass('fa-play');
                    btn.removeClass('btn-primary').addClass('btn-soft-primary');
                } else {
                    audioPlayer.play();
                    icon.removeClass('fa-play').addClass('fa-pause');
                    btn.removeClass('btn-soft-primary').addClass('btn-primary');
                }
                return;
            }

            // Reset previous button
            if (currentPlayingBtn) {
                currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
                currentPlayingBtn.removeClass('btn-primary').addClass('btn-soft-primary');
            }

            // Load and play new recording
            icon.removeClass('fa-play').addClass('fa-spinner fa-spin');
            btn.prop('disabled', true);

            $.get("{{ url('calls/get-recording') }}/" + id, function (response) {
                btn.prop('disabled', false);
                if (response.url) {
                    audioPlayer.src = response.url;
                    audioPlayer.play();

                    currentPlayingBtn = btn;
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-pause');
                    btn.removeClass('btn-soft-primary').addClass('btn-primary');
                } else {
                    icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
                    toastr.error('Recording link not available.');
                }
            }).fail(function () {
                btn.prop('disabled', false);
                icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
                toastr.error('Failed to fetch recording.');
            });
        });

        audioPlayer.onended = function () {
            if (currentPlayingBtn) {
                currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
                currentPlayingBtn.removeClass('btn-primary').addClass('btn-soft-primary');
                currentPlayingBtn = null;
            }
        };

        $(document).on('click', '#save-current-note-btn', function () {
            const btn = $(this);
            const id = btn.data('id');
            const note = $('#current-note-textarea').val();

            if (!note) {
                toastr.warning('Please enter a note.');
                return;
            }

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            $.ajax({
                url: "{{ route('calls.update-note') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    note: note
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                    }
                    btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                },
                error: function () {
                    btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                    toastr.error('Failed to update note.');
                }
            });
        });

        // Auto-resize textarea logic
        function autoResizeTextarea(el) {
            el.style.height = 'auto';
            el.style.height = (el.scrollHeight) + 'px';
        }

        const currentNoteTextarea = document.getElementById('current-note-textarea');
        if (currentNoteTextarea) {
            currentNoteTextarea.addEventListener('input', function () {
                autoResizeTextarea(this);
            });
            // Initial resize if content exists
            autoResizeTextarea(currentNoteTextarea);
        }

        // Note Editing Logic (Cleanup or keep for future use - I'll keep the logic but it won't be triggered if triggers are gone)
        $(document).on('click', '.edit-note-trigger', function () {
            const container = $(this).closest('.note-container');
            container.find('.note-text').addClass('d-none');
            container.find('.note-edit').removeClass('d-none').focus();
            container.find('.edit-actions').removeClass('d-none');
            $(this).addClass('d-none');
            container.addClass('shadow-sm').css('z-index', '20');
        });

        $(document).on('click', '.cancel-note-btn', function () {
            const container = $(this).closest('.note-container');
            container.find('.note-text').removeClass('d-none');
            container.find('.note-edit').addClass('d-none');
            container.find('.edit-actions').addClass('d-none');
            container.find('.edit-note-trigger').removeClass('d-none');
            container.removeClass('shadow-sm').css('z-index', '10');
        });

        $(document).on('click', '.save-note-btn', function () {
            const btn = $(this);
            const id = btn.data('id');
            const container = btn.closest('.note-container');
            const note = container.find('.note-edit').val();

            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: "{{ route('calls.update-note') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    note: note
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        container.find('.note-text').text(note).removeClass('d-none');
                        container.find('.note-edit').addClass('d-none');
                        container.find('.edit-actions').addClass('d-none');
                        container.find('.edit-note-trigger').removeClass('d-none');
                    }
                    btn.prop('disabled', false).text('Save');
                },
                error: function () {
                    btn.prop('disabled', false).text('Save');
                    toastr.error('Failed to update note.');
                }
            });
        });

        // Dynamic Batch Selection Logic
        $(document).on('change', '.course-selection', function () {
            const selected = $(this).find('option:selected');
            const courseId = $(this).val();
            const fees = selected.data('fees');
            const batchSelect = $('.batch-selection');
            const feesInput = $('input[name="course_fees"]');
            const paymentSelect = $('#payment-mode-select');

            // Auto-fill fees and set default payment mode
            if (fees) {
                feesInput.val(fees);
            }
            if (courseId) {
                paymentSelect.val('').trigger('change');
            }

            if (!courseId) {
                batchSelect.html('<option value="">Select Batch</option>').prop('disabled', true);
                return;
            }

            batchSelect.html('<option value="">Loading...</option>').prop('disabled', true);

            $.get("{{ url('courses') }}/" + courseId + "/batches", function (batches) {
                let options = '<option value="">Select Batch</option>';
                if (batches.length > 0) {
                    batches.forEach(batch => {
                        options +=
                            `<option value="${batch.id}" data-code="${batch.batch_code}" data-type="${batch.batch_type}">${batch.batch_code} - ${batch.batch_name}</option>`;
                    });
                    batchSelect.html(options).prop('disabled', false);
                } else {
                    batchSelect.html('<option value="">No active batches found</option>').prop('disabled',
                        true);
                }
            }).fail(function () {
                toastr.error('Failed to fetch batches.');
                batchSelect.html('<option value="">Error loading batches</option>').prop('disabled', true);
            });
        });

        // Auto-select Batch Type when Batch is selected
        $(document).on('change', '.batch-selection', function () {
            const selected = $(this).find('option:selected');
            const batchType = selected.data('type');
            if (batchType) {
                $('select[name="batch_type"]').val(batchType);
            }
        });

        function updatePaymentTabs(mode) {
            const singleTab = $('#single-payment-tab');
            const splitTab = $('#split-payment-tab');

            if (mode === 'Full Payment') {
                singleTab.removeClass('disabled').prop('disabled', false).tab('show');
                splitTab.addClass('disabled').prop('disabled', true);
            } else if (mode === 'Split Payment') {
                splitTab.removeClass('disabled').prop('disabled', false).tab('show');
                singleTab.addClass('disabled').prop('disabled', true);
            } else {
                singleTab.addClass('disabled').prop('disabled', true);
                splitTab.addClass('disabled').prop('disabled', true);
            }
        }

        // Admission Details Submission Logic
        $(document).on('submit', '#admission-details-form', function (e) {
            e.preventDefault();
            const form = $(this);
            const btn = $('#save-admission-btn');

            // Collect data for display
            const courseText = form.find('select[name="course_id"] option:selected').text();
            const batchCode = form.find('select[name="batch_id"] option:selected').data('code') || '-';
            const batchType = form.find('select[name="batch_type"]').val();
            const fees = form.find('input[name="course_fees"]').val();
            const paymentMode = form.find('select[name="payment_mode"]').val();

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            const formData = form.serializeArray();
            formData.push({
                name: '_token',
                value: "{{ csrf_token() }}"
            });
            formData.push({
                name: 'contact_id',
                value: "{{ $call->contact_id }}"
            });

            $.ajax({
                url: "{{ route('admission.store') }}",
                type: "POST",
                data: formData,
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);

                        // Store admission_id for fee collection
                        $('#admission_id_hidden').val(response.admission_id);

                        // Update Readonly View
                        $('#display-course').text(courseText);
                        $('#display-batch-code').text(batchCode);
                        $('#display-batch-type').text(batchType);
                        $('#display-fees').text('₹' + Number(fees).toLocaleString());
                        $('#display-payment-mode').text(paymentMode);

                        // Transition Views
                        form.addClass('d-none');
                        $('#admission-readonly-view').removeClass('d-none');
                        $('#edit-admission-btn').removeClass('d-none');

                        // Unlock Fee Collection
                        $('#fee-collection-locked').addClass('d-none');
                        $('#fee-collection-unlocked').removeClass('d-none');

                        // Update payment tabs based on mode
                        updatePaymentTabs(paymentMode);
                    }
                    btn.prop('disabled', false).text('Save Details');
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Save Details');
                    toastr.error(xhr.responseJSON?.message || 'Failed to save admission details.');
                }
            });
        });

        // Trigger tab update on payment mode change (even before saving if needed, though they are locked)
        $(document).on('change', '#payment-mode-select', function () {
            updatePaymentTabs($(this).val());
        });

        $(document).on('click', '#edit-admission-btn', function () {
            $('#admission-readonly-view').addClass('d-none');
            $('#admission-details-form').removeClass('d-none');
            $(this).addClass('d-none');

            // Trigger batch loading for the currently selected course if editing
            const courseId = $('.course-selection').val();
            if (courseId) {
                // We keep the selected batch ID to re-select it after loading
                const selectedBatchId = $('.batch-selection').data('selected');

                const batchSelect = $('.batch-selection');
                batchSelect.html('<option value="">Loading...</option>').prop('disabled', true);

                $.get("{{ url('courses') }}/" + courseId + "/batches", function (batches) {
                    let options = '<option value="">Select Batch</option>';
                    if (batches.length > 0) {
                        batches.forEach(batch => {
                            const isSelected = batch.id == selectedBatchId ? 'selected' : '';
                            options +=
                                `<option value="${batch.id}" data-code="${batch.batch_code}" data-type="${batch.batch_type}" ${isSelected}>${batch.batch_code} - ${batch.batch_name}</option>`;
                        });
                        batchSelect.html(options).prop('disabled', false);
                    } else {
                        batchSelect.html('<option value="">No active batches found</option>').prop(
                            'disabled', true);
                    }
                });
            }
        });

        // Split Payment: Add another split logic
        $(document).on('click', '#add-split-payment-btn', function () {
            const isAdmin = {{ auth()->user()->hasRole('SuperAdmin') ? 'true' : 'false' }};
            const container = $('#split-payment-containers');
            const currentCount = container.children().length;

            if (!isAdmin && currentCount >= 4) {
                toastr.warning('Maximum 4 installments allowed.');
                return;
            }

            const nextIndex = currentCount + 1;
            const newSplit = `
                    <div class="p-3 rounded-4 mb-3 bg-light border border-secondary border-opacity-10 split-item">
                        <div class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3">Payment ${nextIndex}</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Amount (₹)</label>
                                <input type="number" class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-amount" placeholder="e.g. 10000">
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted smaller text-uppercase fw-bold mb-2 d-block">Date</label>
                                <input type="date" class="form-control bg-white border-secondary text-dark rounded-3 py-2 border-opacity-25 split-date">
                            </div>
                        </div>
                    </div>
                `;
            container.append(newSplit);
        });

        // Save Single Payment
        $(document).on('click', '#save-single-payment-btn', function () {
            const btn = $(this);
            const admissionId = $('#admission_id_hidden').val();
            const amount = $('#single-amount').val();
            const date = $('#single-date').val();

            if (!admissionId) {
                toastr.error('Admission ID not found. Please save admission details first.');
                return;
            }
            if (!amount || !date) {
                toastr.error('Please fill all payment fields.');
                return;
            }

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            $.ajax({
                url: "{{ route('fee.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    admission_id: admissionId,
                    payments: [{
                        amount: amount,
                        date: date
                    }]
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                    btn.prop('disabled', false).text('Save');
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Save');
                    toastr.error(xhr.responseJSON?.message || 'Failed to save payment.');
                }
            });
        });

        // Save Split Payments
        $(document).on('click', '#save-split-payments-btn', function () {
            const btn = $(this);
            const admissionId = $('#admission_id_hidden').val();
            const payments = [];

            if (!admissionId) {
                toastr.error('Admission ID not found. Please save admission details first.');
                return;
            }

            let valid = true;
            $('.split-item').each(function () {
                const amount = $(this).find('.split-amount').val();
                const date = $(this).find('.split-date').val();
                if (!amount || !date) {
                    valid = false;
                    return false;
                }
                payments.push({
                    amount: amount,
                    date: date
                });
            });

            if (!valid || payments.length === 0) {
                toastr.error('Please fill all payment fields.');
                return;
            }

            btn.prop('disabled', true).html(
                '<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

            $.ajax({
                url: "{{ route('fee.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    admission_id: admissionId,
                    payments: payments
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                    btn.prop('disabled', false).text('Save All');
                },
                error: function (xhr) {
                    btn.prop('disabled', false).text('Save All');
                    toastr.error(xhr.responseJSON?.message || 'Failed to save payments.');
                }
            });
        });

        // Initialization for existing admissions
        $(document).ready(function () {
            const admissionId = $('#admission_id_hidden').val();
            if (admissionId) {
                const paymentMode = $('#payment-mode-select').val();
                updatePaymentTabs(paymentMode);

                // If editing, we might need to load all batches for the selected course
                // though we've pre-filled the current one.
                const courseId = $('.course-selection').val();
                if (courseId) {
                    // We don't necessarily need to reload if the batch is already there, 
                    // but if the user clicks "Edit", they expect the full list.
                }
            }
        });
    </script>
@endpush