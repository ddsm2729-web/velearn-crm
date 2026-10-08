<?php

namespace App\Http\Controllers;

use App\Models\ScheduleInterview;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InterviewStatusController extends Controller
{
    /**
     * Display a listing of scheduled interviews with student and company tracking.
     */
    public function index(Request $request): View
    {
        $query = ScheduleInterview::with(['user', 'courseUser'])
            ->orderByDesc('date')
            ->orderByDesc('time');

        // Search filter (student name, company, role, phone, email, course)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%")
                    ->orWhere('batch_code', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status') && strtolower($request->status) !== 'all') {
            $query->where('status', strtolower($request->status));
        }

        // Company filter
        if ($request->filled('company') && strtolower($request->company) !== 'all') {
            $query->where('company', $request->company);
        }

        // Date filter
        if ($request->filled('date') && strtolower($request->date) !== 'all') {
            $query->whereDate('date', $request->date);
        }

        // Month filter
        if ($request->filled('month') && strtolower($request->month) !== 'all') {
            $query->whereMonth('date', (int) $request->month);
        }

        $interviews = $query->paginate(25)->withQueryString();

        // Unique filter options
        $companies = ScheduleInterview::distinct()
            ->whereNotNull('company')
            ->where('company', '!=', '')
            ->pluck('company')
            ->sort()
            ->values();

        $availableDates = ScheduleInterview::distinct()
            ->whereNotNull('date')
            ->orderByDesc('date')
            ->pluck('date')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->unique()
            ->values();

        // Company-grouped data for Company Interview Status tab
        $groupedByCompany = (clone $query)->get()->groupBy('company');

        $activeTab = $request->get('tab', 'students');

        return view('dashboards.placement_interview_status', compact(
            'interviews',
            'companies',
            'availableDates',
            'groupedByCompany',
            'activeTab'
        ));
    }
}
