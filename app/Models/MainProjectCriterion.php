<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainProjectCriterion extends Model
{
    use HasFactory;

    protected $table = 'main_project_criteria';

    protected $fillable = [
        'main_project_id',
        'criterion',
        'maximum_mark',
    ];

    protected $casts = [
        'maximum_mark' => 'decimal:2',
    ];

    public function project()
    {
        return $this->belongsTo(MainProject::class, 'main_project_id');
    }

}