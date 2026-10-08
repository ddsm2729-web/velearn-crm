<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Staff;
use App\Models\SessionBooking;

class SessionBookingController extends Controller
{
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('SessionBooking store request received', $request->all());
        
        $request->validate([
            'type'         => 'required|in:demo,doubt',
            'admission_id' => 'required|exists:admissions,id',
            'date'         => 'required|date',
            'time'         => 'required|string',
            'topic'        => 'nullable|string',
            'notes'        => 'nullable|string',
            'staff_id'     => 'nullable|exists:staff,id',
        ]);

        \Illuminate\Support\Facades\Log::info('SessionBooking store started', $request->all());

        $selectedStaffId = $request->staff_id;
        if (!$selectedStaffId || (!auth()->user()->hasRole('Technical Head') && !auth()->user()->hasRole('SuperAdmin'))) {
            $userId = auth()->id();
            $staff = Staff::where('auth_id', $userId)->first();
            if (!$staff) {
                \Illuminate\Support\Facades\Log::info('Staff not found for user', ['user_id' => $userId]);
                return back()->with('error', 'Staff record not found');
            }
            $selectedStaffId = $staff->id;
        }

        $formattedDate = \Carbon\Carbon::parse($request->date)->format('Y-m-d');

        $existsInSession = SessionBooking::where('staff_id', $selectedStaffId)
            ->where('date', $formattedDate)
            ->where('time', $request->time)
            ->exists();
            
        $existsInCallLog = \App\Models\CallLog::where('assigned_staff_id', $selectedStaffId)
            ->where('demo_date', $formattedDate)
            ->where('demo_time', $request->time)
            ->exists();
            
        $existsInDoubts = \App\Models\SupportTicketAllocation::where('staff_id', $selectedStaffId)
            ->whereDate('allocated_date', $formattedDate)
            ->where('allocated_slot', $request->time)
            ->whereIn('status', ['Scheduled', 'Completed'])
            ->exists();

        if ($existsInSession || $existsInCallLog || $existsInDoubts) {
            \Illuminate\Support\Facades\Log::info('Slot already booked', [
                'existsInSession' => $existsInSession,
                'existsInCallLog' => $existsInCallLog,
                'existsInDoubts'  => $existsInDoubts,
            ]);
            return back()->with('error', 'This slot is already booked. Please choose another time.');
        }

        $booking = SessionBooking::create([
            'type'         => $request->type,
            'staff_id'     => $selectedStaffId,
            'admission_id' => $request->admission_id,
            'date'         => $formattedDate,
            'time'         => $request->time,
            'topic'        => $request->topic,
            'notes'        => $request->notes,
            'status'       => 'booked'
        ]);
        
        try {
            $zoomService = new \App\Services\ZoomService();
            // Start time format for Zoom: YYYY-MM-DDTHH:MM:SS
            // time is like '11:00 AM'
            $timeParsed = \Carbon\Carbon::parse($request->time);
            $startTimeString = \Carbon\Carbon::parse($formattedDate)->setTime($timeParsed->hour, $timeParsed->minute)->format('Y-m-d\TH:i:s');
            $topic = $request->type === 'demo' ? 'Demo Session' : 'Doubt Session';
            
            $staffMember = Staff::find($selectedStaffId);
            $alternativeHosts = $staffMember && $staffMember->email ? $staffMember->email : '';

            $meeting = $zoomService->createMeeting($topic, $startTimeString, 60, 'Asia/Kolkata', $alternativeHosts); // 60 mins duration
            
            if ($meeting && isset($meeting['join_url'])) {
                $booking->update([
                    'zoom_meeting_id' => $meeting['id'] ?? null,
                    'zoom_meet_link' => $meeting['join_url'],
                ]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to create zoom meeting for Session Booking: ' . $e->getMessage());
        }

        return back()->with('success', ucfirst($request->type) . ' slot booked successfully!');
    }

    public function complete($id)
    {
        $booking = SessionBooking::findOrFail($id);
        $booking->update(['status' => 'completed']);
        return back()->with('success', ucfirst($booking->type) . ' marked as completed successfully!');
    }
}
