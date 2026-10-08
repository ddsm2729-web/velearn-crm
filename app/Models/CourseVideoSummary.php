<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideoSummary extends Model
{
    use HasFactory;

    protected $table = 'course_video_summeries';

    protected $fillable = [
        'course_video_id',
        'summary',
    ];

    protected $casts = [
    ];

    public function video()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }

}