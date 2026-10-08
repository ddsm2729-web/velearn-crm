<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallLog extends Model
{
    use HasFactory;

    protected $table = 'call_logs';

    protected $fillable = [
        'contact_id',
        'lead_id',
        'caller_number',
        'call_duration',
        'department',
        'recording_url',
        'status',
        'call_start',
        'call_end',
        'full_log',
        'note',
        'zoom_meet_link',
        'zoom_meeting_id',
        'followup_note',
        'demo_date',
        'demo_time',
        'demo_status',
        'followup_date',
        'followup_time',
        'assigned_staff_id',
        'staff_id',
        'followup_status',
        'direction',
        'lead_tag',
    ];

    protected $casts = [
        'full_log' => 'array',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

}