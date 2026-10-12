<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CallLog;
use App\Models\Contact;
use App\Models\Staff;
use App\Models\Batch;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all staff members eligible for demos
        $staffs = Staff::with(['role', 'schedules'])
            ->whereRelation('role', 'name', 'Technical Staff')
            ->get();

        // Get the next 4 days (including Sat/Sun)
        $dates = [];
        $tempDate = now();
        while(count($dates) < 4) {
            $dates[] = [
                'full' => $tempDate->toDateString(),
                'day_name' => $tempDate->isToday() ? 'Today' : ($tempDate->isTomorrow() ? 'Tomorrow' : $tempDate->format('D j')),
                'day_of_week' => $tempDate->format('l')
            ];
            $tempDate->addDay();
        }

        $calls = CallLog::whereNotNull('demo_date')->orderByDesc('created_at')->paginate(10);
        $bookedDemos = CallLog::whereNotNull('demo_date')
            ->whereNotNull('demo_time')
            ->where('demo_date', '>=', now()->subDays(1)->toDateString())
            ->get(['assigned_staff_id', 'demo_date', 'demo_time'])
            ->map(function($d) {
                return [
                    'assigned_staff_id' => $d->assigned_staff_id,
                    'demo_date' => $d->demo_date instanceof \Carbon\Carbon ? $d->demo_date->toDateString() : $d->demo_date,
                    'demo_time' => \Carbon\Carbon::parse($d->demo_time)->format('H:i:s')
                ];
            });

        $allocatedDoubts = \App\Models\SupportTicketAllocation::where('allocated_date', '>=', now()->subDays(1)->toDateString())
            ->whereIn('status', ['Scheduled', 'Completed', 'Missed'])
            ->get(['staff_id', 'allocated_date', 'allocated_slot'])
            ->map(function($a) {
                return [
                    'assigned_staff_id' => $a->staff_id,
                    'demo_date' => $a->allocated_date instanceof \Carbon\Carbon ? $a->allocated_date->toDateString() : (substr($a->allocated_date, 0, 10)),
                    'demo_time' => \Carbon\Carbon::parse($a->allocated_slot)->format('H:i:s')
                ];
            });
            
        $sessionBookings = \App\Models\SessionBooking::where('date', '>=', now()->subDays(1)->toDateString())
            ->whereIn('status', ['booked', 'completed', 'missed'])
            ->get(['staff_id', 'date', 'time'])
            ->map(function($b) {
                return [
                    'assigned_staff_id' => $b->staff_id,
                    'demo_date' => $b->date instanceof \Carbon\Carbon ? $b->date->toDateString() : (substr($b->date, 0, 10)),
                    'demo_time' => \Carbon\Carbon::parse($b->time)->format('H:i:s')
                ];
            });

        $bookedDemos = $bookedDemos->concat($allocatedDoubts)->concat($sessionBookings);

        $preFilledContact = null;
        if ($request->has('contact_id')) {
            $c = Contact::find($request->contact_id);
            if($c) {
                $preFilledContact = [
                    'id' => $c->id,
                    'type' => 'contact',
                    'first_name' => $c->name,
                    'last_name' => '',
                    'caller_number' => $c->caller_number,
                ];
            }
        } elseif ($request->has('lead_id')) {
            $l = \App\Models\Lead::find($request->lead_id);
            if($l) {
                $preFilledContact = [
                    'id' => $l->id,
                    'type' => 'lead',
                    'first_name' => $l->name,
                    'last_name' => '',
                    'caller_number' => $l->phone,
                ];
            }
        }

        return view('schedules.index', compact('staffs', 'dates', 'calls', 'preFilledContact', 'bookedDemos'));
    }

    public function searchContacts(Request $request)
    {
        $search = $request->get('q');
        $contacts = Contact::where('name', 'LIKE', "%$search%")
            ->orWhere('caller_number', 'LIKE', "%$search%")
            ->take(10)
            ->get(['id', 'name', 'caller_number'])
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'type' => 'contact',
                    'first_name' => $c->name,
                    'last_name' => '',
                    'caller_number' => $c->caller_number,
                ];
            });

        $leads = \App\Models\Lead::where('name', 'LIKE', "%$search%")
            ->orWhere('phone', 'LIKE', "%$search%")
            ->take(10)
            ->get(['id', 'name', 'phone'])
            ->map(function($l) {
                return [
                    'id' => $l->id,
                    'type' => 'lead',
                    'first_name' => $l->name,
                    'last_name' => '',
                    'caller_number' => $l->phone,
                ];
            });

        $results = array_merge($contacts->toArray(), $leads->toArray());
        $results = array_slice($results, 0, 10);

        return response()->json($results);
    }

    public function getBatchesByCourse($courseId)
    {
        $batches = Batch::where('course_id', $courseId)
            ->where(function($q) {
                $q->where('batch_status', '!=', 'Completed')
                  ->orWhereNull('batch_status');
            })
            ->get(['id', 'batch_code', 'batch_name', 'batch_type']);

        return response()->json([
            'status' => true,
            'data' => $batches
        ]);
    }

    public function bookDemo(Request $request)
    {
        $request->validate([
            'contact_id' => 'nullable|exists:contacts,id',
            'lead_id'    => 'nullable|exists:leads,id',
            'staff_id' => 'required|exists:staff,id',
            'demo_date' => 'required|date',
            'demo_time' => 'required',
        ]);

        if (!$request->contact_id && !$request->lead_id) {
            return response()->json(['status' => false, 'message' => 'Please select a contact or lead.']);
        }

        $staff = \App\Models\Staff::find($request->staff_id);

        $existingDemo = null;
        $latestLog = null;
        $studentName = 'Student';
        $contactEmail = null;
        $callerNumber = null;

        if ($request->contact_id) {
            $contact = \App\Models\Contact::find($request->contact_id);
            $studentName = $contact->name;
            $contactEmail = $contact->email;
            $callerNumber = $contact->caller_number;

            $existingDemo = CallLog::where('contact_id', $request->contact_id)
                ->where('demo_date', '>=', now()->toDateString())
                ->where('lead_tag', 'Demo booked')
                ->first();
            $latestLog = CallLog::where('contact_id', $request->contact_id)->orderByDesc('id')->first();
        } else {
            $lead = \App\Models\Lead::find($request->lead_id);
            $studentName = $lead->name;
            $contactEmail = $lead->email;
            $callerNumber = $lead->phone;

            $existingDemo = CallLog::where('lead_id', $request->lead_id)
                ->where('demo_date', '>=', now()->toDateString())
                ->where('lead_tag', 'Demo booked')
                ->first();
            $latestLog = CallLog::where('lead_id', $request->lead_id)->orderByDesc('id')->first();
        }

        // Check if student already has an upcoming demo
        if ($existingDemo) {
            $date = \Carbon\Carbon::parse($existingDemo->demo_date)->format('d M');
            $time = \Carbon\Carbon::parse($existingDemo->demo_time)->format('h:i A');
            return response()->json([
                'status' => false, 
                'message' => "Student already has a demo booked for $date at $time"
            ]);
        }

        // Check if staff is already booked for this slot (Demo or Doubt)
        $demoConflict = CallLog::where('assigned_staff_id', $request->staff_id)
            ->whereDate('demo_date', $request->demo_date)
            ->whereTime('demo_time', \Carbon\Carbon::parse($request->demo_time)->format('H:i:s'))
            ->where(function ($q) {
                $q->whereNull('lead_tag')
                  ->orWhereNotIn('lead_tag', ['Demo Missed', 'Lost']);
            })
            ->exists();
            
        $doubtConflict = \App\Models\SupportTicketAllocation::where('staff_id', $request->staff_id)
            ->whereDate('allocated_date', $request->demo_date)
            ->where('allocated_slot', \Carbon\Carbon::parse($request->demo_time)->format('H:i:s'))
            ->whereIn('status', ['Scheduled', 'Completed', 'Missed'])
            ->exists();
            
        $sessionConflict = \App\Models\SessionBooking::where('staff_id', $request->staff_id)
            ->whereDate('date', $request->demo_date)
            ->where('time', \Carbon\Carbon::parse($request->demo_time)->format('H:i:s'))
            ->whereIn('status', ['booked', 'completed', 'missed'])
            ->exists();

        if ($demoConflict || $doubtConflict || $sessionConflict) {
            return response()->json([
                'status' => false, 
                'message' => "This staff member is already booked for a session at this time."
            ]);
        }

        $zoomLink = null;
        $zoomMeetingId = null;
        try {
            $zoomService = new \App\Services\ZoomService();
            // Format start time for Zoom: YYYY-MM-DDTHH:MM:SS
            $startTime = \Carbon\Carbon::parse($request->demo_date . ' ' . $request->demo_time)->format('Y-m-d\TH:i:s');
            
            $staffMember = \App\Models\Staff::find($request->staff_id);
            $alternativeHosts = $staffMember && $staffMember->email ? $staffMember->email : '';

            $zoomMeeting = $zoomService->createMeeting(
                'Demo Session with ' . $studentName,
                $startTime,
                30, // default 30 mins
                'Asia/Kolkata',
                $alternativeHosts
            );
            
            if ($zoomMeeting && isset($zoomMeeting['join_url'])) {
                $zoomLink = $zoomMeeting['join_url'];
                $zoomMeetingId = $zoomMeeting['id'] ?? null;
            }
        } catch (\Exception $e) {
            \Log::error('Zoom meeting creation failed during demo booking: ' . $e->getMessage());
        }

        $updateData = [
            'assigned_staff_id' => $request->staff_id,
            'demo_date' => $request->demo_date,
            'demo_time' => $request->demo_time,
            'status' => 'Demo Scheduled',
            'zoom_meet_link' => $zoomLink,
            'zoom_meeting_id' => $zoomMeetingId,
        ];

        $updateData['lead_tag'] = 'Demo booked';

        if ($request->note) {
            $updateData['note'] = $latestLog && $latestLog->note 
                ? $latestLog->note . " | " . $request->note 
                : $request->note;
        }

        if ($latestLog) {
            $latestLog->update($updateData);
        } else {
            // Fallback: Create if no log exists
            if ($request->contact_id) $updateData['contact_id'] = $request->contact_id;
            if ($request->lead_id) $updateData['lead_id'] = $request->lead_id;
            $updateData['caller_number'] = $callerNumber;
            CallLog::create($updateData);
        }

        return response()->json(['status' => true, 'message' => 'Demo booked successfully']);
    }
}
