<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'published_date',
        'image',
        'status',
    ];

    /**
     * One blog has one blog info
     */
    public function info()
    {
        return $this->hasMany(BlogInfo::class);
    }
}
