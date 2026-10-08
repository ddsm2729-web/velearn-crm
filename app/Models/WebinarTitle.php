<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarTitle extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'title',
    ];

    /* ================= Relationships ================= */

    // Title belongs to a webinar
    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    // Title has many descriptions
    public function descriptions()
    {
        return $this->hasMany(WebinarDescription::class);
    }
}
