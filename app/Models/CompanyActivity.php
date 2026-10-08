<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyActivity extends Model
{
    protected $fillable = [
        'company_id', 'staff_id', 'tag_id',
        'type', 'direction', 'outcome',
        'note', 'next_followup_date'
    ];

    protected $casts = [
        'next_followup_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function tag()
    {
        return $this->belongsTo(LeadTag::class, 'tag_id');
    }
}
