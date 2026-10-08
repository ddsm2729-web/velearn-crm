<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementEvaluationAnswer extends Model
{
    protected $fillable = ['placement_evaluation_question_id', 'answer', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(PlacementEvaluationQuestion::class, 'placement_evaluation_question_id');
    }
}
