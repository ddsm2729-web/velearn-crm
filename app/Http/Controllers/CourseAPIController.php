<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Batch;
use App\Models\BatchAttendance;
use App\Models\BatchSchedule;
use App\Models\BatchTopicLog;
use App\Models\CallLog;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCurriculum;
use App\Models\CourseEnrollment;
use App\Models\CoursePractice;
use App\Models\CourseQuiz;
use App\Models\CourseUser;
use App\Models\CourseVideo;
use App\Models\CourseVideoProgress;
use App\Models\Lead;
use App\Models\LeadSource;
use App\Models\MainProject;
use App\Models\MainProjectSubmission;
use App\Models\MiniProject;
use App\Models\MiniProjectSubmission;
use App\Models\Payment;
use App\Models\QuizResult;
use App\Models\SessionBooking;
use App\Models\SupportTicketAllocation;
use App\Models\User;
use App\Services\StudentConversionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;

class CourseAPIController extends Controller
{
    /* ==========================================================
     |  COURSE LISTINGS
     ========================================================== */
    public function freeCourse()
    {
        $course = Course::where('course_type', 'free')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function paidCourse()
    {
        $course = Course::where('course_type', 'paid')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function recordedCourse()
    {
        $course = Course::where('course_type', '!=', 'live')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function categoryCourse(Request $request)
    {
        $query = Course::where('status', 1)->orderBy('release_date', 'desc');

        if ($request->categories && $request->categories != '') {
            $query->where('categories', $request->categories);
        }

        $course = $query->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    /* ==========================================================
     |  COURSE DETAILS
     ========================================================== */
    public function freeCourseDetail($id, Request $request)
    {
        $userId = $request->user_id;

        $course = Course::with([
            'curricula:id,course_id,title',
            'curricula.descriptions:id,course_curriculum_id,description',
            'leans:id,course_id,title,description',
            'requirements:id,course_id,description',
            'benefits:id,course_id,description',
            'skills:id,course_id,name',
            'faqs:id,course_id,question,answer',
            'testimonials:id,course_id,name,content,rating,image',
        ])
            ->where('course_type', 'free')
            ->where('id', $id)
            ->when($userId, function ($query) use ($userId) {
                $query->withExists([
                    'courseUsers as is_bought' => function ($q) use ($userId) {
                        $q->where('auth_id', $userId)
                            ->whereIn('status', ['active', 'ongoing', 'completed']);
                    },
                ]);
            }, function ($query) {
                // guest user → always false
                $query->selectRaw('courses.*, false as is_bought');
            })
            ->first();

        if (! $course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function paidCourseDetail($id)
    {
        $course = Course::with([
            'curricula:id,course_id,title',
            'curricula.descriptions:id,course_curriculum_id,description',
            'leans:id,course_id,title,description',
            'requirements:id,course_id,description',
            'benefits:id,course_id,description',
            'skills:id,course_id,name',
            'faqs:id,course_id,question,answer',
            'testimonials:id,course_id,name,content,rating,image',
        ])
            ->where('course_type', 'paid')
            ->find($id);

        if (! $course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function courseDetail($id, Request $request)
    {
        $userId = $request->user_id;

        $course = Course::with([
            'curricula:id,course_id,title',
            'curricula.descriptions:id,course_curriculum_id,description',
            'leans:id,course_id,title,description',
            'requirements:id,course_id,description',
            'benefits:id,course_id,description',
            'skills:id,course_id,name',
            'faqs:id,course_id,question,answer',
            'testimonials:id,course_id,name,content,rating,image',
        ])
            ->where('id', $id)
            ->when($userId, function ($query) use ($userId) {
                $query->withExists([
                    'courseUsers as is_bought' => function ($q) use ($userId) {
                        $q->where('auth_id', $userId)
                            ->whereIn('status', ['active', 'ongoing', 'completed']);
                    },
                ]);
            }, function ($query) {
                // guest user → always false
                $query->selectRaw('courses.*, false as is_bought');
            })
            ->first();

        if (! $course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    /* ==========================================================
     |  COMBO COURSES
     ========================================================== */
    public function comboCourse()
    {
        $course = Course::where('course_type', 'combo')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function comboCourseDetail($id)
    {
        $course = Course::where('course_type', 'combo')
            ->with([
                /* ================= COMBO COURSE DETAILS ================= */
                'curricula:id,course_id,title',
                'curricula.descriptions:id,course_curriculum_id,description',
                'leans:id,course_id,title,description',
                'requirements:id,course_id,description',
                'benefits:id,course_id,description',
                'skills:id,course_id,name',
                'faqs:id,course_id,question,answer',
                'testimonials:id,course_id,name,content,rating,image',
                /* ================= INCLUDED COURSES ================= */
                'includedCourses' => function ($query) {
                    $query
                        ->with([
                            'curricula:id,course_id,title',
                            'curricula.descriptions:id,course_curriculum_id,description',
                            'leans:id,course_id,title,description',
                            'requirements:id,course_id,description',
                            'benefits:id,course_id,description',
                            'skills:id,course_id,name',
                            'faqs:id,course_id,question,answer',
                            'testimonials:id,course_id,name,content,rating,image',
                        ]);
                },
            ])
            ->find($id);

        if (! $course) {
            return response()->json([
                'status' => false,
                'message' => 'Combo course not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    /* ==========================================================
     |  LIVE COURSES
     ========================================================== */
    public function liveCourse()
    {
        $course = Course::where('course_type', 'live')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    public function liveCourseDetail($id)
    {
        $course = Course::with([
            'curricula:id,course_id,title',
            'curricula.descriptions:id,course_curriculum_id,description',
            'leans:id,course_id,title,description',
            'requirements:id,course_id,description',
            'benefits:id,course_id,description',
            'skills:id,course_id,name',
            'faqs:id,course_id,question,answer',
            'testimonials:id,course_id,name,content,rating,image',
        ])
            ->where('course_type', 'live')
            ->find($id);

        if (! $course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course,
        ]);
    }

    /* ==========================================================
     |  QUIZ API (FOR FRONTEND/MOBILE)
     ========================================================== */
    public function videoQuizApi($video_id)
    {
        try {
            $quizzes = CourseQuiz::where('course_video_id', $video_id)
                ->select(['id', 'course_video_id', 'question', 'option1', 'option2', 'option3', 'answer'])
                ->get();

            $userId = request()->query('user_id') ?: Auth::id();

            $hasSubmitted = false;
            $previousResult = null;
            $lastPosition = 0;

            if ($userId) {
                // Quiz status
                $previousResult = QuizResult::where('auth_id', $userId)
                    ->where('course_video_id', $video_id)
                    ->latest()
                    ->first();
                $hasSubmitted = ! empty($previousResult);

                // Video progress
                $progress = CourseVideoProgress::where('auth_id', $userId)
                    ->where('course_video_id', $video_id)
                    ->first();
                $lastPosition = $progress ? $progress->last_position : 0;
            }

            return response()->json([
                'status' => true,
                'message' => 'Video data fetched successfully',
                'data' => $quizzes,
                'has_submitted' => $hasSubmitted,
                'previous_result' => $previousResult,
                'last_position' => $lastPosition,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function submitQuiz(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_video_id' => 'required|exists:course_videos,id',
                'answers' => 'required|array',
            ]);

            if ($validator->fails()) {
                Log::error('CourseController Validation Failed', $validator->errors()->toArray());

                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $userId = Auth::id() ?: $request->user_id;  // Support both session and API

            if (! $userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            $video_id = $request->course_video_id;
            $submittedAnswers = $request->answers;  // Expecting [ { quiz_id: 1, selected: 'A' }, ... ]

            $quizzes = CourseQuiz::where('course_video_id', $video_id)->get();
            $totalQuestions = $quizzes->count();
            $correctAnswersCount = 0;
            $detailedAnswers = [];

            foreach ($quizzes as $quiz) {
                // Find matching answer in submitted ones
                $matchingAnswer = collect($submittedAnswers)->firstWhere('quiz_id', $quiz->id);
                $selectedIdx = $matchingAnswer ? $matchingAnswer['selected'] : null;

                // Resolve Selected Text
                $selectedText = $selectedIdx;
                if ($selectedIdx == '1') {
                    $selectedText = $quiz->option1;
                } elseif ($selectedIdx == '2') {
                    $selectedText = $quiz->option2;
                } elseif ($selectedIdx == '3') {
                    $selectedText = $quiz->option3;
                }

                // Resolve Correct Answer Text
                $correctText = $quiz->answer;
                if ($quiz->answer == '1') {
                    $correctText = $quiz->option1;
                } elseif ($quiz->answer == '2') {
                    $correctText = $quiz->option2;
                } elseif ($quiz->answer == '3') {
                    $correctText = $quiz->option3;
                }

                $isCorrect = (trim(strtolower($selectedText ?? '')) === trim(strtolower($correctText ?? '')));

                if ($isCorrect) {
                    $correctAnswersCount++;
                }

                $detailedAnswers[] = [
                    'quiz_id' => $quiz->id,
                    'question' => $quiz->question,
                    'selected' => $selectedText,
                    'correct_answer' => $correctText,
                    'is_correct' => $isCorrect,
                ];
            }

            $score = $totalQuestions > 0 ? ($correctAnswersCount / $totalQuestions) * 100 : 0;

            $result = QuizResult::create([
                'auth_id' => $userId,
                'course_video_id' => $video_id,
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswersCount,
                'score' => $score,
                'answers' => $detailedAnswers,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Quiz submitted successfully',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* ==========================================================
     |  VIDEO PROGRESS
     ========================================================== */
    public function updateVideoProgress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_video_id' => 'required|exists:course_videos,id',
                'last_position' => 'required|numeric',
                'user_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                Log::error('CourseController Validation Failed', $validator->errors()->toArray());

                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $userId = Auth::id() ?: $request->user_id;
            if (! $userId) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $progress = CourseVideoProgress::updateOrCreate(
                ['auth_id' => $userId, 'course_video_id' => $request->course_video_id],
                ['last_position' => $request->last_position]
            );

            if ($request->boolean('is_completed')) {
                $progress->is_completed = true;
                $progress->save();

                // Check if all videos for this course are completed
                $courseVideo = CourseVideo::find($request->course_video_id);
                if ($courseVideo) {
                    $courseId = $courseVideo->course_id;
                    $totalVideos = CourseVideo::where('course_id', $courseId)->count();
                    $completedVideos = CourseVideoProgress::where('auth_id', $userId)
                        ->whereHas('video', function ($query) use ($courseId) {
                            $query->where('course_id', $courseId);
                        })
                        ->where('is_completed', true)
                        ->count();

                    if ($totalVideos > 0 && $completedVideos >= $totalVideos) {
                        CourseEnrollment::where('auth_id', $userId)
                            ->where('course_id', $courseId)
                            ->update([
                                'status' => 'completed',
                                'completed_at' => now(),
                            ]);
                    }
                }
            }

            return response()->json(['status' => true, 'message' => 'Progress updated']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ==========================================================
     |  PRACTICE API (FOR FRONTEND/MOBILE)
     ========================================================== */
    public function videoPracticeApi(Request $request)
    {
        try {
            // 1. Get user login id from request (for app connection)
            $userId = $request->user_id;

            if (! $userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User ID is required',
                ], 400);
            }

            // Optional: Verify user exists in Auth table
            $userExists = \App\Models\Auth::where('id', $userId)->exists();
            if (! $userExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found',
                ], 404);
            }

            // 2. Handle single practice details if practice_id is provided
            $practiceId = $request->practice_id;

            if ($practiceId) {
                $practice = CoursePractice::where('id', $practiceId)
                    ->where('status', 1)
                    ->select(['id', 'main_title', 'question', 'input', 'actual_output', 'status'])
                    ->first();

                if (! $practice) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Practice not found or inactive',
                        'data' => null,
                    ], 404);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Practice details fetched successfully',
                    'data' => $practice,
                ]);
            }

            // 3. Display all active practice sets
            $practices = CoursePractice::where('status', 1)
                ->select(['id', 'main_title', 'question', 'input', 'actual_output', 'status'])
                ->get();

            if ($practices->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No practices found',
                    'data' => [],
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Practices list fetched successfully',
                'data' => $practices,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /* ==========================================================
     |  COURSE ENROLLMENT
     ========================================================== */

    /**
     * Enroll authenticated user in a course
     */
    public function enrollNow(Request $request)
    {
        try {
            $request->validate([
                'course_id' => 'required|exists:courses,id',
            ]);

            $courseId = $request->course_id;
            $userId = $request->user_id ?? $request->auth_id;

            if (! $userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User ID is required (user_id or auth_id)',
                ], 422);
            }

            return DB::transaction(function () use ($courseId, $userId, $request) {
                $user = User::findOrFail($userId);

                // Check duplicate enrollment
                $existingEnrollment = CourseUser::where('auth_id', $userId)
                    ->where('course_id', $courseId)
                    ->first();

                if ($existingEnrollment && in_array(strtolower($existingEnrollment->status), ['active', 'ongoing', 'completed'])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'You are already enrolled in this course.',
                    ], 409);
                }

                // Ensure a Contact record exists (sync from User if missing)
                $contact = Contact::where('email', $user->email)
                    ->orWhere('caller_number', $user->phonenumber)
                    ->first();

                if (! $contact) {
                    $contact = Contact::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'caller_number' => $user->phonenumber,
                        'source' => $request->lead_source ?? 'Website',
                    ]);
                }

                // Ensure a Lead record exists as well
                $lead = Lead::where('email', $user->email)
                    ->orWhere('phone', $user->phonenumber)
                    ->first();

                if (! $lead) {
                    $lead = Lead::create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phonenumber,
                        'lead_source_id' => LeadSource::where('name', $request->lead_source ?? 'Website')->value('id') ?? 3,
                    ]);
                }

                // Ensure a CallLog exists so it shows in Outbound Calls
                $existingCallLog = CallLog::where('lead_id', $lead->id)->first();
                if (! $existingCallLog) {
                    CallLog::create([
                        'lead_id' => $lead->id,
                        'contact_id' => $contact->id ?? null,
                        'caller_number' => $user->phonenumber,
                        'direction' => 'outbound',
                        'note' => 'New enrollment lead from website',
                        'lead_tag' => 'New Lead',
                    ]);
                }

                // Fetch course for type checking
                $course = Course::findOrFail($courseId);

                // Always attach course to lead so it appears in Assigned Courses in the CRM
                $lead->courses()->syncWithoutDetaching([$courseId]);

                // Determine initial status
                $status = 'inactive';
                if ($course->course_type === 'free') {
                    $status = 'ongoing';
                }

                // If paid recorded/combo course, we create a pending admission and return Razorpay order (live courses are handled below)
                if ($course->course_type !== 'free' && $course->course_type !== 'live' && $course->buy_price > 0) {
                    if ($existingEnrollment) {
                        $enrollment = $existingEnrollment;
                        $enrollment->update(['status' => 'pending']);
                    } else {
                        $enrollment = CourseUser::create([
                            'auth_id' => $userId,
                            'course_id' => $courseId,
                            'status' => 'pending',
                        ]);
                    }

                    // Check for existing pending admission
                    $admission = Admission::where('lead_id', $lead->id ?? null)
                        ->where('course_id', $courseId)
                        ->first();

                    if (! $admission) {
                        $admission = Admission::create([
                            'contact_id' => $contact->id ?? null,
                            'lead_id' => $lead->id ?? null,
                            'course_id' => $courseId,
                            'course_fees' => $course->buy_price,
                            'payment_mode' => 'online',
                            'status' => 'pending_fee',
                        ]);
                    } else {
                        $admission->update([
                            'course_fees' => $course->buy_price,
                            'status' => 'pending_fee',
                        ]);
                    }

                    $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
                    $orderData = [
                        'receipt' => 'rcptid_'.$admission->id,
                        'amount' => $course->buy_price * 100, // Amount in paise
                        'currency' => 'INR',
                        'payment_capture' => 1, // auto capture
                    ];
                    $razorpayOrder = $api->order->create($orderData);

                    // Create or update pending payment
                    $payment = Payment::where('admission_id', $admission->id)
                        ->whereIn('status', ['Pending', 'Failed', 'pending', 'failed'])
                        ->first();

                    if ($payment) {
                        $payment->update([
                            'amount' => $course->buy_price,
                            'payment_date' => now(),
                            'status' => 'Pending',
                            'razorpay_order_id' => $razorpayOrder['id'],
                            'notes' => json_encode(['enrollment_id' => $enrollment->id]),
                        ]);
                    } else {
                        $payment = Payment::create([
                            'admission_id' => $admission->id,
                            'amount' => $course->buy_price,
                            'payment_date' => now(),
                            'status' => 'Pending',
                            'razorpay_order_id' => $razorpayOrder['id'],
                            'payment_method' => 'online',
                            'notes' => json_encode(['enrollment_id' => $enrollment->id]),
                        ]);
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Payment required',
                        'payment_required' => true,
                        'razorpay' => [
                            'order_id' => $razorpayOrder['id'],
                            'amount' => $course->buy_price * 100,
                            'key' => config('services.razorpay.key'),
                        ],
                        'data' => [
                            'enrollment_id' => $enrollment->id,
                            'admission_id' => $admission->id,
                            'user' => $user,
                        ],
                    ], 200);
                }

                // For free and live courses (or if buy_price is 0 for some reason)
                if ($existingEnrollment) {
                    $enrollment = $existingEnrollment;
                    $enrollment->update(['status' => $status]);
                } else {
                    $enrollment = CourseUser::create([
                        'auth_id' => $userId,
                        'course_id' => $courseId,
                        'status' => $status,
                    ]);
                }

                // For live courses, create an Admission record and a CallLog entry
                if ($course->course_type === 'live') {
                    $lastCallLog = CallLog::where('contact_id', $contact->id ?? null)
                        ->whereNotNull('assigned_staff_id')
                        ->orderByDesc('created_at')
                        ->first();

                    CallLog::create([
                        'contact_id' => $contact->id,
                        'lead_id' => $lead->id,
                        'caller_number' => $contact->caller_number,
                        'direction' => 'Enrollment',
                        'lead_tag' => 'New',
                        'department' => 'Admissions',
                        'status' => 'New',
                        'note' => $request->notes ?? 'Enrolled from website',
                        'call_start' => null,
                        'assigned_staff_id' => $lastCallLog ? $lastCallLog->assigned_staff_id : null,
                        'staff_id' => $lastCallLog ? $lastCallLog->staff_id : null,
                    ]);

                    $admission = Admission::where('lead_id', $lead->id)
                        ->where('course_id', $courseId)
                        ->first();

                    if (! $admission) {
                        Admission::create([
                            'contact_id' => $contact->id,
                            'lead_id' => $lead->id,
                            'course_id' => $courseId,
                            'course_fees' => $course->buy_price ?? 0,
                            'payment_mode' => 'online',
                            'status' => $status === 'ongoing' ? 'ongoing' : 'pending',
                        ]);
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Successfully enrolled in the course',
                    'data' => [
                        'enrollment' => $enrollment,
                        'user' => $user,
                    ],
                ], 201);
            });

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Course Enrollment Error: '.$e->getMessage(), [
                'user_id' => $request->user_id ?? $request->auth_id ?? null,
                'course_id' => $request->course_id ?? null,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function verifyCoursePayment(Request $request)
    {
        try {
            $request->validate([
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
                'enrollment_id' => 'required',
                'admission_id' => 'required',
            ]);

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $api->utility->verifyPaymentSignature($attributes);

            $admission = Admission::find($request->admission_id);
            $enrollment = CourseUser::find($request->enrollment_id);

            if (! $admission || ! $enrollment) {
                return response()->json(['status' => false, 'message' => 'Record not found'], 404);
            }

            // Find pending Payment
            $payment = Payment::where('razorpay_order_id', $request->razorpay_order_id)->first();

            if (! $payment) {
                // Fallback creation just in case
                $payment = Payment::create([
                    'admission_id' => $admission->id,
                    'amount' => $admission->course_fees,
                    'payment_date' => now(),
                    'status' => 'Paid',
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'payment_method' => 'online',
                    'notes' => json_encode(['enrollment_id' => $enrollment->id]),
                ]);
            } else {
                $payment->update([
                    'status' => 'Paid',
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                ]);
            }

            // Update admission and enrollment
            $admission->update(['status' => 'ongoing']);
            $enrollment->update(['status' => 'active']);

            // Convert Lead to Student (assigns role, etc.)
            StudentConversionService::convertLeadToStudent($admission);

            return response()->json([
                'status' => true,
                'message' => 'Payment verified successfully and enrolled',
                'data' => [
                    'enrollment' => $enrollment,
                    'payment' => $payment,
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Verification Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Payment verification failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle failed or cancelled payment for a course
     */
    public function coursePaymentFailed(Request $request)
    {
        try {
            $enrollmentId = $request->enrollment_id;
            $admissionId = $request->admission_id;
            $orderId = $request->razorpay_order_id;
            $reason = $request->reason ?? 'Payment failed or cancelled';

            if ($orderId) {
                $payment = Payment::where('razorpay_order_id', $orderId)->first();
                if ($payment && strtolower($payment->status) !== 'paid') {
                    $notes = json_decode($payment->notes, true) ?: [];
                    $notes['failure_reason'] = $reason;
                    if ($request->error_code) {
                        $notes['error_code'] = $request->error_code;
                    }
                    $payment->update([
                        'status' => 'Failed',
                        'notes' => json_encode($notes),
                    ]);
                }
            }

            if ($enrollmentId) {
                $enrollment = CourseUser::find($enrollmentId);
                if ($enrollment && $enrollment->status === 'pending') {
                    $enrollment->update(['status' => 'failed']);
                }
            }

            if ($admissionId) {
                $admission = Admission::find($admissionId);
                if ($admission && ($admission->status === 'pending_fee' || $admission->status === 'pending')) {
                    $admission->update(['status' => 'failed']);
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Payment failure logged successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Course Payment Failed Error: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Error logging payment failure',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all courses enrolled by a user
     */
    public function myCourses(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            // Load enrollments with their course, excluding live courses
            $enrollments = CourseUser::with('course')
                ->where('auth_id', $id)
                ->whereIn('status', ['active', 'ongoing', 'completed', 'inactive'])
                ->whereHas('course', function ($q) {
                    $q->where('course_type', '!=', 'live');
                })
                ->get();

            $all = [];
            $completed = [];
            $ongoing = [];
            $inactive = [];

            foreach ($enrollments as $enrollment) {
                $course = $enrollment->course;
                if (! $course) {
                    continue;
                }

                // Normalize thumbnail path
                $rawImage = $course->image ?? '';
                $thumbnail = ltrim(str_replace('/../public/', '', $rawImage), '/');

                $totalVideos = CourseVideo::where('course_id', $course->id)->count();
                $completedVideos = CourseVideoProgress::where('auth_id', $id)
                    ->whereHas('video', function ($query) use ($course) {
                        $query->where('course_id', $course->id);
                    })
                    ->where('is_completed', true)
                    ->count();

                // Self-heal course completion
                if ($totalVideos > 0 && $completedVideos >= $totalVideos && $enrollment->status !== 'completed') {
                    $enrollment->status = 'completed';
                    if (! $enrollment->completed_at) {
                        $enrollment->completed_at = now();
                    }
                    $enrollment->save();
                }

                $item = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'thumbnail' => $thumbnail ?: null,
                    'price' => $course->buy_price,
                    'course_type' => $course->course_type,
                    'enrollment' => [
                        'status' => $enrollment->status,
                        'enrolled_at' => $enrollment->created_at
                            ? Carbon::parse($enrollment->created_at)->format('d M, Y')
                            : 'N/A',
                        'completed_at' => $enrollment->completed_at
                            ? Carbon::parse($enrollment->completed_at)->format('d M, Y')
                            : null,
                        'completed_videos' => $completedVideos,
                        'total_videos' => $totalVideos,
                    ],
                ];

                if ($enrollment->status === 'completed') {
                    $all[] = $item;
                    $completed[] = $item;
                } elseif ($enrollment->status === 'ongoing' || $enrollment->status === 'active') {
                    $all[] = $item;
                    $ongoing[] = $item;
                } elseif ($enrollment->status === 'inactive') {
                    $all[] = $item;
                    $inactive[] = $item;
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Courses retrieved successfully',
                'data' => [
                    'all' => $all,
                    'ongoing' => $ongoing,
                    'completed' => $completed,
                    'inactive' => $inactive,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching courses',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all assignments for a student
     */
    public function myAssignments(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
            }

            // A student is converted if they have an active enrollment
            $enrollments = CourseUser::where('auth_id', $id)
                ->where('status', 'active')
                ->get();

            if ($enrollments->isEmpty()) {
                // If not converted or no active enrollments, return empty as per user request
                return response()->json(['status' => 'success', 'data' => []]);
            }

            $courseIds = $enrollments->pluck('course_id');
            $batchIds = $enrollments->pluck('batch_id')->filter();

            $assignments = Assignment::with(['submissions' => function ($q) use ($id) {
                $q->where('user_id', $id);
            }])
                ->where('status', 'active') // Only display active assignments
                ->whereIn('course_id', $courseIds)
                ->where(function ($query) use ($batchIds) {
                    $query->whereNull('batch_id')
                        ->orWhereIn('batch_id', $batchIds);
                })
                ->latest()
                ->get();

            $data = $assignments->map(function ($assignment) {
                $assignment->submission = $assignment->submissions->first();
                unset($assignment->submissions);

                return $assignment;
            });

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Submit an assignment
     */
    public function submitAssignment(Request $request, $userId, $id)
    {
        try {
            if (! $request->hasFile('file')) {
                return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
            }

            $assignment = Assignment::find($id);
            if (! $assignment) {
                return response()->json(['status' => 'error', 'message' => 'Assignment not found'], 404);
            }

            $submission = AssignmentSubmission::where('assignment_id', $id)
                ->where('user_id', $userId)
                ->first();

            if ($submission && $submission->reupload_approved !== true && $submission->attempts >= 3) {
                return response()->json(['status' => 'error', 'message' => 'Attempt limit reached'], 400);
            }

            $safeTitle = Str::slug($assignment->title);
            $dateTime = now()->format('Y_m_d_H_i_s');
            $docName = $safeTitle.'_'.$dateTime.'_'.uniqid().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/assignments'), $docName);

            $dueDate = $assignment->due_date ? Carbon::parse($assignment->due_date)->endOfDay() : null;
            $status = ($dueDate && now()->greaterThan($dueDate)) ? 'late' : 'pending';

            if ($submission) {
                $history = is_array($submission->attempts_history) ? $submission->attempts_history : [];
                $history[] = [
                    'attempt' => $submission->attempts,
                    'score' => $submission->score,
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback,
                    'file_path' => $submission->file_path,
                    'evaluated_by' => $submission->evaluated_by,
                    'evaluated_at' => $submission->updated_at->toDateTimeString(),
                    'breakdowns' => $submission->scoreBreakdowns->map(function ($b) {
                        return [
                            'criterion' => $b->criterion,
                            'max_score' => $b->max_score,
                            'obtained_score' => $b->obtained_score,
                        ];
                    })->toArray(),
                ];

                $submission->update([
                    'attempts_history' => $history,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => $submission->attempts + 1,
                    'reupload_approved' => false,
                    'score' => null,
                    'grade' => null,
                    'feedback' => null,
                    'evaluated_by' => null,
                ]);
                $submission->scoreBreakdowns()->delete();
            } else {
                AssignmentSubmission::create([
                    'assignment_id' => $id,
                    'user_id' => $userId,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => 1,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Assignment submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function myMainProjects(Request $request, $id)
    {
        try {
            $enrollments = CourseUser::where('auth_id', $id)->where('status', 'active')->get();
            if ($enrollments->isEmpty()) {
                return response()->json(['status' => 'success', 'data' => []]);
            }

            $courseIds = $enrollments->pluck('course_id');
            $batchIds = $enrollments->pluck('batch_id')->filter();

            $projects = MainProject::with(['submissions' => function ($q) use ($id) {
                $q->where('user_id', $id);
            }])
                ->where('status', 'active')
                ->whereIn('course_id', $courseIds)
                ->where(function ($query) use ($batchIds) {
                    $query->whereNull('batch_id')->orWhereIn('batch_id', $batchIds);
                })
                ->latest()
                ->get();

            $data = $projects->map(function ($project) {
                $subs = [];
                foreach ($project->submissions as $sub) {
                    $subs[(string) $sub->review_number] = $sub;
                }
                unset($project->submissions);
                $array = $project->toArray();
                $array['submissions'] = $subs;

                return $array;
            });

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function myMiniProjects(Request $request, $id)
    {
        try {
            $enrollments = CourseUser::where('auth_id', $id)->where('status', 'active')->get();
            if ($enrollments->isEmpty()) {
                return response()->json(['status' => 'success', 'data' => []]);
            }

            $courseIds = $enrollments->pluck('course_id');
            $batchIds = $enrollments->pluck('batch_id')->filter();

            $projects = MiniProject::with(['submissions' => function ($q) use ($id) {
                $q->where('user_id', $id);
            }])
                ->where('status', 'active')
                ->whereIn('course_id', $courseIds)
                ->where(function ($query) use ($batchIds) {
                    $query->whereNull('batch_id')->orWhereIn('batch_id', $batchIds);
                })
                ->latest()
                ->get();

            $data = $projects->map(function ($project) {
                $project->submission = $project->submissions->first();
                unset($project->submissions);

                return $project;
            });

            return response()->json(['status' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function submitMainProject(Request $request, $userId, $id)
    {
        try {
            if (! $request->hasFile('file')) {
                return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
            }

            $project = MainProject::find($id);
            if (! $project) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }

            $reviewNumber = $request->input('review_number');
            if (! $reviewNumber) {
                return response()->json(['status' => 'error', 'message' => 'Review number is required'], 400);
            }

            $submission = MainProjectSubmission::where('main_project_id', $id)
                ->where('user_id', $userId)
                ->where('review_number', $reviewNumber)
                ->first();

            if ($submission && $submission->reupload_approved !== true && $submission->attempts >= 3) {
                return response()->json(['status' => 'error', 'message' => 'Attempt limit reached'], 400);
            }

            $docName = Str::slug($project->title).'_'.now()->format('Y_m_d_H_i_s').'_'.uniqid().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/main_projects'), $docName);

            $dueDate = $project->due_date ? Carbon::parse($project->due_date)->endOfDay() : null;
            $status = ($dueDate && now()->greaterThan($dueDate)) ? 'late' : 'pending';

            if ($submission) {
                $history = json_decode($submission->attempts_history, true) ?: [];
                $history[] = [
                    'attempt' => $submission->attempts,
                    'score' => $submission->score,
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback,
                    'file_path' => $submission->file_path,
                    'evaluated_by' => $submission->evaluated_by,
                    'evaluated_at' => $submission->updated_at->toDateTimeString(),
                    'breakdowns' => $submission->scoreBreakdowns->map(function ($b) {
                        return [
                            'criterion' => $b->criterion,
                            'max_score' => $b->max_score,
                            'obtained_score' => $b->obtained_score,
                        ];
                    })->toArray(),
                ];

                $submission->update([
                    'attempts_history' => $history,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => $submission->attempts + 1,
                    'reupload_approved' => false,
                    'score' => null,
                    'grade' => null,
                    'feedback' => null,
                    'evaluated_by' => null,
                ]);
                $submission->scoreBreakdowns()->delete();
            } else {
                MainProjectSubmission::create([
                    'main_project_id' => $id,
                    'user_id' => $userId,
                    'review_number' => $reviewNumber,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => 1,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Main Project submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function submitMiniProject(Request $request, $userId, $id)
    {
        try {
            if (! $request->hasFile('file')) {
                return response()->json(['status' => 'error', 'message' => 'No file uploaded'], 400);
            }

            $project = MiniProject::find($id);
            if (! $project) {
                return response()->json(['status' => 'error', 'message' => 'Project not found'], 404);
            }

            $submission = MiniProjectSubmission::where('mini_project_id', $id)->where('user_id', $userId)->first();
            if ($submission && $submission->reupload_approved !== true && $submission->attempts >= 3) {
                return response()->json(['status' => 'error', 'message' => 'Attempt limit reached'], 400);
            }

            $docName = Str::slug($project->title).'_'.now()->format('Y_m_d_H_i_s').'_'.uniqid().'.'.$request->file->extension();
            $request->file->move(public_path('uploads/mini_projects'), $docName);

            $dueDate = $project->due_date ? Carbon::parse($project->due_date)->endOfDay() : null;
            $status = ($dueDate && now()->greaterThan($dueDate)) ? 'late' : 'pending';

            if ($submission) {
                $history = json_decode($submission->attempts_history, true) ?: [];
                $history[] = [
                    'attempt' => $submission->attempts,
                    'score' => $submission->score,
                    'grade' => $submission->grade,
                    'feedback' => $submission->feedback,
                    'file_path' => $submission->file_path,
                    'evaluated_by' => $submission->evaluated_by,
                    'evaluated_at' => $submission->updated_at->toDateTimeString(),
                    'breakdowns' => $submission->scoreBreakdowns->map(function ($b) {
                        return [
                            'criterion' => $b->criterion,
                            'max_score' => $b->max_score,
                            'obtained_score' => $b->obtained_score,
                        ];
                    })->toArray(),
                ];

                $submission->update([
                    'attempts_history' => $history,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => $submission->attempts + 1,
                    'reupload_approved' => false,
                    'score' => null,
                    'grade' => null,
                    'feedback' => null,
                    'evaluated_by' => null,
                ]);
                $submission->scoreBreakdowns()->delete();
            } else {
                MiniProjectSubmission::create([
                    'mini_project_id' => $id,
                    'user_id' => $userId,
                    'file_path' => $docName,
                    'status' => $status,
                    'attempts' => 1,
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Mini Project submitted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function liveCourseHistory(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $enrollments = CourseUser::with(['course', 'batch', 'batch.instructor'])
                ->where('auth_id', $id)
                ->whereHas('course', function ($q) {
                    $q->where('course_type', 'live');
                })
                ->get();

            $data = [];
            foreach ($enrollments as $enrollment) {
                $course = $enrollment->course;
                $batch = $enrollment->batch;

                // Self-healing: if the batch is missing on the frontend record, try to find it in the CRM Admission record
                if (! $batch) {
                    $contact = Contact::where('caller_number', $user->phonenumber)
                        ->orWhere('email', $user->email)
                        ->first();

                    if ($contact) {
                        $admission = Admission::where('contact_id', $contact->id)
                            ->where('course_id', $course->id)
                            ->whereNotNull('batch_id')
                            ->first();

                        if ($admission) {
                            $batch = Batch::with('instructor')->find($admission->batch_id);
                            if ($batch) {
                                // Fix it in the database for the future
                                $enrollment->update([
                                    'batch_id' => $batch->id,
                                    'status' => 'active',
                                ]);
                                // Update current memory object for the response
                                $enrollment->status = 'active';
                            }
                        }
                    }
                }

                $data[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'thumbnail' => $course->image ? ltrim(str_replace('/../public/', '', $course->image), '/') : null,
                    'status' => $enrollment->status === 'active' ? 1 : 0,
                    'batch' => $batch ? [
                        'instructor' => $batch->instructor ? $batch->instructor->name : 'TBA',
                        'batch_time' => $batch->batch_from_time && $batch->batch_to_time
                                            ? date('h:i A', strtotime($batch->batch_from_time)).' - '.date('h:i A', strtotime($batch->batch_to_time))
                                            : 'Scheduled Sessions',
                        'name' => $batch->batch_name ?: 'Standard Batch',
                        'end_date' => $batch->end_date,
                        'batch_status' => $batch->batch_status,
                    ] : [
                        'instructor' => 'TBA',
                        'batch_time' => 'Scheduled Sessions',
                        'name' => 'Standard Batch',
                        'end_date' => Carbon::now()->addMonths(1)->format('Y-m-d'),
                    ],
                ];
            }

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function studentModulesHistory(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found']);
            }

            // Get active live enrollments
            $enrollments = CourseUser::with(['course.curricula.descriptions', 'batch.instructor', 'batch.schedules'])
                ->where('auth_id', $id)
                ->whereHas('course', function ($q) {
                    $q->where('course_type', 'live');
                })
                ->where('status', 'active')
                ->get();

            if ($enrollments->isEmpty()) {
                return response()->json(['status' => true, 'todayClasses' => [], 'modules' => []]);
            }

            $todayClasses = [];
            $modules = [];
            $today = now()->format('Y-m-d');

            foreach ($enrollments as $enrollment) {
                $course = $enrollment->course;
                $batch = $enrollment->batch;

                // Get all schedules for today for this batch
                if ($batch) {
                    $currentSchedules = BatchSchedule::where('batch_id', $batch->id)
                        ->where('date', $today)
                        ->orderBy('start_time')
                        ->get();

                    foreach ($currentSchedules as $schedule) {
                        $startDateTime = Carbon::parse($schedule->date.' '.$schedule->start_time);
                        // Activate button 10 minutes before start time
                        $isActive = now()->greaterThanOrEqualTo($startDateTime->copy()->subMinutes(10)) && now()->lessThan($startDateTime->copy()->addHours(3));

                        $todayClasses[] = [
                            'id' => $schedule->id,
                            'title' => $course->title.' - Live Session',
                            'course_title' => $course->title,
                            'mentor' => $batch->instructor ? $batch->instructor->name : 'Mentor',
                            'started' => 'Scheduled at '.date('h:i A', strtotime($schedule->start_time)),
                            'start_time' => $schedule->start_time,
                            'meet_link' => $schedule->meeting_link ?? $schedule->zoom_meeting_id ?? '#',
                            'is_active' => $isActive,
                        ];
                    }
                }

                if ($course && $course->curricula) {
                    $index = 1;
                    foreach ($course->curricula as $curriculum) {
                        $topicLogs = collect();
                        if ($batch) {
                            $topicLogs = BatchTopicLog::with('schedule')
                                ->where('batch_id', $batch->id)
                                ->where('course_curriculum_id', $curriculum->id)
                                ->get();
                        }

                        $isCompleted = false;
                        $content = [];
                        $loggedDescriptions = [];

                        if ($topicLogs->count() > 0) {
                            foreach ($topicLogs as $log) {
                                if ($log->is_completed || strtolower($log->status) === 'completed') {
                                    $isCompleted = true;
                                }
                                $content[] = [
                                    'log_id' => $log->id,
                                    'title' => $log->description ?: $curriculum->title.' Session',
                                    'date' => $log->created_at->format('M d'),
                                    'type' => 'watch',
                                    'status' => $log->status ?: ($log->is_completed ? 'Completed' : 'Pending'),
                                    'video' => $log->video ? asset($log->video) : null,
                                    'note' => $log->note,
                                ];
                                if ($log->description) {
                                    $loggedDescriptions[] = $log->description;
                                }
                            }
                        }

                        if (isset($curriculum->descriptions)) {
                            foreach ($curriculum->descriptions as $desc) {
                                if (! in_array($desc->description, $loggedDescriptions)) {
                                    $content[] = [
                                        'title' => $desc->description,
                                        'date' => 'TBA',
                                        'type' => 'upcoming',
                                        'status' => 'Pending',
                                    ];
                                }
                            }
                        }

                        $totalExpected = isset($curriculum->descriptions) ? $curriculum->descriptions->count() : 0;
                        $totalLogged = $topicLogs->count();
                        $isAllCompleted = ($totalExpected > 0 && $totalLogged >= $totalExpected);

                        $status = $isAllCompleted ? 'Done' : ($totalLogged > 0 ? 'Active' : 'Upcoming');

                        $modules[] = [
                            'id' => 'M'.$index,
                            'title' => $curriculum->title,
                            'status' => $status,
                            'sessions' => count($content),
                            'content' => $content,
                        ];
                        $index++;
                    }
                }
            }

            return response()->json([
                'status' => true,
                'todayClasses' => $todayClasses,
                'modules' => $modules,
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    public function studentInvoices(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $leadIds = Lead::where('email', $user->email)
                ->orWhere('phone', $user->phonenumber)
                ->pluck('id');

            $contactIds = Contact::where('email', $user->email)
                ->orWhere('caller_number', $user->phonenumber)
                ->pluck('id');

            $admissions = Admission::where(function ($q) use ($leadIds, $contactIds) {
                if ($leadIds->isNotEmpty()) {
                    $q->orWhereIn('lead_id', $leadIds);
                }
                if ($contactIds->isNotEmpty()) {
                    $q->orWhereIn('contact_id', $contactIds);
                }
            })
                ->with(['payments', 'course'])
                ->get();

            $data = [];
            $processedCourses = [];

            foreach ($admissions as $admission) {
                if (! $admission->course) {
                    continue;
                }
                $course = $admission->course;

                // Skip free courses
                if ($course->mrp_price <= 0 && $course->buy_price <= 0) {
                    continue;
                }

                $processedCourses[] = $course->id;

                foreach ($admission->payments as $payment) {
                    if (strtolower($payment->status) !== 'paid') {
                        continue;
                    }

                    $data[] = [
                        'id' => 'p_'.$payment->id,
                        'course' => $course->title,
                        'invoice_number' => 'INV-'.str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                        'date' => Carbon::parse($payment->payment_date ?? $payment->created_at)->format('d M, Y'),
                        'course_amount' => $admission->course_fees > 0 ? $admission->course_fees : $course->mrp_price,
                        'paid_amount' => $payment->amount,
                        'status' => 'Paid',
                        'type' => $course->course_type === 'live' ? 'live' : 'recorded',
                    ];
                }
            }

            $enrollments = CourseUser::with('course')
                ->where('auth_id', $id)
                ->get();

            foreach ($enrollments as $enrollment) {
                if (! $enrollment->course) {
                    continue;
                }
                $course = $enrollment->course;

                // Skip free courses
                if ($course->mrp_price <= 0 && $course->buy_price <= 0) {
                    continue;
                }

                if (in_array($course->id, $processedCourses)) {
                    continue;
                }

                $isPaid = in_array(strtolower($enrollment->status), ['active', 'ongoing', 'completed']);

                $data[] = [
                    'id' => 'e_'.$enrollment->id,
                    'course' => $course->title,
                    'invoice_number' => 'INV-'.str_pad($enrollment->id, 5, '0', STR_PAD_LEFT),
                    'date' => Carbon::parse($enrollment->created_at)->format('d M, Y'),
                    'course_amount' => $course->mrp_price,
                    'paid_amount' => $isPaid ? $course->buy_price : 0,
                    'status' => $isPaid ? 'Paid' : 'Unpaid',
                    'type' => $course->course_type === 'live' ? 'live' : 'recorded',
                ];
            }

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function courseVideos(Request $request, $id)
    {
        try {
            $course = Course::with('videos.summaries')->find($id);

            if (! $course) {
                return response()->json(['status' => false, 'message' => 'Course not found'], 404);
            }

            return response()->json([
                'status' => true,
                'course' => $course,
                'videos' => $course->videos,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function studentMeetings(Request $request, $id)
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found']);
            }

            $meetings = [];

            $userPhone = $user->phonenumber;
            $cleanPhone = preg_replace('/[^0-9]/', '', $userPhone ?? '');
            $last10 = strlen($cleanPhone) >= 10 ? substr($cleanPhone, -10) : $cleanPhone;

            // 1. Session Bookings (through Admissions matched by user email/phone)
            $admissions = Admission::where(function ($query) use ($user, $last10) {
                $query->whereHas('contact', function ($q) use ($user, $last10) {
                    if ($user->email) {
                        $q->where('email', $user->email);
                    }
                    if ($user->phonenumber) {
                        $q->orWhere('caller_number', $user->phonenumber)
                            ->orWhere('secondary_number', $user->phonenumber);
                    }
                    if ($last10) {
                        $q->orWhere('caller_number', 'like', "%{$last10}%")
                            ->orWhere('secondary_number', 'like', "%{$last10}%");
                    }
                })->orWhereHas('lead', function ($q) use ($user, $last10) {
                    if ($user->email) {
                        $q->where('email', $user->email);
                    }
                    if ($user->phonenumber) {
                        $q->orWhere('phone', $user->phonenumber)
                            ->orWhere('whatsapp', $user->phonenumber);
                    }
                    if ($last10) {
                        $q->orWhere('phone', 'like', "%{$last10}%")
                            ->orWhere('whatsapp', 'like', "%{$last10}%");
                    }
                });
            })->pluck('id');

            $sessionBookings = SessionBooking::with(['staff', 'admission.course'])
                ->whereIn('admission_id', $admissions)
                ->get();

            foreach ($sessionBookings as $booking) {
                $isDemo = $booking->type === 'demo';
                $meetings[] = [
                    'id' => 'session_'.$booking->id,
                    'type' => $isDemo ? 'Demo Session' : 'Session',
                    'title' => $booking->topic ?: ($isDemo ? 'Live Demo Session' : 'Mentorship Session'),
                    'date' => clone Carbon::parse($booking->date),
                    'date_raw' => Carbon::parse($booking->date)->format('Y-m-d'),
                    'date_formatted' => Carbon::parse($booking->date)->format('M d, Y'),
                    'time' => Carbon::parse($booking->time)->format('h:i A'),
                    'instructor' => $booking->staff ? $booking->staff->name : 'Mentor',
                    'course' => $booking->admission && $booking->admission->course ? $booking->admission->course->title : ($isDemo ? 'Live Demo' : 'General'),
                    'link' => $booking->zoom_meet_link,
                    'status' => $booking->status,
                ];
            }

            // 2. Demo Sessions from CallLog (scheduled demos matched by phone, contact, or lead)
            $demoCallLogs = CallLog::with(['assignedStaff', 'staff', 'contact.admissions.course', 'lead.courses'])
                ->whereNotNull('demo_date')
                ->where(function ($query) use ($user, $last10) {
                    if ($user->phonenumber) {
                        $query->where('caller_number', $user->phonenumber);
                    }
                    if ($last10) {
                        $query->orWhere('caller_number', 'like', "%{$last10}%");
                    }
                    $query->orWhereHas('contact', function ($cq) use ($user, $last10) {
                        if ($user->email) {
                            $cq->where('email', $user->email);
                        }
                        if ($user->phonenumber) {
                            $cq->orWhere('caller_number', $user->phonenumber)
                                ->orWhere('secondary_number', $user->phonenumber);
                        }
                        if ($last10) {
                            $cq->orWhere('caller_number', 'like', "%{$last10}%")
                                ->orWhere('secondary_number', 'like', "%{$last10}%");
                        }
                    })->orWhereHas('lead', function ($lq) use ($user, $last10) {
                        if ($user->email) {
                            $lq->where('email', $user->email);
                        }
                        if ($user->phonenumber) {
                            $lq->orWhere('phone', $user->phonenumber)
                                ->orWhere('whatsapp', $user->phonenumber);
                        }
                        if ($last10) {
                            $lq->orWhere('phone', 'like', "%{$last10}%")
                                ->orWhere('whatsapp', 'like', "%{$last10}%");
                        }
                    });
                })
                ->get();

            foreach ($demoCallLogs as $demo) {
                $instructor = 'Mentor';
                if ($demo->assignedStaff) {
                    $instructor = $demo->assignedStaff->name;
                } elseif ($demo->staff) {
                    $instructor = $demo->staff->name;
                }

                $courseTitle = 'Live Demo Class';
                if ($demo->lead && $demo->lead->courses && $demo->lead->courses->isNotEmpty()) {
                    $courseTitle = $demo->lead->courses->first()->title;
                } elseif ($demo->contact && $demo->contact->admissions && $demo->contact->admissions->isNotEmpty()) {
                    $lastAdm = $demo->contact->admissions->last();
                    if ($lastAdm && $lastAdm->course) {
                        $courseTitle = $lastAdm->course->title;
                    }
                }

                $meetings[] = [
                    'id' => 'demo_'.$demo->id,
                    'type' => 'Demo Session',
                    'title' => 'Live Demo Session',
                    'date' => clone Carbon::parse($demo->demo_date),
                    'date_raw' => Carbon::parse($demo->demo_date)->format('Y-m-d'),
                    'date_formatted' => Carbon::parse($demo->demo_date)->format('M d, Y'),
                    'time' => $demo->demo_time ? Carbon::parse($demo->demo_time)->format('h:i A') : 'TBA',
                    'instructor' => $instructor,
                    'course' => $courseTitle,
                    'link' => $demo->zoom_meet_link,
                    'status' => $demo->demo_status ?: ($demo->status ?: 'Demo Scheduled'),
                ];
            }

            // 3. Support Tickets (Doubt Sessions)
            $supportTickets = SupportTicketAllocation::with(['staff', 'ticket.course'])
                ->whereHas('ticket', function ($q) use ($id) {
                    $q->where('user_id', $id);
                })
                ->get();

            foreach ($supportTickets as $allocation) {
                if ($allocation->allocated_date) {
                    $meetings[] = [
                        'id' => 'support_'.$allocation->id,
                        'type' => 'Doubt Session',
                        'title' => $allocation->ticket ? $allocation->ticket->subject : 'Doubt Clearance',
                        'date' => clone Carbon::parse($allocation->allocated_date),
                        'date_raw' => Carbon::parse($allocation->allocated_date)->format('Y-m-d'),
                        'date_formatted' => Carbon::parse($allocation->allocated_date)->format('M d, Y'),
                        'time' => $allocation->allocated_slot ? Carbon::parse($allocation->allocated_slot)->format('h:i A') : '',
                        'instructor' => $allocation->staff ? $allocation->staff->name : 'Technical Staff',
                        'course' => $allocation->ticket && $allocation->ticket->course ? $allocation->ticket->course->title : 'General',
                        'link' => $allocation->zoom_meet_link,
                        'status' => $allocation->status,
                    ];
                }
            }

            // 4. Live Batch Meetings (Classes)
            $enrollments = CourseUser::with(['batch', 'batch.instructor', 'course'])
                ->where('auth_id', $id)
                ->whereHas('course', function ($q) {
                    $q->where('course_type', 'live');
                })
                ->where('status', 'active')
                ->get();

            foreach ($enrollments as $enrollment) {
                if ($enrollment->batch && $enrollment->batch->batch_status !== 'Completed') {
                    $meetings[] = [
                        'id' => 'batch_'.$enrollment->batch->id,
                        'type' => 'Live Class',
                        'title' => 'Batch: '.($enrollment->batch->batch_name ?: 'Standard Batch'),
                        'date' => Carbon::now(),
                        'date_raw' => Carbon::now()->format('Y-m-d'),
                        'date_formatted' => 'Recurring Class',
                        'time' => $enrollment->batch->batch_from_time ? Carbon::parse($enrollment->batch->batch_from_time)->format('h:i A').' - '.Carbon::parse($enrollment->batch->batch_to_time)->format('h:i A') : 'TBA',
                        'instructor' => $enrollment->batch->instructor ? $enrollment->batch->instructor->name : 'Mentor',
                        'course' => $enrollment->course->title,
                        'link' => null,
                        'status' => 'Ongoing',
                    ];
                }
            }

            // 5. 1:1 Student Evaluation Meetings (scheduled by Technical Staff)
            $staffEvaluationMeetings = \App\Models\StaffMeeting::with(['staff', 'admission.course'])
                ->where('meeting_type', 'Student Evaluation')
                ->where(function ($q) use ($id, $admissions, $user, $last10) {
                    $q->where('student_id', $id);
                    if ($admissions->isNotEmpty()) {
                        $q->orWhereIn('admission_id', $admissions);
                    }
                    if ($user && $user->email) {
                        $q->orWhereHas('admission.contact', fn($cq) => $cq->where('email', $user->email));
                    }
                    if ($user && $user->phonenumber) {
                        $q->orWhereHas('admission.contact', fn($cq) => $cq->where('caller_number', $user->phonenumber));
                    }
                    if ($last10) {
                        $q->orWhereHas('admission.contact', fn($cq) => $cq->where('caller_number', 'like', "%{$last10}%"));
                    }
                })
                ->get();

            foreach ($staffEvaluationMeetings as $evalM) {
                $meetings[] = [
                    'id' => 'eval_'.$evalM->id,
                    'type' => 'Evaluation Class',
                    'title' => $evalM->title ?: '1:1 Evaluation Session',
                    'date' => clone Carbon::parse($evalM->date),
                    'date_raw' => Carbon::parse($evalM->date)->format('Y-m-d'),
                    'date_formatted' => Carbon::parse($evalM->date)->format('M d, Y'),
                    'time' => $evalM->time ? Carbon::parse($evalM->time)->format('h:i A') : 'TBA',
                    'instructor' => $evalM->staff ? $evalM->staff->name : 'Technical Staff',
                    'course' => $evalM->admission && $evalM->admission->course ? $evalM->admission->course->title : 'Evaluation Session',
                    'link' => $evalM->meet_link ?: $evalM->zoom_meet_link,
                    'status' => $evalM->status ?: 'upcoming',
                ];
            }

            // Sort by date descending
            usort($meetings, function ($a, $b) {
                return $b['date'] <=> $a['date'];
            });

            return response()->json([
                'status' => true,
                'data' => $meetings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function liveDashboard(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found']);
            }

            // 1. Enrollment & Batch Info
            $enrollment = CourseUser::with(['batch', 'course'])
                ->where('auth_id', $id)
                ->whereHas('course', function ($q) {
                    $q->where('course_type', 'live');
                })
                ->where('status', 'active')
                ->first();

            $courseName = $enrollment && $enrollment->course ? $enrollment->course->title : 'No Active Course';
            $batchName = $enrollment && $enrollment->batch ? $enrollment->batch->batch_name : '';
            $batchCode = $enrollment && $enrollment->batch ? $enrollment->batch->batch_code : '';
            $batchStr = $batchName ? ($batchName.($batchCode ? ' • Batch '.$batchCode : '')) : '';

            // 2. Course Progress (Dynamic calculation based on curricula and batch topic logs)
            $completedModules = 0;
            $totalModules = 0;
            $courseProgress = 0;

            if ($enrollment && $enrollment->course) {
                // Fetch the curricula (modules) with their descriptions (topics)
                $curricula = CourseCurriculum::with('descriptions')
                    ->where('course_id', $enrollment->course->id)
                    ->get();

                $totalModules = $curricula->count();

                if ($enrollment->batch_id) {
                    foreach ($curricula as $curriculum) {
                        $totalExpected = $curriculum->descriptions ? $curriculum->descriptions->count() : 0;

                        $totalLogged = BatchTopicLog::where('batch_id', $enrollment->batch_id)
                            ->where('course_curriculum_id', $curriculum->id)
                            ->count();

                        if ($totalExpected > 0 && $totalLogged >= $totalExpected) {
                            $completedModules++;
                        } elseif ($totalExpected == 0 && $totalLogged > 0) {
                            // If there are no descriptions but logs exist, consider it completed
                            $completedModules++;
                        }
                    }
                }

                $courseProgress = $totalModules > 0 ? round(($completedModules / $totalModules) * 100) : 0;
            }

            // 3. Assignments
            $assignments = [];
            $totalAssignments = 0;
            $submittedAssignments = 0;
            $avgScore = 0;

            if ($enrollment && $enrollment->course_id) {
                $assignmentQuery = Assignment::where('status', 'active')
                    ->where('course_id', $enrollment->course_id)
                    ->where(function ($query) use ($enrollment) {
                        $query->whereNull('batch_id');
                        if ($enrollment->batch_id) {
                            $query->orWhere('batch_id', $enrollment->batch_id);
                        }
                    });

                $totalAssignments = $assignmentQuery->count();
                $assignmentIds = $assignmentQuery->pluck('id');

                $submissions = AssignmentSubmission::where('user_id', $id)
                    ->whereIn('assignment_id', $assignmentIds)
                    ->get();

                $submittedAssignments = $submissions->count();
                $totalScore = $submissions->sum('score');
                $avgScore = $submittedAssignments > 0 ? round($totalScore / $submittedAssignments) : 0;
            }

            // 4. Mini Projects
            $miniProjects = [];
            if ($enrollment && $enrollment->course_id) {
                $projects = MiniProject::where('status', 'active')
                    ->where('course_id', $enrollment->course_id)
                    ->where(function ($query) use ($enrollment) {
                        $query->whereNull('batch_id');
                        if ($enrollment->batch_id) {
                            $query->orWhere('batch_id', $enrollment->batch_id);
                        }
                    })->get();

                foreach ($projects as $p) {
                    $sub = MiniProjectSubmission::where('user_id', $id)->where('mini_project_id', $p->id)->first();
                    $miniProjects[] = [
                        'id' => $p->id,
                        'name' => $p->title,
                        'module' => $p->module ?: 'General',
                        'submitted_date' => $sub ? Carbon::parse($sub->created_at)->format('M d') : null,
                        'score' => $sub ? $sub->score : 0,
                        'status' => $sub ? (($sub->score >= 50 && ! $sub->reupload_approved) ? 'Completed' : 'Changes Needed') : 'Pending',
                        'progress' => $sub ? $sub->score : 0,
                    ];
                }
            }

            // 5. Main Projects
            $mainProjects = [];
            if ($enrollment && $enrollment->course_id) {
                $projects = MainProject::where('status', 'active')
                    ->where('course_id', $enrollment->course_id)
                    ->where(function ($query) use ($enrollment) {
                        $query->whereNull('batch_id');
                        if ($enrollment->batch_id) {
                            $query->orWhere('batch_id', $enrollment->batch_id);
                        }
                    })->get();

                foreach ($projects as $p) {
                    $submissions = MainProjectSubmission::where('user_id', $id)
                        ->where('main_project_id', $p->id)
                        ->get()
                        ->keyBy('review_number');

                    $milestones = [];
                    $completedReviews = 0;

                    for ($i = 1; $i <= 3; $i++) {
                        $sub = $submissions->get($i);

                        $mStatus = 'Pending';
                        if ($sub) {
                            if ($sub->score >= 50 && ! $sub->reupload_approved) {
                                $mStatus = 'Completed';
                                $completedReviews++;
                            }
                        }

                        $milestones[] = [
                            'title' => "Review {$i}",
                            'date' => $sub ? Carbon::parse($sub->created_at)->format('M d') : 'Pending',
                            'status' => $mStatus,
                            'score' => $sub ? ($sub->score ?: 0) : 0,
                            'desc' => $sub && $sub->feedback ? Str::limit(strip_tags($sub->feedback), 80) : ($i == 3 ? 'Final submission & complete evaluation' : 'Progress check & intermediate evaluation'),
                        ];
                    }

                    $mainProjects[] = [
                        'id' => $p->id,
                        'title' => $p->title,
                        'review' => "Review {$completedReviews} / 3",
                        'milestones' => $milestones,
                    ];
                }
            }

            // 6. Dynamic Monthly Attendance (supports optional month & year, blocks future months)
            $attMonth = $request->input('month');
            $attYear = $request->input('year');
            $monthlyAttendance = $this->getStudentMonthlyAttendance($user, $enrollment, $attYear, $attMonth);

            return response()->json([
                'status' => true,
                'data' => [
                    'user' => [
                        'name' => $user->name,
                        'course_title' => $courseName,
                        'batch_string' => $batchStr,
                    ],
                    'progress' => [
                        'course_progress' => $courseProgress,
                        'completed_modules' => $completedModules,
                        'total_modules' => $totalModules,
                    ],
                    'assignments' => [
                        'total' => $totalAssignments,
                        'submitted' => $submittedAssignments,
                        'avg_score' => $avgScore,
                    ],
                    'mini_projects' => $miniProjects,
                    'main_projects' => $mainProjects,
                    'attendance' => $monthlyAttendance['attendance'],
                    'attendance_summary' => $monthlyAttendance['attendance_summary'],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * API to fetch student attendance calendar for any specific month and year.
     * GET /api/student/{id}/attendance-calendar?month=10&year=2026
     * Prevents viewing future months.
     */
    public function studentAttendanceCalendar(Request $request, $id): JsonResponse
    {
        try {
            $user = User::find($id);
            if (! $user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            $enrollment = CourseUser::with(['batch', 'course'])
                ->where('auth_id', $id)
                ->whereHas('course', function ($q) {
                    $q->where('course_type', 'live');
                })
                ->where('status', 'active')
                ->first();

            $month = $request->input('month');
            $year = $request->input('year');

            $data = $this->getStudentMonthlyAttendance($user, $enrollment, $year, $month);

            return response()->json([
                'status' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Compute dynamic monthly attendance for a student.
     * Prevents future months: clamps to current month and year.
     */
    private function getStudentMonthlyAttendance($user, $enrollment, $year = null, $month = null): array
    {
        $now = Carbon::now();
        $year = (int) ($year ?: $now->year);
        $month = (int) ($month ?: $now->month);

        // Disallow future months: clamp to current year and month
        if ($year > $now->year || ($year === $now->year && $month > $now->month)) {
            $year = $now->year;
            $month = $now->month;
        }

        $targetDate = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $targetDate->daysInMonth;
        $isCurrentMonth = ($year === $now->year && $month === $now->month);
        $currentDay = $isCurrentMonth ? $now->day : $daysInMonth;

        // Query BatchAttendance records for this student in the requested month & year
        $studentAttendances = BatchAttendance::where(function ($q) use ($user) {
                $q->where('student_id', $user->id)
                  ->orWhere('student_email', strtolower($user->email));
            })
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->day;
            });

        // Collect class days for the student's batch
        $batchClassDays = collect();
        if ($enrollment && $enrollment->batch_id) {
            $scheduleDays = BatchSchedule::where('batch_id', $enrollment->batch_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d)->day);

            $topicDays = BatchTopicLog::where('batch_id', $enrollment->batch_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d)->day);

            $batchAttendanceDays = BatchAttendance::where('batch_id', $enrollment->batch_id)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->pluck('date')
                ->map(fn($d) => Carbon::parse($d)->day);

            $batchClassDays = $scheduleDays->concat($topicDays)->concat($batchAttendanceDays)->unique();
        }

        $attendanceData = [];
        $presentCount = 0;
        $absentCount = 0;
        $noClassCount = 0;

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dateObj = Carbon::createFromDate($year, $month, $d);
            $status = 'none';

            // Only past days or today can be marked as present/absent
            if (!$isCurrentMonth || $d <= $currentDay) {
                if ($studentAttendances->has($d)) {
                    $att = $studentAttendances->get($d);
                    $attStatus = strtolower(trim($att->status ?? ''));
                    if (in_array($attStatus, ['present', 'half day']) || (float) $att->attendance_percentage >= 45) {
                        $status = 'present';
                        $presentCount++;
                    } else {
                        $status = 'absent';
                        $absentCount++;
                    }
                } elseif ($batchClassDays->contains($d)) {
                    // Class was held for the batch on this date, but student had no attendance recorded
                    $status = 'absent';
                    $absentCount++;
                } else {
                    $status = 'none';
                    $noClassCount++;
                }
            } else {
                // Future days in current month
                $status = 'none';
                $noClassCount++;
            }

            $attendanceData[] = [
                'day' => $d,
                'date' => $dateObj->toDateString(),
                'status' => $status,
            ];
        }

        $totalClassesHeld = $presentCount + $absentCount;
        $attendancePercentage = $totalClassesHeld > 0
            ? round(($presentCount / $totalClassesHeld) * 100)
            : 0;

        $attendanceSummary = [
            'present' => $presentCount,
            'absent' => $absentCount,
            'no_class' => $noClassCount,
            'total_classes' => $totalClassesHeld,
            'percentage' => $attendancePercentage,
            'month' => $month,
            'month_name' => $targetDate->format('F'),
            'year' => $year,
            'days_in_month' => $daysInMonth,
            'first_day_of_week' => $targetDate->dayOfWeekIso, // 1 (Mon) - 7 (Sun)
            'is_current_month' => $isCurrentMonth,
        ];

        return [
            'attendance' => $attendanceData,
            'attendance_summary' => $attendanceSummary,
        ];
    }
}
