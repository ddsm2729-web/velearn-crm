<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InboundCallController extends Controller
{
    public function index(Request $request)
    {
        // Get the latest CallLog ID for each lead_id
        $latestIdsQuery = \App\Models\CallLog::selectRaw('MAX(id)')
            ->groupBy('lead_id');

        // Inbound leads are those from MyOperator (lead_source_id = 1)
        $query = \App\Models\CallLog::with(['lead.source'])
            ->whereIn('id', $latestIdsQuery)
            ->whereHas('lead', function($q) {
                $q->where('lead_source_id', 1);
                
                if (!auth()->user()->hasRole('SuperAdmin')) {
                    $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
                    if ($staff) {
                        $handledLeadIds = \App\Models\CallLog::where('staff_id', $staff->id)->pluck('lead_id')->merge(
                            \App\Models\LeadActivity::where('staff_id', $staff->id)->pluck('lead_id')
                        )->unique();
                        
                        $q->where(function($sq) use ($staff, $handledLeadIds) {
                            $sq->where('assigned_to', $staff->id)
                               ->orWhereNull('assigned_to')
                               ->orWhereIn('id', $handledLeadIds);
                        });
                    }
                }
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('caller_number', 'LIKE', "%$search%")
                  ->orWhereHas('lead', function($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%$search%")
                         ->orWhere('email', 'LIKE', "%$search%");
                  });
            });
        }

        if ($request->filled('tag') && $request->tag !== 'All Tags') {
            $query->where('lead_tag', $request->tag);
        }

        $calls = $query->orderByDesc('created_at')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'data' => view('calls.partials.call_rows', compact('calls'))->render(),
                'pagination' => (string) $calls->links()
            ]);
        }

        $totalLeads = (clone $query)->count();
        $newToday = (clone $query)->whereDate('created_at', now())->count();

        return view('calls.inbound', compact('calls', 'totalLeads', 'newToday'));
    }
}
