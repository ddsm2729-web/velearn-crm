<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $table = 'assignment_submissions';

    protected $fillable = [
        'assignment_id',
        'user_id',
        'status',
        'file_path',
        'attempts',
        'score',
        'attempts_history',
        'reupload_approved',
        'grade',
        'feedback',
        'evaluated_by',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'attempts_history' => 'array',
        'reupload_approved' => 'boolean',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function evaluator()
    {
        return $this->belongsTo(Staff::class, 'evaluated_by');
    }

    public function scoreBreakdowns()
    {
        return $this->hasMany(AssignmentScoreBreakdown::class, 'assignment_submission_id');
    }

}