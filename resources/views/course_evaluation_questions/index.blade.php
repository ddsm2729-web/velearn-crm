@extends('layouts.header')
@section('pageTitle', 'Course Evaluation Questions')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Course Evaluation Questions</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1'>
        <a href='{{ route("course-evaluation-questions.create") }}' class='btn btn-primary d-flex align-items-center shadow-sm'>
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
                                <th>COURSE ID</th><th>QUESTION</th><th>MARKS</th>
                                <th class='pe-4 py-3 text-end'>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($questions as $question)
                            <tr>
                                <td class='ps-4'>{{ $question->id }}</td>
                                <td>{{ $question->course_id }}</td><td>{{ $question->question }}</td><td>{{ $question->marks }}</td>
                                <td class='pe-4 text-end'>
                                    <a href='{{ route("course-evaluation-questions.edit", $question->id) }}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                    <form action='{{ route("course-evaluation-questions.destroy", $question->id) }}' method='POST' class='d-inline'>
                                        @csrf
                                        @method('DELETE')
                                        <button class='btn btn-sm btn-outline-danger' onclick='return confirm("Delete this item?")'>Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                            @if($questions->isEmpty())
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
