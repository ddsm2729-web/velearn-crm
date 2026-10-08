<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProject extends Model
{
    use HasFactory;

    protected $table = 'main_projects';

    protected $fillable = [
        'course_id',
        'batch_id',
        'module',
        'title',
        'description',
        'document',
        'review_1_title',
        'review_1_deadline',
        'review_2_title',
        'review_2_deadline',
        'review_3_title',
        'review_3_deadline',
        'reveal_date',
        'status',
    ];

    protected $casts = [
        'reveal_date' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function criteria()
    {
        return $this->hasMany(MainProjectCriterion::class, 'main_project_id');
    }

    public function submissions()
    {
        return $this->hasMany(MainProjectSubmission::class, 'main_project_id');
    }

}