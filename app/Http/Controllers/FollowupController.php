<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Lead;
use App\Models\Staff;
use App\Models\LeadTag;

class FollowupController extends Controller
{
    public function index(Request $request)
    {
        $isSuperAdmin = auth()->user()->hasRole('SuperAdmin');
        $staff = Staff::where('auth_id', auth()->id())->first();
        $staffId = $staff ? $staff->id : 0;

        $query = Lead::with(['source', 'status', 'tags', 'latestActivity'])
            ->whereHas('latestActivity', function($q) use ($isSuperAdmin, $staffId) {
                $q->whereNotNull('next_followup_date');
                if (!$isSuperAdmin && $staffId) {
                    $q->where('staff_id', $staffId);
                }
            });

        // Authorization check - if user is not superadmin and has no staff record, return empty
        if (!$isSuperAdmin && !$staff) {
            $query->where('id', '<', 0); 
        }

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                  ->orWhere('phone', 'LIKE', "%$search%")
                  ->orWhere('email', 'LIKE', "%$search%");
            });
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('name', $request->tag);
            });
        }

        if ($request->filled('type')) {
            if ($request->type === 'inbound') {
                $query->where('lead_source_id', 1);
            } elseif ($request->type === 'outbound') {
                $query->where(function($q) {
                    $q->where('lead_source_id', '!=', 1)->orWhereNull('lead_source_id');
                });
            }
        }

        $leads = $query->get();

        $now = now();
        
        $missed = $leads->filter(function($lead) use ($now) {
            return $lead->latestActivity->next_followup_date < $now;
        })->sortByDesc(function($lead) {
            return $lead->latestActivity->next_followup_date;
        });

        $upcoming = $leads->filter(function($lead) use ($now) {
            return $lead->latestActivity->next_followup_date >= $now;
        })->sortBy(function($lead) {
            return $lead->latestActivity->next_followup_date;
        });

        if ($request->ajax()) {
            return view('followups.partials.rows', compact('missed', 'upcoming'))->render();
        }

        $tags = LeadTag::all();

        return view('followups.index', compact('missed', 'upcoming', 'tags'));
    }
}

