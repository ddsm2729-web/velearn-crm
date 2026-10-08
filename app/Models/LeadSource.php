<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class LeadSource extends Model
{
    use HasFactory;

    protected $table = 'lead_sources';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'lead_source_role', 'lead_source_id', 'role_id');
    }
}