<?php

namespace App\Http\Controllers;

use App\Models\CurrentOpening;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CurrentOpeningController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Current Openings List', only: ['index']),
            new Middleware('permission:Current Openings Create', only: ['create', 'store']),
            new Middleware('permission:Current Openings Edit', only: ['edit', 'update']),
            new Middleware('permission:Current Openings Delete', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $query = CurrentOpening::with('company');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_role', 'LIKE', "%{$search}%")
                  ->orWhere('job_location', 'LIKE', "%{$search}%")
                  ->orWhereHas('company', function($c) use ($search) {
                      $c->where('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            if ($request->status === 'Today\'s') {
                $query->whereDate('created_at', Carbon::today());
            } else {
                $query->where('opening_status', $request->status);
            }
        }

        $openings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        if ($request->ajax()) {
            $html = view('current_openings._table_rows', compact('openings'))->render();
            $pagination = (string) $openings->links('pagination::bootstrap-4');
            return response()->json(['html' => $html, 'pagination' => $pagination]);
        }

        $totalOpenings = CurrentOpening::count();

        return view('current_openings.index', compact('openings', 'totalOpenings'));
    }
    
    public function show($id)
    {
        $opening = CurrentOpening::with('company')->findOrFail(decrypt($id));
        
        // Fetch call logs based on the HR phone number
        $callLogs = [];
        if ($opening->company && $opening->company->hr_contact_number) {
            $phoneNumber = $opening->company->hr_contact_number;
            if (class_exists(\App\Models\CallLog::class)) {
                $callLogs = \App\Models\CallLog::with('staff')->where('caller_number', $phoneNumber)->orderBy('created_at', 'desc')->get();
            }
        }

        $students = \App\Models\Admission::with(['contact', 'course', 'batch'])
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'Completed'])
            ->latest()
            ->take(100)
            ->get();
        
        // Fetch scheduled interviews for this opening
        $companyName = trim($opening->company->company_name ?? '');
        $roleName = trim($opening->job_role ?? '');

        $interviewsQuery = \App\Models\ScheduleInterview::with('user');
        if (!empty($companyName) && !empty($roleName)) {
            $interviewsQuery->where(function ($q) use ($companyName, $roleName) {
                $q->where(function ($sq) use ($companyName, $roleName) {
                    $sq->where('company', $companyName)
                       ->where('role', $roleName);
                })->orWhere(function ($sq) use ($companyName, $roleName) {
                    $sq->where('company', 'LIKE', '%' . $companyName . '%')
                       ->where('role', 'LIKE', '%' . $roleName . '%');
                });
            });
        } elseif (!empty($companyName)) {
            $interviewsQuery->where(function ($q) use ($companyName) {
                $q->where('company', $companyName)
                  ->orWhere('company', 'LIKE', '%' . $companyName . '%');
            });
        } elseif (!empty($roleName)) {
            $interviewsQuery->where('role', $roleName);
        }

        $interviews = $interviewsQuery->orderBy('date', 'desc')->orderBy('time', 'desc')->get();

        $interviewSummary = [
            'scheduled' => $interviews->filter(fn($i) => empty($i->attendance) || strtolower($i->status) === 'scheduled')->count(),
            'attended' => $interviews->filter(fn($i) => strtolower($i->attendance ?? '') === 'attended' || in_array(strtolower($i->status ?? ''), ['attended', 'selected', 'rejected', 'on hold', 'next round']))->count(),
            'not_attended' => $interviews->filter(fn($i) => in_array(strtolower($i->attendance ?? ''), ['not attended', 'no show']) || in_array(strtolower($i->status ?? ''), ['not attended', 'no show', 'not_attended', 'no_show']))->count(),
            'selected' => $interviews->filter(fn($i) => strtolower($i->result ?? '') === 'selected' || strtolower($i->status ?? '') === 'selected')->count(),
            'rejected' => $interviews->filter(fn($i) => strtolower($i->result ?? '') === 'rejected' || strtolower($i->status ?? '') === 'rejected')->count(),
            'hold' => $interviews->filter(fn($i) => in_array(strtolower($i->result ?? ''), ['on hold', 'hold']) || in_array(strtolower($i->status ?? ''), ['on hold', 'hold']))->count(),
        ];
        
        return view('current_openings.show', compact('opening', 'callLogs', 'students', 'interviews', 'interviewSummary'));
    }

    public function storeCallLog(Request $request, $id)
    {
        $opening = CurrentOpening::findOrFail(decrypt($id));
        $request->validate(['note' => 'required']);

        $phoneNumber = $opening->company->hr_contact_number ?? null;

        if ($phoneNumber && class_exists(\App\Models\CallLog::class)) {
            \App\Models\CallLog::create([
                'caller_number' => $phoneNumber,
                'note' => $request->note,
                'direction' => 'Outbound', // Default to Outbound for manual logs from dashboard
                'staff_id' => auth()->id(),
                'call_start' => now(),
            ]);
            return redirect()->back()->with('success', 'Call entry added successfully.');
        }

        return redirect()->back()->with('error', 'Cannot log call. HR Phone Number is missing or CallLog model not found.');
    }

    public function create()
    {
        $companies = Company::orderBy('company_name', 'ASC')->get();
        return view('current_openings.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_role' => 'required',
            'job_location' => 'required',
            'minimum_qualification' => 'required',
            'experience_required' => 'required',
            'ctc_salary' => 'required',
            'number_of_openings' => 'required|numeric',
            'interview_mode' => 'required',
            'opening_status' => 'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('job_description_file')) {
            $file = $request->file('job_description_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/job_descriptions'), $filename);
            $data['job_description_file'] = 'uploads/job_descriptions/' . $filename;
        }

        CurrentOpening::create($data);

        return redirect()->route('current-openings.index')->with('success', 'Current Opening created successfully.');
    }
    
    public function edit($id)
    {
        $opening = CurrentOpening::findOrFail(decrypt($id));
        $companies = Company::orderBy('company_name', 'ASC')->get();
        return view('current_openings.edit', compact('opening', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $opening = CurrentOpening::findOrFail(decrypt($id));
        
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'job_role' => 'required',
            'job_location' => 'required',
            'minimum_qualification' => 'required',
            'experience_required' => 'required',
            'ctc_salary' => 'required',
            'number_of_openings' => 'required|numeric',
            'interview_mode' => 'required',
            'opening_status' => 'required',
        ]);

        $data = $request->all();

        if ($request->hasFile('job_description_file')) {
            $file = $request->file('job_description_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/job_descriptions'), $filename);
            $data['job_description_file'] = 'uploads/job_descriptions/' . $filename;
            
            // Delete old file if exists
            if ($opening->job_description_file && file_exists(public_path($opening->job_description_file))) {
                @unlink(public_path($opening->job_description_file));
            }
        }

        $opening->update($data);

        return redirect()->route('current-openings.index')->with('success', 'Current Opening updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $opening = CurrentOpening::findOrFail(decrypt($id));
            $opening->delete();
            return response()->json(['status' => true, 'message' => 'Current Opening deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Error deleting entry.']);
        }
    }

    public function sendMail(Request $request, $id)
    {
        try {
            $opening = CurrentOpening::with('company')->findOrFail(decrypt($id));
            
            // Dynamic recipient resolution: request input -> opening hr_email_id -> company_mail_id -> company hr_email
            $recipient = $request->input('email') 
                ?: ($opening->hr_email_id 
                ?: ($opening->company_mail_id 
                ?: ($opening->company->hr_email ?? null)));

            if (!$recipient || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                return response()->json([
                    'status' => false, 
                    'message' => 'No valid recipient email provided or found for this opening. Please provide an email address.'
                ], 422);
            }
            
            \Illuminate\Support\Facades\Mail::mailer('brevo')
                ->to($recipient)
                ->send(new \App\Mail\CurrentOpeningMail($opening));

            \Illuminate\Support\Facades\Log::info("Brevo Mail sent successfully to {$recipient} for opening ID: {$opening->id}");
            return response()->json([
                'status' => true, 
                'message' => "Opening details sent successfully to {$recipient}"
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Brevo Mail exception: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Error sending email: ' . $e->getMessage()], 500);
        }
    }
}
