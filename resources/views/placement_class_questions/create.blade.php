@extends('layouts.header')
@section('pageTitle', 'Create Placement Class Question')
@section('section')
    <div class='row mb-2 mb-xl-3'>
        <div class='col-auto d-none d-sm-block'>
            <h3><strong>Create Placement Class Question</strong></h3>
        </div>
        <div class='col-auto ms-auto text-end mt-n1'>
            <a href='{{ route('placement-class-questions.index') }}' class='btn btn-secondary shadow-sm'>Back</a>
        </div>
    </div>
    <div class='card'>
        <div class='card-body'>
            <form action='{{ route('placement-class-questions.store') }}' method='POST'>
                @csrf
                <div class='mb-3'><label>Placement Day</label><select name='placement_class_id' class='form-control'
                        required>
                        <option value="" disabled {{ !isset($selectedClassId) ? 'selected' : '' }}>Choose Day</option>
                        @foreach($classes as $class)
                            <option value='{{ $class->id }}' {{ (isset($selectedClassId) && $selectedClassId == $class->id) ? 'selected' : '' }}>Day {{ $class->day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class='mb-3'><label>Question Text</label><textarea name='question' class='form-control'
                        required>{{ $question->question ?? '' }}</textarea></div>
                <div class='mb-3'><label>Marks</label><input type='number' name='marks' class='form-control'
                        value='{{ $question->marks ?? 1 }}' required></div>

                <h5 class='mt-4'>Options</h5>
                @for ($i = 0; $i < 4; $i++)
                    <div class='row mb-2 align-items-center'>
                        <div class='col-10'>
                            <input type='text' name='options[{{ $i }}]' class='form-control' placeholder='Option {{ $i + 1 }}'
                                value='{{ isset($question) && isset($question->options[$i]) ? $question->options[$i]->option : '' }}'>
                        </div>
                        <div class='col-2'>
                            <div class='form-check'>
                                <input class='form-check-input' type='radio' name='correct_option' value='{{ $i }}' {{ (isset($question) && isset($question->options[$i]) && $question->options[$i]->is_correct) ? 'checked' : ($i == 0 && !isset($question) ? 'checked' : '') }} required>
                                <label class='form-check-label'>Correct</label>
                            </div>
                        </div>
                    </div>
                @endfor

                <button type='submit' class='btn btn-success mt-3'>Save Question & Options</button>
            </form>
        </div>
    </div>
@endsection