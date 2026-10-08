<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AssignmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Assignment List', only: ['index', 'show']),
            new Middleware('permission:Assignment Create', only: ['create', 'store']),
            new Middleware('permission:Assignment Edit', only: ['edit', 'update']),
            new Middleware('permission:Assignment Delete', only: ['destroy']),
            new Middleware('permission:Assignment Status', only: ['updateStatus']),
        ];
    }

    public function index(Request $request)
    {
        // "top display courses are live courses"
        $liveCourses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        
        $assignments = Assignment::with(['course', 'batch'])
            ->when($request->course_id, function ($query, $course_id) {
                return $query->where('course_id', $course_id);
            })
            ->when($request->batch_id, function ($query, $batch_id) {
                return $query->where('batch_id', $batch_id);
            })
            ->latest()
            ->paginate(10);

        return view('assignments.index', compact('assignments', 'liveCourses', 'batches'));
    }

    public function create()
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        return view('assignments.create', compact('courses', 'batches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'reveal_date' => 'required|date',
        ]);

        $validated['status'] = 'pending';

        if ($request->hasFile('document')) {
            $docName = time() . '_' . uniqid() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads/assignments'), $docName);
            $validated['document'] = $docName;
        }

        $assignment = Assignment::create($validated);

        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $assignment->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('assignments.index')->with('success', 'Assignment created successfully.');
    }

    public function show(Assignment $assignment)
    {
        $assignment->load(['course', 'batch', 'submissions.student', 'submissions.scoreBreakdowns']);
        return view('assignments.show', compact('assignment'));
    }

    public function edit(Assignment $assignment)
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        return view('assignments.edit', compact('assignment', 'courses', 'batches'));
    }

    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'reveal_date' => 'required|date',
        ]);

        if ($request->hasFile('document')) {
            if ($assignment->document && file_exists(public_path('uploads/assignments/' . $assignment->document))) {
                unlink(public_path('uploads/assignments/' . $assignment->document));
            }
            $docName = time() . '_' . uniqid() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads/assignments'), $docName);
            $validated['document'] = $docName;
        }

        $assignment->update($validated);

        $assignment->criteria()->delete();
        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $assignment->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('assignments.index')->with('success', 'Assignment updated successfully.');
    }

    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->route('assignments.index')->with('success', 'Assignment deleted successfully.');
    }

    public function updateStatus(Request $request, Assignment $assignment)
    {
        $assignment->status = $request->status;
        $assignment->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
