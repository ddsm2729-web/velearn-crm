<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideoProgress extends Model
{
    use HasFactory;

    protected $table = 'course_video_progress';

    protected $fillable = [
        'auth_id',
        'course_video_id',
        'last_position',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function video()
    {
        return $this->belongsTo(CourseVideo::class, 'course_video_id');
    }

}