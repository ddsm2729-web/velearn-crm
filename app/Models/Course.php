<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Course extends Model
{
    use HasFactory, Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $table = 'courses';

    protected $fillable = [
        'course_code',
        'syllabus_pdf',
        'release_date',
        'image',
        'course_pdf',
        'slug',
        'title',
        'sub_description',
        'with_certificate',
        'recorded_content',
        'language',
        'cour_language',
        'categories',
        'about_course',
        'mrp_price',
        'buy_price',
        'single_student_price',
        'group_student_price',
        'offer_price',
        'course_type',
        'combo_course_type',
        'status',
        'duration',
        'end_card_title',
        'end_card_description',
        'live_course_type',
        'split_count',
        'combo_type',
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
    ];

    protected $casts = [
        'with_certificate' => 'boolean',
        'mrp_price' => 'float',
        'buy_price' => 'float',
        'single_student_price' => 'float',
        'group_student_price' => 'float',
        'offer_price' => 'float',
    ];

    public function benefits()
    {
        return $this->hasMany(CourseBenefit::class, 'course_id');
    }

    public function combos()
    {
        return $this->hasMany(CourseCombo::class, 'course_id');
    }

    /**
     * The individual courses included in this combo course.
     * Uses the course_combos pivot table: course_id = combo, class_id = included course.
     */
    public function includedCourses()
    {
        return $this->belongsToMany(Course::class, 'course_combos', 'course_id', 'class_id');
    }

    public function curricula()
    {
        return $this->hasMany(CourseCurriculum::class, 'course_id');
    }

    public function faqs()
    {
        return $this->hasMany(CourseFaq::class, 'course_id');
    }

    public function leans()
    {
        return $this->hasMany(CourseLean::class, 'course_id');
    }

    public function practices()
    {
        return $this->hasMany(CoursePractice::class, 'course_id');
    }

    public function requirements()
    {
        return $this->hasMany(CourseRequirement::class, 'course_id');
    }

    public function skills()
    {
        return $this->hasMany(CourseSkill::class, 'course_id');
    }

    public function testimonials()
    {
        return $this->hasMany(CourseTestimonial::class, 'course_id');
    }

    public function users()
    {
        return $this->hasMany(CourseUser::class, 'course_id');
    }

    public function videos()
    {
        return $this->hasMany(CourseVideo::class, 'course_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'course_id');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'course_id');
    }

    public function mainProjects()
    {
        return $this->hasMany(MainProject::class, 'course_id');
    }

    public function miniProjects()
    {
        return $this->hasMany(MiniProject::class, 'course_id');
    }

    public function priceApprovalRequests()
    {
        return $this->hasMany(PriceApprovalRequest::class, 'course_id');
    }

    public function leads()
    {
        return $this->belongsToMany(Lead::class)->withTimestamps();
    }

    public function evaluationQuestions()
    {
        return $this->hasMany(CourseEvaluationQuestion::class, 'course_id');
    }

    public function eligibilityTests()
    {
        return $this->hasMany(CourseEligibilityTest::class, 'course_id');
    }
}
