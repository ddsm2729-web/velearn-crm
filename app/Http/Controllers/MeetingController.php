<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Admission;
use App\Models\StaffMeeting;
use App\Models\User;
use App\Services\ZoomService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();
        $isTechStaff = $user->hasRole('Technical Staff');
        $isTechHead = $user->hasRole('Technical Head') || $user->hasRole('SuperAdmin');
        
        $staff = Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;

        $today = Carbon::today()->toDateString();
        $startDate = request('start_date', $today);
        $endDate = request('end_date');
        $activeTab = request('tab');

        if (!$activeTab) {
            $activeTab = 'scheduled';
        }

        // 1. CallLog Demos (Existing declaration - preserved intact)
        $callLogDemos = \App\Models\CallLog::with('contact')
            ->whereNotNull('demo_date')
            ->whereNotNull('demo_time')
            ->where('demo_date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('demo_date', '<=', $endDate))
            ->where(function ($q) {
                $q->whereNull('lead_tag')
                  ->orWhereNotIn('lead_tag', ['Demo Missed', 'Lost']);
            })
            ->when($isTechStaff && $staffId, fn($q) => $q->where('assigned_staff_id', $staffId))
            ->get()
            ->map(function($d) {
                $date = $d->demo_date instanceof Carbon ? $d->demo_date->toDateString() : $d->demo_date;
                $time = Carbon::parse($d->demo_time)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($d->demo_time)->format('H:i'));
                return (object)[
                    'type' => 'Demo',
                    'title' => 'Demo Session',
                    'student_name' => $d->contact->name ?? 'Unknown',
                    'topic' => null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $d->zoom_meet_link,
                    'status' => 'upcoming'
                ];
            });

        // 2. SessionBooking (Demo & Doubt) (Existing declaration - preserved intact)
        $sessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
            ->whereIn('status', ['booked', 'completed'])
            ->when($isTechStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get()
            ->map(function($b) {
                $date = $b->date instanceof Carbon ? $b->date->toDateString() : substr($b->date, 0, 10);
                $time = Carbon::parse($b->time)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($b->time)->format('H:i'));
                $isDemo = $b->type === 'demo';
                return (object)[
                    'type' => $isDemo ? 'Demo' : 'Doubt',
                    'title' => $isDemo ? 'Demo Session' : 'Doubt Session',
                    'student_name' => $b->admission->contact->name ?? 'Unknown',
                    'topic' => current(array_filter([$b->admission->course->course_name ?? null, $b->topic])) ?: null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $b->meet_link ?: $b->zoom_meet_link,
                    'status' => $b->status === 'completed' ? 'completed' : 'upcoming'
                ];
            });

        // 3. SupportTicketAllocation (Doubt) (Existing declaration - preserved intact)
        $supportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where('allocated_date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('allocated_date', '<=', $endDate))
            ->whereIn('status', ['Scheduled', 'Completed'])
            ->when($isTechStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get()
            ->map(function($a) {
                $date = $a->allocated_date instanceof Carbon ? $a->allocated_date->toDateString() : substr($a->allocated_date, 0, 10);
                $time = Carbon::parse($a->allocated_slot)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($a->allocated_slot)->format('H:i'));
                return (object)[
                    'type' => 'Doubt',
                    'title' => 'Doubt Session',
                    'student_name' => $a->ticket->user->name ?? 'Unknown',
                    'topic' => $a->ticket->subject ?? null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $a->zoom_meet_link,
                    'status' => strtolower($a->status) === 'completed' ? 'completed' : 'upcoming'
                ];
            });

        // 4. StaffMeeting records for General Scheduled Meetings tab
        $customMeetings = StaffMeeting::with(['staff', 'student', 'admission.contact'])
            ->where('date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
            ->when($isTechStaff && $staffId, function($q) use ($staffId) {
                $q->where(function($sq) use ($staffId) {
                    $sq->where('staff_id', $staffId)
                       ->orWhere('meeting_type', 'Group Meeting');
                });
            })
            ->get()
            ->map(function($m) {
                $date = $m->date instanceof Carbon ? $m->date->toDateString() : substr($m->date, 0, 10);
                $time = Carbon::parse($m->time)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($m->time)->format('H:i'));
                $partyName = $m->meeting_type === 'Student Evaluation' 
                    ? ($m->student_name ?: ($m->admission->contact->name ?? 'Student'))
                    : ($m->meeting_type === 'Group Meeting' ? 'All Staff' : ($m->staff->name ?? 'Staff'));

                $meetingTypeLabel = match($m->meeting_type) {
                    'Student Evaluation' => '1:1 Student Evaluation',
                    'Group Meeting' => 'Group Staff Meeting',
                    'Staff Meeting' => '1:1 Staff Meeting',
                    default => $m->meeting_type
                };

                return (object)[
                    'type' => $meetingTypeLabel,
                    'title' => $m->title,
                    'student_name' => $partyName,
                    'topic' => $m->agenda,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $m->meet_link ?: $m->zoom_meet_link,
                    'status' => $m->status ?: 'upcoming'
                ];
            });

        $allMeetings = $callLogDemos->concat($sessionBookings)->concat($supportDoubts)->concat($customMeetings)->sortBy('timestamp')->values();

        // 5. Students under this technical staff's batches (for Tab: Evaluation Class Link)
        $staffStudents = collect();
        if ($isTechStaff && $staffId) {
            $staffStudents = Admission::with(['contact', 'course', 'batch'])
                ->whereHas('course', fn($q) => $q->where('course_type', 'live')->where('status', '1'))
                ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'Completed'])
                ->whereHas('batch', fn($q) => $q->where('instructor_id', $staffId))
                ->get();
        } elseif ($isTechHead) {
            // Technical Head can select from all active students in live courses
            $staffStudents = Admission::with(['contact', 'course', 'batch'])
                ->whereHas('course', fn($q) => $q->where('course_type', 'live')->where('status', '1'))
                ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'Completed'])
                ->latest()
                ->take(100)
                ->get();
        }

        // 6. Student Evaluation Meetings list
        $studentEvaluationMeetingsQuery = StaffMeeting::with(['staff', 'student', 'admission.contact', 'admission.course'])
            ->where('meeting_type', 'Student Evaluation');
        if ($isTechStaff && $staffId) {
            $studentEvaluationMeetingsQuery->where('staff_id', $staffId);
        }
        $studentEvaluationMeetings = $studentEvaluationMeetingsQuery->latest('date')->latest('time')->get();

        // 7. Technical Staff List (for Technical Head to schedule 1:1 Staff Meeting)
        $technicalStaffList = Staff::where(function($q) {
            $q->whereHas('user.roles', function($rq) {
                $rq->where('name', 'Technical Staff');
            })->orWhereHas('role', function($rq) {
                $rq->where('name', 'Technical Staff');
            });
        })->get();
        if ($technicalStaffList->isEmpty()) {
            $technicalStaffList = Staff::where('status', 1)->get();
        }

        // 8. Staff Meetings List (1:1 Staff Meetings & Group Meetings for Technical Head / Staff)
        $staffMeetingsQuery = StaffMeeting::with(['staff', 'creator'])
            ->whereIn('meeting_type', ['Staff Meeting', 'Group Meeting']);

        if ($isTechStaff && $staffId && !$isTechHead) {
            $staffMeetingsQuery->where(function($q) use ($staffId) {
                $q->where('staff_id', $staffId)
                  ->orWhere('meeting_type', 'Group Meeting');
            });
        }
        $staffMeetings = $staffMeetingsQuery->latest('date')->latest('time')->get();

        return view('meetings.index', compact(
            'allMeetings',
            'startDate',
            'endDate',
            'isTechStaff',
            'isTechHead',
            'staffStudents',
            'studentEvaluationMeetings',
            'technicalStaffList',
            'staffMeetings',
            'activeTab'
        ));
    }

    /**
     * AJAX endpoint to generate a Zoom meeting link instantly
     */
    public function generateZoomLink(Request $request)
    {
        $topic = $request->input('title', 'VeLearn Live Session');
        $date = $request->input('date', Carbon::today()->toDateString());
        $time = $request->input('time', Carbon::now()->format('H:i'));
        $duration = (int) $request->input('duration', 60);

        try {
            $timeParsed = Carbon::parse($time);
            $startTimeString = Carbon::parse($date)->setTime($timeParsed->hour, $timeParsed->minute)->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            $startTimeString = Carbon::now()->addMinutes(5)->format('Y-m-d\TH:i:s');
        }

        $zoomService = new ZoomService();
        $meeting = null;

        try {
            $meeting = $zoomService->createMeeting($topic, $startTimeString, $duration, 'Asia/Kolkata');
        } catch (\Exception $e) {
            Log::error('Zoom meeting generation failed: ' . $e->getMessage());
        }

        if ($meeting && isset($meeting['join_url'])) {
            return response()->json([
                'success' => true,
                'link' => $meeting['join_url'],
                'zoom_meeting_id' => $meeting['id'] ?? null,
                'zoom_password' => $meeting['password'] ?? null,
                'start_url' => $meeting['start_url'] ?? null,
                'source' => 'zoom',
                'message' => 'Zoom meeting link generated successfully!'
            ]);
        }

        // Fallback to instant high-performance meeting room (e.g. Jitsi Meet)
        $uniqueRoom = 'velearn-' . Str::slug($topic) . '-' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
        $fallbackLink = "https://meet.jit.si/{$uniqueRoom}";

        return response()->json([
            'success' => true,
            'link' => $fallbackLink,
            'zoom_meeting_id' => null,
            'source' => 'direct',
            'message' => 'Live meeting room link generated successfully!'
        ]);
    }

    /**
     * Store 1:1 Student Evaluation Meeting created by Technical Staff
     */
    public function storeStudentMeeting(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'admission_id' => 'required|exists:admissions,id',
            'date' => 'nullable|date',
            'time' => 'nullable|string',
            'meet_link' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $staff = Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;

        $admission = Admission::with('contact')->find($request->admission_id);
        $studentName = $admission && $admission->contact ? $admission->contact->name : 'Student';

        // Match user for student LMS
        $studentUser = null;
        if ($admission && $admission->contact) {
            $studentUser = User::where(function($q) use ($admission) {
                if ($admission->contact->email) {
                    $q->where('email', $admission->contact->email);
                }
                if ($admission->contact->caller_number) {
                    $q->orWhere('phonenumber', $admission->contact->caller_number);
                }
            })->first();
        }

        $date = $request->date ?: Carbon::today()->toDateString();
        $time = $request->time ?: Carbon::now()->format('H:i');

        $meetLink = $request->meet_link;
        $zoomMeetingId = $request->zoom_meeting_id;
        $zoomPassword = $request->zoom_password;
        $zoomStartUrl = $request->zoom_start_url;

        // If no link was provided/generated, create Zoom meeting automatically
        if (empty($meetLink)) {
            try {
                $timeParsed = Carbon::parse($time);
                $startTimeString = Carbon::parse($date)->setTime($timeParsed->hour, $timeParsed->minute)->format('Y-m-d\TH:i:s');
                $zoomService = new ZoomService();
                $zoomData = $zoomService->createMeeting($request->title, $startTimeString, 60, 'Asia/Kolkata');
                if ($zoomData && isset($zoomData['join_url'])) {
                    $meetLink = $zoomData['join_url'];
                    $zoomMeetingId = $zoomData['id'] ?? null;
                    $zoomPassword = $zoomData['password'] ?? null;
                    $zoomStartUrl = $zoomData['start_url'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error('Auto Zoom creation error in storeStudentMeeting: ' . $e->getMessage());
            }

            if (empty($meetLink)) {
                $uniqueRoom = 'velearn-eval-' . Str::slug($request->title) . '-' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
                $meetLink = "https://meet.jit.si/{$uniqueRoom}";
            }
        }

        $meeting = StaffMeeting::create([
            'meeting_type' => 'Student Evaluation',
            'title' => $request->title,
            'date' => $date,
            'time' => $time,
            'staff_id' => $staffId,
            'admission_id' => $admission->id,
            'student_id' => $studentUser ? $studentUser->id : null,
            'student_name' => $studentName,
            'agenda' => '1:1 Student Evaluation Session',
            'meet_link' => $meetLink,
            'zoom_meeting_id' => $zoomMeetingId,
            'zoom_meet_link' => $meetLink,
            'zoom_start_url' => $zoomStartUrl,
            'zoom_password' => $zoomPassword,
            'created_by' => $userId,
            'status' => 'upcoming',
        ]);

        $action = $request->input('action_type', 'save');
        if ($action === 'connect') {
            return redirect($meetLink);
        }

        return redirect()->route('meetings.index', ['tab' => 'evaluation'])
            ->with('success', "1:1 Evaluation meeting for {$studentName} scheduled successfully! Meeting link generated.");
    }

    /**
     * Store 1:1 Staff Meeting or Group Meeting created by Technical Head
     */
    public function storeStaffMeeting(Request $request)
    {
        $request->validate([
            'meeting_type' => 'required|in:Staff Meeting,Group Meeting',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'staff_id' => 'nullable|exists:staff,id',
            'agenda' => 'nullable|string',
            'meet_link' => 'nullable|string',
        ]);

        $meetingType = $request->meeting_type;
        $staffId = $meetingType === 'Staff Meeting' ? $request->staff_id : null;
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;

        $meetLink = $request->meet_link;
        $zoomMeetingId = $request->zoom_meeting_id;
        $zoomPassword = $request->zoom_password;
        $zoomStartUrl = $request->zoom_start_url;

        // Auto-generate Zoom meeting if link not provided
        if (empty($meetLink)) {
            try {
                $timeParsed = Carbon::parse($time);
                $startTimeString = Carbon::parse($date)->setTime($timeParsed->hour, $timeParsed->minute)->format('Y-m-d\TH:i:s');
                $zoomService = new ZoomService();
                $staffMember = $staffId ? Staff::find($staffId) : null;
                $altHosts = $staffMember && $staffMember->email ? $staffMember->email : '';
                $zoomData = $zoomService->createMeeting($request->title, $startTimeString, 60, 'Asia/Kolkata', $altHosts);
                if ($zoomData && isset($zoomData['join_url'])) {
                    $meetLink = $zoomData['join_url'];
                    $zoomMeetingId = $zoomData['id'] ?? null;
                    $zoomPassword = $zoomData['password'] ?? null;
                    $zoomStartUrl = $zoomData['start_url'] ?? null;
                }
            } catch (\Exception $e) {
                Log::error('Auto Zoom creation error in storeStaffMeeting: ' . $e->getMessage());
            }

            if (empty($meetLink)) {
                $roomPrefix = $meetingType === 'Group Meeting' ? 'velearn-group-' : 'velearn-staff-';
                $uniqueRoom = $roomPrefix . Str::slug($request->title) . '-' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
                $meetLink = "https://meet.jit.si/{$uniqueRoom}";
            }
        }

        StaffMeeting::create([
            'meeting_type' => $meetingType,
            'title' => $request->title,
            'date' => $date,
            'time' => $time,
            'staff_id' => $staffId,
            'agenda' => $request->agenda,
            'meet_link' => $meetLink,
            'zoom_meeting_id' => $zoomMeetingId,
            'zoom_meet_link' => $meetLink,
            'zoom_start_url' => $zoomStartUrl,
            'zoom_password' => $zoomPassword,
            'created_by' => auth()->id(),
            'status' => 'upcoming',
        ]);

        return redirect()->route('meetings.index', ['tab' => 'group_meetings'])
            ->with('success', "{$meetingType} scheduled successfully!");
    }

    /**
     * Update meeting status
     */
    public function updateStatus(Request $request, $id)
    {
        $meeting = StaffMeeting::findOrFail($id);
        $meeting->update([
            'status' => $request->input('status', 'completed')
        ]);

        return back()->with('success', 'Meeting status updated successfully.');
    }
}

