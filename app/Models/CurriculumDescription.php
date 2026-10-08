<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurriculumDescription extends Model
{
    use HasFactory;

    protected $table = 'curriculum_descriptions';

    protected $fillable = [
        'course_curriculum_id',
        'description',
    ];

    protected $casts = [
    ];

    public function curriculum()
    {
        return $this->belongsTo(CourseCurriculum::class, 'course_curriculum_id');
    }

}