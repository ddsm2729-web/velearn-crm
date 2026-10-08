<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadStatus;
use App\Models\LeadSource;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LeadController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Lead List', only: ['index']),
            new Middleware('permission:Lead Show', only: ['show']),
            new Middleware('permission:Lead Create', only: ['create', 'store']),
            new Middleware('permission:Lead Edit', only: ['edit', 'update']),
            new Middleware('permission:Lead Delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = Lead::with(['status', 'assignee', 'source', 'latestActivity']);

        if (!auth()->user()->hasRole('SuperAdmin')) {
            $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if ($staff) {
                // Show leads assigned to them OR leads they've handled (called or logged activity)
                $handledLeadIds = \App\Models\CallLog::where('staff_id', $staff->id)->pluck('lead_id')->merge(
                    \App\Models\LeadActivity::where('staff_id', $staff->id)->pluck('lead_id')
                )->unique();
                
                $query->where(function($q) use ($staff, $handledLeadIds) {
                    $q->where('assigned_to', $staff->id)
                      ->orWhereIn('id', $handledLeadIds);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('date_filter')) {
            $dates = explode(' - ', $request->date_filter);
            if (count($dates) == 2) {
                $query->whereDate('created_at', '>=', trim($dates[0]))
                      ->whereDate('created_at', '<=', trim($dates[1]));
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('lead_tags.id', $request->tag);
            });
        }

        if ($request->filled('status')) {
            $query->where('lead_status_id', $request->status);
        }

        if ($request->filled('assignee')) {
            $query->where('assigned_to', $request->assignee);
        }

        if ($request->filled('followup')) {
            $followup = $request->followup;
            $query->whereHas('latestActivity', function($q) use ($followup) {
                if ($followup === 'today') {
                    $q->whereDate('next_followup_date', \Carbon\Carbon::today());
                } elseif ($followup === 'yesterday') {
                    $q->whereDate('next_followup_date', \Carbon\Carbon::yesterday());
                } elseif ($followup === 'missed') {
                    $q->whereDate('next_followup_date', '<', \Carbon\Carbon::today());
                } elseif ($followup === 'upcoming') {
                    $q->whereDate('next_followup_date', '>', \Carbon\Carbon::today());
                } elseif ($followup === 'last_7_days') {
                    $q->whereDate('next_followup_date', '>=', \Carbon\Carbon::today()->subDays(7))
                      ->whereDate('next_followup_date', '<=', \Carbon\Carbon::today());
                } elseif ($followup === 'all') {
                    $q->whereNotNull('next_followup_date');
                }
            });
        }

        $leads = $query->latest()->paginate(15)->withQueryString();
        
        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $statuses = LeadStatus::all();
            $staffs = Staff::whereHas('role', function($q) { $q->where('name', '!=', 'SuperAdmin'); })->latest()->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $statuses = LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = LeadStatus::all();
            }
            $staffs = Staff::where('auth_id', auth()->id())->get();
        }
        return view('leads.index', compact('leads', 'statuses', 'staffs'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $sources = LeadSource::orderBy('name')->get();
            $staffs = Staff::whereHas('role', function($q) { $q->where('name', '!=', 'SuperAdmin'); })->latest()->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $sources = LeadSource::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            // Fallback: if no role-specific sources, show all
            if ($sources->isEmpty()) {
                $sources = LeadSource::orderBy('name')->get();
            }
            $staffs = Staff::where('auth_id', auth()->id())->get();
        }
        if ($user->hasRole('SuperAdmin')) {
            $statuses = LeadStatus::all();
            $tags = \App\Models\LeadTag::orderBy('name')->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $statuses = LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = LeadStatus::all();
            }
            $tags = \App\Models\LeadTag::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            if ($tags->isEmpty()) {
                $tags = \App\Models\LeadTag::orderBy('name')->get();
            }
        }
        
        return view('leads.create', compact('sources', 'statuses', 'staffs', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',
            'whatsapp' => 'required|numeric|digits:10',
            'email' => 'required|email|max:255',
            'location' => 'required|string|max:255',
            'lead_source_id' => 'required|exists:lead_sources,id',
            'lead_status_id' => 'required|exists:lead_statuses,id',
            'assigned_to' => 'required|exists:staff,id',
            'expected_close_date' => 'nullable|date',
        ]);

        if (empty($validated['assigned_to'])) {
            $currentStaff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if ($currentStaff) {
                $validated['assigned_to'] = $currentStaff->id;
            }
        }

        $validated['phone'] = '+91' . $validated['phone'];
        $validated['whatsapp'] = '+91' . $validated['whatsapp'];

        $lead = Lead::create($validated);

        if ($request->filled('tag_id')) {
            $lead->tags()->attach($request->tag_id);
        }

        return response()->json([
            'status' => true,
            'message' => 'Lead created successfully!'
        ]);
    }

    public function show($id)
    {
        $id = decrypt($id);
        $lead = Lead::with(['status', 'assignee', 'source', 'tags', 'activities.staff', 'activities.tag', 'activities.status', 'callLogs.staff', 'courses'])->findOrFail($id);
        
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
                'timestamp' => \Carbon\Carbon::parse($callLog->created_at),
                'data' => $callLog
            ]);
        }
        $timeline = $timeline->sortByDesc(function($item) {
            return $item['timestamp']->getTimestamp();
        })->values();

        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $statuses = LeadStatus::all();
            $tags = \App\Models\LeadTag::orderBy('name')->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $statuses = LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = LeadStatus::all();
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

    public function edit($id)
    {
        $id = decrypt($id);
        $lead = Lead::findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('SuperAdmin')) {
            $sources = LeadSource::orderBy('name')->get();
            $staffs = Staff::whereHas('role', function($q) { $q->where('name', '!=', 'SuperAdmin'); })->latest()->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $sources = LeadSource::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            if ($sources->isEmpty()) {
                $sources = LeadSource::orderBy('name')->get();
            }
            $staffs = Staff::where('auth_id', auth()->id())->get();
        }
        if ($user->hasRole('SuperAdmin')) {
            $statuses = LeadStatus::all();
            $tags = \App\Models\LeadTag::orderBy('name')->get();
        } else {
            $userRoleNames = $user->getRoleNames();
            $statuses = LeadStatus::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->get();
            if ($statuses->isEmpty()) {
                $statuses = LeadStatus::all();
            }
            $tags = \App\Models\LeadTag::whereHas('roles', function($q) use ($userRoleNames) {
                $q->whereIn('name', $userRoleNames);
            })->orderBy('name')->get();
            if ($tags->isEmpty()) {
                $tags = \App\Models\LeadTag::orderBy('name')->get();
            }
        }

        return view('leads.edit', compact('lead', 'sources', 'statuses', 'staffs', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:10',
            'whatsapp' => 'required|numeric|digits:10',
            'email' => 'required|email|max:255',
            'location' => 'required|string|max:255',
            'lead_source_id' => 'required|exists:lead_sources,id',
            'lead_status_id' => 'required|exists:lead_statuses,id',
            'assigned_to' => 'nullable|exists:staff,id',
            'expected_close_date' => 'nullable|date',
        ]);

        if (empty($validated['assigned_to'])) {
            unset($validated['assigned_to']);
        }

        $validated['phone'] = '+91' . $validated['phone'];
        $validated['whatsapp'] = '+91' . $validated['whatsapp'];

        $lead->update($validated);

        if ($request->filled('tag_id')) {
            $lead->tags()->sync([$request->tag_id]);
        } else {
            $lead->tags()->sync([]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Lead updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $id = decrypt($id);
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('leads.index')->with('success', 'Lead deleted successfully!');
    }

    public function storeActivity(Request $request, $id)
    {
        $lead = Lead::findOrFail(decrypt($id));

        $validated = $request->validate([
            'type' => 'nullable|string',
            'outcome' => 'nullable|string',
            'note' => 'required|string',
            'next_action' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'new_status_id' => 'nullable|exists:lead_statuses,id',
            'tag_id' => 'nullable|exists:lead_tags,id',
            'expected_close_date' => 'nullable|date',
            'converted_date' => 'nullable|date',
        ]);

        // Log the activity
        $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
        
        // Determine what tag and status will be set
        $snapshotTagId = $request->filled('tag_id') ? (int)$request->tag_id : $lead->tags->first()?->id;
        $snapshotStatusId = !empty($validated['new_status_id']) ? (int)$validated['new_status_id'] : $lead->lead_status_id;

        $lead->activities()->create([
            'staff_id'          => $staff ? $staff->id : null,
            'tag_id'            => $snapshotTagId,
            'status_id'         => $snapshotStatusId,
            'type'              => $validated['type'] ?? 'Call',
            'direction'         => 'Outbound',
            'outcome'           => $validated['outcome'] ?? 'Contacted',
            'note'              => $validated['note'],
            'next_action'       => $validated['next_action'] ?? null,
            'next_followup_date'=> $validated['next_followup_date'] ?? null,
        ]);

        // Auto-assign lead to the staff member who first handles it (if unassigned)
        if ($staff && is_null($lead->assigned_to)) {
            $lead->update(['assigned_to' => $staff->id]);
        }

        // Log pipeline changes (status/tag)
        $oldStatusId = $lead->lead_status_id;
        $oldTagId = $lead->tags->first()?->id;
        $newTagId = $snapshotTagId;
        $newStatusId = $snapshotStatusId;

        // Update lead status
        $lead->update([
            'lead_status_id' => $newStatusId,
            'expected_close_date' => $validated['expected_close_date'] ?? $lead->expected_close_date,
            'converted_date' => $validated['converted_date'] ?? $lead->converted_date,
        ]);

        if ($newStatusId != $oldStatusId) {
            $newStatusObj = \App\Models\LeadStatus::find($newStatusId);
            if ($newStatusObj && strtolower($newStatusObj->name) === 'converted') {
                if ($request->has('admission_officer_id') && $request->filled('admission_officer_id')) {
                    $lead->update(['assigned_to' => $request->admission_officer_id]);
                } else {
                    $admissionOfficer = \App\Models\Staff::whereHas('role', function($q) {
                        $q->where('name', 'Admission Officer');
                    })->first();
                    
                    if ($admissionOfficer) {
                        $lead->update(['assigned_to' => $admissionOfficer->id]);
                    }
                }
            }
        }
        
        if ($request->has('tag_id')) {
            if ($request->filled('tag_id')) {
                $lead->tags()->sync([$request->tag_id]);
            } else {
                $lead->tags()->sync([]);
                $newTagId = null;
            }
        }

        // Record pipeline log if anything changed
        if ($oldStatusId != $newStatusId || $oldTagId != $newTagId) {
            \App\Models\LeadPipelineLog::create([
                'lead_id'       => $lead->id,
                'changed_by'    => $staff ? $staff->id : null,
                'old_status_id' => $oldStatusId,
                'new_status_id' => $newStatusId != $oldStatusId ? $newStatusId : null,
                'old_tag_id'    => $oldTagId,
                'new_tag_id'    => $newTagId != $oldTagId ? $newTagId : null,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Activity logged successfully!'
        ]);
    }

    public function updateActivity(Request $request, $id)
    {
        $activity = \App\Models\LeadActivity::findOrFail($id);
        
        if (!auth()->user()->hasRole('SuperAdmin') && !auth()->user()->can('Lead Activity Edit')) {
            $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if (!$staff || $activity->staff_id !== $staff->id) {
                return response()->json(['status' => false, 'message' => 'You can only edit your own logged activities.'], 403);
            }
        }

        // Only the latest activity can be edited
        $latestActivity = $activity->lead->activities()->latest()->first();
        if (!$latestActivity || $latestActivity->id !== $activity->id) {
            return response()->json(['status' => false, 'message' => 'Only the most recent log entry can be edited.'], 403);
        }
        
        $validated = $request->validate([
            'type' => 'nullable|string',
            'outcome' => 'nullable|string',
            'note' => 'required|string',
            'next_action' => 'nullable|string',
            'next_followup_date' => 'nullable|date',
            'new_status_id' => 'required|exists:lead_statuses,id',
            'tag_id' => 'nullable|exists:lead_tags,id',
            'expected_close_date' => 'nullable|date',
            'converted_date' => 'nullable|date',
        ]);

        $snapshotTagId = $request->filled('tag_id') ? (int)$request->tag_id : $activity->tag_id;
        $snapshotStatusId = (int)$validated['new_status_id'];

        $activity->update([
            'type'              => $validated['type'] ?? $activity->type,
            'outcome'           => $validated['outcome'] ?? $activity->outcome,
            'note'              => $validated['note'],
            'next_action'       => $validated['next_action'] ?? null,
            'next_followup_date'=> $validated['next_followup_date'] ?? null,
            'tag_id'            => $snapshotTagId,
            'status_id'         => $snapshotStatusId,
        ]);

        $lead = $activity->lead;

        // Log pipeline changes (status/tag)
        $oldStatusId = $lead->lead_status_id;
        $oldTagId = $lead->tags->first()?->id;
        $newTagId = $request->filled('tag_id') ? (int)$request->tag_id : $oldTagId;
        $newStatusId = (int)$validated['new_status_id'];

        // Update activity
        $activity->update([
            'type' => $validated['type'] ?? $activity->type,
            'outcome' => $validated['outcome'] ?? $activity->outcome,
            'note' => $validated['note'],
            'next_action' => $validated['next_action'] ?? null,
            'next_followup_date' => $validated['next_followup_date'] ?? null,
        ]);

        // Update the lead's status
        $lead->update([
            'lead_status_id' => $newStatusId,
            'expected_close_date' => $validated['expected_close_date'] ?? $lead->expected_close_date,
            'converted_date' => $validated['converted_date'] ?? $lead->converted_date,
        ]);

        if ($newStatusId != $oldStatusId) {
            $newStatusObj = \App\Models\LeadStatus::find($newStatusId);
            if ($newStatusObj && strtolower($newStatusObj->name) === 'converted') {
                if ($request->has('admission_officer_id') && $request->filled('admission_officer_id')) {
                    $lead->update(['assigned_to' => $request->admission_officer_id]);
                } else {
                    $admissionOfficer = \App\Models\Staff::whereHas('role', function($q) {
                        $q->where('name', 'Admission Officer');
                    })->first();
                    
                    if ($admissionOfficer) {
                        $lead->update(['assigned_to' => $admissionOfficer->id]);
                    }
                }
            }
        }
        
        if ($request->has('tag_id')) {
            if ($request->filled('tag_id')) {
                $lead->tags()->sync([$request->tag_id]);
            } else {
                $lead->tags()->sync([]);
                $newTagId = null;
            }
        }

        // Record pipeline log if anything changed
        $staffEdit = \App\Models\Staff::where('auth_id', auth()->id())->first();
        if ($oldStatusId != $newStatusId || $oldTagId != $newTagId) {
            \App\Models\LeadPipelineLog::create([
                'lead_id'       => $lead->id,
                'changed_by'    => $staffEdit ? $staffEdit->id : null,
                'old_status_id' => $oldStatusId,
                'new_status_id' => $newStatusId != $oldStatusId ? $newStatusId : null,
                'old_tag_id'    => $oldTagId,
                'new_tag_id'    => $newTagId != $oldTagId ? $newTagId : null,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Activity and Lead updated successfully!'
        ]);
    }

    public function setPriority(Request $request, $id)
    {
        $lead = Lead::findOrFail(decrypt($id));
        
        $validated = $request->validate([
            'priority' => 'required|string|in:Low,Medium,High',
        ]);

        $lead->update(['priority' => $validated['priority']]);

        return response()->json([
            'status' => true,
            'message' => 'Priority updated successfully!'
        ]);
    }

    public function attachCourse(Request $request, $id)
    {
        $lead = Lead::findOrFail(decrypt($id));

        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        if ($lead->courses()->where('course_id', $request->course_id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => 'Course is already attached to this lead.'
            ]);
        }

        $lead->courses()->attach($request->course_id);

        return response()->json([
            'status' => true,
            'message' => 'Course attached successfully!',
            'redirect_url' => route('leads.show', encrypt($lead->id))
        ]);
    }

    public function detachCourse(Request $request, $id, $courseId)
    {
        $lead = Lead::findOrFail(decrypt($id));
        $lead->courses()->detach($courseId);

        return response()->json([
            'status' => true,
            'message' => 'Course removed successfully!'
        ]);
    }
}
