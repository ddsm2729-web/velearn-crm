<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEvaluationOption extends Model
{
    protected $fillable = ['course_evaluation_question_id', 'option', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(CourseEvaluationQuestion::class, 'course_evaluation_question_id');
    }
}
