@extends('layouts.header')

@section('pageTitle')
    {{ $scheduleInterview->name }} - Interview Details | {{ config('app.name') }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calls-show.css') }}">
<style>
    .bg-soft-danger { background-color: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
    .bg-soft-info { background-color: rgba(6, 182, 212, 0.1) !important; color: #0891b2 !important; }
    .bg-soft-secondary { background-color: rgba(100, 116, 139, 0.1) !important; color: #64748b !important; }
    .interview-profile-container {
        padding: 0.5rem 1.5rem 2rem 1.5rem !important;
    }
    .back-btn {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 1rem;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 50rem;
        color: #64748b;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease-in-out;
    }
    .back-btn:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateX(-2px);
    }
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }
    .profile-avatar {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.25);
        flex-shrink: 0;
    }
    .info-grid-15 {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
    }
    .detail-tile {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 14px;
        transition: all 0.2s ease;
    }
    .detail-tile:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
    }
    .detail-tile .lbl {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .detail-tile .val {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
    }
    .detail-tile .val.mono {
        font-family: 'SF Mono', Monaco, 'Courier New', monospace;
    }
    
    /* Tag Pills */
    .tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-radius: 999px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .tag .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        display: inline-block;
    }
    .tag-scheduled { background: rgba(37, 99, 235, 0.1); color: #2563eb; border-color: rgba(37, 99, 235, 0.25); }
    .tag-attended { background: rgba(22, 163, 74, 0.1); color: #16a34a; border-color: rgba(22, 163, 74, 0.25); }
    .tag-selected { background: rgba(16, 185, 129, 0.1); color: #059669; border-color: rgba(16, 185, 129, 0.25); }
    .tag-rejected { background: rgba(220, 38, 38, 0.1); color: #dc2626; border-color: rgba(220, 38, 38, 0.25); }
    .tag-hold { background: rgba(147, 51, 234, 0.1); color: #9333ea; border-color: rgba(147, 51, 234, 0.25); }
    .tag-notattended { background: rgba(234, 88, 12, 0.1); color: #ea580c; border-color: rgba(234, 88, 12, 0.25); }
    .tag-noshow { background: rgba(220, 38, 38, 0.1); color: #dc2626; border-color: rgba(220, 38, 38, 0.25); }
    .tag-rescheduled { background: rgba(217, 119, 6, 0.1); color: #d97706; border-color: rgba(217, 119, 6, 0.25); }

    /* Timeline Row */
    .timeline-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 8px;
        transition: all 0.2s ease;
        position: relative;
    }
    .timeline-row:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(2px);
    }
    .timeline-row.green { border-left: 4px solid #16a34a; }
    .timeline-row.red { border-left: 4px solid #dc2626; }
    .timeline-row.amber { border-left: 4px solid #f59e0b; }
    .timeline-row.blue { border-left: 4px solid #2563eb; }
    .timeline-row .timeline-time {
        font-family: 'SF Mono', Monaco, 'Courier New', monospace;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        min-width: 110px;
    }

    /* Modal Form Styles */
    .modal-dialog-custom {
        max-width: 620px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }
    .form-grid .full {
        grid-column: 1 / -1;
    }
    .form-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 12px;
        color: #1e293b;
        font-size: 13.5px;
        outline: none;
        transition: all 0.15s;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .form-input[readonly] {
        background: #f1f5f9;
        color: #64748b;
        cursor: not-allowed;
    }
</style>
@endpush

@section('section')
@php
    $nameParts = explode(' ', trim($scheduleInterview->name ?? 'Candidate'));
    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1)));
    if (strlen($initials) < 2) $initials = substr(strtoupper($scheduleInterview->name ?? 'AK'), 0, 2);

    $displayId = 'S' . str_pad($scheduleInterview->id, 3, '0', STR_PAD_LEFT);
    $formattedDate = $scheduleInterview->date ? \Carbon\Carbon::parse($scheduleInterview->date)->format('d M Y') : 'N/A';
    $formattedTime = $scheduleInterview->time ? \Carbon\Carbon::parse($scheduleInterview->time)->format('h:i A') : 'N/A';

    $rawStatus = strtolower($scheduleInterview->status ?? 'scheduled');
    $rawAttendance = strtolower($scheduleInterview->attendance ?? '');
    $rawResult = strtolower($scheduleInterview->result ?? '');
    
    $tagCls = 'tag-scheduled';
    $tagLabel = 'SCHEDULED';
    
    if ($rawResult === 'selected') {
        $tagCls = 'tag-selected';
        $tagLabel = 'SELECTED';
    } elseif ($rawResult === 'rejected') {
        $tagCls = 'tag-rejected';
        $tagLabel = 'REJECTED';
    } elseif (in_array($rawResult, ['on hold', 'hold'])) {
        $tagCls = 'tag-hold';
        $tagLabel = 'HOLD';
    } elseif (in_array($rawAttendance, ['not attended', 'no show'])) {
        $tagCls = 'tag-notattended';
        $tagLabel = 'NOT ATTENDED';
    } elseif ($rawAttendance === 'attended') {
        $tagCls = 'tag-attended';
        $tagLabel = 'ATTENDED';
    } elseif ($rawStatus === 'selected') {
        $tagCls = 'tag-selected';
        $tagLabel = 'SELECTED';
    } elseif ($rawStatus === 'rejected') {
        $tagCls = 'tag-rejected';
        $tagLabel = 'REJECTED';
    } elseif (in_array($rawStatus, ['on hold', 'hold'])) {
        $tagCls = 'tag-hold';
        $tagLabel = 'HOLD';
    } elseif (in_array($rawStatus, ['not attended', 'no show', 'not_attended', 'no_show'])) {
        $tagCls = 'tag-notattended';
        $tagLabel = 'NOT ATTENDED';
    } elseif ($rawStatus === 'attended') {
        $tagCls = 'tag-attended';
        $tagLabel = 'ATTENDED';
    }
@endphp

<div class="container-fluid p-0 interview-profile-container">
    {{-- Header / Back Button --}}
    <div class="mb-3 d-flex align-items-center">
        <a href="javascript:history.back()" class="back-btn">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    {{-- Main Profile Card --}}
    <div class="card profile-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <div class="profile-avatar me-3">
                        {{ $initials }}
                    </div>
                    <div>
                        <h2 class="mb-1 fw-bold text-dark" style="font-size: 1.5rem;">{{ $scheduleInterview->name }}</h2>
                        <div class="d-flex align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.85rem;">
                            <span class="tag {{ $tagCls }}">
                                <span class="dot"></span>
                                {{ $tagLabel }}
                            </span>
                            <span class="badge bg-light text-dark border">{{ $displayId }}</span>
                            <span>&middot;</span>
                            <span>{{ $scheduleInterview->company }}</span>
                            <span>&middot;</span>
                            <span>{{ $scheduleInterview->role ?? 'N/A' }}</span>
                            <span>&middot;</span>
                            <span>{{ $scheduleInterview->location ?? 'N/A' }}</span>
                            <span>&middot;</span>
                            <span>Assigned &rarr; <strong class="text-primary">{{ auth()->user()->name ?? 'Self' }}</strong></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    @if(auth()->check() && (auth()->user()->hasRole('Placement Coordinator') || auth()->user()->hasRole('Student Coordinator') || auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
                    <button type="button" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm fw-bold d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#updateOutcomeModal">
                        <i class="fas fa-check-circle me-1"></i> Update Outcome
                    </button>
                    @endif
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 rounded-pill fw-bold d-flex align-items-center" onclick="alert('Interview ID: {{ $scheduleInterview->id }}')">
                        <i class="fas fa-edit me-1"></i> Edit
                    </button>
                </div>
            </div>

            {{-- 15 Info Grid Cells --}}
            <div class="info-grid-15">
                <div class="detail-tile">
                    <div class="lbl">Phone</div>
                    <div class="val mono">{{ $scheduleInterview->phone }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Email</div>
                    <div class="val text-truncate" title="{{ $scheduleInterview->email }}">{{ $scheduleInterview->email }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Course</div>
                    <div class="val">{{ $scheduleInterview->course }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Duration</div>
                    <div class="val">{{ $scheduleInterview->duration }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Batch Type</div>
                    <div class="val">{{ $scheduleInterview->batch_type }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Batch Code</div>
                    <div class="val mono">{{ $scheduleInterview->batch_code }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Company</div>
                    <div class="val text-primary">{{ $scheduleInterview->company }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Role</div>
                    <div class="val">{{ $scheduleInterview->role ?? 'N/A' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Location</div>
                    <div class="val">{{ $scheduleInterview->location ?? 'N/A' }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Interview Date</div>
                    <div class="val mono">{{ $formattedDate }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Interview Time</div>
                    <div class="val mono">{{ $formattedTime }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Interview Mode</div>
                    <div class="val">{{ $scheduleInterview->interview_mode }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Meeting Link</div>
                    <div class="val">
                        @if(!empty($scheduleInterview->meeting_link))
                            <a href="{{ $scheduleInterview->meeting_link }}" target="_blank" class="text-primary fw-bold text-decoration-none">
                                <i class="fas fa-video me-1"></i> Open link
                            </a>
                        @else
                            <span class="text-muted">&mdash;</span>
                        @endif
                    </div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">HR Contact (Day-of)</div>
                    <div class="val mono">{{ $hrContactDay }}</div>
                </div>
                <div class="detail-tile">
                    <div class="lbl">Attended Count</div>
                    <div class="val mono text-primary fw-bold">{{ $attendedCount }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes Card --}}
    <div class="card profile-card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-sticky-note text-warning me-2"></i> Notes</h5>
        </div>
        <div class="card-body p-4 text-secondary" style="font-size: 0.95rem; line-height: 1.6;">
            {{ $scheduleInterview->reason ?? $scheduleInterview->hr_feedback ?? 'Bring updated resume in PDF.' }}
        </div>
    </div>

    {{-- Interview Timeline Card --}}
    <div class="card profile-card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="fas fa-history text-primary me-2"></i> Interview Timeline 
                <small class="text-muted fw-normal ms-1">(every company attended)</small>
            </h5>
            <span class="badge bg-light text-dark border">{{ count($timeline) }} rounds</span>
        </div>
        <div class="card-body p-4">
            @forelse($timeline as $t)
                @php
                    $tDate = $t->date ? \Carbon\Carbon::parse($t->date)->format('d M Y') : 'N/A';
                    $tRes = strtolower($t->result ?? '');
                    $tAtt = strtolower($t->attendance ?? '');
                    $tStatus = strtolower($t->status ?? '');

                    $rowClass = 'blue';
                    $pillCls = 'tag-scheduled';
                    $pillLabel = 'SCHEDULED';

                    if ($tRes === 'selected' || $tStatus === 'selected') {
                        $rowClass = 'green';
                        $pillCls = 'tag-selected';
                        $pillLabel = 'SELECTED';
                    } elseif ($tRes === 'rejected' || $tStatus === 'rejected') {
                        $rowClass = 'red';
                        $pillCls = 'tag-rejected';
                        $pillLabel = 'REJECTED';
                    } elseif (in_array($tRes, ['on hold', 'hold']) || in_array($tStatus, ['on hold', 'hold'])) {
                        $rowClass = 'amber';
                        $pillCls = 'tag-hold';
                        $pillLabel = 'HOLD';
                    } elseif (in_array($tAtt, ['not attended', 'no show'])) {
                        $rowClass = 'amber';
                        $pillCls = 'tag-notattended';
                        $pillLabel = 'NOT ATTENDED';
                    } elseif ($tAtt === 'attended') {
                        $rowClass = 'green';
                        $pillCls = 'tag-attended';
                        $pillLabel = 'ATTENDED';
                    }
                @endphp
                <div class="timeline-row {{ $rowClass }}">
                    <div class="timeline-time">{{ $tDate }}</div>
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">
                            {{ $t->company }} <span class="text-muted fw-normal">&mdash; {{ $t->role ?? 'Role' }}</span>
                        </div>
                        <div class="text-muted small mt-1">
                            {{ $t->hr_feedback ?? $t->reason ?? ($t->id == $scheduleInterview->id ? 'Current round scheduled' : 'Past interview round') }}
                        </div>
                    </div>
                    <div>
                        <span class="tag {{ $pillCls }}">
                            <span class="dot"></span>
                            {{ $pillLabel }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    No interview history recorded for this candidate yet.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Call History / Timeline Card (Matching Leads Show Page) --}}
    <div class="card timeline-card profile-card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-history me-2 text-primary"></i> Timeline 
                    <span class="text-muted small fw-normal ms-2">(Previous calls & activities)</span>
                </h5>
                <button class="btn btn-primary btn-sm px-4 rounded-pill shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#conversationModal">
                    <i class="fas fa-plus me-1"></i> Add Entry
                </button>
            </div>

            @php
                $currentTimelineItem = null;
                $previousTimelineItems = collect();

                if (isset($callLogs) && count($callLogs) > 0) {
                    $currentTimelineItem = $callLogs->first();
                    $previousTimelineItems = $callLogs->slice(1);
                }
            @endphp

            @if(isset($callLogs) && count($callLogs) > 0)
                {{-- PREVIOUS SECTION --}}
                <div class="timeline-section mb-5">
                    <div class="accordion-header-custom p-3 rounded-3 d-flex align-items-center justify-content-between mb-2"
                        style="cursor: pointer; background: #f1f3f5; transition: all 0.2s;"
                        data-bs-toggle="collapse" data-bs-target="#previousTimeline" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <span class="text-uppercase fw-bold text-muted smaller me-2">PREVIOUS</span>
                            <span class="text-muted small">({{ count($previousTimelineItems) }} records - already spoken)</span>
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
                                        @foreach($previousTimelineItems as $log)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">
                                                        {{ $log->call_start ? \Carbon\Carbon::parse($log->call_start)->format('h:i A') : $log->created_at->format('h:i A') }}
                                                    </div>
                                                    <div class="text-muted smaller">
                                                        {{ $log->call_start ? \Carbon\Carbon::parse($log->call_start)->format('d M Y') : $log->created_at->format('d M Y') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @php $dir = strtolower($log->direction ?? ''); @endphp
                                                    @if(in_array($dir, ['incoming', 'inbound']))
                                                        <span class="badge bg-soft-success text-success px-3 rounded-pill border border-success border-opacity-25">
                                                            <i class="fas fa-arrow-down me-1"></i> INCOMING
                                                        </span>
                                                    @elseif(in_array($dir, ['outgoing', 'outbound']))
                                                        <span class="badge bg-soft-warning text-warning px-3 rounded-pill border border-warning border-opacity-25">
                                                            <i class="fas fa-arrow-up me-1"></i> OUTGOING
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary px-3 rounded-pill border border-secondary border-opacity-25">
                                                            <i class="fas fa-phone me-1"></i> {{ strtoupper($log->direction ?? 'CALL') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($log->recording_url)
                                                        <button type="button" class="btn btn-soft-primary btn-sm rounded px-3 py-1 play-btn"
                                                            data-id="{{ $log->id }}" data-url="{{ $log->recording_url }}">
                                                            <i class="fas fa-play me-1"></i> Play
                                                        </button>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-dark">
                                                        @if($log->call_duration >= 3600)
                                                            {{ gmdate("H:i:s", $log->call_duration) }}
                                                        @elseif($log->call_duration > 0)
                                                            {{ gmdate("i:s", $log->call_duration) }}
                                                        @else
                                                            00:00
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-dark">
                                                        {{ $log->staff ? trim($log->staff->first_name . ' ' . $log->staff->last_name) : 'System/IVR' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @php $st = strtolower($log->status ?? ''); @endphp
                                                    @if($log->status == 1 || $st === 'answered')
                                                        <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">Answered</span>
                                                    @elseif($log->status == 2 || $st === 'missed')
                                                        <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">Missed</span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">{{ $log->status ?? 'Logged' }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="mb-2 p-3 rounded border bg-white shadow-sm" style="max-width: 280px; white-space: normal; word-break: break-word; overflow-wrap: break-word; line-height: 1.5;">
                                                        <span class="text-muted small">
                                                            {{ $log->note ?? 'Call record logged.' }}
                                                        </span>
                                                    </div>
                                                    @if($log->followup_date)
                                                        <div class="p-2 rounded border border-info border-opacity-25 bg-soft-info d-flex align-items-center" style="max-width: 280px;">
                                                            <i class="far fa-calendar-alt text-info me-2"></i>
                                                            <div>
                                                                <div class="text-info fw-bold" style="font-size: 0.7rem;">Follow-up Scheduled</div>
                                                                <div class="text-muted" style="font-size: 0.7rem;">Date: <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($log->followup_date)->format('d M Y h:i A') }}</span></div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
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
                <div class="timeline-section mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-circle text-primary smaller me-2" style="font-size: 0.5rem;"></i>
                        <span class="text-uppercase fw-bold text-primary smaller me-2">CURRENT</span>
                        <span class="text-muted small">(Active conversation being handled)</span>
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
                                    <th>NOTES + ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $call = $currentTimelineItem; @endphp
                                <tr class="active-call-row">
                                    <td>
                                        <div class="fw-bold">
                                            {{ $call->call_start ? \Carbon\Carbon::parse($call->call_start)->format('h:i A') : $call->created_at->format('h:i A') }}
                                        </div>
                                        <div class="text-muted smaller">
                                            {{ $call->call_start ? \Carbon\Carbon::parse($call->call_start)->format('d M Y') : $call->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td>
                                        @php $dir = strtolower($call->direction ?? ''); @endphp
                                        @if(in_array($dir, ['incoming', 'inbound']))
                                            <span class="badge bg-soft-success text-success px-3 rounded-pill border border-success border-opacity-25">
                                                <i class="fas fa-arrow-down me-1"></i> INCOMING
                                            </span>
                                        @elseif(in_array($dir, ['outgoing', 'outbound']))
                                            <span class="badge bg-soft-warning text-warning px-3 rounded-pill border border-warning border-opacity-25">
                                                <i class="fas fa-arrow-up me-1"></i> OUTGOING
                                            </span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary px-3 rounded-pill border border-secondary border-opacity-25">
                                                <i class="fas fa-phone me-1"></i> {{ strtoupper($call->direction ?? 'CALL') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($call->recording_url)
                                            <button type="button" class="btn btn-soft-primary btn-sm rounded px-3 py-1 play-btn"
                                                data-id="{{ $call->id }}" data-url="{{ $call->recording_url }}">
                                                <i class="fas fa-play me-1"></i> Play
                                            </button>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="text-dark">
                                            @if($call->call_duration >= 3600)
                                                {{ gmdate("H:i:s", $call->call_duration) }}
                                            @elseif($call->call_duration > 0)
                                                {{ gmdate("i:s", $call->call_duration) }}
                                            @else
                                                00:00
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-dark">
                                            {{ $call->staff ? trim($call->staff->first_name . ' ' . $call->staff->last_name) : 'System/IVR' }}
                                        </div>
                                    </td>
                                    <td>
                                        @php $st = strtolower($call->status ?? ''); @endphp
                                        @if($call->status == 1 || $st === 'answered')
                                            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">Answered</span>
                                        @elseif($call->status == 2 || $st === 'missed')
                                            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">Missed</span>
                                        @else
                                            <span class="badge bg-soft-secondary text-secondary border border-secondary border-opacity-25 px-3 py-2 text-uppercase fw-bold" style="font-size: 0.6rem;">{{ $call->status ?? 'Active' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(empty($call->note) || $call->note === 'Call record logged.')
                                            {{-- Inline form for current unhandled call log --}}
                                            <form action="{{ route('schedule-interviews.call-log.store', encrypt($scheduleInterview->id)) }}" method="POST" class="inline-activity-form d-flex flex-column gap-2 bg-light p-2 rounded border" style="min-width: 250px;">
                                                @csrf
                                                <input type="hidden" name="type" value="Call">
                                                <input type="hidden" name="direction" value="{{ $call->direction ?? 'Outgoing' }}">
                                                <input type="hidden" name="call_status" value="{{ $call->status ?? 'Answered' }}">
                                                <textarea class="form-control form-control-sm" name="note" rows="2" placeholder="Add notes here..." required></textarea>
                                                
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="fas fa-save me-1"></i> Save Note</button>
                                                    <button type="button" class="btn btn-outline-primary btn-sm flex-grow-1" data-bs-toggle="modal" data-bs-target="#conversationModal">Detailed</button>
                                                </div>
                                            </form>
                                        @else
                                            <div class="mb-2 p-3 rounded border bg-white shadow-sm" style="max-width: 280px; white-space: normal; word-break: break-word; overflow-wrap: break-word; line-height: 1.5;">
                                                <span class="text-muted small">
                                                    {{ $call->note }}
                                                </span>
                                            </div>
                                            @if($call->followup_date)
                                                <div class="p-2 rounded border border-info border-opacity-25 bg-soft-info d-flex align-items-center mb-2" style="max-width: 280px;">
                                                    <i class="far fa-calendar-alt text-info me-2"></i>
                                                    <div>
                                                        <div class="text-info fw-bold" style="font-size: 0.7rem;">Follow-up Scheduled</div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">Date: <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($call->followup_date)->format('d M Y h:i A') }}</span></div>
                                                    </div>
                                                </div>
                                            @endif
                                            <div>
                                                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#conversationModal">
                                                    <i class="fas fa-plus me-1"></i> Add Entry
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-phone-slash fa-2x mb-3 text-secondary opacity-50"></i>
                    <div class="fw-semibold">No call records found for this student.</div>
                    <p class="small text-muted mt-1">Click "Add Entry" above to log a conversation or call record.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal 1: Update Interview Outcome Modal -->
<div class="modal fade" id="updateOutcomeModal" tabindex="-1" aria-labelledby="updateOutcomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-custom">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark" id="modalTitle">
                    <i class="fas fa-clipboard-check text-primary me-2"></i> Update Interview Outcome
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateOutcomeForm" action="{{ route('schedule-interviews.outcome', $scheduleInterview->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4" id="modalBody">
                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label">Student *</label>
                            <input class="form-input" type="text" name="studentName" value="{{ $scheduleInterview->name }}" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Company *</label>
                            <input class="form-input" type="text" name="companyName" value="{{ $scheduleInterview->company }}" readonly="readonly">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Attendance *</label>
                            <select class="form-select" name="attendance" id="modalAttendanceSelect" required onchange="toggleReasonField(this.value)">
                                <option value="Attended" {{ strtolower($scheduleInterview->attendance ?? '') === 'attended' ? 'selected' : '' }}>Attended</option>
                                <option value="Not Attended" {{ strtolower($scheduleInterview->attendance ?? '') === 'not attended' ? 'selected' : '' }}>Not Attended</option>
                                <option value="No Show" {{ strtolower($scheduleInterview->attendance ?? '') === 'no show' ? 'selected' : '' }}>No Show</option>
                            </select>
                        </div>
                        <div class="form-group full" id="reasonGroup" style="display: {{ in_array(strtolower($scheduleInterview->attendance ?? ''), ['not attended', 'no show']) ? 'block' : 'none' }};">
                            <label class="form-label text-danger">Reason (if not attended) *</label>
                            <textarea class="form-textarea" name="reason" id="modalReasonInput" rows="2" placeholder="Explain why candidate was absent...">{{ $scheduleInterview->reason }}</textarea>
                        </div>
                        <div class="form-group" id="resultGroup" style="display: {{ in_array(strtolower($scheduleInterview->attendance ?? ''), ['not attended', 'no show']) ? 'none' : 'block' }};">
                            <label class="form-label">Result</label>
                            <select class="form-select" name="result">
                                <option value="" {{ empty($scheduleInterview->result) ? 'selected' : '' }}>&mdash;</option>
                                <option value="Selected" {{ strtolower($scheduleInterview->result ?? '') === 'selected' ? 'selected' : '' }}>Selected</option>
                                <option value="Rejected" {{ strtolower($scheduleInterview->result ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="On Hold" {{ in_array(strtolower($scheduleInterview->result ?? ''), ['on hold', 'hold']) ? 'selected' : '' }}>Hold</option>
                                <option value="Next Round" {{ strtolower($scheduleInterview->result ?? '') === 'next round' ? 'selected' : '' }}>Next Round</option>
                            </select>
                        </div>
                        <div class="form-group full" id="feedbackGroup" style="display: {{ in_array(strtolower($scheduleInterview->attendance ?? ''), ['not attended', 'no show']) ? 'none' : 'block' }};">
                            <label class="form-label">HR Feedback</label>
                            <textarea class="form-textarea" name="hr_feedback" rows="3" placeholder="Feedback notes from the HR or technical team...">{{ $scheduleInterview->hr_feedback }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary shadow-none px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold" id="modalSubmitBtn">
                        <i class="fas fa-check me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Log Activity / Conversation Modal (Matching Leads Show Page) -->
<div class="modal fade" id="conversationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-bottom px-4 py-3">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="fas fa-comments text-primary me-2"></i> Complete Conversation
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="log-activity-form" action="{{ route('schedule-interviews.call-log.store', encrypt($scheduleInterview->id)) }}" method="POST" onsubmit="return false;">
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
                        <hr class="my-3">
                        
                        <h6 class="fw-bold mb-1 text-dark">Call Details</h6>

                        <div class="col-md-6">
                            <label class="form-label text-muted small">Candidate Phone</label>
                            <input type="text" class="form-control bg-white" value="{{ $scheduleInterview->phone }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Candidate Name</label>
                            <input type="text" class="form-control bg-white" value="{{ $scheduleInterview->name }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Call Direction</label>
                            <select class="form-select" name="direction">
                                <option value="Outgoing" selected>Outgoing</option>
                                <option value="Incoming">Incoming</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Call Status / Outcome</label>
                            <select class="form-select" name="call_status">
                                <option value="Answered" selected>Answered</option>
                                <option value="Busy">Busy</option>
                                <option value="Not Reachable">Not Reachable</option>
                                <option value="Callback Requested">Callback Requested</option>
                                <option value="Wrong Number">Wrong Number</option>
                                <option value="Disconnected">Disconnected</option>
                                <option value="Missed">Missed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted small">Duration</label>
                            <input type="text" class="form-control" name="duration" placeholder="e.g. 3m 12s or seconds" value="3m 00s">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top px-4 py-3">
                    <button type="button" class="btn btn-secondary shadow-none px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm" id="log-activity-submit">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="activity-loader"></span>
                        Complete Conversation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Global Audio Element for Playback --}}
<audio id="globalAudioPlayer" style="display: none;"></audio>
@endsection

@push('script')
<script>
function toggleReasonField(val) {
    const isAttended = (val === 'Attended');
    const reasonGroup = document.getElementById('reasonGroup');
    const reasonInput = document.getElementById('modalReasonInput');
    const resultGroup = document.getElementById('resultGroup');
    const feedbackGroup = document.getElementById('feedbackGroup');

    if (isAttended) {
        if (reasonGroup) reasonGroup.style.display = 'none';
        if (reasonInput) reasonInput.required = false;
        if (resultGroup) resultGroup.style.display = 'block';
        if (feedbackGroup) feedbackGroup.style.display = 'block';
    } else {
        if (reasonGroup) reasonGroup.style.display = 'block';
        if (reasonInput) reasonInput.required = true;
        if (resultGroup) resultGroup.style.display = 'none';
        if (feedbackGroup) feedbackGroup.style.display = 'none';
    }
}

$(document).ready(function () {
    // Accordion chevron animation
    $('#previousTimeline').on('show.bs.collapse', function () {
        $(this).prev().find('.transition-icon').css('transform', 'rotate(180deg)');
    }).on('hide.bs.collapse', function () {
        $(this).prev().find('.transition-icon').css('transform', 'rotate(0deg)');
    });

    // Update Outcome Modal Form Submit (AJAX with Toastr + auto-reload)
    $('#updateOutcomeForm').on('submit', function (e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('#modalSubmitBtn');

        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $btn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            },
            success: function (res) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(res.message || 'Interview outcome updated successfully.');
                }
                setTimeout(function () {
                    window.location.reload();
                }, 800);
            },
            error: function (xhr) {
                $btn.attr('disabled', false).html('<i class="fas fa-check me-1"></i> Save');
                let msg = 'Failed to update interview outcome.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                if (typeof toastr !== 'undefined') {
                    toastr.error(msg);
                } else {
                    alert(msg);
                }
            }
        });
    });

    // Complete Conversation Modal Submit
    $('#log-activity-submit').on('click', function () {
        let $btn = $(this);
        let $loader = $btn.find('#activity-loader');
        let $form = $('#log-activity-form');

        $form.find('.invalid-feedback').text('').hide();
        $form.find('.form-control').removeClass('is-invalid');

        // Client-side validation for mandatory note
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
                    if (typeof toastr !== 'undefined') {
                        toastr.success(res.message);
                    }
                    setTimeout(function () {
                        if (res.redirect_url) {
                            window.location.href = res.redirect_url;
                        } else {
                            window.location.reload();
                        }
                    }, 800);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(res.message || 'Something went wrong.');
                    } else {
                        alert(res.message || 'Something went wrong.');
                    }
                    $btn.attr('disabled', false);
                }
            },
            error: function (xhr) {
                $btn.attr('disabled', false);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    $.each(errors, function (key, value) {
                        let $input = $form.find('[name="' + key + '"]');
                        $input.addClass('is-invalid');
                        if (typeof toastr !== 'undefined') {
                            toastr.error(value[0]);
                        }
                    });
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Something went wrong. Please try again.');
                    } else {
                        alert('Something went wrong. Please try again.');
                    }
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Inline note save form
    $(document).on('submit', '.inline-activity-form', function(e) {
        e.preventDefault();
        let $form = $(this);
        let $btn = $form.find('button[type="submit"]');

        if (!this.checkValidity()) {
            e.stopPropagation();
            $form.addClass('was-validated');
            return;
        }

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
                    if (typeof toastr !== 'undefined') toastr.success(res.message);
                    setTimeout(function () {
                        window.location.reload();
                    }, 800);
                } else {
                    if (typeof toastr !== 'undefined') toastr.error(res.message || 'Something went wrong.');
                    $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                }
            },
            error: function (xhr) {
                $btn.attr('disabled', false).html('<i class="fas fa-save me-1"></i> Save Note');
                if (typeof toastr !== 'undefined') {
                    toastr.error('Something went wrong. Please try again.');
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    });

    // Audio Playback Logic
    const audioPlayer = document.getElementById('globalAudioPlayer');
    let currentPlayingBtn = null;

    $(document).on('click', '.play-btn', function () {
        const btn = $(this);
        const id = btn.data('id');
        const directUrl = btn.data('url');
        const icon = btn.find('i');

        if (currentPlayingBtn && currentPlayingBtn[0] === btn[0]) {
            if (!audioPlayer.paused) {
                audioPlayer.pause();
                icon.removeClass('fa-pause').addClass('fa-play');
                btn.removeClass('btn-info').addClass('btn-soft-primary');
            } else {
                audioPlayer.play();
                icon.removeClass('fa-play').addClass('fa-pause');
                btn.removeClass('btn-soft-primary').addClass('btn-info');
            }
            return;
        }

        if (currentPlayingBtn) {
            currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
            currentPlayingBtn.removeClass('btn-info').addClass('btn-soft-primary');
        }

        if (directUrl) {
            audioPlayer.src = directUrl;
            audioPlayer.play();
            currentPlayingBtn = btn;
            icon.removeClass('fa-play').addClass('fa-pause');
            btn.removeClass('btn-soft-primary').addClass('btn-info');
            return;
        }

        icon.removeClass('fa-play').addClass('fa-spinner fa-spin');
        btn.prop('disabled', true);

        $.get("{{ url('calls/get-recording') }}/" + id, function (response) {
            btn.prop('disabled', false);
            if (response.url) {
                audioPlayer.src = response.url;
                audioPlayer.play();

                currentPlayingBtn = btn;
                icon.removeClass('fa-spinner fa-spin').addClass('fa-pause');
                btn.removeClass('btn-soft-primary').addClass('btn-info');
            } else {
                icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
                if (typeof toastr !== 'undefined') toastr.error('Recording link not available.');
            }
        }).fail(function () {
            btn.prop('disabled', false);
            icon.removeClass('fa-spinner fa-spin').addClass('fa-play');
            if (typeof toastr !== 'undefined') toastr.error('Failed to fetch recording.');
        });
    });

    if (audioPlayer) {
        audioPlayer.onended = function () {
            if (currentPlayingBtn) {
                currentPlayingBtn.find('i').removeClass('fa-pause').addClass('fa-play');
                currentPlayingBtn.removeClass('btn-info').addClass('btn-soft-primary');
                currentPlayingBtn = null;
            }
        };
    }
});
</script>
@endpush
