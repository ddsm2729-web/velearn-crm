@extends('layouts.master')

@section('pageTitle', 'Technical Head Dashboard')



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
    <div class="list-container-card">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h3 class="section-title mb-0">
                <i class="fas fa-bolt" style="color: var(--accent-orange)"></i> Today's Activity
            </h3>
            <span style="font-size:0.75rem; color:var(--text-secondary); background:var(--bg-gray); padding:0.2rem 0.6rem; border-radius:12px; border:1px solid rgba(0,0,0,0.05);">Live - Real-time notifications</span>
        

</div>
        
        <div class="activity-tabs">
            <div class="activity-tab active">
                <i class="fas fa-users"></i> Staff Activities
            </div>
            <div class="activity-tab" style="background:var(--bg-gray);">
                <i class="fas fa-bell" style="color:var(--accent-red);"></i> Technical Head Notifications
            </div>
        </div>

        <div class="list-wrapper">
            @foreach($todayActivities as $activity)
            <div class="list-item-row flex-wrap flex-sm-nowrap" style="background: transparent; border: none; border-bottom: 1px solid var(--border-color); border-radius: 0; padding-left: 0; padding-right: 0;">
                <div class="d-flex align-items-center w-100 w-sm-auto mb-2 mb-sm-0 flex-grow-1">
                    <div class="avatar-tech" style="background: {{ $activity['color'] }}; color: white; width: 35px; height: 35px; font-size:0.9rem;">
                        {{ $activity['initials'] }}
                    </div>
                    <div class="item-info mb-0">
                        <div class="item-name" style="font-size:0.85rem;">
                            {{ $activity['name'] }} <span style="font-weight:normal; font-size:0.75rem; color:var(--text-secondary);">• {{ $activity['role'] }}</span>
                        </div>
                        <div class="item-meta" style="color: var(--text-primary); font-size: 0.85rem; font-weight:500;">
                            {!! str_replace('—', '<span style="color:var(--text-secondary); margin:0 4px;">—</span>', $activity['action']) !!}
                        </div>
                    </div>
                </div>
                <div class="batch-meta d-flex flex-row flex-sm-column align-items-center align-items-sm-end justify-content-between justify-content-sm-center" style="gap:0.5rem;">
                    <div class="activity-badge" style="background: {{ $activity['badge_color'] }}15; color: {{ $activity['badge_color'] }}; border-color: {{ $activity['badge_color'] }}30;">
                        <i class="fas fa-circle" style="font-size:0.4rem; margin-right:4px;"></i> {{ $activity['badge'] }}
                    </div>
                    <div class="activity-time">{{ $activity['time_ago'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
