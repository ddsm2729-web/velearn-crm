<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffMonitoringController extends Controller
{
    public function index()
    {
        $staffMembers = \App\Models\User::role('Technical Staff')->with('staffProfile')->get();
        
        foreach ($staffMembers as $user) {
            $staff = $user->staffProfile;
            $user->metrics = $this->computeStaffMetrics($user->id, $staff ? $staff->id : null);
        }

        return view('tech.staff-monitoring.index', compact('staffMembers'));
    }

    public function show($id)
    {
        $user = \App\Models\User::with('staffProfile')->findOrFail($id);
        $staff = $user->staffProfile;
        $staffId = $staff ? $staff->id : null;
        $staffIds = array_filter([$user->id, $staffId]);

        $metrics = $this->computeStaffMetrics($user->id, $staffId);
        $batches = $metrics['batches'];

        $pendingDemos = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->whereIn('staff_id', $staffIds)
            ->whereIn('status', ['booked', 'pending', 'scheduled'])
            ->orderBy('date')->orderBy('time')
            ->get();

        $pendingDoubts = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->whereIn('staff_id', $staffIds)
            ->whereIn('status', ['booked', 'pending', 'scheduled'])
            ->orderBy('date')->orderBy('time')
            ->get();

        return view('tech.staff-monitoring.show', compact('user', 'staff', 'batches', 'pendingDemos', 'pendingDoubts', 'metrics'));
    }

    protected function computeStaffMetrics($userId, $staffId = null)
    {
        $staffIds = array_filter([$userId, $staffId]);

        $batches = \App\Models\Batch::with('course')
            ->whereIn('instructor_id', $staffIds)
            ->get();

        $batchIds = $batches->pluck('id')->toArray();
        $courseIds = $batches->pluck('course_id')->filter()->unique()->toArray();
        $batchesCount = $batches->count();

        // 1. Syllabus Covered / Course Progress (Strictly based on how many modules completed in BatchTopicLog)
        $batchProgressList = [];
        $totalModulesCount = 0;
        $completedModulesCount = 0;

        foreach ($batches as $batch) {
            $curricula = \App\Models\CourseCurriculum::where('course_id', $batch->course_id)->with('descriptions')->get();
            $batchTotalModules = $curricula->count();
            $batchCompletedModules = 0;

            foreach ($curricula as $curriculum) {
                $expectedTopics = $curriculum->descriptions ? $curriculum->descriptions->count() : 0;
                $loggedTopics = \App\Models\BatchTopicLog::where('batch_id', $batch->id)
                    ->where('course_curriculum_id', $curriculum->id)
                    ->count();

                $hasExplicitComplete = \App\Models\BatchTopicLog::where('batch_id', $batch->id)
                    ->where('course_curriculum_id', $curriculum->id)
                    ->where(function ($q) {
                        $q->where('is_completed', 1)->orWhere('status', 'Completed');
                    })
                    ->exists();

                if ($hasExplicitComplete) {
                    $batchCompletedModules++;
                } elseif ($expectedTopics > 0 && $loggedTopics >= $expectedTopics) {
                    $batchCompletedModules++;
                } elseif ($expectedTopics == 0 && $loggedTopics > 0) {
                    $batchCompletedModules++;
                }
            }

            $totalModulesCount += $batchTotalModules;
            $completedModulesCount += $batchCompletedModules;

            if ($batchTotalModules > 0) {
                $batchProgressList[] = min(100, round(($batchCompletedModules / $batchTotalModules) * 100));
            } else {
                $batchProgressList[] = 0;
            }
        }
        $syllabusDone = !empty($batchProgressList) ? round(array_sum($batchProgressList) / count($batchProgressList)) : 0;

        // 2. Attendance
        $attendances = !empty($batchIds) ? \App\Models\BatchAttendance::whereIn('batch_id', $batchIds)->get() : collect();
        $totalAtt = $attendances->count();
        if ($totalAtt > 0) {
            $percSum = $attendances->sum('attendance_percentage');
            if ($percSum > 0) {
                $avgAttendance = round($percSum / $totalAtt);
            } else {
                $presentAtt = $attendances->where('status', 'Present')->count();
                $avgAttendance = round(($presentAtt / $totalAtt) * 100);
            }
        } else {
            $avgAttendance = 0;
        }

        // 3. Scores across assignments, mini projects, and main projects
        $avgScore = 0;
        if (!empty($batchIds)) {
            $studentUserIds = \App\Models\CourseUser::whereIn('batch_id', $batchIds)->pluck('auth_id')->toArray();
            if (empty($studentUserIds)) {
                $admissionLeads = \App\Models\Admission::whereIn('batch_id', $batchIds)->with('lead')->get();
                $emails = $admissionLeads->pluck('lead.email')->filter()->toArray();
                if (!empty($emails)) {
                    $studentUserIds = \App\Models\User::whereIn('email', $emails)->pluck('id')->toArray();
                }
            }

            $assignmentIds = \App\Models\Assignment::whereIn('batch_id', $batchIds)
                ->orWhere(function ($q) use ($courseIds) {
                    $q->whereNull('batch_id')->whereIn('course_id', $courseIds);
                })->pluck('id');

            $miniIds = \App\Models\MiniProject::whereIn('batch_id', $batchIds)
                ->orWhere(function ($q) use ($courseIds) {
                    $q->whereNull('batch_id')->whereIn('course_id', $courseIds);
                })->pluck('id');

            $mainIds = \App\Models\MainProject::whereIn('batch_id', $batchIds)
                ->orWhere(function ($q) use ($courseIds) {
                    $q->whereNull('batch_id')->whereIn('course_id', $courseIds);
                })->pluck('id');

            $asScore = \App\Models\AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
                ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
                ->whereNotNull('score')->avg('score');

            $miniScore = \App\Models\MiniProjectSubmission::whereIn('mini_project_id', $miniIds)
                ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
                ->whereNotNull('score')->avg('score');

            $mainScore = \App\Models\MainProjectSubmission::whereIn('main_project_id', $mainIds)
                ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
                ->whereNotNull('score')->avg('score');

            $scores = array_filter([$asScore, $miniScore, $mainScore]);
            $avgScore = !empty($scores) ? round(array_sum($scores) / count($scores)) : 0;
        }

        // 4. Performance (composite dynamic score)
        $activeMetrics = [];
        $activeMetrics[] = $syllabusDone;
        if ($avgAttendance > 0) $activeMetrics[] = $avgAttendance;
        if ($avgScore > 0) $activeMetrics[] = $avgScore;
        $performance = count($activeMetrics) > 0 ? round(array_sum($activeMetrics) / count($activeMetrics)) : $syllabusDone;

        // 5. Students count
        $admissionsCount = !empty($batchIds) ? \App\Models\Admission::whereIn('batch_id', $batchIds)->count() : 0;
        $studentsCount = max($admissionsCount, $batches->sum('joined_student_count'));

        // 6. Demos & Doubts count
        $demosCount = \App\Models\SessionBooking::where('type', 'demo')->whereIn('staff_id', $staffIds)->count();
        $doubtsCount = \App\Models\SessionBooking::where('type', 'doubt')->whereIn('staff_id', $staffIds)->count();

        // 7. Status Percentage (Staff Activity / Attendance Rate)
        $statusPerc = $avgAttendance > 0 ? $avgAttendance : ($batchesCount > 0 ? 100 : 0);

        $primaryCourse = $batches->first() && $batches->first()->course ? $batches->first()->course->title : 'N/A';

        return [
            'batches' => $batches,
            'batchesCount' => $batchesCount,
            'studentsCount' => $studentsCount,
            'demosCount' => $demosCount,
            'doubtsCount' => $doubtsCount,
            'primaryCourse' => $primaryCourse,
            'syllabusDone' => $syllabusDone,
            'completedModules' => $completedModulesCount,
            'totalModules' => $totalModulesCount,
            'avgAttendance' => $avgAttendance,
            'avgScore' => $avgScore,
            'performance' => $performance,
            'statusPerc' => $statusPerc,
        ];
    }

    public function scheduleMeeting(Request $request, $id)
    {
        $request->validate([
            'meeting_type' => 'required',
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $meetingType = $request->meeting_type;
        $user = \App\Models\User::find($id);
        $staff = $user && $user->staffProfile ? $user->staffProfile : (\App\Models\Staff::where('auth_id', $id)->first() ?? \App\Models\Staff::find($id));
        
        // For 1:1 Staff Meeting, associate with staff table ID. For Group Meeting, null (or specific batch staff)
        $staffId = $meetingType === 'Staff Meeting' ? ($staff ? $staff->id : $id) : ($request->staff_id ?: null);
        
        $date = \Carbon\Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;

        $meetLink = $request->meet_link;
        $zoomMeetingId = $request->zoom_meeting_id;
        $zoomPassword = $request->zoom_password;
        $zoomStartUrl = $request->zoom_start_url;

        // Auto-generate Zoom meeting if link not provided
        if (empty($meetLink)) {
            try {
                $timeParsed = \Carbon\Carbon::parse($time);
                $startTimeString = \Carbon\Carbon::parse($date)->setTime($timeParsed->hour, $timeParsed->minute)->format('Y-m-d\TH:i:s');
                $zoomService = new \App\Services\ZoomService();
                $altHosts = ($user && $user->email) ? $user->email : ($staff && $staff->email ? $staff->email : '');

                $zoomData = $zoomService->createMeeting($request->title, $startTimeString, 60, 'Asia/Kolkata', $altHosts);
                if ($zoomData && isset($zoomData['join_url'])) {
                    $meetLink = $zoomData['join_url'];
                    $zoomMeetingId = $zoomData['id'] ?? null;
                    $zoomPassword = $zoomData['password'] ?? null;
                    $zoomStartUrl = $zoomData['start_url'] ?? null;
                }
            } catch (\Exception $e) {
                \Log::error('Auto Zoom creation error in scheduleMeeting: ' . $e->getMessage());
            }

            if (empty($meetLink)) {
                $roomPrefix = $meetingType === 'Group Meeting' ? 'velearn-group-' : 'velearn-staff-';
                $uniqueRoom = $roomPrefix . \Illuminate\Support\Str::slug($request->title) . '-' . substr(md5(uniqid(mt_rand(), true)), 0, 8);
                $meetLink = "https://meet.jit.si/{$uniqueRoom}";
            }
        }

        $meeting = \App\Models\StaffMeeting::create([
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

        return response()->json([
            'success' => true,
            'message' => "{$meetingType} scheduled successfully!",
            'meeting' => $meeting,
            'meet_link' => $meetLink,
            'start_url' => $zoomStartUrl,
            'zoom_meeting_id' => $zoomMeetingId,
            'zoom_password' => $zoomPassword,
        ]);
    }
}
