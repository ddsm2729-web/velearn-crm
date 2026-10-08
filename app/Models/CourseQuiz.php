<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseQuiz extends Model
{
    use HasFactory;

    protected $table = 'course_quizzes';

    protected $fillable = [
        'course_video_id',
        'question',
        'option1',
        'option2',
        'option3',
        'answer',
    ];

    protected $casts = [
    ];

    public function video()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }

}