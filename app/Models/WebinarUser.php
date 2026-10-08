<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarUser extends Model
{
    use HasFactory;

    protected $table = 'webinar_users';

    protected $fillable = [
        'webinar_id',
        'auth_id',
        'name',
        'email',
        'phone',
        'status',
    ];

    public function webinar()
    {
        return $this->belongsTo(Webinar::class, 'webinar_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'auth_id');
    }
}
