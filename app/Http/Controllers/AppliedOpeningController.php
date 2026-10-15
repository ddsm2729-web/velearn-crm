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
    public function show($id, Request $request)
    {
        $opening = CurrentOpening::with([
            'company',
            'applications.user.placementProfile',
            'applications.courseUser.course',
            'applications.courseUser.batch',
        ])->findOrFail($id);

        if ($request->wantsJson() && !$request->has('view')) {
            return response()->json([
                'status' => true,
                'opening' => $opening,
            ]);
        }

        $applications = $opening->applications ?? collect();
        $companyName = $opening->company->company_name ?? '';
        $role = $opening->job_role ?? '';

        // Match company and role interviews
        $interviewQuery = ScheduleInterview::query();
        if ($companyName) {
            $interviewQuery->where('company', $companyName);
        }
        if ($role) {
            $interviewQuery->where('role', $role);
        }
        $interviews = $interviewQuery->latest()->get();

        $pendingApplicants = $applications->where('status', 'applied');
        $sentHrApplicants = $applications->where('status', 'sent_hr');

        $byTag = [
            'scheduled' => $interviews->whereIn('status', ['scheduled', 're-scheduled']),
            'attended' => $interviews->where('status', 'attended'),
            'selected' => $interviews->where('status', 'selected'),
            'rejected' => $interviews->where('status', 'rejected'),
            'hold' => $interviews->whereIn('status', ['hold', 'on hold']),
        ];

        $tabCounts = [
            'all' => $applications->count(),
            'applied' => $pendingApplicants->count(),
            'sent_hr' => $sentHrApplicants->count(),
            'scheduled' => $byTag['scheduled']->count(),
            'attended' => $byTag['attended']->count(),
            'selected' => $byTag['selected']->count(),
            'rejected' => $byTag['rejected']->count(),
            'hold' => $byTag['hold']->count(),
        ];

        return view('applied-openings.show', compact(
            'opening',
            'applications',
            'pendingApplicants',
            'sentHrApplicants',
            'interviews',
            'byTag',
            'tabCounts'
        ));
    }

    /**
     * Forward an applicant profile to HR with dynamic email notification.
     */
    public function forwardToHr(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => ['required', 'integer', 'exists:placement_applications,id'],
            'hr_email' => ['required', 'email'],
            'link_type' => ['nullable', 'string', 'in:combined,resume,portfolio,project'],
            'subject' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $application = PlacementApplication::with([
            'user.placementProfile',
            'courseUser.course',
            'courseUser.batch',
            'opening.company'
        ])->findOrFail($validated['application_id']);

        $hrEmail = $validated['hr_email'];
        $linkType = $validated['link_type'] ?? 'combined';
        $subject = $validated['subject'] ?: ('Candidate Profile for ' . ($application->opening->job_role ?? 'Position'));
        $remarks = $validated['remarks'] ?? '';

        $emailDispatched = false;
        try {
            \Illuminate\Support\Facades\Mail::mailer('brevo')
                ->to($hrEmail)
                ->send(new \App\Mail\ForwardProfileToHrMail($application, $linkType, $subject, $remarks));

            \Illuminate\Support\Facades\Log::info("Brevo Candidate Profile forwarded successfully to {$hrEmail} for app ID: {$application->id}");
            $emailDispatched = true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Brevo forwardToHr Mail exception: " . $e->getMessage());
        }

        $application->update([
            'status' => 'sent_hr',
            'is_new' => false,
            'sent_to_hr_at' => now(),
        ]);

        $msg = $emailDispatched 
            ? "Student profile forwarded to HR ({$hrEmail}) successfully."
            : "Student marked as Sent to HR, but email delivery encountered an issue (check logs).";

        return response()->json([
            'status' => true,
            'message' => $msg,
            'email_sent' => $emailDispatched,
        ]);
    }
}
