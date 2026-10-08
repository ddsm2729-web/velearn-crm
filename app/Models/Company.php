<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = [];

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function leadTag()
    {
        return $this->belongsTo(LeadTag::class, 'lead_tag_id');
    }

    public function openings()
    {
        return $this->hasMany(CurrentOpening::class);
    }

    public function activities()
    {
        return $this->hasMany(CompanyActivity::class)->latest();
    }
}
