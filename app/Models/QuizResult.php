<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    protected $fillable = [
        'auth_id',
        'course_video_id',
        'total_questions',
        'correct_answers',
        'score',
        'answers'
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}
