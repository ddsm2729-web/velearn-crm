<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RazorpayWebhookLog extends Model
{
    use HasFactory;

    protected $table = 'razorpay_webhook_logs';

    protected $fillable = [
        'event',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_refund_id',
        'status',
        'payload',
        'signature',
        'signature_valid',
        'error_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'signature_valid' => 'boolean',
    ];

}