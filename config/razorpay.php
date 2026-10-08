<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Razorpay API Credentials
    |--------------------------------------------------------------------------
    |
    | Get your API Key ID and Secret Key from Razorpay Dashboard:
    | Settings -> API Keys
    | https://dashboard.razorpay.com/app/keys
    |
    */

    'key' => env('RAZORPAY_KEY', 'rzp_test_xxxxxxxxxxxxxx'),

    'secret' => env('RAZORPAY_SECRET', 'xxxxxxxxxxxxxxxxxxxxxxxx'),

    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET', ''),

    'currency' => env('RAZORPAY_CURRENCY', 'INR'),
];
