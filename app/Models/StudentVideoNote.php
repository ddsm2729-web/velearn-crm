<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentVideoNote extends Model
{
    protected $fillable = [
        'student_id',
        'batch_topic_log_id',
        'title',
        'content',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function batchTopicLog()
    {
        return $this->belongsTo(BatchTopicLog::class, 'batch_topic_log_id');
    }
}
