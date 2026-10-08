<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadActivity extends Model
{
    protected $fillable = [
        'lead_id', 'staff_id', 'tag_id', 'status_id',
        'type', 'direction', 'outcome',
        'note', 'next_action', 'next_followup_date'
    ];

    protected $casts = [
        'next_followup_date' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function tag()
    {
        return $this->belongsTo(LeadTag::class, 'tag_id');
    }

    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
}
