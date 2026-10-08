<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechHeadDoubtSessionController extends Controller
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

        $techStaffMembers = \App\Models\Staff::whereHas('role', function($q) {
            $q->where('name', 'Technical Staff');
        })->get();

        $selectedStaffId = request('staff_id');
        if (!$selectedStaffId) {
            $currentStaff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if ($currentStaff) {
                $selectedStaffId = $currentStaff->id;
            } elseif ($techStaffMembers->isNotEmpty()) {
                $selectedStaffId = $techStaffMembers->first()->id;
            }
        }

        $targetDate = request('date', $dateTabs[0]->format('Y-m-d'));
        $dbSlots = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->where('staff_id', $selectedStaffId)
            ->where('date', $targetDate)
            ->get();
            
        $allBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->where('staff_id', $selectedStaffId)
            ->whereIn('date', array_map(function($d) { return $d->format('Y-m-d'); }, $dateTabs))
            ->orderBy('date')->orderBy('time')
            ->get();

        $supportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->whereDate('allocated_date', $targetDate)
            ->where('staff_id', $selectedStaffId)
            ->get();

        $callLogBookings = \App\Models\CallLog::with('contact')
            ->whereDate('demo_date', $targetDate)
            ->whereNotNull('demo_time')
            ->where('assigned_staff_id', $selectedStaffId)
            ->get();

        $slots = [];
        $times = ['11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM'];
        
        foreach ($times as $index => $time) {
            $booking = $dbSlots->firstWhere('time', $time); 
            $supportDoubt = $supportDoubts->first(fn($d) => $d->allocated_slot && \Carbon\Carbon::parse($d->allocated_slot)->format('g:i A') === $time);
            $callLogDemo = $callLogBookings->first(fn($c) => $c->demo_time && \Carbon\Carbon::parse($c->demo_time)->format('g:i A') === $time);
            
            $status = ($booking || $supportDoubt || $callLogDemo) ? 'booked' : 'available';
            $name = null;
            $course = null;
            $topic = null;
            
            if ($booking) {
                $name = $booking->admission->contact->name ?? 'Unknown';
                $course = $booking->admission->course->title ?? 'N/A';
                $topic = $booking->topic;
            } elseif ($supportDoubt) {
                $name = $supportDoubt->ticket->user->name ?? 'Unknown';
                $course = 'Support Ticket';
                $topic = $supportDoubt->ticket->title ?? $supportDoubt->ticket->subject ?? 'Doubt Session';
            } elseif ($callLogDemo) {
                $name = $callLogDemo->contact->name ?? 'Unknown';
                $course = 'Call Log Demo';
                $topic = 'Demo Session';
            }

            $slots[] = [
                'id' => $index + 1,
                'time' => $time,
                'status' => $status,
                'name' => $name,
                'course' => $course,
                'topic' => $topic,
                'color' => '#3b82f6',
            ];
        }

        $datesArray = array_map(fn($d) => $d->format('Y-m-d'), $dateTabs);

        $allSupportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where(function ($query) use ($datesArray) {
                foreach ($datesArray as $dateStr) {
                    $query->orWhereDate('allocated_date', $dateStr);
                }
            })
            ->where('staff_id', $selectedStaffId)
            ->orderBy('allocated_date')->orderBy('allocated_slot')
            ->get();

        $allCallLogBookings = \App\Models\CallLog::with('contact')
            ->whereIn('demo_date', $datesArray)
            ->whereNotNull('demo_time')
            ->where('assigned_staff_id', $selectedStaffId)
            ->get();

        $bookedSlots = [];
        foreach($allBookings as $b) {
             $bookedSlots[] = [
                'name' => $b->admission->contact->name ?? 'Unknown',
                'course' => $b->admission->course->title ?? 'N/A',
                'topic' => $b->topic,
                'time' => $b->time,
                'date' => \Carbon\Carbon::parse($b->date)->format('d M Y')
            ];
        }

        foreach ($allSupportDoubts as $sd) {
            $bookedSlots[] = [
                'name'   => $sd->ticket->user->name ?? 'Unknown',
                'course' => 'Support Ticket',
                'topic'  => $sd->ticket->title ?? $sd->ticket->subject ?? 'Doubt Session',
                'time'   => \Carbon\Carbon::parse($sd->allocated_slot)->format('g:i A'),
                'date'   => \Carbon\Carbon::parse($sd->allocated_date)->format('d M Y')
            ];
        }

        foreach ($allCallLogBookings as $c) {
            $bookedSlots[] = [
                'name'   => $c->contact->name ?? 'Unknown',
                'course' => 'Call Log Demo',
                'topic'  => 'Demo Session',
                'time'   => \Carbon\Carbon::parse($c->demo_time)->format('g:i A'),
                'date'   => \Carbon\Carbon::parse($c->demo_date)->format('d M Y')
            ];
        }

        $admissions = \App\Models\Admission::with(['contact', 'course'])
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing'])
            ->get();

        $assignedDoubts = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->where('staff_id', $selectedStaffId)
            ->where('status', 'booked')
            ->orderBy('date', 'desc')->orderBy('time')
            ->get();

        $completedDoubts = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'doubt')
            ->where('staff_id', $selectedStaffId)
            ->where('status', 'completed')
            ->orderBy('date', 'desc')->orderBy('time')
            ->get();

        return view('tech-head.doubt-sessions.index', compact('slots', 'bookedSlots', 'dateTabs', 'admissions', 'targetDate', 'assignedDoubts', 'completedDoubts', 'techStaffMembers', 'selectedStaffId'));
    }
}
