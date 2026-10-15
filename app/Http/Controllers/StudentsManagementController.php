<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\BatchAttendance;
use App\Models\BatchSchedule;
use App\Models\BatchTopicLog;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\MiniProject;
use App\Models\MiniProjectSubmission;
use App\Models\MainProject;
use App\Models\MainProjectSubmission;
use App\Models\SessionBooking;
use App\Models\Staff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentsManagementController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();
        
        $admissionsQuery = Admission::with(['contact', 'course', 'batch', 'payments'])
            ->whereHas('course', function($q) {
                $q->where('course_type', 'live')->where('status', '1');
            })
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'Completed']);
            
        if (auth()->check() && auth()->user()->hasRole('Technical Staff')) {
            $staff = Staff::where('auth_id', auth()->id())->first();
            if ($staff) {
                $admissionsQuery->whereHas('batch', function($q) use ($staff) {
                    $q->where('instructor_id', $staff->id);
                });
            } else {
                // If not a staff, they shouldn't see students (or handle gracefully)
                $admissionsQuery->where('id', 0);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $admissionsQuery->where(function($q) use ($search) {
                $q->whereHas('contact', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('caller_number', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                })->orWhereHas('batch', function($q2) use ($search) {
                    $q2->where('batch_code', 'like', "%{$search}%")
                       ->orWhere('batch_name', 'like', "%{$search}%");
                });
            });
        }

        $admissions = $admissionsQuery->get();

        // Calculate dynamic attendance and fees for each student based on their course and batch
        foreach ($admissions as $admission) {
            $att = self::calculateStudentAttendance($admission);
            $admission->attendance_percentage = $att['percentage'];
            $admission->attendance_data = $att;

            $fee = self::calculateStudentFeeDetails($admission);
            $admission->fee_details = $fee;
        }

        if ($request->ajax()) {
            return view('tech.students.partials.list', compact('admissions'))->render();
        }

        return view('tech.students.index', compact('courses', 'admissions'));
    }
    
    public function show($id)
    {
        $admission = Admission::with(['contact', 'course', 'batch', 'payments'])->findOrFail($id);
        $feeDetails = self::calculateStudentFeeDetails($admission);
        
        $courseId = $admission->course_id;
        $contact = $admission->contact;
        $user = $contact ? User::where('email', $contact->email)->first() : null;
        $userId = $user ? $user->id : 0;
        
        // Curriculum Modules
        $modules = CourseCurriculum::where('course_id', $courseId)->get();
        $modulesTotal = $modules->count();

        // Dynamic curriculum progress
        $completedTopics = BatchTopicLog::where('batch_id', $admission->batch_id)->where('is_completed', 1)->count();
        if ($modulesTotal > 0 && $completedTopics > 0) {
            $modulesDone = min($modulesTotal, $completedTopics);
            $progress = round(($modulesDone / $modulesTotal) * 100);
        } else {
            $modulesDone = 0;
            $progress = 0;
        }

        // Assignments
        $assignmentsList = Assignment::where('course_id', $courseId)->get();
        $assignmentsCount = $assignmentsList->count();
        $assignmentSubmissions = AssignmentSubmission::where('user_id', $userId)->get()->keyBy('assignment_id');
        $assignmentsSubmitted = $assignmentSubmissions->count();

        // Mini Projects
        $miniProjectsList = MiniProject::where('course_id', $courseId)->get();
        $miniProjectsCount = $miniProjectsList->count();
        $miniProjectSubmissions = MiniProjectSubmission::where('user_id', $userId)->get()->keyBy('mini_project_id');
        $miniProjectsSubmitted = $miniProjectSubmissions->count();
        
        // Main Project
        $mainProjectsList = MainProject::where('course_id', $courseId)->get();
        $mainProjectSubmissions = MainProjectSubmission::where('user_id', $userId)->get()->keyBy('main_project_id');

        // Sessions
        $demos = SessionBooking::where('admission_id', $admission->id)->where('type', 'demo')->orderBy('date', 'desc')->get();
        $doubts = SessionBooking::where('admission_id', $admission->id)->where('type', 'doubt')->orderBy('date', 'desc')->get();

        // Dynamic Attendance Calculation for this course and batch
        $attData = self::calculateStudentAttendance($admission);
        $attendance = $attData['percentage'];
        $attendanceRecords = $attData['records'];
        $attendanceTotalClasses = $attData['total_classes'];
        $attendanceAttendedClasses = $attData['attended_classes'];
        $attendancePresentCount = $attData['present_count'];
        $attendanceHalfDayCount = $attData['half_day_count'];
        $attendanceLeaveCount = $attData['leave_count'];

        // Monthly calendar breakdown
        $now = Carbon::now();
        $targetDate = Carbon::createFromDate($now->year, $now->month, 1);
        $daysInMonth = $targetDate->daysInMonth;

        $recordsKeyedByDay = $attendanceRecords->keyBy(function ($r) {
            return (int) Carbon::parse($r->date)->day;
        });

        $calendarDays = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = Carbon::createFromDate($now->year, $now->month, $d)->toDateString();
            $rec = $recordsKeyedByDay->get($d);
            $calendarDays[] = [
                'day' => $d,
                'date' => $dateStr,
                'status' => $rec ? $rec->status : ($d <= $now->day ? 'None' : 'Future'),
                'percentage' => $rec ? (float) $rec->attendance_percentage : 0,
                'schedule' => $rec && $rec->batchSchedule ? $rec->batchSchedule->start_time . ' - ' . $rec->batchSchedule->end_time : null,
                'attended_minutes' => $rec ? $rec->attended_duration_minutes : 0,
                'duration_minutes' => $rec ? $rec->meeting_duration_minutes : 0,
            ];
        }

        $attendanceCalendar = [
            'month_name' => $targetDate->format('F Y'),
            'days' => $calendarDays,
        ];

        return view('tech.students.show', compact(
            'admission', 'modules', 'modulesTotal', 'modulesDone',
            'assignmentsList', 'assignmentSubmissions', 'assignmentsCount', 'assignmentsSubmitted',
            'miniProjectsList', 'miniProjectSubmissions', 'miniProjectsCount', 'miniProjectsSubmitted',
            'mainProjectsList', 'mainProjectSubmissions',
            'demos', 'doubts',
            'progress', 'attendance',
            'attendanceRecords', 'attendanceTotalClasses', 'attendanceAttendedClasses',
            'attendancePresentCount', 'attendanceHalfDayCount', 'attendanceLeaveCount',
            'attendanceCalendar',
            'feeDetails'
        ));
    }

    /**
     * Return dynamic attendance details for a student as JSON (for modal quick-view)
     */
    public function attendanceDetails($id)
    {
        $admission = Admission::with(['contact', 'course', 'batch', 'payments'])->findOrFail($id);
        $attData = self::calculateStudentAttendance($admission);
        $feeData = self::calculateStudentFeeDetails($admission);

        $name = $admission->contact->name ?? 'Unknown';
        $email = $admission->contact->email ?? $admission->contact->caller_number ?? 'No email';
        $courseTitle = $admission->course->title ?? 'Unknown Course';
        $batchCode = $admission->batch->batch_code ?? 'Unassigned';
        $batchName = $admission->batch->batch_name ?? 'Unassigned';

        // Monthly calendar breakdown
        $now = Carbon::now();
        $targetDate = Carbon::createFromDate($now->year, $now->month, 1);
        $daysInMonth = $targetDate->daysInMonth;

        $recordsKeyedByDay = $attData['records']->keyBy(function ($r) {
            return (int) Carbon::parse($r->date)->day;
        });

        $calendarDays = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateStr = Carbon::createFromDate($now->year, $now->month, $d)->toDateString();
            $rec = $recordsKeyedByDay->get($d);
            $calendarDays[] = [
                'day' => $d,
                'date' => $dateStr,
                'status' => $rec ? $rec->status : ($d <= $now->day ? 'None' : 'Future'),
                'percentage' => $rec ? (float) $rec->attendance_percentage : 0,
            ];
        }

        return response()->json([
            'status' => true,
            'student' => [
                'id' => $admission->id,
                'name' => $name,
                'email' => $email,
                'course' => $courseTitle,
                'batch_code' => $batchCode,
                'batch_name' => $batchName,
                'joined' => $admission->created_at ? $admission->created_at->format('d M Y') : 'N/A',
                'profile_url' => route('tech.students.show', $admission->id) . '?tab=attendance',
                'fee' => [
                    'status' => $feeData['fee_status'],
                    'course_fees' => $feeData['course_fees'],
                    'paid_amount' => $feeData['paid_amount'],
                    'due_amount' => $feeData['due_amount'],
                ],
            ],
            'attendance' => [
                'percentage' => $attData['percentage'],
                'total_classes' => $attData['total_classes'],
                'attended_classes' => $attData['attended_classes'],
                'present_count' => $attData['present_count'],
                'half_day_count' => $attData['half_day_count'],
                'leave_count' => $attData['leave_count'],
                'calendar' => [
                    'month_name' => $targetDate->format('F Y'),
                    'days' => $calendarDays,
                ],
                'records' => $attData['records']->map(function ($r) {
                    return [
                        'id' => $r->id,
                        'date' => Carbon::parse($r->date)->format('d M Y'),
                        'raw_date' => $r->date,
                        'schedule' => $r->batchSchedule ? $r->batchSchedule->start_time . ' - ' . $r->batchSchedule->end_time : 'Live Class',
                        'duration_minutes' => $r->meeting_duration_minutes,
                        'attended_minutes' => $r->attended_duration_minutes,
                        'percentage' => round($r->attendance_percentage),
                        'status' => $r->status,
                        'remarks' => $r->remarks,
                    ];
                }),
            ]
        ]);
    }

    /**
     * Compute dynamic attendance statistics for a student based on their course and assigned batch.
     *
     * @param \App\Models\Admission $admission
     * @return array
     */
    public static function calculateStudentAttendance($admission)
    {
        if (!$admission || !$admission->batch_id) {
            return [
                'percentage' => 0,
                'total_classes' => 0,
                'attended_classes' => 0,
                'present_count' => 0,
                'half_day_count' => 0,
                'leave_count' => 0,
                'records' => collect(),
            ];
        }

        $batchId = $admission->batch_id;
        $contact = $admission->contact;
        $email = $contact ? strtolower(trim($contact->email)) : null;
        $user = $email ? User::where('email', $email)->first() : null;
        $userId = $user ? $user->id : null;

        $today = Carbon::today()->toDateString();

        // 1. Total classes held or scheduled for this batch up to today
        $scheduleDates = BatchSchedule::where('batch_id', $batchId)
            ->whereDate('date', '<=', $today)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString());

        $attendanceDates = BatchAttendance::where('batch_id', $batchId)
            ->whereDate('date', '<=', $today)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString());

        $topicDates = BatchTopicLog::where('batch_id', $batchId)
            ->whereDate('date', '<=', $today)
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString());

        $allClassDates = $scheduleDates->concat($attendanceDates)->concat($topicDates)->unique();
        $totalClassesHeld = $allClassDates->count();

        // 2. Student attendance records for this batch
        $studentAttendances = BatchAttendance::where('batch_id', $batchId)
            ->where(function ($q) use ($userId, $email) {
                if ($userId) {
                    $q->where('student_id', $userId);
                }
                if ($email) {
                    $q->orWhere('student_email', $email);
                }
            })
            ->with(['batchSchedule', 'batch'])
            ->orderBy('date', 'desc')
            ->get();

        $recordsCount = $studentAttendances->count();

        if ($recordsCount > 0) {
            $presentCount = $studentAttendances->where('status', 'Present')->count();
            $halfDayCount = $studentAttendances->where('status', 'Half Day')->count();
            $leaveCount = $studentAttendances->where('status', 'Leave')->count();
            $effectiveAttended = $presentCount + ($halfDayCount * 0.5);

            $percentageSum = $studentAttendances->sum('attendance_percentage');
            $denominator = max($totalClassesHeld, $recordsCount, 1);

            if ($percentageSum > 0) {
                $percentage = round($percentageSum / $denominator);
            } else {
                $percentage = round(($effectiveAttended / $denominator) * 100);
            }

            $percentage = (int) min(100, max(0, $percentage));

            return [
                'percentage' => $percentage,
                'total_classes' => $totalClassesHeld,
                'attended_classes' => $effectiveAttended,
                'present_count' => $presentCount,
                'half_day_count' => $halfDayCount,
                'leave_count' => $leaveCount,
                'records' => $studentAttendances,
            ];
        }

        // No attendance records yet for this student
        return [
            'percentage' => 0,
            'total_classes' => $totalClassesHeld,
            'attended_classes' => 0,
            'present_count' => 0,
            'half_day_count' => 0,
            'leave_count' => $totalClassesHeld,
        ];
    }

    /**
     * Compute dynamic fee details and auto-sync admission status.
     */
    public static function calculateStudentFeeDetails($admission)
    {
        $courseFees = (float) ($admission->course_fees ?? ($admission->course->buy_price ?? 0));
        
        $payments = $admission->relationLoaded('payments') ? $admission->payments : $admission->payments()->get();
        
        $paidAmount = (float) $payments->filter(function($p) {
            return in_array(strtolower($p->status), ['paid', 'completed', 'success']);
        })->sum('amount');

        $dueAmount = max(0, $courseFees - $paidAmount);

        if ($paidAmount >= $courseFees && $courseFees > 0) {
            $feeStatus = 'Paid';
        } elseif ($paidAmount > 0) {
            $feeStatus = 'Partial';
        } else {
            $feeStatus = 'Pending';
        }

        // Auto sync admission status in database if paid in full
        if ($feeStatus === 'Paid' && $admission->status === 'pending_fee') {
            $admission->update(['status' => 'ongoing']);
        }

        return [
            'course_fees' => $courseFees,
            'paid_amount' => $paidAmount,
            'due_amount' => $dueAmount,
            'fee_status' => $feeStatus,
            'payments' => $payments,
        ];
    }
}
