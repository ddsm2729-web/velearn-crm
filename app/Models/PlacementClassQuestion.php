<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementClassQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['placement_class_id', 'question', 'marks'];

    public function placementClass()
    {
        return $this->belongsTo(PlacementClass::class);
    }

    public function options()
    {
        return $this->hasMany(PlacementClassOption::class);
    }
}
