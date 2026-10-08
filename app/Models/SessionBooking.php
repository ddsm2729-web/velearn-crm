<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
    use HasFactory;

    protected $table = 'session_bookings';

    protected $fillable = [
        'type',
        'staff_id',
        'admission_id',
        'date',
        'time',
        'topic',
        'notes',
        'status',
        'zoom_meeting_id',
        'zoom_meet_link',
    ];

    protected $casts = [
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

}