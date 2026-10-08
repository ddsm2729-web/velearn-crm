<?php

namespace App\Http\Controllers;

use App\Models\MiniProject;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MiniProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Mini Project List', only: ['index', 'show']),
            new Middleware('permission:Mini Project Create', only: ['create', 'store']),
            new Middleware('permission:Mini Project Edit', only: ['edit', 'update']),
            new Middleware('permission:Mini Project Delete', only: ['destroy']),
            new Middleware('permission:Mini Project Status', only: ['updateStatus']),
        ];
    }

    public function index(Request $request)
    {
        // "top display courses are live courses"
        $liveCourses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        
        $mini_projects = MiniProject::with(['course', 'batch'])
            ->when($request->course_id, function ($query, $course_id) {
                return $query->where('course_id', $course_id);
            })
            ->when($request->batch_id, function ($query, $batch_id) {
                return $query->where('batch_id', $batch_id);
            })
            ->latest()
            ->paginate(10);

        return view('mini_projects.index', compact('mini_projects', 'liveCourses', 'batches'));
    }

    public function create()
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        return view('mini_projects.create', compact('courses', 'batches'));
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
            $request->document->move(public_path('uploads/mini_projects'), $docName);
            $validated['document'] = $docName;
        }

        $mini_project = MiniProject::create($validated);

        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $mini_project->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('mini_projects.index')->with('success', 'Mini Project Created successfully.');
    }

    public function show(MiniProject $mini_project)
    {
        $mini_project->load(['course', 'batch', 'submissions.student', 'submissions.scoreBreakdowns']);
        return view('mini_projects.show', compact('mini_project'));
    }

    public function edit(MiniProject $mini_project)
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        return view('mini_projects.edit', compact('mini_project', 'courses', 'batches'));
    }

    public function update(Request $request, MiniProject $mini_project)
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
            if ($mini_project->document && file_exists(public_path('uploads/mini_projects/' . $mini_project->document))) {
                unlink(public_path('uploads/mini_projects/' . $mini_project->document));
            }
            $docName = time() . '_' . uniqid() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads/mini_projects'), $docName);
            $validated['document'] = $docName;
        }

        $mini_project->update($validated);

        $mini_project->criteria()->delete();
        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $mini_project->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('mini_projects.index')->with('success', 'MiniProject updated successfully.');
    }

    public function destroy(MiniProject $mini_project)
    {
        $mini_project->delete();
        return redirect()->route('mini_projects.index')->with('success', 'Mini Project Deleted successfully.');
    }

    public function updateStatus(Request $request, MiniProject $mini_project)
    {
        $mini_project->status = $request->status;
        $mini_project->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
