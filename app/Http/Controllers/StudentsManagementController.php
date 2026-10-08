<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentsManagementController extends Controller
{
    public function index(Request $request)
    {
        $courses = \App\Models\Course::where('status', '1')->where('course_type', 'live')->get();
        
        $admissionsQuery = \App\Models\Admission::with(['contact', 'course', 'batch'])
            ->whereHas('course', function($q) {
                $q->where('course_type', 'live')->where('status', '1');
            })
            ->whereIn('status', ['Active', 'ongoing', 'Ongoing', 'pending_fee', 'Completed']);
            
        if (auth()->check() && auth()->user()->hasRole('Technical Staff')) {
            $staff = \App\Models\Staff::where('auth_id', auth()->id())->first();
            if ($staff) {
                $admissionsQuery->whereHas('batch', function($q) use ($staff) {
                    $q->where('instructor_id', $staff->id);
                });
            } else {
                // If not a staff, they shouldn't see students (or handle gracefully)
                $admissionsQuery->where('id', 0);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $admissionsQuery->where(function($q) use ($search) {
                $q->whereHas('contact', function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('caller_number', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('course', function($q2) use ($search) {
                    $q2->where('title', 'like', "%{$search}%");
                })->orWhereHas('batch', function($q2) use ($search) {
                    $q2->where('batch_code', 'like', "%{$search}%")
                       ->orWhere('batch_name', 'like', "%{$search}%");
                });
            });
        }

        $admissions = $admissionsQuery->get();

        if ($request->ajax()) {
            return view('tech.students.partials.list', compact('admissions'))->render();
        }

        return view('tech.students.index', compact('courses', 'admissions'));
    }
    
    public function show($id)
    {
        $admission = \App\Models\Admission::with(['contact', 'course', 'batch'])->findOrFail($id);
        
        $courseId = $admission->course_id;
        $contact = $admission->contact;
        $user = \App\Models\User::where('email', $contact->email)->first();
        $userId = $user ? $user->id : 0;
        
        // Curriculum Modules
        $modules = \App\Models\CourseCurriculum::where('course_id', $courseId)->get();
        $modulesTotal = $modules->count();
        $modulesDone = rand(1, max(1, $modulesTotal));

        // Assignments
        $assignmentsList = \App\Models\Assignment::where('course_id', $courseId)->get();
        $assignmentsCount = $assignmentsList->count();
        $assignmentSubmissions = \App\Models\AssignmentSubmission::where('user_id', $userId)->get()->keyBy('assignment_id');
        $assignmentsSubmitted = $assignmentSubmissions->count();

        // Mini Projects
        $miniProjectsList = \App\Models\MiniProject::where('course_id', $courseId)->get();
        $miniProjectsCount = $miniProjectsList->count();
        $miniProjectSubmissions = \App\Models\MiniProjectSubmission::where('user_id', $userId)->get()->keyBy('mini_project_id');
        $miniProjectsSubmitted = $miniProjectSubmissions->count();
        
        // Main Project
        $mainProjectsList = \App\Models\MainProject::where('course_id', $courseId)->get();
        $mainProjectSubmissions = \App\Models\MainProjectSubmission::where('user_id', $userId)->get()->keyBy('main_project_id');

        // Sessions
        $demos = \App\Models\SessionBooking::where('admission_id', $admission->id)->where('type', 'demo')->orderBy('date', 'desc')->get();
        $doubts = \App\Models\SessionBooking::where('admission_id', $admission->id)->where('type', 'doubt')->orderBy('date', 'desc')->get();

        $progress = rand(40, 100);
        $attendance = rand(60, 100);

        return view('tech.students.show', compact(
            'admission', 'modules', 'modulesTotal', 'modulesDone',
            'assignmentsList', 'assignmentSubmissions', 'assignmentsCount', 'assignmentsSubmitted',
            'miniProjectsList', 'miniProjectSubmissions', 'miniProjectsCount', 'miniProjectsSubmitted',
            'mainProjectsList', 'mainProjectSubmissions',
            'demos', 'doubts',
            'progress', 'attendance'
        ));
    }
}
