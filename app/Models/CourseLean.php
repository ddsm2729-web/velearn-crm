<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLean extends Model
{
    use HasFactory;

    protected $table = 'course_leans';

    protected $fillable = [
        'course_id',
        'title',
        'description',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}