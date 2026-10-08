<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $table = 'admissions';

    protected $fillable = [
        'lead_id',
        'contact_id',
        'course_id',
        'batch_id',
        'batch_type',
        'course_fees',
        'payment_mode',
        'status',
    ];

    protected $casts = [
        'course_fees' => 'decimal:2',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'admission_id');
    }

    public function sessionBookings()
    {
        return $this->hasMany(SessionBooking::class, 'admission_id');
    }

}