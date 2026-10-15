<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\User;
use App\Models\CourseUser;
use App\Models\Batch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentConversionService
{
    public static function convertLeadToStudent(Admission $admission)
    {
        // Prevent double conversion
        if ($admission->lead && $admission->lead->converted_date) {
            return false;
        }

        $lead = $admission->lead;
        if (!$lead) return false;

        // Check if user already exists
        $user = User::where('email', $lead->email)->orWhere('phonenumber', $lead->phone)->first();

        if (!$user) {
            $password = Str::random(10);
            $user = User::create([
                'name' => $lead->name,
                'email' => $lead->email,
                'phonenumber' => $lead->phone,
                'whatsapp' => $lead->whatsapp ?? $lead->phone,
                'password' => Hash::make($password),
                'lead_source' => $lead->source ? $lead->source->name : null,
            ]);

            // Assign role
            $user->assignRole('Student');
            
            // TODO: Optional: Send email with password to student
        }

        // Assign to Batch & Course
        if ($admission->course_id && $admission->batch_id) {
            $courseUserExists = CourseUser::where('auth_id', $user->id)
                ->where('course_id', $admission->course_id)
                ->where('batch_id', $admission->batch_id)
                ->exists();

            if (!$courseUserExists) {
                CourseUser::create([
                    'auth_id' => $user->id,
                    'course_id' => $admission->course_id,
                    'batch_id' => $admission->batch_id,
                    'status' => 'active',
                ]);

                // Sync batch student count
                $batch = Batch::find($admission->batch_id);
                if ($batch) {
                    $batchCount = Admission::where('batch_id', $batch->id)->count();
                    $batch->update(['joined_student_count' => $batchCount]);
                }
            }
        }

        // Update Lead Status
        $registeredStatus = \App\Models\LeadStatus::where('name', 'Registered')->first();
        $lead->update([
            'converted_date' => now(),
            'lead_status_id' => $registeredStatus ? $registeredStatus->id : $lead->lead_status_id,
        ]);

        return true;
    }
}
