<?php

namespace App\Http\Controllers;

use App\Models\PlacementEvaluation;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PlacementEvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Placement Evaluation List|Placement Evaluation Create|Placement Evaluation Edit|Placement Evaluation Delete', only: ['index','show']),
            new Middleware('permission:Placement Evaluation Create', only: ['create','store']),
            new Middleware('permission:Placement Evaluation Edit', only: ['edit','update']),
            new Middleware('permission:Placement Evaluation Delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        $evaluations = PlacementEvaluation::with('course')->get();
        return view('placement_evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $existingCourseIds = PlacementEvaluation::pluck('course_id');
        $courses = Course::where('status', 1)
            ->where('course_type', 'live')
            ->whereNotIn('id', $existingCourseIds)
            ->get();
        return view('placement_evaluations.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id|unique:placement_evaluations,course_id',
            'title' => 'nullable|string|max:255',
            'test_timing' => 'required|integer|min:1',
            'correction_type' => 'required|in:auto,tutor',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
        ]);

        $evaluation = PlacementEvaluation::create([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'test_timing' => $validated['test_timing'],
            'correction_type' => $validated['correction_type'],
        ]);

        foreach ($validated['questions'] as $qData) {
            $question = $evaluation->questions()->create([
                'question' => $qData['question'],
                'marks' => $qData['marks'] ?? 1,
                'type' => 'mcq'
            ]);

            foreach ($qData['answers'] as $aIndex => $aData) {
                $question->answers()->create([
                    'answer' => $aData['answer'],
                    'is_correct' => ($aIndex == $qData['correct_answer'])
                ]);
            }
        }

        return redirect()->route('placement-evaluations.index')->with('success', 'Placement Evaluation created successfully.');
    }

    public function show($id)
    {
        $evaluation = PlacementEvaluation::findOrFail($id);
        return view('placement_evaluations.show', compact('evaluation'));
    }

    public function edit($id)
    {
        $evaluation = PlacementEvaluation::with('questions.answers')->findOrFail($id);
        
        $existingCourseIds = PlacementEvaluation::where('id', '!=', $id)->pluck('course_id');
        $courses = Course::where('status', 1)
            ->where('course_type', 'live')
            ->whereNotIn('id', $existingCourseIds)
            ->get();
            
        return view('placement_evaluations.edit', compact('evaluation', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id|unique:placement_evaluations,course_id,' . $id,
            'title' => 'nullable|string|max:255',
            'test_timing' => 'required|integer|min:1',
            'correction_type' => 'required|in:auto,tutor',
            'questions' => 'required|array|min:1',
            'questions.*.id' => 'nullable|integer',
            'questions.*.question' => 'required|string',
            'questions.*.marks' => 'required|integer|min:1',
            'questions.*.answers' => 'required|array|min:2',
            'questions.*.answers.*.id' => 'nullable|integer',
            'questions.*.answers.*.answer' => 'required|string',
            'questions.*.correct_answer' => 'required|integer',
        ]);

        $evaluation = PlacementEvaluation::findOrFail($id);
        $evaluation->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'test_timing' => $validated['test_timing'],
            'correction_type' => $validated['correction_type'],
        ]);

        $submittedQuestionIds = collect($validated['questions'])->pluck('id')->filter()->toArray();
        $evaluation->questions()->whereNotIn('id', $submittedQuestionIds)->delete();

        foreach ($validated['questions'] as $qData) {
            if (!empty($qData['id'])) {
                $question = $evaluation->questions()->find($qData['id']);
                if ($question) {
                    $question->update([
                        'question' => $qData['question'],
                        'marks' => $qData['marks'] ?? 1,
                    ]);
                }
            } else {
                $question = $evaluation->questions()->create([
                    'question' => $qData['question'],
                    'marks' => $qData['marks'] ?? 1,
                    'type' => 'mcq'
                ]);
            }

            if ($question) {
                $submittedAnswerIds = collect($qData['answers'])->pluck('id')->filter()->toArray();
                $question->answers()->whereNotIn('id', $submittedAnswerIds)->delete();

                foreach ($qData['answers'] as $aIndex => $aData) {
                    if (!empty($aData['id'])) {
                        $answer = $question->answers()->find($aData['id']);
                        if ($answer) {
                            $answer->update([
                                'answer' => $aData['answer'],
                                'is_correct' => ($aIndex == $qData['correct_answer'])
                            ]);
                        }
                    } else {
                        $question->answers()->create([
                            'answer' => $aData['answer'],
                            'is_correct' => ($aIndex == $qData['correct_answer'])
                        ]);
                    }
                }
            }
        }

        return redirect()->route('placement-evaluations.index')->with('success', 'Placement Evaluation updated successfully.');
    }

    public function destroy($id)
    {
        $evaluation = PlacementEvaluation::findOrFail($id);
        $evaluation->delete();
        return redirect()->route('placement-evaluations.index')->with('success', 'Placement Evaluation deleted successfully.');
    }
}
