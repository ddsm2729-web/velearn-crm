<?php

namespace App\Http\Controllers;

use App\Models\BatchTopicLog;
use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseCurriculum;
use Illuminate\Http\Request;

class TechBatchLogController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isTechStaff = $user->hasRole('Technical Staff');
        $staffId = null;
        if ($isTechStaff) {
            $staff = \App\Models\Staff::where('auth_id', $user->id)->first();
            $staffId = $staff ? $staff->id : null;
        }

        $logsQuery = BatchTopicLog::with(['batch', 'curriculum'])->latest();
        if ($isTechStaff && $staffId) {
            $logsQuery->whereHas('batch', function($q) use ($staffId) {
                $q->where('instructor_id', $staffId);
            });
        }
        $logs = $logsQuery->paginate(15);

        $courses = Course::where('course_type', 'live')
            ->whereHas('batches', function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
            })
            ->with(['batches' => function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
                $q->orderBy('batch_name', 'asc');
            }, 'curricula'])
            ->orderBy('title', 'asc')
            ->get();
            
        return view('tech.batch-logs.index', compact('logs', 'courses'));
    }

    public function create()
    {
        $user = auth()->user();
        $isTechStaff = $user->hasRole('Technical Staff');
        $staffId = null;
        if ($isTechStaff) {
            $staff = \App\Models\Staff::where('auth_id', $user->id)->first();
            $staffId = $staff ? $staff->id : null;
        }

        $courses = Course::where('course_type', 'live')
            ->whereHas('batches', function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
            })
            ->with(['batches' => function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
                $q->orderBy('batch_name', 'asc');
            }, 'curricula'])
            ->orderBy('title', 'asc')
            ->get();

        return view('tech.batch-logs.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'course_curriculum_id' => 'required|exists:course_curricula,id',
            'status' => 'required|in:Pending,In Progress,Completed',
            'date' => 'required|date',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
            'note' => 'nullable|string',
        ]);

        $log = new BatchTopicLog();
        $log->batch_id = $request->batch_id;
        $log->course_curriculum_id = $request->course_curriculum_id;
        $log->description = $request->description;
        $log->status = $request->status;
        $log->date = $request->date;
        $log->note = $request->note;
        $log->is_completed = ($request->status === 'Completed');

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('videos/batch_videos'), $videoName);
            $log->video = 'videos/batch_videos/' . $videoName;
        }

        $log->save();

        return redirect()->route('tech.batch-logs.index')->with('success', 'Batch Topic Log created successfully.');
    }

    public function edit(BatchTopicLog $batch_log)
    {
        $user = auth()->user();
        $isTechStaff = $user->hasRole('Technical Staff');
        $staffId = null;
        if ($isTechStaff) {
            $staff = \App\Models\Staff::where('auth_id', $user->id)->first();
            $staffId = $staff ? $staff->id : null;
        }

        $courses = Course::where('course_type', 'live')
            ->whereHas('batches', function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
            })
            ->with(['batches' => function($q) use ($isTechStaff, $staffId) {
                if ($isTechStaff && $staffId) {
                    $q->where('instructor_id', $staffId);
                }
                $q->orderBy('batch_name', 'asc');
            }, 'curricula'])
            ->orderBy('title', 'asc')
            ->get();

        return view('tech.batch-logs.edit', compact('batch_log', 'courses'));
    }

    public function update(Request $request, BatchTopicLog $batch_log)
    {
        $request->validate([
            'batch_id' => 'required|exists:batches,id',
            'course_curriculum_id' => 'required|exists:course_curricula,id',
            'status' => 'required|in:Pending,In Progress,Completed',
            'date' => 'required|date',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200',
            'note' => 'nullable|string',
        ]);

        $batch_log->batch_id = $request->batch_id;
        $batch_log->course_curriculum_id = $request->course_curriculum_id;
        $batch_log->description = $request->description;
        $batch_log->status = $request->status;
        $batch_log->date = $request->date;
        $batch_log->note = $request->note;
        $batch_log->is_completed = ($request->status === 'Completed');

        if ($request->hasFile('video')) {
            if ($batch_log->video && file_exists(public_path($batch_log->video))) {
                unlink(public_path($batch_log->video));
            }
            $video = $request->file('video');
            $videoName = time() . '_' . uniqid() . '.' . $video->getClientOriginalExtension();
            $video->move(public_path('videos/batch_videos'), $videoName);
            $batch_log->video = 'videos/batch_videos/' . $videoName;
        }

        $batch_log->save();

        return redirect()->route('tech.batch-logs.index')->with('success', 'Batch Topic Log updated successfully.');
    }

    public function destroy(BatchTopicLog $batch_log)
    {
        if ($batch_log->video && file_exists(public_path($batch_log->video))) {
            unlink(public_path($batch_log->video));
        }
        $batch_log->delete();
        return redirect()->route('tech.batch-logs.index')->with('success', 'Batch Topic Log deleted successfully.');
    }
}
