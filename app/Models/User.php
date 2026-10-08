<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'education',
        'designation',
        'address',
        'email',
        'image',
        'password',
        'phonenumber',
        'secondary_phone',
        'lead_source',
        'fcm_token',
        'state_id',
        'city',
        'zip_code',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth'     => 'date',
            'password'          => 'hashed',
        ];
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function courseUsers()
    {
        return $this->hasMany(CourseUser::class, 'auth_id');
    }

    public function courseVideoProgresses()
    {
        return $this->hasMany(CourseVideoProgress::class, 'auth_id');
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'user_id');
    }

    public function mainProjectSubmissions()
    {
        return $this->hasMany(MainProjectSubmission::class, 'user_id');
    }

    public function miniProjectSubmissions()
    {
        return $this->hasMany(MiniProjectSubmission::class, 'user_id');
    }

    public function supportTickets()
    {
        return $this->hasMany(SupportTicket::class, 'user_id');
    }

    public function webinarUsers()
    {
        return $this->hasMany(WebinarUser::class, 'auth_id');
    }

    public function enrolledWebinars()
    {
        return $this->belongsToMany(Webinar::class, 'webinar_users', 'auth_id', 'webinar_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function staffProfile()
    {
        return $this->hasOne(Staff::class, 'auth_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        \Illuminate\Support\Facades\Mail::mailer('brevo')->to($this->email)->send(new \App\Mail\ResetPasswordMail($resetUrl));
    }
}
