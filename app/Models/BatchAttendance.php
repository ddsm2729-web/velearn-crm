<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchAttendance extends Model
{
    use HasFactory;

    protected $table = 'batch_attendances';

    protected $fillable = [
        'batch_id',
        'batch_schedule_id',
        'student_id',
        'student_name',
        'student_email',
        'zoom_meeting_id',
        'date',
        'meeting_duration_minutes',
        'attended_duration_minutes',
        'attended_duration_seconds',
        'attendance_percentage',
        'status', // 'Present', 'Half Day', 'Leave'
        'remarks',
        'join_time',
        'leave_time',
    ];

    protected $casts = [
        'date' => 'date',
        'attendance_percentage' => 'decimal:2',
        'meeting_duration_minutes' => 'integer',
        'attended_duration_minutes' => 'integer',
        'attended_duration_seconds' => 'integer',
        'join_time' => 'datetime',
        'leave_time' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function batchSchedule()
    {
        return $this->belongsTo(BatchSchedule::class, 'batch_schedule_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
