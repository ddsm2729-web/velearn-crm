<?php

namespace App\Http\Controllers;

use App\Models\CourseEvaluationQuestion;
use App\Models\CourseEvaluationOption;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseEvaluationQuestionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Course Evaluation Question List|Course Evaluation Question Create|Course Evaluation Question Edit|Course Evaluation Question Delete', only: ['index','show']),
            new Middleware('permission:Course Evaluation Question Create', only: ['create','store']),
            new Middleware('permission:Course Evaluation Question Edit', only: ['edit','update']),
            new Middleware('permission:Course Evaluation Question Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $questions = CourseEvaluationQuestion::with(['course', 'options'])->get();
        return view('course_evaluation_questions.index', compact('questions'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('course_evaluation_questions.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question' => 'required|string',
            'marks' => 'required|integer',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer'
        ]);

        $question = CourseEvaluationQuestion::create([
            'course_id' => $request->course_id,
            'question' => $request->question,
            'marks' => $request->marks,
        ]);

        foreach ($request->options as $index => $optText) {
            if (!empty($optText)) {
                CourseEvaluationOption::create([
                    'course_evaluation_question_id' => $question->id,
                    'option' => $optText,
                    'is_correct' => ($index == $request->correct_option)
                ]);
            }
        }

        return redirect()->route('course-evaluation-questions.index')->with('success', 'Question created successfully.');
    }

    public function show($id)
    {
        $question = CourseEvaluationQuestion::with('options')->findOrFail($id);
        return view('course_evaluation_questions.show', compact('question'));
    }

    public function edit($id)
    {
        $question = CourseEvaluationQuestion::with('options')->findOrFail($id);
        $courses = Course::all();
        return view('course_evaluation_questions.edit', compact('question', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'question' => 'required|string',
            'marks' => 'required|integer',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer'
        ]);

        $question = CourseEvaluationQuestion::findOrFail($id);
        $question->update([
            'course_id' => $request->course_id,
            'question' => $request->question,
            'marks' => $request->marks,
        ]);

        // Recreate options
        $question->options()->delete();
        
        foreach ($request->options as $index => $optText) {
            if (!empty($optText)) {
                CourseEvaluationOption::create([
                    'course_evaluation_question_id' => $question->id,
                    'option' => $optText,
                    'is_correct' => ($index == $request->correct_option)
                ]);
            }
        }

        return redirect()->route('course-evaluation-questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy($id)
    {
        $question = CourseEvaluationQuestion::findOrFail($id);
        $question->delete();
        return redirect()->route('course-evaluation-questions.index')->with('success', 'Question deleted successfully.');
    }
}
