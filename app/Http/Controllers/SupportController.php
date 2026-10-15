<?php

namespace App\Http\Controllers;

use App\Models\CallLog;
use App\Models\Staff;
use App\Models\SupportTicket;
use App\Models\SupportTicketAllocation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Store a Technical Support session booking.
     */
    public function storeTechnical(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'date' => 'required|date',
                'slot' => 'required|string',
            ]);

            $userId = auth()->id() ?? $request->user_id;
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $ticket = SupportTicket::create([
                'user_id' => $userId,
                'reference_id' => 'TQ-' . strtoupper(Str::random(6)),
                'type' => 'technical',
                'title' => $request->title,
                'description' => $request->description,
                'preferred_date' => $request->date,
                'preferred_slot' => $request->slot,
                'status' => 'New',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Technical support session booked successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a Non-Technical Support request.
     * File stored in public/uploads/support_attachments/ (same pattern as courses).
     */
    public function storeNonTechnical(Request $request)
    {
        try {
            $request->validate([
                'category' => 'required|string',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
            ]);

            $userId = auth()->id() ?? $request->user_id;
            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            /* ---------- ATTACHMENT ---------- */
            $attachmentName = null;
            if ($request->hasFile('attachment')) {
                $uploadDir = public_path('uploads/support_attachments');

                // Auto-create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $file = $request->file('attachment');
                $attachmentName = time() . '_' . uniqid() . '.' . $file->extension();
                $file->move($uploadDir, $attachmentName);
            }

            $ticket = SupportTicket::create([
                'user_id' => $userId,
                'reference_id' => 'NTQ-' . strtoupper(Str::random(6)),
                'type' => 'non-technical',
                'category' => $request->category,
                'subject' => $request->subject,
                'description' => $request->description,
                'attachment' => $attachmentName,  // just filename, like courses store image
                'status' => 'New',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Non-technical support request submitted successfully',
                'data' => $ticket
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get 5 most recent support tickets for the authenticated user.
     * Appends full attachment_url for file access.
     */
    public function getRecent()
    {
        try {
            $tickets = SupportTicket::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($ticket) {
                    // Append a publicly accessible URL for the attachment
                    $ticket->attachment_url = $ticket->attachment
                        ? url('uploads/support_attachments/' . $ticket->attachment)
                        : null;
                    return $ticket;
                });

            return response()->json([
                'status' => true,
                'data' => $tickets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get booked slots for a specific date.
     */
    public function getBookedSlots(Request $request)
    {
        try {
            $date = $request->get('date');
            if (!$date) {
                return response()->json(['status' => false, 'message' => 'Date is required'], 400);
            }

            // Find all slots booked on this date that are active
            $bookedSlots = SupportTicket::where('preferred_date', $date)
                ->where('type', 'technical')
                ->whereNotIn('status', ['Resolved', 'Cancelled'])
                ->pluck('preferred_slot');

            return response()->json([
                'status' => true,
                'data' => $bookedSlots
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Doubt Query Leads index page.
     */
    public function doubtQueryLeads(Request $request)
    {
        $type = $request->get('type', 'technical');
        $status = $request->get('status', 'New');

        $statuses = ['New', 'Opened', 'Pending', 'In Review', 'Resolved'];

        // Counts per type
        $technicalCount = SupportTicket::where('type', 'technical')->count();
        $nonTechnicalCount = SupportTicket::where('type', 'non-technical')->count();

        // Status counts for active type
        $statusCounts = [];
        foreach ($statuses as $st) {
            $statusCounts[$st] = SupportTicket::where('type', $type)
                ->where('status', $st)
                ->count();
        }

        // Paginated tickets
        $tickets = SupportTicket::with('user')
            ->where('type', $type)
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // All tickets JSON for client-side detail modal
        $allTicketsJson = SupportTicket::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'reference_id' => $t->reference_id,
                    'type' => $t->type,
                    'title' => $t->title,
                    'subject' => $t->subject,
                    'description' => $t->description,
                    'preferred_date' => $t->preferred_date,
                    'preferred_slot' => $t->preferred_slot,
                    'status' => $t->status,
                    'attachment' => $t->attachment,
                ];
            });

        return view('doubt-query-leads.index', compact(
            'tickets',
            'technicalCount',
            'nonTechnicalCount',
            'statusCounts',
            'allTicketsJson'
        ));
    }

    /**
     * CRM: Update ticket status / schedule a doubt session.
     */
    public function scheduleDoubt(Request $request)
    {
        try {
            $request->validate([
                'ticket_id' => 'required|exists:support_tickets,id',
                'date' => 'nullable|date',
                'slot' => 'nullable|string',
                'status' => 'nullable|string',
            ]);

            $ticket = SupportTicket::findOrFail($request->ticket_id);

            $ticket->update(array_filter([
                'preferred_date' => $request->date,
                'preferred_slot' => $request->slot,
                'status' => $request->status ?? 'Opened',
            ]));

            return response()->json(['status' => true, 'message' => 'Session scheduled successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Manually add a doubt query lead.
     */
    public function manualStore(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'type' => 'required|in:technical,non-technical',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            // Find or create a user by phonenumber
            $user = User::firstOrCreate(
                ['phonenumber' => $request->phone],
                [
                    'name' => $request->name,
                    'email' => 'manual_' . time() . '@velearn.in',
                    'password' => bcrypt(Str::random(12)),
                ]
            );

            SupportTicket::create([
                'user_id' => $user->id,
                'reference_id' => ($request->type === 'technical' ? 'TQ-' : 'NTQ-') . strtoupper(Str::random(6)),
                'type' => $request->type,
                'title' => $request->title,
                'subject' => $request->title,
                'description' => $request->description,
                'status' => 'New',
            ]);

            return response()->json(['status' => true, 'message' => 'Lead added successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Update ticket status (Opened / Pending / In Review / Resolved).
     */
    public function updateTicketStatus(Request $request)
    {
        try {
            $request->validate([
                'ticket_id' => 'required|exists:support_tickets,id',
                'status' => 'required|in:New,Opened,Pending,In Review,Resolved',
            ]);

            $ticket = SupportTicket::findOrFail($request->ticket_id);
            $ticket->update(['status' => $request->status]);

            return response()->json(['status' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Doubt Allocation grid page.
     * Shows support staff columns × time-slot rows for today.
     */
    public function doubtAllocation(Request $request)
    {
        // Build an array of the next 6 working days (skipping weekends)
        $dates = [];
        $carbon = Carbon::today();
        while (count($dates) < 6) {
            if (!$carbon->isWeekend()) {
                $label = $carbon->isToday() ? 'Today' : ($carbon->isTomorrow() ? 'Tomorrow' : $carbon->format('D, j M Y'));
                $dates[] = [
                    'full' => $carbon->toDateString(),
                    'label' => $label
                ];
            }
            $carbon->addDay();
        }

        // Default to the first valid working date if no date is provided
        $selectedDate = $request->get('date', $dates[0]['full']);
        $dayOfWeek = Carbon::parse($selectedDate)->format('l');

        // Load all support-executive staff with their schedules for the selected day
        $staffList = Staff::with(['schedules' => function ($q) use ($dayOfWeek) {
            $q->where('day', $dayOfWeek)->where('status', 'available')->orderBy('schedule_time');
        }])
            ->whereHas('user', function ($q) {
                $q->role('Technical Staff');
            })
            ->get();

        // All allocations for the selected date
        $allocatedSlots = SupportTicketAllocation::with(['ticket.user'])
            ->whereDate('allocated_date', $selectedDate)
            ->get();

        // Pending tickets for assignment (anything not resolved)
        $pendingTickets = SupportTicket::with('user')
            ->whereNotIn('status', ['Resolved'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Demo bookings for the selected date
        $demoBookings = \App\Models\CallLog::with('contact')
            ->whereDate('demo_date', $selectedDate)
            ->whereNotNull('demo_time')
            ->where(function ($q) {
                $q->whereNull('lead_tag')
                  ->orWhereNotIn('lead_tag', ['Demo Missed', 'Lost']);
            })
            ->get();
            
        // SessionBookings (Demo and Doubt) for the selected date
        $sessionBookings = \App\Models\SessionBooking::with(['admission.contact', 'admission.course'])
            ->whereDate('date', $selectedDate)
            ->whereIn('status', ['booked', 'completed'])
            ->get();

        // Unique time slots across all staff for this day (for grid rows)
        $timeSlots = $staffList->flatMap(function ($staff) {
            return $staff->schedules->pluck('schedule_time');
        })->unique()->sort()->values();

        // Slot usage summary
        $totalSlots = $staffList->sum(fn($s) => $s->schedules->count());
        $allocatedCount = $allocatedSlots->count() + $demoBookings->count() + $sessionBookings->count();

        return view('doubt-allocation.index', compact(
            'staffList',
            'allocatedSlots',
            'pendingTickets',
            'demoBookings',
            'sessionBookings',
            'timeSlots',
            'totalSlots',
            'allocatedCount',
            'selectedDate',
            'dayOfWeek',
            'dates'
        ));
    }

    /**
     * CRM: Allocate a ticket to a staff member at a specific slot.
     */
    public function allocateDoubt(Request $request)
    {
        try {
            $request->validate([
                'ticket_id' => 'required|exists:support_tickets,id',
                'staff_id' => 'required|exists:staff,id',
                'allocated_date' => 'required|date',
                'allocated_slot' => 'required|string',
                'notes' => 'nullable|string',
            ]);

            $ticket = SupportTicket::findOrFail($request->ticket_id);

            // Check if there's already an active (Scheduled) allocation for this ticket
            $hasActive = SupportTicketAllocation::where('support_ticket_id', $request->ticket_id)
                ->where('status', 'Scheduled')
                ->exists();

            if ($hasActive) {
                return response()->json([
                    'status' => false,
                    'message' => 'This student already has an active schedule. Please complete or cancel it before re-allocating.'
                ]);
            }

            // Check if the staff already has a Demo booked for this slot
            $demoConflict = \App\Models\CallLog::whereDate('demo_date', $request->allocated_date)
                ->whereTime('demo_time', \Carbon\Carbon::parse($request->allocated_slot)->format('H:i:s'))
                ->where(function ($q) use ($request) {
                    $q->where('assigned_staff_id', $request->staff_id)
                      ->orWhere('staff_id', $request->staff_id);
                })
                ->where(function ($q) {
                    $q->whereNull('lead_tag')
                      ->orWhereNotIn('lead_tag', ['Demo Missed', 'Lost']);
                })
                ->exists();

            if ($demoConflict) {
                return response()->json([
                    'status' => false,
                    'message' => 'This staff member already has a Demo booked for this time slot.'
                ]);
            }

            // Create a new separate allocation
            $zoomLink = null;
            $zoomMeetingId = null;

            try {
                $zoomService = new \App\Services\ZoomService();
                // Format start time for Zoom: YYYY-MM-DDTHH:MM:SS
                $startTime = \Carbon\Carbon::parse($request->allocated_date . ' ' . $request->allocated_slot)->format('Y-m-d\TH:i:s');
                
                $staff = \App\Models\Staff::find($request->staff_id);
                $studentName = $ticket->user->name ?? 'Student';
                $alternativeHosts = $staff && $staff->email ? $staff->email : '';

                $zoomMeeting = $zoomService->createMeeting(
                    'Doubt Session with ' . $studentName,
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
                \Log::error('Zoom meeting creation failed during doubt allocation: ' . $e->getMessage());
            }

            SupportTicketAllocation::create([
                'support_ticket_id' => $request->ticket_id,
                'staff_id' => $request->staff_id,
                'allocated_date' => $request->allocated_date,
                'allocated_slot' => $request->allocated_slot,
                'notes' => $request->notes,
                'status' => 'Scheduled',
                'zoom_meet_link' => $zoomLink,
                'zoom_meeting_id' => $zoomMeetingId,
            ]);

            // Update ticket status to Opened/Scheduled if it was New
            if ($ticket->status === 'New') {
                $ticket->update(['status' => 'Opened']);
            }

            return response()->json(['status' => true, 'message' => 'Doubt allocated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Remove allocation from a slot (free it up).
     */
    public function deallocateDoubt(Request $request)
    {
        try {
            $allocation = SupportTicketAllocation::where([
                'support_ticket_id' => $request->ticket_id,
                'staff_id' => $request->staff_id,
                'allocated_slot' => $request->allocated_slot,
            ])->whereDate('allocated_date', $request->allocated_date)->first();

            if ($allocation) {
                if ($allocation->zoom_meeting_id) {
                    try {
                        $zoomService = new \App\Services\ZoomService();
                        $zoomService->deleteMeeting($allocation->zoom_meeting_id);
                    } catch (\Exception $e) {
                        \Log::error('Zoom meeting deletion failed during doubt deallocation: ' . $e->getMessage());
                    }
                }
                
                $allocation->delete();
                return response()->json(['status' => true, 'message' => 'Allocation removed and Zoom meeting canceled']);
            }

            return response()->json(['status' => false, 'message' => 'Allocation not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * CRM: Mark an allocation as completed.
     */
    public function completeDoubt(Request $request)
    {
        try {
            $allocation = SupportTicketAllocation::where([
                'support_ticket_id' => $request->ticket_id,
                'staff_id' => $request->staff_id,
                'allocated_slot' => $request->allocated_slot,
            ])->whereDate('allocated_date', $request->allocated_date)->first();

            if ($allocation) {
                $allocation->update(['status' => 'Completed']);
                return response()->json(['status' => true, 'message' => 'Allocation marked as completed']);
            }

            return response()->json(['status' => false, 'message' => 'Allocation not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function doubtQueryFollowups(Request $request)
    {
        $isSuperAdmin = auth()->user()->hasRole('SuperAdmin');
        $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
        $staffId = $staff ? $staff->id : 0;

        $query = \App\Models\Lead::with(['source', 'status', 'tags', 'latestActivity'])
            ->whereHas('latestActivity', function($q) use ($isSuperAdmin, $staffId) {
                $q->whereNotNull('next_followup_date');
                if (!$isSuperAdmin && $staffId) {
                    $q->where('staff_id', $staffId);
                }
            });

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('phone', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        // Tag Filter
        if ($request->filled('tag') && $request->tag !== 'All Tags') {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        // Type Filter
        if ($request->filled('type') && $request->type !== 'All Types') {
            if ($request->type === 'Inbound') {
                $query->whereHas('source', function($q) {
                    $q->where('name', 'LIKE', '%MyOperator%')->orWhere('name', 'LIKE', '%Inbound%');
                });
            } elseif ($request->type === 'Outbound') {
                $query->whereHas('source', function($q) {
                    $q->where('name', 'NOT LIKE', '%MyOperator%')->where('name', 'NOT LIKE', '%Inbound%');
                })->orWhereNull('lead_source_id');
            }
        }

        $now = now();
        $totalCount = (clone $query)->count();
        $overdueCount = (clone $query)->whereHas('latestActivity', function($q) use ($now) {
            $q->where('next_followup_date', '<', $now);
        })->count();
        $upcomingCount = (clone $query)->whereHas('latestActivity', function($q) use ($now) {
            $q->where('next_followup_date', '>=', $now);
        })->count();

        // Sort by next_followup_date
        $query->addSelect([
            'next_followup' => \App\Models\LeadActivity::select('next_followup_date')
                ->whereColumn('lead_id', 'leads.id')
                ->orderByDesc('id')
                ->limit(1)
        ])->orderBy('next_followup', 'asc');

        $calls = $query->paginate(15)->withQueryString();

        foreach ($calls as $call) {
            $call->is_pending = $call->next_followup < $now;
        }

        if ($request->ajax()) {
            return view('doubt-query-leads.partials.followup_rows', compact('calls', 'totalCount', 'overdueCount', 'upcomingCount'))->render();
        }

        $tags = \App\Models\LeadTag::all();

        return view('doubt-query-leads.followups', compact('calls', 'totalCount', 'overdueCount', 'upcomingCount', 'tags'));
    }

    // view call log profile
    public function show($id)
    {
        // Store a safe back URL in session from known list pages only
        $referer = request()->headers->get('referer', '');
        $safeKeywords = ['doubt-query-leads', 'doubt-query-followups', 'follow-up', 'followup', 'leads'];
        $isSafeReferer = collect($safeKeywords)->some(fn($k) => str_contains($referer, $k));
        if ($isSafeReferer) {
            session(['support_back_url' => $referer]);
        } elseif (!session()->has('support_back_url')) {
            session(['support_back_url' => route('doubt-query-leads.index')]);
        }

        $baseId = str_replace('+91', '', $id);
        
        $lead = \App\Models\Lead::with(['status', 'assignee', 'source', 'tags', 'activities.staff', 'activities.tag', 'activities.status', 'callLogs.staff', 'courses'])
            ->where('phone', $id)
            ->orWhere('phone', $baseId)
            ->orWhere('phone', '+91' . $baseId)
            ->first();
            
        if (!$lead) {
            return redirect()->back()->with('error', 'Lead profile not found for this user.');
        }

        $timeline = collect();
        foreach ($lead->activities as $activity) {
            $timeline->push([
                'type' => 'activity',
                'timestamp' => $activity->created_at,
                'data' => $activity
            ]);
        }
        foreach ($lead->callLogs as $callLog) {
            $timeline->push([
                'type' => 'call_log',
                'timestamp' => \Carbon\Carbon::parse($callLog->call_start),
                'data' => $callLog
            ]);
        }
        $timeline = $timeline->sortByDesc('timestamp');

        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $statuses = \App\Models\LeadStatus::all();
            $tags = \App\Models\LeadTag::orderBy('name')->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $statuses = \App\Models\LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = \App\Models\LeadStatus::all();
            }
            $tags = \App\Models\LeadTag::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            if ($tags->isEmpty()) {
                $tags = \App\Models\LeadTag::orderBy('name')->get();
            }
        }
        
        $all_courses = \App\Models\Course::where('status', 1)->where('course_type', 'live')->orderBy('title')->get();
        
        $pipelineLogs = \App\Models\LeadPipelineLog::with(['oldStatus', 'newStatus', 'oldTag', 'newTag', 'changedBy'])
            ->where('lead_id', $lead->id)
            ->orderByDesc('created_at')
            ->get();
        
        $admissionOfficers = \App\Models\Staff::whereHas('role', function($q) {
            $q->where('name', 'Admission Officer');
        })->get();
        $isConfirmed = $lead->tags->contains(function ($tag) {
            return in_array(strtolower($tag->name), ['converted', 'confirmed']);
        }) || ($lead->status && strtolower($lead->status->name) === 'confirmed');
        $admission = \App\Models\Admission::where('lead_id', $lead->id)->first();
        
        return view('leads.show', compact('lead', 'statuses', 'tags', 'timeline', 'all_courses', 'pipelineLogs', 'admissionOfficers', 'isConfirmed', 'admission'));
    }

    public function assignLead(Request $request)
    {
        $request->validate([
            'call_log_id' => 'required|exists:call_logs,id',
            'assigned_staff_id' => 'required',
            'update_tag' => 'sometimes|string',
            'force_new' => 'sometimes|boolean',
            'followup_date' => 'nullable|date',
            'assign_notes' => 'required|string',
        ]);

        $callLog = CallLog::findOrFail($request->call_log_id);

        if ($request->assigned_staff_id === 'self' || empty($request->assigned_staff_id)) {
            $updateData = [
                'followup_note' => $request->assign_notes,
            ];

            if ($request->filled('followup_date')) {
                $updateData['followup_date'] = $request->followup_date;
                $updateData['followup_time'] = $request->followup_time;
            }

            $staffId = auth()->id();
            $updateData['assigned_staff_id'] = $staffId;
            if (empty($callLog->staff_id)) {
                $updateData['staff_id'] = $staffId;
            }

            if ($request->update_tag !== 'keep') {
                $updateData['lead_tag'] = $request->update_tag;
            }

            $callLog->update($updateData);
        } else {
            // Determine if we should force creation of a new log
            $forceNew = $request->boolean('force_new');

            if (!$forceNew) {
                // Check if a log already exists for this contact assigned to the selected admission officer
                $existingLog = CallLog::where('contact_id', $callLog->contact_id)
                    ->where('assigned_staff_id', $request->assigned_staff_id)
                    ->first();

                if ($existingLog) {
                    // Update the existing log with new details
                    $updateData = [
                        'followup_note' => $request->assign_notes,
                    ];
                    if ($request->filled('followup_date')) {
                        $updateData['followup_date'] = $request->followup_date;
                        $updateData['followup_time'] = $request->followup_time;
                    }
                    if ($request->update_tag !== 'keep') {
                        $updateData['lead_tag'] = $request->update_tag;
                    }
                    $existingLog->update($updateData);
                } else {
                    // No existing log, create a new one for the admission officer
                    $newLogData = [
                        'contact_id' => $callLog->contact_id,
                        'caller_number' => $callLog->caller_number,
                        'direction' => 'N/A',
                        'lead_tag' => $request->update_tag !== 'keep' ? $request->update_tag : $callLog->lead_tag,
                        'note' => 'Assigned to Admission Officer.',
                        'followup_note' => $request->assign_notes,
                        'assigned_staff_id' => $request->assigned_staff_id,
                        'staff_id' => $request->assigned_staff_id,
                    ];
                    if ($request->filled('followup_date')) {
                        $newLogData['followup_date'] = $request->followup_date;
                        $newLogData['followup_time'] = $request->followup_time;
                    }
                    CallLog::create($newLogData);
                }
            } else {
                // Force creation of a new log regardless of existing records
                $newLogData = [
                    'contact_id' => $callLog->contact_id,
                    'caller_number' => $callLog->caller_number,
                    'direction' => 'N/A',
                    'department' => 'Sales',
                    'lead_tag' => $request->update_tag !== 'keep' ? $request->update_tag : $callLog->lead_tag,
                    'note' => 'Forced new assignment to Admission Officer.',
                    'followup_note' => $request->assign_notes,
                    'assigned_staff_id' => $request->assigned_staff_id,
                    'staff_id' => $request->assigned_staff_id,
                ];
                if ($request->filled('followup_date')) {
                    $newLogData['followup_date'] = $request->followup_date;
                    $newLogData['followup_time'] = $request->followup_time;
                }
                CallLog::create($newLogData);
            }
        }

        return response()->json(['status' => true, 'message' => 'Lead assigned successfully']);
    }
}
