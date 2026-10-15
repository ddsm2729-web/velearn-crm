@extends('layouts.master')

@section('pageTitle', 'Meetings')

@push('css')
<style>
    .meeting-section-title {
        font-weight: 700;
        color: #2b3445;
        font-size: 1.1rem;
    }
    
    .nav-tabs-custom {
        display: flex;
        gap: 8px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 24px;
    }

    .nav-tabs-custom .tab-btn {
        padding: 10px 20px;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        border: none;
        background: transparent;
        border-radius: 8px 8px 0 0;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        text-decoration: none;
    }

    .nav-tabs-custom .tab-btn.active {
        color: #4f46e5;
        background: #f8fafc;
        border-bottom: 2px solid #4f46e5;
    }

    .nav-tabs-custom .tab-badge {
        font-size: 0.7rem;
        background: #e2e8f0;
        color: #475569;
        padding: 2px 7px;
        border-radius: 12px;
        font-weight: 700;
    }

    .nav-tabs-custom .tab-btn.active .tab-badge {
        background: #e0e7ff;
        color: #4338ca;
    }

    /* Common Meeting Items */
    .meeting-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .meeting-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .meeting-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }

    .meeting-item-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .demo-item .meeting-item-icon {
        background-color: #e0e7ff;
        color: #4f46e5;
    }

    .doubt-item .meeting-item-icon {
        background-color: #fef3c7;
        color: #d97706;
    }

    .eval-item .meeting-item-icon {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    .staff-item .meeting-item-icon {
        background-color: #f3e8ff;
        color: #9333ea;
    }

    .group-item .meeting-item-icon {
        background-color: #dbeafe;
        color: #2563eb;
    }

    .meeting-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .meeting-meta {
        display: flex;
        gap: 14px;
        font-size: 0.8rem;
        color: #64748b;
    }

    .meeting-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .badge-status, .badge-type, .meeting-link-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .status-active, .status-scheduled, .status-Active, .status-Scheduled, .status-upcoming {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-completed, .status-Completed {
        background-color: #f1f5f9;
        color: #475569;
    }

    .status-cancelled, .status-Cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .status-pending, .status-Pending {
        background-color: #fef9c3;
        color: #854d0e;
    }

    .badge-type {
        background-color: #f3f4f6;
        color: #374151;
    }

    .badge-eval {
        background-color: #ede9fe;
        color: #6d28d9;
    }

    .badge-staff-type {
        background-color: #f3e8ff;
        color: #7e22ce;
    }

    .badge-group-type {
        background-color: #dbeafe;
        color: #1d4ed8;
    }

    .badge-demo-type {
        background-color: #e0e7ff;
        color: #4338ca;
    }

    .badge-doubt-type {
        background-color: #fef3c7;
        color: #b45309;
    }

    .meeting-link-badge {
        background-color: #e0f2fe;
        color: #0369a1;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .meeting-link-badge:hover {
        background-color: #bae6fd;
        color: #0284c7;
    }

    .btn-join {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        padding: 7px 18px;
        border-radius: 7px;
        font-weight: 600;
        font-size: 0.825rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        box-shadow: 0 3px 10px rgba(79, 70, 229, 0.2);
    }

    .btn-join:hover:not([disabled]) {
        transform: translateY(-1px);
        box-shadow: 0 5px 14px rgba(79, 70, 229, 0.3);
        color: white;
    }

    .empty-state {
        background: #ffffff;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 45px 20px;
        text-align: center;
        color: #64748b;
    }

    .empty-state i {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 12px;
    }
    
    .empty-state h5 {
        font-weight: 600;
        color: #334155;
        font-size: 1rem;
        margin-bottom: 4px;
    }

    .empty-state p {
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    /* Screenshot 1: Evaluation Class Link Card Styles */
    .eval-class-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
        max-width: 720px;
        margin-bottom: 30px;
    }

    .eval-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .eval-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .eval-live-badge {
        background-color: #d1fae5;
        color: #065f46;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .eval-desc {
        color: #6b7280;
        font-size: 0.875rem;
        line-height: 1.5;
        margin-bottom: 22px;
    }

    .eval-form-label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #374151;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }

    .eval-input, .eval-select {
        width: 100%;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.875rem;
        color: #1f2937;
        transition: border-color 0.2s, box-shadow 0.2s;
        background-color: #fff;
    }

    .eval-input:focus, .eval-select:focus {
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .eval-btn-group {
        display: flex;
        gap: 12px;
        margin-top: 22px;
        flex-wrap: wrap;
    }

    .btn-gen-link {
        background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 22px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-gen-link:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        color: #ffffff;
    }

    .btn-connect {
        background: #059669;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-connect:hover {
        background: #047857;
        transform: translateY(-1px);
        color: #ffffff;
    }

    .btn-schedule-save {
        background: #f1f5f9;
        color: #334155;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-schedule-save:hover {
        background: #e2e8f0;
    }

    /* Group Meeting Clean Light Layout */
    .staff-meetings-container {
        background: transparent;
        padding: 0;
        color: #1e293b;
        border: none;
    }

    .staff-meetings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .staff-meetings-header h4 {
        color: #2b3445;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
    }

    .staff-btn-staff {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 7px 16px;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .staff-btn-staff:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #1e293b;
    }

    .staff-btn-group {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: #ffffff;
        border: none;
        padding: 7px 18px;
        border-radius: 8px;
        font-size: 0.825rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        box-shadow: 0 3px 10px rgba(79, 70, 229, 0.2);
    }

    .staff-btn-group:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        color: #ffffff;
    }

    /* Filter Sub-Tabs */
    .staff-filter-pills {
        display: inline-flex;
        gap: 6px;
        margin-bottom: 20px;
        background: #f1f5f9;
        padding: 4px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .staff-pill-btn {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .staff-pill-btn.active {
        background: #ffffff;
        color: #4f46e5;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }

    /* Meeting Item Row */
    .staff-meeting-row {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        transition: all 0.2s;
        gap: 15px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }

    .staff-meeting-row:hover {
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
    }

    .staff-meeting-title {
        color: #1e293b;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .staff-meeting-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 6px;
        flex-wrap: wrap;
    }

    .staff-meeting-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .staff-badge-upcoming {
        background-color: #fef9c3;
        color: #854d0e;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .staff-badge-completed {
        background-color: #f1f5f9;
        color: #475569;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .staff-badge-staff {
        background-color: #f3e8ff;
        color: #7e22ce;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .staff-badge-group {
        background-color: #dbeafe;
        color: #1d4ed8;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">



    <!-- Top Navigation Tabs -->
    <div class="nav-tabs-custom">
        <button type="button" class="tab-btn {{ $activeTab === 'scheduled' ? 'active' : '' }}" data-tab="scheduled" onclick="switchMeetingTab('scheduled')">
            <i class="fas fa-calendar-alt"></i> Scheduled Meetings
            <span class="tab-badge">{{ count($allMeetings) }}</span>
        </button>

        @if($isTechStaff || $isTechHead)
        <button type="button" class="tab-btn {{ $activeTab === 'evaluation' ? 'active' : '' }}" data-tab="evaluation" onclick="switchMeetingTab('evaluation')">
            <i class="fas fa-user-graduate"></i> 1:1 Student Evaluation
            @if(count($studentEvaluationMeetings) > 0)
                <span class="tab-badge">{{ count($studentEvaluationMeetings) }}</span>
            @endif
        </button>
        @endif

        @if($isTechHead || $isTechStaff)
        <button type="button" class="tab-btn {{ in_array($activeTab, ['group_meetings', 'staff_meetings']) ? 'active' : '' }}" data-tab="group_meetings" onclick="switchMeetingTab('group_meetings')">
            <i class="fas fa-users"></i> Group Meeting
            @if(count($staffMeetings) > 0)
                <span class="tab-badge">{{ count($staffMeetings) }}</span>
            @endif
        </button>
        @endif
    </div>

    <!-- ==================== TAB 1: Scheduled Meetings (Current declaration preserved intact) ==================== -->
    <div id="tabContentScheduled" style="{{ $activeTab === 'scheduled' ? 'display: block;' : 'display: none;' }}">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h5 class="meeting-section-title mb-0">
                <i class="fas fa-calendar-alt text-primary me-2"></i> Scheduled Meetings
            </h5>
            @include('partials.daterange_picker', ['startDate' => $startDate, 'endDate' => $endDate])
        </div>

        <div class="meeting-list">
            @forelse($allMeetings as $meeting)
                @php
                    $isDemo = str_contains($meeting->type, 'Demo');
                    $isDoubt = str_contains($meeting->type, 'Doubt');
                    $isEval = str_contains($meeting->type, 'Evaluation');
                    $isStaff = str_contains($meeting->type, 'Staff');
                    $isGroup = str_contains($meeting->type, 'Group');

                    $itemClass = $isDemo ? 'demo-item' : ($isDoubt ? 'doubt-item' : ($isEval ? 'eval-item' : ($isGroup ? 'group-item' : 'staff-item')));
                    $badgeTypeClass = $isDemo ? 'badge-demo-type' : ($isDoubt ? 'badge-doubt-type' : ($isEval ? 'badge-eval' : ($isGroup ? 'badge-group-type' : 'badge-staff-type')));
                    $iconClass = $isDemo ? 'fa-video' : ($isDoubt ? 'fa-question-circle' : ($isEval ? 'fa-user-graduate' : ($isGroup ? 'fa-users' : 'fa-user-tie')));
                @endphp
                <div class="meeting-item {{ $itemClass }}" data-date="{{ $meeting->raw_date }}">
                    <div class="meeting-item-icon">
                        <i class="fas {{ $iconClass }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="meeting-title">
                            {{ $meeting->title }} — {{ $meeting->student_name }}
                        </div>
                        <div class="meeting-meta mb-2">
                            <span><i class="far fa-calendar-alt"></i> {{ $meeting->date_str }}</span>
                            <span><i class="far fa-clock"></i> {{ $meeting->time_str }}</span>
                            @if($meeting->topic)
                                <span><i class="fas fa-info-circle"></i> {{ Str::limit($meeting->topic, 40) }}</span>
                            @endif
                        </div>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <span class="badge-status status-{{ $meeting->status }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $meeting->status }}
                            </span>
                            <span class="badge-type {{ $badgeTypeClass }}">
                                <i class="fas {{ $iconClass }}"></i> {{ $meeting->type }}
                            </span>
                            @if($meeting->link)
                                <a href="{{ $meeting->link }}" target="_blank" class="meeting-link-badge">
                                    <i class="fas fa-link"></i> {{ Str::limit(str_replace(['http://', 'https://'], '', $meeting->link), 30) }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($meeting->link)
                            <a href="{{ $meeting->link }}" target="_blank" class="btn-join"><i class="fas fa-video"></i> Join</a>
                        @else
                            <button class="btn-join" style="opacity: 0.5; background: #94a3b8; box-shadow: none;" disabled><i class="fas fa-video"></i> Join</button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h5>No Scheduled Meetings</h5>
                    <p>There are no upcoming demo, doubt, or evaluation sessions for this date range.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ==================== TAB 2: Evaluation Class Link (1:1 Student Session - Screenshot 1) ==================== -->
    @if($isTechStaff || $isTechHead)
    <div id="tabContentEvaluation" style="{{ $activeTab === 'evaluation' ? 'display: block;' : 'display: none;' }}">
        <!-- Evaluation Class Creation Card (Screenshot 1) -->
        <div class="eval-class-card">
            <div class="eval-header">
                <div class="eval-title">
                    <i class="fas fa-video" style="color: #4f46e5;"></i> Evaluation Class Link
                </div>
                <div class="eval-live-badge">
                    <i class="fas fa-circle" style="font-size: 6px; color: #10b981;"></i> Live Meeting
                </div>
            </div>
            <div class="eval-desc">
                Generate a private meeting link for student evaluation sessions. The link is shared directly with the selected student.
            </div>

            <form action="{{ route('meetings.student-evaluation.store') }}" method="POST" id="evalMeetingForm">
                @csrf
                
                <div class="mb-3">
                    <label class="eval-form-label">SESSION TITLE *</label>
                    <input type="text" name="title" id="evalSessionTitle" class="eval-input" placeholder="e.g. Full Stack — Final Evaluation, Module 9 Review..." required>
                </div>

                <div class="mb-3">
                    <label class="eval-form-label">SELECT STUDENT *</label>
                    <select name="admission_id" id="evalSelectStudent" class="eval-select" required>
                        <option value="">— Select student —</option>
                        @foreach($staffStudents as $stu)
                            <option value="{{ $stu->id }}" data-student-name="{{ $stu->contact->name ?? 'Student' }}">
                                {{ $stu->contact->name ?? 'Student' }} 
                                @if($stu->batch) • Batch: {{ $stu->batch->batch_name ?? $stu->batch->batch_code }} @endif
                                @if($stu->course) • {{ $stu->course->title }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-md-6">
                        <label class="eval-form-label">DATE</label>
                        <input type="date" name="date" id="evalDate" class="eval-input" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="eval-form-label">TIME</label>
                        <input type="time" name="time" id="evalTime" class="eval-input" value="{{ date('H:i') }}">
                    </div>
                </div>

                <!-- Hidden / Dynamic Zoom Link Details -->
                <input type="hidden" name="meet_link" id="evalMeetLink">
                <input type="hidden" name="zoom_meeting_id" id="evalZoomMeetingId">
                <input type="hidden" name="zoom_password" id="evalZoomPassword">
                <input type="hidden" name="zoom_start_url" id="evalZoomStartUrl">

                <div id="evalLinkStatus" class="mb-3" style="display: none;">
                    <div class="p-2 px-3 rounded bg-light border d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-success"></i>
                            <span class="small font-monospace text-truncate" id="evalGeneratedLinkText" style="max-width: 400px;"></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="copyEvalLink()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                </div>

                <div class="eval-btn-group">
                    <button type="button" class="btn-gen-link" id="btnGenLink" onclick="generateStudentZoomLink()">
                        <i class="fas fa-link"></i> <span id="btnGenLinkText">Generate Link</span>
                    </button>
                    <button type="submit" name="action_type" value="connect" class="btn-connect" id="btnConnect">
                        <i class="fas fa-video"></i> Connect
                    </button>
                    <button type="submit" name="action_type" value="save" class="btn-schedule-save">
                        <i class="far fa-calendar-check"></i> Save & Schedule
                    </button>
                </div>
            </form>
        </div>

        <!-- List of Created 1:1 Student Evaluation Meetings -->
        <div class="mt-4">
            <h6 class="fw-bold text-dark mb-3">
                <i class="fas fa-list-ul text-primary me-2"></i> Scheduled Student Evaluation Sessions ({{ count($studentEvaluationMeetings) }})
            </h6>

            <div class="meeting-list">
                @forelse($studentEvaluationMeetings as $evalMeeting)
                    <div class="meeting-item eval-item">
                        <div class="meeting-item-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="meeting-title">
                                {{ $evalMeeting->title }} — {{ $evalMeeting->student_name ?: ($evalMeeting->admission->contact->name ?? 'Student') }}
                            </div>
                            <div class="meeting-meta mb-2">
                                <span><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($evalMeeting->date)->format('d M Y') }}</span>
                                <span><i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($evalMeeting->time)->format('g:i A') }}</span>
                                @if($evalMeeting->admission && $evalMeeting->admission->course)
                                    <span><i class="fas fa-book"></i> {{ $evalMeeting->admission->course->title }}</span>
                                @endif
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <span class="badge-status status-{{ $evalMeeting->status }}">
                                    <i class="fas fa-circle" style="font-size: 6px;"></i> {{ $evalMeeting->status }}
                                </span>
                                <span class="badge-type badge-eval">
                                    <i class="fas fa-user-graduate"></i> 1:1 Student Evaluation
                                </span>
                                @if($evalMeeting->meet_link)
                                    <a href="{{ $evalMeeting->meet_link }}" target="_blank" class="meeting-link-badge">
                                        <i class="fas fa-link"></i> {{ Str::limit(str_replace(['http://', 'https://'], '', $evalMeeting->meet_link), 35) }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            @if($evalMeeting->meet_link)
                                <a href="{{ $evalMeeting->meet_link }}" target="_blank" class="btn-join">
                                    <i class="fas fa-video"></i> Join
                                </a>
                            @endif
                            @if($evalMeeting->status === 'upcoming')
                                <form action="{{ route('meetings.status', $evalMeeting->id) }}" method="POST" onsubmit="return confirm('Mark this evaluation as completed?');">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Mark Completed" style="padding: 7px 12px; font-size: 0.8rem; border-radius: 7px;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-user-graduate"></i>
                        <h5>No 1:1 Student Evaluations Scheduled Yet</h5>
                        <p>Select a student from your batch above and click "Generate Link" or "Connect" to start a 1:1 evaluation session.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <!-- ==================== TAB 3: Group Meeting (Screenshot 2: 1:1 Staff & Group Meetings) ==================== -->
    @if($isTechHead || $isTechStaff)
    <div id="tabContentStaffMeetings" style="{{ in_array($activeTab, ['group_meetings', 'staff_meetings']) ? 'display: block;' : 'display: none;' }}">
        <div class="staff-meetings-container">
            <div class="staff-meetings-header">
                <div>
                    <h4>Group Meeting</h4>
                </div>
                @if($isTechHead)
                <div class="d-flex gap-2">
                    <button class="staff-btn-staff" data-bs-toggle="modal" data-bs-target="#modalStaffMeeting">
                        <i class="fas fa-user-friends"></i> + Staff Meeting
                    </button>
                    <button class="staff-btn-group" data-bs-toggle="modal" data-bs-target="#modalGroupMeeting">
                        <i class="fas fa-users"></i> + Group Meeting
                    </button>
                </div>
                @endif
            </div>

            <!-- Filter Tabs matching Screenshot 2: All | Staff Meeting | Group Meeting -->
            <div class="staff-filter-pills">
                <button class="staff-pill-btn active" id="btnFilterAll" onclick="filterStaffMeetings('all')">
                    All
                </button>
                <button class="staff-pill-btn" id="btnFilterStaff" onclick="filterStaffMeetings('Staff Meeting')">
                    <i class="fas fa-user-friends"></i> Staff Meeting
                </button>
                <button class="staff-pill-btn" id="btnFilterGroup" onclick="filterStaffMeetings('Group Meeting')">
                    <i class="fas fa-users"></i> Group Meeting
                </button>
            </div>

            <!-- Meetings List -->
            <div id="staffMeetingRowsList">
                @forelse($staffMeetings as $sm)
                    <div class="staff-meeting-row" data-type="{{ $sm->meeting_type }}">
                        <div class="flex-grow-1">
                            <div class="staff-meeting-title">
                                {{ $sm->title }}
                            </div>
                            <div class="staff-meeting-meta">
                                <span>
                                    <i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($sm->date)->format('d M Y') }}
                                </span>
                                <span>
                                    <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($sm->time)->format('H:i') }}
                                </span>
                                <span>
                                    @if($sm->meeting_type === 'Group Meeting')
                                        <i class="fas fa-users"></i> All Staff ({{ count($technicalStaffList) }})
                                    @else
                                        <i class="fas fa-user-tie"></i> {{ $sm->staff->name ?? 'Staff Member' }}
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <span class="{{ $sm->status === 'completed' ? 'staff-badge-completed' : 'staff-badge-upcoming' }}">
                                    <i class="fas fa-circle" style="font-size: 5px;"></i> {{ $sm->status ?: 'upcoming' }}
                                </span>
                                <span class="{{ $sm->meeting_type === 'Group Meeting' ? 'staff-badge-group' : 'staff-badge-staff' }}">
                                    <i class="fas {{ $sm->meeting_type === 'Group Meeting' ? 'fa-users' : 'fa-user-friends' }}"></i> {{ $sm->meeting_type === 'Group Meeting' ? 'Group Meeting' : '1:1 Staff Meeting' }}
                                </span>
                                @if($sm->meet_link)
                                    <a href="{{ $sm->meet_link }}" target="_blank" class="meeting-link-badge">
                                        <i class="fas fa-link"></i> {{ Str::limit(str_replace(['http://', 'https://'], '', $sm->meet_link), 30) }}
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div>
                            @if($sm->status === 'completed')
                                <a href="{{ $sm->meet_link ?: '#' }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="border-radius: 7px; font-weight: 600; font-size: 0.825rem; padding: 7px 16px;">
                                    <i class="fas fa-play"></i> Recording
                                </a>
                            @else
                                <a href="{{ $sm->meet_link ?: '#' }}" target="_blank" class="btn-join">
                                    <i class="fas fa-video"></i> Join
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-calendar-check"></i>
                        <h5>No Group Meetings Scheduled</h5>
                        <p class="small text-muted mb-0">
                            @if($isTechHead)
                                Use "+ Staff Meeting" or "+ Group Meeting" above to schedule meetings.
                            @else
                                Any staff or group meetings organized by the Technical Head will appear here.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal 1: + Staff Meeting (1:1 with specific Technical Staff) -->
<div class="modal fade" id="modalStaffMeeting" tabindex="-1" aria-labelledby="modalStaffMeetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none; overflow: hidden;">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title fw-bold" id="modalStaffMeetingLabel">
                    <i class="fas fa-user-friends me-2"></i> Schedule 1:1 Staff Meeting
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('meetings.staff-meeting.store') }}" method="POST">
                @csrf
                <input type="hidden" name="meeting_type" value="Staff Meeting">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">MEETING TITLE *</label>
                        <input type="text" name="title" id="staffMeetTitle" class="form-control" placeholder="e.g. 1:1 — Performance Review, Curriculum Sync" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">SELECT TECHNICAL STAFF *</label>
                        <select name="staff_id" class="form-select" required>
                            <option value="">— Select Technical Staff —</option>
                            @foreach($technicalStaffList as $stf)
                                <option value="{{ $stf->id }}">
                                    {{ $stf->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">DATE *</label>
                            <input type="date" name="date" id="staffMeetDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">TIME *</label>
                            <input type="time" name="time" id="staffMeetTime" class="form-control" value="{{ date('H:i') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-muted mb-0">MEETING LINK</label>
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 text-decoration-none" onclick="generateStaffModalZoom('staffMeetTitle', 'staffMeetDate', 'staffMeetTime', 'staffMeetLink')">
                                <i class="fas fa-magic"></i> Auto-Generate Zoom Link
                            </button>
                        </div>
                        <input type="url" name="meet_link" id="staffMeetLink" class="form-control" placeholder="https://zoom.us/j/... or auto-generate">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">AGENDA / NOTES</label>
                        <textarea name="agenda" class="form-control" rows="2" placeholder="Discussion points..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                        <i class="fas fa-calendar-plus me-1"></i> Schedule Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: + Group Meeting (All Technical Staff) -->
<div class="modal fade" id="modalGroupMeeting" tabindex="-1" aria-labelledby="modalGroupMeetingLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; border: none; overflow: hidden;">
            <div class="modal-header bg-dark text-white">
                <h6 class="modal-title fw-bold" id="modalGroupMeetingLabel">
                    <i class="fas fa-users me-2"></i> Schedule Group Meeting (All Staff)
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('meetings.staff-meeting.store') }}" method="POST">
                @csrf
                <input type="hidden" name="meeting_type" value="Group Meeting">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">MEETING TITLE *</label>
                        <input type="text" name="title" id="groupMeetTitle" class="form-control" placeholder="e.g. Weekly Curriculum Sync, Tech Stack Discussion" required>
                    </div>

                    <div class="mb-3 p-2 px-3 bg-light rounded border text-muted small">
                        <i class="fas fa-info-circle text-primary me-1"></i>
                        Invites <strong>All Technical Staff ({{ count($technicalStaffList) }} members)</strong> automatically.
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">DATE *</label>
                            <input type="date" name="date" id="groupMeetDate" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold small text-muted">TIME *</label>
                            <input type="time" name="time" id="groupMeetTime" class="form-control" value="{{ date('H:i') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-bold small text-muted mb-0">MEETING LINK</label>
                            <button type="button" class="btn btn-sm btn-link text-primary p-0 text-decoration-none" onclick="generateStaffModalZoom('groupMeetTitle', 'groupMeetDate', 'groupMeetTime', 'groupMeetLink')">
                                <i class="fas fa-magic"></i> Auto-Generate Zoom Link
                            </button>
                        </div>
                        <input type="url" name="meet_link" id="groupMeetLink" class="form-control" placeholder="https://zoom.us/j/... or auto-generate">
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-muted">AGENDA / NOTES</label>
                        <textarea name="agenda" class="form-control" rows="2" placeholder="Topics for the group meeting..."></textarea>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold">
                        <i class="fas fa-users me-1"></i> Schedule Group Meeting
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function switchMeetingTab(tabName) {
        if (!tabName) return;

        const scheduledTab = document.getElementById('tabContentScheduled');
        const evalTab = document.getElementById('tabContentEvaluation');
        const staffTab = document.getElementById('tabContentStaffMeetings');

        if (scheduledTab) {
            scheduledTab.style.display = tabName === 'scheduled' ? 'block' : 'none';
        }
        if (evalTab) {
            evalTab.style.display = tabName === 'evaluation' ? 'block' : 'none';
        }
        if (staffTab) {
            staffTab.style.display = (tabName === 'staff_meetings' || tabName === 'group_meetings') ? 'block' : 'none';
        }

        // Update active class on custom tabs
        document.querySelectorAll('.nav-tabs-custom .tab-btn').forEach(btn => {
            const btnTab = btn.getAttribute('data-tab');
            if (btnTab === tabName || (btnTab === 'group_meetings' && (tabName === 'group_meetings' || tabName === 'staff_meetings'))) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Update query string in URL without full reload
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            window.history.replaceState({}, '', url);
        } catch (e) {}
    }

    // Generate Zoom / Instant Live Link for Student Evaluation (Screenshot 1)
    function generateStudentZoomLink() {
        const titleInput = document.getElementById('evalSessionTitle');
        const studentSelect = document.getElementById('evalSelectStudent');
        const dateInput = document.getElementById('evalDate');
        const timeInput = document.getElementById('evalTime');
        const btnText = document.getElementById('btnGenLinkText');

        const title = titleInput.value.trim() || 'Student Evaluation Session';
        const date = dateInput.value || '{{ date("Y-m-d") }}';
        const time = timeInput.value || '{{ date("H:i") }}';

        btnText.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

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
            btnText.innerHTML = 'Generate Link';
            if (data.link) {
                document.getElementById('evalMeetLink').value = data.link;
                if (data.zoom_meeting_id) {
                    document.getElementById('evalZoomMeetingId').value = data.zoom_meeting_id;
                    document.getElementById('evalZoomPassword').value = data.zoom_password || '';
                    document.getElementById('evalZoomStartUrl').value = data.start_url || '';
                }

                document.getElementById('evalLinkStatus').style.display = 'block';
                document.getElementById('evalGeneratedLinkText').innerText = data.link;
                
                alert(data.message || 'Meeting link generated successfully!');
            }
        })
        .catch(err => {
            btnText.innerHTML = 'Generate Link';
            console.error('Link gen error:', err);
            const fallback = 'https://meet.jit.si/velearn-eval-' + Math.random().toString(36).substring(2, 9);
            document.getElementById('evalMeetLink').value = fallback;
            document.getElementById('evalLinkStatus').style.display = 'block';
            document.getElementById('evalGeneratedLinkText').innerText = fallback;
        });
    }

    function copyEvalLink() {
        const link = document.getElementById('evalMeetLink').value;
        if (link) {
            navigator.clipboard.writeText(link);
            alert('Meeting link copied to clipboard!');
        }
    }

    // Modal Zoom Generation Helper for Technical Head
    function generateStaffModalZoom(titleId, dateId, timeId, linkInputId) {
        const title = document.getElementById(titleId).value.trim() || 'Staff Meeting';
        const date = document.getElementById(dateId).value || '{{ date("Y-m-d") }}';
        const time = document.getElementById(timeId).value || '{{ date("H:i") }}';
        const linkInput = document.getElementById(linkInputId);

        linkInput.placeholder = 'Generating Zoom link...';

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
            if (data.link) {
                linkInput.value = data.link;
            }
        })
        .catch(err => {
            console.error(err);
            linkInput.value = 'https://meet.jit.si/velearn-staff-' + Math.random().toString(36).substring(2, 9);
        });
    }

    // Client-side sub-filter for Screenshot 2 (All | Staff Meeting | Group Meeting)
    function filterStaffMeetings(type) {
        document.querySelectorAll('.staff-pill-btn').forEach(btn => btn.classList.remove('active'));
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('active');
        }

        const rows = document.querySelectorAll('#staffMeetingRowsList .staff-meeting-row, #staffMeetingRowsList .dark-meeting-row');
        rows.forEach(row => {
            if (type === 'all' || row.getAttribute('data-type') === type) {
                row.style.display = 'flex';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Ensure tab is set on load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.nav-tabs-custom .tab-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const tab = this.getAttribute('data-tab');
                switchMeetingTab(tab);
            });
        });

        const urlParams = new URLSearchParams(window.location.search);
        const currentTab = urlParams.get('tab') || '{{ $activeTab }}' || 'scheduled';
        switchMeetingTab(currentTab);
    });
</script>
@endsection