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
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h3 class="section-title mb-0">
                <i class="fas fa-bolt" style="color: var(--accent-orange)"></i> Today's Activity
            </h3>
            <span class="badge bg-light text-muted border rounded-pill px-3 py-1 small">
                {{ $todayActivities->count() }} Activity Record{{ $todayActivities->count() === 1 ? '' : 's' }}
            </span>
        </div>
        
        <div class="activity-tabs mb-3">
            <div class="activity-tab active" data-filter="all">
                <i class="fas fa-list-ul" style="color: #6366f1;"></i> All <span class="badge rounded-pill ms-1">{{ $activityCounts['all'] ?? 0 }}</span>
            </div>
            <div class="activity-tab" data-filter="assignment">
                <i class="fas fa-file-alt" style="color: #6366f1;"></i> Assignments <span class="badge rounded-pill ms-1">{{ $activityCounts['assignment'] ?? 0 }}</span>
            </div>
            <div class="activity-tab" data-filter="mini_project">
                <i class="fas fa-layer-group" style="color: #ec4899;"></i> Mini Projects <span class="badge rounded-pill ms-1">{{ $activityCounts['mini_project'] ?? 0 }}</span>
            </div>
            <div class="activity-tab" data-filter="main_project">
                <i class="fas fa-project-diagram" style="color: #10b981;"></i> Main Projects <span class="badge rounded-pill ms-1">{{ $activityCounts['main_project'] ?? 0 }}</span>
            </div>
            <div class="activity-tab" data-filter="schedule">
                <i class="fas fa-calendar-alt" style="color: #f59e0b;"></i> Class Schedules <span class="badge rounded-pill ms-1">{{ $activityCounts['schedule'] ?? 0 }}</span>
            </div>
            <div class="activity-tab" data-filter="meeting">
                <i class="fas fa-video" style="color: #8b5cf6;"></i> Meetings <span class="badge rounded-pill ms-1">{{ $activityCounts['meeting'] ?? 0 }}</span>
            </div>
        </div>

        <div class="list-wrapper" id="activitiesList">
            @forelse($todayActivities as $activity)
            <div class="list-item-row activity-item-row flex-wrap flex-sm-nowrap align-items-center" 
                 data-type="{{ $activity['type'] }}" 
                 style="background: #f8fafc; border: none; border-bottom: 1px solid #f1f5f9; border-radius: 0; padding: 1rem 1.25rem;">
                
                <div class="d-flex align-items-start w-100 w-sm-auto mb-2 mb-sm-0 flex-grow-1 gap-3">
                    <div class="avatar-tech mt-1" style="background: {{ $activity['color'] }}; color: white; width: 42px; height: 42px; font-weight: 700; flex-shrink: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        {{ $activity['initials'] }}
                    </div>
                    <div class="item-info mb-0 flex-grow-1">
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                            <span class="item-name fw-bold text-dark" style="font-size: 0.95rem;">{{ $activity['name'] }}</span>
                            <span class="badge rounded-pill px-2 py-1" style="background: {{ $activity['type_color'] }}18; color: {{ $activity['type_color'] }}; border: 1px solid {{ $activity['type_color'] }}35; font-size: 0.7rem; font-weight: 600;">
                                <i class="{{ $activity['type_icon'] }} me-1"></i>{{ $activity['type_label'] }}
                            </span>
                        </div>
                        <div class="item-meta fw-medium" style="color: #334155; font-size: 0.84rem;">
                            {{ $activity['action'] }}
                        </div>
                        <div class="text-muted small mt-1 d-flex align-items-center flex-wrap gap-3" style="font-size: 0.76rem;">
                            @if(!empty($activity['course']))
                                <span><i class="fas fa-book-open me-1 opacity-75"></i> {{ $activity['course'] }}</span>
                            @endif
                            @if(!empty($activity['date']) || !empty($activity['time']))
                                <span><i class="far fa-clock me-1 opacity-75"></i> {{ $activity['date'] }} {{ $activity['time'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="batch-meta d-flex flex-column align-items-sm-end text-sm-end mt-2 mt-sm-0 ms-sm-3 flex-shrink-0">
                    <div class="batch-id fw-bold text-primary mb-1">{{ $activity['batch'] }}</div>
                    <div class="batch-type text-muted small mb-1">{{ $activity['batch_type'] }}</div>
                    @if(!empty($activity['link']) && !empty($activity['link_text']))
                        <a href="{{ $activity['link'] }}" 
                           target="{{ str_contains($activity['link'], 'http') ? '_blank' : '_self' }}" 
                           class="btn btn-sm rounded-pill px-3 shadow-sm" 
                           style="background: {{ $activity['type_color'] }}; color: white; font-size: 0.74rem; font-weight: 600; text-decoration: none;">
                            <i class="{{ str_contains($activity['link'], 'http') ? 'fas fa-video' : 'fas fa-arrow-right' }} me-1"></i> {{ $activity['link_text'] }}
                        </a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-calendar-check fa-3x mb-3 text-secondary opacity-50"></i>
                <div class="fw-bold text-dark fs-6 mb-1">No activities found for today</div>
                <p class="small text-muted mb-0">Student assignment submissions, mini & main projects, scheduled live classes, and doubt/demo meetings will automatically stream here.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.activity-tab');
        const items = document.querySelectorAll('.activity-item-row');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.getAttribute('data-filter');

                items.forEach(item => {
                    if (filter === 'all' || item.getAttribute('data-type') === filter) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection
