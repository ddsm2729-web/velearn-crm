<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentSuccessController extends Controller
{
    public function success(Request $request)
    {
        // Razorpay appends razorpay_payment_id, razorpay_payment_link_id, etc. to the URL
        $paymentId = $request->query('razorpay_payment_id');
        $paymentLinkId = $request->query('razorpay_payment_link_id');
        $status = $request->query('razorpay_payment_link_status'); // e.g., 'paid'

        if ($paymentLinkId && strtolower($status) === 'paid') {
            $payment = \App\Models\Payment::where('razorpay_payment_link_id', $paymentLinkId)->first();
            if ($payment && $payment->status !== 'Paid') {
                $payment->update([
                    'status' => 'Paid',
                    'razorpay_payment_id' => $paymentId,
                ]);

                // Convert lead to student and allocate batch on first payment success
                if ($payment->admission) {
                    \App\Services\StudentConversionService::convertLeadToStudent($payment->admission);
                }
            }
        }

        return view('payments.success', compact('paymentId', 'paymentLinkId', 'status'));
    }
}
