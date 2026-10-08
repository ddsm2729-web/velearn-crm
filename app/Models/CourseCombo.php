<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCombo extends Model
{
    use HasFactory;

    protected $table = 'course_combos';

    protected $fillable = [
        'course_id',
        'class_id',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}