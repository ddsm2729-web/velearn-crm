<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseUser;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\MiniProject;
use App\Models\MiniProjectSubmission;
use App\Models\MainProject;
use App\Models\MainProjectSubmission;

class PlacementAPIController extends Controller
{
    public function getEligibilityScores(Request $request, $userId)
    {
        $enrollment = CourseUser::where('auth_id', $userId)
            ->whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })->first();
        
        $assignmentScore = 0;
        $miniProjectScore = 0;
        $mainProjectScore = 0;

        if ($enrollment && $enrollment->course_id) {
            // Assignment Score
            $assignmentQuery = Assignment::where('status', 'active')
                ->where('course_id', $enrollment->course_id)
                ->where(function ($query) use ($enrollment) {
                    $query->whereNull('batch_id');
                    if ($enrollment->batch_id) {
                        $query->orWhere('batch_id', $enrollment->batch_id);
                    }
                });
            
            $assignmentIds = $assignmentQuery->pluck('id');
            $assignmentSubmissions = AssignmentSubmission::where('user_id', $userId)
                ->whereIn('assignment_id', $assignmentIds)
                ->get();
            
            $assignmentCount = $assignmentSubmissions->count();
            if ($assignmentCount > 0) {
                $assignmentScore = round($assignmentSubmissions->sum('score') / $assignmentCount);
            }

            // Mini Project Score
            $miniProjects = MiniProject::where('status', 'active')
                ->where('course_id', $enrollment->course_id)
                ->where(function ($query) use ($enrollment) {
                    $query->whereNull('batch_id');
                    if ($enrollment->batch_id) {
                        $query->orWhere('batch_id', $enrollment->batch_id);
                    }
                })->get();
            
            $miniScores = [];
            foreach ($miniProjects as $mp) {
                $sub = MiniProjectSubmission::where('user_id', $userId)->where('mini_project_id', $mp->id)->first();
                if ($sub && $sub->score !== null) {
                    $miniScores[] = $sub->score;
                } else {
                    $miniScores[] = 0;
                }
            }
            if (count($miniScores) > 0) {
                $miniProjectScore = round(array_sum($miniScores) / count($miniScores));
            }

            // Main Project Score
            $mainProjects = MainProject::where('status', 'active')
                ->where('course_id', $enrollment->course_id)
                ->where(function ($query) use ($enrollment) {
                    $query->whereNull('batch_id');
                    if ($enrollment->batch_id) {
                        $query->orWhere('batch_id', $enrollment->batch_id);
                    }
                })->get();

            $mainScores = [];
            foreach ($mainProjects as $mp) {
                $submissions = MainProjectSubmission::where('user_id', $userId)->where('main_project_id', $mp->id)->get();
                $mpTotal = 0;
                $mpCount = 0;
                foreach ($submissions as $sub) {
                    if ($sub->score !== null) {
                        $mpTotal += $sub->score;
                        $mpCount++;
                    }
                }
                if ($mpCount > 0) {
                    $mainScores[] = round($mpTotal / $mpCount);
                } else {
                    $mainScores[] = 0;
                }
            }
            if (count($mainScores) > 0) {
                $mainProjectScore = round(array_sum($mainScores) / count($mainScores));
            }
        }

        $overallScore = round(($assignmentScore + $miniProjectScore + $mainProjectScore) / 3, 1);
        $isEligible = $overallScore >= 80;

        return response()->json([
            'status' => 'success',
            'data' => [
                'assignment' => $assignmentScore,
                'mini_project' => $miniProjectScore,
                'main_project' => $mainProjectScore,
                'overall_score' => $overallScore,
                'is_eligible' => $isEligible,
                'class_attendance' => 92, // Mocked for now since attendance API isn't fully ready
                'assignment_submission_rate' => 100,
                'doubt_sessions_attended' => '8/10',
                'live_class_participation' => 87
            ]
        ]);
    }

    public function getEvaluationDetails(Request $request, $userId)
    {
        $enrollment = CourseUser::where('auth_id', $userId)
            ->whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })->first();

        if (!$enrollment || !$enrollment->course_id) {
            return response()->json(['status' => 'error', 'message' => 'No active live course found.'], 404);
        }

        $evaluation = \App\Models\PlacementEvaluation::with(['questions.answers' => function($q) {
                $q->select('id', 'placement_evaluation_question_id', 'answer'); // Exclude is_correct from frontend
            }])
            ->where('course_id', $enrollment->course_id)
            ->first();

        if (!$evaluation) {
            return response()->json(['status' => 'error', 'message' => 'No placement evaluation found for this course.'], 404);
        }

        // Check if already submitted
        $alreadySubmitted = \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
            ->where('placement_evaluation_id', $evaluation->id)
            ->exists();

        if ($alreadySubmitted) {
            $studentAnswers = \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
                ->where('placement_evaluation_id', $evaluation->id)
                ->get();
            $score = $studentAnswers->sum('marks_obtained');
            $maxScore = $evaluation->questions->sum('marks');
            $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100) : 0;
            
            // Collect question-level results
            $resultsBreakdown = $studentAnswers->map(function($ans) {
                return [
                    'question_id' => $ans->placement_evaluation_question_id,
                    'selected_answer_id' => $ans->selected_answer_id,
                    'is_correct' => $ans->marks_obtained > 0,
                    'marks_obtained' => $ans->marks_obtained,
                ];
            });

            return response()->json([
                'status' => 'success',
                'message' => 'You have already submitted this evaluation.',
                'data' => [
                    'evaluation' => $evaluation,
                    'already_submitted' => true,
                    'score' => $score,
                    'max_score' => $maxScore,
                    'percentage' => $percentage,
                    'results_breakdown' => $resultsBreakdown,
                ]
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'evaluation' => $evaluation,
                'already_submitted' => false,
            ]
        ]);
    }

    public function submitEvaluation(Request $request, $userId)
    {
        $validated = $request->validate([
            'evaluation_id' => 'required|exists:placement_evaluations,id',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:placement_evaluation_questions,id',
            'answers.*.selected_answer_id' => 'required|exists:placement_evaluation_answers,id',
        ]);

        $evaluation = \App\Models\PlacementEvaluation::findOrFail($validated['evaluation_id']);
        
        $alreadySubmitted = \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
            ->where('placement_evaluation_id', $evaluation->id)
            ->exists();

        if ($alreadySubmitted) {
            return response()->json(['status' => 'error', 'message' => 'Already submitted.'], 400);
        }

        $totalMarksObtained = 0;
        $maxMarks = 0;

        foreach ($validated['answers'] as $ansData) {
            $question = \App\Models\PlacementEvaluationQuestion::find($ansData['question_id']);
            $selectedAnswer = \App\Models\PlacementEvaluationAnswer::find($ansData['selected_answer_id']);

            $marksObtained = 0;
            if ($evaluation->correction_type == 'auto') {
                if ($selectedAnswer && $selectedAnswer->is_correct) {
                    $marksObtained = $question->marks;
                    $totalMarksObtained += $marksObtained;
                }
                $maxMarks += $question->marks;
            }

            \App\Models\PlacementEvaluationStudentAnswer::create([
                'user_id' => $userId,
                'course_id' => $evaluation->course_id,
                'placement_evaluation_id' => $evaluation->id,
                'placement_evaluation_question_id' => $question->id,
                'selected_answer_id' => $selectedAnswer->id ?? null,
                'marks_obtained' => $evaluation->correction_type == 'auto' ? $marksObtained : null,
            ]);
        }

        if ($evaluation->correction_type == 'tutor') {
            return response()->json([
                'status' => 'success',
                'message' => 'Submitted successfully. Waiting for tutor correction within 24 hours.',
                'correction_type' => 'tutor'
            ]);
        }

        $percentage = $maxMarks > 0 ? round(($totalMarksObtained / $maxMarks) * 100) : 0;
        
        $resultsBreakdown = \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
            ->where('placement_evaluation_id', $evaluation->id)
            ->get()->map(function($ans) {
                return [
                    'question_id' => $ans->placement_evaluation_question_id,
                    'selected_answer_id' => $ans->selected_answer_id,
                    'is_correct' => $ans->marks_obtained > 0,
                    'marks_obtained' => $ans->marks_obtained,
                ];
            });

        return response()->json([
            'status' => 'success',
            'message' => 'Submitted successfully.',
            'correction_type' => 'auto',
            'score' => $totalMarksObtained,
            'max_score' => $maxMarks,
            'percentage' => $percentage,
            'results_breakdown' => $resultsBreakdown
        ]);
    }

    public function retestEvaluation(Request $request, $userId)
    {
        $validated = $request->validate([
            'evaluation_id' => 'required|exists:placement_evaluations,id',
        ]);

        \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
            ->where('placement_evaluation_id', $validated['evaluation_id'])
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Previous evaluation answers cleared for retest.'
        ]);
    }
}
