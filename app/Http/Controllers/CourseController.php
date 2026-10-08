<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCombo;
use App\Models\CourseCurriculum;
use App\Models\CoursePractice;
use App\Models\CourseQuiz;
use App\Models\CourseUser;
use App\Models\CourseVideo;
use App\Models\CourseVideoProgress;
use App\Models\CourseVideoSummery;
use App\Models\CurriculumDescription;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:Course List|Course Create|Course Edit|Course Delete|Course Status', only: ['index', 'show', 'combo', 'live', 'courseVideos']),
            new Middleware('permission:Course Create', only: ['create', 'store', 'uploadVideo', 'storeVideo', 'storeVideoQuiz', 'addComboCourse', 'storeComboCourse', 'addLiveCourse', 'storeLiveCourse']),
            new Middleware('permission:Course Edit', only: ['edit', 'update', 'editVideo', 'updateVideo', 'editComboCourse', 'updateComboCourse', 'editLiveCourse', 'updateLiveCourse']),
            new Middleware('permission:Course Delete', only: ['destroy', 'deleteQuiz', 'destroyComboCourse']),
            new Middleware('permission:Course Status', only: ['updateCourseStatus']),

            new Middleware('permission:Practice List', only: ['managePractices']),
            new Middleware('permission:Practice Create', only: ['storeCoursePractice']),
            new Middleware('permission:Practice Edit', only: ['updateCoursePractice']),
            new Middleware('permission:Practice Delete', only: ['deletePractice']),
            new Middleware('permission:Practice Status', only: ['updatePracticeStatus']),
        ];
    }

    /* ==========================================================
     |  LIST COURSES
     ========================================================== */
    public function index(Request $request)
    {
        $courses = Course::withCount('videos')
            ->whereNotIn('course_type', ['live', 'combo']);

        if ($request->filled('search')) {
            $courses->where('title', 'like', '%' . trim($request->search) . '%');
        }

        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        $courses = $courses
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('courses.index', compact('courses'));
    }

    public function combo(Request $request)
    {
        $courses = Course::withCount('videos')
            ->where('course_type', 'combo');

        if ($request->filled('search')) {
            $courses->where('title', 'like', '%' . trim($request->search) . '%');
        }

        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        $courses = $courses
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('courses-combo.index', compact('courses'));
    }

    public function live(Request $request)
    {
        $courses = Course::withCount('videos')
            ->where('course_type', 'live');

        if ($request->filled('search')) {
            $courses->where('title', 'like', '%' . trim($request->search) . '%');
        }

        if ($request->filled('status')) {
            $courses->where('status', $request->status);
        }

        $courses = $courses
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('courses-live.index', compact('courses'));
    }

    /* ==========================================================
     |  SHOW COURSE DETAILS
     ========================================================== */
    public function show(string $id)
    {
        try {
            $courseId = decrypt($id);
            $course = Course::with(['curricula.descriptions', 'leans', 'requirements', 'benefits', 'skills', 'videos', 'mini_projects', 'main_projects', 'assignments'])
                ->findOrFail($courseId);

            return view('courses.show', compact('course'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Invalid Course ID or Course not found.');
        }
    }

    /* ==========================================================
     |  CREATE VIEW
     ========================================================== */
    public function create()
    {
        return view('courses.create');
    }

    public function addComboCourse()
    {
        $courses = Course::where('course_type', '!=', 'combo')->get();
        return view('courses-combo.create', compact('courses'));
    }

    public function addLiveCourse()
    {
        return view('courses-live.create');
    }

    /* ==========================================================
     |  STORE COURSE
     ========================================================== */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'title' => 'required|string|max:200',
            'sub_description' => 'nullable|string',
            'with_certificate' => 'required',
            'recorded_content' => 'required',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'nullable|string|max:255',
            'learning_title' => 'nullable|string|max:255',
            'learning_description' => 'nullable|string',
            'why_us_title' => 'nullable|string|max:255',
            'why_us_description' => 'nullable|string',
            'why_us_heading_1' => 'nullable|string|max:255',
            'why_us_desc_1' => 'nullable|string',
            'why_us_heading_2' => 'nullable|string|max:255',
            'why_us_desc_2' => 'nullable|string',
            'why_us_heading_3' => 'nullable|string|max:255',
            'why_us_desc_3' => 'nullable|string',
            'why_us_heading_4' => 'nullable|string|max:255',
            'why_us_desc_4' => 'nullable|string',
            'skills_title' => 'nullable|string|max:255',
            'skills_description' => 'nullable|string',
            'end_card_description' => 'nullable|string',
            'about_course' => 'required|string',
            'mrp_price' => 'nullable|numeric',
            'buy_price' => 'nullable|numeric',
            'course_type' => 'required|in:free,paid,combo',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string|max:200',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*' => 'required|string',
            'leans' => 'nullable|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'required|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'required|string',
            'skills' => 'nullable|array',
            'skills.*' => 'required|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
            'testimonials' => 'nullable|array',
            'testimonials.*.name' => 'required|string|max:200',
            'testimonials.*.content' => 'required|string',
            'testimonials.*.rating' => 'required|string',
            'testimonials.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* ---------- IMAGE ---------- */
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
            }

            /* ---------- SYLLABUS PDF ---------- */
            $syllabusPdfName = null;
            if ($request->hasFile('syllabus_pdf')) {
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
            }

            /* ---------- COURSE ---------- */
            $course = Course::create([
                'course_code' => $request->course_code,
                'syllabus_pdf' => $syllabusPdfName,
                'release_date' => $request->release_date,
                'image' => $imageName,
                'title' => ucwords(trim($request->title)),
                'sub_description' => $request->sub_description,
                'with_certificate' => $request->with_certificate,
                'recorded_content' => $request->recorded_content,
                'about_course' => $request->about_course,
                'language' => $request->language,
                'cour_language' => $request->cour_language,
                'categories' => $request->categories,
                'mrp_price' => $request->mrp_price,
                'buy_price' => $request->buy_price,
                'single_student_price' => $request->single_student_price,
                'group_student_price' => $request->group_student_price,
                'offer_price' => $request->offer_price,
                'course_type' => $request->course_type,
                'end_card_title' => $request->end_card_title,
                'learning_title' => $request->learning_title,
                'learning_description' => $request->learning_description,
                'why_us_title' => $request->why_us_title,
                'why_us_description' => $request->why_us_description,
                'why_us_heading_1' => $request->why_us_heading_1,
                'why_us_desc_1' => $request->why_us_desc_1,
                'why_us_heading_2' => $request->why_us_heading_2,
                'why_us_desc_2' => $request->why_us_desc_2,
                'why_us_heading_3' => $request->why_us_heading_3,
                'why_us_desc_3' => $request->why_us_desc_3,
                'why_us_heading_4' => $request->why_us_heading_4,
                'why_us_desc_4' => $request->why_us_desc_4,
                'skills_title' => $request->skills_title,
                'skills_description' => $request->skills_description,
                'end_card_description' => $request->end_card_description,
                'status' => 1,
            ]);

            /* ---------- CURRICULA ---------- */
            foreach ($request->curricula as $item) {
                $curriculum = $course->curricula()->create([
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                ]);

                foreach ($item['descriptions'] as $desc) {
                    $curriculum->descriptions()->create([
                        'description' => $desc,
                    ]);
                }
            }

            /* ---------- CURRICULA ---------- */
            if ($request->has('curricula')) {
                foreach ($request->curricula as $item) {
                    $curriculum = $course->curricula()->create([
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                    ]);
                    if (!empty($item['descriptions'])) {
                        foreach ($item['descriptions'] as $desc) {
                            $curriculum->descriptions()->create([
                                'description' => $desc
                            ]);
                        }
                    }
                }
            }

            /* ---------- LEARNING POINTS ---------- */
            if (!empty($request->leans)) {
                foreach ($request->leans as $lean) {
                    $course->leans()->create([
                        'title' => $lean['title'] ?? null,
                        'description' => $lean['description']
                    ]);
                }
            }

            /* ---------- REQUIREMENTS ---------- */
            if (!empty($request->requirements)) {
                foreach ($request->requirements as $requirement) {
                    $course->requirements()->create([
                        'description' => $requirement
                    ]);
                }
            }

            /* ---------- BENEFITS ---------- */
            if (!empty($request->benefits)) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create([
                        'description' => $benefit
                    ]);
                }
            }

            if (!empty($request->skills)) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create([
                        'name' => $skill
                    ]);
                }
            }

            /* ---------- FAQS ---------- */
            if (!empty($request->faqs)) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            /* ---------- TESTIMONIALS ---------- */
            if (!empty($request->testimonials)) {
                foreach ($request->testimonials as $testi) {
                    $testiImageName = null;
                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeComboCourse(Request $request)
    {
        if ($request->has('live_course_type')) {
            $request->merge(['combo_course_type' => $request->live_course_type]);
        }

        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'title' => 'required|string|max:200',
            'sub_description' => 'required|string',
            'with_certificate' => 'required|string',
            'recorded_content' => 'required|numeric',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'required|string|max:255',
            'learning_title' => 'required|string|max:255',
            'learning_description' => 'required|string',
            'why_us_title' => 'required|string|max:255',
            'why_us_description' => 'required|string',
            'why_us_heading_1' => 'required|string|max:255',
            'why_us_desc_1' => 'required|string',
            'why_us_heading_2' => 'required|string|max:255',
            'why_us_desc_2' => 'required|string',
            'why_us_heading_3' => 'required|string|max:255',
            'why_us_desc_3' => 'required|string',
            'why_us_heading_4' => 'required|string|max:255',
            'why_us_desc_4' => 'required|string',
            'skills_title' => 'required|string|max:255',
            'skills_description' => 'required|string',
            'end_card_description' => 'required|string',
            'about_course' => 'required|string',
            'mrp_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'single_student_price' => 'required|numeric',
            'group_student_price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'course_type' => 'required|in:combo',
            'combo_course_type' => 'nullable',
            'leans' => 'required|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'required|array',
            'requirements.*' => 'required|string',
            'benefits' => 'required|array',
            'benefits.*' => 'required|string',
            'skills' => 'required|array',
            'skills.*' => 'required|string',
            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string|max:200',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
            'courses.*.required' => 'The course selection is required.',
            'courses.*.exists' => 'The selected course is invalid.',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* IMAGE */
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
            }

            /* SYLLABUS PDF */
            $syllabusPdfName = null;
            if ($request->hasFile('syllabus_pdf')) {
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
            }

            /* COURSE */
            $course = Course::create([
                'course_code' => $request->course_code,
                'syllabus_pdf' => $syllabusPdfName,
                'release_date' => $request->release_date,
                'image' => $imageName,
                'title' => ucwords(trim($request->title)),
                'sub_description' => $request->sub_description,
                'with_certificate' => $request->with_certificate,
                'recorded_content' => $request->recorded_content,
                'about_course' => $request->about_course,
                'language' => $request->language,
                'cour_language' => $request->cour_language,
                'categories' => $request->categories,
                'mrp_price' => $request->mrp_price,
                'buy_price' => $request->buy_price,
                'single_student_price' => $request->single_student_price,
                'group_student_price' => $request->group_student_price,
                'offer_price' => $request->offer_price,
                'course_type' => 'combo',
                'combo_course_type' => $request->live_course_type,
                'end_card_title' => $request->end_card_title,
                'learning_title' => $request->learning_title,
                'learning_description' => $request->learning_description,
                'why_us_title' => $request->why_us_title,
                'why_us_description' => $request->why_us_description,
                'why_us_heading_1' => $request->why_us_heading_1,
                'why_us_desc_1' => $request->why_us_desc_1,
                'why_us_heading_2' => $request->why_us_heading_2,
                'why_us_desc_2' => $request->why_us_desc_2,
                'why_us_heading_3' => $request->why_us_heading_3,
                'why_us_desc_3' => $request->why_us_desc_3,
                'why_us_heading_4' => $request->why_us_heading_4,
                'why_us_desc_4' => $request->why_us_desc_4,
                'skills_title' => $request->skills_title,
                'skills_description' => $request->skills_description,
                'end_card_description' => $request->end_card_description,
                'status' => 1,
            ]);

            /* ---------- LEARNING POINTS ---------- */
            if (!empty($request->leans)) {
                foreach ($request->leans as $lean) {
                    $course->leans()->create([
                        'title' => $lean['title'] ?? null,
                        'description' => $lean['description']
                    ]);
                }
            }

            /* ---------- REQUIREMENTS ---------- */
            if (!empty($request->requirements)) {
                foreach ($request->requirements as $requirement) {
                    $course->requirements()->create([
                        'description' => $requirement
                    ]);
                }
            }

            /* ---------- BENEFITS ---------- */
            if (!empty($request->benefits)) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create([
                        'description' => $benefit
                    ]);
                }
            }

            if (!empty($request->skills)) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create([
                        'name' => $skill
                    ]);
                }
            }

            /* ---------- FAQS ---------- */
            if (!empty($request->faqs)) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            /* ---------- TESTIMONIALS ---------- */
            if (!empty($request->testimonials)) {
                foreach ($request->testimonials as $testi) {
                    $testiImageName = null;
                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            }

            /* ---------- CURRICULA ---------- */
            if ($request->has('curricula')) {
                foreach ($request->curricula as $item) {
                    $curriculum = $course->curricula()->create([
                        'title' => $item['title'] ?? null,
                        'description' => $item['description'] ?? null,
                    ]);
                    if (!empty($item['descriptions'])) {
                        foreach ($item['descriptions'] as $desc) {
                            $curriculum->descriptions()->create([
                                'description' => $desc,
                            ]);
                        }
                    }
                }
            }

            /* COMBO COURSES */
            foreach ($request->courses as $courseId) {
                CourseCombo::create([
                    'course_id' => $course->id,  // combo course
                    'class_id' => $courseId,  // included course
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Combo course created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeLiveCourse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'course_pdf' => 'nullable|mimes:pdf|max:2048',
            'title' => 'required|string|max:200',
            'sub_description' => 'required|string',
            'with_certificate' => 'required',
            'recorded_content' => 'required',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'required|string|max:255',
            'learning_title' => 'required|string|max:255',
            'learning_description' => 'required|string',
            'why_us_title' => 'required|string|max:255',
            'why_us_description' => 'required|string',
            'why_us_heading_1' => 'required|string|max:255',
            'why_us_desc_1' => 'required|string',
            'why_us_heading_2' => 'required|string|max:255',
            'why_us_desc_2' => 'required|string',
            'why_us_heading_3' => 'required|string|max:255',
            'why_us_desc_3' => 'required|string',
            'why_us_heading_4' => 'required|string|max:255',
            'why_us_desc_4' => 'required|string',
            'skills_title' => 'required|string|max:255',
            'skills_description' => 'required|string',
            'end_card_description' => 'required|string',
            'about_course' => 'required|string',
            'mrp_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'course_type' => 'required|in:live',
            'duration' => 'required|string|max:100',
            'live_course_type' => 'required|in:normal,fast track',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string|max:200',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*' => 'required|string',
            'leans' => 'required|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'required|array',
            'requirements.*' => 'required|string',
            'benefits' => 'required|array',
            'benefits.*' => 'required|string',
            'skills' => 'required|array',
            'skills.*' => 'required|string',
            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
            'courses.*.required' => 'The course selection is required.',
            'courses.*.exists' => 'The selected course is invalid.',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* ---------- IMAGE ---------- */
            $imageName = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
            }

            /* ---------- COURSE PDF ---------- */
            $pdfName = null;
            if ($request->hasFile('course_pdf')) {
                $pdfName = time() . '_' . uniqid() . '.' . $request->course_pdf->extension();
                $request->course_pdf->move(public_path('uploads/courses'), $pdfName);
            }

            /* ---------- SYLLABUS PDF ---------- */
            $syllabusPdfName = null;
            if ($request->hasFile('syllabus_pdf')) {
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
            }

            /* ---------- COURSE ---------- */
            $course = Course::create([
                'course_code' => $request->course_code,
                'syllabus_pdf' => $syllabusPdfName,
                'release_date' => $request->release_date,
                'image' => $imageName,
                'course_pdf' => $pdfName,
                'title' => ucwords(trim($request->title)),
                'sub_description' => $request->sub_description,
                'with_certificate' => $request->with_certificate,
                'recorded_content' => $request->recorded_content,
                'about_course' => $request->about_course,
                'language' => $request->language,
                'cour_language' => $request->cour_language,
                'categories' => $request->categories,
                'mrp_price' => $request->mrp_price,
                'buy_price' => $request->buy_price,
                'single_student_price' => $request->single_student_price,
                'group_student_price' => $request->group_student_price,
                'offer_price' => $request->offer_price,
                'course_type' => $request->course_type,
                'end_card_title' => $request->end_card_title,
                'learning_title' => $request->learning_title,
                'learning_description' => $request->learning_description,
                'why_us_title' => $request->why_us_title,
                'why_us_description' => $request->why_us_description,
                'why_us_heading_1' => $request->why_us_heading_1,
                'why_us_desc_1' => $request->why_us_desc_1,
                'why_us_heading_2' => $request->why_us_heading_2,
                'why_us_desc_2' => $request->why_us_desc_2,
                'why_us_heading_3' => $request->why_us_heading_3,
                'why_us_desc_3' => $request->why_us_desc_3,
                'why_us_heading_4' => $request->why_us_heading_4,
                'why_us_desc_4' => $request->why_us_desc_4,
                'skills_title' => $request->skills_title,
                'skills_description' => $request->skills_description,
                'end_card_description' => $request->end_card_description,
                'duration' => $request->duration,
                'end_card_title' => $request->end_card_title,
                'learning_title' => $request->learning_title,
                'learning_description' => $request->learning_description,
                'why_us_title' => $request->why_us_title,
                'why_us_description' => $request->why_us_description,
                'why_us_heading_1' => $request->why_us_heading_1,
                'why_us_desc_1' => $request->why_us_desc_1,
                'why_us_heading_2' => $request->why_us_heading_2,
                'why_us_desc_2' => $request->why_us_desc_2,
                'why_us_heading_3' => $request->why_us_heading_3,
                'why_us_desc_3' => $request->why_us_desc_3,
                'why_us_heading_4' => $request->why_us_heading_4,
                'why_us_desc_4' => $request->why_us_desc_4,
                'end_card_description' => $request->end_card_description,
                'live_course_type' => $request->live_course_type,
                'status' => 1,
            ]);

            /* ---------- CURRICULA ---------- */
            foreach ($request->curricula as $item) {
                $curriculum = $course->curricula()->create([
                    'title' => $item['title'],
                    'description' => $item['description'] ?? null,
                ]);

                foreach ($item['descriptions'] as $desc) {
                    $curriculum->descriptions()->create([
                        'description' => $desc,
                    ]);
                }
            }

            /* ---------- LEARNING POINTS ---------- */
            if (!empty($request->leans)) {
                foreach ($request->leans as $lean) {
                    $course->leans()->create([
                        'title' => $lean['title'] ?? null,
                        'description' => $lean['description']
                    ]);
                }
            }

            /* ---------- REQUIREMENTS ---------- */
            if (!empty($request->requirements)) {
                foreach ($request->requirements as $requirement) {
                    $course->requirements()->create([
                        'description' => $requirement
                    ]);
                }
            }

            /* ---------- BENEFITS ---------- */
            if (!empty($request->benefits)) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create([
                        'description' => $benefit
                    ]);
                }
            }

            /* ---------- SKILLS ---------- */
            if (!empty($request->skills)) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create([
                        'name' => $skill
                    ]);
                }
            }

            /* ---------- FAQS ---------- */
            if (!empty($request->faqs)) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            /* ---------- TESTIMONIALS ---------- */
            if (!empty($request->testimonials)) {
                foreach ($request->testimonials as $testi) {
                    $testiImageName = null;
                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ==========================================================
     |  EDIT COURSE
     ========================================================== */
    public function edit(string $id)
    {
        $course = Course::with('curricula.descriptions', 'leans', 'requirements', 'benefits', 'skills', 'faqs', 'testimonials')
            ->findOrFail(decrypt($id));

        return view('courses.edit', compact('course'));
    }

    public function editComboCourse(string $id)
    {
        $course = Course::with('includedCourses', 'curricula.descriptions', 'leans', 'requirements', 'benefits', 'skills', 'faqs')
            ->findOrFail(decrypt($id));

        $courses = Course::where('course_type', '!=', 'combo')->get();

        // ✅ ADD THIS
        $selectedCourses = $course->includedCourses->pluck('id')->toArray();

        return view('courses-combo.edit', compact(
            'course',
            'courses',
            'selectedCourses'
        ));
    }

    public function editLiveCourse(string $id)
    {
        $course = Course::with('curricula.descriptions', 'leans', 'requirements', 'benefits', 'skills', 'faqs')
            ->findOrFail(decrypt($id));

        return view('courses-live.edit', compact('course'));
    }

    /* ==========================================================
     |  UPDATE COURSE
     ========================================================== */
    public function update(Request $request, $id)
    {
        $course = Course::with('curricula.descriptions', 'leans', 'requirements')
            ->findOrFail(decrypt($id));

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'title' => 'required|string|max:200',
            'sub_description' => 'nullable|string',
            'with_certificate' => 'required|string',
            'recorded_content' => 'required|numeric',
            'about_course' => 'required|string',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'nullable|string|max:255',
            'learning_title' => 'nullable|string|max:255',
            'learning_description' => 'nullable|string',
            'why_us_title' => 'nullable|string|max:255',
            'why_us_description' => 'nullable|string',
            'why_us_heading_1' => 'nullable|string|max:255',
            'why_us_desc_1' => 'nullable|string',
            'why_us_heading_2' => 'nullable|string|max:255',
            'why_us_desc_2' => 'nullable|string',
            'why_us_heading_3' => 'nullable|string|max:255',
            'why_us_desc_3' => 'nullable|string',
            'why_us_heading_4' => 'nullable|string|max:255',
            'why_us_desc_4' => 'nullable|string',
            'skills_title' => 'nullable|string|max:255',
            'skills_description' => 'nullable|string',
            'end_card_description' => 'nullable|string',
            'mrp_price' => 'nullable|numeric',
            'buy_price' => 'nullable|numeric',
            'course_type' => 'required|in:free,paid,combo',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*.description' => 'required|string',
            'leans' => 'nullable|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'required|string',
            'benefits' => 'nullable|array',
            'benefits.*' => 'required|string',
            'skills' => 'nullable|array',
            'skills.*' => 'required|string',
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.description.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ---------- IMAGE ----------
            if ($request->hasFile('image')) {
                if ($course->image && file_exists(public_path('uploads/courses/' . $course->image))) {
                    unlink(public_path('uploads/courses/' . $course->image));
                }
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $course->image = $imageName;
            }

            // ---------- SYLLABUS PDF ----------
            if ($request->hasFile('syllabus_pdf')) {
                if ($course->syllabus_pdf && file_exists(public_path('uploads/courses/' . $course->syllabus_pdf))) {
                    unlink(public_path('uploads/courses/' . $course->syllabus_pdf));
                }
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
                $course->syllabus_pdf = $syllabusPdfName;
            }

            // ---------- UPDATE COURSE ----------
            $course->update($request->only([
                'course_code', 'release_date',
                'title', 'sub_description', 'with_certificate', 'recorded_content',
                'about_course', 'language', 'cour_language', 'categories', 'mrp_price', 'buy_price', 'single_student_price', 'group_student_price', 'offer_price', 'course_type', 'end_card_title', 'end_card_description',
                'learning_title',
                'learning_description',
                'why_us_title',
                'why_us_description',
                'why_us_heading_1',
                'why_us_desc_1',
                'why_us_heading_2',
                'why_us_desc_2',
                'why_us_heading_3',
                'why_us_desc_3',
                'why_us_heading_4',
                'why_us_desc_4', 'learning_title', 'learning_description',
                'why_us_title',
                'why_us_description',
                'why_us_heading_1',
                'why_us_desc_1',
                'why_us_heading_2',
                'why_us_desc_2',
                'why_us_heading_3',
                'why_us_desc_3',
                'why_us_heading_4',
                'why_us_desc_4',
                'why_us_title', 'why_us_description',
                'why_us_heading_1', 'why_us_desc_1',
                'why_us_heading_2', 'why_us_desc_2',
                'why_us_heading_3', 'why_us_desc_3',
                'why_us_heading_4', 'why_us_desc_4',
                'skills_title', 'skills_description'
            ]));

            // ---------- CURRICULA ----------
            $curriculumIds = [];
            foreach ($request->curricula as $currData) {
                $curriculum = CourseCurriculum::updateOrCreate(
                    ['id' => $currData['id'] ?? null],
                    [
                        'course_id' => $course->id,
                        'title' => $currData['title'],
                        'description' => $currData['description'] ?? null,
                    ]
                );

                $curriculumIds[] = $curriculum->id;
                $descIds = [];

                foreach ($currData['descriptions'] as $descData) {
                    $description = CurriculumDescription::updateOrCreate(
                        ['id' => $descData['id'] ?? null],
                        [
                            'course_curriculum_id' => $curriculum->id,
                            'description' => $descData['description'],
                        ]
                    );
                    $descIds[] = $description->id;
                }

                // Delete removed descriptions
                $curriculum->descriptions()->whereNotIn('id', $descIds)->delete();
            }

            // Delete removed curricula
            $course->curricula()->whereNotIn('id', $curriculumIds)->delete();

            // ---------- CURRICULA ----------
            if ($request->has('curricula')) {
                $curriculumIds = collect($request->curricula)->pluck('id')->filter()->toArray();

                foreach ($request->curricula as $currData) {
                    $curriculum = $course->curricula()->updateOrCreate(
                        ['id' => $currData['id'] ?? null],
                        [
                            'title' => $currData['title'] ?? null,
                            'description' => $currData['description'] ?? null,
                        ]
                    );

                    if (!empty($currData['descriptions'])) {
                        $descIds = collect($currData['descriptions'])->pluck('id')->filter()->toArray();
                        foreach ($currData['descriptions'] as $descData) {
                            $curriculum->descriptions()->updateOrCreate(
                                ['id' => $descData['id'] ?? null],
                                ['description' => $descData['description']]
                            );
                        }
                        $curriculum->descriptions()->whereNotIn('id', $descIds)->delete();
                    } else {
                        $curriculum->descriptions()->delete();
                    }
                }

                $course->curricula()->whereNotIn('id', $curriculumIds)->delete();
            } else {
                $course->curricula()->delete();
            }

            // ---------- LEANS ----------
            $course->leans()->delete();
            if ($request->filled('leans')) {
                foreach ($request->leans as $leanData) {
                    $course->leans()->create([
                        'title' => $leanData['title'] ?? null,
                        'description' => $leanData['description']
                    ]);
                }
            }

            // ---------- REQUIREMENTS ----------
            $course->requirements()->delete();
            if ($request->filled('requirements')) {
                foreach ($request->requirements as $req) {
                    if (is_array($req)) {
                        $course->requirements()->create(['description' => $req['description']]);
                    } else {
                        $course->requirements()->create(['description' => $req]);
                    }
                }
            }

            // ---------- BENEFITS ----------
            $course->benefits()->delete();
            if ($request->filled('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create(['description' => $benefit]);
                }
            }

            $course->skills()->delete();
            if ($request->filled('skills')) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create(['name' => $skill]);
                }
            }

            // ---------- FAQS ----------
            $course->faqs()->delete();
            if ($request->filled('faqs')) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            // ---------- TESTIMONIALS ----------
            // We need to carefully update to keep existing images if no new image is provided
            if ($request->has('testimonials')) {
                $existingTestimonials = $course->testimonials->keyBy('id')->toArray();
                $course->testimonials()->delete();

                foreach ($request->testimonials as $tIndex => $testi) {
                    $testiImageName = null;

                    // If there is a new image file
                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    } else {
                        // Check if there was an old image provided by hidden field or existing data
                        if (isset($testi['old_image']) && !empty($testi['old_image'])) {
                            $testiImageName = $testi['old_image'];
                        }
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            } else {
                $course->testimonials()->delete();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateComboCourse(Request $request, $id)
    {
        if ($request->has('live_course_type')) {
            $request->merge(['combo_course_type' => $request->live_course_type]);
        }

        $course = Course::findOrFail(decrypt($id));

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'title' => 'required|string|max:200',
            'sub_description' => 'required|string',
            'with_certificate' => 'required|string',
            'recorded_content' => 'required|numeric',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'required|string|max:255',
            'learning_title' => 'required|string|max:255',
            'learning_description' => 'required|string',
            'why_us_title' => 'required|string|max:255',
            'why_us_description' => 'required|string',
            'why_us_heading_1' => 'required|string|max:255',
            'why_us_desc_1' => 'required|string',
            'why_us_heading_2' => 'required|string|max:255',
            'why_us_desc_2' => 'required|string',
            'why_us_heading_3' => 'required|string|max:255',
            'why_us_desc_3' => 'required|string',
            'why_us_heading_4' => 'required|string|max:255',
            'why_us_desc_4' => 'required|string',
            'skills_title' => 'required|string|max:255',
            'skills_description' => 'required|string',
            'end_card_description' => 'required|string',
            'about_course' => 'required|string',
            'mrp_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'single_student_price' => 'required|numeric',
            'group_student_price' => 'required|numeric',
            'offer_price' => 'required|numeric',
            'course_type' => 'required|in:combo',
            'combo_course_type' => 'nullable',
            'courses' => 'required|array|min:1',
            'courses.*' => 'required|exists:courses,id',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string|max:200',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*.description' => 'required|string',
            'leans' => 'required|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'required|array',
            'requirements.*' => 'required|string',
            'benefits' => 'required|array',
            'benefits.*' => 'required|string',
            'skills' => 'required|array',
            'skills.*' => 'required|string',
            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
            'courses.*.required' => 'The course selection is required.',
            'courses.*.exists' => 'The selected course is invalid.',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            /* IMAGE */
            if ($request->hasFile('image')) {
                if ($course->image && file_exists(public_path('uploads/courses/' . $course->image))) {
                    unlink(public_path('uploads/courses/' . $course->image));
                }

                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $course->image = $imageName;
            }

            /* SYLLABUS PDF */
            if ($request->hasFile('syllabus_pdf')) {
                if ($course->syllabus_pdf && file_exists(public_path('uploads/courses/' . $course->syllabus_pdf))) {
                    unlink(public_path('uploads/courses/' . $course->syllabus_pdf));
                }
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
                $course->syllabus_pdf = $syllabusPdfName;
            }

            /* UPDATE COURSE */
            $course->update($request->only([
                'course_code',
                'release_date',
                'title',
                'sub_description',
                'with_certificate',
                'recorded_content',
                'about_course',
                'language',
                'cour_language',
                'categories',
                'mrp_price',
                'buy_price',
                'single_student_price',
                'group_student_price',
                'offer_price',
                'course_type',
                'combo_course_type',
                'end_card_title',
                'end_card_description',
                'learning_title',
                'learning_description',
                'why_us_title',
                'why_us_description',
                'why_us_heading_1',
                'why_us_desc_1',
                'why_us_heading_2',
                'why_us_desc_2',
                'why_us_heading_3',
                'why_us_desc_3',
                'why_us_heading_4',
                'why_us_desc_4',
                'skills_title',
                'skills_description',
            ]));

            // ---------- LEANS ----------
            $course->leans()->delete();
            if ($request->filled('leans')) {
                foreach ($request->leans as $leanData) {
                    $course->leans()->create([
                        'title' => $leanData['title'] ?? null,
                        'description' => $leanData['description']
                    ]);
                }
            }

            // ---------- REQUIREMENTS ----------
            $course->requirements()->delete();
            if ($request->filled('requirements')) {
                foreach ($request->requirements as $req) {
                    if (is_array($req)) {
                        $course->requirements()->create(['description' => $req['description']]);
                    } else {
                        $course->requirements()->create(['description' => $req]);
                    }
                }
            }

            // ---------- BENEFITS ----------
            $course->benefits()->delete();
            if ($request->filled('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create(['description' => $benefit]);
                }
            }

            $course->skills()->delete();
            if ($request->filled('skills')) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create(['name' => $skill]);
                }
            }

            // ---------- FAQS ----------
            $course->faqs()->delete();
            if ($request->filled('faqs')) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            // ---------- TESTIMONIALS ----------
            if ($request->has('testimonials')) {
                $existingTestimonials = $course->testimonials->keyBy('id')->toArray();
                $course->testimonials()->delete();

                foreach ($request->testimonials as $tIndex => $testi) {
                    $testiImageName = null;

                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    } else {
                        if (isset($testi['old_image']) && !empty($testi['old_image'])) {
                            $testiImageName = $testi['old_image'];
                        }
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            } else {
                $course->testimonials()->delete();
            }

            // ---------- CURRICULA ----------
            if ($request->has('curricula')) {
                $curriculumIds = collect($request->curricula)->pluck('id')->filter()->toArray();

                foreach ($request->curricula as $currData) {
                    $curriculum = $course->curricula()->updateOrCreate(
                        ['id' => $currData['id'] ?? null],
                        [
                            'title' => $currData['title'] ?? null,
                            'description' => $currData['description'] ?? null,
                        ]
                    );

                    if (!empty($currData['descriptions'])) {
                        $descIds = collect($currData['descriptions'])->pluck('id')->filter()->toArray();
                        foreach ($currData['descriptions'] as $descData) {
                            $curriculum->descriptions()->updateOrCreate(
                                ['id' => $descData['id'] ?? null],
                                ['description' => $descData['description']]
                            );
                        }
                        $curriculum->descriptions()->whereNotIn('id', $descIds)->delete();
                    } else {
                        $curriculum->descriptions()->delete();
                    }
                }

                $course->curricula()->whereNotIn('id', $curriculumIds)->delete();
            } else {
                $course->curricula()->delete();
            }

            /* SYNC COMBO COURSES */
            CourseCombo::where('course_id', $course->id)->delete();

            foreach ($request->courses as $classId) {
                CourseCombo::create([
                    'course_id' => $course->id,
                    'class_id' => $classId,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Combo course updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateLiveCourse(Request $request, $id)
    {
        $course = Course::with('curricula.descriptions', 'leans', 'requirements')
            ->findOrFail(decrypt($id));

        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'course_pdf' => 'nullable|mimes:pdf|max:2048',
            'title' => 'required|string|max:200',
            'sub_description' => 'required|string',
            'with_certificate' => 'required|string',
            'recorded_content' => 'required|numeric',
            'about_course' => 'required|string',
            'language' => 'required|in:english,tamil',
            'cour_language' => 'required|string|max:100',
            'categories' => 'required|string|max:200',
            'end_card_title' => 'required|string|max:255',
            'learning_title' => 'required|string|max:255',
            'learning_description' => 'required|string',
            'why_us_title' => 'required|string|max:255',
            'why_us_description' => 'required|string',
            'why_us_heading_1' => 'required|string|max:255',
            'why_us_desc_1' => 'required|string',
            'why_us_heading_2' => 'required|string|max:255',
            'why_us_desc_2' => 'required|string',
            'why_us_heading_3' => 'required|string|max:255',
            'why_us_desc_3' => 'required|string',
            'why_us_heading_4' => 'required|string|max:255',
            'why_us_desc_4' => 'required|string',
            'skills_title' => 'required|string|max:255',
            'skills_description' => 'required|string',
            'end_card_description' => 'required|string',
            'mrp_price' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'course_type' => 'required|in:live',
            'duration' => 'required|string|max:100',
            'live_course_type' => 'required|in:normal,fast track',
            'curricula' => 'required|array|min:1',
            'curricula.*.title' => 'required|string',
            'curricula.*.descriptions' => 'required|array|min:1',
            'curricula.*.descriptions.*.description' => 'required|string',
            'leans' => 'required|array',
            'leans.*.title' => 'required|string|max:200',
            'leans.*.description' => 'required|string',
            'requirements' => 'required|array',
            'requirements.*' => 'required|string',
            'benefits' => 'required|array',
            'benefits.*' => 'required|string',
            'skills' => 'required|array',
            'skills.*' => 'required|string',
        ], [
            'curricula.*.title.required' => 'The section title is required.',
            'curricula.*.descriptions.required' => 'At least one section description is required.',
            'curricula.*.descriptions.*.description.required' => 'The section description is required.',
            'leans.*.title.required' => 'The learning point title is required.',
            'leans.*.description.required' => 'The learning point description is required.',
            'requirements.*.required' => 'The requirement field is required.',
            'benefits.*.required' => 'The benefit field is required.',
            'skills.*.required' => 'The skill field is required.',
            'faqs.*.question.required' => 'The FAQ question is required.',
            'faqs.*.answer.required' => 'The FAQ answer is required.',
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // ---------- IMAGE ----------
            if ($request->hasFile('image')) {
                if ($course->image && file_exists(public_path('uploads/courses/' . $course->image))) {
                    unlink(public_path('uploads/courses/' . $course->image));
                }
                $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/courses'), $imageName);
                $course->image = $imageName;
            }

            /* ---------- COURSE PDF ---------- */
            if ($request->hasFile('course_pdf')) {
                if ($course->course_pdf && file_exists(public_path('uploads/courses/' . $course->course_pdf))) {
                    unlink(public_path('uploads/courses/' . $course->course_pdf));
                }
                $pdfName = time() . '_' . uniqid() . '.' . $request->course_pdf->extension();
                $request->course_pdf->move(public_path('uploads/courses'), $pdfName);
                $course->course_pdf = $pdfName;
            }

            /* ---------- SYLLABUS PDF ---------- */
            if ($request->hasFile('syllabus_pdf')) {
                if ($course->syllabus_pdf && file_exists(public_path('uploads/courses/' . $course->syllabus_pdf))) {
                    unlink(public_path('uploads/courses/' . $course->syllabus_pdf));
                }
                $syllabusPdfName = time() . '_' . uniqid() . '.' . $request->syllabus_pdf->extension();
                $request->syllabus_pdf->move(public_path('uploads/courses'), $syllabusPdfName);
                $course->syllabus_pdf = $syllabusPdfName;
            }

            // ---------- UPDATE COURSE ----------
            $course->update($request->only([
                'course_code', 'release_date',
                'title', 'sub_description', 'with_certificate', 'recorded_content',
                'about_course', 'language', 'cour_language', 'categories', 'mrp_price', 'buy_price', 'single_student_price', 'group_student_price', 'offer_price', 'course_type', 'duration', 'live_course_type', 'end_card_title', 'end_card_description',
                'learning_title',
                'learning_description',
                'why_us_title',
                'why_us_description',
                'why_us_heading_1',
                'why_us_desc_1',
                'why_us_heading_2',
                'why_us_desc_2',
                'why_us_heading_3',
                'why_us_desc_3',
                'why_us_heading_4',
                'why_us_desc_4', 'learning_title', 'learning_description',
                'why_us_title',
                'why_us_description',
                'why_us_heading_1',
                'why_us_desc_1',
                'why_us_heading_2',
                'why_us_desc_2',
                'why_us_heading_3',
                'why_us_desc_3',
                'why_us_heading_4',
                'why_us_desc_4',
                'why_us_title', 'why_us_description',
                'why_us_heading_1', 'why_us_desc_1',
                'why_us_heading_2', 'why_us_desc_2',
                'why_us_heading_3', 'why_us_desc_3',
                'why_us_heading_4', 'why_us_desc_4',
                'skills_title', 'skills_description'
            ]));

            // ---------- CURRICULA ----------
            $curriculumIds = [];
            foreach ($request->curricula as $currData) {
                $curriculum = CourseCurriculum::updateOrCreate(
                    ['id' => $currData['id'] ?? null],
                    [
                        'course_id' => $course->id,
                        'title' => $currData['title'],
                        'description' => $currData['description'] ?? null,
                    ]
                );

                $curriculumIds[] = $curriculum->id;
                $descIds = [];

                foreach ($currData['descriptions'] as $descData) {
                    $description = CurriculumDescription::updateOrCreate(
                        ['id' => $descData['id'] ?? null],
                        [
                            'course_curriculum_id' => $curriculum->id,
                            'description' => $descData['description'],
                        ]
                    );
                    $descIds[] = $description->id;
                }

                // Delete removed descriptions
                $curriculum->descriptions()->whereNotIn('id', $descIds)->delete();
            }

            // Delete removed curricula
            $course->curricula()->whereNotIn('id', $curriculumIds)->delete();

            // ---------- LEANS ----------
            $course->leans()->delete();
            if ($request->filled('leans')) {
                foreach ($request->leans as $leanData) {
                    $course->leans()->create([
                        'title' => $leanData['title'] ?? null,
                        'description' => $leanData['description']
                    ]);
                }
            }

            // ---------- REQUIREMENTS ----------
            $course->requirements()->delete();
            if ($request->filled('requirements')) {
                foreach ($request->requirements as $req) {
                    if (is_array($req)) {
                        $course->requirements()->create(['description' => $req['description']]);
                    } else {
                        $course->requirements()->create(['description' => $req]);
                    }
                }
            }

            // ---------- BENEFITS ----------
            $course->benefits()->delete();
            if ($request->filled('benefits')) {
                foreach ($request->benefits as $benefit) {
                    $course->benefits()->create(['description' => $benefit]);
                }
            }

            $course->skills()->delete();
            if ($request->filled('skills')) {
                foreach ($request->skills as $skill) {
                    $course->skills()->create(['name' => $skill]);
                }
            }

            // ---------- FAQS ----------
            $course->faqs()->delete();
            if ($request->filled('faqs')) {
                foreach ($request->faqs as $faq) {
                    $course->faqs()->create([
                        'question' => $faq['question'] ?? null,
                        'answer' => $faq['answer'] ?? null
                    ]);
                }
            }

            // ---------- TESTIMONIALS ----------
            if ($request->has('testimonials')) {
                $existingTestimonials = $course->testimonials->keyBy('id')->toArray();
                $course->testimonials()->delete();

                foreach ($request->testimonials as $tIndex => $testi) {
                    $testiImageName = null;

                    if (isset($testi['image']) && $testi['image'] instanceof \Illuminate\Http\UploadedFile) {
                        $testiImageName = time() . '_' . uniqid() . '.' . $testi['image']->extension();
                        $testi['image']->move(public_path('uploads/testimonials'), $testiImageName);
                    } else {
                        if (isset($testi['old_image']) && !empty($testi['old_image'])) {
                            $testiImageName = $testi['old_image'];
                        }
                    }

                    $course->testimonials()->create([
                        'name' => $testi['name'] ?? null,
                        'content' => $testi['content'] ?? null,
                        'rating' => $testi['rating'] ?? null,
                        'image' => $testiImageName,
                    ]);
                }
            } else {
                $course->testimonials()->delete();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ==========================================================
     |  DELETE COURSE
     ========================================================== */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $course = Course::findOrFail(decrypt($id));

            if ($course->image && file_exists(public_path('uploads/courses/' . $course->image))) {
                unlink(public_path('uploads/courses/' . $course->image));
            }

            $course->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course has been deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroyComboCourse(string $id)
    {
        try {
            DB::beginTransaction();

            $course = Course::findOrFail(decrypt($id));

            if ($course->image && file_exists(public_path('uploads/courses/' . $course->image))) {
                unlink(public_path('uploads/courses/' . $course->image));
            }

            $course->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Course has been deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /* ==========================================================
     |  UPDATE STATUS
     ========================================================== */
    public function updateCourseStatus(Request $request)
    {
        $request->validate([
            'courseId' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $course = Course::findOrFail(decrypt($request->courseId));
        $course->update(['status' => $request->status]);

        return [
            'status' => true,
            'message' => 'Course status updated successfully.'
        ];
    }

    public function uploadVideo($id)
    {
        try {
            $courseId = decrypt($id);
            $course = Course::findOrFail($courseId);

            return view('courses.upload-video', compact('course'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function storeVideo(Request $request, $id)
    {
        try {
            // Use the plain ID (no decrypt unless you encrypted it)
            $course = Course::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:200',
                'video' => 'required|mimes:mp4,mov,avi,webm|max:2048000',  // 2GB
                'script' => 'nullable|string',
                'summaries.*' => 'required|string|max:1000'
            ]);

            // Upload Video
            $videoFile = $request->file('video');
            $timestamp = time();
            $pureFileName = preg_replace('/\s+/', '_', $videoFile->getClientOriginalName());
            $filename = $timestamp . '_' . $pureFileName;

            $uploadPath = public_path('videos/courses');
            $subPath = public_path('videos/subtitles');

            if (!file_exists($uploadPath))
                mkdir($uploadPath, 0777, true);
            if (!file_exists($subPath))
                mkdir($subPath, 0777, true);

            $videoFile->move($uploadPath, $filename);
            $videoRelativePath = 'videos/courses/' . $filename;

            // Handle Subtitles
            $subtitleEnPath = null;
            if ($request->hasFile('subtitle_en')) {
                $enName = $timestamp . '_' . pathinfo($pureFileName, PATHINFO_FILENAME) . '_en.vtt';
                $request->file('subtitle_en')->move($subPath, $enName);
                $subtitleEnPath = 'videos/subtitles/' . $enName;
            }

            $subtitleTaPath = null;
            if ($request->hasFile('subtitle_ta')) {
                $taName = $timestamp . '_' . pathinfo($pureFileName, PATHINFO_FILENAME) . '_ta.vtt';
                $request->file('subtitle_ta')->move($subPath, $taName);
                $subtitleTaPath = 'videos/subtitles/' . $taName;
            }

            $courseVideo = CourseVideo::create([
                'course_id' => $course->id,
                'title' => $request->title,
                'video' => $videoRelativePath,
                'subtitle_en' => $subtitleEnPath,
                'subtitle_ta' => $subtitleTaPath,
                'script' => $request->script,
            ]);

            // Save multiple summaries
            if ($request->has('summaries')) {
                foreach ($request->summaries as $summary) {
                    $courseVideo->summaries()->create([
                        'summary' => $summary
                    ]);
                }
            }

            return response()->json([
                'message' => 'Video and summaries uploaded successfully!'
            ]);
        } catch (\Exception $e) {
            // Log the error in laravel.log for debugging
            Log::error('Course Video Upload Error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'course_id' => $id,
                'file' => $request->file('video') ? $request->file('video')->getClientOriginalName() : null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'Something went wrong while uploading the video. Please check logs.'
            ], 500);
        }
    }

    public function courseVideos($id)
    {
        try {
            $courseId = decrypt($id);

            $course = Course::findOrFail($courseId);

            // Paginate videos (9 per page) + eager load summaries
            $videos = CourseVideo::with('summaries')
                ->where('course_id', $courseId)
                ->latest()
                ->paginate(9);

            return view('courses.videos.index', compact('course', 'videos'));
        } catch (\Exception $e) {
            Log::error('View Course Videos Error: ' . $e->getMessage(), [
                'course_id' => $id
            ]);

            return redirect()->back()->with('error', 'Unable to load course videos');
        }
    }

    // Edit page
    public function editVideo($id)
    {
        try {
            $videoId = decrypt($id);  // 🔐 DECRYPT HERE

            $video = CourseVideo::with('summaries')->findOrFail($videoId);

            return view('courses.videos.edit', compact('video'));
        } catch (\Exception $e) {
            Log::error('Edit Video Load Error: ' . $e->getMessage(), [
                'encrypted_id' => $id
            ]);

            return redirect()->back()->with('error', 'Unable to load video');
        }
    }

    // Update
    public function updateVideo(Request $request, $id)
    {
        try {
            $videoId = decrypt($id);

            $video = CourseVideo::with('summaries')->findOrFail($videoId);

            $request->validate([
                'title' => 'required|string|max:200',
                'video' => 'nullable|mimes:mp4,mov,avi,webm|max:2048000',
                'script' => 'nullable|string',
                'summaries.*' => 'required|string|max:1000'
            ]);

            // Update title and script
            $video->update([
                'title' => $request->title,
                'script' => $request->script,
            ]);

            // Replace video file (optional)
            if ($request->hasFile('video')) {
                if ($video->video && file_exists(public_path($video->video))) {
                    unlink(public_path($video->video));
                }

                $file = $request->file('video');
                $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->move(public_path('videos/courses'), $filename);

                $video->update(['video' => 'videos/courses/' . $filename]);
            }

            // Replace/Add English Subtitles
            if ($request->hasFile('subtitle_en')) {
                if ($video->subtitle_en && file_exists(public_path($video->subtitle_en))) {
                    unlink(public_path($video->subtitle_en));
                }
                $enName = time() . '_en_' . $request->file('subtitle_en')->getClientOriginalName();
                $request->file('subtitle_en')->move(public_path('videos/subtitles'), $enName);
                $video->update(['subtitle_en' => 'videos/subtitles/' . $enName]);
            }

            // Replace/Add Tamil Subtitles
            if ($request->hasFile('subtitle_ta')) {
                if ($video->subtitle_ta && file_exists(public_path($video->subtitle_ta))) {
                    unlink(public_path($video->subtitle_ta));
                }
                $taName = time() . '_ta_' . $request->file('subtitle_ta')->getClientOriginalName();
                $request->file('subtitle_ta')->move(public_path('videos/subtitles'), $taName);
                $video->update(['subtitle_ta' => 'videos/subtitles/' . $taName]);
            }

            // Update summaries
            $video->summaries()->delete();
            foreach ($request->summaries as $summary) {
                $video->summaries()->create([
                    'summary' => $summary
                ]);
            }

            return redirect()
                ->route('courses.videos.index', encrypt($video->course_id))
                ->with('success', 'Video updated successfully');
        } catch (\Exception $e) {
            Log::error('Video Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Update failed');
        }
    }

    /* ==========================================================
     |  COURSE DETAIL API
     ========================================================== */
    public function freeCourse()
    {
        $course = Course::where('course_type', 'free')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course
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
            'data' => $course
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
            'data' => $course
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
            'data' => $course
        ]);
    }

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
                'testimonials:id,course_id,name,content,rating,image'
            ])
            ->where('course_type', 'free')
            ->where('id', $id)
            ->when($userId, function ($query) use ($userId) {
                $query->withExists([
                    'courseUsers as is_bought' => function ($q) use ($userId) {
                        $q->where('auth_id', $userId)
                            ->whereIn('status', ['active', 'ongoing', 'completed']);
                    }
                ]);
            }, function ($query) {
                // guest user → always false
                $query->selectRaw('courses.*, false as is_bought');
            })
            ->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course
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
                'testimonials:id,course_id,name,content,rating,image'
            ])
            ->where('course_type', 'paid')
            ->find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course
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
                'testimonials:id,course_id,name,content,rating,image'
            ])
            ->where('id', $id)
            ->when($userId, function ($query) use ($userId) {
                $query->withExists([
                    'courseUsers as is_bought' => function ($q) use ($userId) {
                        $q->where('auth_id', $userId)
                            ->whereIn('status', ['active', 'ongoing', 'completed']);
                    }
                ]);
            }, function ($query) {
                // guest user → always false
                $query->selectRaw('courses.*, false as is_bought');
            })
            ->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course
        ]);
    }

    public function comboCourse()
    {
        $course = Course::where('course_type', 'combo')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course
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
                            'testimonials:id,course_id,name,content,rating,image'
                        ]);
                }
            ])
            ->find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Combo course not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course
        ]);
    }

    public function liveCourse()
    {
        $course = Course::where('course_type', 'live')
            ->where('status', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $course
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
                'testimonials:id,course_id,name,content,rating,image'
            ])
            ->where('course_type', 'live')
            ->find($id);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $course
        ]);
    }

    /* ==========================================================
     |  QUIZ MANAGEMENT
     ========================================================== */
    public function getVideoQuizzes($id)
    {
        try {
            $videoId = decrypt($id);
            $quizzes = CourseQuiz::where('course_video_id', $videoId)->latest()->get();

            return response()->json([
                'status' => true,
                'quizzes' => $quizzes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeVideoQuiz(Request $request, $id)
    {
        try {
            $videoId = decrypt($id);

            $request->validate([
                'question' => 'required|string',
                'option1' => 'required|string',
                'option2' => 'required|string',
                'option3' => 'required|string',
                'answer' => 'required|string',
            ]);

            $quiz = CourseQuiz::create([
                'course_video_id' => $videoId,
                'question' => $request->question,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'answer' => $request->answer,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Quiz question added successfully',
                'quiz' => $quiz
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteQuiz($id)
    {
        try {
            $quiz = CourseQuiz::findOrFail($id);
            $quiz->delete();

            return response()->json([
                'status' => true,
                'message' => 'Quiz question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
                $hasSubmitted = !empty($previousResult);

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
                'last_position' => $lastPosition
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateVideoProgress(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'course_video_id' => 'required|exists:course_videos,id',
                'last_position' => 'required|numeric',
                'user_id' => 'nullable'
            ]);

            if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $userId = Auth::id() ?: $request->user_id;
            if (!$userId) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
            }

            $progress = CourseVideoProgress::updateOrCreate(
                ['auth_id' => $userId, 'course_video_id' => $request->course_video_id],
                ['last_position' => $request->last_position]
            );

            return response()->json(['status' => true, 'message' => 'Progress updated']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /* ==========================================================
     |  PRACTICE MANAGEMENT
     ========================================================== */
    public function managePractices()
    {
        $practices = CoursePractice::latest()->get();

        return view('courses.practices.index', compact('practices'));
    }

    public function storeCoursePractice(Request $request)
    {
        try {
            $request->validate([
                'language' => 'required|string',
                'question' => 'required|string',
                'input' => 'nullable|string',
                'actual_output' => 'nullable|string',
            ]);

            $practice = CoursePractice::create([
                'course_id' => null,  // Removed dependency
                'language' => $request->language,
                'main_title' => $request->main_title,
                'question' => $request->question,
                'input' => $request->input,
                'actual_output' => $request->actual_output,
                'status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Practice question added successfully',
                'practice' => $practice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCoursePractice(Request $request, $id)
    {
        try {
            $request->validate([
                'language' => 'required|string',
                'question' => 'required|string',
                'input' => 'nullable|string',
                'actual_output' => 'nullable|string',
            ]);

            $practice = CoursePractice::findOrFail($id);
            $practice->update([
                'language' => $request->language,
                'main_title' => $request->main_title,
                'question' => $request->question,
                'input' => $request->input,
                'actual_output' => $request->actual_output,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Practice question updated successfully',
                'practice' => $practice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePracticeStatus(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:course_practices,id',
                'status' => 'required|in:0,1',
            ]);

            $practice = CoursePractice::findOrFail($request->id);
            $practice->update(['status' => $request->status]);

            return response()->json([
                'status' => true,
                'message' => $request->status == 1
                    ? 'Practice activated successfully'
                    : 'Practice deactivated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePractice($id)
    {
        try {
            $practice = CoursePractice::findOrFail($id);
            $practice->delete();

            return response()->json([
                'status' => true,
                'message' => 'Practice question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function videoPracticeApi(Request $request)
    {
        try {
            // 1. Get user login id from request (for app connection)
            $userId = $request->user_id;

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User ID is required'
                ], 400);
            }

            // Optional: Verify user exists in Auth table
            $userExists = \App\Models\Auth::where('id', $userId)->exists();
            if (!$userExists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Student not found'
                ], 404);
            }

            // 2. Handle single practice details if practice_id is provided
            $practiceId = $request->practice_id;

            if ($practiceId) {
                $practice = CoursePractice::where('id', $practiceId)
                    ->where('status', 1)
                    ->select(['id', 'main_title', 'question', 'input', 'actual_output', 'status'])
                    ->first();

                if (!$practice) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Practice not found or inactive',
                        'data' => null
                    ], 404);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Practice details fetched successfully',
                    'data' => $practice
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
                    'data' => []
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Practices list fetched successfully',
                'data' => $practices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
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
            \Illuminate\Support\Facades\Log::error("CourseController Validation Failed", $validator->errors()->toArray());
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = Auth::id() ?: $request->user_id;  // Support both session and API

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not authenticated'
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
                if ($selectedIdx == '1')
                    $selectedText = $quiz->option1;
                elseif ($selectedIdx == '2')
                    $selectedText = $quiz->option2;
                elseif ($selectedIdx == '3')
                    $selectedText = $quiz->option3;

                // Resolve Correct Answer Text
                $correctText = $quiz->answer;
                if ($quiz->answer == '1')
                    $correctText = $quiz->option1;
                elseif ($quiz->answer == '2')
                    $correctText = $quiz->option2;
                elseif ($quiz->answer == '3')
                    $correctText = $quiz->option3;

                $isCorrect = (trim(strtolower($selectedText ?? '')) === trim(strtolower($correctText ?? '')));

                if ($isCorrect) {
                    $correctAnswersCount++;
                }

                $detailedAnswers[] = [
                    'quiz_id' => $quiz->id,
                    'question' => $quiz->question,
                    'selected' => $selectedText,
                    'correct_answer' => $correctText,
                    'is_correct' => $isCorrect
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
                'data' => $result
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
