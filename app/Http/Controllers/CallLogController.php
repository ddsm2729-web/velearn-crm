<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallLog;
use App\Models\Staff;

class CallLogController extends Controller
{
    public function index(Request $request)
    {
        // Get all staff with their latest 20 call logs to allow unique grouping
        $staffs = Staff::with(['callLogs' => function ($query) {
            $query->latest('call_start')->take(20);
        }, 'role'])
        ->whereHas('callLogs')
        ->get();
        
        // Get unassigned calls (system calls)
        $unassignedCalls = CallLog::whereNull('staff_id')
            ->latest('call_start')
            ->take(20)
            ->get();

        $stats = [
            'total' => CallLog::count(),
            'incoming' => CallLog::where('direction', 'incoming')->count(),
            'outgoing' => CallLog::where('direction', 'outgoing')->count(),
            'avg_duration' => CallLog::avg('call_duration') ?? 0, 
        ];

        return view('calls.history', compact('staffs', 'unassignedCalls', 'stats'));
    }

    public function staffHistory($staffId)
    {
        $staff = Staff::with('role')->findOrFail($staffId);
        $callLogs = CallLog::where('staff_id', $staffId)->latest('call_start')->paginate(20);

        return view('calls.staff_history', compact('staff', 'callLogs'));
    }
    
    public function unassignedHistory()
    {
        $callLogs = CallLog::whereNull('staff_id')->latest('call_start')->paginate(20);
        return view('calls.unassigned_history', compact('callLogs'));
    }
}
