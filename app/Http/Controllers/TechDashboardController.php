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

        $todayActivities = [
            ['name' => 'Priya Sharma', 'action' => 'Submitted Assignment: React To-Do App', 'initials' => 'P', 'color' => '#6366f1', 'batch' => 'FS-2024-A', 'batch_type' => 'Weekday'],
            ['name' => 'Rahul Verma', 'action' => 'Attended Live Class: Module 9 - Redux', 'initials' => 'R', 'color' => '#4f46e5', 'batch' => 'FS-2024-A', 'batch_type' => 'Weekday'],
            ['name' => 'Anjali Singh', 'action' => 'Submitted Mini Project: Portfolio Website', 'initials' => 'A', 'color' => '#4338ca', 'batch' => 'FS-2024-B', 'batch_type' => 'Weekend'],
            ['name' => 'Arun Kumar', 'action' => 'Attended Live Class: Module 15 - Deployment', 'initials' => 'A', 'color' => '#4338ca', 'batch' => 'FS-2024-C', 'batch_type' => 'Fast Track'],
        ];

        return view('tech.dashboard.index', compact('totalStudents', 'totalBatches', 'todayDemos', 'todayDoubts', 'todayActivities'));
    }
}
