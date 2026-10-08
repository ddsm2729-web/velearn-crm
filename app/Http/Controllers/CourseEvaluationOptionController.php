<?php

namespace App\Http\Controllers;

use App\Models\CourseEvaluationOption;
use App\Models\CourseEvaluationQuestion;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseEvaluationOptionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Course Evaluation Option List|Course Evaluation Option Create|Course Evaluation Option Edit|Course Evaluation Option Delete', only: ['index','show']),
            new Middleware('permission:Course Evaluation Option Create', only: ['create','store']),
            new Middleware('permission:Course Evaluation Option Edit', only: ['edit','update']),
            new Middleware('permission:Course Evaluation Option Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $options = CourseEvaluationOption::with('question')->get();
        return view('course_evaluation_options.index', compact('options'));
    }

    public function create()
    {
        $questions = CourseEvaluationQuestion::all();
        return view('course_evaluation_options.create', compact('questions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_evaluation_question_id' => 'required|exists:course_evaluation_questions,id',
            'option' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        CourseEvaluationOption::create($request->all());
        return redirect()->route('course-evaluation-options.index')->with('success', 'Option created successfully.');
    }

    public function show($id)
    {
        $courseEvaluationOption = CourseEvaluationOption::findOrFail($id);
        return view('course_evaluation_options.show', compact('courseEvaluationOption'));
    }

    public function edit($id)
    {
        $courseEvaluationOption = CourseEvaluationOption::findOrFail($id);
        $questions = CourseEvaluationQuestion::all();
        return view('course_evaluation_options.edit', compact('courseEvaluationOption', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_evaluation_question_id' => 'required|exists:course_evaluation_questions,id',
            'option' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        $courseEvaluationOption = CourseEvaluationOption::findOrFail($id);
        
        $data = $request->all();
        $data['is_correct'] = $request->has('is_correct');
        
        $courseEvaluationOption->update($data);
        return redirect()->route('course-evaluation-options.index')->with('success', 'Option updated successfully.');
    }

    public function destroy($id)
    {
        $courseEvaluationOption = CourseEvaluationOption::findOrFail($id);
        $courseEvaluationOption->delete();
        return redirect()->route('course-evaluation-options.index')->with('success', 'Option deleted successfully.');
    }
}
