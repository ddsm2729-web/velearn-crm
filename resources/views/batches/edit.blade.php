@extends('layouts.master')

@section('pageTitle', 'Edit Batch')

@push('css')

@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-4">
            <a href="{{ route('batches.index') }}" class="btn btn-link text-muted p-0 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Batches
            </a>
            <h3 class="fw-bold">Edit Batch: {{ $batch->batch_code }}</h3>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card border-0 shadow-lg" style="border-radius: 1.5rem;">
                    <div class="card-body p-4">
                        <form action="{{ route('batches.update', $batch) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <!-- Batch Track Selection -->
                                <div class="col-12 mb-2">
                                    <label class="form-label small fw-bold text-muted d-block mb-3">Batch Type *</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <input type="radio" class="btn-check" name="batch_track" id="track_normal"
                                                value="Normal" {{ old('batch_track', $batch->batch_track) == 'Normal' ? 'checked' : '' }} required>
                                            <label
                                                class="card-track-option p-4 rounded-4 w-100 d-flex align-items-center gap-3 cursor-pointer"
                                                for="track_normal">
                                                <div class="icon-wrapper p-3 rounded-3"
                                                    style="background: rgba(99, 102, 241, 0.1); color: #6366f1; transition: all 0.25s ease;">
                                                    <i class="fas fa-graduation-cap fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1 text-dark">Normal Batch</h6>
                                                    <span class="text-muted smaller">Standard duration</span>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="radio" class="btn-check" name="batch_track" id="track_fast"
                                                value="Fast Track" {{ old('batch_track', $batch->batch_track) == 'Fast Track' ? 'checked' : '' }} required>
                                            <label
                                                class="card-track-option p-4 rounded-4 w-100 d-flex align-items-center gap-3 cursor-pointer"
                                                for="track_fast">
                                                <div class="icon-wrapper p-3 rounded-3"
                                                    style="background: rgba(245, 158, 11, 0.1); color: #f59e0b; transition: all 0.25s ease;">
                                                    <i class="fas fa-bolt fa-lg"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-1 text-dark">Fast Track Batch</h6>
                                                    <span class="text-muted smaller">Accelerated</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @error('batch_track')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Course Selection -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Select Course *</label>
                                    <select name="course_id"
                                        class="form-select rounded-3 p-3 @error('course_id') is-invalid @enderror" required>
                                        <option value="">Choose a course...</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id', $batch->course_id) == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Batch Code -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch Code *</label>
                                    <input type="text" name="batch_code"
                                        class="form-control rounded-3 p-3 @error('batch_code') is-invalid @enderror"
                                        placeholder="e.g. MERN-MAY-24" value="{{ old('batch_code', $batch->batch_code) }}"
                                        required>
                                    @error('batch_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Batch Type -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch Type *</label>
                                    <select name="batch_type"
                                        class="form-select rounded-3 p-3 @error('batch_type') is-invalid @enderror"
                                        required>
                                        <option value="Weekday Morning" {{ old('batch_type', $batch->batch_type) == 'Weekday Morning' ? 'selected' : '' }}>Weekday Morning</option>
                                        <option value="Weekday Evening" {{ old('batch_type', $batch->batch_type) == 'Weekday Evening' ? 'selected' : '' }}>Weekday Evening</option>
                                        <option value="Weekend" {{ old('batch_type', $batch->batch_type) == 'Weekend' ? 'selected' : '' }}>Weekend</option>
                                    </select>
                                    @error('batch_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Batch Name -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Batch Name *</label>
                                    <input type="text" name="batch_name"
                                        class="form-control rounded-3 p-3 @error('batch_name') is-invalid @enderror"
                                        placeholder="e.g. Full Stack Web Development - Evening Batch"
                                        value="{{ old('batch_name', $batch->batch_name) }}" required>
                                    @error('batch_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assigned Staff -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Assigned Staff *</label>
                                    <select name="instructor_id"
                                        class="form-select rounded-3 p-3 @error('instructor_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Staff...</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ old('instructor_id', $batch->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                                {{ $instructor->name }} ({{ $instructor->role->name ?? 'Staff' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('instructor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Start Date *</label>
                                    <input type="date" name="start_date"
                                        class="form-control rounded-3 p-3 @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date', $batch->start_date) }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Time Slots -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch From Time *</label>
                                    <input type="time" name="batch_from_time" id="batch_from_time"
                                        class="form-control rounded-3 p-3 @error('batch_from_time') is-invalid @enderror"
                                        value="{{ old('batch_from_time', substr($batch->batch_from_time, 0, 5)) }}"
                                        required>
                                    @error('batch_from_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch To Time *</label>
                                    <input type="time" name="batch_to_time" id="batch_to_time"
                                        class="form-control rounded-3 p-3 @error('batch_to_time') is-invalid @enderror"
                                        value="{{ old('batch_to_time', substr($batch->batch_to_time, 0, 5)) }}" required>
                                    @error('batch_to_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Class Duration -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Class Duration *</label>
                                    <select name="class_duration" id="class_duration"
                                        class="form-select rounded-3 p-3 @error('class_duration') is-invalid @enderror"
                                        required>
                                        <option value="">Select Duration...</option>
                                        <option value="1 hr" {{ old('class_duration', $batch->class_duration) == '1 hr' ? 'selected' : '' }}>1 Hour</option>
                                        <option value="1.5 hrs" {{ old('class_duration', $batch->class_duration) == '1.5 hrs' ? 'selected' : '' }}>1.5 Hours</option>
                                        <option value="2 hrs" {{ old('class_duration', $batch->class_duration) == '2 hrs' ? 'selected' : '' }}>2 Hours</option>
                                        <option value="2.5 hrs" {{ old('class_duration', $batch->class_duration) == '2.5 hrs' ? 'selected' : '' }}>2.5 Hours</option>
                                        <option value="3 hrs" {{ old('class_duration', $batch->class_duration) == '3 hrs' ? 'selected' : '' }}>3 Hours</option>
                                    </select>
                                    @error('class_duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Enrollment Deadline -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Join Deadline</label>
                                    <input type="date" name="join_deadline"
                                        class="form-control rounded-3 p-3 @error('join_deadline') is-invalid @enderror"
                                        value="{{ old('join_deadline', $batch->join_deadline) }}">
                                    @error('join_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Students -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Max Students *</label>
                                    <input type="number" name="max_students"
                                        class="form-control rounded-3 p-3 @error('max_students') is-invalid @enderror"
                                        value="{{ old('max_students', $batch->max_students) }}" min="1" required>
                                    @error('max_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <input type="hidden" name="status" value="pending">
                                </div>

                                <!-- Meeting Link Generation -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Meeting Links</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="generate_zoom_link"
                                            name="generate_zoom_link" value="1" {{ old('generate_zoom_link', !$batch->meeting_link) ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-bold text-primary"
                                            for="generate_zoom_link">Automatically generate Zoom Meeting Links for all
                                            scheduled dates</label>
                                    </div>
                                    <input type="url" name="meeting_link" id="meeting_link_input"
                                        class="form-control rounded-3 p-3 {{ old('generate_zoom_link', !$batch->meeting_link) ? 'd-none' : '' }}"
                                        placeholder="Or paste a common meeting link..."
                                        value="{{ old('meeting_link', $batch->meeting_link) }}">
                                    @error('meeting_link')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4 d-flex align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">
                                        Update Batch
                                    </button>
                                    <a href="{{ route('batches.index') }}"
                                        class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm bg-info text-white" style="border-radius: 1.5rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-edit me-2"></i> Quick Summary</h5>
                        <p class="small mb-2"><strong>Current Students:</strong> {{ $batch->joined_student_count }}</p>
                        <p class="small mb-2"><strong>Capacity:</strong> {{ $batch->max_students }}</p>
                        <p class="small mb-0"><strong>Last Updated:</strong> {{ $batch->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateDuration() {
                const fromTime = document.getElementById('batch_from_time').value;
                const toTime = document.getElementById('batch_to_time').value;

                if (fromTime && toTime) {
                    const fromParts = fromTime.split(':');
                    const toParts = toTime.split(':');

                    const fromDate = new Date(1970, 0, 1, fromParts[0], fromParts[1]);
                    const toDate = new Date(1970, 0, 1, toParts[0], toParts[1]);

                    let diffInMs = toDate - fromDate;
                    if (diffInMs < 0) {
                        diffInMs += 24 * 60 * 60 * 1000;
                    }

                    const diffInHours = diffInMs / (1000 * 60 * 60);
                    const durationSelect = document.getElementById('class_duration');

                    let targetValue = '';
                    if (diffInHours === 1) targetValue = '1 hr';
                    else if (diffInHours === 1.5) targetValue = '1.5 hrs';
                    else if (diffInHours === 2) targetValue = '2 hrs';
                    else if (diffInHours === 2.5) targetValue = '2.5 hrs';
                    else if (diffInHours === 3) targetValue = '3 hrs';

                    if (targetValue) {
                        durationSelect.value = targetValue;
                    }
                }
            }

            document.getElementById('batch_from_time').addEventListener('change', updateDuration);
            document.getElementById('batch_to_time').addEventListener('change', updateDuration);

            const zoomCheckbox = document.getElementById('generate_zoom_link');
            const meetingLinkInput = document.getElementById('meeting_link_input');

            function toggleMeetingInput() {
                if (zoomCheckbox.checked) {
                    meetingLinkInput.classList.add('d-none');
                } else {
                    meetingLinkInput.classList.remove('d-none');
                }
            }

            if (zoomCheckbox && meetingLinkInput) {
                zoomCheckbox.addEventListener('change', toggleMeetingInput);
                toggleMeetingInput(); // Initialize state
            }
        });
    </script>
@endpush