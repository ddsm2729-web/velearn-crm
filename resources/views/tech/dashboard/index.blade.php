@extends('layouts.master')

@section('pageTitle', 'Technical Staff Dashboard')



@section('content')
<div class="tech-dashboard-wrapper">
    <!-- Header -->
    <div class="d-flex justify-content-between flex-wrap-header mb-4">
        <div class="dash-header">
            <h1>{{ auth()->user()->roles->first()->name ?? 'Technical Staff' }} <span>Dashboard</span></h1>
            <span class="date-text">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
        </div>
        <div class="filter-btn-group ms-auto">
            @include('partials.daterange_picker')
        </div>
    </div>

    <!-- Total Students Section -->
    <div class="mb-4">
        <h3 class="section-title">
            <i class="fas fa-users"></i> Total Students
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
                            {{ isset($totalStudents[$key]['batches']) ? 'across ' . $totalStudents[$key]['batches'] . ' batches' : $totalStudents[$key]['subtext'] }}
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
                            <span class="stat-card-title">{{ str_replace('_', ' ', str_replace('uiux', 'UIUX', $key)) }}</span>
                        </div>
                        <div class="stat-value-large">{{ $totalBatches[$key]['count'] }}</div>
                        <div class="stat-subtext">
                            {{ isset($totalBatches[$key]['subtext']) ? $totalBatches[$key]['subtext'] : 'Active batches' }}
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
                                <i class="fas fa-mobile-alt"></i> {{ $demo['phone'] }} &bull; {{ $demo['course'] }} &bull; {{ $demo['time'] }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-badge" style="background: #e0e7ff; color: #4338ca;">Demo Booked</div>
                            <a href="#" class="btn-connect">
                                <i class="fas fa-link"></i> Connect
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
                                <i class="fas fa-mobile-alt"></i> {{ $doubt['phone'] }} &bull; {{ $doubt['course'] }} &bull; {{ $doubt['time'] }}
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-badge" style="background: #f3e8ff; color: #7e22ce;">Doubt Booked</div>
                            <a href="#" class="btn-connect" style="border-color: #7e22ce; color: #7e22ce;">
                                <i class="fas fa-link"></i> Connect
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
        <h3 class="section-title mb-3">
            <i class="fas fa-bolt" style="color: var(--accent-orange)"></i> Today's Activity
        </h3>
        
        <div class="activity-tabs">
            <div class="activity-tab active">
                <i class="fas fa-bolt" style="color: var(--accent-orange)"></i> Full Stack
            </div>
            <div class="activity-tab">
                <i class="fas fa-palette" style="color: var(--accent-pink)"></i> UI/UX Design
            </div>
            <div class="activity-tab">
                <i class="fas fa-bullhorn" style="color: var(--accent-orange)"></i> Digital Mktg
            </div>
            <div class="activity-tab">
                <i class="fas fa-chart-bar" style="color: var(--accent-green)"></i> Data Science
            </div>
        </div>

        <div class="list-wrapper">
            @foreach($todayActivities as $activity)
            <div class="list-item-row flex-wrap flex-sm-nowrap" style="background: #f8fafc; border: none; border-bottom: 1px solid #f1f5f9; border-radius: 0;">
                <div class="d-flex align-items-center w-100 w-sm-auto mb-2 mb-sm-0 flex-grow-1">
                    <div class="avatar-tech" style="background: {{ $activity['color'] }}; color: white;">
                        {{ $activity['initials'] }}
                    </div>
                    <div class="item-info mb-0">
                        <div class="item-name">{{ $activity['name'] }}</div>
                        <div class="item-meta" style="color: #94a3b8; font-size: 0.8rem;">
                            {{ $activity['action'] }}
                        </div>
                    </div>
                </div>
                <div class="batch-meta">
                    <div class="batch-id">{{ $activity['batch'] }}</div>
                    <div class="batch-type">{{ $activity['batch_type'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
