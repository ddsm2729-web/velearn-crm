<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallLog;

class DemoTrackingController extends Controller
{
    public function index(Request $request)
    {
        $query = CallLog::with(['contact', 'assignedStaff']);

        $status = $request->get('status', 'All');

        if ($status !== 'All') {
            $query->where('lead_tag', $status);
        } else {
            // "All" tab: Latest record per contact that is EITHER a scheduled demo OR has a demo-related tag
            $query->whereIn('id', function($q) {
                $q->selectRaw('MAX(id)')
                  ->from('call_logs')
                  ->where(function($sub) {
                      $sub->whereNotNull('demo_date')
                          ->orWhereIn('lead_tag', ['Demo booked', 'Demo Attended', 'Demo Missed', 'Converted', 'Lost']);
                  })
                  ->groupBy('contact_id', 'lead_id');
            });
        }
        
        if (auth()->check() && auth()->user()->hasRole('Technical Staff')) {
            $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if ($staff) {
                $query->where('assigned_staff_id', $staff->id);
            } else {
                $query->where('id', 0); // No staff record found for user
            }
        }

        $demos = $query->orderByRaw('demo_date IS NULL, demo_date ASC, demo_time ASC')
            ->paginate(15);

        return view('demo.tracking', compact('demos'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Demo Attended,Demo Missed,Converted,Lost',
        ]);

        $log = CallLog::findOrFail($id);
        $log->lead_tag = $request->status;

        if (in_array($request->status, ['Demo Missed', 'Lost']) && $log->zoom_meeting_id) {
            try {
                $zoomService = new \App\Services\ZoomService();
                $zoomService->deleteMeeting($log->zoom_meeting_id);
                $log->zoom_meet_link = null;
                $log->zoom_meeting_id = null;
            } catch (\Exception $e) {
                \Log::error('Failed to delete zoom meeting: ' . $e->getMessage());
            }
        }

        $log->save();

        return response()->json(['status' => true, 'message' => 'Status updated successfully']);
    }
}
