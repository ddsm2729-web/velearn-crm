<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class MeetingController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $user = auth()->user();
        $isStaff = $user->hasRole('Technical Staff');
        
        $staff = \App\Models\Staff::where('auth_id', $userId)->first();
        $staffId = $staff ? $staff->id : null;

        $today = Carbon::today()->toDateString();
        
        $startDate = request('start_date', $today);
        $endDate = request('end_date');

        // 1. CallLog Demos
        $callLogDemos = \App\Models\CallLog::with('contact')
            ->whereNotNull('demo_date')
            ->whereNotNull('demo_time')
            ->where('demo_date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('demo_date', '<=', $endDate))
            ->where(function ($q) {
                $q->whereNull('lead_tag')
                  ->orWhereNotIn('lead_tag', ['Demo Missed', 'Lost']);
            })
            ->when($isStaff && $staffId, fn($q) => $q->where('assigned_staff_id', $staffId))
            ->get()
            ->map(function($d) {
                $date = $d->demo_date instanceof Carbon ? $d->demo_date->toDateString() : $d->demo_date;
                $time = Carbon::parse($d->demo_time)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($d->demo_time)->format('H:i'));
                return (object)[
                    'type' => 'Demo',
                    'title' => 'Demo Session',
                    'student_name' => $d->contact->name ?? 'Unknown',
                    'topic' => null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $d->zoom_meet_link,
                    'status' => 'upcoming'
                ];
            });

        // 2. SessionBooking (Demo & Doubt)
        $sessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->where('date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('date', '<=', $endDate))
            ->whereIn('status', ['booked', 'completed'])
            ->when($isStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get()
            ->map(function($b) {
                $date = $b->date instanceof Carbon ? $b->date->toDateString() : substr($b->date, 0, 10);
                $time = Carbon::parse($b->time)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($b->time)->format('H:i'));
                $isDemo = $b->type === 'demo';
                return (object)[
                    'type' => $isDemo ? 'Demo' : 'Doubt',
                    'title' => $isDemo ? 'Demo Session' : 'Doubt Session',
                    'student_name' => $b->admission->contact->name ?? 'Unknown',
                    'topic' => current(array_filter([$b->admission->course->course_name ?? null, $b->topic])) ?: null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $b->meet_link,
                    'status' => 'upcoming'
                ];
            });

        // 3. SupportTicketAllocation (Doubt)
        $supportDoubts = \App\Models\SupportTicketAllocation::with('ticket.user')
            ->where('allocated_date', '>=', $startDate)
            ->when($endDate, fn($q) => $q->where('allocated_date', '<=', $endDate))
            ->whereIn('status', ['Scheduled', 'Completed'])
            ->when($isStaff && $staffId, fn($q) => $q->where('staff_id', $staffId))
            ->get()
            ->map(function($a) {
                $date = $a->allocated_date instanceof Carbon ? $a->allocated_date->toDateString() : substr($a->allocated_date, 0, 10);
                $time = Carbon::parse($a->allocated_slot)->format('g:i A');
                $timestamp = Carbon::parse("$date " . Carbon::parse($a->allocated_slot)->format('H:i'));
                return (object)[
                    'type' => 'Doubt',
                    'title' => 'Doubt Session',
                    'student_name' => $a->ticket->user->name ?? 'Unknown',
                    'topic' => $a->ticket->subject ?? null,
                    'raw_date' => $date,
                    'date_str' => Carbon::parse($date)->format('d M Y'),
                    'time_str' => $time,
                    'timestamp' => $timestamp,
                    'link' => $a->zoom_meet_link,
                    'status' => 'upcoming'
                ];
            });

        $allMeetings = $callLogDemos->concat($sessionBookings)->concat($supportDoubts)->sortBy('timestamp')->values();

        return view('meetings.index', compact('allMeetings', 'startDate', 'endDate'));
    }
}
