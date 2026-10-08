<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSkill extends Model
{
    use HasFactory;

    protected $table = 'course_skills';

    protected $fillable = [
        'course_id',
        'name',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}