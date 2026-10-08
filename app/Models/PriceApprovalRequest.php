<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceApprovalRequest extends Model
{
    use HasFactory;

    protected $table = 'price_approval_requests';

    protected $fillable = [
        'course_id',
        'requested_by',
        'requested_price',
        'reason',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'requested_price' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

}