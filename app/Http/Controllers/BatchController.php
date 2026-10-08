<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BatchController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Batch List', only: ['index']),
            new Middleware('permission:Batch Create', only: ['create', 'store']),
            new Middleware('role_or_permission:Technical Head|Batch Edit', only: ['edit', 'update']),
            new Middleware('role_or_permission:Technical Head|Batch Delete', only: ['destroy']),
            new Middleware('permission:Batch Allocation', only: ['allocation']),
        ];
    }

    public function index()
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();
        $today = now()->toDateString();
        
        $batches = Batch::with(['course', 'instructor'])
            ->orderBy('start_date', 'asc')
            ->get()
            ->groupBy('course_id');

        return view('batches.index', compact('courses', 'batches', 'today'));
    }

    public function allocation()
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();
        $today = now()->toDateString();
        
        $batches = Batch::whereHas('course', function($q) {
                $q->where('course_type', 'live');
            })
            ->with(['course', 'instructor'])
            ->orderBy('start_date', 'asc')
            ->get();

        return view('batches.allocation', compact('courses', 'batches', 'today'));
    }

    public function show(Batch $batch)
    {
        $batch->load(['course', 'instructor']);
        
        $admissions = \App\Models\Admission::with('contact')
            ->where('batch_id', $batch->id)
            ->get();

        $topicLogs = \App\Models\BatchTopicLog::with('curriculum')
            ->where('batch_id', $batch->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedTopics = $topicLogs->where('is_completed', true)->pluck('course_curriculum_id');
        
        $pendingTopics = \App\Models\CourseCurriculum::where('course_id', $batch->course_id)
            ->whereNotIn('id', $completedTopics)
            ->get();
            
        return view('batches.show', compact('batch', 'admissions', 'topicLogs', 'pendingTopics'));
    }

    public function storeTopicLog(Request $request, $batchId)
    {
        $request->validate([
            'course_curriculum_id' => 'required',
            'description' => 'nullable',
            'is_completed' => 'nullable',
            'status' => 'nullable'
        ]);
        
        \App\Models\BatchTopicLog::create([
            'batch_id' => $batchId,
            'course_curriculum_id' => $request->course_curriculum_id,
            'description' => $request->description,
            'is_completed' => $request->has('is_completed') ? true : false,
            'status' => $request->status ?? ($request->has('is_completed') ? 'Completed' : 'Pending'),
        ]);
        return back()->with('success', 'Topic logged successfully.');
    }
    
    public function updateTopicLog(Request $request, $id)
    {
        $log = \App\Models\BatchTopicLog::findOrFail($id);
        $request->validate([
            'course_curriculum_id' => 'required',
            'description' => 'nullable',
            'is_completed' => 'nullable',
            'status' => 'nullable'
        ]);
        
        $log->update([
            'course_curriculum_id' => $request->course_curriculum_id,
            'description' => $request->description,
            'is_completed' => $request->has('is_completed') ? true : false,
            'status' => $request->status ?? ($request->has('is_completed') ? 'Completed' : 'Pending'),
        ]);
        return back()->with('success', 'Topic log updated successfully.');
    }

    public function create()
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();
        $instructors = Staff::where('status', 1)
            ->whereHas('role', function($q) {
                $q->where('name', 'Technical Staff');
            })->get();
        return view('batches.create', compact('courses', 'instructors'));
    }

    public function store(Request $request, \App\Services\ZoomService $zoomService)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|unique:batches,batch_code',
            'batch_type' => 'required|in:Weekday Morning,Weekday Evening,Weekend',
            'batch_track' => 'required|in:Normal,Fast Track',
            'status' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'class_dates' => 'required|array',
            'class_dates.*' => 'required|date',
            'batch_from_time' => 'required',
            'batch_to_time' => 'required',
            'class_duration' => 'nullable|string|max:255',
            'max_students' => 'required|integer|min:1',
        ]);

        $data = $request->all();

        // We don't save the meeting link on the Batch model directly anymore,
        // unless it's a manual link for the whole batch. 
        // But the requirements asked to generate for all dates.
        if (!$request->has('generate_zoom_link') || $request->generate_zoom_link != '1') {
            $data['meeting_link'] = $request->meeting_link;
        }

        $batch = Batch::create($data);

        // Generate schedules and links
        $durationMap = [
            '1 hr' => 60,
            '1.5 hrs' => 90,
            '2 hrs' => 120,
            '2.5 hrs' => 150,
            '3 hrs' => 180,
        ];
        $durationInMinutes = $durationMap[$request->class_duration] ?? 60;

        // We only use the explicitly assigned instructor as an alternative host.
        // Sending all technical heads blindly causes Zoom Error 1114 loops if any email isn't licensed.
        $instructorEmail = null;
        if ($request->has('instructor_id') && !empty($request->instructor_id)) {
            $instructor = \App\Models\Staff::find($request->instructor_id);
            if ($instructor && $instructor->email) {
                $instructorEmail = $instructor->email;
            }
        }

        $allHosts = [];
        if ($instructorEmail) {
            $allHosts[] = $instructorEmail;
        }
        $alternativeHosts = implode(',', array_unique(array_filter($allHosts)));

        foreach ($request->class_dates as $date) {
            $scheduleData = [
                'batch_id' => $batch->id,
                'date' => $date,
                'start_time' => $request->batch_from_time,
                'end_time' => $request->batch_to_time,
                'meeting_link' => null,
                'zoom_meeting_id' => null,
            ];

            if ($request->has('generate_zoom_link') && $request->generate_zoom_link == '1') {
                $startTime = $date . 'T' . $request->batch_from_time . ':00';
                $topic = $request->batch_name . ' (' . $request->batch_code . ') - ' . \Carbon\Carbon::parse($date)->format('d M Y');
                
                $meeting = $zoomService->createMeeting(
                    $topic,
                    $startTime,
                    $durationInMinutes,
                    'Asia/Kolkata',
                    $alternativeHosts
                );

                if ($meeting && isset($meeting['join_url'])) {
                    $scheduleData['meeting_link'] = $meeting['join_url'];
                    $scheduleData['zoom_meeting_id'] = $meeting['id'] ?? null;
                }
            } else {
                $scheduleData['meeting_link'] = $request->meeting_link;
            }

            \App\Models\BatchSchedule::create($scheduleData);
        }

        return redirect()->route('batches.index')->with('success', 'Batch created successfully');
    }

    public function edit(Batch $batch)
    {
        $courses = Course::where('status', '1')->where('course_type', 'live')->get();
        $instructors = Staff::where('status', 1)
            ->whereHas('role', function($q) {
                $q->where('name', 'Technical Staff');
            })->get();
        return view('batches.edit', compact('batch', 'courses', 'instructors'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_name' => 'required|string|max:255',
            'batch_code' => 'required|string|unique:batches,batch_code,' . $batch->id,
            'batch_type' => 'required|in:Weekday Morning,Weekday Evening,Weekend',
            'batch_track' => 'required|in:Normal,Fast Track',
            'status' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'batch_from_time' => 'required',
            'batch_to_time' => 'required',
            'class_duration' => 'nullable|string|max:255',
            'max_students' => 'required|integer|min:1',
        ]);

        $batch->update($request->all());

        return redirect()->route('batches.index')->with('success', 'Batch updated successfully');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully');
    }

    public function toggleStatus(Batch $batch)
    {
        $batch->batch_status = $batch->batch_status === 'Completed' ? 'Running' : 'Completed';
        $batch->save();

        return response()->json([
            'success' => true,
            'status' => $batch->batch_status,
            'message' => 'Batch status updated successfully.'
        ]);
    }

    public function searchAllocationStudents(Request $request)
    {
        $search = $request->get('q');
        $batchId = $request->get('batch_id');
        $batch = Batch::findOrFail($batchId);
        $courseId = $batch->course_id;

        $contacts = \App\Models\Contact::where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('caller_number', 'LIKE', "%{$search}%");
            })
            // Allow if Contact is associated with a Lead that is Confirmed/Converted, OR just allow all matching contacts for flexibility during manual allocation.
            // We will just allow all matching contacts to avoid complex phone number matching logic that might drop valid students.
            ->with(['admissions.course', 'admissions.batch'])
            ->take(20)
            ->get();
        
        $results = [];
        foreach ($contacts as $contact) {
            $existingAdmissionForCourse = $contact->admissions->where('course_id', $courseId)->first();
            
            $results[] = [
                'id' => $contact->id,
                'name' => $contact->name,
                'phone' => $contact->caller_number,
                // Only consider assigned if they have an admission AND a batch is already set
                'is_already_assigned' => $existingAdmissionForCourse && !is_null($existingAdmissionForCourse->batch_id),
                'assigned_batch' => $existingAdmissionForCourse && $existingAdmissionForCourse->batch ? $existingAdmissionForCourse->batch->batch_code : null,
                'avatar_initials' => strtoupper(substr($contact->name, 0, 2)),
            ];
        }

        return response()->json($results);
    }

    public function allocateStudent(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'batch_id' => 'required|exists:batches,id',
        ]);

        $batch = Batch::with('course')->findOrFail($request->batch_id);
        
        // Find existing admission to handle count transfers
        $existingAdmission = \App\Models\Admission::where('contact_id', $request->contact_id)
            ->where('course_id', $batch->course_id)
            ->first();
            
        $oldBatchId = $existingAdmission ? $existingAdmission->batch_id : null;

        $admission = \App\Models\Admission::updateOrCreate(
            [
                'contact_id' => $request->contact_id,
                'course_id' => $batch->course_id
            ],
            [
                'batch_id' => $batch->id,
                'batch_type' => $batch->batch_type ?? 'Weekday',
                'course_fees' => $batch->course->buy_price ?? 0,
                'payment_mode' => 'Full Payment',
                'status' => 'Active'
            ]
        );

        if ($oldBatchId != $batch->id) {
            // Increment the new batch
            $batch->increment('joined_student_count');
            
            // Decrement the old batch if there was one
            if ($oldBatchId) {
                Batch::where('id', $oldBatchId)->where('joined_student_count', '>', 0)->decrement('joined_student_count');
            }
        }

        // Sync to the frontend CourseUser record so the Profile page displays it
        $contact = \App\Models\Contact::find($request->contact_id);
        if ($contact) {
            $user = \App\Models\User::where('phonenumber', $contact->caller_number)
                ->orWhere('email', $contact->email)
                ->first();
                
            if ($user) {
                \App\Models\CourseUser::where('auth_id', $user->id)
                    ->where('course_id', $batch->course_id)
                    ->update([
                        'batch_id' => $batch->id,
                        'status' => 'active'
                    ]);
            }
        }

        return response()->json(['status' => true, 'message' => 'Student allocated to batch successfully.']);
    }

    public function getPendingTopics($batchId)
    {
        $batch = Batch::findOrFail($batchId);
        
        // Get all completed topics for this batch
        $completedTopics = \App\Models\BatchTopicLog::where('batch_id', $batchId)
            ->where('is_completed', true)
            ->pluck('course_curriculum_id');
            
        // Get topics that are NOT completed
        $pendingTopics = \App\Models\CourseCurriculum::where('course_id', $batch->course_id)
            ->whereNotIn('id', $completedTopics)
            ->get();
            
        return response()->json([
            'status' => true,
            'data' => $pendingTopics
        ]);
    }

    public function logMeetingTopic(Request $request, $batchId)
    {
        $request->validate([
            'course_curriculum_id' => 'required|exists:course_curricula,id',
            'description' => 'nullable|string',
            'is_completed' => 'boolean',
            'status' => 'nullable|string'
        ]);

        $log = \App\Models\BatchTopicLog::create([
            'batch_id' => $batchId,
            'batch_schedule_id' => $request->batch_schedule_id,
            'course_curriculum_id' => $request->course_curriculum_id,
            'description' => $request->description,
            'is_completed' => $request->is_completed ?? false,
            'status' => $request->status ?? ($request->is_completed ? 'Completed' : 'Pending'),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Meeting topic logged successfully',
            'data' => $log
        ]);
    }
}
