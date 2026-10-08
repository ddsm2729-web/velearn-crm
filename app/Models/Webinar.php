<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'category',
        'course_level',
        'date',
        'from_time',
        'to_time',
        'meeting_link',
        'seats',
        'description',
        'professor',
        'professor_position',
        'professor_experience',
        'requirements',
        'status',
        'zoom_meeting_id'
    ];

    /* ================= Relationships ================= */

    // Webinar has many titles
    public function titles()
    {
        return $this->hasMany(WebinarTitle::class);
    }

    // Webinar has many descriptions
    public function descriptions()
    {
        return $this->hasMany(WebinarDescription::class);
    }

    // Users registered for this webinar
    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'webinar_users',
            'webinar_id',
            'auth_id'
        )->withPivot('status')->withTimestamps();
    }
}
