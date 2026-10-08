<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicketAllocation extends Model
{
    use HasFactory;

    protected $table = 'support_ticket_allocations';

    protected $fillable = [
        'support_ticket_id',
        'staff_id',
        'allocated_date',
        'allocated_slot',
        'notes',
        'status',
        'zoom_meet_link',
        'zoom_meeting_id',
    ];

    protected $casts = [
        'allocated_date' => 'date',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

}