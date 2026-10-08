<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';

    protected $fillable = [
        'state_name',
    ];

    protected $casts = [
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'state_id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'state_id');
    }

    public function staff()
    {
        return $this->hasMany(Staff::class, 'state_id');
    }

}