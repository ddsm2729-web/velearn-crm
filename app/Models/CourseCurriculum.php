<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseCurriculum extends Model
{
    use HasFactory;

    protected $table = 'course_curricula';

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

    public function descriptions()
    {
        return $this->hasMany(CurriculumDescription::class, 'course_curriculum_id');
    }

}