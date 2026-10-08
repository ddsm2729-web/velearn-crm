@extends('layouts.header')
@section('pageTitle', 'Edit Placement Evaluation')
@section('section')
<div class='row mb-2 mb-xl-3'>
    <div class='col-auto d-none d-sm-block'>
        <h3><strong>Edit Placement Evaluation</strong></h3>
    </div>
</div>
<div class='row'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-body'>
                <form action="{{ route('placement-evaluations.update', $evaluation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ $evaluation->course_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $evaluation->title }}" placeholder="Optional Evaluation Title">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Test Timing (Minutes)</label>
                        <input type="number" name="test_timing" class="form-control" value="{{ $evaluation->test_timing }}" required min="1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correction Type</label>
                        <select name="correction_type" class="form-select" required>
                            <option value="auto" {{ $evaluation->correction_type == 'auto' ? 'selected' : '' }}>Auto</option>
                            <option value="tutor" {{ $evaluation->correction_type == 'tutor' ? 'selected' : '' }}>Tutor</option>
                        </select>
                    </div>

                    <hr class="my-4">
                    <h4>Questions</h4>
                    <div id="questions-container">
                    </div>
                    <div class="mb-4">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="addQuestion()">
                            <i class="align-middle" data-feather="plus"></i> Add Question
                        </button>
                    </div>

                    <hr>
                    <div class="d-flex gap-2 mb-3">
                        <button type="submit" class="btn btn-primary">Update Evaluation</button>
                        <a href="{{ route('placement-evaluations.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    let questionCount = 0;
    const existingQuestions = @json($evaluation->questions);

    function addQuestion(existingData = null) {
        const container = document.getElementById('questions-container');
        const qIndex = questionCount++;
        const questionText = existingData ? existingData.question : '';
        const marks = existingData ? existingData.marks : 1;
        const questionId = existingData ? existingData.id : '';

        const html = `
            <div class="card shadow-sm mb-4 border-primary question-card" id="question-${qIndex}">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">Question ${qIndex + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm" onclick="removeQuestion(${qIndex})">
                        <i class="align-middle" data-feather="trash-2"></i> Remove
                    </button>
                </div>
                <div class="card-body bg-light">
                    <input type="hidden" name="questions[${qIndex}][id]" value="${questionId}">
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label class="form-label fw-bold">Question Text</label>
                            <textarea name="questions[${qIndex}][question]" class="form-control" rows="2" placeholder="Enter your question here..." required>${questionText}</textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label fw-bold">Marks</label>
                            <input type="number" name="questions[${qIndex}][marks]" class="form-control" value="${marks}" required min="1">
                        </div>
                    </div>
                    <hr>
                    <h6 class="fw-bold mb-3 text-secondary">Answers Options</h6>
                    <div id="answers-container-${qIndex}" class="mb-3">
                        <!-- Default answers -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAnswer(${qIndex})">
                        <i class="align-middle" data-feather="plus"></i> Add Option
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);

        if (existingData && existingData.answers && existingData.answers.length > 0) {
            existingData.answers.forEach((ans, aIndex) => {
                addAnswer(qIndex, ans, aIndex, ans.is_correct);
            });
        } else {
            addAnswer(qIndex);
            addAnswer(qIndex);
        }
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }

    function removeQuestion(index) {
        document.getElementById('question-' + index).remove();
    }

    function addAnswer(qIndex, existingAns = null, existingIndex = null, isCorrect = false) {
        const container = document.getElementById('answers-container-' + qIndex);
        const aCount = container.children.length;
        const answerText = existingAns ? existingAns.answer : '';
        const answerId = existingAns ? existingAns.id : '';
        const checked = isCorrect ? 'checked' : '';

        const html = `
            <div class="d-flex align-items-center mb-2 answer-row">
                <input type="hidden" name="questions[${qIndex}][answers][${aCount}][id]" value="${answerId}">
                <div class="form-check me-3">
                    <input class="form-check-input" type="radio" name="questions[${qIndex}][correct_answer]" value="${aCount}" required title="Mark as correct answer" ${checked}>
                </div>
                <input type="text" name="questions[${qIndex}][answers][${aCount}][answer]" class="form-control me-2" required placeholder="Option text" value="${answerText}">
                <button type="button" class="btn btn-sm btn-danger px-3" onclick="this.parentElement.remove()" title="Remove Option">X</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const questions = document.querySelectorAll('.question-card');
        if (questions.length === 0) {
            e.preventDefault();
            toastr.error('You must add at least 1 question.');
            return;
        }

        let isValid = true;
        questions.forEach((q, index) => {
            const answers = q.querySelectorAll('.answer-row');
            if (answers.length < 2) {
                toastr.error(`Question ${index + 1} must have at least 2 answers.`);
                isValid = false;
            }
            
            const checkedRadio = q.querySelector('input[type="radio"]:checked');
            if (!checkedRadio) {
                toastr.error(`Please select the correct answer for Question ${index + 1}.`);
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });

    if (existingQuestions.length > 0) {
        existingQuestions.forEach(q => addQuestion(q));
    } else {
        addQuestion();
    }
</script>
@endpush
