@extends('layouts.header')
@section('pageTitle', 'Edit Course Evaluation Question')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Edit Course Evaluation Question</strong></h3>
    </div>
    <div class='col-auto ms-auto text-end mt-n1'>
        <a href="javascript:history.back()" class='btn btn-secondary shadow-sm'><i class="fas fa-arrow-left me-2"></i> Back</a>
    </div>
</div>
<div class='card'>
    <div class='card-body'>
        <form action='{{ route('course-evaluation-questions.update', $question->id) }}' method='POST'>
            @csrf
            @method('PUT')
            <div class='mb-3'><label>Course</label><select name='course_id' class='form-control' required>
    @foreach($courses as $course)
        <option value='{{ $course->id }}' {{ (isset($question) && $question->course_id == $course->id) ? 'selected' : '' }}>{{ $course->title }}</option>
    @endforeach
</select></div>
<div class='mb-3'><label>Question Text</label><textarea name='question' class='form-control' required>{{ $question->question ?? '' }}</textarea></div>
<div class='mb-3'><label>Marks</label><input type='number' name='marks' class='form-control' value='{{ $question->marks ?? 1 }}' required></div>

<h5 class='mt-4'>Options</h5>
@for ($i = 0; $i < 4; $i++)
    <div class='row mb-2 align-items-center'>
        <div class='col-10'>
            <input type='text' name='options[{{ $i }}]' class='form-control' placeholder='Option {{ $i + 1 }}' value='{{ isset($question) && isset($question->options[$i]) ? $question->options[$i]->option : '' }}'>
        </div>
        <div class='col-2'>
            <div class='form-check'>
                <input class='form-check-input' type='radio' name='correct_option' value='{{ $i }}' {{ (isset($question) && isset($question->options[$i]) && $question->options[$i]->is_correct) ? 'checked' : ($i == 0 && !isset($question) ? 'checked' : '') }} required>
                <label class='form-check-label'>Correct</label>
            </div>
        </div>
    </div>
@endfor

            <button type='submit' class='btn btn-primary mt-3'>Update Question & Options</button>
        </form>
    </div>
</div>
@endsection
