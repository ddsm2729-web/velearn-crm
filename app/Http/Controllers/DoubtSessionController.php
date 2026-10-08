<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoubtSessionController extends Controller
{
    public function index()
    {
        \Illuminate\Support\Facades\Log::info('DoubtSessionController index loaded', request()->all());
        $dateTabs = [];
        $currentDate = now();
        while (count($dateTabs) < 4) {
            if (!$currentDate->isWeekend()) {
                $dateTabs[] = $currentDate->copy();
            }
            $currentDate->addDay();
        }

        $userId = auth()->id();
        $staff = \App\Models\Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;
        $isStaff = $staffId !== null;

        $targetDate = request('date', $dateTabs[0]->format('Y-m-d'));

        // 1. SessionBookings by status
        $assignedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')->where('status', 'booked')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('date', 'desc')->get();

        $completedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')->where('status', 'completed')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('date', 'desc')->get();

        $missedSessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')->where('status', 'missed')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('date', 'desc')->get();

        $assignedSupportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where('status', 'Scheduled')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('allocated_date', 'desc')->get();

        $completedSupportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where('status', 'Completed')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('allocated_date', 'desc')->get();

        $missedSupportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where('status', 'Missed')
            ->when($isStaff, fn($q) => $q->where('staff_id', $staffId))
            ->orderBy('allocated_date', 'desc')->get();

        // 2. Unify Assigned Doubts
        $assignedDoubts = collect();
        foreach ($assignedSessionBookings as $sb) {
            $assignedDoubts->push((object)[
                'id'            => $sb->id,
                'type'          => 'session_booking',
                'name'          => $sb->admission->contact->name ?? 'Unknown',
                'course'        => $sb->admission->course->title ?? 'N/A',
                'topic'         => $sb->topic ?? 'N/A',
                'date'          => $sb->date,
                'time'          => $sb->time,
                'zoom_meet_link'=> $sb->zoom_meet_link ?? null,
                'phone'         => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? null,
            ]);
        }
        foreach ($assignedSupportDoubts as $sd) {
            $assignedDoubts->push((object)[
                'id'            => $sd->id,
                'type'          => 'support_allocation',
                'name'          => $sd->ticket->user->name ?? 'Unknown',
                'course'        => 'Doubt (Support)',
                'topic'         => $sd->ticket->subject ?? 'N/A',
                'date'          => $sd->allocated_date,
                'time'          => $sd->allocated_slot,
                'zoom_meet_link'=> null,
                'phone'         => $sd->ticket->user->phonenumber ?? $sd->ticket->user->secondary_phone ?? null,
            ]);
        }
        $assignedDoubts = $assignedDoubts->sortByDesc('date');

        // 3. Unify Completed Doubts
        $completedDoubts = collect();
        foreach ($completedSessionBookings as $sb) {
            $completedDoubts->push((object)[
                'id'            => $sb->id,
                'type'          => 'session_booking',
                'name'          => $sb->admission->contact->name ?? 'Unknown',
                'course'        => $sb->admission->course->title ?? 'N/A',
                'topic'         => $sb->topic ?? 'N/A',
                'date'          => $sb->date,
                'time'          => $sb->time,
                'zoom_meet_link'=> $sb->zoom_meet_link ?? null,
                'phone'         => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? null,
            ]);
        }
        foreach ($completedSupportDoubts as $sd) {
            $completedDoubts->push((object)[
                'id'            => $sd->id,
                'type'          => 'support_allocation',
                'name'          => $sd->ticket->user->name ?? 'Unknown',
                'course'        => 'Doubt (Support)',
                'topic'         => $sd->ticket->subject ?? 'N/A',
                'date'          => $sd->allocated_date,
                'time'          => $sd->allocated_slot,
                'zoom_meet_link'=> null,
                'phone'         => $sd->ticket->user->phonenumber ?? $sd->ticket->user->secondary_phone ?? null,
            ]);
        }
        $completedDoubts = $completedDoubts->sortByDesc('date');

        // 4. Unify Missed Doubts
        $missedDoubts = collect();
        foreach ($missedSessionBookings as $sb) {
            $missedDoubts->push((object)[
                'id'            => $sb->id,
                'type'          => 'session_booking',
                'name'          => $sb->admission->contact->name ?? 'Unknown',
                'course'        => $sb->admission->course->title ?? 'N/A',
                'topic'         => $sb->topic ?? 'N/A',
                'date'          => $sb->date,
                'time'          => $sb->time,
                'zoom_meet_link'=> $sb->zoom_meet_link ?? null,
                'phone'         => $sb->admission->contact->caller_number ?? $sb->admission->contact->secondary_number ?? null,
            ]);
        }
        foreach ($missedSupportDoubts as $sd) {
            $missedDoubts->push((object)[
                'id'            => $sd->id,
                'type'          => 'support_allocation',
                'name'          => $sd->ticket->user->name ?? 'Unknown',
                'course'        => 'Doubt (Support)',
                'topic'         => $sd->ticket->subject ?? 'N/A',
                'date'          => $sd->allocated_date,
                'time'          => $sd->allocated_slot,
                'zoom_meet_link'=> null,
                'phone'         => $sd->ticket->user->phonenumber ?? $sd->ticket->user->secondary_phone ?? null,
            ]);
        }
        $missedDoubts = $missedDoubts->sortByDesc('date');

        // 5. Admissions for booking modal
        $admissions = \App\Models\Admission::with(['contact', 'course'])
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'pending'])
            ->get();

        // 6. All bookings for date tabs and slot grid
        $dbSlotsQuery = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')->where('date', $targetDate);
        $allBookingsQuery = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->whereIn('date', array_map(fn($d) => $d->format('Y-m-d'), $dateTabs))
            ->orderBy('date')->orderBy('time');

        $datesArray = array_map(fn($d) => $d->format('Y-m-d'), $dateTabs);
        $allSupportDoubtsQuery = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where(function ($query) use ($datesArray) {
                foreach ($datesArray as $dateStr) {
                    $query->orWhereDate('allocated_date', $dateStr);
                }
            })
            ->orderBy('allocated_date')->orderBy('allocated_slot');

        if ($isStaff) {
            $dbSlotsQuery->where('staff_id', $staffId);
            $allBookingsQuery->where('staff_id', $staffId);
            $allSupportDoubtsQuery->where('staff_id', $staffId);
        }

        $dbSlots    = $dbSlotsQuery->get();
        $allBookings = $allBookingsQuery->get();
        $allSupportDoubts = $allSupportDoubtsQuery->get();

        $targetDateCarbon = \Carbon\Carbon::parse($targetDate);
        $dayName = $targetDateCarbon->format('l');

        // Schedule-based times
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
            $times = ['11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM'];
        }

        $now     = now();
        $isToday = $targetDateCarbon->isToday();

        // Also check SupportTicketAllocation for doubts that block slots
        $supportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->whereDate('allocated_date', $targetDate)
            ->when($isStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get();
            
        \Illuminate\Support\Facades\Log::info('Fetched supportDoubts', [
            'targetDate' => $targetDate,
            'isStaff' => $isStaff,
            'staffId' => $staffId,
            'count' => $supportDoubts->count(),
            'doubts' => $supportDoubts->pluck('id')
        ]);

        // Check Demo SessionBookings to block slots
        $demoBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')->where('date', $targetDate)
            ->when($isStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get();

        // Check CallLog demo bookings to block slots
        $callLogBookings = \App\Models\CallLog::with('contact')
            ->where('demo_date', $targetDate)
            ->when($isStaff && $staffId, fn($q) => $q->where('assigned_staff_id', $staffId))
            ->get();

        $slots = [];
        foreach ($times as $index => $time) {
            $booking      = $dbSlots->first(fn($b) => $b->time && \Carbon\Carbon::parse($b->time)->format('g:i A') === $time);
            $supportDoubt = $supportDoubts->first(function($d) use ($time) {
                if (!$d->allocated_slot) return false;
                $parsed = \Carbon\Carbon::parse($d->allocated_slot)->format('g:i A');
                \Illuminate\Support\Facades\Log::info('Checking slot match', [
                    'd_id' => $d->id,
                    'allocated_slot' => $d->allocated_slot,
                    'parsed' => $parsed,
                    'time' => $time,
                    'match' => ($parsed === $time)
                ]);
                return $parsed === $time;
            });
            $demoBooking  = $demoBookings->first(fn($b) => $b->time && \Carbon\Carbon::parse($b->time)->format('g:i A') === $time);
            $callLogDemo  = $callLogBookings->first(fn($c) => $c->demo_time && \Carbon\Carbon::parse($c->demo_time)->format('g:i A') === $time);

            $status = ($booking || $supportDoubt || $demoBooking || $callLogDemo) ? 'booked' : 'available';

            $slotTimeCarbon = \Carbon\Carbon::parse($time);
            $isSlotPassed   = $isToday && $now->greaterThan($slotTimeCarbon);

            if ($status === 'available' && $isSlotPassed) {
                $status = 'passed';
            }

            $name = $course = $topic = null;
            if ($booking) {
                $name   = $booking->admission->contact->name ?? 'Unknown';
                $course = $booking->admission->course->title ?? 'N/A';
                $topic  = $booking->topic ?? '';
            } elseif ($supportDoubt) {
                $name   = $supportDoubt->ticket->user->name ?? 'Unknown';
                $course = 'Doubt (Support)';
                $topic  = $supportDoubt->ticket->subject ?? '';
            } elseif ($demoBooking) {
                $name   = $demoBooking->admission->contact->name ?? 'Unknown';
                $course = $demoBooking->admission->course->title ?? 'N/A';
                $topic  = 'Demo Session';
            } elseif ($callLogDemo) {
                $name   = $callLogDemo->contact->name ?? $callLogDemo->lead->name ?? 'Unknown';
                $course = 'Demo (Call Log)';
                $topic  = '';
            }

            $slots[] = [
                'id'       => $index + 1,
                'time'     => $time,
                'status'   => $status,
                'is_passed'=> $isSlotPassed,
                'name'     => $name,
                'course'   => $course,
                'topic'    => $topic,
                'color'    => '#3b82f6',
            ];
        }

        $bookedSlots = [];
        foreach ($allBookings as $b) {
            $bookedSlots[] = [
                'name'   => $b->admission->contact->name ?? 'Unknown',
                'course' => $b->admission->course->title ?? 'N/A',
                'topic'  => $b->topic,
                'time'   => $b->time,
                'date'   => \Carbon\Carbon::parse($b->date)->format('d M Y'),
                'phone'  => $b->admission->contact->caller_number ?? $b->admission->contact->secondary_number ?? null,
            ];
        }

        foreach ($allSupportDoubts as $sd) {
            $bookedSlots[] = [
                'name'   => $sd->ticket->user->name ?? 'Unknown',
                'course' => 'Doubt (Support)',
                'topic'  => $sd->ticket->subject ?? 'N/A',
                'time'   => \Carbon\Carbon::parse($sd->allocated_slot)->format('g:i A'),
                'date'   => \Carbon\Carbon::parse($sd->allocated_date)->format('d M Y'),
                'phone'  => $sd->ticket->user->phonenumber ?? $sd->ticket->user->secondary_phone ?? null,
            ];
        }

        return view('tech.doubt-sessions.index', compact(
            'slots', 'bookedSlots', 'dateTabs', 'admissions', 'targetDate',
            'assignedDoubts', 'completedDoubts', 'missedDoubts'
        ));
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Doubt Resolved,Doubt Missed'
        ]);

        $statusMap = [
            'Doubt Resolved' => 'completed',
            'Doubt Missed'   => 'missed',
        ];
        $dbStatus = $statusMap[$request->status] ?? 'completed';

        if ($type === 'session_booking') {
            $booking = \App\Models\SessionBooking::findOrFail($id);
            $booking->status = $dbStatus;
            $booking->save();
        } elseif ($type === 'support_allocation') {
            $allocation = \App\Models\SupportTicketAllocation::findOrFail($id);
            // Map status for SupportTicketAllocation
            $supportStatusMap = [
                'Doubt Resolved' => 'Completed',
                'Doubt Missed'   => 'Missed',
            ];
            $allocation->status = $supportStatusMap[$request->status] ?? 'Completed';
            $allocation->save();

            // Also update ticket status if completed
            if ($allocation->status === 'Completed') {
                $ticket = $allocation->ticket;
                if ($ticket) {
                    $ticket->status = 'Completed';
                    $ticket->save();
                }
            }
        }

        return back()->with('success', 'Doubt status updated to ' . $request->status);
    }
}
