<?php

namespace App\Http\Controllers;

use App\Models\MainProject;
use App\Models\MainProjectSubmission;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MainProjectEvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Main Project Evaluate', only: ['evaluate', 'storeEvaluation']),
        ];
    }

    public function evaluate(MainProjectSubmission $submission)
    {
        $submission->load(['main_project', 'student', 'scoreBreakdowns']);
        
        // Render evaluation view modal or page
        // "evaluation only on manual upcoming that part set dynamic"
        // Here we just prepare the submission data
        return view('main_projects.evaluate', compact('submission'));
    }

    public function storeEvaluation(Request $request, MainProjectSubmission $submission)
    {
        $validated = $request->validate([
            'grade' => 'required|string',
            'feedback' => 'nullable|string',
            'breakdowns' => 'required|array',
            'breakdowns.*.criterion' => 'required|string',
            'breakdowns.*.max_score' => 'required|numeric',
            'breakdowns.*.obtained_score' => 'required|numeric|min:0',
            'reupload_approved' => 'required|in:0,1',
        ]);

        $totalScore = collect($validated['breakdowns'])->sum('obtained_score');

        $submission->update([
            'status' => 'evaluated',
            'score' => $totalScore,
            'grade' => $validated['grade'],
            'feedback' => $validated['feedback'],
            'evaluated_by' => auth()->id(), // Assuming staff is logged in
            'reupload_approved' => ($submission->attempts < 3) ? $validated['reupload_approved'] : 0,
        ]);

        // Sync breakdowns
        $submission->scoreBreakdowns()->delete(); // Clear old ones if re-evaluating
        
        foreach ($validated['breakdowns'] as $breakdown) {
            $submission->scoreBreakdowns()->create($breakdown);
        }

        return redirect()->back()->with('success', 'Evaluation submitted successfully.');
    }
}
