<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchSchedule;
use App\Models\BatchAttendance;
use App\Models\CourseUser;
use App\Models\Admission;
use App\Models\User;
use App\Services\ZoomService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZoomAttendanceController extends Controller
{
    protected $zoomService;

    public function __construct(ZoomService $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    /**
     * Handle incoming Zoom Webhook events
     * Handles both endpoint.url_validation and meeting.ended
     */
    public function handleWebhook(Request $request)
    {
        $event = $request->input('event');
        Log::info("Zoom Webhook received event: {$event}", [
            'payload' => $request->all(),
        ]);

        // 1. Zoom Webhook URL Validation (CRC Challenge)
        if ($event === 'endpoint.url_validation') {
            $plainToken = $request->input('payload.plainToken');
            if ($plainToken) {
                $response = $this->zoomService->generateWebhookTokenResponse($plainToken);
                return response()->json($response, 200);
            }
            return response()->json(['error' => 'Missing plainToken'], 400);
        }

        // 2. Zoom Meeting Ended Event
        if ($event === 'meeting.ended') {
            $meetingObject = $request->input('payload.object', []);
            $meetingId = $meetingObject['id'] ?? null;
            $durationMinutes = $meetingObject['duration'] ?? null;
            $startTime = $meetingObject['start_time'] ?? null;
            $endTime = $meetingObject['end_time'] ?? null;

            if ($meetingId) {
                Log::info("Processing attendance for ended Zoom meeting ID: {$meetingId}");
                $result = $this->calculateAndMarkAttendance(
                    (string) $meetingId,
                    $durationMinutes,
                    $startTime,
                    $endTime
                );
                return response()->json([
                    'status' => true,
                    'message' => 'Attendance processing completed.',
                    'result' => $result
                ], 200);
            }

            return response()->json(['status' => false, 'message' => 'Meeting ID not found in payload'], 400);
        }

        // Acknowledge other unhandled events
        return response()->json(['status' => true, 'message' => 'Event acknowledged'], 200);
    }

    /**
     * Manual or Programmatic API to trigger attendance processing
     * POST /api/zoom/attendance/process
     * Request body:
     * - meeting_id (string, required or optional if schedule_id given)
     * - batch_schedule_id (optional)
     * - duration_minutes (optional override)
     * - custom_participants (optional array for testing/simulation)
     */
    public function processAttendance(Request $request)
    {
        $request->validate([
            'meeting_id' => 'nullable|string',
            'batch_schedule_id' => 'nullable|exists:batch_schedules,id',
            'duration_minutes' => 'nullable|numeric',
            'custom_participants' => 'nullable|array',
        ]);

        $meetingId = $request->input('meeting_id');
        $scheduleId = $request->input('batch_schedule_id');
        $customDuration = $request->input('duration_minutes');
        $customParticipants = $request->input('custom_participants');

        if (!$meetingId && $scheduleId) {
            $schedule = BatchSchedule::find($scheduleId);
            $meetingId = $schedule ? $schedule->zoom_meeting_id : null;
        }

        if (!$meetingId && !$scheduleId) {
            return response()->json([
                'status' => false,
                'message' => 'Either meeting_id or batch_schedule_id must be provided.'
            ], 422);
        }

        $result = $this->calculateAndMarkAttendance(
            (string) $meetingId,
            $customDuration,
            null,
            null,
            $scheduleId,
            $customParticipants
        );

        return response()->json([
            'status' => $result['success'] ?? false,
            'message' => $result['message'] ?? 'Processed',
            'data' => $result
        ], ($result['success'] ?? false) ? 200 : 400);
    }

    /**
     * Core logic to match batch, fetch participants, calculate attendance percentages,
     * and mark Present (>= 75%), Half Day (>= 45%), or Leave (< 45%) based on registered email.
     */
    public function calculateAndMarkAttendance(
        string $meetingId,
        $durationMinutes = null,
        $startTime = null,
        $endTime = null,
        $explicitScheduleId = null,
        $customParticipants = null
    ) {
        // 1. Identify Batch & BatchSchedule
        $schedule = null;
        if ($explicitScheduleId) {
            $schedule = BatchSchedule::with('batch')->find($explicitScheduleId);
        }

        if (!$schedule && $meetingId) {
            $schedule = BatchSchedule::with('batch')
                ->where('zoom_meeting_id', $meetingId)
                ->latest('date')
                ->first();
        }

        if (!$schedule && $meetingId) {
            $schedule = BatchSchedule::with('batch')
                ->where('meeting_link', 'LIKE', "%{$meetingId}%")
                ->latest('date')
                ->first();
        }

        $batch = null;
        if ($schedule && $schedule->batch) {
            $batch = $schedule->batch;
        } elseif ($meetingId) {
            $batch = Batch::where('meeting_link', 'LIKE', "%{$meetingId}%")->first();
        }

        if (!$batch) {
            $msg = "No matching batch or schedule found for Zoom Meeting ID: {$meetingId}";
            Log::warning($msg);
            return [
                'success' => false,
                'message' => $msg,
            ];
        }

        $attendanceDate = $schedule ? $schedule->date : now()->toDateString();

        // 2. Determine Meeting Total Duration
        $totalMeetingDurationMinutes = 0;

        if ($durationMinutes && (int)$durationMinutes > 0) {
            $totalMeetingDurationMinutes = (int) $durationMinutes;
        }

        // Try getting past meeting details from Zoom API if duration not provided
        if ($totalMeetingDurationMinutes <= 0 && $meetingId) {
            $meetingDetails = $this->zoomService->getPastMeetingDetails($meetingId);
            if ($meetingDetails && !empty($meetingDetails['duration'])) {
                $totalMeetingDurationMinutes = (int) $meetingDetails['duration'];
            } elseif ($meetingDetails && !empty($meetingDetails['total_minutes'])) {
                $totalMeetingDurationMinutes = (int) $meetingDetails['total_minutes'];
            }
        }

        // Fallback: Calculate from schedule start_time and end_time (e.g. "10:00" and "11:30")
        if ($totalMeetingDurationMinutes <= 0 && $schedule && $schedule->start_time && $schedule->end_time) {
            try {
                $start = Carbon::parse($schedule->start_time);
                $end = Carbon::parse($schedule->end_time);
                $diff = $start->diffInMinutes($end);
                if ($diff > 0) {
                    $totalMeetingDurationMinutes = (int) $diff;
                }
            } catch (\Exception $e) {
                // Ignore parse errors
            }
        }

        // Fallback: Parse from batch->class_duration (e.g. "1 hr", "1.5 hrs", "2 hrs")
        if ($totalMeetingDurationMinutes <= 0 && $batch->class_duration) {
            $durationMap = [
                '1 hr' => 60,
                '1.5 hrs' => 90,
                '2 hrs' => 120,
                '2.5 hrs' => 150,
                '3 hrs' => 180,
            ];
            $totalMeetingDurationMinutes = $durationMap[$batch->class_duration] ?? 60;
        }

        // Default fallback: 60 minutes
        if ($totalMeetingDurationMinutes <= 0) {
            $totalMeetingDurationMinutes = 60;
        }

        $totalMeetingDurationSeconds = $totalMeetingDurationMinutes * 60;

        // 3. Fetch Participants from Zoom API (or custom for test)
        if (is_array($customParticipants)) {
            $participants = $customParticipants;
        } else {
            $participants = $this->zoomService->getMeetingParticipants($meetingId);
        }

        Log::info("Zoom meeting {$meetingId} participants count: " . count($participants));

        // Group participant duration by normalized email ID
        // Note: Zoom participants report duration in SECONDS
        $participantStatsByEmail = [];
        foreach ($participants as $p) {
            $email = strtolower(trim($p['user_email'] ?? $p['email'] ?? ''));
            if (!$email) {
                continue;
            }

            $durationSec = (int) ($p['duration'] ?? 0);
            $joinTime = isset($p['join_time']) ? Carbon::parse($p['join_time']) : null;
            $leaveTime = isset($p['leave_time']) ? Carbon::parse($p['leave_time']) : null;

            if (!isset($participantStatsByEmail[$email])) {
                $participantStatsByEmail[$email] = [
                    'name' => $p['name'] ?? null,
                    'total_duration_seconds' => 0,
                    'first_join_time' => $joinTime,
                    'last_leave_time' => $leaveTime,
                ];
            }

            $participantStatsByEmail[$email]['total_duration_seconds'] += $durationSec;

            if ($joinTime && (!$participantStatsByEmail[$email]['first_join_time'] || $joinTime->lt($participantStatsByEmail[$email]['first_join_time']))) {
                $participantStatsByEmail[$email]['first_join_time'] = $joinTime;
            }
            if ($leaveTime && (!$participantStatsByEmail[$email]['last_leave_time'] || $leaveTime->gt($participantStatsByEmail[$email]['last_leave_time']))) {
                $participantStatsByEmail[$email]['last_leave_time'] = $leaveTime;
            }
        }

        // 4. Retrieve All Registered Students for this Batch
        // A student is registered via CourseUser or Admission
        $enrolledStudents = collect();

        // From CourseUser
        $courseUsers = CourseUser::where('batch_id', $batch->id)
            ->with('user')
            ->get();
        foreach ($courseUsers as $cu) {
            if ($cu->user && $cu->user->email) {
                $enrolledStudents->put(strtolower(trim($cu->user->email)), [
                    'id' => $cu->user->id,
                    'name' => $cu->user->name,
                    'email' => strtolower(trim($cu->user->email)),
                ]);
            }
        }

        // From Admissions
        $admissions = Admission::where('batch_id', $batch->id)
            ->with(['lead', 'contact'])
            ->get();
        foreach ($admissions as $adm) {
            $email = null;
            $name = null;
            if ($adm->lead && $adm->lead->email) {
                $email = strtolower(trim($adm->lead->email));
                $name = $adm->lead->name;
            } elseif ($adm->contact && $adm->contact->email) {
                $email = strtolower(trim($adm->contact->email));
                $name = $adm->contact->name;
            }

            if ($email && !$enrolledStudents->has($email)) {
                $user = User::where('email', $email)->first();
                $enrolledStudents->put($email, [
                    'id' => $user ? $user->id : null,
                    'name' => $name ?? ($user ? $user->name : 'Enrolled Student'),
                    'email' => $email,
                ]);
            }
        }

        // Also add any participant who attended and matches a User in the system
        foreach ($participantStatsByEmail as $email => $stat) {
            if (!$enrolledStudents->has($email)) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $enrolledStudents->put($email, [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $email,
                    ]);
                }
            }
        }

        // 5. Evaluate Attendance and Apply User's Thresholds
        // Rules:
        // >= 75% -> Present
        // >= 45% and < 75% -> Half Day
        // < 45% -> Leave (Absent / not attended)
        $records = [];
        $summary = [
            'total_students' => $enrolledStudents->count(),
            'present_count' => 0,
            'half_day_count' => 0,
            'leave_count' => 0,
        ];

        foreach ($enrolledStudents as $email => $student) {
            $stat = $participantStatsByEmail[$email] ?? null;
            $attendedSeconds = $stat ? $stat['total_duration_seconds'] : 0;
            $attendedMinutes = (int) round($attendedSeconds / 60);

            // Calculate percentage
            $percentage = 0.0;
            if ($totalMeetingDurationSeconds > 0) {
                $percentage = round(($attendedSeconds / $totalMeetingDurationSeconds) * 100, 2);
            }
            if ($percentage > 100.0) {
                $percentage = 100.0;
            }

            // Determine attendance status
            if ($percentage >= 75.0) {
                $status = 'Present';
                $summary['present_count']++;
                $remarks = "Attended {$attendedMinutes}m of {$totalMeetingDurationMinutes}m ({$percentage}%) - Marked Present (>= 75%)";
            } elseif ($percentage >= 45.0) {
                $status = 'Half Day';
                $summary['half_day_count']++;
                $remarks = "Attended {$attendedMinutes}m of {$totalMeetingDurationMinutes}m ({$percentage}%) - Marked Half Day (>= 45%)";
            } else {
                $status = 'Leave';
                $summary['leave_count']++;
                if ($attendedSeconds > 0) {
                    $remarks = "Attended {$attendedMinutes}m of {$totalMeetingDurationMinutes}m ({$percentage}%) - Marked Leave (< 45%)";
                } else {
                    $remarks = "Did not attend meeting - Marked Leave";
                }
            }

            // Save or Update Attendance Record
            $attendance = BatchAttendance::updateOrCreate(
                [
                    'batch_id' => $batch->id,
                    'date' => $attendanceDate,
                    'student_email' => $email,
                ],
                [
                    'batch_schedule_id' => $schedule ? $schedule->id : null,
                    'student_id' => $student['id'] ?? null,
                    'student_name' => $student['name'] ?? null,
                    'zoom_meeting_id' => (string) $meetingId,
                    'meeting_duration_minutes' => $totalMeetingDurationMinutes,
                    'attended_duration_minutes' => $attendedMinutes,
                    'attended_duration_seconds' => $attendedSeconds,
                    'attendance_percentage' => $percentage,
                    'status' => $status,
                    'remarks' => $remarks,
                    'join_time' => $stat['first_join_time'] ?? null,
                    'leave_time' => $stat['last_leave_time'] ?? null,
                ]
            );

            $records[] = [
                'student_id' => $student['id'],
                'student_name' => $student['name'],
                'student_email' => $email,
                'meeting_duration_minutes' => $totalMeetingDurationMinutes,
                'attended_duration_minutes' => $attendedMinutes,
                'attended_percentage' => $percentage,
                'status' => $status,
                'remarks' => $remarks,
            ];
        }

        Log::info("Attendance marked for Batch #{$batch->id} ({$batch->batch_code}) on {$attendanceDate}: ", $summary);

        return [
            'success' => true,
            'message' => "Attendance processed successfully for Batch {$batch->batch_name} ({$batch->batch_code}).",
            'batch' => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'batch_name' => $batch->batch_name,
            ],
            'schedule' => $schedule ? [
                'id' => $schedule->id,
                'date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ] : null,
            'meeting_id' => $meetingId,
            'meeting_duration_minutes' => $totalMeetingDurationMinutes,
            'summary' => $summary,
            'attendances' => $records,
        ];
    }

    /**
     * API to fetch attendance records for a specific batch
     * GET /api/batch/{batchId}/attendance
     */
    public function getBatchAttendance(Request $request, $batchId)
    {
        $batch = Batch::findOrFail($batchId);

        $query = BatchAttendance::where('batch_id', $batchId)
            ->with(['student', 'batchSchedule']);

        if ($request->has('date') && !empty($request->date)) {
            $query->where('date', $request->date);
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('student_name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'batch' => [
                'id' => $batch->id,
                'batch_code' => $batch->batch_code,
                'batch_name' => $batch->batch_name,
            ],
            'data' => $attendances,
        ]);
    }

    /**
     * API to fetch attendance records for a specific student
     * GET /api/student/{userId}/attendance
     */
    public function getStudentAttendance(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $attendances = BatchAttendance::where(function ($q) use ($user) {
            $q->where('student_id', $user->id)
              ->orWhere('student_email', strtolower($user->email));
        })
        ->with(['batch', 'batchSchedule'])
        ->orderBy('date', 'desc')
        ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'Present')->count();
        $halfDays = $attendances->where('status', 'Half Day')->count();
        $leaveDays = $attendances->where('status', 'Leave')->count();

        return response()->json([
            'status' => true,
            'student' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'summary' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'half_days' => $halfDays,
                'leave_days' => $leaveDays,
                'overall_percentage' => $totalDays > 0 ? round((($presentDays + ($halfDays * 0.5)) / $totalDays) * 100, 2) : 0,
            ],
            'data' => $attendances,
        ]);
    }
}
