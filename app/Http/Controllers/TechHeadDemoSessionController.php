<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechHeadDemoSessionController extends Controller
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
            ->where('type', 'demo')
            ->where('staff_id', $selectedStaffId)
            ->where('date', $targetDate)
            ->get();
            
        $allBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->where('staff_id', $selectedStaffId)
            ->whereIn('date', array_map(function($d) { return $d->format('Y-m-d'); }, $dateTabs))
            ->orderBy('date')->orderBy('time')
            ->get();

        $slots = [];
        $times = ['11:00 AM', '11:30 AM', '12:00 PM', '12:30 PM', '1:00 PM', '3:00 PM', '3:30 PM', '4:00 PM', '4:30 PM', '5:00 PM'];
        
        foreach ($times as $index => $time) {
            $booking = $dbSlots->firstWhere('time', $time); 
            $slots[] = [
                'id' => $index + 1,
                'time' => $time,
                'status' => $booking ? 'booked' : 'available',
                'name' => $booking ? ($booking->admission->contact->name ?? 'Unknown') : null,
                'course' => $booking ? ($booking->admission->course->title ?? 'N/A') : null,
                'color' => '#3b82f6',
            ];
        }

        $bookedSlots = [];
        foreach($allBookings as $b) {
             $bookedSlots[] = [
                'name' => $b->admission->contact->name ?? 'Unknown',
                'course' => $b->admission->course->title ?? 'N/A',
                'time' => $b->time,
                'date' => \Carbon\Carbon::parse($b->date)->format('d M Y')
            ];
        }

        $admissions = \App\Models\Admission::with(['contact', 'course'])
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing'])
            ->get();

        $assignedDemos = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->where('staff_id', $selectedStaffId)
            ->where('status', 'booked')
            ->orderBy('date', 'desc')->orderBy('time')
            ->get();

        $completedDemos = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('type', 'demo')
            ->where('staff_id', $selectedStaffId)
            ->where('status', 'completed')
            ->orderBy('date', 'desc')->orderBy('time')
            ->get();

        return view('tech-head.demo-sessions.index', compact('slots', 'bookedSlots', 'dateTabs', 'admissions', 'targetDate', 'assignedDemos', 'completedDemos', 'techStaffMembers', 'selectedStaffId'));
    }
}
