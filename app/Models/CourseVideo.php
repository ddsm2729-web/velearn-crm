<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    use HasFactory;

    protected $table = 'course_videos';

    protected $fillable = [
        'course_id',
        'title',
        'video',
        'subtitle_en',
        'subtitle_ta',
        'script',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function quizzes()
    {
        return $this->hasMany(CourseQuiz::class, 'course_video_id');
    }

    public function progresses()
    {
        return $this->hasMany(CourseVideoProgress::class, 'course_video_id');
    }

    public function summaries()
    {
        return $this->hasMany(CourseVideoSummary::class, 'course_video_id');
    }

}