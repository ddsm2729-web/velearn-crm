<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssignmentEvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Assignment Evaluate', only: ['evaluate', 'storeEvaluation']),
        ];
    }

    public function evaluate(AssignmentSubmission $submission)
    {
        $submission->load(['assignment', 'student', 'scoreBreakdowns']);
        
        // Render evaluation view modal or page
        // "evaluation only on manual upcoming that part set dynamic"
        // Here we just prepare the submission data
        return view('assignments.evaluate', compact('submission'));
    }

    public function storeEvaluation(Request $request, AssignmentSubmission $submission)
    {
        $validated = $request->validate([
            'grade' => 'required|string',
            'feedback' => 'nullable|string',
            'reupload_approved' => 'required|in:0,1',
            'breakdowns' => 'required|array',
            'breakdowns.*.criterion' => 'required|string',
            'breakdowns.*.max_score' => 'required|numeric',
            'breakdowns.*.obtained_score' => 'required|numeric|min:0',
        ]);

        $totalScore = collect($validated['breakdowns'])->sum('obtained_score');

        $history = is_string($submission->attempts_history) ? json_decode($submission->attempts_history, true) : ($submission->attempts_history ?? []);
        if (!is_array($history)) {
            $history = [];
        }

        $attemptNumber = $submission->attempts;

        $existingAttemptIndex = -1;
        foreach ($history as $index => $item) {
            if (isset($item['attempt']) && $item['attempt'] == $attemptNumber) {
                $existingAttemptIndex = $index;
                break;
            }
        }

        $attemptData = [
            'attempt' => $attemptNumber,
            'evaluated_at' => now()->toDateTimeString(),
            'score' => $totalScore,
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
            'file_path' => strpos($submission->file_path, 'uploads/assignments/') === 0 ? $submission->file_path : 'uploads/assignments/' . $submission->file_path,
            'breakdowns' => $validated['breakdowns']
        ];

        if ($existingAttemptIndex !== -1) {
            $history[$existingAttemptIndex] = $attemptData;
        } else {
            $history[] = $attemptData;
        }

        $submission->update([
            'status' => 'evaluated',
            'score' => $totalScore,
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
            'reupload_approved' => (int) $validated['reupload_approved'],
            'evaluated_by' => auth()->id(), // Assuming staff is logged in
            'attempts_history' => $history
        ]);

        // Sync breakdowns
        $submission->scoreBreakdowns()->delete(); // Clear old ones if re-evaluating
        
        foreach ($validated['breakdowns'] as $breakdown) {
            $submission->scoreBreakdowns()->create($breakdown);
        }

        return redirect()->back()->with('success', 'Evaluation submitted successfully.');
    }
}
