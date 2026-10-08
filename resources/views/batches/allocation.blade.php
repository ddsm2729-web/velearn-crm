@extends('layouts.master')

@section('pageTitle', 'Batch Allocation')

@section('content')
<div class="container-fluid p-3 p-md-4 allocation-page">
    <!-- Header Section -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="fw-bold mb-0">Batch Allocation</h3>
                <p class="text-muted small">Manage student assignments to active and upcoming live batches.</p>
            </div>
            @if(auth()->user()->hasRole('SuperAdmin') || auth()->user()->can('Batch Create'))
            <a href="{{ route('batches.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Create New Batch
            </a>
            @endif
        </div>
    </div>

    @php
    $startedBatches = $batches->where('start_date', '<=', $today); $upcomingBatches=$batches->where('start_date', '>', $today);
        @endphp

        <!-- Started Batches Section -->
        <div class="section-container mb-5">
            <div class="d-flex align-items-center mb-4">
                <span class="badge bg-danger-soft text-danger me-2 px-3 py-1 rounded-pill">
                    <span class="pulse-dot me-1"></span> LIVE
                </span>
                <h4 class="fw-bold text-dark mb-0">Started Batches</h4>
                <span class="text-muted small ms-3">— allocation open within last 3 days</span>
            </div>

            <!-- Filter Tabs -->
            @if($courses->count() > 0)
                <div class="">
                    <ul class="nav nav-pills nav-justified filter-tabs mb-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2 px-4 fw-bold filter-btn filter-btn-started active" data-filter="all"><i class="fas fa-layer-group me-2"></i> All</button>
                        </li>
                        @foreach($courses as $course)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2 px-4 fw-bold filter-btn filter-btn-started" data-filter="course-{{ $course->id }}">
                                <i class="fas fa-laptop-code me-2"></i> {{ $course->title }}{{ $course->course_code ? ' (#' . $course->course_code . ')' : '' }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row" id="startedBatchesContainer">
                @forelse($startedBatches as $batch)
                <div class="col-12 col-md-6 col-xl-4 batch-item-started course-{{ $batch->course_id }}">
                    <div class="batch-card started shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="category-tag mb-2">{{ strtoupper($batch->course->title) }}</span>
                                <h5 class="batch-code text-dark mb-1">{{ $batch->batch_code }}</h5>
                                <p class="mentor-name text-muted small mb-0">Mentor: <span class="fw-bold text-primary">{{ $batch->instructor->name ?? 'Unassigned' }}</span></p>
                            </div>
                            <span class="live-indicator-pill px-2 py-1 rounded-pill small">
                                <i class="fas fa-circle me-1 small"></i> LIVE
                            </span>
                        </div>

                        <div class="card-body-custom mb-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="info-label">TYPE</label>
                                    <p class="info-value">Weekday</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">HOURS</label>
                                    <p class="info-value">{{ $batch->class_duration ?? '120h' }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">STARTS</label>
                                    <p class="info-value">{{ date('d M Y', strtotime($batch->start_date)) }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">LAST JOIN</label>
                                    <p class="info-value text-dark">{{ $batch->join_deadline ? date('d M Y', strtotime($batch->join_deadline)) : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer-custom d-flex align-items-center justify-content-between pt-3 flex-nowrap">
                            <div class="student-count me-2 text-nowrap">
                                <i class="fas fa-user-friends me-1 text-muted small"></i>
                                <span class="text-dark small">{{ $batch->joined_student_count ?? 0 }} student(s)</span>
                            </div>
                            <div class="action-buttons d-flex flex-nowrap">
                                <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-secondary btn-xs rounded-pill px-2 me-1">Overview</a>
                                <button class="btn btn-primary btn-xs rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#allocateModal" data-batch-id="{{ $batch->id }}" data-batch-info="{{ $batch->batch_code }} - {{ $batch->course->title }}">Allocate</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 1rem;">
                        <div class="text-muted mb-3"><i class="fas fa-box-open fa-3x opacity-50"></i></div>
                        <h5 class="fw-bold text-dark">No started batches found</h5>
                        <p class="text-muted small mb-0">There are no live batches available for allocation right now.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Batches Section -->
        <div class="section-container">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary-soft text-primary me-2 px-3 py-1 rounded-pill">UPCOMING</span>
                    <h4 class="fw-bold text-dark mb-0">Upcoming Batches</h4>
                </div>
            </div>

            <!-- Filter Tabs -->
            @if($courses->count() > 0)
                <div class="">
                    <ul class="nav nav-pills nav-justified filter-tabs mb-2" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2 px-4 fw-bold filter-btn filter-btn-upcoming active" data-filter="all"><i class="fas fa-layer-group me-2"></i> All</button>
                        </li>
                        @foreach($courses as $course)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill py-2 px-4 fw-bold filter-btn filter-btn-upcoming" data-filter="course-{{ $course->id }}">
                                <i class="fas fa-laptop-code me-2"></i> {{ $course->title }}{{ $course->course_code ? ' (#' . $course->course_code . ')' : '' }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4" id="upcomingBatchesContainer">
                @forelse($upcomingBatches as $batch)
                <div class="col-12 col-md-6 col-xl-4 batch-item-upcoming course-{{ $batch->course_id }}">
                    <div class="batch-card upcoming shadow-lg">
                        <div class="card-header-custom d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="category-tag mb-2">{{ strtoupper($batch->course->title) }}</span>
                                <h5 class="batch-code text-white mb-1">{{ $batch->batch_code }}</h5>
                                <p class="mentor-name text-muted small mb-0">Mentor: <span class="fw-bold text-primary">{{ $batch->instructor->name ?? 'Unassigned' }}</span></p>
                            </div>
                            <span class="upcoming-indicator-pill px-2 py-1 rounded-pill small">UPCOMING</span>
                        </div>

                        <div class="card-body-custom mb-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="info-label">TYPE</label>
                                    <p class="info-value">Weekday</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">HOURS</label>
                                    <p class="info-value text-dark">{{ $batch->class_duration ?? '120h' }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">STARTS</label>
                                    <p class="info-value">{{ date('d M Y', strtotime($batch->start_date)) }}</p>
                                </div>
                                <div class="col-6">
                                    <label class="info-label">LAST JOIN</label>
                                    <p class="info-value">{{ $batch->join_deadline ? date('d M Y', strtotime($batch->join_deadline)) : '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer-custom d-flex align-items-center justify-content-between pt-3 border-top-dashed flex-nowrap">
                            <div class="student-count me-2 text-nowrap">
                                <i class="fas fa-user-friends me-1 text-muted small"></i>
                                <span class="text-dark small">{{ $batch->joined_student_count ?? 0 }} student(s)</span>
                            </div>
                            <div class="action-buttons d-flex flex-nowrap">
                                <a href="{{ route('batches.show', $batch->id) }}" class="btn btn-outline-secondary btn-xs rounded-pill px-2 me-1">Overview</a>
                                <button class="btn btn-primary btn-xs rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#allocateModal" data-batch-id="{{ $batch->id }}" data-batch-info="{{ $batch->batch_code }} - {{ $batch->course->title }}">Allocate</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 1rem;">
                        <div class="text-muted mb-3"><i class="fas fa-calendar-times fa-3x opacity-50"></i></div>
                        <h5 class="fw-bold text-dark">No upcoming batches found</h5>
                        <p class="text-muted small mb-0">There are no upcoming batches scheduled for allocation yet.</p>
                    </div>
                </div>
                @endforelse
            </div>


        </div>
</div>

@include('batches.partials.allocate_modal')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/batches-allocation.css') }}">
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Started Batches Filter functionality
        const filterBtnsStarted = document.querySelectorAll('.filter-btn-started');
        const batchItemsStarted = document.querySelectorAll('.batch-item-started');

        filterBtnsStarted.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-filter');

                // Update active state
                filterBtnsStarted.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Filter items
                batchItemsStarted.forEach(item => {
                    if (filter === 'all' || item.classList.contains(filter)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Upcoming Batches Filter functionality
        const filterBtnsUpcoming = document.querySelectorAll('.filter-btn-upcoming');
        const batchItemsUpcoming = document.querySelectorAll('.batch-item-upcoming');

        filterBtnsUpcoming.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.getAttribute('data-filter');

                // Update active state
                filterBtnsUpcoming.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Filter items
                batchItemsUpcoming.forEach(item => {
                    if (filter === 'all' || item.classList.contains(filter)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Modal batch info handling is now in partial
    });

</script>
@endsection

