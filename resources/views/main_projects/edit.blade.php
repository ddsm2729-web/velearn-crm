@extends('layouts.header')

@section('pageTitle', 'Edit Main Project')

@section('section')
    <div class="container-fluid p-0">
        <div class="mb-4">
            <h3 class="fw-bold">Edit Main Project</h3>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('main_projects.update', $main_project) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Course</label>
                        <select name="course_id" id="course_id" class="form-control select2" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $main_project->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Module</label>
                        <input type="text" name="module" class="form-control" required placeholder="e.g., Module 9"
                            value="{{ $main_project->module }}">
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required value="{{ $main_project->title }}">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ $main_project->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Upload Complete Details (PDF/Word)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                        @if($main_project->document)
                            <div class="mt-2">
                                <a href="{{ asset('uploads/main_projects/' . $main_project->document) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> View Current Document
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Review 1 Title <span class="text-danger">*</span></label>
                            <input type="text" name="review_1_title" class="form-control" required
                                value="{{ $main_project->review_1_title }}">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label>Review 1 Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="review_1_deadline" class="form-control" required
                                value="{{ $main_project->review_1_deadline ? \Carbon\Carbon::parse($main_project->review_1_deadline)->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Review 2 Title <span class="text-danger">*</span></label>
                            <input type="text" name="review_2_title" class="form-control" required
                                value="{{ $main_project->review_2_title }}">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label>Review 2 Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="review_2_deadline" class="form-control" required
                                value="{{ $main_project->review_2_deadline ? \Carbon\Carbon::parse($main_project->review_2_deadline)->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Review 3 Title <span class="text-danger">*</span></label>
                            <input type="text" name="review_3_title" class="form-control" required
                                value="{{ $main_project->review_3_title }}">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label>Review 3 Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="review_3_deadline" class="form-control" required
                                value="{{ $main_project->review_3_deadline ? \Carbon\Carbon::parse($main_project->review_3_deadline)->format('Y-m-d') : '' }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Reveal Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="reveal_date" class="form-control"
                            title="Students will only see this project on or after this date." required
                            value="{{ $main_project->reveal_date ? \Carbon\Carbon::parse($main_project->reveal_date)->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted">The project will remain hidden from students until this date and
                            time.</small>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($main_project->status) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Criteria & Maximum Marks</label>
                        <table class="table table-bordered" id="criteriaTable">
                            <thead>
                                <tr>
                                    <th>Criterion</th>
                                    <th>Maximum Mark</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($main_project->criteria && $main_project->criteria->count() > 0)
                                    @foreach($main_project->criteria as $criterion)
                                        <tr>
                                            <td><input type="text" name="criteria[]" class="form-control" required
                                                    value="{{ $criterion->criterion }}"></td>
                                            <td><input type="number" step="0.01" name="maximum_marks[]" class="form-control"
                                                    required value="{{ $criterion->maximum_mark }}"></td>
                                            <td><button type="button" class="btn btn-danger remove-criterion">Remove</button></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td><input type="text" name="criteria[]" class="form-control" required
                                                placeholder="e.g. Code Quality"></td>
                                        <td><input type="number" step="0.01" name="maximum_marks[]" class="form-control"
                                                required placeholder="e.g. 10"></td>
                                        <td><button type="button" class="btn btn-danger remove-criterion">Remove</button></td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addCriterion">Add Criterion</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Main Project</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                width: '100%'
            });

            // Dynamic Criteria Table Logic
            $('#addCriterion').on('click', function () {
                var newRow = `<tr>
                            <td><input type="text" name="criteria[]" class="form-control" required placeholder="e.g. Code Quality"></td>
                            <td><input type="number" step="0.01" name="maximum_marks[]" class="form-control" required placeholder="e.g. 10"></td>
                            <td><button type="button" class="btn btn-danger remove-criterion">Remove</button></td>
                        </tr>`;
                $('#criteriaTable tbody').append(newRow);
            });

            $(document).on('click', '.remove-criterion', function () {
                if ($('#criteriaTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                } else {
                    alert('You must have at least one criterion.');
                }
            });
        });
    </script>
@endpush