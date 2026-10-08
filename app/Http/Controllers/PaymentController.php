<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use App\Models\Admission;
use App\Models\Payment;
use App\Models\Lead;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentLinkMail;

class PaymentController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $statusFilter = $request->input('status');
        $searchFilter = $request->input('search');
        $courseFilter = $request->input('course_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $metricsQuery = Payment::query();
        if ($startDate && $endDate) {
            $metricsQuery->whereBetween('payment_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        $collected = (clone $metricsQuery)->where(function($query) {
            $query->where('status', 'Paid')->orWhere('status', 'paid');
        })->sum('amount');
        
        $pending = (clone $metricsQuery)->where(function($query) {
            $query->where('status', 'pending')->orWhere('status', 'unpaid');
        })->sum('amount');
        
        $failed = (clone $metricsQuery)->where(function($query) {
            $query->where('status', 'failed')->orWhere('status', 'Failed');
        })->sum('amount');

        $paymentsQuery = Payment::with(['admission.lead', 'admission.contact', 'admission.course']);

        if ($statusFilter) {
            $paymentsQuery->where('status', $statusFilter);
        }

        if ($courseFilter) {
            $paymentsQuery->whereHas('admission', function($q) use ($courseFilter) {
                $q->where('course_id', $courseFilter);
            });
        }

        if ($startDate && $endDate) {
            $paymentsQuery->whereBetween('payment_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($searchFilter) {
            $paymentsQuery->where(function($q) use ($searchFilter) {
                $q->whereHas('admission.contact', function($q2) use ($searchFilter) {
                    $q2->where('name', 'like', "%{$searchFilter}%")
                       ->orWhere('caller_number', 'like', "%{$searchFilter}%")
                       ->orWhere('secondary_number', 'like', "%{$searchFilter}%");
                })->orWhereHas('admission.lead', function($q2) use ($searchFilter) {
                    $q2->where('name', 'like', "%{$searchFilter}%")
                       ->orWhere('phone', 'like', "%{$searchFilter}%");
                });
            });
        }

        $payments = $paymentsQuery->orderByRaw("CASE WHEN LOWER(status) = 'pending' THEN 1 ELSE 2 END")
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $courses = \App\Models\Course::where('course_type', 'live')->select('id', 'title')->orderBy('title')->get();

        return view('payments.index', compact('collected', 'pending', 'failed', 'payments', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'admission_id' => 'required|exists:admissions,id',
            'type' => 'required|in:single,split',
        ]);

        $admissionId = $request->admission_id;
        $admission = Admission::with(['lead', 'contact', 'course'])->findOrFail($admissionId);
        
        $customerName = $admission->contact ? $admission->contact->name : ($admission->lead->name ?? 'Student');
        $customerEmail = $admission->contact ? $admission->contact->email : ($admission->lead->email ?? null);
        $customerPhone = $admission->contact ? $admission->contact->caller_number : ($admission->lead->phone ?? null);

        // Delete existing pending/unpaid payments for this admission before inserting new ones
        Payment::where('admission_id', $admissionId)
            ->whereIn('status', ['pending', 'unpaid', 'failed']) // adjust based on logic
            ->delete();

        if ($request->type === 'single') {
            $request->validate([
                'amount' => 'required|numeric|min:0',
                'payment_date' => 'required|date',
            ]);

            \App\Models\Payment::create([
                'admission_id' => $admissionId,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'status' => 'pending',
                'payment_method' => 'cash', // Default or from request
            ]);
        } elseif ($request->type === 'split') {
            $request->validate([
                'payments' => 'required|array',
                'payments.*.amount' => 'required|numeric|min:0',
                'payments.*.payment_date' => 'required|date',
            ]);

            foreach ($request->payments as $payment) {
                \App\Models\Payment::create([
                    'admission_id' => $admissionId,
                    'amount' => $payment['amount'],
                    'payment_date' => $payment['payment_date'],
                    'status' => 'pending',
                    'payment_method' => 'cash', // Default or from request
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Payment records updated successfully!'
        ]);
    }

    public function generateLink($id)
    {
        $payment = Payment::with('admission.lead', 'admission.contact', 'admission.course')->findOrFail($id);
        
        $contact = $payment->admission->contact;
        $lead = $payment->admission->lead;

        $customerName = ($contact && $contact->name) ? $contact->name : ($lead->name ?? 'Student');
        $customerEmail = ($contact && $contact->email) ? $contact->email : ($lead->email ?? null);
        $customerPhone = ($contact && $contact->caller_number) ? $contact->caller_number : ($lead->phone ?? null);

        if (empty($customerEmail)) {
            return response()->json([
                'status' => false,
                'message' => 'Please fill the email ID for the student before generating the payment link.'
            ]);
        }

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $expiryTime = time() + (24 * 60 * 60); // 24 hours from now

            $linkData = [
                'amount' => $payment->amount * 100, // amount in paise
                'currency' => 'INR',
                'accept_partial' => false,
                'expire_by' => $expiryTime,
                'reference_id' => 'PAY_' . $payment->id . '_' . time(),
                'description' => 'Payment for ' . ($payment->admission->course->title ?? 'Course'),
                'customer' => [
                    'name' => $customerName,
                    'email' => $customerEmail,
                    'contact' => $customerPhone
                ],
                'notify' => [
                    'sms' => false,
                    'email' => false
                ],
                'reminder_enable' => false,
                'callback_url' => route('payment.success'),
                'callback_method' => 'get'
            ];

            $paymentLink = $api->paymentLink->create($linkData);

            $payment->update([
                'razorpay_payment_link_id' => $paymentLink->id,
                'payment_link_url' => $paymentLink->short_url,
                'payment_link_expires_at' => \Carbon\Carbon::createFromTimestamp($expiryTime),
                'payment_method' => 'link',
            ]);

            $mailSent = false;
            $mailError = '';
            if ($customerEmail) {
                try {
                    $details = [
                        'student_name' => $customerName,
                        'course_name' => $payment->admission->course->title ?? 'Your Course',
                        'amount' => number_format($payment->amount, 2),
                        'payment_link' => $payment->payment_link_url,
                        'expires_at' => \Carbon\Carbon::parse($payment->payment_link_expires_at)->format('d M Y, h:i A')
                    ];
                    Mail::mailer('brevo')->to($customerEmail)->send(new PaymentLinkMail($details));
                    $payment->update(['payment_link_sent_at' => now()]);
                    $mailSent = true;
                } catch (\Throwable $e) {
                    $mailError = $e->getMessage();
                }
            }

            $message = 'Payment link generated successfully!';
            if ($mailSent) {
                $message .= ' Email sent to student.';
            } else if ($customerEmail) {
                $message .= ' But failed to send email: ' . $mailError;
            } else {
                $message .= ' (No email sent, student has no registered email)';
            }

            return response()->json([
                'status' => true,
                'message' => $message,
                'payment_link_url' => $payment->payment_link_url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error generating payment link: ' . $e->getMessage()
            ], 500);
        }
    }

    public function sendMail($id)
    {
        $payment = Payment::with('admission.lead', 'admission.contact', 'admission.course')->findOrFail($id);

        if (!$payment->payment_link_url) {
            return response()->json([
                'status' => false,
                'message' => 'Payment link not generated yet.'
            ], 400);
        }

        $contact = $payment->admission->contact;
        $lead = $payment->admission->lead;

        $customerName = ($contact && $contact->name) ? $contact->name : ($lead->name ?? 'Student');
        $customerEmail = ($contact && $contact->email) ? $contact->email : ($lead->email ?? null);

        if (!$customerEmail) {
            return response()->json([
                'status' => false,
                'message' => 'Student does not have a registered email address.'
            ], 400);
        }

        try {
            $details = [
                'student_name' => $customerName,
                'course_name' => $payment->admission->course->title ?? 'Your Course',
                'amount' => number_format($payment->amount, 2),
                'payment_link' => $payment->payment_link_url,
                'expires_at' => \Carbon\Carbon::parse($payment->payment_link_expires_at)->format('d M Y, h:i A')
            ];
            
            Mail::mailer('brevo')->to($customerEmail)->send(new PaymentLinkMail($details));

            $payment->update(['payment_link_sent_at' => now()]);

            return response()->json([
                'status' => true,
                'message' => 'Payment link sent to student successfully!'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error sending email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        $secret = env('RAZORPAY_WEBHOOK_SECRET', 'your_webhook_secret_here');

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($payload, $signature, $secret);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Webhook Signature Verification Failed: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);

        if (isset($data['event']) && $data['event'] === 'payment_link.paid') {
            $paymentLinkId = $data['payload']['payment_link']['entity']['id'];
            $orderId = $data['payload']['order']['entity']['id'];
            $paymentId = $data['payload']['payment']['entity']['id'];

            $payment = Payment::where('razorpay_payment_link_id', $paymentLinkId)->first();

            if ($payment && $payment->status !== 'Paid') {
                $payment->update([
                    'status' => 'Paid',
                    'razorpay_order_id' => $orderId,
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature, // We store the webhook signature for reference
                ]);
                
                // Convert lead to student and allocate batch on first payment success
                if ($payment->admission) {
                    \App\Services\StudentConversionService::convertLeadToStudent($payment->admission);
                }

                \Illuminate\Support\Facades\Log::info('Payment Link successful via webhook for Payment ID: ' . $payment->id);
            }
        } elseif (isset($data['event']) && $data['event'] === 'order.paid') {
             // In case they are using standard Orders API directly instead of Payment Links
             $orderId = $data['payload']['order']['entity']['id'];
             $paymentId = $data['payload']['payment']['entity']['id'];
 
             $payment = Payment::where('razorpay_order_id', $orderId)->first();
 
             if ($payment && $payment->status !== 'Paid') {
                 $payment->update([
                     'status' => 'Paid',
                     'razorpay_payment_id' => $paymentId,
                     'razorpay_signature' => $signature,
                 ]);
 
                 // Convert lead to student and allocate batch on first payment success
                 if ($payment->admission) {
                     $payment->admission->update(['status' => 'ongoing']);
                     \App\Services\StudentConversionService::convertLeadToStudent($payment->admission);
                 }
                 
                 // If this payment was for a direct course enrollment (e.g. recorded course checkout)
                 // We stored the enrollment_id in notes as JSON
                 if ($payment->notes) {
                     $notes = json_decode($payment->notes, true);
                     if (is_array($notes) && isset($notes['enrollment_id'])) {
                         $enrollment = \App\Models\CourseUser::find($notes['enrollment_id']);
                         if ($enrollment) {
                             $enrollment->update(['status' => 'active']);
                         }
                     }
                 }

                 \Illuminate\Support\Facades\Log::info('Order successful via webhook for Payment ID: ' . $payment->id);
             }
        }

        return response()->json(['status' => 'success']);
    }
    public function studentDues(Request $request)
    {
        $query = Payment::with(['admission.contact', 'admission.course', 'admission.batch'])
            ->where('status', 'Pending')
            ->orderBy('payment_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('admission.contact', function($contactQ) use ($search) {
                    $contactQ->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('caller_number', 'LIKE', "%{$search}%")
                             ->orWhere('secondary_number', 'LIKE', "%{$search}%");
                })->orWhereHas('admission.lead', function($leadQ) use ($search) {
                    $leadQ->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            });
        }

        $payments = $query->paginate(10);
        return view('payments.student_dues', compact('payments'));
    }

    public function dueFollowups(Request $request)
    {
        $query = Payment::with(['admission.contact', 'admission.course', 'admission.batch'])
            ->where('status', 'Pending')
            ->whereDate('payment_date', '<', now())
            ->orderBy('payment_date');

        $followups = $query->paginate(10);
        return view('payments.due_followups', compact('followups'));
    }

    public function completedPayments(Request $request)
    {
        $query = Payment::with(['admission.contact', 'admission.course'])
            ->where('status', 'Paid')
            ->orderBy('updated_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('admission.contact', function($contactQ) use ($search) {
                    $contactQ->where('name', 'LIKE', "%{$search}%")
                             ->orWhere('caller_number', 'LIKE', "%{$search}%")
                             ->orWhere('secondary_number', 'LIKE', "%{$search}%");
                })->orWhereHas('admission.lead', function($leadQ) use ($search) {
                    $leadQ->where('name', 'LIKE', "%{$search}%")
                          ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            });
        }

        $payments = $query->paginate(10);
        return view('payments.completed_payments', compact('payments'));
    }

    public function createOrder(Request $request)
    {
        $request->validate(['payment_id' => 'required|exists:payments,id']);
        
        $payment = Payment::with('admission.contact', 'admission.lead')->find($request->payment_id);
        
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $orderData = [
                'receipt'         => 'rcptid_' . $payment->id,
                'amount'          => $payment->amount * 100, // Amount in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];
            
            $razorpayOrder = $api->order->create($orderData);
            
            $payment->update(['razorpay_order_id' => $razorpayOrder['id']]);
            
            $studentName = $payment->admission->contact->name ?? ($payment->admission->lead->name ?? 'Student');
            
            return response()->json([
                'success'      => true,
                'key_id'       => config('services.razorpay.key'),
                'order_id'     => $razorpayOrder['id'],
                'amount'       => $orderData['amount'],
                'currency'     => 'INR',
                'description'  => 'Course Fee Payment',
                'student_name' => $studentName,
                'payment_id'   => $payment->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required',
            'razorpay_payment_id' => 'required',
            'razorpay_signature' => 'required',
            'payment_id' => 'required|exists:payments,id'
        ]);

        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );

            $api->utility->verifyPaymentSignature($attributes);
            
            $payment = Payment::find($request->payment_id);
            $payment->update([
                'status' => 'Paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            if ($payment->admission) {
                \App\Services\StudentConversionService::convertLeadToStudent($payment->admission);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

