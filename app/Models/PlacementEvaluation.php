<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementEvaluation extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'test_timing',
        'correction_type'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function questions()
    {
        return $this->hasMany(PlacementEvaluationQuestion::class, 'placement_evaluation_id');
    }
}
