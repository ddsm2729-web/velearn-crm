<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchSystemController extends Controller
{
    public function index(Request $request)
    {
        $courses = \App\Models\Course::where('status', '1')->where('course_type', 'live')->get();
        
        $query = \App\Models\Batch::whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })
            ->with(['course', 'instructor'])
            ->orderBy('start_date', 'asc');

        if ($request->ajax()) {
            if ($request->course_id && $request->course_id !== 'all') {
                $courseId = str_replace('course-', '', $request->course_id);
                if (is_numeric($courseId)) {
                    $query->where('course_id', $courseId);
                }
            }

            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('batch_code', 'like', '%' . $request->search . '%')
                      ->orWhere('batch_name', 'like', '%' . $request->search . '%')
                      ->orWhereHas('instructor', function($q2) use ($request) {
                          $q2->where('first_name', 'like', '%' . $request->search . '%')
                             ->orWhere('last_name', 'like', '%' . $request->search . '%');
                      })
                      ->orWhereHas('course', function($q3) use ($request) {
                          $q3->where('title', 'like', '%' . $request->search . '%');
                      });
                });
            }

            if ($request->track) {
                if ($request->track === 'normal') {
                    $query->where(function($q) {
                        $q->where('batch_track', 'Normal')->orWhereNull('batch_track');
                    });
                } elseif ($request->track === 'fast-track') {
                    $query->where('batch_track', 'Fast Track');
                }
            }

            if ($request->status) {
                $today = now()->toDateString();
                if ($request->status === 'active') {
                    $query->where('start_date', '<=', $today); 
                } elseif ($request->status === 'completed') {
                    // Logic for completed
                }
            }

            $batches = $query->get();
            return view('batch-system.partials.list', compact('batches'))->render();
        }

        $batches = $query->get();

        return view('batch-system.index', compact('courses', 'batches'));
    }
}
