@extends('layouts.header')
@section('pageTitle', 'Course Evaluation Options')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Course Evaluation Options</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1'>
        <a href='{{ route("course-evaluation-options.create") }}' class='btn btn-primary d-flex align-items-center shadow-sm'>
            <i class='fas fa-plus me-2'></i> Create
        </a>
    </div>
</div>
<div class='row'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-body p-0'>
                <div class='table-responsive'>
                    <table class='table table-hover mb-0'>
                        <thead class='text-uppercase text-muted' style='font-size: 0.75rem; background-color: #f8f9fa;'>
                            <tr>
                                <th class='ps-4 py-3'>ID</th>
                                <th>QUESTION ID</th><th>OPTION</th><th>IS CORRECT</th>
                                <th class='pe-4 py-3 text-end'>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($options as $option)
                            <tr>
                                <td class='ps-4'>{{ $option->id }}</td>
                                <td>{{ $option->course_evaluation_question_id }}</td><td>{{ $option->option }}</td><td>{{ $option->is_correct ? 'Yes' : 'No' }}</td>
                                <td class='pe-4 text-end'>
                                    <a href='{{ route("course-evaluation-options.edit", $option->id) }}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                    <form action='{{ route("course-evaluation-options.destroy", $option->id) }}' method='POST' class='d-inline'>
                                        @csrf
                                        @method('DELETE')
                                        <button class='btn btn-sm btn-outline-danger' onclick='return confirm("Delete this item?")'>Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($options->isEmpty())
                            <tr><td colspan='10' class='text-center py-4'>No records found.</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
