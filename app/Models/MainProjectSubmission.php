<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProjectSubmission extends Model
{
    use HasFactory;

    protected $table = 'main_project_submissions';

    protected $fillable = [
        'main_project_id',
        'user_id',
        'review_number',
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

    public function project()
    {
        return $this->belongsTo(MainProject::class, 'main_project_id');
    }

    public function main_project()
    {
        return $this->belongsTo(MainProject::class, 'main_project_id');
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
        return $this->hasMany(MainProjectScoreBreakdown::class, 'main_project_submission_id');
    }

}