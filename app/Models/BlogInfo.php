<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'descriptions',
    ];

    /**
     * Blog info belongs to a blog
     */
    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
