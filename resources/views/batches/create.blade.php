@extends('layouts.master')

@section('pageTitle', 'Create Batch')

@push('css')

@endpush

@section('content')
    <div class="container-fluid p-0">
        <div class="mb-4">
            <a href="{{ route('batches.index') }}" class="btn btn-link text-muted p-0 mb-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Batches
            </a>
            <h3 class="fw-bold">Create New Batch</h3>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="card border-0 shadow-lg" style="border-radius: 1.5rem;">
                    <div class="card-body p-4">
                        <form action="{{ route('batches.store') }}" method="POST" id="batchForm">
                            @csrf

                            <!-- STEP 1: Basic Details -->
                            <div id="step1" class="row g-4">
                                <div class="col-12 mb-3">
                                    <h5>Step 1: Batch Details</h5>
                                    <hr>
                                </div>

                                <!-- Batch Track Selection -->
                                <div class="col-12 mb-2">
                                    <label class="form-label small fw-bold text-muted d-block mb-3">Batch Type *</label>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <input type="radio" class="btn-check" name="batch_track" id="track_normal"
                                                value="Normal" {{ old('batch_track', 'Normal') == 'Normal' ? 'checked' : '' }}
                                                required>
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
                                                value="Fast Track" {{ old('batch_track') == 'Fast Track' ? 'checked' : '' }}
                                                required>
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
                                    <select name="course_id" id="course_id"
                                        class="form-select rounded-3 p-3 @error('course_id') is-invalid @enderror" required>
                                        <option value="">Choose a course...</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                    <input type="text" name="batch_code" id="batch_code"
                                        class="form-control rounded-3 p-3 @error('batch_code') is-invalid @enderror"
                                        placeholder="e.g. MERN-MAY-24" value="{{ old('batch_code') }}" required>
                                    @error('batch_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Batch Type -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch Schedule Type *</label>
                                    <select name="batch_type" id="batch_type"
                                        class="form-select rounded-3 p-3 @error('batch_type') is-invalid @enderror"
                                        required>
                                        <option value="Weekday Morning" {{ old('batch_type') == 'Weekday Morning' ? 'selected' : '' }}>Weekday Morning</option>
                                        <option value="Weekday Evening" {{ old('batch_type') == 'Weekday Evening' ? 'selected' : '' }}>Weekday Evening</option>
                                        <option value="Weekend" {{ old('batch_type') == 'Weekend' ? 'selected' : '' }}>Weekend
                                        </option>
                                    </select>
                                    @error('batch_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Batch Name -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Batch Name *</label>
                                    <input type="text" name="batch_name" id="batch_name"
                                        class="form-control rounded-3 p-3 @error('batch_name') is-invalid @enderror"
                                        placeholder="e.g. Full Stack Web Development - Evening Batch"
                                        value="{{ old('batch_name') }}" required>
                                    @error('batch_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assigned Staff -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Assigned Staff *</label>
                                    <select name="instructor_id" id="instructor_id"
                                        class="form-select rounded-3 p-3 @error('instructor_id') is-invalid @enderror"
                                        required>
                                        <option value="">Select Staff...</option>
                                        @foreach($instructors as $instructor)
                                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                                {{ $instructor->name }} ({{ $instructor->role->name ?? 'Staff' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('instructor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="status" value="pending">

                                <!-- Start Date -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Start Date *</label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control rounded-3 p-3 @error('start_date') is-invalid @enderror"
                                        value="{{ old('start_date') }}" required>
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">End Date *</label>
                                    <input type="date" name="end_date" id="end_date"
                                        class="form-control rounded-3 p-3 @error('end_date') is-invalid @enderror"
                                        value="{{ old('end_date') }}" required>
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Time Slots -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch From Time *</label>
                                    <input type="time" name="batch_from_time" id="batch_from_time"
                                        class="form-control rounded-3 p-3 @error('batch_from_time') is-invalid @enderror"
                                        value="{{ old('batch_from_time') }}" required>
                                    @error('batch_from_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Batch To Time *</label>
                                    <input type="time" name="batch_to_time" id="batch_to_time"
                                        class="form-control rounded-3 p-3 @error('batch_to_time') is-invalid @enderror"
                                        value="{{ old('batch_to_time') }}" required>
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
                                        <option value="1 hr" {{ old('class_duration') == '1 hr' ? 'selected' : '' }}>1 Hour
                                        </option>
                                        <option value="1.5 hrs" {{ old('class_duration') == '1.5 hrs' ? 'selected' : '' }}>1.5
                                            Hours</option>
                                        <option value="2 hrs" {{ old('class_duration') == '2 hrs' ? 'selected' : '' }}>2 Hours
                                        </option>
                                        <option value="2.5 hrs" {{ old('class_duration') == '2.5 hrs' ? 'selected' : '' }}>2.5
                                            Hours</option>
                                        <option value="3 hrs" {{ old('class_duration') == '3 hrs' ? 'selected' : '' }}>3 Hours
                                        </option>
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
                                        value="{{ old('join_deadline') }}">
                                    @error('join_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Max Students -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted">Max Students *</label>
                                    <input type="number" name="max_students"
                                        class="form-control rounded-3 p-3 @error('max_students') is-invalid @enderror"
                                        value="{{ old('max_students', 50) }}" min="1" required>
                                    @error('max_students')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4 d-flex align-items-center gap-3">
                                    <button type="button" id="btnNext"
                                        class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">
                                        Next Step <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    <a href="{{ route('batches.index') }}"
                                        class="btn btn-link text-muted fw-bold text-decoration-none">Cancel</a>
                                </div>
                            </div>

                            <!-- STEP 2: Date Selection -->
                            <div id="step2" class="row g-4" style="display: none;">
                                <div class="col-12 mb-3">
                                    <h5>Step 2: Class Schedule & Zoom Links</h5>
                                    <hr>
                                    <p class="text-muted">Review the generated class dates below. Uncheck any dates where
                                        classes will not be held (e.g., holidays). Meeting links will be created for all
                                        checked dates.</p>
                                </div>

                                <div class="col-12 mb-4">
                                    <div class="card bg-light border-0">
                                        <div class="card-body" id="calendarContainer">
                                            <!-- Dates will be populated here via JS -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Meeting Link Generation -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted">Meeting Links</label>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" id="generate_zoom_link"
                                            name="generate_zoom_link" value="1" checked>
                                        <label class="form-check-label small fw-bold text-primary"
                                            for="generate_zoom_link">Automatically generate Zoom Meeting Links for all
                                            scheduled dates</label>
                                    </div>
                                    <input type="url" name="meeting_link" id="meeting_link_input"
                                        class="form-control rounded-3 p-3 d-none"
                                        placeholder="Or paste a common meeting link..." value="{{ old('meeting_link') }}">
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="button" id="btnBack"
                                        class="btn btn-secondary rounded-pill px-4 py-3 fw-bold shadow-sm me-2">
                                        <i class="fas fa-arrow-left me-2"></i> Back
                                    </button>
                                    <button type="submit" id="btnSubmit"
                                        class="btn btn-success rounded-pill px-5 py-3 fw-bold shadow-sm">
                                        Create Batch & Generate Links
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 1.5rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i> Batch Tips</h5>
                        <ul class="list-unstyled small mb-0">
                            <li class="mb-2"><i class="fas fa-check me-2 text-white-50"></i> Choose a unique batch code for
                                easy identification.</li>
                            <li class="mb-2"><i class="fas fa-check me-2 text-white-50"></i> Assign an instructor to notify
                                them of the schedule.</li>
                            <li class="mb-2"><i class="fas fa-check me-2 text-white-50"></i> Set a join deadline to manage
                                early-bird registrations.</li>
                            <li class="mb-2"><i class="fas fa-check me-2 text-white-50"></i> The system will automatically
                                skip weekends for Weekday batches.</li>
                            <li><i class="fas fa-check me-2 text-white-50"></i> Review your generated dates carefully before
                                submission!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const step1 = document.getElementById('step1');
            const step2 = document.getElementById('step2');
            const btnNext = document.getElementById('btnNext');
            const btnBack = document.getElementById('btnBack');
            const btnSubmit = document.getElementById('btnSubmit');

            const zoomCheckbox = document.getElementById('generate_zoom_link');
            const meetingLinkInput = document.getElementById('meeting_link_input');

            function toggleMeetingInput() {
                if (zoomCheckbox.checked) {
                    meetingLinkInput.value = '';
                    meetingLinkInput.classList.add('d-none');
                } else {
                    meetingLinkInput.classList.remove('d-none');
                }
            }

            zoomCheckbox.addEventListener('change', toggleMeetingInput);
            toggleMeetingInput(); // init

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
                        diffInMs += 24 * 60 * 60 * 1000; // handle overnight
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

            btnNext.addEventListener('click', function () {
                // Basic validation
                let isValid = true;
                ['course_id', 'batch_code', 'batch_type', 'batch_name', 'instructor_id', 'start_date', 'end_date', 'batch_from_time', 'batch_to_time', 'class_duration'].forEach(id => {
                    const el = document.getElementById(id);
                    if (!el.value) {
                        el.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        el.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Please fill in all required fields in Step 1 before proceeding.');
                    return;
                }

                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(document.getElementById('end_date').value);

                if (endDate < startDate) {
                    alert('End Date cannot be before Start Date.');
                    return;
                }

                // Generate calendar based on Batch Type
                const batchType = document.getElementById('batch_type').value;
                generateCalendar(startDate, endDate, batchType);

                // Switch steps
                step1.style.display = 'none';
                step2.style.display = 'flex';
            });

            btnBack.addEventListener('click', function () {
                step2.style.display = 'none';
                step1.style.display = 'flex';
            });

            document.getElementById('batchForm').addEventListener('submit', function (e) {
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                btnSubmit.disabled = true;
            });

            function generateCalendar(start, end, type) {
                const container = document.getElementById('calendarContainer');
                container.innerHTML = '';

                let currentDate = new Date(start);
                let html = '<div class="row g-2">';
                let count = 0;

                while (currentDate <= end) {
                    const dayOfWeek = currentDate.getDay(); // 0 = Sun, 1 = Mon ... 6 = Sat
                    let isClassDay = true;

                    if (type === 'Weekday Morning' || type === 'Weekday Evening') {
                        if (dayOfWeek === 0 || dayOfWeek === 6) isClassDay = false;
                    } else if (type === 'Weekend') {
                        if (dayOfWeek !== 0 && dayOfWeek !== 6) isClassDay = false;
                    }

                    if (isClassDay) {
                        const dateStr = currentDate.toISOString().split('T')[0];
                        const displayDate = currentDate.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });

                        html += `
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check p-2 border rounded bg-white">
                                    <input class="form-check-input ms-1 me-2" type="checkbox" name="class_dates[]" value="${dateStr}" id="date_${dateStr}" checked>
                                    <label class="form-check-label small fw-bold" style="cursor: pointer;" for="date_${dateStr}">
                                        ${displayDate}
                                    </label>
                                </div>
                            </div>
                        `;
                        count++;
                    }

                    currentDate.setDate(currentDate.getDate() + 1);
                }

                html += '</div>';

                if (count === 0) {
                    html = '<div class="alert alert-warning">No valid class dates found between the selected dates for the chosen Batch Type.</div>';
                    btnSubmit.disabled = true;
                } else {
                    btnSubmit.disabled = false;
                }

                container.innerHTML = html;
            }
        });
    </script>
@endpush