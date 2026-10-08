<?php

namespace App\Http\Controllers;

use App\Models\CurrentOpening;
use App\Models\PlacementApplication;
use App\Models\ScheduleInterview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppliedOpeningController extends Controller
{
    /**
     * Display a listing of openings with applied student statistics and pipeline.
     */
    public function index(Request $request): View
    {
        $query = CurrentOpening::with([
            'company',
            'applications.user',
            'applications.courseUser.course',
            'applications.courseUser.batch',
        ]);

        // Search filter (company name, role, location)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('job_role', 'like', "%{$search}%")
                    ->orWhere('job_location', 'like', "%{$search}%")
                    ->orWhereHas('company', function ($cq) use ($search) {
                        $cq->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Status filter (Show: All, Active, Filled, Hold, Closed, Old)
        if ($request->filled('status') && strtolower($request->status) !== 'all') {
            $query->where('opening_status', $request->status);
        }

        $openings = $query->latest()->paginate(20)->withQueryString();

        // Calculate dynamic counts for each opening
        $openings->getCollection()->transform(function ($opening) {
            $applications = $opening->applications ?? collect();
            $totalApplied = $applications->count();
            $newApplied = $applications->where('is_new', true)->count();
            $sentHr = $applications->where('status', 'sent_hr')->count();

            // Match company name in ScheduleInterview
            $companyName = $opening->company->company_name ?? '';
            $role = $opening->job_role ?? '';

            $interviewQuery = ScheduleInterview::query();
            if ($companyName) {
                $interviewQuery->where('company', $companyName);
            }
            if ($role) {
                $interviewQuery->where('role', $role);
            }

            $interviews = (clone $interviewQuery)->get();
            $scheduledCount = $interviews->whereIn('status', ['scheduled', 're-scheduled'])->count();
            $selectedCount = $interviews->where('status', 'selected')->count();

            $opening->total_applied = $totalApplied;
            $opening->new_applied = $newApplied;
            $opening->sent_hr_count = $sentHr;
            $opening->scheduled_count = $scheduledCount;
            $opening->selected_count = $selectedCount;

            return $opening;
        });

        $totalOpeningsWithApplications = CurrentOpening::has('applications')->count();
        $totalAppliedCount = PlacementApplication::count();

        return view('applied-openings.index', compact('openings', 'totalOpeningsWithApplications', 'totalAppliedCount'));
    }

    /**
     * Get details of a specific opening including its applicants.
     */
    public function show($id): JsonResponse
    {
        $opening = CurrentOpening::with([
            'company',
            'applications.user',
            'applications.courseUser.course',
            'applications.courseUser.batch',
        ])->findOrFail($id);

        return response()->json([
            'status' => true,
            'opening' => $opening,
        ]);
    }

    /**
     * Forward an applicant profile to HR.
     */
    public function forwardToHr(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => ['required', 'integer', 'exists:placement_applications,id'],
        ]);

        $application = PlacementApplication::findOrFail($validated['application_id']);
        $application->update([
            'status' => 'sent_hr',
            'is_new' => false,
            'sent_to_hr_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Student profile successfully forwarded to HR.',
        ]);
    }
}
