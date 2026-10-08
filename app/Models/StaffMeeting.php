<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffMeeting extends Model
{
    protected $fillable = [
        'meeting_type',
        'title',
        'date',
        'time',
        'staff_id',
        'agenda',
        'meet_link'
    ];
}
