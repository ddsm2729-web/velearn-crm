<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementClassOption extends Model
{
    use HasFactory;

    protected $fillable = ['placement_class_question_id', 'option', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(PlacementClassQuestion::class, 'placement_class_question_id');
    }
}
