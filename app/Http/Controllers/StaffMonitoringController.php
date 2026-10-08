<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffMonitoringController extends Controller
{
    public function index()
    {
        $staffMembers = \App\Models\User::role('Technical Staff')->with('staffProfile')->get();
        return view('tech.staff-monitoring.index', compact('staffMembers'));
    }

    public function show($id)
    {
        $user = \App\Models\User::with('staffProfile')->findOrFail($id);
        $staff = $user->staffProfile;
        $staffId = $staff ? $staff->id : null;

        $batches = \App\Models\Batch::with('course')
            ->where('instructor_id', $user->id)
            ->orWhere('instructor_id', $staffId)
            ->get();

        $pendingDemos = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->whereIn('staff_id', array_filter([$user->id, $staffId]))
            ->where('status', 'booked')
            ->orderBy('date')->orderBy('time')
            ->get();

        $pendingDoubts = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->whereIn('staff_id', array_filter([$user->id, $staffId]))
            ->where('status', 'booked')
            ->orderBy('date')->orderBy('time')
            ->get();

        return view('tech.staff-monitoring.show', compact('user', 'staff', 'batches', 'pendingDemos', 'pendingDoubts'));
    }

    public function scheduleMeeting(Request $request, $id)
    {
        $request->validate([
            'meeting_type' => 'required',
            'title' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        \App\Models\StaffMeeting::create([
            'meeting_type' => $request->meeting_type,
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'staff_id' => $id, // ID is the route parameter
            'agenda' => $request->agenda,
            'meet_link' => (function() use ($request, $id) {
                if (!empty($request->meet_link)) return $request->meet_link;
                try {
                    $zoomService = new \App\Services\ZoomService();
                    $startTime = \Carbon\Carbon::parse($request->date . ' ' . $request->time)->format('Y-m-d\TH:i:s');
                    
                    $user = \App\Models\User::find($id);
                    $alternativeHosts = $user && $user->email ? $user->email : '';

                    $zoomMeeting = $zoomService->createMeeting($request->title ?? 'Staff Meeting', $startTime, 45, 'Asia/Kolkata', $alternativeHosts);
                    if ($zoomMeeting && isset($zoomMeeting['join_url'])) return $zoomMeeting['join_url'];
                } catch (\Exception $e) { \Log::error('Zoom error: ' . $e->getMessage()); }
                return null;
            })()
        ]);

        return response()->json(['success' => true, 'message' => 'Meeting scheduled successfully']);
    }
}
