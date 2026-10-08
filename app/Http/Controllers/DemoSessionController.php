<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoSessionController extends Controller
{
    public function index()
    {
        $dateTabs = [];
        $currentDate = now();
        while(count($dateTabs) < 4) {
            if (!$currentDate->isWeekend()) {
                $dateTabs[] = $currentDate->copy();
            }
            $currentDate->addDay();
        }

        $userId = auth()->id();
        $staff = \App\Models\Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;

        $targetDate = request('date', $dateTabs[0]->format('Y-m-d'));

        $isStaff = auth()->user()->hasRole('Technical Staff');

        $dbSlotsQuery = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('date', $targetDate)
            ->where('type', 'demo')
            ->where('status', 'booked');
            
        $allBookingsQuery = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->whereIn('date', array_map(function($d) { return $d->format('Y-m-d'); }, $dateTabs))
            ->orderBy('date')->orderBy('time');

        // Remove the separate query builders and fetch everything into a unified collection
        
        // 1. SessionBookings
        $assignedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')->where('status', 'booked')
            ->when($isStaff, function($q) use ($staffId) { return $q->where('staff_id', $staffId); })
            ->orderBy('date', 'desc')->get();
            
        $completedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')->where('status', 'completed')
            ->when($isStaff, function($q) use ($staffId) { return $q->where('staff_id', $staffId); })
            ->orderBy('date', 'desc')->get();
            
        $missedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')->where('status', 'missed')
            ->when($isStaff, function($q) use ($staffId) { return $q->where('staff_id', $staffId); })
            ->orderBy('date', 'desc')->get();
            
        $lostSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')->where('status', 'lost')
            ->when($isStaff, function($q) use ($staffId) { return $q->where('staff_id', $staffId); })
            ->orderBy('date', 'desc')->get();

        // 2. CallLogs
        $assignedCallLogs = \App\Models\CallLog::with(['contact', 'lead'])
            ->whereNotNull('demo_date')->whereIn('lead_tag', ['Demo booked'])
            ->when($isStaff, function($q) use ($staffId) { return $q->where('assigned_staff_id', $staffId); })
            ->orderBy('demo_date', 'desc')->get();
            
        $completedCallLogs = \App\Models\CallLog::with(['contact', 'lead'])
            ->whereNotNull('demo_date')->whereIn('lead_tag', ['Demo Attended', 'Converted', 'Demo converted'])
            ->when($isStaff, function($q) use ($staffId) { return $q->where('assigned_staff_id', $staffId); })
            ->orderBy('demo_date', 'desc')->get();
            
        $missedCallLogs = \App\Models\CallLog::with(['contact', 'lead'])
            ->whereNotNull('demo_date')->whereIn('lead_tag', ['Demo Missed'])
            ->when($isStaff, function($q) use ($staffId) { return $q->where('assigned_staff_id', $staffId); })
            ->orderBy('demo_date', 'desc')->get();
            
        $lostCallLogs = \App\Models\CallLog::with(['contact', 'lead'])
            ->whereNotNull('demo_date')->whereIn('lead_tag', ['Demo lost', 'Lost'])
            ->when($isStaff, function($q) use ($staffId) { return $q->where('assigned_staff_id', $staffId); })
            ->orderBy('demo_date', 'desc')->get();

        // 3. Unify Assigned Demos
        $assignedDemos = collect();
        foreach ($assignedSessionBookings as $sb) {
            $assignedDemos->push((object)[
                'id' => $sb->id,
                'type' => 'session_booking',
                'name' => $sb->admission->contact->name ?? 'Unknown',
                'course' => $sb->admission->course->title ?? 'N/A',
                'date' => $sb->date,
                'time' => $sb->time,
                'zoom_meet_link' => $sb->zoom_meet_link ?? null,
                'phone' => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? 'N/A',
            ]);
        }
        foreach ($assignedCallLogs as $cl) {
            $assignedDemos->push((object)[
                'id' => $cl->id,
                'type' => 'call_log',
                'name' => $cl->contact->name ?? $cl->lead->name ?? 'Unknown',
                'course' => 'Demo Session',
                'date' => $cl->demo_date,
                'time' => $cl->demo_time,
                'zoom_meet_link' => $cl->zoom_meet_link ?? null,
                'phone' => $cl->contact->caller_number ?? $cl->contact->secondary_number ?? $cl->lead->phone ?? 'N/A',
            ]);
        }
        $assignedDemos = $assignedDemos->sortByDesc('date');

        // 4. Unify Completed Demos
        $completedDemos = collect();
        foreach ($completedSessionBookings as $sb) {
            $completedDemos->push((object)[
                'id' => $sb->id,
                'type' => 'session_booking',
                'name' => $sb->admission->contact->name ?? 'Unknown',
                'course' => $sb->admission->course->title ?? 'N/A',
                'date' => $sb->date,
                'time' => $sb->time,
                'zoom_meet_link' => $sb->zoom_meet_link ?? null,
                'phone' => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? 'N/A',
            ]);
        }
        foreach ($completedCallLogs as $cl) {
            $completedDemos->push((object)[
                'id' => $cl->id,
                'type' => 'call_log',
                'name' => $cl->contact->name ?? $cl->lead->name ?? 'Unknown',
                'course' => 'Demo Session',
                'date' => $cl->demo_date,
                'time' => $cl->demo_time,
                'zoom_meet_link' => $cl->zoom_meet_link ?? null,
                'phone' => $cl->contact->caller_number ?? $cl->contact->secondary_number ?? $cl->lead->phone ?? 'N/A',
            ]);
        }
        $completedDemos = $completedDemos->sortByDesc('date');

        // 5. Unify Missed Demos
        $missedDemos = collect();
        foreach ($missedSessionBookings as $sb) {
            $missedDemos->push((object)[
                'id' => $sb->id,
                'type' => 'session_booking',
                'name' => $sb->admission->contact->name ?? 'Unknown',
                'course' => $sb->admission->course->title ?? 'N/A',
                'date' => $sb->date,
                'time' => $sb->time,
                'zoom_meet_link' => $sb->zoom_meet_link ?? null,
                'phone' => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? 'N/A',
            ]);
        }
        foreach ($missedCallLogs as $cl) {
            $missedDemos->push((object)[
                'id' => $cl->id,
                'type' => 'call_log',
                'name' => $cl->contact->name ?? $cl->lead->name ?? 'Unknown',
                'course' => 'Demo Session',
                'date' => $cl->demo_date,
                'time' => $cl->demo_time,
                'zoom_meet_link' => $cl->zoom_meet_link ?? null,
                'phone' => $cl->contact->caller_number ?? $cl->contact->secondary_number ?? $cl->lead->phone ?? 'N/A',
            ]);
        }
        $missedDemos = $missedDemos->sortByDesc('date');

        // 6. Unify Lost Demos
        $lostDemos = collect();
        foreach ($lostSessionBookings as $sb) {
            $lostDemos->push((object)[
                'id' => $sb->id,
                'type' => 'session_booking',
                'name' => $sb->admission->contact->name ?? 'Unknown',
                'course' => $sb->admission->course->title ?? 'N/A',
                'date' => $sb->date,
                'time' => $sb->time,
                'zoom_meet_link' => $sb->zoom_meet_link ?? null,
                'phone' => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? 'N/A',
            ]);
        }
        foreach ($lostCallLogs as $cl) {
            $lostDemos->push((object)[
                'id' => $cl->id,
                'type' => 'call_log',
                'name' => $cl->contact->name ?? $cl->lead->name ?? 'Unknown',
                'course' => 'Demo Session',
                'date' => $cl->demo_date,
                'time' => $cl->demo_time,
                'zoom_meet_link' => $cl->zoom_meet_link ?? null,
                'phone' => $cl->contact->caller_number ?? $cl->contact->secondary_number ?? $cl->lead->phone ?? 'N/A',
            ]);
        }
        $lostDemos = $lostDemos->sortByDesc('date');

        $admissionsQuery = \App\Models\Admission::with(['contact', 'course'])
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'pending']);

        if ($isStaff) {
            $dbSlotsQuery->where('staff_id', $staffId);
            $allBookingsQuery->where('staff_id', $staffId);
            
            // Removed batch->instructor_id restriction here so that Technical Staff can see all students/leads for Demo Booking
        }

        $dbSlots = $dbSlotsQuery->get();
        $allBookings = $allBookingsQuery->get();
        $admissions = $admissionsQuery->get();

        $targetDateCarbon = \Carbon\Carbon::parse($targetDate);
        $dayName = $targetDateCarbon->format('l');

        $scheduleQuery = \App\Models\StaffSchedule::where('day', $dayName)->where('status', 'available');
        if ($isStaff && $staffId) {
            $scheduleQuery->where('staff_id', $staffId);
        }
        $scheduleTimes = $scheduleQuery->pluck('schedule_time')->unique()->sort();

        $times = [];
        foreach ($scheduleTimes as $st) {
            $times[] = \Carbon\Carbon::parse($st)->format('g:i A');
        }

        if (empty($times)) {
            // Fallback if no schedule is found
            $times = ['11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM'];
        }
        
        $now = now();
        $isToday = $targetDateCarbon->isToday();
        
        // Fetch bookings from CallLog and Doubts to block slots
        $callLogBookings = \App\Models\CallLog::with('contact')
            ->where('demo_date', $targetDate)
            ->when($isStaff && $staffId, function($q) use ($staffId) {
                return $q->where('assigned_staff_id', $staffId);
            })->get();
            
        $datesArray = array_map(function($d) { return $d->format('Y-m-d'); }, $dateTabs);
        $allCallLogBookings = \App\Models\CallLog::with('contact')
            ->whereIn('demo_date', $datesArray)
            ->whereNotNull('demo_time')
            ->when($isStaff && $staffId, function($q) use ($staffId) {
                return $q->where('assigned_staff_id', $staffId);
            })
            ->orderBy('demo_date')->orderBy('demo_time')
            ->get();
            
        $doubtBookings = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->whereDate('allocated_date', $targetDate)
            ->when($isStaff && $staffId, function($q) use ($staffId) {
                return $q->where('staff_id', $staffId);
            })->get();

        // Also check SessionBookings where type=doubt
        $sessionDoubtBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('date', $targetDate)
            ->where('type', 'doubt')
            ->when($isStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get();

        $slots = [];
        foreach ($times as $index => $time) {
            $booking = $dbSlots->first(function($b) use ($time) {
                return $b->time && \Carbon\Carbon::parse($b->time)->format('g:i A') === $time;
            });
            $callLogBooking = $callLogBookings->first(function($c) use ($time) {
                return $c->demo_time && \Carbon\Carbon::parse($c->demo_time)->format('g:i A') === $time;
            });
            $doubtBooking = $doubtBookings->first(function($d) use ($time) {
                return $d->allocated_slot && \Carbon\Carbon::parse($d->allocated_slot)->format('g:i A') === $time;
            });
            $sessionDoubtBooking = $sessionDoubtBookings->first(function($b) use ($time) {
                return $b->time && \Carbon\Carbon::parse($b->time)->format('g:i A') === $time;
            });
            
            $status = ($booking || $callLogBooking || $doubtBooking || $sessionDoubtBooking) ? 'booked' : 'available';
            
            $slotTimeCarbon = \Carbon\Carbon::parse($time);
            $isSlotPassed = $isToday && $now->greaterThan($slotTimeCarbon);
            
            if ($status === 'available' && $isSlotPassed) {
                $status = 'passed';
            }
            
            // Determine name and course to show if booked
            $name = $course = $topic = null;
            if ($booking) {
                $name = $booking->admission->contact->name ?? 'Unknown';
                $course = $booking->admission->course->title ?? 'N/A';
                $topic = $booking->topic ?? '';
            } elseif ($callLogBooking) {
                $name = $callLogBooking->contact->name ?? 'Unknown';
                $course = 'Demo (Call Log)';
                $topic = '';
            } elseif ($doubtBooking) {
                $name = $doubtBooking->ticket->user->name ?? 'Unknown';
                $course = 'Doubt Session';
                $topic = $doubtBooking->ticket->subject ?? '';
            } elseif ($sessionDoubtBooking) {
                $name = $sessionDoubtBooking->admission->contact->name ?? 'Unknown';
                $course = 'Doubt Session';
                $topic = $sessionDoubtBooking->topic ?? '';
            }
            
            $slots[] = [
                'id' => $index + 1,
                'time' => $time,
                'status' => $status,
                'is_passed' => $isSlotPassed,
                'name' => $name,
                'course' => $course,
                'topic' => $topic,
                'color' => '#3b82f6',
            ];
        }

        $bookedSlots = [];
        foreach($allBookings as $b) {
             $bookedSlots[] = [
                'name' => $b->admission->contact->name ?? 'Unknown',
                'course' => $b->admission->course->title ?? 'N/A',
                'time' => $b->time,
                'date' => \Carbon\Carbon::parse($b->date)->format('d M Y'),
                'phone' => $b->admission->contact->caller_number ?? $b->admission->contact->secondary_number ?? null,
            ];
        }
        // Also include CallLog bookings for this date
        foreach($allCallLogBookings as $cl) {
            $bookedSlots[] = [
                'name' => $cl->contact->name ?? $cl->lead->name ?? 'Unknown',
                'course' => 'Demo (Call Log)',
                'time' => \Carbon\Carbon::parse($cl->demo_time)->format('g:i A'),
                'date' => \Carbon\Carbon::parse($cl->demo_date)->format('d M Y'),
                'phone' => $cl->contact->caller_number ?? $cl->contact->secondary_number ?? $cl->lead->phone ?? null,
            ];
        }

        return view('tech.demo-sessions.index', compact('slots', 'bookedSlots', 'dateTabs', 'admissions', 'targetDate', 'assignedDemos', 'completedDemos', 'missedDemos', 'lostDemos'));
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Demo Attended,Demo Missed,Demo converted,Demo lost'
        ]);

        if ($type === 'call_log') {
            $log = \App\Models\CallLog::findOrFail($id);
            $log->update(['lead_tag' => $request->status]);
            
            if (in_array($request->status, ['Demo Missed', 'Demo lost']) && $log->zoom_meeting_id) {
                try {
                    $zoomService = new \App\Services\ZoomService();
                    $zoomService->deleteMeeting($log->zoom_meeting_id);
                    $log->update(['zoom_meet_link' => null, 'zoom_meeting_id' => null]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to delete zoom meeting: ' . $e->getMessage());
                }
            }
        } elseif ($type === 'session_booking') {
            $booking = \App\Models\SessionBooking::findOrFail($id);
            
            $statusMap = [
                'Demo Attended' => 'completed',
                'Demo Missed' => 'missed',
                'Demo lost' => 'lost'
            ];
            
            $booking->update(['status' => $statusMap[$request->status]]);
            
            if (in_array($request->status, ['Demo Missed', 'Demo lost']) && $booking->zoom_meeting_id) {
                try {
                    $zoomService = new \App\Services\ZoomService();
                    $zoomService->deleteMeeting($booking->zoom_meeting_id);
                    $booking->update(['zoom_meet_link' => null, 'zoom_meeting_id' => null]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to delete zoom meeting: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'Demo status updated successfully!');
    }
}
