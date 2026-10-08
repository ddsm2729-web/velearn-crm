<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentCriterion extends Model
{
    use HasFactory;

    protected $table = 'assignment_criteria';

    protected $fillable = [
        'assignment_id',
        'criterion',
        'maximum_mark',
    ];

    protected $casts = [
        'maximum_mark' => 'decimal:2',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }

}