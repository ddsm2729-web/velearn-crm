<?php

namespace App\Http\Controllers;

use App\Models\MainProject;
use App\Models\Course;
use App\Models\Batch;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class MainProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Main Project List', only: ['index', 'show']),
            new Middleware('permission:Main Project Create', only: ['create', 'store']),
            new Middleware('permission:Main Project Edit', only: ['edit', 'update']),
            new Middleware('permission:Main Project Delete', only: ['destroy']),
            new Middleware('permission:Main Project Status', only: ['updateStatus']),
        ];
    }

    public function index(Request $request)
    {
        // "top display courses are live courses"
        $liveCourses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        
        $main_projects = MainProject::with(['course', 'batch'])
            ->when($request->course_id, function ($query, $course_id) {
                return $query->where('course_id', $course_id);
            })
            ->when($request->batch_id, function ($query, $batch_id) {
                return $query->where('batch_id', $batch_id);
            })
            ->latest()
            ->get();

        return view('main_projects.index', compact('main_projects', 'liveCourses', 'batches'));
    }

    public function create(Request $request)
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        $selectedCourseId = $request->query('course_id');
        return view('main_projects.create', compact('courses', 'batches', 'selectedCourseId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'review_1_title' => 'required|string|max:255',
            'review_1_deadline' => 'required|date',
            'review_2_title' => 'required|string|max:255',
            'review_2_deadline' => 'required|date',
            'review_3_title' => 'required|string|max:255',
            'review_3_deadline' => 'required|date',
            'reveal_date' => 'required|date',
        ]);

        $validated['status'] = 'pending';

        if ($request->hasFile('document')) {
            $docName = time() . '_' . uniqid() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads/main_projects'), $docName);
            $validated['document'] = $docName;
        }

        $main_project = MainProject::create($validated);

        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $main_project->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('main_projects.index')->with('success', 'Main Project Created successfully.');
    }

    public function show(MainProject $main_project)
    {
        $main_project->load(['course', 'batch', 'submissions.student', 'submissions.scoreBreakdowns']);
        return view('main_projects.show', compact('main_project'));
    }

    public function edit(MainProject $main_project)
    {
        $courses = Course::where('course_type', 'live')->get();
        $batches = Batch::all();
        return view('main_projects.edit', compact('main_project', 'courses', 'batches'));
    }

    public function update(Request $request, MainProject $main_project)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'review_1_title' => 'required|string|max:255',
            'review_1_deadline' => 'required|date',
            'review_2_title' => 'required|string|max:255',
            'review_2_deadline' => 'required|date',
            'review_3_title' => 'required|string|max:255',
            'review_3_deadline' => 'required|date',
            'reveal_date' => 'required|date',
        ]);

        if ($request->hasFile('document')) {
            if ($main_project->document && file_exists(public_path('uploads/main_projects/' . $main_project->document))) {
                unlink(public_path('uploads/main_projects/' . $main_project->document));
            }
            $docName = time() . '_' . uniqid() . '.' . $request->document->extension();
            $request->document->move(public_path('uploads/main_projects'), $docName);
            $validated['document'] = $docName;
        }

        $main_project->update($validated);

        $main_project->criteria()->delete();
        if ($request->has('criteria') && $request->has('maximum_marks')) {
            foreach ($request->criteria as $index => $criterion) {
                if (!empty($criterion) && isset($request->maximum_marks[$index])) {
                    $main_project->criteria()->create([
                        'criterion' => $criterion,
                        'maximum_mark' => $request->maximum_marks[$index]
                    ]);
                }
            }
        }

        return redirect()->route('main_projects.index')->with('success', 'MainProject updated successfully.');
    }

    public function destroy(MainProject $main_project)
    {
        $main_project->delete();
        return redirect()->route('main_projects.index')->with('success', 'Main Project Deleted successfully.');
    }

    public function updateStatus(Request $request, MainProject $main_project)
    {
        $main_project->status = $request->status;
        $main_project->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
    }
}
