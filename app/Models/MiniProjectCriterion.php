<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiniProjectCriterion extends Model
{
    use HasFactory;

    protected $table = 'mini_project_criteria';

    protected $fillable = [
        'mini_project_id',
        'criterion',
        'maximum_mark',
    ];

    protected $casts = [
        'maximum_mark' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(MiniProject::class, 'mini_project_id');
    }

}