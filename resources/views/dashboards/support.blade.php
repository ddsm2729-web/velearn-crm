@extends('layouts.master')

@section('pageTitle', 'Support Executive Dashboard')

@push('css')
@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dashboards-support_executive.css') }}">
@endpush
@endpush

@section('content')
<div class="support-dashboard-wrapper">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="dash-header">
            <h1>Dashboard</h1>
            <span>Support CRM &mdash; Today's overview</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            @include('partials.daterange_picker')
            <div class="position-relative">
                <i class="fas fa-search position-absolute top-50 translate-middle-y ms-3" style="color: var(--text-muted)"></i>
                <input type="text" class="search-box ps-5" placeholder="Search leads, students...">
            </div>
            <button class="btn btn-add-lead">
                <i class="fas fa-plus me-2"></i> Add Lead
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-3 mb-4">
        <!-- Technical Queries -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-accent-bar" style="background: var(--blue-accent)"></div>
                <div class="icon-container" style="background: rgba(37, 99, 235, 0.1); color: var(--blue-accent)">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Technical Today</div>
                    <div class="stat-value">{{ $stats['technical']['count'] }}</div>
                    <div class="stat-trend {{ $stats['technical']['trend'] == 'up' ? 'trend-positive' : 'trend-negative' }}">
                        <i class="fas fa-caret-{{ $stats['technical']['trend'] == 'up' ? 'up' : 'down' }}"></i> {{ $stats['technical']['diff'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Non-Technical Queries -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-accent-bar" style="background: var(--purple-accent)"></div>
                <div class="icon-container" style="background: rgba(124, 58, 237, 0.1); color: var(--purple-accent)">
                    <i class="fas fa-pen"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Non-Tech Today</div>
                    <div class="stat-value">{{ $stats['non_technical']['count'] }}</div>
                    <div class="stat-trend {{ $stats['non_technical']['trend'] == 'up' ? 'trend-positive' : 'trend-negative' }}">
                        <i class="fas fa-caret-{{ $stats['non_technical']['trend'] == 'up' ? 'up' : 'down' }}"></i> {{ $stats['non_technical']['diff'] }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Queries -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-accent-bar" style="background: var(--yellow-accent)"></div>
                <div class="icon-container" style="background: rgba(217, 119, 6, 0.1); color: var(--yellow-accent)">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Pending Total</div>
                    <div class="stat-value">{{ $stats['pending']['count'] }}</div>
                    <div class="stat-trend trend-negative" style="font-size: 0.65rem">
                        {{ $stats['pending']['overdue'] }} overdue
                    </div>
                </div>
            </div>
        </div>

        <!-- Solved Today -->
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-accent-bar" style="background: var(--green-accent)"></div>
                <div class="icon-container" style="background: rgba(5, 150, 105, 0.1); color: var(--green-accent)">
                    <i class="fas fa-check"></i>
                </div>
                <div class="stat-details">
                    <div class="stat-label">Solved Today</div>
                    <div class="stat-value">{{ $stats['solved']['count'] }}</div>
                    <div class="stat-trend trend-positive">
                        <i class="fas fa-chart-line"></i> {{ $stats['solved']['status'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lists Row -->
    <div class="row g-4">
        <!-- Technical List -->
        <div class="col-md-6">
            <div class="query-list-container">
                <div class="list-title">
                    <h4>Today &mdash; Technical query raisers</h4>
                    <span class="badge-count">{{ count($technicalQueries) }} today</span>
                </div>
                <div class="scrollable-list">
                    @forelse($technicalQueries as $query)
                    <div class="query-item">
                        <div class="avatar-circle" style="background: {{ $query['color'] }}15; color: {{ $query['color'] }}; border: 1px solid {{ $query['color'] }}30;">
                            {{ $query['initials'] }}
                        </div>
                        <div class="query-info">
                            <div class="name">{{ $query['name'] }}</div>
                            <div class="topic">{{ $query['topic'] }}</div>
                        </div>
                        <div class="query-time">{{ $query['time'] }}</div>
                    </div>
                    @empty
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2" style="opacity: 0.5;"></i>
                        <p class="mb-0">No technical queries raised today.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Non-Technical List -->
        <div class="col-md-6">
            <div class="query-list-container">
                <div class="list-title">
                    <h4>Today &mdash; Non-technical raisers</h4>
                    <span class="badge-count">{{ count($nonTechnicalQueries) }} today</span>
                </div>
                <div class="scrollable-list">
                    @forelse($nonTechnicalQueries as $query)
                    <div class="query-item">
                        <div class="avatar-circle" style="background: {{ $query['color'] }}15; color: {{ $query['color'] }}; border: 1px solid {{ $query['color'] }}30;">
                            {{ $query['initials'] }}
                        </div>
                        <div class="query-info">
                            <div class="name">{{ $query['name'] }}</div>
                            <div class="topic">{{ $query['topic'] }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center p-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2" style="opacity: 0.5;"></i>
                        <p class="mb-0">No non-technical queries raised today.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
