<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\LeadSource;
use App\Models\LeadTag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CompanyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Company Database List', only: ['index']),
            new Middleware('permission:Company Database Create', only: ['create', 'store']),
            new Middleware('permission:Company Database Edit', only: ['edit', 'update']),
            new Middleware('permission:Company Database Delete', only: ['destroy']),
        ];
    }

    private function getRoleSourcesAndTags()
    {
        $user = Auth::user();
        
        // If super admin or no roles are explicitly mapped, we might need to fallback to all.
        // Let's just return all for Company Database if the user has access to create/edit.
        // If strict role filtering is needed, we should ensure the role relation works properly.
        // The previous code had a bug where SuperAdmin was checked as 'Super Admin', 
        // and standard users didn't have their roles properly mapped to LeadSource/LeadTags.
        
        return [
            'leadSources' => LeadSource::all(),
            'leadTags' => LeadTag::all()
        ];

        return [
            'leadSources' => $leadSources,
            'leadTags' => $leadTags
        ];
    }

    public function index(Request $request)
    {
        $query = Company::with(['source', 'leadTag']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('hr_name', 'LIKE', "%{$search}%")
                  ->orWhere('city_location', 'LIKE', "%{$search}%")
                  ->orWhere('hr_email_id', 'LIKE', "%{$search}%")
                  ->orWhere('hr_contact_number', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('lead_tag_id') && $request->lead_tag_id !== 'All') {
            $query->where('lead_tag_id', $request->lead_tag_id);
        }

        if ($request->filled('openings_flag') && $request->openings_flag !== 'All') {
            $query->where('current_openings', $request->openings_flag === 'Yes' ? 1 : 0);
        }

        if ($request->filled('city') && $request->city !== 'All') {
            $query->where('city_location', $request->city);
        }

        if ($request->filled('industry') && $request->industry !== 'All') {
            $query->where('industry_sector', $request->industry);
        }

        if ($request->filled('date_filter') && $request->date_filter !== 'All') {
            $dateFilter = $request->date_filter;
            if ($dateFilter === 'Today') {
                $query->whereDate('created_at', now()->toDateString());
            } elseif ($dateFilter === 'This Week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateFilter === 'This Month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }
        }

        $companies = $query->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->ajax()) {
            $html = view('company_database._table_rows', compact('companies'))->render();
            $pagination = (string) $companies->links('pagination::bootstrap-4');
            return response()->json(['html' => $html, 'pagination' => $pagination]);
        }

        $roleData = $this->getRoleSourcesAndTags();
        $leadSources = $roleData['leadSources'];
        $leadTags = $roleData['leadTags'];

        // Grouped cities with counts based on DB values
        $cities = \Illuminate\Support\Facades\DB::table('companies')
            ->select('city_location', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('city_location')
            ->where('city_location', '!=', '')
            ->groupBy('city_location')
            ->get();

        // Grouped industries with counts based on DB values
        $industries = \Illuminate\Support\Facades\DB::table('companies')
            ->select('industry_sector', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
            ->whereNotNull('industry_sector')
            ->where('industry_sector', '!=', '')
            ->groupBy('industry_sector')
            ->get();

        return view('company_database.index', compact('companies', 'leadSources', 'leadTags', 'cities', 'industries'));
    }

    public function create()
    {
        $roleData = $this->getRoleSourcesAndTags();
        $leadSources = $roleData['leadSources'];
        $leadTags = $roleData['leadTags'];
        
        return view('company_database.create', compact('leadSources', 'leadTags'));
    }

    public function store(Request $request)
    {
        $rules = [
            'company_name' => 'required|string|max:255',
            'city_location' => 'nullable|string|max:255',
            'industry_sector' => 'nullable|string|max:255',
            'company_website' => 'nullable|string|max:255',
            'company_mail_id' => 'nullable|email|max:255',
            'hr_name' => 'nullable|string|max:255',
            'hr_contact_number' => 'nullable|string|max:255',
            'hr_email_id' => 'nullable|email|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'source_id' => 'nullable|exists:lead_sources,id',
            'current_openings' => 'nullable|in:Yes,No',
            'lead_tag_id' => 'nullable|exists:lead_tags,id',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $data = $request->all();
            $data['current_openings'] = ($request->input('current_openings') === 'Yes') ? 1 : 0;
            Company::create($data);
            return response()->json(['status' => true, 'message' => 'Company has been created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $company = Company::with(['source', 'leadTag', 'openings', 'activities.staff'])->findOrFail(decrypt($id));
        $tags = LeadTag::orderBy('name')->get();
        $statuses = []; // Companies don't have lead_statuses, just tags for now

        $timeline = collect();
        foreach ($company->activities as $activity) {
            $timeline->push([
                'type' => 'activity',
                'data' => $activity,
                'date' => $activity->created_at
            ]);
        }
        
        if (!empty($company->hr_contact_number)) {
            // Strip out non-numeric characters for a more robust match if needed, or match as is
            $callLogs = \App\Models\CallLog::with('staff')
                ->where('caller_number', 'LIKE', '%' . ltrim($company->hr_contact_number, '+91 ') . '%')
                ->get();
                
            foreach ($callLogs as $callLog) {
                $timeline->push([
                    'type' => 'call_log',
                    'data' => $callLog,
                    'date' => \Carbon\Carbon::parse($callLog->call_start)
                ]);
            }
        }

        $timeline = $timeline->sortByDesc('date')->values();

        return view('company_database.show', compact('company', 'tags', 'statuses', 'timeline'));
    }

    public function storeActivity(Request $request, $encryptedId)
    {
        try {
            $id = decrypt($encryptedId);
            $company = Company::findOrFail($id);

            $request->validate([
                'note' => 'required|string',
                'tag_id' => 'nullable|exists:lead_tags,id',
            ]);

            \DB::beginTransaction();

            $activity = \App\Models\CompanyActivity::create([
                'company_id' => $company->id,
                'staff_id' => auth()->user()->staff?->id,
                'type' => $request->input('type', 'Call'),
                'note' => $request->note,
                'tag_id' => $request->tag_id,
                'next_followup_date' => $request->next_followup_date,
            ]);

            if ($request->filled('tag_id') && $company->lead_tag_id != $request->tag_id) {
                $company->update([
                    'lead_tag_id' => $request->tag_id
                ]);
            }

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Activity logged successfully.',
                'activity' => $activity
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Failed to log activity. ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $company = Company::findOrFail(decrypt($id));
        $roleData = $this->getRoleSourcesAndTags();
        $leadSources = $roleData['leadSources'];
        $leadTags = $roleData['leadTags'];
        
        return view('company_database.edit', compact('company', 'leadSources', 'leadTags'));
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail(decrypt($id));

        $rules = [
            'company_name' => 'required|string|max:255',
            'city_location' => 'nullable|string|max:255',
            'industry_sector' => 'nullable|string|max:255',
            'company_website' => 'nullable|string|max:255',
            'company_mail_id' => 'nullable|email|max:255',
            'hr_name' => 'nullable|string|max:255',
            'hr_contact_number' => 'nullable|string|max:255',
            'hr_email_id' => 'nullable|email|max:255',
            'linkedin_url' => 'nullable|string|max:255',
            'source_id' => 'nullable|exists:lead_sources,id',
            'current_openings' => 'nullable|in:Yes,No',
            'lead_tag_id' => 'nullable|exists:lead_tags,id',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $data = $request->all();
            $data['current_openings'] = ($request->input('current_openings') === 'Yes') ? 1 : 0;
            $company->update($data);
            return response()->json(['status' => true, 'message' => 'Company has been updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail(decrypt($id));
            $company->delete();
            return response()->json(['status' => true, 'message' => 'Company has been deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }
}
