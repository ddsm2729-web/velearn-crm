<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\WebinarController;
use App\Http\Controllers\SupportApiController;
use App\Http\Controllers\CourseAPIController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Blog Info
Route::get('blogs', [BlogController::class, 'blogsInfo']);
Route::get('blog/{id}', [BlogController::class, 'blogDetail']);

// Webinar Info
Route::get('webinars', [WebinarController::class, 'webinarInfo']);
Route::get('webinar/{id}', [WebinarController::class, 'webinarDetail']);
Route::get('/webinars/live', [WebinarController::class, 'live']);
Route::get('/webinars/upcoming', [WebinarController::class, 'upcoming']);
Route::get('/webinars/past', [WebinarController::class, 'past']);
Route::post('/webinar-register', [\App\Http\Controllers\WebinarRegisterController::class, 'enrollNow']);
Route::get('/my-webinars/{id}', [\App\Http\Controllers\WebinarRegisterController::class, 'myWebinars']);

// Support API
Route::post('/support/technical', [SupportApiController::class, 'storeTechnical'])->middleware('auth:sanctum');
Route::post('/support/non-technical', [SupportApiController::class, 'storeNonTechnical'])->middleware('auth:sanctum');
Route::get('/support/recent', [SupportApiController::class, 'getRecent'])->middleware('auth:sanctum');
Route::get('/support/booked-slots', [SupportApiController::class, 'getBookedSlots'])->middleware('auth:sanctum');

// After Call - API (MyOperator)
Route::match(['get', 'post'], '/webhook', [WebhookController::class, 'handleWebhook']);
Route::get('/call-recording/{id}', [WebhookController::class, 'getRecording']);

// Razorpay Webhook
Route::post('/razorpay/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook']);

// Zoom Webhook & Attendance API
Route::post('/zoom/webhook', [\App\Http\Controllers\ZoomAttendanceController::class, 'handleWebhook']);
Route::post('/zoom/attendance/process', [\App\Http\Controllers\ZoomAttendanceController::class, 'processAttendance']);
Route::get('/batch/{batchId}/attendance', [\App\Http\Controllers\ZoomAttendanceController::class, 'getBatchAttendance']);
Route::get('/student/{userId}/attendance', [\App\Http\Controllers\ZoomAttendanceController::class, 'getStudentAttendance']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::post('/update-logo', [AuthController::class, 'updateLogo']);
Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
Route::post('/test-notification', [AuthController::class, 'testNotification']);
Route::get('/profile', [AuthController::class, 'getProfile']);
Route::post('/profile/update', [AuthController::class, 'updateProfile']);
Route::get('/states', [AuthController::class, 'getStates']);

// Course Details
Route::get('free-course', [CourseAPIController::class, 'freeCourse']);
Route::get('paid-course', [CourseAPIController::class, 'paidCourse']);
Route::get('recorded-course', [CourseAPIController::class, 'recordedCourse']);
Route::get('course-categories', [CourseAPIController::class, 'categoryCourse']);
Route::get('free-course-detail/{id}', [CourseAPIController::class, 'freeCourseDetail']);
Route::get('paid-course-detail/{id}', [CourseAPIController::class, 'paidCourseDetail']);
Route::get('course-detail/{id}', [CourseAPIController::class, 'courseDetail']);
Route::get('combo-course', [CourseAPIController::class, 'comboCourse']);
Route::get('combo-course-detail/{id}', [CourseAPIController::class, 'comboCourseDetail']);
Route::get('live-course', [CourseAPIController::class, 'liveCourse']);
Route::get('live-course-detail/{id}', [CourseAPIController::class, 'liveCourseDetail']);
Route::get('course-videos/{id}', [CourseAPIController::class, 'courseVideos']);

// Video Quiz & Practice
Route::get('/video-quiz/{video_id}', [CourseAPIController::class, 'videoQuizApi']);
Route::post('/submit-quiz', [CourseAPIController::class, 'submitQuiz']);
Route::post('/update-video-progress', [CourseAPIController::class, 'updateVideoProgress']);
Route::get('/course-practices', [CourseAPIController::class, 'videoPracticeApi']);

// Course Enroll & Details
Route::post('/enroll-now', [CourseAPIController::class, 'enrollNow']);
Route::get('/my-courses/{id}', [CourseAPIController::class, 'myCourses']);
Route::get('/student-invoices/{id}', [CourseAPIController::class, 'studentInvoices']);
Route::get('/live-course-history/{id}', [CourseAPIController::class, 'liveCourseHistory']);
Route::get('/student-meetings/{id}', [CourseAPIController::class, 'studentMeetings']);
Route::get('/my-assignments/{id}', [CourseAPIController::class, 'myAssignments']);
Route::post('/assignments/{userId}/{id}/submit', [CourseAPIController::class, 'submitAssignment']);
Route::get('/my-main-projects/{id}', [CourseAPIController::class, 'myMainProjects']);
Route::get('/my-mini-projects/{id}', [CourseAPIController::class, 'myMiniProjects']);
Route::post('/main-projects/{userId}/{id}/submit', [CourseAPIController::class, 'submitMainProject']);
Route::post('/mini-projects/{userId}/{id}/submit', [CourseAPIController::class, 'submitMiniProject']);
Route::get('/live-dashboard/{id}', [CourseAPIController::class, 'liveDashboard']);
Route::get('/student-modules-history/{id}', [CourseAPIController::class, 'studentModulesHistory']);

// Student Video Notes
Route::get('/student-video-notes/{studentId}/{logId}', [\App\Http\Controllers\API\StudentVideoNoteController::class, 'index']);
Route::post('/student-video-notes', [\App\Http\Controllers\API\StudentVideoNoteController::class, 'store']);
Route::put('/student-video-notes/{id}', [\App\Http\Controllers\API\StudentVideoNoteController::class, 'update']);
Route::delete('/student-video-notes/{id}', [\App\Http\Controllers\API\StudentVideoNoteController::class, 'destroy']);

// Batch Topics Log
Route::get('/batch/{batchId}/pending-topics', [\App\Http\Controllers\BatchController::class, 'getPendingTopics']);
Route::post('/batch/{batchId}/log-topic', [\App\Http\Controllers\BatchController::class, 'logMeetingTopic']);

// Verify Course Payment
Route::post('/verify-course-payment', [CourseAPIController::class, 'verifyCoursePayment']);
Route::post('/course-payment-failed', [CourseAPIController::class, 'coursePaymentFailed']);


Route::get('placement/{userId}/eligibility', [App\Http\Controllers\API\PlacementAPIController::class, 'getEligibilityScores']);
Route::get('placement/{userId}/evaluation', [App\Http\Controllers\API\PlacementAPIController::class, 'getEvaluationDetails']);
Route::post('placement/{userId}/evaluation/submit', [App\Http\Controllers\API\PlacementAPIController::class, 'submitEvaluation']);
Route::post('placement/{userId}/evaluation/retest', [App\Http\Controllers\API\PlacementAPIController::class, 'retestEvaluation']);

Route::get('placement-classes', [\App\Http\Controllers\PlacementClassController::class, 'index']);

// Placement Profile 
Route::get('placement/{userId}/profile', [\App\Http\Controllers\PlacementProfileController::class, 'getProfile']);
Route::post('placement/{userId}/profile/resume', [\App\Http\Controllers\PlacementProfileController::class, 'uploadResume']);
Route::post('placement/{userId}/profile/portfolio', [\App\Http\Controllers\PlacementProfileController::class, 'updatePortfolio']);
Route::post('placement/{userId}/profile/github', [\App\Http\Controllers\PlacementProfileController::class, 'updateGithub']);
Route::post('placement/{userId}/profile/project-docs', [\App\Http\Controllers\PlacementProfileController::class, 'uploadProjectDocs']);

// Contact Info - API
Route::prefix('contacts')->group(function () {
    Route::post('/send-mail', [ContactController::class, 'sendMail']);
});
