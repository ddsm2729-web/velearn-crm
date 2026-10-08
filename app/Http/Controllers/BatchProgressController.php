<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BatchProgressController extends Controller
{
    public function index()
    {
        $courses = \App\Models\Course::where('status', '1')->where('course_type', 'live')->get();
        
        $batches = \App\Models\Batch::whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })
            ->with(['course', 'instructor'])
            ->orderBy('start_date', 'asc')
            ->get();

        return view('tech.batch-progress.index', compact('courses', 'batches'));
    }
}
