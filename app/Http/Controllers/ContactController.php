<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactUsMail;

class ContactController extends Controller
{
    protected $baseUrl;
    protected $companyId;
    protected $apiKey;
    protected $xApiKey;

    public function __construct()
    {
        $this->baseUrl = env('MYOPERATOR_BASE_URL', 'https://publicapi.myoperator.co');
        $this->companyId = env('MYOPERATOR_COMPANY_ID');
        $this->apiKey = env('MYOPERATOR_API_KEY');
        $this->xApiKey = env('MYOPERATOR_X_API_KEY');
    }

    public function index()
    {
        return view('contacts.index');
    }

    public function getContacts()
    {
        try {
            $response = Http::withHeaders([
                'X-MYOP-COMPANY-ID' => $this->companyId,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/contacts");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error fetching contacts: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to fetch contacts'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'X-MYOP-COMPANY-ID' => $this->companyId,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/contacts", [
                'name' => $request->name,
                'email_id' => $request->email_id,
                'phone_number' => $request->phone_number,
                'country_code' => $request->country_code,
                'marketing_opt_in' => $request->marketing_opt_in,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to create contact'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->only([
                'name', 
                'email_id', 
                'marketing_opt_in', 
                'custom_fields'
            ]);
            
            // Remove null values so we only patch fields that were provided
            $data = array_filter($data, function($value) {
                return $value !== null;
            });

            $response = Http::withHeaders([
                'X-MYOP-COMPANY-ID' => $this->companyId,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->patch("{$this->baseUrl}/contacts/{$id}", $data);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error updating contact: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to update contact'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'X-MYOP-COMPANY-ID' => $this->companyId,
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->delete("{$this->baseUrl}/contacts/{$id}");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to delete contact'], 500);
        }
    }

    /* SEND CONTACT MAIL ONLY */
    public function sendMail(Request $request)
    {
        $payload = $request->validate([
            'name'             => 'required|string',
            'phone_number'     => 'required|string',
            'email_id'         => 'required|email',
            'course'           => 'nullable|string',
            'message'          => 'nullable|string',
        ]);

        // Send Email Notification via Brevo Mailer
        try {
            $adminEmail = env('MAIL_FROM_ADDRESS', 'admin@velearn.in');
            
            // Format data for the existing template
            $mailData = [
                'name' => $payload['name'],
                'email_id' => $payload['email_id'],
                'country_code' => $request->get('country_code', '+91'),
                'phone_number' => $payload['phone_number'],
                'custom_fields' => [
                    'course' => $payload['course'] ?? 'N/A',
                    'message' => $payload['message'] ?? 'N/A'
                ]
            ];

            \Illuminate\Support\Facades\Mail::mailer('brevo')->to($adminEmail)->send(new \App\Mail\ContactUsMail($mailData));

            return response()->json([
                'status' => true,
                'message' => 'Mail sent successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send contact email: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}
