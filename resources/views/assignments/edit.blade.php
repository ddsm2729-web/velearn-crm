@extends('layouts.header')

@section('pageTitle', 'Edit Assignment')

@section('section')
    <div class="container-fluid p-0">
        <div class="mb-4">
            <h3 class="fw-bold">Edit Assignment</h3>
        </div>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('assignments.update', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label>Course</label>
                        <select name="course_id" id="course_id" class="form-control select2" required>
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}" {{ $assignment->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Module</label>
                        <input type="text" name="module" class="form-control" required placeholder="e.g., Module 9"
                            value="{{ $assignment->module }}">
                    </div>
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required value="{{ $assignment->title }}">
                    </div>
                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control">{{ $assignment->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Upload Complete Details (PDF/Word)</label>
                        <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                        @if($assignment->document)
                            <div class="mt-2">
                                <a href="{{ asset('uploads/assignments/' . $assignment->document) }}" target="_blank">
                                    <i class="bi bi-file-earmark-pdf"></i> View Current Document
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control"
                            value="{{ $assignment->due_date ? \Carbon\Carbon::parse($assignment->due_date)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="mb-3">
                        <label>Reveal Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="reveal_date" class="form-control"
                            title="Students will only see this assignment on or after this date." required
                            value="{{ $assignment->reveal_date ? \Carbon\Carbon::parse($assignment->reveal_date)->format('Y-m-d\TH:i') : '' }}">
                        <small class="text-muted">The assignment will remain hidden from students until this date and
                            time.</small>
                    </div>

                    <div class="mb-3">
                        <label>Status</label>
                        <input type="text" class="form-control" value="{{ ucfirst($assignment->status) }}" disabled>
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
                                @if ($assignment->criteria && $assignment->criteria->count() > 0)
                                    @foreach ($assignment->criteria as $criterion)
                                        <tr>
                                            <td><input type="text" name="criteria[]" class="form-control" required
                                                    value="{{ $criterion->criterion }}"></td>
                                            <td><input type="number" step="0.01" name="maximum_marks[]" class="form-control"
                                                    required value="{{ $criterion->maximum_mark }}">
                                            </td>
                                            <td><button type="button" class="btn btn-danger remove-criterion">Remove</button></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td><input type="text" name="criteria[]" class="form-control" required
                                                placeholder="e.g. Code Quality"></td>
                                        <td><input type="number" step="0.01" name="maximum_marks[]" class="form-control"
                                                required placeholder="e.g. 10"></td>
                                        <td><button type="button" class="btn btn-danger remove-criterion">Remove</button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-success" id="addCriterion">Add Criterion</button>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Assignment</button>
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