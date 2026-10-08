<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'batch_code',
        'batch_type',
        'batch_track',
        'batch_name',
        'instructor_id',
        'start_date',
        'end_date',
        'batch_from_time',
        'batch_to_time',
        'class_duration',
        'join_deadline',
        'joined_student_count',
        'meeting_link',
        'max_students',
        'status',
        'batch_status',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Staff::class, 'instructor_id');
    }

    public function mini_projects()
    {
        return $this->hasMany(MiniProject::class);
    }

    public function main_projects()
    {
        return $this->hasMany(MainProject::class);
    }

    public function schedules()
    {
        return $this->hasMany(BatchSchedule::class);
    }
}
