<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RazorpayPaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlacementEvaluationController;


Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();

        $roleRoutes = [
            'SuperAdmin'            => 'admin.dashboard',
            'Tele Caller'           => 'tele-caller.dashboard',
            'Admission Officer'     => 'admission-officer.dashboard',
            'Support Executive'     => 'support.dashboard',
            'Technical Staff'       => 'tech.dashboard',
            'Technical Head'        => 'tech-head.dashboard',
            'Dues collector'        => 'dues.dashboard',
            'Digital Marketing'     => 'marketing.dashboard',
            'Student Coordinator'   => 'student-coordinator.dashboard',
            'Learning Coordinator'  => 'learning.dashboard',
            'Operation Executive'   => 'operation.dashboard',
            'Placement Coordinator' => 'placement-coordinator.dashboard',
            'Student'               => 'student.dashboard',
        ];

        foreach ($roleRoutes as $role => $routeName) {
            if ($user->hasRole($role)) {
                return redirect()->route($routeName);
            }
        }

        return response()->view('errors.not_eligible', [], 403);
    }

    return redirect()->route('login');
})->name('/');

Route::get('/dashboard', function () {
    return redirect()->route('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->middleware('dashboard.role:SuperAdmin')->name('admin.dashboard');
    Route::get('/admin/staff-monitoring', [App\Http\Controllers\StaffMonitoringController::class, 'index'])->middleware('dashboard.role:SuperAdmin')->name('admin.staff-monitoring.index');
    Route::get('/admin/impersonate/{id}', function ($id) {
        $impersonateUser = \App\Models\User::findOrFail($id);
        return view('auth.login', ['impersonateUser' => $impersonateUser]);
    })->middleware('dashboard.role:SuperAdmin')->name('admin.impersonate');
    Route::post('/admin/impersonate/{id}', function ($id, \Illuminate\Http\Request $request) {
        $impersonateUser = \App\Models\User::findOrFail($id);
        $originalId = \Illuminate\Support\Facades\Auth::id();
        \Illuminate\Support\Facades\Auth::login($impersonateUser);
        $request->session()->put('impersonated_by', $originalId);
        return redirect()->intended('/');
    })->middleware('dashboard.role:SuperAdmin')->name('admin.impersonate.login');
    Route::get('/admin/impersonate-leave', function (\Illuminate\Http\Request $request) {
        if ($request->session()->has('impersonated_by')) {
            $originalUser = \App\Models\User::find($request->session()->get('impersonated_by'));
            if ($originalUser) {
                \Illuminate\Support\Facades\Auth::login($originalUser);
                $request->session()->forget('impersonated_by');
            }
        }
        return redirect()->route('admin.dashboard');
    })->name('admin.impersonate.leave');
    Route::get('/tele-caller/dashboard', [DashboardController::class, 'teleCaller'])->middleware('dashboard.role:Tele Caller')->name('tele-caller.dashboard');
    Route::get('/admission-officer/dashboard', [DashboardController::class, 'admissionOfficer'])->middleware('dashboard.role:Admission Officer')->name('admission-officer.dashboard');
    Route::get('/support/dashboard', [DashboardController::class, 'supportExecutive'])->middleware('dashboard.role:Support Executive')->name('support.dashboard');
    // Route removed and moved to tech group
    Route::get('/tech-head/dashboard', [DashboardController::class, 'technicalHead'])->middleware('dashboard.role:Technical Head')->name('tech-head.dashboard');
    Route::get('/dues/dashboard', [DashboardController::class, 'duesCollector'])->middleware('dashboard.role:Dues collector')->name('dues.dashboard');
    Route::get('/marketing/dashboard', [DashboardController::class, 'digitalMarketing'])->middleware('dashboard.role:Digital Marketing')->name('marketing.dashboard');
    Route::get('/student-coordinator/dashboard', [App\Http\Controllers\StudentCoordinatorDashboardController::class, 'index'])->middleware('dashboard.role:Student Coordinator')->name('student-coordinator.dashboard');
    Route::get('/learning/dashboard', [DashboardController::class, 'learningCoordinator'])->middleware('dashboard.role:Learning Coordinator')->name('learning.dashboard');
    Route::get('/operation/dashboard', [DashboardController::class, 'operationExecutive'])->middleware('dashboard.role:Operation Executive')->name('operation.dashboard');
    Route::get('/placement-coordinator/dashboard', [DashboardController::class, 'placementCoordinator'])->middleware('dashboard.role:Placement Coordinator')->name('placement-coordinator.dashboard');
    Route::get('/student/dashboard', [DashboardController::class, 'student'])->middleware('dashboard.role:Student')->name('student.dashboard');
    Route::get('/eligible-students', [App\Http\Controllers\EligibleStudentController::class, 'index'])->name('eligible-students.index');
    Route::get('/eligible-students/batches', [App\Http\Controllers\EligibleStudentController::class, 'getBatches'])->name('eligible-students.batches');
    Route::get('/eligible-students/{student}', [App\Http\Controllers\EligibleStudentController::class, 'show'])->name('eligible-students.show');
    Route::post('/eligible-students/{student}/review', [App\Http\Controllers\EligibleStudentController::class, 'updateReview'])->name('eligible-students.review');
    Route::post('/schedule-interviews', [App\Http\Controllers\ScheduleInterviewController::class, 'store'])->name('schedule-interviews.store');
    Route::post('/schedule-interviews/{scheduleInterview}/outcome', [App\Http\Controllers\ScheduleInterviewController::class, 'updateOutcome'])->name('schedule-interviews.outcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Lead Tags Settings
    Route::prefix('settings')->group(function () {
        Route::resource('lead-tags', App\Http\Controllers\LeadTagController::class);
        Route::resource('lead-statuses', App\Http\Controllers\LeadStatusController::class);
        Route::resource('lead-sources', App\Http\Controllers\LeadSourceController::class);
    });

    // Leads
    Route::resource('leads', App\Http\Controllers\LeadController::class);
    Route::post('/leads/{lead}/activity', [App\Http\Controllers\LeadController::class, 'storeActivity'])->name('leads.activity.store');
    Route::put('/leads/activity/{activity}', [App\Http\Controllers\LeadController::class, 'updateActivity'])->name('leads.activity.update');
    Route::post('/leads/{lead}/priority', [App\Http\Controllers\LeadController::class, 'setPriority'])->name('leads.priority.update');
    Route::post('/leads/{lead}/attach-course', [App\Http\Controllers\LeadController::class, 'attachCourse'])->name('leads.attach-course');
    Route::delete('/leads/{lead}/detach-course/{course}', [App\Http\Controllers\LeadController::class, 'detachCourse'])->name('leads.detach-course');

    // Contacts
    Route::get('/contacts', [App\Http\Controllers\ContactController::class, 'index'])->name('contacts.info');
    Route::get('/api/contacts', [App\Http\Controllers\ContactController::class, 'getContacts']);
    Route::post('/api/contacts/create', [App\Http\Controllers\ContactController::class, 'store']);
    Route::patch('/api/contacts/{id}', [App\Http\Controllers\ContactController::class, 'update']);
    Route::delete('/api/contacts/{id}', [App\Http\Controllers\ContactController::class, 'destroy']);

    // Manage Roles & Permissions Routes
    Route::post('/roles/assign', [App\Http\Controllers\RoleManageController::class, 'assignPermissionsToRole'])->name('roles.assign');
    Route::resource('roles', App\Http\Controllers\RoleManageController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionManageController::class);

    // Staff Management
    Route::resource('staffs', App\Http\Controllers\StaffController::class)->name('staffs', '*');
    Route::post('/staffs/status', [App\Http\Controllers\StaffController::class, 'updateStaffStatus'])->name('staffs.status');

    // Staff Schedule
    Route::get('/staff-schedule', [App\Http\Controllers\StaffScheduleController::class, 'index'])->name('staff.schedule.index');
    Route::post('/staff-schedule/generate', [App\Http\Controllers\StaffScheduleController::class, 'generate'])->name('staff.schedule.generate');
    Route::post('/staff-schedule/update-status', [App\Http\Controllers\StaffScheduleController::class, 'updateStatus'])->name('staff.schedule.updateStatus');
    Route::get('/staff-schedule/fetch/{staff_id}', [App\Http\Controllers\StaffScheduleController::class, 'fetch'])->name('staff.schedule.fetch');
    Route::delete('/staff-schedule/{id}', [App\Http\Controllers\StaffScheduleController::class, 'destroy'])->name('staff.schedule.destroy');
    Route::delete('/staff-schedule/clear/{staff_id}', [App\Http\Controllers\StaffScheduleController::class, 'clearAll'])->name('staff.schedule.clearAll');

    // Call History
    Route::get('/calls/history', [App\Http\Controllers\CallLogController::class, 'index'])->name('calls.history');
    Route::get('/calls/history/staff/{staff}', [App\Http\Controllers\CallLogController::class, 'staffHistory'])->name('calls.staff_history');
    Route::get('/calls/history/unassigned', [App\Http\Controllers\CallLogController::class, 'unassignedHistory'])->name('calls.unassigned_history');
    Route::get('/calls/get-recording/{id}', [App\Http\Controllers\WebhookController::class, 'getRecording'])->name('calls.get-recording');

    // Course Management
    Route::resource('courses', App\Http\Controllers\CourseController::class)->name('courses', '*');
    Route::post('courses/update-status', [App\Http\Controllers\CourseController::class, 'updateCourseStatus'])->name('courses.update.status');
    Route::get('courses/upload-video/{id}', [App\Http\Controllers\CourseController::class, 'uploadVideo'])->name('courses.upload-video');
    Route::post('courses/{id}/upload-video', [App\Http\Controllers\CourseController::class, 'storeVideo'])->name('courses.video.store');
    Route::get('courses/{id}/videos', [App\Http\Controllers\CourseController::class, 'courseVideos'])->name('courses.videos.index');
    Route::get('course-videos/{id}/edit', [App\Http\Controllers\CourseController::class, 'editVideo'])->name('course.videos.edit');
    Route::put('course-videos/{id}', [App\Http\Controllers\CourseController::class, 'updateVideo']) ->name('course.videos.update');

    // Quiz
    Route::get('videos/{id}/quizzes', [App\Http\Controllers\CourseController::class, 'getVideoQuizzes'])->name('videos.quizzes.index');
    Route::post('videos/{id}/quizzes', [App\Http\Controllers\CourseController::class, 'storeVideoQuiz'])->name('videos.quizzes.store');
    Route::delete('quizzes/{id}', [App\Http\Controllers\CourseController::class, 'deleteQuiz'])->name('courses.quizzes.destroy');

    // Practice Course
    Route::get('practices', [App\Http\Controllers\CourseController::class, 'managePractices'])->name('practices.index');
    Route::post('practices', [App\Http\Controllers\CourseController::class, 'storeCoursePractice'])->name('practices.store');
    Route::put('practices/{id}', [App\Http\Controllers\CourseController::class, 'updateCoursePractice'])->name('practices.update');
    Route::delete('practices/{id}', [App\Http\Controllers\CourseController::class, 'deletePractice'])->name('practices.destroy');
    Route::post('practices/update-status', [App\Http\Controllers\CourseController::class, 'updatePracticeStatus'])->name('practices.update-status');

    // Blogs
    Route::resource('blogs-info', App\Http\Controllers\BlogController::class);
    Route::post('blogs/{blog}/update-status', [App\Http\Controllers\BlogController::class, 'updateStatus'])
        ->name('blogs.update.status');

    // Webinar
    Route::resource('webinars', App\Http\Controllers\WebinarController::class);
    Route::post('webinars/update-status', [App\Http\Controllers\WebinarController::class, 'updateWebinarStatus'])->name('webinars.update.status');

    // E-Book
    Route::get('/ebooks', [App\Http\Controllers\EbookController::class, 'index'])->name('ebooks.index');
    Route::post('/ebooks', [App\Http\Controllers\EbookController::class, 'store'])->name('ebooks.store');
    Route::post('/ebooks/{id}/update', [App\Http\Controllers\EbookController::class, 'update'])->name('ebooks.update');
    Route::delete('/ebooks/{id}', [App\Http\Controllers\EbookController::class, 'destroy'])->name('ebooks.destroy');
    Route::post('/ebooks/{id}/toggle-status', [App\Http\Controllers\EbookController::class, 'toggleStatus'])->name('ebooks.toggle-status');

    Route::prefix('courses-combo')->name('courses.combo.')->group(function () {
        Route::get('/', [App\Http\Controllers\CourseController::class, 'combo'])->name('index');
        Route::get('/create', [App\Http\Controllers\CourseController::class, 'addComboCourse'])->name('create');
        Route::post('/store', [App\Http\Controllers\CourseController::class, 'storeComboCourse'])->name('store');
        Route::get('/{course}/edit', [App\Http\Controllers\CourseController::class, 'editComboCourse'])->name('edit');
        Route::put('/{course}', [App\Http\Controllers\CourseController::class, 'updateComboCourse'])->name('update');
        Route::delete('/{course}', [App\Http\Controllers\CourseController::class, 'destroyComboCourse'])->name('destroy');
    });

    Route::prefix('courses-live')->name('courses.live.')->group(function () {
        Route::get('/', [App\Http\Controllers\CourseController::class, 'live'])->name('index');
        Route::get('/create', [App\Http\Controllers\CourseController::class, 'addLiveCourse'])->name('create');
        Route::post('/store', [App\Http\Controllers\CourseController::class, 'storeLiveCourse'])->name('store');
        Route::get('/{course}/edit', [App\Http\Controllers\CourseController::class, 'editLiveCourse'])->name('edit');
        Route::put('/{course}', [App\Http\Controllers\CourseController::class, 'updateLiveCourse'])->name('update');
    });

    // Batches
    Route::resource('batches', App\Http\Controllers\BatchController::class);
    Route::post('/batches/{batch}/topics', [App\Http\Controllers\BatchController::class, 'storeTopicLog'])->name('batches.topics.store');
    Route::put('/batches/topics/{id}', [App\Http\Controllers\BatchController::class, 'updateTopicLog'])->name('batches.topics.update');
    Route::post('/batches/{batch}/toggle-status', [App\Http\Controllers\BatchController::class, 'toggleStatus'])->name('batches.toggle-status');
    Route::get('/batch-allocation', [App\Http\Controllers\BatchController::class, 'allocation'])->name('batch-allocation.index');
    Route::get('/batch-allocation/search', [App\Http\Controllers\BatchController::class, 'searchAllocationStudents'])->name('batch-allocation.search');
    Route::post('/batch-allocation/allocate', [App\Http\Controllers\BatchController::class, 'allocateStudent'])->name('batch-allocation.allocate');

    // Assignments
    Route::resource('assignments', App\Http\Controllers\AssignmentController::class);
    Route::post('/assignments/{assignment}/update-status', [App\Http\Controllers\AssignmentController::class, 'updateStatus'])->name('assignments.update-status');
    Route::get('/assignments/submissions/{submission}/evaluate', [App\Http\Controllers\AssignmentEvaluationController::class, 'evaluate'])->name('assignments.evaluate');
    Route::post('/assignments/submissions/{submission}/evaluate', [App\Http\Controllers\AssignmentEvaluationController::class, 'storeEvaluation'])->name('assignments.storeEvaluation');

    // Mini Projects
    Route::resource('mini-projects', App\Http\Controllers\MiniProjectController::class)->names('mini_projects');
    Route::post('/mini-projects/{mini_project}/update-status', [App\Http\Controllers\MiniProjectController::class, 'updateStatus'])->name('mini_projects.update-status');
    Route::get('/mini-projects/submissions/{submission}/evaluate', [App\Http\Controllers\MiniProjectEvaluationController::class, 'evaluate'])->name('mini_projects.evaluate');
    Route::post('/mini-projects/submissions/{submission}/evaluate', [App\Http\Controllers\MiniProjectEvaluationController::class, 'storeEvaluation'])->name('mini_projects.storeEvaluation');

    // Main Projects
    Route::resource('main-projects', App\Http\Controllers\MainProjectController::class)->names('main_projects');
    Route::post('/main-projects/{main_project}/update-status', [App\Http\Controllers\MainProjectController::class, 'updateStatus'])->name('main_projects.update-status');
    Route::get('/main-projects/submissions/{submission}/evaluate', [App\Http\Controllers\MainProjectEvaluationController::class, 'evaluate'])->name('main_projects.evaluate');
    Route::post('/main-projects/submissions/{submission}/evaluate', [App\Http\Controllers\MainProjectEvaluationController::class, 'storeEvaluation'])->name('main_projects.storeEvaluation');

    // Tech Staff Routes
    Route::prefix('tech')->name('tech.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\TechDashboardController::class, 'index'])->middleware('dashboard.role:Technical Staff')->name('dashboard');
        
        Route::get('/batch-progress', [App\Http\Controllers\BatchProgressController::class, 'index'])->name('batch-progress.index');
        
        Route::resource('/batch-logs', App\Http\Controllers\TechBatchLogController::class);

        Route::get('/staff-monitoring', [App\Http\Controllers\StaffMonitoringController::class, 'index'])->name('staff-monitoring.index');
        Route::get('/staff-monitoring/{id}', [App\Http\Controllers\StaffMonitoringController::class, 'show'])->name('staff-monitoring.show');
        Route::post('/staff-monitoring/{id}/schedule', [App\Http\Controllers\StaffMonitoringController::class, 'scheduleMeeting'])->name('staff-monitoring.schedule');
        Route::get('/students', [App\Http\Controllers\StudentsManagementController::class, 'index'])->name('students.index');
        Route::get('/students/{id}', [App\Http\Controllers\StudentsManagementController::class, 'show'])->name('students.show');
        
        Route::get('/demo-sessions', [App\Http\Controllers\DemoSessionController::class, 'index'])->name('demo-sessions.index');
        Route::post('/demo-sessions/book', [App\Http\Controllers\SessionBookingController::class, 'store'])->name('demo-sessions.book');
        Route::post('/demo-sessions/{type}/{id}/status', [App\Http\Controllers\DemoSessionController::class, 'updateStatus'])->name('demo-sessions.update-status');
        
        Route::get('/doubt-sessions', [App\Http\Controllers\DoubtSessionController::class, 'index'])->name('doubt-sessions.index');
        Route::post('/doubt-sessions/book', [App\Http\Controllers\SessionBookingController::class, 'store'])->name('doubt-sessions.book');
        Route::post('/doubt-sessions/{type}/{id}/status', [App\Http\Controllers\DoubtSessionController::class, 'updateStatus'])->name('doubt-sessions.update-status');
    });

    // Batch System (Un-prefixed)
    Route::get('/batch-system', [App\Http\Controllers\BatchSystemController::class, 'index'])->name('batch-system.index');
    
    // Tech Head specific session routes
    Route::get('/tech-head/demo-sessions', [App\Http\Controllers\TechHeadDemoSessionController::class, 'index'])->name('tech-head.demo-sessions.index');
    Route::post('/tech-head/demo-sessions/book', [App\Http\Controllers\SessionBookingController::class, 'store'])->name('tech-head.demo-sessions.book');
    Route::get('/tech-head/doubt-sessions', [App\Http\Controllers\TechHeadDoubtSessionController::class, 'index'])->name('tech-head.doubt-sessions.index');
    Route::post('/tech-head/doubt-sessions/book', [App\Http\Controllers\SessionBookingController::class, 'store'])->name('tech-head.doubt-sessions.book');
    Route::post('/sessions/{id}/complete', [App\Http\Controllers\SessionBookingController::class, 'complete'])->name('sessions.complete');
    
    // Meetings
    Route::get('/meetings', [App\Http\Controllers\MeetingController::class, 'index'])->name('meetings.index');

    // Doubt Query Leads (CRM)
    Route::get('/doubt-query-leads', [App\Http\Controllers\SupportController::class, 'doubtQueryLeads'])->name('doubt-query-leads.index');
    Route::get('/doubt-query-followups', [App\Http\Controllers\SupportController::class, 'doubtQueryFollowups'])->name('doubt-query-followups.index');
    Route::post('/doubt-query-leads/schedule', [App\Http\Controllers\SupportController::class, 'scheduleDoubt'])->name('doubt-query-leads.schedule');
    Route::post('/doubt-query-leads/manual-store', [App\Http\Controllers\SupportController::class, 'manualStore'])->name('doubt-query-leads.manual-store');
    Route::post('/doubt-query-leads/update-status', [App\Http\Controllers\SupportController::class, 'updateTicketStatus'])->name('doubt-query-leads.update-status');
    Route::get('doubt-query-leads/profile/{id}', [App\Http\Controllers\SupportController::class, 'show'])->name('support.calls.profile');
    Route::post('/doubt-query-leads', [App\Http\Controllers\SupportController::class, 'assignLead'])->name('doubt-query-leads.assign-lead');

    // Doubt Allocation (CRM)
    Route::get('/doubt-allocation', [App\Http\Controllers\SupportController::class, 'doubtAllocation'])->name('doubt-allocation.index');
    Route::post('/doubt-allocation/allocate', [App\Http\Controllers\SupportController::class, 'allocateDoubt'])->name('doubt-allocation.allocate');
    Route::post('/doubt-allocation/deallocate', [App\Http\Controllers\SupportController::class, 'deallocateDoubt'])->name('doubt-allocation.deallocate');
    Route::post('/doubt-allocation/complete', [App\Http\Controllers\SupportController::class, 'completeDoubt'])->name('doubt-allocation.complete');

    // Certificate Dispatch
    Route::get('/certificate-dispatch', [App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/calls/inbound', [App\Http\Controllers\InboundCallController::class, 'index'])->name('calls.inbound');
    Route::get('/calls/outbound', [App\Http\Controllers\OutboundCallController::class, 'index'])->name('calls.outbound');
    Route::get('/followups', [App\Http\Controllers\FollowupController::class, 'index'])->name('followups.index');
    Route::get('/schedules', [App\Http\Controllers\ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/contacts/search', [App\Http\Controllers\ScheduleController::class, 'searchContacts'])->name('contacts.search');
    Route::post('/demo/book', [App\Http\Controllers\ScheduleController::class, 'bookDemo'])->name('demo.book');
    Route::get('/courses/{id}/batches', [App\Http\Controllers\ScheduleController::class, 'getBatchesByCourse'])->name('courses.batches');
    Route::get('/demo-tracking', [App\Http\Controllers\DemoTrackingController::class, 'index'])->name('demo.tracking');
    Route::post('/demo-tracking/{id}/status', [App\Http\Controllers\DemoTrackingController::class, 'updateStatus'])->name('demo.tracking.status');
    
    // Admission
    Route::post('/admission', [App\Http\Controllers\AdmissionController::class, 'store'])->name('admission.store');
    
    // Fee Collection
    Route::post('/fee', [App\Http\Controllers\PaymentController::class, 'store'])->name('fee.store');
    Route::post('/payments/{id}/generate-link', [App\Http\Controllers\PaymentController::class, 'generateLink'])->name('payments.generate-link');
    Route::post('/payments/{id}/send-link', [App\Http\Controllers\PaymentController::class, 'sendMail'])->name('payments.send-link');
    Route::post('/payments/create-order', [App\Http\Controllers\PaymentController::class, 'createOrder'])->name('payments.create-order');
    Route::post('/payments/verify', [App\Http\Controllers\PaymentController::class, 'verify'])->name('payments.verify');


    Route::get('/students', [App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
    Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/student-dues', [App\Http\Controllers\PaymentController::class, 'studentDues'])->name('student-dues.index');
    Route::get('/due-followups', [App\Http\Controllers\PaymentController::class, 'dueFollowups'])->name('due-followups.index');
    Route::get('/completed-payments', [App\Http\Controllers\PaymentController::class, 'completedPayments'])->name('completed-payments.index');
    Route::resource('company-database', App\Http\Controllers\CompanyController::class);
    Route::post('/company-database/{id}/activity', [App\Http\Controllers\CompanyController::class, 'storeActivity'])->name('company-database.activity.store');
    Route::resource('current-openings', App\Http\Controllers\CurrentOpeningController::class);
    Route::post('current-openings/{id}/send-mail', [App\Http\Controllers\CurrentOpeningController::class, 'sendMail'])->name('current-openings.send-mail');
    Route::post('current-openings/{id}/call-log', [App\Http\Controllers\CurrentOpeningController::class, 'storeCallLog'])->name('current-openings.call-log.store');
    Route::get('/interview-status', [App\Http\Controllers\InterviewStatusController::class, 'index'])->name('interview-status.index');
    Route::get('/applied-openings', [App\Http\Controllers\AppliedOpeningController::class, 'index'])->name('applied-openings.index');
    Route::get('/applied-openings/{id}', [App\Http\Controllers\AppliedOpeningController::class, 'show'])->name('applied-openings.show');
    Route::post('/applied-openings/forward-to-hr', [App\Http\Controllers\AppliedOpeningController::class, 'forwardToHr'])->name('applied-openings.forward-to-hr');
    Route::get('/placement-followups', [App\Http\Controllers\DashboardController::class, 'placementFollowups'])->name('placement-followups.index');

    Route::resource('placement-evaluations', PlacementEvaluationController::class);
    Route::resource('course-evaluation-questions', App\Http\Controllers\CourseEvaluationQuestionController::class);
    Route::resource('course-evaluation-options', App\Http\Controllers\CourseEvaluationOptionController::class);

    Route::resource('placement-classes', App\Http\Controllers\PlacementClassController::class);
    Route::resource('placement-class-questions', App\Http\Controllers\PlacementClassQuestionController::class);
    
    // Payment Success Page (Publicly accessible for Razorpay redirect)
    Route::get('/payment/success', [App\Http\Controllers\PaymentSuccessController::class, 'success'])->name('payment.success');

});


require __DIR__.'/auth.php';


    