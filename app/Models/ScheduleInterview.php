<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleInterview extends Model
{
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function courseUser()
    {
        return $this->hasOne(CourseUser::class, 'auth_id', 'user_id');
    }
}
