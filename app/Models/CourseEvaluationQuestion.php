<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEvaluationQuestion extends Model
{
    protected $fillable = ['course_id', 'question', 'marks'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function options()
    {
        return $this->hasMany(CourseEvaluationOption::class);
    }
}
