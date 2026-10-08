<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProjectScoreBreakdown extends Model
{
    use HasFactory;

    protected $table = 'main_project_score_breakdowns';

    protected $fillable = [
        'main_project_submission_id',
        'criterion',
        'max_score',
        'obtained_score',
    ];

    protected $casts = [
        'max_score' => 'decimal:2',
        'obtained_score' => 'decimal:2',
    ];

    public function submission()
    {
        return $this->belongsTo(MainProjectSubmission::class, 'main_project_submission_id');
    }

}