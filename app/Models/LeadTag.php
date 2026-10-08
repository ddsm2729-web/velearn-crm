<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class LeadTag extends Model
{
    use HasFactory;

    protected $table = 'lead_tags';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'lead_tag_role', 'lead_tag_id', 'role_id');
    }
}