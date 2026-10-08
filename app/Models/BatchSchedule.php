<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatchSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'date',
        'start_time',
        'end_time',
        'meeting_link',
        'zoom_meeting_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
