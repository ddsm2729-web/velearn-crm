<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $table = 'support_tickets';

    protected $fillable = [
        'user_id',
        'reference_id',
        'type',
        'category',
        'subject',
        'title',
        'description',
        'preferred_date',
        'preferred_slot',
        'attachment',
        'assigned_staff_id',
        'allocated_date',
        'allocated_slot',
        'notes',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'allocated_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(Staff::class, 'assigned_staff_id');
    }

    public function allocations()
    {
        return $this->hasMany(SupportTicketAllocation::class, 'support_ticket_id');
    }

}