<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentCoordinatorDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $dateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('created_at', '>=', $startDate)
                  ->whereDate('created_at', '<=', $endDate);
            }
        };

        $liveCourses = \App\Models\Course::where('course_type', 'live')->where('status', '1')->get();

        $coursesData = [];
        $totals = [
            'total_courses' => $liveCourses->count(),
            'main_total' => 0, 'main_active' => 0, 'main_pending' => 0,
            'mini_total' => 0, 'mini_active' => 0, 'mini_pending' => 0,
            'assignment_total' => 0, 'assignment_active' => 0, 'assignment_pending' => 0,
            'students_total' => 0, 'batches_total' => 0,
            'staff_total' => \App\Models\Staff::where('status', 1)->orWhere('status', 'active')->count()
        ];

        foreach ($liveCourses as $course) {
            $mainProjects = \App\Models\MainProject::where('course_id', $course->id)->where($dateFilter)->get();
            $miniProjects = \App\Models\MiniProject::where('course_id', $course->id)->where($dateFilter)->get();
            $assignments = \App\Models\Assignment::where('course_id', $course->id)->where($dateFilter)->get();
            $studentsCount = \App\Models\Admission::where('course_id', $course->id)->where($dateFilter)->count();
            $batchesCount = \App\Models\Batch::where('course_id', $course->id)->count();

            $mainCount = $mainProjects->count();
            $mainActive = $mainProjects->where('status', 'active')->count();
            $mainPending = $mainProjects->where('status', 'pending')->count();

            $miniCount = $miniProjects->count();
            $miniActive = $miniProjects->where('status', 'active')->count();
            $miniPending = $miniProjects->where('status', 'pending')->count();

            $assignCount = $assignments->count();
            $assignActive = $assignments->where('status', 'active')->count();
            $assignPending = $assignments->where('status', 'pending')->count();

            $coursesData[] = [
                'course' => $course,
                'main_count' => $mainCount,
                'main_active' => $mainActive,
                'main_pending' => $mainPending,
                'mini_count' => $miniCount,
                'mini_active' => $miniActive,
                'mini_pending' => $miniPending,
                'assignment_count' => $assignCount,
                'assignment_active' => $assignActive,
                'assignment_pending' => $assignPending,
                'students_count' => $studentsCount,
                'batches_count' => $batchesCount,
            ];

            $totals['main_total'] += $mainCount;
            $totals['main_active'] += $mainActive;
            $totals['main_pending'] += $mainPending;

            $totals['mini_total'] += $miniCount;
            $totals['mini_active'] += $miniActive;
            $totals['mini_pending'] += $miniPending;

            $totals['assignment_total'] += $assignCount;
            $totals['assignment_active'] += $assignActive;
            $totals['assignment_pending'] += $assignPending;

            $totals['students_total'] += $studentsCount;
            $totals['batches_total'] += $batchesCount;
        }

        return view('student-coordinator.dashboard.index', compact('coursesData', 'totals'));
    }
}
