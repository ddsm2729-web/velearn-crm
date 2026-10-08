<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadPipelineLog extends Model
{
    protected $fillable = [
        'lead_id', 'changed_by',
        'old_status_id', 'new_status_id',
        'old_tag_id', 'new_tag_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(Staff::class, 'changed_by');
    }

    public function oldStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'new_status_id');
    }

    public function oldTag()
    {
        return $this->belongsTo(LeadTag::class, 'old_tag_id');
    }

    public function newTag()
    {
        return $this->belongsTo(LeadTag::class, 'new_tag_id');
    }
}
