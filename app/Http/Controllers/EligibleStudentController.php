<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EligibleStudentController extends Controller
{
    public function index(Request $request)
    {
        $courses = \App\Models\Course::where('course_type', 'live')->orderBy('title')->get(['id', 'title']);
        
        $query = \App\Models\CourseUser::with(['user', 'course', 'batch'])
            ->whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })
            ->whereNotNull('batch_id');

        // Apply filters
        if ($request->filled('course_id') && $request->course_id !== 'all') {
            $query->where('course_id', $request->course_id);
        }
        
        if ($request->filled('batch_id') && $request->batch_id !== 'all') {
            $query->where('batch_id', $request->batch_id);
        }

        // Only get completed batches per requirement if strictly needed,
        // but typically Eligible Students have status = 'Completed'.
        // For now, let's fetch those who are in a batch, and we will calculate progress.
        $students = $query->paginate(20);

        // Attach calculated scores to each student in the paginator collection
        foreach ($students->getCollection() as $courseUser) {
            $scores = $this->calculateScores($courseUser->auth_id, $courseUser->course_id, $courseUser->batch_id);
            $courseUser->calc_progress = $scores['progress'];
            $courseUser->calc_overall = $scores['overall'];
        }

        return view('eligible-students.index', compact('students', 'courses'));
    }

    private function calculateScores($userId, $courseId, $batchId)
    {
        // 1. Eligibility (overall_score)
        $assignmentScore = 0;
        $miniProjectScore = 0;
        $mainProjectScore = 0;

        $assignmentIds = \App\Models\Assignment::where('status', 'active')
            ->where('course_id', $courseId)
            ->where(function ($q) use ($batchId) {
                $q->whereNull('batch_id');
                if ($batchId) $q->orWhere('batch_id', $batchId);
            })->pluck('id');
            
        $assignmentSubmissions = \App\Models\AssignmentSubmission::where('user_id', $userId)
            ->whereIn('assignment_id', $assignmentIds)->get();
            
        if ($assignmentSubmissions->count() > 0) {
            $assignmentScore = round($assignmentSubmissions->sum('score') / $assignmentSubmissions->count());
        }

        $miniProjects = \App\Models\MiniProject::where('status', 'active')
            ->where('course_id', $courseId)
            ->where(function ($q) use ($batchId) {
                $q->whereNull('batch_id');
                if ($batchId) $q->orWhere('batch_id', $batchId);
            })->get();
            
        if ($miniProjects->count() > 0) {
            $miniScores = [];
            foreach ($miniProjects as $mp) {
                $sub = \App\Models\MiniProjectSubmission::where('user_id', $userId)->where('mini_project_id', $mp->id)->first();
                $miniScores[] = ($sub && $sub->score !== null) ? $sub->score : 0;
            }
            $miniProjectScore = round(array_sum($miniScores) / count($miniScores));
        }

        $mainProjects = \App\Models\MainProject::where('status', 'active')
            ->where('course_id', $courseId)
            ->where(function ($q) use ($batchId) {
                $q->whereNull('batch_id');
                if ($batchId) $q->orWhere('batch_id', $batchId);
            })->get();
            
        if ($mainProjects->count() > 0) {
            $mainScores = [];
            foreach ($mainProjects as $mp) {
                $subs = \App\Models\MainProjectSubmission::where('user_id', $userId)->where('main_project_id', $mp->id)->get();
                $mpTotal = 0;
                $mpCount = 0;
                foreach ($subs as $sub) {
                    if ($sub->score !== null) {
                        $mpTotal += $sub->score;
                        $mpCount++;
                    }
                }
                $mainScores[] = $mpCount > 0 ? round($mpTotal / $mpCount) : 0;
            }
            $mainProjectScore = round(array_sum($mainScores) / count($mainScores));
        }

        $eligibilityOverall = round(($assignmentScore + $miniProjectScore + $mainProjectScore) / 3, 1);

        // 2. Evaluation / Test Percentage
        $evalPercentage = 0;
        $evaluation = \App\Models\PlacementEvaluation::with('questions')
            ->where('course_id', $courseId)->first();
            
        if ($evaluation) {
            $studentAnswers = \App\Models\PlacementEvaluationStudentAnswer::where('user_id', $userId)
                ->where('placement_evaluation_id', $evaluation->id)->get();
            if ($studentAnswers->count() > 0) {
                $score = $studentAnswers->sum('marks_obtained');
                $maxScore = $evaluation->questions->sum('marks');
                if ($maxScore > 0) {
                    $evalPercentage = round(($score / $maxScore) * 100);
                }
            }
        }

        // 3. Cumulative Overall
        $cumulativeOverall = round(($eligibilityOverall + $evalPercentage) / 2);

        return [
            'progress' => $evalPercentage,
            'overall' => $cumulativeOverall
        ];
    }

    public function getBatches(Request $request)
    {
        $courseId = $request->get('course_id');
        $batches = \App\Models\Batch::where('course_id', $courseId)->orderBy('batch_name')->get(['id', 'batch_name', 'batch_code']);
        return response()->json($batches);
    }

    public function show($id)
    {
        $courseUser = \App\Models\CourseUser::with(['user', 'course.curricula', 'batch'])->findOrFail($id);
        
        $scores = $this->calculateScores($courseUser->auth_id, $courseUser->course_id, $courseUser->batch_id);
        $courseUser->calc_progress = $scores['progress'];
        $courseUser->calc_overall = $scores['overall'];
        
        $placementProfile = \App\Models\PlacementProfile::where('user_id', $courseUser->auth_id)->first();
        
        $mainProject = \App\Models\MainProject::where('course_id', $courseUser->course_id)
            ->where(function ($q) use ($courseUser) {
                $q->whereNull('batch_id')->orWhere('batch_id', $courseUser->batch_id);
            })->first();
            
        $mainProjectSubmission = null;
        if ($mainProject) {
            $mainProjectSubmission = \App\Models\MainProjectSubmission::where('user_id', $courseUser->auth_id)
                ->where('main_project_id', $mainProject->id)->first();
        }
        
        $userObj = $courseUser->user;
        $lead = null;
        if ($userObj) {
            $lead = \App\Models\Lead::where('email', $userObj->email)
                ->orWhere('phone', $userObj->phonenumber)
                ->orWhere('whatsapp', $userObj->phonenumber)
                ->with(['activities.staff', 'activities.tag', 'activities.status', 'callLogs.staff'])
                ->first();
        }

        $timeline = collect();
        $latestActivityId = null;

        if ($lead) {
            foreach ($lead->activities as $activity) {
                $timeline->push([
                    'type' => 'activity',
                    'timestamp' => $activity->created_at,
                    'data' => $activity
                ]);
            }
            foreach ($lead->callLogs as $callLog) {
                $timeline->push([
                    'type' => 'call_log',
                    'timestamp' => \Carbon\Carbon::parse($callLog->created_at),
                    'data' => $callLog
                ]);
            }
            $timeline = $timeline->sortByDesc(function($item) {
                return $item['timestamp']->getTimestamp();
            })->values();

            foreach($timeline as $t) {
                if($t['type'] === 'activity') {
                    $latestActivityId = $t['data']->id;
                    break;
                }
            }
        }

        $userAuth = auth()->user();
        if ($userAuth->hasRole('SuperAdmin')) {
            $statuses = \App\Models\LeadStatus::all();
            $tags = \App\Models\LeadTag::orderBy('name')->get();
        } else {
            $userRoleNames = $userAuth->getRoleNames();
            $statuses = \App\Models\LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = \App\Models\LeadStatus::all();
            }
            $tags = \App\Models\LeadTag::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            if ($tags->isEmpty()) {
                $tags = \App\Models\LeadTag::orderBy('name')->get();
            }
        }
        
        $admissionOfficers = \App\Models\Staff::whereHas('role', function($q) {
            $q->where('name', 'Admission Officer');
        })->get();
        
        $lmsFeedOpenings = \App\Models\CurrentOpening::with('company')
            ->where('opening_status', 'Active') // assuming 'Active' is a valid status, if not we could just use latest()
            ->latest()
            ->take(5)
            ->get();

        $interviewOpenings = \App\Models\CurrentOpening::with('company')
            ->where('opening_status', 'Active')
            ->latest()
            ->get();

        $scheduledInterviews = \App\Models\ScheduleInterview::where('user_id', $courseUser->auth_id)
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();
        
        return view('eligible-students.show', compact('courseUser', 'placementProfile', 'mainProject', 'mainProjectSubmission', 'lead', 'timeline', 'latestActivityId', 'tags', 'statuses', 'admissionOfficers', 'lmsFeedOpenings', 'interviewOpenings', 'scheduledInterviews'));
    }

    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'resume_status' => 'required|in:pending,verified,rejected,changes_needed',
            'resume_feedback' => 'nullable|string',
            'portfolio_status' => 'required|in:pending,verified,rejected,changes_needed',
            'portfolio_feedback' => 'nullable|string',
            'project_docs_status' => 'required|in:pending,verified,rejected,changes_needed',
            'project_docs_feedback' => 'nullable|string',
            'github_status' => 'required|in:pending,verified,rejected,changes_needed',
            'github_feedback' => 'nullable|string',
            'overall_status' => 'required|in:pending,verified,rejected,approved,changes_needed',
        ]);

        $courseUser = \App\Models\CourseUser::findOrFail($id);
        $placementProfile = \App\Models\PlacementProfile::where('user_id', $courseUser->auth_id)->first();

        if ($placementProfile) {
            $placementProfile->update([
                'resume_status' => $request->resume_status,
                'resume_feedback' => $request->resume_feedback,
                'portfolio_status' => $request->portfolio_status,
                'portfolio_feedback' => $request->portfolio_feedback,
                'project_docs_status' => $request->project_docs_status,
                'project_docs_feedback' => $request->project_docs_feedback,
                'github_status' => $request->github_status,
                'github_feedback' => $request->github_feedback,
                'overall_status' => $request->overall_status,
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ]);
        }

        return redirect()->back()->with('success', 'Profile review updated successfully.');
    }
}
