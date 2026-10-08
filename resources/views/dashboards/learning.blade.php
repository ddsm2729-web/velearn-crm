@extends('layouts.header')

@section('pageTitle', 'Learning Coordinator Dashboard')

@push('css')
<style>
    .lc-hero {
        background: linear-gradient(135deg, #1a1c38 0%, #2e1a47 45%, #0f3d59 100%);
        border-radius: 1.25rem;
        padding: 2.25rem 2.5rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(15, 61, 89, 0.18);
    }
    .lc-hero::before {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(236, 72, 153, 0.22) 0%, rgba(236, 72, 153, 0) 70%);
        pointer-events: none;
    }
    .lc-hero::after {
        content: '';
        position: absolute;
        bottom: -70px;
        right: 140px;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, rgba(56, 189, 248, 0) 70%);
        pointer-events: none;
    }
    .lc-hero-avatar {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        margin-bottom: 0.75rem;
    }
    .lc-chip {
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 2rem;
        padding: 0.45rem 1rem;
        font-size: 0.8rem;
        color: #fff;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .live-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        box-shadow: 0 0 8px #10b981;
        animation: pulse-live 1.6s infinite;
    }
    @keyframes pulse-live {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.85); }
    }

    /* KPI Cards */
    .lc-kpi {
        background: #ffffff;
        border-radius: 1.15rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        padding: 1.5rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .lc-kpi:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }
    .lc-kpi-top-bar {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .lc-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.85rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 0.85rem;
    }
    .lc-kpi-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #718096;
        font-weight: 700;
        margin-bottom: 0.35rem;
    }
    .lc-kpi-num {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1.1;
        color: #1a202c;
        margin-bottom: 0.4rem;
    }
    .lc-kpi-sub {
        font-size: 0.78rem;
        color: #718096;
    }
    .lc-prog {
        height: 5px;
        border-radius: 99px;
        background: #edf2f7;
        overflow: hidden;
        margin-top: 0.85rem;
    }
    .lc-prog-fill {
        height: 100%;
        border-radius: 99px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Section Cards */
    .lc-panel {
        background: #ffffff;
        border-radius: 1.15rem;
        border: 1px solid #edf2f7;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 1.75rem;
    }
    .lc-panel-head {
        padding: 1.2rem 1.6rem;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
    }
    .lc-panel-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* Donut Chart */
    .lc-donut-wrap {
        position: relative;
        height: 210px;
        margin: 1.25rem 0 1rem;
    }
    .lc-donut-center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        pointer-events: none;
        z-index: 2;
    }
    .lc-donut-val {
        font-size: 2.1rem;
        font-weight: 800;
        color: #1a202c;
        line-height: 1;
    }
    .lc-donut-lbl {
        font-size: 0.68rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 700;
        margin-top: 0.2rem;
    }

    .lc-leg-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.55rem 0;
        border-bottom: 1px solid #edf2f7;
    }
    .lc-leg-row:last-child {
        border-bottom: none;
    }
    .lc-leg-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    /* Timeline / Activity List */
    .lc-activity-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid #edf2f7;
        transition: background 0.15s ease;
    }
    .lc-activity-row:hover {
        background: #fafbfd;
    }
    .lc-activity-row:last-child {
        border-bottom: none;
    }

    /* Zero State */
    .lc-empty-state {
        padding: 3.5rem 1.5rem;
        text-align: center;
    }
    .lc-empty-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, rgba(56, 189, 248, 0.12) 100%);
        color: #10b981;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.2rem;
        margin-bottom: 1.25rem;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.15);
    }

    /* Quick Action Link */
    .lc-quick-tile {
        padding: 1rem 1.25rem;
        border-radius: 0.85rem;
        border: 1px solid #edf2f7;
        background: #fafbfd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        text-decoration: none;
        color: #2d3748;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
    }
    .lc-quick-tile:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        color: #1a202c;
    }
</style>
@endpush

@section('section')
<div class="container-fluid p-0">

    {{-- HERO BANNER --}}
    <div class="lc-hero">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div style="position: relative; z-index: 1;">
                <div class="lc-hero-avatar">
                    <i class="fas fa-tasks"></i>
                </div>
                <h2 class="text-white fw-bold mb-1">
                    Good {{ now()->format('H') < 12 ? 'Morning' : (now()->format('H') < 17 ? 'Afternoon' : 'Evening') }}, {{ auth()->user()->first_name ?? auth()->user()->name }}! 👋
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 0.95rem;">
                    Academic Approvals & Learning Operations Hub · {{ now()->format('l, d M Y') }}
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap align-items-start" style="position: relative; z-index: 1;">
                <span class="lc-chip">
                    <span class="live-pulse"></span> LC Session Active
                </span>
                <span class="lc-chip">
                    <i class="far fa-calendar-alt"></i> {{ now()->format('d M Y') }}
                </span>
                <div>
                    @include('partials.daterange_picker')
                </div>
            </div>
        </div>

        {{-- Hero Quick Shortcuts Bar --}}
        <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top border-white border-opacity-10" style="position: relative; z-index: 1;">
            <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 shadow-sm">
                <i class="fas fa-file-signature me-1"></i> Assignment Centre ({{ $totals['assignment_total'] }})
            </a>
            <a href="{{ route('mini_projects.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 shadow-sm">
                <i class="fas fa-layer-group me-1"></i> Mini Projects ({{ $totals['mini_total'] }})
            </a>
            <a href="{{ route('main_projects.index') }}" class="btn btn-sm btn-outline-light rounded-pill px-3 py-1 shadow-sm">
                <i class="fas fa-project-diagram me-1"></i> Main Projects ({{ $totals['main_total'] }})
            </a>
            @if(($totals['total_pending'] ?? 0) > 0)
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill ms-auto fw-bold d-inline-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ $totals['total_pending'] }} Requests Need Review
                </span>
            @else
                <span class="badge bg-success-subtle text-white border border-white border-opacity-25 px-3 py-2 rounded-pill ms-auto fw-bold d-inline-flex align-items-center">
                    <i class="fas fa-check-circle me-1"></i> All Records Approved
                </span>
            @endif
        </div>
    </div>

    {{-- TOP KPI CARDS (4 Cards) --}}
    <div class="row g-3 mb-4">
        {{-- Card 1: Pending Approvals Queue (High Priority) --}}
        <div class="col-sm-6 col-xl-3">
            <div class="lc-kpi h-100">
                <div class="lc-kpi-top-bar" style="background: linear-gradient(90deg, #f59e0b, #ef4444);"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="lc-kpi-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fas fa-clock"></i>
                    </div>
                    @if($totals['total_pending'] > 0)
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1 small fw-bold">
                            Requires Action
                        </span>
                    @else
                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small fw-bold">
                            Up to Date
                        </span>
                    @endif
                </div>
                <div class="lc-kpi-label">Waiting LC Approval</div>
                <div class="lc-kpi-num" style="{{ $totals['total_pending'] > 0 ? 'color: #e11d48;' : 'color: #059669;' }}">
                    {{ $totals['total_pending'] }}
                </div>
                <div class="lc-kpi-sub d-flex justify-content-between">
                    <span>Active Records: <strong>{{ $totals['total_active'] }}</strong></span>
                    <span>Approval Rate: <strong>{{ $totals['approval_rate'] }}%</strong></span>
                </div>
                <div class="lc-prog">
                    <div class="lc-prog-fill" style="background: linear-gradient(90deg, #f59e0b, #ef4444); width: {{ $totals['total_items'] > 0 ? min(100, ($totals['total_pending'] / $totals['total_items']) * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>

        {{-- Card 2: Assignments --}}
        <div class="col-sm-6 col-xl-3">
            <div class="lc-kpi h-100">
                <div class="lc-kpi-top-bar" style="background: linear-gradient(90deg, #ec4899, #f43f5e);"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="lc-kpi-icon" style="background: #fce7f3; color: #db2777;">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <a href="{{ route('assignments.index') }}" class="text-muted text-decoration-none small" title="View Assignments">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div class="lc-kpi-label">Total Assignments</div>
                <div class="lc-kpi-num">{{ $totals['assignment_total'] }}</div>
                <div class="lc-kpi-sub d-flex justify-content-between">
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>{{ $totals['assignment_active'] }} Active</span>
                    <span class="text-warning"><i class="fas fa-clock me-1"></i>{{ $totals['assignment_pending'] }} Pending</span>
                </div>
                <div class="lc-prog">
                    <div class="lc-prog-fill" style="background: linear-gradient(90deg, #ec4899, #f43f5e); width: {{ $totals['assignment_total'] > 0 ? min(100, ($totals['assignment_active'] / $totals['assignment_total']) * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>

        {{-- Card 3: Mini Projects --}}
        <div class="col-sm-6 col-xl-3">
            <div class="lc-kpi h-100">
                <div class="lc-kpi-top-bar" style="background: linear-gradient(90deg, #10b981, #06b6d4);"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="lc-kpi-icon" style="background: #d1fae5; color: #059669;">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <a href="{{ route('mini_projects.index') }}" class="text-muted text-decoration-none small" title="View Mini Projects">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div class="lc-kpi-label">Total Mini Projects</div>
                <div class="lc-kpi-num">{{ $totals['mini_total'] }}</div>
                <div class="lc-kpi-sub d-flex justify-content-between">
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>{{ $totals['mini_active'] }} Active</span>
                    <span class="text-warning"><i class="fas fa-clock me-1"></i>{{ $totals['mini_pending'] }} Pending</span>
                </div>
                <div class="lc-prog">
                    <div class="lc-prog-fill" style="background: linear-gradient(90deg, #10b981, #06b6d4); width: {{ $totals['mini_total'] > 0 ? min(100, ($totals['mini_active'] / $totals['mini_total']) * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>

        {{-- Card 4: Main Projects --}}
        <div class="col-sm-6 col-xl-3">
            <div class="lc-kpi h-100">
                <div class="lc-kpi-top-bar" style="background: linear-gradient(90deg, #6366f1, #8b5cf6);"></div>
                <div class="d-flex justify-content-between align-items-start">
                    <div class="lc-kpi-icon" style="background: #e0e7ff; color: #4f46e5;">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <a href="{{ route('main_projects.index') }}" class="text-muted text-decoration-none small" title="View Main Projects">
                        <i class="fas fa-arrow-up-right-from-square"></i>
                    </a>
                </div>
                <div class="lc-kpi-label">Total Main Projects</div>
                <div class="lc-kpi-num">{{ $totals['main_total'] }}</div>
                <div class="lc-kpi-sub d-flex justify-content-between">
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>{{ $totals['main_active'] }} Active</span>
                    <span class="text-warning"><i class="fas fa-clock me-1"></i>{{ $totals['main_pending'] }} Pending</span>
                </div>
                <div class="lc-prog">
                    <div class="lc-prog-fill" style="background: linear-gradient(90deg, #6366f1, #8b5cf6); width: {{ $totals['main_total'] > 0 ? min(100, ($totals['main_active'] / $totals['main_total']) * 100) : 0 }}%;"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN 2-COLUMN SECTION --}}
    <div class="row g-4 mb-4">

        {{-- LEFT COLUMN: Approval Queue & Recent Activity (Col 8) --}}
        <div class="col-lg-8">

            {{-- PANEL 1: Items Awaiting Approval Desk --}}
            <div class="lc-panel">
                <div class="lc-panel-head">
                    <h5 class="lc-panel-title">
                        <i class="fas fa-clipboard-check text-warning fs-5"></i> Items Awaiting Approval Queue
                        @if($totals['total_pending'] > 0)
                            <span class="badge bg-warning text-dark rounded-pill px-3 py-1">{{ $totals['total_pending'] }} Pending</span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">0 Pending</span>
                        @endif
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('assignments.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" style="font-size: 0.78rem;">
                            Assignment Centre <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="p-0">
                    @if($totals['total_pending'] == 0)
                        <div class="lc-empty-state">
                            <div class="lc-empty-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Your Approval Queue is Clear! 🎉</h4>
                            <p class="text-muted mx-auto mb-4" style="max-width: 520px; font-size: 0.92rem; line-height: 1.6;">
                                All assignments, mini projects, and main projects in the system are currently verified and approved. New academic tasks submitted by Technical Staff will appear here immediately for review.
                            </p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('assignments.index') }}" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold">
                                    <i class="fas fa-file-signature me-1"></i> View Assignments
                                </a>
                                <a href="{{ route('mini_projects.index') }}" class="btn btn-outline-success rounded-pill px-4 py-2 fw-semibold">
                                    <i class="fas fa-layer-group me-1"></i> View Mini Projects
                                </a>
                                <a href="{{ route('main_projects.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                                    <i class="fas fa-project-diagram me-1"></i> View Main Projects
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: #718096;">
                                    <tr>
                                        <th class="ps-4">Type</th>
                                        <th>Title & Module</th>
                                        <th>Course & Batch</th>
                                        <th>Created / Due</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingAssignments as $assignment)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">
                                                    Assignment
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $assignment->title }}</div>
                                                <div class="small text-muted">{{ $assignment->module ?? 'General' }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-secondary">{{ $assignment->course->title ?? 'N/A' }}</div>
                                                <div class="small text-muted">{{ $assignment->batch->batch_name ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-muted">{{ $assignment->created_at ? $assignment->created_at->format('d M Y') : 'N/A' }}</div>
                                                @if($assignment->due_date)
                                                    <div class="small text-danger fw-bold">Due: {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-normal">
                                                    Waiting Approval
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('assignments.index', ['course_id' => $assignment->course_id]) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    Review <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach($pendingMiniProjects as $project)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                                    Mini Project
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $project->title }}</div>
                                                <div class="small text-muted">{{ $project->module ?? 'General' }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-secondary">{{ $project->course->title ?? 'N/A' }}</div>
                                                <div class="small text-muted">{{ $project->batch->batch_name ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-muted">{{ $project->created_at ? $project->created_at->format('d M Y') : 'N/A' }}</div>
                                                @if($project->due_date)
                                                    <div class="small text-danger fw-bold">Due: {{ \Carbon\Carbon::parse($project->due_date)->format('d M Y') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-normal">
                                                    Waiting Approval
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('mini_projects.index', ['course_id' => $project->course_id]) }}" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                                    Review <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach($pendingMainProjects as $project)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1">
                                                    Main Project
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $project->title }}</div>
                                                <div class="small text-muted">{{ $project->module ?? 'General' }}</div>
                                            </td>
                                            <td>
                                                <div class="small fw-semibold text-secondary">{{ $project->course->title ?? 'N/A' }}</div>
                                                <div class="small text-muted">{{ $project->batch->batch_name ?? 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-muted">{{ $project->created_at ? $project->created_at->format('d M Y') : 'N/A' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-normal">
                                                    Waiting Approval
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('main_projects.index', ['course_id' => $project->course_id]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                                    Review <i class="fas fa-arrow-right ms-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            {{-- PANEL 2: Recent Academic Directory & Activity Feed --}}
            <div class="lc-panel">
                <div class="lc-panel-head">
                    <h5 class="lc-panel-title">
                        <i class="fas fa-history text-primary fs-5"></i> Recent Academic Tasks & Records
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small">
                        Showing Latest Added
                    </span>
                </div>

                <div class="p-0">
                    @forelse($recentRecords as $rec)
                        <div class="lc-activity-row">
                            <span class="badge bg-{{ $rec->type_badge_class }}-subtle text-{{ $rec->type_badge_class }} border border-{{ $rec->type_badge_class }}-subtle rounded-pill px-3 py-1" style="font-size: 0.72rem; min-width: 100px; text-align: center;">
                                {{ $rec->record_type }}
                            </span>
                            <div class="flex-grow-1">
                                <div class="fw-semibold text-dark" style="font-size: 0.9rem;">{{ $rec->title }}</div>
                                <div class="text-muted small">
                                    <i class="fas fa-book-open me-1 text-secondary opacity-75"></i> {{ $rec->course->title ?? 'Course' }}
                                    &bull;
                                    <i class="fas fa-users me-1 text-secondary opacity-75"></i> {{ $rec->batch->batch_name ?? 'Batch' }}
                                    @if($rec->module)
                                        &bull; <span>{{ $rec->module }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-end">
                                @if($rec->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small me-2">
                                        <i class="fas fa-check-circle me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1 small me-2" style="color: #d97706 !important;">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @endif
                                <a href="{{ $rec->manage_url }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1" style="font-size: 0.76rem;">
                                    Manage
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 px-3 text-muted">
                            <i class="fas fa-folder-open text-muted mb-2 d-block" style="font-size: 2rem; opacity: 0.4;"></i>
                            <div class="fw-semibold text-dark">No Academic Records Created Yet</div>
                            <p class="small text-muted mb-0">Once instructors create assignments or projects, their history and approval logs will stream here.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: Analytics, Guidelines & Shortcuts (Col 4) --}}
        <div class="col-lg-4">

            {{-- WIDGET 1: Approval Distribution Donut Chart --}}
            <div class="lc-panel">
                <div class="lc-panel-head">
                    <h5 class="lc-panel-title">
                        <i class="fas fa-chart-pie text-success fs-5"></i> Approval Health
                    </h5>
                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 small">
                        {{ $totals['approval_rate'] }}% Verified
                    </span>
                </div>
                <div class="p-4">
                    <div class="lc-donut-wrap">
                        <canvas id="lc-approval-donut"></canvas>
                        <div class="lc-donut-center">
                            <div class="lc-donut-val">{{ $totals['approval_rate'] }}%</div>
                            <div class="lc-donut-lbl">Approved</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="lc-leg-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lc-leg-dot" style="background: #10b981;"></span>
                                <span class="small fw-semibold text-dark">Active & Approved</span>
                            </div>
                            <div>
                                <span class="small fw-bold text-dark">{{ $totals['total_active'] }}</span>
                                <span class="small text-muted ms-1">({{ $totals['total_items'] > 0 ? round(($totals['total_active'] / $totals['total_items']) * 100) : 100 }}%)</span>
                            </div>
                        </div>
                        <div class="lc-leg-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lc-leg-dot" style="background: #f59e0b;"></span>
                                <span class="small fw-semibold text-dark">Waiting LC Approval</span>
                            </div>
                            <div>
                                <span class="small fw-bold text-dark">{{ $totals['total_pending'] }}</span>
                                <span class="small text-muted ms-1">({{ $totals['total_items'] > 0 ? round(($totals['total_pending'] / $totals['total_items']) * 100) : 0 }}%)</span>
                            </div>
                        </div>
                        <div class="lc-leg-row">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lc-leg-dot" style="background: #6366f1;"></span>
                                <span class="small fw-semibold text-dark">Total Records Managed</span>
                            </div>
                            <div>
                                <span class="small fw-bold text-dark">{{ $totals['total_items'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- WIDGET 2: LC Approval Workflow Checklist --}}
            <div class="lc-panel">
                <div class="lc-panel-head">
                    <h5 class="lc-panel-title">
                        <i class="fas fa-clipboard-list text-info fs-5"></i> Verification Checklist
                    </h5>
                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1 small">
                        Guide
                    </span>
                </div>
                <div class="p-4">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; flex-shrink: 0; font-size: 0.85rem;">1</div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.88rem;">Inspect Curriculum Alignment</div>
                            <p class="text-muted small mb-0">Ensure assignments & projects correctly match the target course and module.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; flex-shrink: 0; font-size: 0.85rem;">2</div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.88rem;">Review Rubric & Due Dates</div>
                            <p class="text-muted small mb-0">Confirm criteria, maximum marks, and due dates before activating.</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center fw-bold" style="width: 30px; height: 30px; flex-shrink: 0; font-size: 0.85rem;">3</div>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size: 0.88rem;">Activate to Release to Students</div>
                            <p class="text-muted small mb-0">Switch status from Pending to Active to publish task to batch students.</p>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                        <div class="small fw-bold text-uppercase text-muted mb-2" style="font-size: 0.68rem; letter-spacing: 0.08em;">Status Conventions:</div>
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <span class="small text-dark"><span class="badge bg-success rounded-pill px-2 me-1">Active</span> Visible to enrolled students</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small text-dark"><span class="badge bg-warning text-dark rounded-pill px-2 me-1">Pending</span> Draft / Waiting LC review</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- WIDGET 3: Quick Navigation Shortcuts --}}
            <div class="lc-panel">
                <div class="lc-panel-head">
                    <h5 class="lc-panel-title">
                        <i class="fas fa-compass text-danger fs-5"></i> Quick Academic Hubs
                    </h5>
                </div>
                <div class="p-3">
                    <a href="{{ route('assignments.index') }}" class="lc-quick-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-2 bg-danger bg-opacity-10 text-danger">
                                <i class="fas fa-file-signature fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.88rem;">Assignment Centre</div>
                                <div class="text-muted small">Manage & approve assignments</div>
                            </div>
                        </div>
                        <span class="badge bg-danger rounded-pill px-2 py-1">{{ $totals['assignment_total'] }}</span>
                    </a>

                    <a href="{{ route('mini_projects.index') }}" class="lc-quick-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-2 bg-success bg-opacity-10 text-success">
                                <i class="fas fa-layer-group fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.88rem;">Mini Project Hub</div>
                                <div class="text-muted small">Manage & approve mini projects</div>
                            </div>
                        </div>
                        <span class="badge bg-success rounded-pill px-2 py-1">{{ $totals['mini_total'] }}</span>
                    </a>

                    <a href="{{ route('main_projects.index') }}" class="lc-quick-tile">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-2 bg-primary bg-opacity-10 text-primary">
                                <i class="fas fa-project-diagram fs-5"></i>
                            </div>
                            <div>
                                <div class="fw-bold" style="font-size: 0.88rem;">Main Project Hub</div>
                                <div class="text-muted small">Manage & approve capstones</div>
                            </div>
                        </div>
                        <span class="badge bg-primary rounded-pill px-2 py-1">{{ $totals['main_total'] }}</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

    {{-- CONDITIONAL: Course-Wise Breakdown (Only rendered when live courses exist) --}}
    @if(!empty($coursesData) && count($coursesData) > 0)
        <div class="mb-4">
            <h4 class="fw-bold mb-3 text-dark">
                <i class="fas fa-graduation-cap text-primary me-2"></i> Course-Wise Academic Distribution
            </h4>

            @foreach($coursesData as $data)
                @php
                    $pendingCount = $data['assignment_pending'] + $data['mini_pending'] + $data['main_pending'];
                @endphp
                <div class="card shadow-sm border mb-4 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="fas fa-book-open me-2 text-primary"></i>{{ $data['course']->title }}
                        </h5>
                        @if($pendingCount > 0)
                            <span class="badge bg-warning text-dark border px-3 py-1 rounded-pill">
                                <i class="fas fa-clock me-1"></i> {{ $pendingCount }} Waiting Approval
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i> All Approved
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <!-- Assignments -->
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 bg-light h-100 shadow-sm position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-muted mb-0 text-uppercase" style="font-size: 0.8rem;">Assignments</h6>
                                        <a href="{{ route('assignments.index', ['course_id' => $data['course']->id]) }}" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" title="Manage Assignments">
                                            Manage <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <div>
                                            <h3 class="mb-0 fw-bold">{{ $data['assignment_count'] }}</h3>
                                            <small class="text-muted">Total</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-success">{{ $data['assignment_active'] }}</h3>
                                            <small class="text-muted">Activated</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-warning" style="color: #e65c00 !important;">{{ $data['assignment_pending'] }}</h3>
                                            <small class="text-muted">Waiting Approval</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mini Projects -->
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 bg-light h-100 shadow-sm position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-muted mb-0 text-uppercase" style="font-size: 0.8rem;">Mini Projects</h6>
                                        <a href="{{ route('mini_projects.index', ['course_id' => $data['course']->id]) }}" class="btn btn-sm btn-outline-success py-0 px-2" style="font-size: 0.75rem;" title="Manage Mini Projects">
                                            Manage <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <div>
                                            <h3 class="mb-0 fw-bold">{{ $data['mini_count'] }}</h3>
                                            <small class="text-muted">Total</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-success">{{ $data['mini_active'] }}</h3>
                                            <small class="text-muted">Activated</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-warning" style="color: #ffe066 !important;">{{ $data['mini_pending'] }}</h3>
                                            <small class="text-muted">Waiting Approval</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Projects -->
                            <div class="col-md-4">
                                <div class="border rounded-3 p-3 bg-light h-100 shadow-sm position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-muted mb-0 text-uppercase" style="font-size: 0.8rem;">Main Projects</h6>
                                        <a href="{{ route('main_projects.index', ['course_id' => $data['course']->id]) }}" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" title="Manage Main Projects">
                                            Manage <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                    <div class="d-flex justify-content-around align-items-center">
                                        <div>
                                            <h3 class="mb-0 fw-bold">{{ $data['main_count'] }}</h3>
                                            <small class="text-muted">Total</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-success">{{ $data['main_active'] }}</h3>
                                            <small class="text-muted">Activated</small>
                                        </div>
                                        <div>
                                            <h3 class="mb-0 fw-bold text-warning">{{ $data['main_pending'] }}</h3>
                                            <small class="text-muted">Waiting Approval</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var totalActive = {{ $totals['total_active'] ?? 0 }};
    var totalPending = {{ $totals['total_pending'] ?? 0 }};
    var totalItems = totalActive + totalPending;

    var chartData = totalItems > 0 ? [totalActive, totalPending] : [1, 0];
    var chartColors = totalItems > 0 ? ['#10b981', '#f59e0b'] : ['#e2e8f0', '#edf2f7'];
    var chartLabels = totalItems > 0 ? ['Active & Approved', 'Waiting LC Approval'] : ['No Academic Records', ''];

    var canvas = document.getElementById('lc-approval-donut');
    if (canvas) {
        new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: totalItems > 0,
                        callbacks: {
                            label: function(ctx) {
                                var val = ctx.raw;
                                var pct = totalItems > 0 ? Math.round((val / totalItems) * 100) : 0;
                                return ' ' + ctx.label + ': ' + val + ' (' + pct + '%)';
                            }
                        }
                    }
                },
                animation: { animateRotate: true, duration: 800 }
            }
        });
    }

    // Progress bar animation
    document.querySelectorAll('.lc-prog-fill').forEach(function(el) {
        var w = el.style.width;
        el.style.width = '0';
        setTimeout(function(){ el.style.width = w; }, 150);
    });
});
</script>
@endpush
