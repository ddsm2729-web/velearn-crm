<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name'        => 'required|string|max:255',
                'email'       => 'required|email|unique:users,email',
                'phonenumber' => 'required|digits:10|unique:users,phonenumber',
                'password'    => 'required|min:6',
            ]);

            $user = DB::transaction(function () use ($request) {

                $phoneNumber = preg_replace('/[^0-9]/', '', $request->phonenumber);

                // Remove leading 0 if exists
                $phoneNumber = ltrim($phoneNumber, '0');

                // Add +91 only if not already present
                if (!str_starts_with($phoneNumber, '91')) {
                    $phoneNumber = '91' . $phoneNumber;
                }

                $phoneNumber = '+' . $phoneNumber;

                // 1. Create user account
                $user = User::create([
                    'name'        => $request->name,
                    'email'       => $request->email,
                    'phonenumber' => $phoneNumber,
                    'password'    => Hash::make($request->password),
                    'lead_source' => $request->lead_source ?? 'Website',
                ]);

                // 2. Assign default student role
                $user->assignRole('student');

                // 3. Create Contact record
                Contact::create([
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'caller_number' => $phoneNumber,
                    'source'        => $request->lead_source ?? 'Website',
                ]);

                return $user;
            });

            // Create API token (Sanctum)
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Registered successfully',
                'token'   => $token,
                'user'    => $user,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Register Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // LOGIN
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'     => 'required|email',
                'password'  => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'Login successful',
                'token'   => $token,
                'user'    => $user
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Login Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // LOGOUT
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Logout Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function updateFcmToken(Request $request)
    {
        \Log::info('Entering updateFcmToken method', ['request' => $request->all()]);
        try {
            $request->validate([
                'fcm_token' => 'required|string',
                'auth_id'   => 'nullable',
                'user_id'   => 'nullable',
            ]);

            $targetId = $request->auth_id ?? $request->user_id;
            $user = $request->user();

            if (!$user && $targetId) {
                $user = User::find($targetId);
            }

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User not authenticated or auth_id missing',
                    'debug_auth_id' => $request->auth_id
                ], 401);
            }

            if ($user) {
                \Log::info('FCM: Found user, updating token', ['id' => $user->id]);
                $user->update(['fcm_token' => $request->fcm_token]);
                
                // Fire Welcome message now that we guarantee the new token is successfully saved!
                try {
                    $fcmService = app(\App\Services\FcmService::class);
                    $fcmService->sendNotification(
                        $request->fcm_token,
                        'Welcome Back!',
                        'Welcome back to VeLearn! Your login was successful 📚',
                        ['type' => 'login_success'],
                        'https://www.velearn.in/images/login-welcome.png'
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to send login notification after token sync: ' . $e->getMessage());
                }

            } else {
                \Log::warning('FCM: No user found for token update', ['targetId' => $targetId]);
            }

            return response()->json([
                'status'  => true,
                'message' => 'FCM token updated successfully',
                'id'      => $user->id
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('UpdateFcmToken Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function testNotification(Request $request, \App\Services\FcmService $fcmService)
    {
        try {
            $request->validate([
                'fcm_token' => 'required|string',
                'title'     => 'nullable|string',
                'body'      => 'nullable|string',
            ]);

            $success = $fcmService->sendNotification(
                $request->fcm_token,
                $request->title ?? 'Test Notification',
                $request->body ?? 'This is a test notification from VeLearn backend.',
                ['type' => 'test']
            );

            if ($success) {
                return response()->json(['status' => true, 'message' => 'Notification sent successfully']);
            } else {
                return response()->json(['status' => false, 'message' => 'Failed to send notification. Check backend logs for errors.'], 500);
            }

        } catch (\Exception $e) {
            \Log::error('TestNotification Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateLogo(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            ]);

            $authId = $request->auth_id ?? $request->user_id;

            if (!$authId) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User ID is required (auth_id or user_id)',
                ], 422);
            }

            $user = User::where('id', $authId)->first();

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'User profile not found',
                ], 404);
            }

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image && file_exists(public_path('uploads/students/' . $user->image))) {
                    @unlink(public_path('uploads/students/' . $user->image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/students'), $imageName);
                
                $user->update(['image' => $imageName]);

                return response()->json([
                    'status'  => true,
                    'message' => 'Logo updated successfully',
                    'image'   => asset('uploads/students/' . $imageName)
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'No image file uploaded',
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('UpdateLogo Error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found with this email address'
            ], 404);
        }

        $token = \Illuminate\Support\Str::random(60);

        try {
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'email' => $request->email,
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            // Generate dynamic reset link
            $frontendUrl = rtrim(env('FRONTEND_URL', 'https://www.velearn.in'), '/');
            $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . $request->email;

            // Send actual Email
            Mail::mailer('brevo')->to($user->email)->send(new ResetPasswordMail($resetUrl));

            return response()->json([
                'status' => true,
                'message' => 'Password reset link sent to your email',
            ]);

        } catch (\Exception $e) {
            \Log::error('ForgotPassword Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send reset email. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => 'required|min:6|confirmed'
            ]);

            $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

            if (!$reset || !Hash::check($request->token, $reset->token)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid or expired token'
                ], 400);
            }

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $user->update(['password' => Hash::make($request->password)]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Password reset successful'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('ResetPassword Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while resetting password',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'current_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password'
            ]);

            $user = User::find($request->user_id);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 404);
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Current password does not match'
                ], 400);
            }

            $user->update(['password' => Hash::make($request->new_password)]);

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully!'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('ChangePassword Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $userId = $request->user_id ?? $request->auth_id;
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            // Convert to plain array so we can safely manipulate fields
            $data = $user->toArray();

            // Compatibility: frontend expects primary_phone and pincode
            $data['primary_phone'] = $user->phonenumber;
            $data['pincode']       = $user->zip_code;

            // Format date_of_birth as Y-m-d string so HTML date input renders correctly
            $data['date_of_birth'] = $user->date_of_birth
                ? $user->date_of_birth->format('Y-m-d')
                : null;

            // Ensure gender and state_id are strings for frontend select compatibility
            if (!is_null($data['gender']))   $data['gender']   = (string)$data['gender'];
            if (!is_null($data['state_id'])) $data['state_id'] = (string)$data['state_id'];

            return response()->json(['status' => true, 'data' => $data]);
        } catch (\Exception $e) {
            \Log::error('GetProfile Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $userId = $request->user_id ?? $request->auth_id;
            $user = User::find($userId);

            if (!$user) {
                return response()->json(['status' => false, 'message' => 'User not found'], 404);
            }

            // Build a clean update payload — only fillable User fields
            $fillableFields = [
                'first_name', 'last_name', 'date_of_birth', 'gender',
                'education', 'designation', 'address', 'email',
                'image', 'secondary_phone', 'state_id', 'city', 'zip_code',
            ];

            $updateData = [];
            foreach ($fillableFields as $field) {
                if ($request->has($field)) {
                    $value = $request->input($field);

                    // Convert empty strings to null for nullable date/integer/id fields
                    // to prevent Laravel cast exceptions (e.g. date cast on "")
                    $nullableFields = ['date_of_birth', 'state_id', 'gender', 'zip_code', 'city'];
                    if (in_array($field, $nullableFields) && $value === '') {
                        $value = null;
                    }

                    $updateData[$field] = $value;
                }
            }

            // Map frontend aliases to DB column names
            if ($request->has('primary_phone')) {
                $phone = $request->input('primary_phone');
                $updateData['phonenumber'] = $phone === '' ? null : $phone;
            }
            if ($request->has('pincode')) {
                $pincode = $request->input('pincode');
                $updateData['zip_code'] = $pincode === '' ? null : $pincode;
            }

            // Rebuild full name from first_name / last_name
            $firstName = $request->input('first_name', $user->first_name);
            $lastName  = $request->input('last_name',  $user->last_name);
            $fullName  = trim($firstName . ' ' . $lastName);
            if ($fullName) {
                $updateData['name'] = $fullName;
            }

            // Capture old email/phone BEFORE update for syncing contacts/leads
            $oldEmail = $user->email;
            $oldPhone = $user->phonenumber;

            $user->update($updateData);
            $user->refresh();

            // --- Sync changes to contacts table (matched by email or phone) ---
            $contactUpdate = [];
            if (isset($updateData['name']))        $contactUpdate['name']          = $updateData['name'];
            if (isset($updateData['email']))       $contactUpdate['email']         = $updateData['email'];
            if (isset($updateData['phonenumber'])) $contactUpdate['caller_number'] = $updateData['phonenumber'];

            if (!empty($contactUpdate)) {
                Contact::where('email', $oldEmail)
                    ->orWhere('caller_number', $oldPhone)
                    ->update($contactUpdate);
            }

            // --- Sync changes to leads table (matched by email or phone) ---
            $leadUpdate = [];
            if (isset($updateData['name']))        $leadUpdate['name']  = $updateData['name'];
            if (isset($updateData['email']))       $leadUpdate['email'] = $updateData['email'];
            if (isset($updateData['phonenumber'])) $leadUpdate['phone'] = $updateData['phonenumber'];

            if (!empty($leadUpdate)) {
                Lead::where('email', $oldEmail)
                    ->orWhere('phone', $oldPhone)
                    ->update($leadUpdate);
            }

            // Return date_of_birth as Y-m-d string
            $responseData = $user->toArray();
            $responseData['primary_phone'] = $user->phonenumber;
            $responseData['pincode']       = $user->zip_code;
            $responseData['date_of_birth'] = $user->date_of_birth
                ? $user->date_of_birth->format('Y-m-d')
                : null;

            return response()->json(['status' => true, 'message' => 'Profile updated successfully', 'data' => $responseData]);
        } catch (\Exception $e) {
            \Log::error('UpdateProfile Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getStates()
    {
        try {
            $states = State::orderBy('state_name', 'asc')->get();
            return response()->json(['status' => true, 'data' => $states]);
        } catch (\Exception $e) {
            \Log::error('GetStates Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}