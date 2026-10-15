@extends('layouts.master')

@section('pageTitle', 'Technical Head Dashboard')

@push('css')
<style>
    .today-activity-card {
        background: var(--card-bg, #ffffff);
        border-radius: 12px;
        border: 1px solid var(--border-color, #e2e8f0);
        padding: 1.5rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }
    .today-activity-title {
        color: var(--text-primary, #1e293b);
        font-size: 1.15rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        margin: 0;
    }
    .live-notifications-badge {
        font-size: 0.72rem;
        color: var(--text-secondary, #64748b);
        background: #f1f5f9;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .live-pulse-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #10b981;
        display: inline-block;
        box-shadow: 0 0 6px rgba(16, 185, 129, 0.6);
    }
    .today-activity-nav {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .today-tab-btn {
        background: #f8fafc;
        color: var(--text-secondary, #64748b);
        border: 1px solid var(--border-color, #e2e8f0);
        padding: 7px 18px;
        border-radius: 24px;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        user-select: none;
    }
    .today-tab-btn:hover:not(.active) {
        background: #f1f5f9;
        color: var(--text-primary, #1e293b);
        border-color: #cbd5e1;
    }
    .today-tab-btn.active.staff-tab-btn {
        background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
    }
    .today-tab-btn.active.notif-tab-btn {
        background: linear-gradient(135deg, #ec4899 0%, #a855f7 100%) !important;
        color: #ffffff !important;
        border-color: transparent !important;
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
    }

    .today-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 13px 0;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }
    .today-item-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .today-item-row:first-child {
        padding-top: 4px;
    }
    .today-item-row:hover {
        background: #f8fafc;
    }

    .staff-avatar-round {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: white;
        flex-shrink: 0;
    }
    .notif-icon-squircle {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        color: white;
        flex-shrink: 0;
    }
    .today-item-text-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text-primary, #1e293b);
        line-height: 1.3;
    }
    .today-item-text-sub {
        font-size: 0.82rem;
        color: var(--text-secondary, #64748b);
        margin-top: 2px;
        line-height: 1.3;
    }
    .today-item-text-sub strong {
        color: #0f172a;
        font-weight: 600;
    }
    .today-role-sep {
        font-weight: 500;
        font-size: 0.78rem;
        color: #94a3b8;
        margin-left: 2px;
    }

    .pill-activity-badge {
        font-size: 0.74rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }
    .pill-category-badge {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .pill-status-badge {
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: 0.02em;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .pill-status-pending {
        background: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
    }
    .pill-status-done {
        background: #d1fae5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    .pill-status-info {
        background: #dbeafe;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .today-time-ago {
        font-size: 0.76rem;
        color: #94a3b8;
        white-space: nowrap;
        min-width: 65px;
        text-align: right;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-end mb-3">
        @include('partials.daterange_picker')
    </div>

<div class="tech-dashboard-wrapper">
    <!-- Header -->
    <div class="d-flex flex-wrap-header mb-4">
        <div class="dash-header">
            <h1>{{ auth()->user()->roles->first()->name ?? 'Tech Head' }} <span>Dashboard</span></h1>
        </div>
        <div class="header-actions">
            <div class="search-bar-dash">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search batches, students, staff">
            </div>
            <button class="btn-quick-action">
                <i class="fas fa-bolt"></i> Quick Action
            </button>
        </div>
    </div>

    <!-- Total Students Section -->
    <div class="mb-4">
        <h3 class="section-title">
            <i class="fas fa-users"></i> Total Students 
            <span class="section-subtitle">Today, {{ \Carbon\Carbon::now()->format('d M Y') }}</span>
        </h3>
        <div class="row g-3">
            @foreach(['full_stack', 'ui_ux', 'digital_mktg', 'data_science', 'total_demos'] as $key)
                <div class="col-6 col-md-4 col-xl">
                    <div class="stat-card-tech">
                        <div class="stat-accent-top" style="background: linear-gradient(90deg, {{ $totalStudents[$key]['color'] }} 0%, transparent 100%);"></div>
                        <div class="stat-icon-title">
                            <i class="{{ $totalStudents[$key]['icon'] }}" style="color: {{ $totalStudents[$key]['color'] }}"></i>
                            <span class="stat-card-title">{{ str_replace('_', ' ', $key) }}</span>
                        </div>
                        <div class="stat-value-large">{{ $totalStudents[$key]['count'] }}</div>
                        <div class="stat-subtext">
                            {{ $totalStudents[$key]['subtext'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Total Batches & Sessions Section -->
    <div class="mb-4">
        <h3 class="section-title">
            <i class="fas fa-folder-open"></i> Total Batches & Sessions
        </h3>
        <div class="row g-3">
            @foreach(['fs_batches', 'uiux_batches', 'dm_batches', 'ds_batches', 'total_doubts'] as $key)
                <div class="col-6 col-md-4 col-xl">
                    <div class="stat-card-tech">
                        <div class="stat-accent-top" style="background: linear-gradient(90deg, {{ $totalBatches[$key]['color'] }} 0%, transparent 100%);"></div>
                        <div class="stat-icon-title">
                            @if(isset($totalBatches[$key]['icon']))
                                <i class="{{ $totalBatches[$key]['icon'] }}" style="color: {{ $totalBatches[$key]['color'] }}"></i>
                            @endif
                            <span class="stat-card-title">{{ str_replace('_', ' ', str_replace('uiux', 'UX', str_replace('dm', 'DM', str_replace('fs', 'FS', str_replace('ds', 'DS', $key))))) }}</span>
                        </div>
                        <div class="stat-value-large">{{ $totalBatches[$key]['count'] }}</div>
                        <div class="stat-subtext">
                            {{ $totalBatches[$key]['subtext'] }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Demos and Doubts Section -->
    <div class="row g-4 mb-4">
        <!-- Today's Demo -->
        <div class="col-lg-6">
            <div class="list-container-card">
                <h3 class="section-title">
                    <i class="fas fa-bullseye" style="color: var(--accent-red)"></i> Today's Demo
                    <span class="badge-count-title">{{ count($todayDemos) }}</span>
                </h3>
                <div class="list-wrapper">
                    @foreach($todayDemos as $demo)
                    <div class="list-item-row flex-wrap flex-sm-nowrap">
                        <div class="avatar-tech" style="background: {{ $demo['color'] }}; color: white;">
                            {{ $demo['initials'] }}
                        </div>
                        <div class="item-info">
                            <div class="item-name">{{ $demo['name'] }}</div>
                            <div class="item-meta">
                                <i class="fas fa-phone-alt"></i> {{ $demo['phone'] }} &bull; {{ $demo['course'] }} &bull; <i class="far fa-clock"></i> {{ $demo['time'] }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-badge" style="background: transparent; border: 1px solid rgba(0,0,0,0.1); color: var(--text-secondary);">
                                <i class="fas fa-circle" style="font-size:0.5rem; margin-right:4px; color:var(--accent-red);"></i>Demo Booked
                            </div>
                            <a href="#" class="btn-connect" style="border-color: rgba(0,0,0,0.1); color: var(--text-primary);">
                                <i class="fas fa-phone-alt" style="color:var(--accent-red);"></i> Connect
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Today's Doubt -->
        <div class="col-lg-6">
            <div class="list-container-card">
                <h3 class="section-title">
                    <i class="fas fa-comment-dots" style="color: var(--accent-purple)"></i> Today's Doubt
                    <span class="badge-count-title">{{ count($todayDoubts) }}</span>
                </h3>
                <div class="list-wrapper">
                    @foreach($todayDoubts as $doubt)
                    <div class="list-item-row flex-wrap flex-sm-nowrap">
                        <div class="avatar-tech" style="background: {{ $doubt['color'] }}; color: white;">
                            {{ $doubt['initials'] }}
                        </div>
                        <div class="item-info">
                            <div class="item-name">{{ $doubt['name'] }}</div>
                            <div class="item-meta">
                                <i class="fas fa-phone-alt"></i> {{ $doubt['phone'] }} &bull; {{ $doubt['course'] }} &bull; <i class="far fa-clock"></i> {{ $doubt['time'] }}
                            </div>
                            <div class="item-submeta">
                                {{ $doubt['topic'] ?? '' }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-badge" style="background: transparent; border: 1px solid rgba(0,0,0,0.1); color: var(--text-secondary);">
                                <i class="fas fa-circle" style="font-size:0.5rem; margin-right:4px; color:var(--accent-purple);"></i>Doubt Booked
                            </div>
                            <a href="#" class="btn-connect" style="border-color: rgba(0,0,0,0.1); color: var(--text-primary);">
                                <i class="fas fa-phone-alt" style="color:var(--accent-red);"></i> Connect
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Activity Section -->
    <div class="today-activity-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h3 class="today-activity-title">
                <span style="color: #f59e0b; margin-right: 8px;">⚡</span> Today's Activity
            </h3>
            <span class="live-notifications-badge">
                <span class="live-pulse-dot"></span> Live · Real-time notifications
            </span>
        </div>
        
        <div class="today-activity-nav">
            <button type="button" class="today-tab-btn active staff-tab-btn" id="btnTabStaff" onclick="switchTodayActivityTab('staff')">
                <span>🧑‍💼</span> Staff Activities
            </button>
            <button type="button" class="today-tab-btn notif-tab-btn" id="btnTabNotif" onclick="switchTodayActivityTab('notifications')">
                <span>🎯</span> Technical Head Notifications
            </button>
        </div>

        <!-- Panel 1: Staff Activities -->
        <div id="panelStaffActivities">
            @forelse($staffActivities as $act)
            <div class="today-item-row flex-wrap flex-sm-nowrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="staff-avatar-round" style="background: {{ $act['color'] }};">
                        {{ $act['initials'] }}
                    </div>
                    <div>
                        <div class="today-item-text-title">
                            {{ $act['name'] }} <span class="today-role-sep">· {{ $act['role'] }}</span>
                        </div>
                        <div class="today-item-text-sub">
                            {{ $act['action_prefix'] }} <strong>{{ $act['action_highlight'] }}</strong>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-auto ms-sm-0">
                    <span class="pill-activity-badge" style="background: {{ $act['badge_bg'] }}; color: {{ $act['badge_color'] }}; border: 1px solid {{ $act['badge_color'] }}40;">
                        <span>{{ $act['badge_icon'] }}</span> {{ $act['badge_text'] }}
                    </span>
                    <span class="today-time-ago">{{ $act['time_ago'] }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-calendar-check fa-2x mb-2 text-secondary opacity-50"></i>
                <div class="fw-semibold text-secondary">No staff activities recorded yet</div>
            </div>
            @endforelse
        </div>

        <!-- Panel 2: Technical Head Notifications -->
        <div id="panelTechHeadNotifications" style="display: none;">
            @forelse($techHeadNotifications as $notif)
            <div class="today-item-row flex-wrap flex-sm-nowrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="notif-icon-squircle" style="background: {{ $notif['icon_bg'] }};">
                        <i class="{{ $notif['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="today-item-text-title">
                            {{ $notif['title'] }}
                        </div>
                        <div class="today-item-text-sub" style="color: var(--text-secondary, #64748b);">
                            {{ $notif['subtitle'] }}
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto ms-sm-0">
                    <span class="pill-category-badge" style="color: {{ $notif['category_color'] ?? '#64748b' }}; border-color: {{ ($notif['category_color'] ?? '#64748b') }}30; background: #f8fafc;">
                        {{ $notif['category'] }}
                    </span>
                    @if($notif['status'] == 'PENDING')
                        <span class="pill-status-badge pill-status-pending">
                            <i class="fas fa-hourglass-half" style="font-size: 0.65rem;"></i> PENDING
                        </span>
                    @elseif($notif['status'] == 'DONE')
                        <span class="pill-status-badge pill-status-done">
                            <i class="fas fa-check" style="font-size: 0.65rem;"></i> DONE
                        </span>
                    @else
                        <span class="pill-status-badge pill-status-info">
                            <i class="fas fa-info-circle" style="font-size: 0.65rem;"></i> INFO
                        </span>
                    @endif
                    <span class="today-time-ago ms-1">{{ $notif['time_ago'] }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-bell-slash fa-2x mb-2 text-secondary opacity-50"></i>
                <div class="fw-semibold text-secondary">No system notifications found</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function switchTodayActivityTab(tabType) {
        const btnStaff = document.getElementById('btnTabStaff');
        const btnNotif = document.getElementById('btnTabNotif');
        const panelStaff = document.getElementById('panelStaffActivities');
        const panelNotif = document.getElementById('panelTechHeadNotifications');

        if (tabType === 'staff') {
            btnStaff.classList.add('active');
            btnNotif.classList.remove('active');
            panelStaff.style.display = 'block';
            panelNotif.style.display = 'none';
        } else {
            btnNotif.classList.add('active');
            btnStaff.classList.remove('active');
            panelStaff.style.display = 'none';
            panelNotif.style.display = 'block';
        }
    }
</script>
@endpush
