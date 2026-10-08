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
            'meeting_link' => $validated['meetingLink'] ?? null,
            'status' => 'scheduled',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Interview scheduled successfully.',
            'data' => $interview,
        ]);
    }

    /**
     * Update the outcome (attendance, result, feedback) of a scheduled interview.
     */
    public function updateOutcome(Request $request, ScheduleInterview $scheduleInterview): JsonResponse
    {
        $validated = $request->validate([
            'attendance' => ['required', 'in:Attended,Not Attended,No Show'],
            'reason' => ['nullable', 'required_unless:attendance,Attended', 'string', 'max:2000'],
            'result' => ['nullable', 'in:Selected,Rejected,On Hold,Next Round'],
            'hr_feedback' => ['nullable', 'string', 'max:2000'],
        ]);

        $isAttended = $validated['attendance'] === 'Attended';

        $scheduleInterview->update([
            'attendance' => $validated['attendance'],
            'reason' => $isAttended ? null : $validated['reason'],
            'result' => $isAttended ? ($validated['result'] ?? null) : null,
            'hr_feedback' => $validated['hr_feedback'] ?? null,
            'status' => $this->resolveStatus($validated['attendance'], $isAttended ? ($validated['result'] ?? null) : null),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Interview outcome updated successfully.',
            'data' => $scheduleInterview->fresh(),
        ]);
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
