<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\BatchAttendance;
use App\Models\BatchTopicLog;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\CourseUser;
use App\Models\Admission;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\MiniProject;
use App\Models\MiniProjectSubmission;
use App\Models\MainProject;
use App\Models\MainProjectSubmission;
use Illuminate\Http\Request;

class BatchProgressController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();

        $batches = Batch::whereHas('course', function ($q) {
                $q->where('course_type', 'live');
            })
            ->with(['course', 'instructor'])
            ->orderBy('start_date', 'asc')
            ->get();

        // Calculate dynamic progress stats for each batch
        $batchStats = [];

        foreach ($batches as $batch) {
            $batchStats[$batch->id] = $this->calculateBatchStats($batch);
        }

        return view('tech.batch-progress.index', compact('courses', 'batches', 'batchStats'));
    }

    public function show($id)
    {
        $batch = Batch::with(['course', 'instructor', 'schedules'])->findOrFail($id);
        $stats = $this->calculateBatchStats($batch);

        return redirect()->route('batches.show', $batch->id);
    }

    /**
     * Compute dynamic statistics for a batch:
     * 1. Course Progress / Syllabus Done:
     *    - Total curriculum modules for the batch's course
     *    - Completed modules tracked via BatchTopicLog
     * 2. Average Attendance:
     *    - Based on BatchAttendance records for this batch
     * 3. Average Student Score:
     *    - Average of graded Assignment, Mini Project, and Main Project submissions for this batch
     * 4. Overall Progress:
     *    - Composite score of Course Progress, Attendance, and Scores
     */
    protected function calculateBatchStats(Batch $batch)
    {
        // ==========================================
        // 1. COURSE PROGRESS & SYLLABUS DONE
        // ==========================================
        $curricula = CourseCurriculum::with('descriptions')
            ->where('course_id', $batch->course_id)
            ->get();

        $totalModules = $curricula->count();
        $completedModules = 0;

        foreach ($curricula as $curriculum) {
            $expectedTopics = $curriculum->descriptions ? $curriculum->descriptions->count() : 0;

            $loggedTopics = BatchTopicLog::where('batch_id', $batch->id)
                ->where('course_curriculum_id', $curriculum->id)
                ->count();

            // Module is completed if all topics logged or is explicitly marked completed
            $hasExplicitComplete = BatchTopicLog::where('batch_id', $batch->id)
                ->where('course_curriculum_id', $curriculum->id)
                ->where(function ($q) {
                    $q->where('is_completed', true)->orWhere('status', 'Completed');
                })
                ->exists();

            if ($hasExplicitComplete) {
                $completedModules++;
            } elseif ($expectedTopics > 0 && $loggedTopics >= $expectedTopics) {
                $completedModules++;
            } elseif ($expectedTopics == 0 && $loggedTopics > 0) {
                $completedModules++;
            }
        }

        $courseProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
        $syllabusDone = $courseProgress; // directly represents syllabus completion

        // ==========================================
        // 2. DYNAMIC ATTENDANCE
        // ==========================================
        // From BatchAttendance records for this batch
        $attendances = BatchAttendance::where('batch_id', $batch->id)->get();
        $totalAttendanceRecords = $attendances->count();

        if ($totalAttendanceRecords > 0) {
            // Present = 100%, Half Day = 50%, Leave = 0% or use actual attendance_percentage
            $totalPercentageSum = $attendances->sum('attendance_percentage');
            $avgAttendance = round($totalPercentageSum / $totalAttendanceRecords);
        } else {
            // If no meeting ended yet, default to 0%
            $avgAttendance = 0;
        }

        // ==========================================
        // 3. ENROLLED STUDENTS FOR BATCH
        // ==========================================
        $studentUserIds = CourseUser::where('batch_id', $batch->id)->pluck('auth_id')->toArray();
        if (empty($studentUserIds)) {
            // Also fallback to admissions
            $admissionLeads = Admission::where('batch_id', $batch->id)->with('lead')->get();
            $emails = $admissionLeads->pluck('lead.email')->filter()->toArray();
            if (!empty($emails)) {
                $studentUserIds = \App\Models\User::whereIn('email', $emails)->pluck('id')->toArray();
            }
        }

        // ==========================================
        // 4. AVERAGE STUDENT SCORE (ASSIGNMENTS + MINI + MAIN PROJECTS)
        // ==========================================
        $assignmentIds = Assignment::where('batch_id', $batch->id)
            ->orWhere(function ($q) use ($batch) {
                $q->whereNull('batch_id')->where('course_id', $batch->course_id);
            })
            ->pluck('id');

        $miniProjectIds = MiniProject::where('batch_id', $batch->id)
            ->orWhere(function ($q) use ($batch) {
                $q->whereNull('batch_id')->where('course_id', $batch->course_id);
            })
            ->pluck('id');

        $mainProjectIds = MainProject::where('batch_id', $batch->id)
            ->orWhere(function ($q) use ($batch) {
                $q->whereNull('batch_id')->where('course_id', $batch->course_id);
            })
            ->pluck('id');

        // Submissions & Scores
        $assignmentSubsQuery = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->whereNotNull('score');
        if (!empty($studentUserIds)) {
            $assignmentSubsQuery->whereIn('user_id', $studentUserIds);
        }
        $assignmentScores = $assignmentSubsQuery->pluck('score');

        $miniSubsQuery = MiniProjectSubmission::whereIn('mini_project_id', $miniProjectIds)
            ->whereNotNull('score');
        if (!empty($studentUserIds)) {
            $miniSubsQuery->whereIn('user_id', $studentUserIds);
        }
        $miniScores = $miniSubsQuery->pluck('score');

        $mainSubsQuery = MainProjectSubmission::whereIn('main_project_id', $mainProjectIds)
            ->whereNotNull('score');
        if (!empty($studentUserIds)) {
            $mainSubsQuery->whereIn('user_id', $studentUserIds);
        }
        $mainScores = $mainSubsQuery->pluck('score');

        $allScores = collect()
            ->concat($assignmentScores)
            ->concat($miniScores)
            ->concat($mainScores);

        $totalSubmissionsCount = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)
            ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
            ->count()
            + MiniProjectSubmission::whereIn('mini_project_id', $miniProjectIds)
            ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
            ->count()
            + MainProjectSubmission::whereIn('main_project_id', $mainProjectIds)
            ->when(!empty($studentUserIds), fn($q) => $q->whereIn('user_id', $studentUserIds))
            ->count();

        $avgStudentScore = $allScores->count() > 0 ? round($allScores->avg()) : 0;

        // ==========================================
        // 5. OVERALL PROGRESS (Weighted or Average)
        // ==========================================
        $activeMetrics = [];
        $activeMetrics[] = $courseProgress;
        if ($avgAttendance > 0) $activeMetrics[] = $avgAttendance;
        if ($avgStudentScore > 0) $activeMetrics[] = $avgStudentScore;

        $overall = count($activeMetrics) > 0 ? round(array_sum($activeMetrics) / count($activeMetrics)) : $courseProgress;

        return [
            'course' => $courseProgress,
            'syllabus' => $syllabusDone,
            'attendance' => $avgAttendance,
            'score' => $avgStudentScore,
            'overall' => $overall,
            'total_modules' => $totalModules,
            'completed_modules' => $completedModules,
            'submissions' => $totalSubmissionsCount,
            'total_students' => count($studentUserIds) > 0 ? count($studentUserIds) : ($batch->joined_student_count ?? 0),
        ];
    }
}
