<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'phone', 'whatsapp', 'email', 'location',
        'lead_source_id', 'lead_status_id', 'assigned_to',
        'priority', 'expected_close_date', 'converted_date'
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'converted_date' => 'date',
    ];

    public function source() { return $this->belongsTo(LeadSource::class, 'lead_source_id'); }
    public function status() { return $this->belongsTo(LeadStatus::class, 'lead_status_id'); }
    public function assignee() { return $this->belongsTo(Staff::class, 'assigned_to'); }
    
    public function activities() { return $this->hasMany(LeadActivity::class)->orderBy('created_at', 'desc'); }
    
    public function latestFollowupActivity() {
        return $this->hasOne(LeadActivity::class)->ofMany([
            'id' => 'max',
        ], function ($query) {
            $query->whereNotNull('next_followup_date');
        });
    }

    public function latestActivity() {
        return $this->hasOne(LeadActivity::class)->latestOfMany();
    }

    public function tags()
    {
        return $this->belongsToMany(LeadTag::class, 'lead_lead_tag', 'lead_id', 'lead_tag_id')->withTimestamps();
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class)->orderBy('call_start', 'desc');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class)->withTimestamps();
    }

    public function admission()
    {
        return $this->hasOne(Admission::class, 'lead_id');
    }
}
