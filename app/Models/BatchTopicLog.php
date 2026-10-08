<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchTopicLog extends Model
{
    protected $table = 'batch_topic_logs';

    protected $fillable = [
        'batch_id',
        'batch_schedule_id',
        'course_curriculum_id',
        'description',
        'is_completed',
        'status',
        'date',
        'video',
        'note',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function schedule()
    {
        return $this->belongsTo(BatchSchedule::class, 'batch_schedule_id');
    }

    public function curriculum()
    {
        return $this->belongsTo(CourseCurriculum::class, 'course_curriculum_id');
    }
}
