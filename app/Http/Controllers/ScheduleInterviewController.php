<?php

namespace App\Http\Controllers;

use App\Models\ScheduleInterview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleInterviewController extends Controller
{
    /**
     * Store a newly scheduled interview.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'course' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'string', 'max:255'],
            'batchCode' => ['required', 'string', 'max:255'],
            'batchType' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'time' => ['required', 'date_format:H:i'],
            'interviewMode' => ['required', 'string', 'max:50'],
            'meetingLink' => ['nullable', 'url', 'max:500'],
        ]);

        // Meeting link is optional: Only use the link if explicitly provided or generated in the modal
        $meetLink = !empty($validated['meetingLink']) ? $validated['meetingLink'] : null;

        $interview = ScheduleInterview::create([
            'user_id' => $validated['user_id'] ?? null,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'course' => $validated['course'],
            'duration' => $validated['duration'],
            'batch_code' => $validated['batchCode'],
            'batch_type' => $validated['batchType'],
            'company' => $validated['company'],
            'role' => $validated['role'] ?? null,
            'location' => $validated['location'] ?? null,
            'date' => $validated['date'],
            'time' => $validated['time'],
            'interview_mode' => $validated['interviewMode'],
            'meeting_link' => $meetLink,
            'status' => 'scheduled',
        ]);

        // Send dynamic interview confirmation mail to candidate
        if (!empty($interview->email)) {
            try {
                \Illuminate\Support\Facades\Mail::mailer('brevo')
                    ->to($interview->email)
                    ->send(new \App\Mail\InterviewScheduledMail($interview));
                \Illuminate\Support\Facades\Log::info("Brevo Interview call letter sent to {$interview->email} for ID: {$interview->id}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Brevo Interview Mail exception: " . $e->getMessage());
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Interview scheduled successfully. Call letter sent to candidate.',
            'data' => $interview,
        ]);
    }

    /**
     * Update the outcome (attendance, result, feedback) of a scheduled interview.
     */
    public function updateOutcome(Request $request, ScheduleInterview $scheduleInterview)
    {
        $validated = $request->validate([
            'attendance' => ['required', 'in:Attended,Not Attended,No Show'],
            'reason' => ['nullable', 'required_unless:attendance,Attended', 'string', 'max:2000'],
            'result' => ['nullable', 'in:Selected,Rejected,On Hold,Next Round'],
            'hr_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $isAttended = $validated['attendance'] === 'Attended';
        $status = $this->resolveStatus($validated['attendance'], $isAttended ? ($validated['result'] ?? null) : null);

        $scheduleInterview->update([
            'attendance' => $validated['attendance'],
            'reason' => $isAttended ? null : $validated['reason'],
            'result' => $isAttended ? ($validated['result'] ?? null) : null,
            'hr_feedback' => $validated['hr_feedback'] ?? null,
            'status' => $status,
        ]);

        // Auto-generate follow-up leads as specified in Final Placement CRM logic
        if ($isAttended && ($validated['result'] ?? '') === 'On Hold') {
            // Case: Result on hold -> follow up with company in 3 days
            $count = \App\Models\Followup::where('type', 'company')->count() + 1;
            \App\Models\Followup::create([
                'followup_code' => 'FC' . str_pad($count, 3, '0', STR_PAD_LEFT),
                'type' => 'company',
                'student_name' => $scheduleInterview->name,
                'phone' => $scheduleInterview->phone,
                'course' => $scheduleInterview->course,
                'company_name' => $scheduleInterview->company,
                'hr_name' => $scheduleInterview->role ?? 'HR',
                'role' => $scheduleInterview->role,
                'purpose' => 'Ask Interview Status',
                'call_date' => now()->addDays(3)->toDateString(),
                'notes' => 'Result on hold for ' . $scheduleInterview->name . '. ' . ($validated['hr_feedback'] ?? 'HR result awaited.'),
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);
        } elseif (!$isAttended) {
            // Case: Not Attended / No Show -> follow up with student tomorrow to reschedule
            $count = \App\Models\Followup::where('type', 'student')->count() + 1;
            \App\Models\Followup::create([
                'followup_code' => 'FS' . str_pad($count, 3, '0', STR_PAD_LEFT),
                'type' => 'student',
                'student_name' => $scheduleInterview->name,
                'phone' => $scheduleInterview->phone,
                'course' => $scheduleInterview->course,
                'company_name' => $scheduleInterview->company,
                'role' => $scheduleInterview->role,
                'purpose' => 'Inform about Interview',
                'call_date' => now()->addDay()->toDateString(),
                'notes' => 'Student marked as ' . $validated['attendance'] . ' for ' . $scheduleInterview->company . '. Reason: ' . ($validated['reason'] ?? 'Not provided') . '. Reschedule requested.',
                'status' => 'pending',
                'created_by' => auth()->id(),
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Interview outcome updated successfully.',
                'data' => $scheduleInterview->fresh(),
            ]);
        }

        return redirect()->back()->with('success', 'Interview outcome updated successfully.');
    }

    /**
     * Display the full details of a scheduled interview including student academic profile,
     * other interview rounds, and call history logs.
     */
    public function show($id)
    {
        try {
            $realId = decrypt($id);
        } catch (\Exception $e) {
            $realId = $id;
        }

        $scheduleInterview = ScheduleInterview::with(['user', 'courseUser'])->findOrFail($realId);

        // Fetch candidate's all interview timeline records across companies/rounds
        $timeline = ScheduleInterview::where(function ($q) use ($scheduleInterview) {
            if ($scheduleInterview->user_id) {
                $q->where('user_id', $scheduleInterview->user_id);
            }
            if (!empty($scheduleInterview->email)) {
                $q->orWhere('email', $scheduleInterview->email);
            }
            if (!empty($scheduleInterview->phone)) {
                $clean = preg_replace('/[^0-9]/', '', $scheduleInterview->phone);
                if (strlen($clean) >= 7) {
                    $q->orWhere('phone', 'LIKE', '%' . substr($clean, -10) . '%');
                }
            }
        })
        ->orderBy('date', 'desc')
        ->orderBy('time', 'desc')
        ->get();

        // Count attended interviews across all companies
        $attendedCount = $timeline->filter(function ($item) {
            $att = strtolower($item->attendance ?? '');
            $st = strtolower($item->status ?? '');
            return $att === 'attended' || in_array($st, ['attended', 'selected', 'rejected', 'on hold', 'hold']);
        })->count();

        // Company HR contact day-of
        $company = \App\Models\Company::where('company_name', 'LIKE', '%' . trim($scheduleInterview->company) . '%')->first();
        $hrContactDay = $company->hr_contact_number ?? $company->hr_phone ?? 'N/A';

        // Call logs for this student
        $callLogs = collect();
        if (class_exists(\App\Models\CallLog::class)) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $scheduleInterview->phone ?? '');
            $last10 = substr($cleanPhone, -10);

            $callLogsQuery = \App\Models\CallLog::with('staff');
            if (!empty($last10)) {
                $callLogsQuery->where(function ($q) use ($last10) {
                    $q->where('caller_number', 'LIKE', '%' . $last10 . '%');
                    if (class_exists(\App\Models\Lead::class)) {
                        $leadIds = \App\Models\Lead::where('phone', 'LIKE', '%' . $last10 . '%')->pluck('id');
                        if ($leadIds->isNotEmpty()) {
                            $q->orWhereIn('lead_id', $leadIds);
                        }
                    }
                });
            } else {
                $callLogsQuery->whereRaw('1=0');
            }
            $callLogs = $callLogsQuery->orderBy('created_at', 'desc')->get();
        }

        return view('schedule_interviews.show', compact('scheduleInterview', 'timeline', 'attendedCount', 'hrContactDay', 'callLogs'));
    }

    /**
     * Store a call log entry for the candidate.
     */
    public function storeCallLog(Request $request, $id)
    {
        try {
            $realId = decrypt($id);
        } catch (\Exception $e) {
            $realId = $id;
        }

        $scheduleInterview = ScheduleInterview::findOrFail($realId);
        $request->validate(['note' => 'required|string']);

        $cleanPhone = preg_replace('/[^0-9]/', '', $scheduleInterview->phone ?? '');

        if ($cleanPhone && class_exists(\App\Models\CallLog::class)) {
            $durationSec = 0;
            if ($request->filled('call_duration')) {
                $durationSec = intval($request->call_duration);
            } elseif ($request->filled('duration')) {
                $rawDur = trim($request->duration);
                if (preg_match('/(\d+)m\s*(\d+)s/i', $rawDur, $m)) {
                    $durationSec = intval($m[1]) * 60 + intval($m[2]);
                } elseif (preg_match('/(\d+)m/i', $rawDur, $m)) {
                    $durationSec = intval($m[1]) * 60;
                } elseif (preg_match('/(\d+)s/i', $rawDur, $m)) {
                    $durationSec = intval($m[1]);
                } else {
                    $durationSec = intval($rawDur);
                }
            }

            $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            $staffId = $staff ? $staff->id : auth()->id();

            \App\Models\CallLog::create([
                'caller_number' => $scheduleInterview->phone,
                'note' => $request->note,
                'direction' => $request->input('direction', 'Outgoing'),
                'status' => $request->input('call_status', 'Answered'),
                'call_duration' => $durationSec > 0 ? $durationSec : 180,
                'followup_date' => $request->input('next_followup_date'),
                'staff_id' => $staffId,
                'call_start' => now(),
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Call entry logged successfully.',
                    'redirect_url' => route('schedule-interviews.show', encrypt($scheduleInterview->id))
                ]);
            }

            return redirect()->back()->with('success', 'Call entry logged successfully.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Cannot log call. Candidate phone number is missing.'
            ], 422);
        }

        return redirect()->back()->with('error', 'Cannot log call. Candidate phone number is missing.');
    }

    /**
     * Derive the interview status from attendance and result.
     */
    private function resolveStatus(string $attendance, ?string $result): string
    {
        if ($attendance !== 'Attended') {
            return strtolower($attendance);
        }

        return $result ? strtolower($result) : 'attended';
    }
}
