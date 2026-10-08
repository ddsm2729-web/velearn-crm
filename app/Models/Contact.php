<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';

    protected $fillable = [
        'register_number',
        'my_operator_id',
        'caller_number',
        'secondary_number',
        'name',
        'email',
        'location',
        'source',
        'conversation_start_date',
        'conversation_end_date',
    ];

    protected $casts = [
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'contact_id');
    }

    public function callLogs()
    {
        return $this->hasMany(CallLog::class, 'contact_id');
    }

}