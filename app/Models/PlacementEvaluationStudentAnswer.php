<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementEvaluationStudentAnswer extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'placement_evaluation_id', 
        'placement_evaluation_question_id', 'selected_answer_id', 
        'text_answer', 'marks_obtained'
    ];
}
