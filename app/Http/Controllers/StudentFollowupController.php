<?php

namespace App\Http\Controllers;

use App\Models\Followup;
use App\Models\User;
use App\Models\Course;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentFollowupController extends Controller
{
    /**
     * Display a listing of followups with Student and Company tabs.
     */
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'student');
        if (!in_array($activeTab, ['student', 'company'])) {
            $activeTab = 'student';
        }

        // Base query for the active tab
        $query = Followup::where('type', $activeTab)->orderByDesc('call_date')->orderByDesc('id');

        // Search filter
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('hr_name', 'like', "%{$search}%")
                    ->orWhere('hr_phone', 'like', "%{$search}%")
                    ->orWhere('course', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('followup_code', 'like', "%{$search}%");
            });
        }

        // Purpose filter
        if ($request->filled('purpose') && strtolower($request->purpose) !== 'all') {
            $query->where('purpose', $request->purpose);
        }

        // Date filter
        if ($request->filled('date') && strtolower($request->date) !== 'all') {
            $query->whereDate('call_date', $request->date);
        }

        // Month filter
        if ($request->filled('month') && strtolower($request->month) !== 'all') {
            $query->whereMonth('call_date', (int) $request->month);
        }

        $followups = $query->paginate(20)->withQueryString();

        // Counts
        $totalCount = Followup::count();
        $studentCount = Followup::where('type', 'student')->count();
        $companyCount = Followup::where('type', 'company')->count();

        // Unique filter options from data
        $dates = Followup::where('type', $activeTab)
            ->whereNotNull('call_date')
            ->distinct()
            ->orderByDesc('call_date')
            ->pluck('call_date')
            ->map(function ($d) {
                return Carbon::parse($d)->format('Y-m-d');
            })
            ->unique();

        // Purpose options for active tab
        if ($activeTab === 'student') {
            $purposeOptions = [
                'Inform about Interview',
                'Ask Interview Experience',
                'Other'
            ];
        } else {
            $purposeOptions = [
                'Ask Interview Date',
                'Ask Interview Status',
                'Other'
            ];
        }

        // Data for Entry modal
        $studentsList = User::whereHas('courseUsers')
            ->orderBy('name')
            ->get(['id', 'name', 'phonenumber']);
        if ($studentsList->isEmpty()) {
            $studentsList = User::orderBy('name')->take(50)->get(['id', 'name', 'phonenumber']);
        }

        $coursesList = Course::orderBy('title')->get(['id', 'title']);
        $companiesList = Company::orderBy('company_name')->get(['id', 'company_name', 'hr_name', 'hr_contact_number']);

        return view('student-followups.index', compact(
            'followups',
            'activeTab',
            'totalCount',
            'studentCount',
            'companyCount',
            'dates',
            'purposeOptions',
            'studentsList',
            'coursesList',
            'companiesList'
        ));
    }

    /**
     * Store a newly created followup.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:student,company',
            'student_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'course' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'hr_name' => 'nullable|string|max:255',
            'hr_phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:255',
            'call_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Generate followup_code
        $prefix = $validated['type'] === 'student' ? 'FS' : 'FC';
        $latestRecord = Followup::where('type', $validated['type'])
            ->where('followup_code', 'like', "{$prefix}%")
            ->orderByDesc('id')
            ->first();

        $nextNumber = 1;
        if ($latestRecord && preg_match('/' . $prefix . '(\d+)/', $latestRecord->followup_code, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        $code = sprintf("%s%03d", $prefix, $nextNumber);
        while (Followup::where('followup_code', $code)->exists()) {
            $nextNumber++;
            $code = sprintf("%s%03d", $prefix, $nextNumber);
        }

        $validated['followup_code'] = $code;
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'completed';

        Followup::create($validated);

        return redirect()->route('student-followups.index', ['tab' => $validated['type']])
            ->with('success', ucfirst($validated['type']) . ' follow-up entry recorded successfully.');
    }

    /**
     * Remove the specified followup.
     */
    public function destroy($id)
    {
        $followup = Followup::findOrFail($id);
        $tab = $followup->type;
        $followup->delete();

        return redirect()->route('student-followups.index', ['tab' => $tab])
            ->with('success', 'Follow-up removed successfully.');
    }
}
