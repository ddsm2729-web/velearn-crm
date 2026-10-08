<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniProject extends Model
{
    use HasFactory;

    protected $table = 'mini_projects';

    protected $fillable = [
        'course_id',
        'batch_id',
        'module',
        'title',
        'description',
        'document',
        'due_date',
        'reveal_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
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
        return $this->hasMany(MiniProjectCriterion::class, 'mini_project_id');
    }

    public function submissions()
    {
        return $this->hasMany(MiniProjectSubmission::class, 'mini_project_id');
    }

}