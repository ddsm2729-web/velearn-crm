<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Staff;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Registered Students pipeline includes leads with Status OR Tag in: Converted, Confirmed, Registered
        $targetStatuses = ['Converted', 'Confirmed', 'Registered', 'Payment Done'];
        $targetTags = ['converted', 'confirmed', 'Registered', 'register', 'Demo converted'];

        $baseQuery = Lead::where(function($q) use ($targetStatuses, $targetTags) {
            $q->whereHas('status', function($sq) use ($targetStatuses) {
                $sq->whereIn('name', $targetStatuses);
            })->orWhereHas('tags', function($tq) use ($targetTags) {
                $tq->whereIn('name', $targetTags);
            });
        });

        // Calculate Stats
        $totalRegistered = (clone $baseQuery)->count();
        
        // Pending Admission: Converted or Confirmed (not yet registered)
        $pendingAdmission = (clone $baseQuery)->where(function($q) {
            $q->where(function($sq) {
                $sq->whereHas('status', fn($s) => $s->whereIn('name', ['Converted', 'Confirmed']))
                   ->orWhereHas('tags', fn($t) => $t->whereIn('name', ['converted', 'confirmed']));
            })->whereDoesntHave('status', fn($s) => $s->whereIn('name', ['Registered', 'Payment Done']))
              ->whereDoesntHave('tags', fn($t) => $t->whereIn('name', ['Registered', 'register']));
        })->count();

        // Fully Registered: Registered or Payment Done
        $fullyRegistered = (clone $baseQuery)->where(function($q) {
            $q->whereHas('status', fn($s) => $s->whereIn('name', ['Registered', 'Payment Done']))
              ->orWhereHas('tags', fn($t) => $t->whereIn('name', ['Registered', 'register']));
        })->count();

        // Converted, Confirmed, or Registered this week
        $thisWeek = (clone $baseQuery)->where(function($q) {
            $q->where('converted_date', '>=', now()->startOfWeek())
              ->orWhere('updated_at', '>=', now()->startOfWeek());
        })->count();

        // Build filtered query
        $query = clone $baseQuery;

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
            $statusVal = strtolower($request->status);
            if ($statusVal === 'confirmed') {
                $query->where(function($q) {
                    $q->whereHas('status', fn($sq) => $sq->where('name', 'Confirmed'))
                      ->orWhereHas('tags', fn($tq) => $tq->whereIn('name', ['confirmed']));
                });
            } elseif ($statusVal === 'converted') {
                $query->where(function($q) {
                    $q->whereHas('status', fn($sq) => $sq->where('name', 'Converted'))
                      ->orWhereHas('tags', fn($tq) => $tq->whereIn('name', ['converted', 'Demo converted']));
                });
            } elseif ($statusVal === 'registered') {
                $query->where(function($q) {
                    $q->whereHas('status', fn($sq) => $sq->whereIn('name', ['Registered', 'Payment Done']))
                      ->orWhereHas('tags', fn($tq) => $tq->whereIn('name', ['Registered', 'register']));
                });
            }
        }

        // Fetch students
        $convertedLeads = $query->with(['source', 'status', 'assignee', 'tags', 'courses', 'admission.course', 'admission.batch'])
                                ->latest()
                                ->paginate(20)
                                ->withQueryString();

        // Fetch Admission Officers for filter dropdown
        $admissionOfficers = Staff::whereHas('role', function($q) {
            $q->where('name', 'Admission Officer');
        })->get();
        
        return view('students.index', compact(
            'convertedLeads', 'totalRegistered', 'pendingAdmission', 'fullyRegistered', 'thisWeek', 'admissionOfficers'
        ));
    }
}
