<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebinarDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'webinar_id',
        'webinar_title_id',
        'description',
    ];

    /* ================= Relationships ================= */

    // Description belongs to webinar
    public function webinar()
    {
        return $this->belongsTo(Webinar::class);
    }

    // Description belongs to a title
    public function title()
    {
        return $this->belongsTo(WebinarTitle::class, 'webinar_title_id');
    }
}
