<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Webinar;
use App\Models\User;
use App\Models\WebinarUser;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebinarRegisterController extends Controller
{
    /**
     * Enroll authenticated user or guest in a webinar
     */
    public function enrollNow(Request $request)
    {
        try {
            $request->validate([
                'webinar_id' => 'required|exists:webinars,id',
                'auth_id'    => 'nullable|integer',   // Don't check exists — user may be soft-deleted or ID may differ on local DB
                'name'       => 'nullable|string|max:255',
                'email'      => 'required|email|max:255',
                'phone'      => 'nullable|string|max:20',
            ]);

            $webinarId = $request->webinar_id;
            $authId    = $request->auth_id ? (int) $request->auth_id : null;
            $email     = $request->email;
            $webinar   = Webinar::findOrFail($webinarId);

            // 1. Verify auth_id actually exists in users table (handles soft-deletes & mismatched local DBs)
            if ($authId) {
                $userExists = User::withTrashed()->where('id', $authId)->exists();
                if (!$userExists) {
                    // auth_id doesn't exist locally — treat as guest but still use email to link
                    $authId = null;
                }
            }

            // 2. Unification: If not logged in, try to link by email
            if (!$authId) {
                $existingUser = User::where('email', $email)->first();
                if ($existingUser) {
                    $authId = $existingUser->id;
                }
            }

            return DB::transaction(function () use ($request, $webinar, $authId, $email) {
                
                // 2. Comprehensive Duplicate Check (Auth ID OR Email)
                $query = WebinarUser::where('webinar_id', $webinar->id);
                if ($authId) {
                    $query->where(function($q) use ($authId, $email) {
                        $q->where('auth_id', $authId)->orWhere('email', $email);
                    });
                } else {
                    $query->where('email', $email);
                }

                if ($query->exists()) {
                    return response()->json([
                        'status'  => false, 
                        'message' => 'You are already registered for this webinar.'
                     ], 409);
                }

                // 3. Prepare Data
                $name  = $request->name;
                $phone = $request->phone;

                // Sync CRM Contact
                $contactEmail = $email;
                $contactPhone = $phone;
                $contactName = $name;

                if ($authId) {
                    $auth = User::find($authId);
                    $contactName  = $contactName  ?? $auth->name;
                    $contactPhone = $contactPhone ?? $auth->phonenumber;
                    $contactEmail = $contactEmail ?? $auth->email;
                    
                    // Update variables to use the unified auth values if not set
                    $name  = $name  ?? $auth->name;
                    $phone = $phone ?? $auth->phonenumber;
                }

                $contact = Contact::where('email', $contactEmail)
                    ->orWhere('caller_number', $contactPhone)
                    ->first();

                if (!$contact) {
                    Contact::create([
                        'name'          => $contactName,
                        'email'         => $contactEmail,
                        'caller_number' => $contactPhone,
                        'source'        => 'Webinar Registration',
                    ]);
                }

                $registration = WebinarUser::create([
                    'webinar_id' => $webinar->id,
                    'auth_id'    => $authId,
                    'name'       => $name,
                    'email'      => $email,
                    'phone'      => $phone,
                    'status'     => 'joined'
                ]);

                // 4. Send Confirmation Mail
                try {
                    \Illuminate\Support\Facades\Mail::mailer('brevo')->to($registration->email)
                        ->send(new \App\Mail\WebinarConfirmationMail($webinar, $registration));
                } catch (\Exception $e) {
                    Log::error('Webinar Mail Error: ' . $e->getMessage());
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Successfully registered for the webinar. A confirmation email has been sent.',
                    'data'    => $registration
                ], 201);
            });

        } catch (\Exception $e) {
            Log::error('Webinar Registration Error: '.$e->getMessage());
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all webinars registered by authenticated user
     */
    public function myWebinars(Request $request, $id)
    {
        // Try withTrashed so soft-deleted users can still fetch their registrations
        $user = User::withTrashed()->with(['enrolledWebinars'])->find($id);

        if (!$user) {
            // User not found in this DB (e.g. different local DB) — return empty list gracefully
            return response()->json([
                'status' => true,
                'message' => 'No webinars found',
                'data' => []
            ]);
        }

        $webinars = $user->enrolledWebinars->map(function ($webinar) {
            return [
                'id' => $webinar->id,
                'title' => $webinar->title,
                'image' => $webinar->image ? asset('uploads/webinars/' . $webinar->image) : null,
                'date' => $webinar->date,
                'from_time' => $webinar->from_time,
                'to_time' => $webinar->to_time,
                'professor' => $webinar->professor,
                'registration' => [
                    'status' => $webinar->pivot->status,
                    'registered_at' => $webinar->pivot->created_at->format('d M, Y'),
                ]
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Webinars retrieved successfully',
            'data' => $webinars
        ]);
    }

    /**
     * Manage webinar registrations for admin
     */
    public function registerWebinars(Request $request)
    {
        // Get all registrations including guests and authenticated users
        $registrations = WebinarUser::with(['webinar', 'user'])
            ->latest()
            ->paginate(15);

        // Transform for the view
        $registrations->getCollection()->transform(function ($reg) {
            return [
                'id'            => $reg->id,
                'name'          => $reg->name ?? $reg->user?->name ?? 'N/A',
                'email'         => $reg->email ?? $reg->user?->email ?? 'N/A',
                'phonenumber'   => $reg->phone ?? $reg->user?->phonenumber ?? '—',
                'webinar_title' => $reg->webinar?->title ?? 'N/A',
                'webinar_date'  => $reg->webinar ? \Carbon\Carbon::parse($reg->webinar->date)->format('d M, Y') : '—',
                'status'        => $reg->status,
                'registered_at' => $reg->created_at->format('d M, Y h:i A'),
                'is_guest'      => is_null($reg->auth_id)
            ];
        });

        // We'll reuse the view but it might need minor adjustments or we rename the variable
        return view('students.webinars', [
            'registrations' => $registrations
        ]);
    }
}
