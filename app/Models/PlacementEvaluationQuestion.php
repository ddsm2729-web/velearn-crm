<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementEvaluationQuestion extends Model
{
    protected $fillable = ['placement_evaluation_id', 'question', 'type', 'marks'];

    public function answers()
    {
        return $this->hasMany(PlacementEvaluationAnswer::class, 'placement_evaluation_question_id');
    }
}
