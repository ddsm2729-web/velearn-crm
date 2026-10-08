<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentScoreBreakdown extends Model
{
    use HasFactory;

    protected $table = 'assignment_score_breakdowns';

    protected $fillable = [
        'assignment_submission_id',
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
        return $this->belongsTo(AssignmentSubmission::class, 'assignment_submission_id');
    }

}