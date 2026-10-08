<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class Staff extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'staff';

    protected $fillable = [
        'auth_id',
        'first_name',
        'last_name',
        'email',
        'primary_phone',
        'secondary_phone',
        'gender',
        'role_id',
        'date_of_birth',
        'education',
        'address',
        'city',
        'pincode',
        'state_id',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function getNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function schedules()
    {
        return $this->hasMany(StaffSchedule::class, 'staff_id');
    }

    public function ticketAllocations()
    {
        return $this->hasMany(SupportTicketAllocation::class, 'staff_id');
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class, 'staff_id');
    }

    public function sessionBookings()
    {
        return $this->hasMany(SessionBooking::class, 'staff_id');
    }

}