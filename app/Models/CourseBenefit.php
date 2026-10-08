<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseBenefit extends Model
{
    use HasFactory;

    protected $table = 'course_benefits';

    protected $fillable = [
        'course_id',
        'description',
    ];

    protected $casts = [
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

}