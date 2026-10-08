<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_new' => 'boolean',
            'sent_to_hr_at' => 'datetime',
        ];
    }

    public function opening()
    {
        return $this->belongsTo(CurrentOpening::class, 'opening_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courseUser()
    {
        return $this->belongsTo(CourseUser::class, 'course_user_id');
    }
}
