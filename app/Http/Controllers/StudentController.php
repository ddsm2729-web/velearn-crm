<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // We consider "Registered Students" pipeline to include leads that are Converted (2) or Registered (6) or Payment Done (5)
        $query = \App\Models\Lead::whereHas('status', function($q) {
            $q->whereIn('name', ['Converted', 'Registered', 'Payment Done']);
        });

        // Calculate Stats
        $totalRegistered = (clone $query)->count();
        
        $pendingAdmission = (clone $query)->whereHas('status', function($q) {
            $q->where('name', 'Converted');
        })->count();

        $fullyRegistered = (clone $query)->whereHas('status', function($q) {
            $q->whereIn('name', ['Registered', 'Payment Done']);
        })->count();

        $thisWeek = (clone $query)->where('converted_date', '>=', now()->startOfWeek())->count();

        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('ao_id')) {
            $query->where('assigned_to', $request->ao_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('status', function($q) use ($request) {
                $q->where('name', $request->status);
            });
        }

        // Fetch students
        $convertedLeads = $query->with(['source', 'status', 'assignee', 'tags', 'courses'])
                                ->latest()
                                ->paginate(20)
                                ->withQueryString();

        // Fetch Admission Officers for filter dropdown
        $admissionOfficers = \App\Models\Staff::whereHas('role', function($q) {
            $q->where('name', 'Admission Officer');
        })->get();
        
        return view('students.index', compact(
            'convertedLeads', 'totalRegistered', 'pendingAdmission', 'fullyRegistered', 'thisWeek', 'admissionOfficers'
        ));
    }
}
