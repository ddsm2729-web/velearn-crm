@extends('layouts.master')

@section('pageTitle', 'Batch System')

@section('content')
<div class="container-fluid p-3 p-md-4">
    <!-- Header Section -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h3 class="fw-bold mb-0">Batch System</h3>
                <p class="text-muted small">View course batches — started and upcoming</p>
            </div>
            @can('Batch Create')
            <a href="{{ route('batches.create') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="fas fa-plus me-2"></i> Create New Batch
            </a>
            @endcan
        </div>
    </div>

    <!-- Course Tabs Navigation -->
    @if($courses->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem; background: #f8f9fa;">
        <div class="card-body p-2">
            <ul class="nav nav-pills nav-justified" id="courseTabs" role="tablist">
                @foreach($courses as $index => $course)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $index == 0 ? 'active' : '' }} rounded-pill py-2 px-4 fw-bold" 
                        id="tab-{{ $course->id }}" data-bs-toggle="pill" 
                        data-bs-target="#course-{{ $course->id }}" type="button" role="tab">
                        <i class="fas fa-laptop-code me-2"></i> {{ $course->title }} {{ $course->course_code ? ' (#' . $course->course_code . ')' : '' }}
                    </button>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Tabs Content -->
    <div class="tab-content" id="courseTabsContent">
        @foreach($courses as $index => $course)
        <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="course-{{ $course->id }}" role="tabpanel">
            
            <h4 class="fw-bold mb-4 mt-2">
                <i class="fas fa-desktop text-primary me-2"></i> {{ $course->title }} {{ $course->course_code ? ' (#' . $course->course_code . ')' : '' }}
            </h4>

            @php
                $courseBatches = $batches->get($course->id, collect());
                $startedBatches = $courseBatches->where('start_date', '<=', $today);
                $upcomingBatches = $courseBatches->where('start_date', '>', $today);
            @endphp

            <!-- STARTED BATCHES SECTION -->
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <div class="status-indicator bg-success me-2"></div>
                    <h6 class="fw-bold text-success mb-0 text-uppercase letter-spacing-1">Started Batches</h6>
                    <span class="text-muted small ms-3">Currently running — open until join deadline</span>
                </div>

                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-batch-table">
                            <thead>
                                <tr>
                                    <th>Batch Code</th>
                                    <th>Course Name</th>
                                    <th>Mentor</th>
                                    <th>Start Date</th>
                                    <th>Join Deadline</th>
                                    <th>Joined</th>
                                    <th>Required</th>
                                    <th>Status</th>
                                    @if(auth()->user()->can('Batch Edit') || auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                    <th class="text-end pe-4">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($startedBatches as $batch)
                                <tr>
                                    <td class="fw-bold text-primary">
                                        {{ $batch->batch_code }}
                                        <div class="mt-1" style="font-size: 0.7rem;">
                                            <span class="badge bg-light text-secondary border fw-bold">{{ $batch->batch_track ?? 'Normal' }}</span>
                                            <span class="badge bg-light text-secondary border fw-bold">{{ $batch->batch_type }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $batch->course->title }} {{ $batch->course->course_code ? ' (#' . $batch->course->course_code . ')' : '' }}</td>
                                    <td>{{ $batch->instructor->name ?? 'Unassigned' }}</td>
                                    <td class="text-nowrap">
                                        <div class="fw-bold">{{ date('d M Y', strtotime($batch->start_date)) }}</div>
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            <i class="far fa-clock me-1"></i> 
                                            {{ date('h:i A', strtotime($batch->batch_from_time)) }} - {{ date('h:i A', strtotime($batch->batch_to_time)) }}
                                        </div>
                                    </td>
                                    <td class="fw-bold text-warning text-nowrap">{{ $batch->join_deadline ? date('d M Y', strtotime($batch->join_deadline)) : '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span>{{ $batch->joined_student_count }}</span>
                                            <div class="progress flex-grow-1" style="height: 4px; min-width: 60px;">
                                                @php $percent = ($batch->joined_student_count / ($batch->max_students ?: 1)) * 100; @endphp
                                                <div class="progress-bar bg-warning" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $batch->max_students }}</td>
                                    <td>
                                        <div class="d-flex align-items-center bg-white rounded-pill p-1 pe-3 border shadow-sm" style="width: max-content; transition: all 0.2s;">
                                            <div class="form-check form-switch mb-0 ms-2">
                                                <input class="form-check-input status-toggle mt-1" type="checkbox" role="switch" 
                                                    data-batch-id="{{ $batch->id }}" 
                                                    {{ $batch->batch_status === 'Completed' ? 'checked' : '' }}
                                                    style="cursor: pointer; transform: scale(1.1);">
                                            </div>
                                            <span class="ms-2 fw-bolder status-label" id="status-label-{{ $batch->id }}" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                @if($batch->batch_status === 'Completed')
                                                    <span class="text-success">COMPLETED</span>
                                                @else
                                                    <span class="text-primary">RUNNING</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    @if(auth()->user()->can('Batch Edit') || auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            @if(auth()->user()->can('Batch Edit') || auth()->user()->hasRole('Technical Head'))
                                            <a href="{{ route('batches.edit', $batch) }}" class="btn btn-light btn-sm px-3"><i class="fas fa-edit text-info"></i></a>
                                            @endif
                                            @if(auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                            <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm px-3" onclick="return confirm('Delete this batch?')"><i class="fas fa-trash text-danger"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted small italic">No started batches for this course.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- UPCOMING BATCHES SECTION -->
            <div class="mb-5">
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-hourglass-start text-primary me-2"></i>
                    <h6 class="fw-bold text-primary mb-0 text-uppercase letter-spacing-1">Upcoming Batches</h6>
                    <span class="text-muted small ms-3">Not yet started — enrollments open</span>
                </div>

                <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 1rem;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-batch-table">
                            <thead>
                                <tr>
                                    <th>Batch Code</th>
                                    <th>Course Name</th>
                                    <th>Mentor</th>
                                    <th>Start Date</th>
                                    <th>Join Deadline</th>
                                    <th>Joined</th>
                                    <th>Required</th>
                                    <th>Status</th>
                                    @if(auth()->user()->can('Batch Edit') || auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                    <th class="text-end pe-4">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcomingBatches as $batch)
                                <tr>
                                    <td class="fw-bold text-primary">
                                        {{ $batch->batch_code }}
                                        <div class="mt-1" style="font-size: 0.7rem;">
                                            <span class="badge bg-light text-secondary border fw-bold">{{ $batch->batch_track ?? 'Normal' }}</span>
                                            <span class="badge bg-light text-secondary border fw-bold">{{ $batch->batch_type }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $batch->course->title }} {{ $batch->course->course_code ? ' (#' . $batch->course->course_code . ')' : '' }}</td>
                                    <td>{{ $batch->instructor->name ?? 'Unassigned' }}</td>
                                    <td class="text-nowrap">
                                        <div class="fw-bold">{{ date('d M Y', strtotime($batch->start_date)) }}</div>
                                        <div class="small text-muted mt-1" style="font-size: 0.75rem;">
                                            <i class="far fa-clock me-1"></i> 
                                            {{ date('h:i A', strtotime($batch->batch_from_time)) }} - {{ date('h:i A', strtotime($batch->batch_to_time)) }}
                                        </div>
                                    </td>
                                    <td class="fw-bold text-warning text-nowrap">{{ $batch->join_deadline ? date('d M Y', strtotime($batch->join_deadline)) : '-' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span>{{ $batch->joined_student_count }}</span>
                                            <div class="progress flex-grow-1" style="height: 4px; min-width: 60px;">
                                                @php $percent = ($batch->joined_student_count / ($batch->max_students ?: 1)) * 100; @endphp
                                                <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $batch->max_students }}</td>
                                    <td>
                                        <div class="d-flex align-items-center bg-white rounded-pill p-1 pe-3 border shadow-sm" style="width: max-content; transition: all 0.2s;">
                                            <div class="form-check form-switch mb-0 ms-2">
                                                <input class="form-check-input status-toggle mt-1" type="checkbox" role="switch" 
                                                    data-batch-id="{{ $batch->id }}" 
                                                    {{ $batch->batch_status === 'Completed' ? 'checked' : '' }}
                                                    style="cursor: pointer; transform: scale(1.1);">
                                            </div>
                                            <span class="ms-2 fw-bolder status-label" id="status-label-{{ $batch->id }}" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                @if($batch->batch_status === 'Completed')
                                                    <span class="text-success">COMPLETED</span>
                                                @else
                                                    <span class="text-primary">RUNNING</span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    @if(auth()->user()->can('Batch Edit') || auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                    <td class="text-end pe-4">
                                        <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                                            @if(auth()->user()->can('Batch Edit') || auth()->user()->hasRole('Technical Head'))
                                            <a href="{{ route('batches.edit', $batch) }}" class="btn btn-light btn-sm px-3"><i class="fas fa-edit text-info"></i></a>
                                            @endif
                                            @if(auth()->user()->can('Batch Delete') || auth()->user()->hasRole('Technical Head'))
                                            <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm px-3" onclick="return confirm('Delete this batch?')"><i class="fas fa-trash text-danger"></i></button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted small italic">No upcoming batches for this course.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @else
    <div class="card border-0 shadow-lg py-5 mt-5" style="border-radius: 2rem;">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <div class="display-1 text-secondary opacity-50 mb-3">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 class="fw-bold text-dark">No Active Courses Found</h3>
                <p class="text-muted mx-auto" style="max-width: 400px;">
                    It looks like there are no active courses in the system. Please ensure courses are added and set to "Active" status.
                </p>
            </div>
            @can('Course Create')
            <div>
                <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm d-inline-block">
                    Go to Course Management
                </a>
            </div>
            @endcan
        </div>
    </div>
    @endif
</div>

<!-- Status Confirmation Modal -->
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-3">Update Batch Status?</h5>
                <p class="text-muted mb-4">Are you sure you want to change the status of this batch?</p>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" id="confirmStatusUpdateBtn">Yes, Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/batches-index.css') }}">
    <style>
        .form-check-input:checked {
            background-color: #10b981;
            border-color: #10b981;
        }
    </style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentBatchId = null;
        let currentToggleInput = null;
        const confirmModal = new bootstrap.Modal(document.getElementById('statusConfirmModal'));

        document.querySelectorAll('.status-toggle').forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault(); // Prevent UI toggle until confirmed
                currentBatchId = this.getAttribute('data-batch-id');
                currentToggleInput = this;
                confirmModal.show();
            });
        });

        document.getElementById('confirmStatusUpdateBtn').addEventListener('click', function() {
            if (!currentBatchId || !currentToggleInput) return;

            const btn = this;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
            btn.disabled = true;

            fetch(`/batches/${currentBatchId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI manually
                    const isCompleted = data.status === 'Completed';
                    currentToggleInput.checked = isCompleted;
                    
                    const label = document.getElementById(`status-label-${currentBatchId}`);
                    if (isCompleted) {
                        label.innerHTML = '<span class="text-success">COMPLETED</span>';
                    } else {
                        label.innerHTML = '<span class="text-primary">RUNNING</span>';
                    }
                    
                    confirmModal.hide();
                } else {
                    alert('Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the status.');
            })
            .finally(() => {
                btn.innerHTML = 'Yes, Update';
                btn.disabled = false;
            });
        });
    });
</script>
@endpush
@endsection
