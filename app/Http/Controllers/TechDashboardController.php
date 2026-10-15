<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class TechDashboardController extends Controller
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

        $sessionDateFilter = function($q) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $q->whereDate('date', '>=', $startDate)
                  ->whereDate('date', '<=', $endDate);
            }
        };

        $fsStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Full Stack%'); })->where($dateFilter)->count();
        $uiuxStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%UI%UX%'); })->where($dateFilter)->count();
        $dmStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Digital Marketing%'); })->where($dateFilter)->count();
        $dsStudents = \App\Models\Admission::whereHas('course', function($q) { $q->where('title', 'like', '%Data Science%'); })->where($dateFilter)->count();
        $totalDemosToday = \App\Models\SessionBooking::where('type', 'demo')->where($sessionDateFilter)->count();

        $fsBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Full Stack%'); })->count();
        $uiuxBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%UI%UX%'); })->count();
        $dmBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Digital Marketing%'); })->count();
        $dsBatches = \App\Models\Batch::whereHas('course', function($q) { $q->where('title', 'like', '%Data Science%'); })->count();
        $totalDoubtsToday = \App\Models\SessionBooking::where('type', 'doubt')->where($sessionDateFilter)->count();

        $totalStudents = [
            'full_stack' => ['count' => $fsStudents, 'batches' => $fsBatches, 'color' => '#f59e0b', 'icon' => 'fas fa-bolt'],
            'ui_ux' => ['count' => $uiuxStudents, 'batches' => $uiuxBatches, 'color' => '#ec4899', 'icon' => 'fas fa-palette'],
            'digital_mktg' => ['count' => $dmStudents, 'batches' => $dmBatches, 'color' => '#f59e0b', 'icon' => 'fas fa-bullhorn'],
            'data_science' => ['count' => $dsStudents, 'batches' => $dsBatches, 'color' => '#10b981', 'icon' => 'fas fa-chart-bar'],
            'total_demos' => ['count' => $totalDemosToday, 'subtext' => 'today', 'color' => '#ef4444', 'icon' => 'fas fa-bullseye'],
        ];

        $totalBatches = [
            'fs_batches' => ['count' => $fsBatches, 'color' => '#f59e0b'],
            'uiux_batches' => ['count' => $uiuxBatches, 'color' => '#ec4899'],
            'dm_batches' => ['count' => $dmBatches, 'color' => '#f59e0b'],
            'ds_batches' => ['count' => $dsBatches, 'color' => '#10b981'],
            'total_doubts' => ['count' => $totalDoubtsToday, 'subtext' => 'today', 'color' => '#a855f7', 'icon' => 'fas fa-comment-dots'],
        ];

        $colors = ['#3b82f6', '#ec4899', '#10b981', '#f59e0b', '#8b5cf6'];
        
        $userId = auth()->id();
        // Revert to using Staff with auth_id
        $staff = \App\Models\Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;

        $todayDemosRaw = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->where('staff_id', $staffId)
            ->where($sessionDateFilter)
            ->orderBy('time')
            ->get();
            
        $todayDemos = [];
        foreach($todayDemosRaw as $i => $demo) {
            $name = $demo->admission->contact->name ?? 'Unknown';
            $words = explode(' ', $name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            $todayDemos[] = [
                'name' => $name,
                'phone' => $demo->admission->contact->phone ?? '',
                'course' => $demo->admission->course->title ?? '',
                'time' => \Carbon\Carbon::parse($demo->time)->format('h:i A'),
                'initials' => $initials,
                'color' => $colors[$i % count($colors)]
            ];
        }

        $todayDoubtsRaw = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->where('staff_id', $staffId)
            ->where($sessionDateFilter)
            ->orderBy('time')
            ->get();
            
        $todayDoubts = [];
        foreach($todayDoubtsRaw as $i => $doubt) {
            $name = $doubt->admission->contact->name ?? 'Unknown';
            $words = explode(' ', $name);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
            $todayDoubts[] = [
                'name' => $name,
                'phone' => $doubt->admission->contact->phone ?? '',
                'course' => $doubt->admission->course->title ?? '',
                'time' => \Carbon\Carbon::parse($doubt->time)->format('h:i A'),
                'initials' => $initials,
                'topic' => $doubt->topic ?? '',
                'color' => $colors[($i+2) % count($colors)]
            ];
        }

        // Helper closures for initials and colors
        $getColor = function($str) {
            $palette = ['#6366f1', '#ec4899', '#10b981', '#f59e0b', '#3b82f6', '#8b5cf6', '#06b6d4', '#14b8a6'];
            return $palette[abs(crc32($str ?? 'VE')) % count($palette)];
        };

        $getInitials = function($name) {
            $name = trim($name ?: 'Student');
            $parts = preg_split('/\s+/', $name);
            return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
        };

        $todayActivities = collect();

        // 1. Assignment Submissions
        $assignmentSubs = \App\Models\AssignmentSubmission::with(['assignment.course', 'assignment.batch', 'student'])
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->latest('created_at')
            ->take(15)
            ->get();

        foreach ($assignmentSubs as $sub) {
            $studentName = $sub->student->name ?? 'Student';
            $batchCode = $sub->assignment->batch->batch_code ?? 'Batch';
            $batchType = $sub->assignment->batch->batch_type ?? 'Weekday';
            if ($sub->assignment && $sub->assignment->batch && $sub->assignment->batch->batch_track) {
                $batchType .= ' (' . $sub->assignment->batch->batch_track . ')';
            }
            $todayActivities->push([
                'id' => 'assignment_' . $sub->id,
                'type' => 'assignment',
                'type_label' => 'Assignment',
                'type_icon' => 'fas fa-file-alt',
                'type_color' => '#6366f1',
                'name' => $studentName,
                'initials' => $getInitials($studentName),
                'color' => $getColor($studentName),
                'action' => 'Submitted Assignment: ' . ($sub->assignment->title ?? 'Assignment Task'),
                'course' => $sub->assignment->course->title ?? '',
                'batch' => $batchCode,
                'batch_type' => $batchType,
                'time' => $sub->created_at ? $sub->created_at->format('h:i A') : '',
                'date' => $sub->created_at ? $sub->created_at->format('d M') : '',
                'timestamp' => $sub->created_at ? $sub->created_at->timestamp : 0,
                'link' => route('assignments.evaluate', $sub->id),
                'link_text' => 'Evaluate'
            ]);
        }

        // 2. Mini Project Submissions
        $miniSubs = \App\Models\MiniProjectSubmission::with(['project.course', 'project.batch', 'student'])
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->latest('created_at')
            ->take(15)
            ->get();

        foreach ($miniSubs as $sub) {
            $studentName = $sub->student->name ?? 'Student';
            $project = $sub->project ?? $sub->mini_project;
            $batchCode = $project->batch->batch_code ?? 'Batch';
            $batchType = $project->batch->batch_type ?? 'Weekend';
            if ($project && $project->batch && $project->batch->batch_track) {
                $batchType .= ' (' . $project->batch->batch_track . ')';
            }
            $todayActivities->push([
                'id' => 'mini_' . $sub->id,
                'type' => 'mini_project',
                'type_label' => 'Mini Project',
                'type_icon' => 'fas fa-layer-group',
                'type_color' => '#ec4899',
                'name' => $studentName,
                'initials' => $getInitials($studentName),
                'color' => $getColor($studentName),
                'action' => 'Submitted Mini Project: ' . ($project->title ?? 'Mini Project'),
                'course' => $project->course->title ?? '',
                'batch' => $batchCode,
                'batch_type' => $batchType,
                'time' => $sub->created_at ? $sub->created_at->format('h:i A') : '',
                'date' => $sub->created_at ? $sub->created_at->format('d M') : '',
                'timestamp' => $sub->created_at ? $sub->created_at->timestamp : 0,
                'link' => route('mini_projects.evaluate', $sub->id),
                'link_text' => 'Evaluate'
            ]);
        }

        // 3. Main Project Submissions
        $mainSubs = \App\Models\MainProjectSubmission::with(['project.course', 'project.batch', 'student'])
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
            })
            ->latest('created_at')
            ->take(15)
            ->get();

        foreach ($mainSubs as $sub) {
            $studentName = $sub->student->name ?? 'Student';
            $project = $sub->project ?? $sub->main_project;
            $batchCode = $project->batch->batch_code ?? 'Batch';
            $batchType = $project->batch->batch_type ?? 'Normal';
            if ($project && $project->batch && $project->batch->batch_track) {
                $batchType .= ' (' . $project->batch->batch_track . ')';
            }
            $todayActivities->push([
                'id' => 'main_' . $sub->id,
                'type' => 'main_project',
                'type_label' => 'Main Project',
                'type_icon' => 'fas fa-project-diagram',
                'type_color' => '#10b981',
                'name' => $studentName,
                'initials' => $getInitials($studentName),
                'color' => $getColor($studentName),
                'action' => 'Submitted Main Project: ' . ($project->title ?? 'Main Project'),
                'course' => $project->course->title ?? '',
                'batch' => $batchCode,
                'batch_type' => $batchType,
                'time' => $sub->created_at ? $sub->created_at->format('h:i A') : '',
                'date' => $sub->created_at ? $sub->created_at->format('d M') : '',
                'timestamp' => $sub->created_at ? $sub->created_at->timestamp : 0,
                'link' => route('main_projects.evaluate', $sub->id),
                'link_text' => 'Evaluate'
            ]);
        }

        // 4. Batch Scheduled Classes
        $schedulesQuery = \App\Models\BatchSchedule::with(['batch.course', 'batch.instructor']);
        if ($startDate && $endDate) {
            $schedulesQuery->whereBetween('date', [$startDate, $endDate]);
        } else {
            $schedulesQuery->where('date', '>=', now()->subDays(3)->toDateString())
                           ->where('date', '<=', now()->addDays(7)->toDateString());
        }
        $batchSchedules = $schedulesQuery->orderBy('date', 'desc')->orderBy('start_time', 'asc')->take(15)->get();

        foreach ($batchSchedules as $sched) {
            $instructorName = $sched->batch->instructor->name ?? ($sched->batch->batch_name ?? 'Instructor');
            $startTime = \Carbon\Carbon::parse($sched->start_time)->format('h:i A');
            $endTime = \Carbon\Carbon::parse($sched->end_time)->format('h:i A');
            $batchCode = $sched->batch->batch_code ?? 'Batch';
            $batchType = $sched->batch->batch_type ?? 'Weekday';
            if ($sched->batch && $sched->batch->batch_track) {
                $batchType .= ' (' . $sched->batch->batch_track . ')';
            }
            $ts = \Carbon\Carbon::parse($sched->date . ' ' . $sched->start_time)->timestamp;

            $todayActivities->push([
                'id' => 'sched_' . $sched->id,
                'type' => 'schedule',
                'type_label' => 'Live Class',
                'type_icon' => 'fas fa-calendar-alt',
                'type_color' => '#f59e0b',
                'name' => $instructorName,
                'initials' => $getInitials($instructorName),
                'color' => $getColor($instructorName),
                'action' => 'Scheduled Class: ' . ($sched->batch->course->title ?? 'Live Session') . ' (' . $startTime . ' - ' . $endTime . ')',
                'course' => $sched->batch->course->title ?? '',
                'batch' => $batchCode,
                'batch_type' => $batchType,
                'time' => $startTime,
                'date' => \Carbon\Carbon::parse($sched->date)->format('d M'),
                'timestamp' => $ts,
                'link' => $sched->meeting_link ?: route('batches.show', $sched->batch_id),
                'link_text' => $sched->meeting_link ? 'Join Meeting' : 'View Batch'
            ]);
        }

        // 5. Meetings (Demos, Doubts, Staff Meetings)
        $meetingsQuery = \App\Models\SessionBooking::with(['admission.contact', 'admission.course', 'admission.batch', 'staff']);
        if ($startDate && $endDate) {
            $meetingsQuery->whereBetween('date', [$startDate, $endDate]);
        } else {
            $meetingsQuery->where('date', '>=', now()->subDays(3)->toDateString())
                          ->where('date', '<=', now()->addDays(7)->toDateString());
        }
        $meetings = $meetingsQuery->orderBy('date', 'desc')->orderBy('time', 'asc')->take(15)->get();

        foreach ($meetings as $meeting) {
            $studentName = $meeting->admission->contact->name ?? 'Student';
            $sessionType = ucfirst($meeting->type ?? 'Meeting');
            $time = $meeting->time ? \Carbon\Carbon::parse($meeting->time)->format('h:i A') : '';
            $batchCode = $meeting->admission->batch->batch_code ?? ($sessionType === 'Demo' ? 'DEMO' : 'DOUBT');
            $batchType = $meeting->admission->batch->batch_type ?? '1-on-1';
            $ts = \Carbon\Carbon::parse($meeting->date . ' ' . ($meeting->time ?? '00:00:00'))->timestamp;

            $todayActivities->push([
                'id' => 'meet_' . $meeting->id,
                'type' => 'meeting',
                'type_label' => $sessionType . ' Session',
                'type_icon' => 'fas fa-video',
                'type_color' => '#8b5cf6',
                'name' => $studentName,
                'initials' => $getInitials($studentName),
                'color' => $getColor($studentName),
                'action' => $sessionType . ' Meeting: ' . ($meeting->admission->course->title ?? $meeting->topic ?? 'Online Meeting') . ($time ? ' at ' . $time : ''),
                'course' => $meeting->admission->course->title ?? '',
                'batch' => $batchCode,
                'batch_type' => $batchType,
                'time' => $time,
                'date' => $meeting->date ? \Carbon\Carbon::parse($meeting->date)->format('d M') : '',
                'timestamp' => $ts,
                'link' => $meeting->meet_link,
                'link_text' => $meeting->meet_link ? 'Connect' : ''
            ]);
        }

        // Sort all activities descending by timestamp
        $todayActivities = $todayActivities->sortByDesc('timestamp')->values();

        // Calculate counts for filter tabs
        $activityCounts = [
            'all' => $todayActivities->count(),
            'assignment' => $todayActivities->where('type', 'assignment')->count(),
            'mini_project' => $todayActivities->where('type', 'mini_project')->count(),
            'main_project' => $todayActivities->where('type', 'main_project')->count(),
            'schedule' => $todayActivities->where('type', 'schedule')->count(),
            'meeting' => $todayActivities->where('type', 'meeting')->count(),
        ];

        return view('tech.dashboard.index', compact('totalStudents', 'totalBatches', 'todayDemos', 'todayDoubts', 'todayActivities', 'activityCounts'));
    }
}
