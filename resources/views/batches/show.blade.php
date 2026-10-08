@extends('layouts.master')

@section('pageTitle', 'Batch Overview - ' . $batch->batch_code)

@section('content')
<div class="container-fluid p-4 batch-overview-page">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div class="d-flex align-items-center">
            @php
                $backRoute = route('batches.index');
                if (auth()->check() && auth()->user()->hasRole('Technical Staff')) {
                    $backRoute = route('batch-system.index');
                } elseif (auth()->check() && auth()->user()->can('Batch Allocation') && !auth()->user()->hasRole('Super Admin')) {
                    $backRoute = route('batch-allocation.index');
                }
            @endphp
            <a href="{{ $backRoute }}" class="btn btn-link text-muted p-0 me-3" style="text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Batches
            </a>
            <h2 class="fw-bold text-dark mb-0">{{ $batch->batch_code }} — Batch Overview</h2>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-primary-soft text-primary px-3 py-2 rounded-pill">{{ strtoupper($batch->course->title) }}</span>
            <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#allocateModal">
                <i class="fas fa-plus me-1"></i> Allocate Student
            </button>
        </div>
    </div>

    <!-- Batch Detail Grid -->
    <div class="row g-4 mb-5">
        <!-- Row 1 -->
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Course</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->course->title }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Batch Code</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->batch_code }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Mentor</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->instructor->name ?? 'Unassigned' }}</p>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Type</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->batch_type }} ({{ $batch->batch_track ?? 'Normal' }})</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Total Hours</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->class_duration ?? '120h' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Status</label>
                <p class="info-value h5 fw-bold text-success mb-0">{{ $batch->status == '1' ? 'Started' : 'Upcoming' }}</p>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Start Date</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ date('d M Y', strtotime($batch->start_date)) }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Last Join Date</label>
                <p class="info-value h5 fw-bold text-dark mb-0">{{ $batch->join_deadline ? date('d M Y', strtotime($batch->join_deadline)) : '-' }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="detail-card p-4 rounded-4 bg-white border border-light shadow-sm h-100">
                <label class="info-label text-muted text-uppercase smaller fw-bold mb-2 d-block">Students Enrolled</label>
                <p class="info-value h5 fw-bold text-primary mb-0">{{ count($admissions) }} / {{ $batch->max_students }}</p>
            </div>
        </div>
    </div>

    <!-- Student Table -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">Batch Students ({{ count($admissions) }})</h4>
        <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#allocateModal">
            <i class="fas fa-plus me-1"></i> Allocate Student
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 text-muted small text-uppercase">#</th>
                        <th class="border-0 text-muted small text-uppercase">Student Name</th>
                        <th class="border-0 text-muted small text-uppercase">Phone</th>
                        <th class="border-0 text-muted small text-uppercase">Course</th>
                        <th class="border-0 text-muted small text-uppercase">Batch Code</th>
                        <th class="border-0 text-muted small text-uppercase pe-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admissions as $index => $admission)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle-sm me-3 bg-primary bg-opacity-10 text-primary fw-bold">
                                    {{ strtoupper(substr($admission->contact->name, 0, 2)) }}
                                </div>
                                <span class="fw-bold text-dark">{{ $admission->contact->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $admission->contact->caller_number }}</td>
                        <td>{{ $admission->course->title }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                {{ $batch->batch_code }}
                            </span>
                        </td>
                        <td class="pe-4">
                            <span class="badge bg-primary-soft text-primary rounded-pill px-3 py-1">Registered</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash d-block mb-3 h3 opacity-25"></i>
                            No students registered for this batch yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Topics Log Section -->
    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <h4 class="fw-bold text-dark mb-0">Meeting Topics Log</h4>
        <button class="btn btn-secondary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addTopicModal">
            <i class="fas fa-plus me-1"></i> Log Meeting Topic
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 border-0 text-muted small text-uppercase">Date</th>
                        <th class="border-0 text-muted small text-uppercase">Module Taught</th>
                        <th class="border-0 text-muted small text-uppercase">Description</th>
                        <th class="border-0 text-muted small text-uppercase">Status</th>
                        <th class="border-0 text-muted small text-uppercase pe-4 text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topicLogs as $log)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $log->created_at->format('d M Y') }}</td>
                        <td><span class="fw-bold text-dark">{{ $log->curriculum->title ?? 'N/A' }}</span></td>
                        <td class="text-muted">{{ $log->description ?: '-' }}</td>
                        <td>
                            @if($log->status === 'Completed' || ($log->status === null && $log->is_completed))
                                <span class="badge bg-success-soft text-success rounded-pill px-3 py-1">Completed</span>
                            @elseif($log->status === 'In Progress')
                                <span class="badge bg-warning-soft text-warning rounded-pill px-3 py-1">In Progress</span>
                            @else
                                <span class="badge bg-secondary-soft text-secondary rounded-pill px-3 py-1">{{ $log->status ?: 'Pending' }}</span>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-sm btn-light border-0" data-bs-toggle="modal" data-bs-target="#editTopicModal{{ $log->id }}">
                                <i class="fas fa-edit text-muted"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Edit Topic Modal -->
                    <div class="modal fade" id="editTopicModal{{ $log->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('batches.topics.update', $log->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Topic Log</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Module Taught</label>
                                            <select name="course_curriculum_id" class="form-select" required>
                                                <option value="{{ $log->course_curriculum_id }}" selected>{{ $log->curriculum->title ?? 'N/A' }}</option>
                                                @foreach($pendingTopics as $topic)
                                                    <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" class="form-control" rows="3">{{ $log->description }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Progress Status</label>
                                            <select name="status" class="form-select">
                                                <option value="Pending" {{ ($log->status === 'Pending' || ($log->status === null && !$log->is_completed)) ? 'selected' : '' }}>Pending</option>
                                                <option value="In Progress" {{ $log->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Completed" {{ ($log->status === 'Completed' || ($log->status === null && $log->is_completed)) ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="is_completed" id="edit_is_completed_{{ $log->id }}" {{ $log->is_completed ? 'checked' : '' }}>
                                            <label class="form-check-label" for="edit_is_completed_{{ $log->id }}">
                                                Mark as Completed (Hides from future selections)
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            No meeting topics logged yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('batches.topics.store', $batch->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Log Meeting Topic</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Module Taught</label>
                        <select name="course_curriculum_id" class="form-select" required>
                            <option value="">-- Select Module --</option>
                            @foreach($pendingTopics as $topic)
                                <option value="{{ $topic->id }}">{{ $topic->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="What was covered today?"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Progress Status</label>
                        <select name="status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="In Progress" selected>In Progress</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_completed" id="is_completed">
                        <label class="form-check-label" for="is_completed">
                            Mark as Completed (Hides from future selections)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Log</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('batches.partials.allocate_modal')

@push('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/batches-show.css') }}">
@endpush
@endsection
