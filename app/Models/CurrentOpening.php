<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrentOpening extends Model
{
    use HasFactory;

    protected $table = 'current_openings';

    protected $fillable = [
        'company_id',
        'job_role',
        'company_mail_id',
        'hr_name',
        'hr_contact_number',
        'hr_email_id',
        'job_location',
        'skills_required',
        'minimum_qualification',
        'experience_required',
        'ctc_salary',
        'number_of_openings',
        'application_deadline',
        'interview_mode',
        'interview_process',
        'bond_service_agmt',
        'opening_status',
        'notes',
        'job_description_type',
        'job_description_file',
        'job_description_text',
    ];

    protected $casts = [
        'application_deadline' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(PlacementApplication::class, 'opening_id');
    }
}