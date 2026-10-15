<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffMeeting extends Model
{
    use HasFactory;

    protected $table = 'staff_meetings';

    protected $fillable = [
        'meeting_type',
        'title',
        'date',
        'time',
        'staff_id',
        'student_id',
        'admission_id',
        'student_name',
        'agenda',
        'meet_link',
        'zoom_meeting_id',
        'zoom_meet_link',
        'zoom_start_url',
        'zoom_password',
        'created_by',
        'status',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class, 'admission_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
