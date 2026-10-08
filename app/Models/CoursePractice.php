<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePractice extends Model
{
    use HasFactory;

    protected $table = 'course_practices';

    protected $fillable = [
        'main_title',
        'language',
        'level',
        'question',
        'input',
        'actual_output',
        'status',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}