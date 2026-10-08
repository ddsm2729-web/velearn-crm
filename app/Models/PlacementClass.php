<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PlacementClass extends Model
{
    protected $fillable = ['day', 'title', 'video_url', 'video_path'];

    /**
     * @var list<string>
     */
    protected $appends = ['video'];

    /**
     * Public URL of the uploaded video (falls back to the legacy video_url).
     */
    protected function video(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->video_path) {
                return asset($this->video_path);
            }

            return $this->video_url;
        });
    }

    public function questions()
    {
        return $this->hasMany(PlacementClassQuestion::class);
    }
}
