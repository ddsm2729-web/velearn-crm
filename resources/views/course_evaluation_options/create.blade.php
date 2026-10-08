@extends('layouts.header')
@section('pageTitle', 'Create Course Evaluation Options')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Create Course Evaluation Options</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1'>
        <a href='{{ route("course-evaluation-options.index") }}' class='btn btn-secondary shadow-sm'>Back</a>
    </div>
</div>
<div class='card'>
    <div class='card-body'>
        <form action='{{ route("course-evaluation-options.store") }}' method='POST'>
            @csrf
            <div class='mb-3'><label>Question</label><select name='course_evaluation_question_id' class='form-control' required>@foreach($questions as $q)<option value='{{ $q->id }}'>{{ $q->question }}</option>@endforeach</select></div><div class='mb-3'><label>Option Text</label><input type='text' name='option' class='form-control' value='{{ $model->option ?? '' }}' required></div><div class='mb-3 form-check'><input type='checkbox' name='is_correct' class='form-check-input' value='1' {{ isset($model) && $model->is_correct ? 'checked' : '' }}><label class='form-check-label'>Is Correct Option?</label></div>
            <button type='submit' class='btn btn-success'>Save</button>
        </form>
    </div>
</div>
@endsection
