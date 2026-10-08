<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseTestimonial extends Model
{
    use HasFactory;

    protected $table = 'course_testimonials';

    protected $fillable = [
        'course_id',
        'name',
        'image',
        'content',
        'rating',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}