<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Lead;
use App\Models\LeadPipelineLog;
use App\Models\LeadTag;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'course_id' => 'required|exists:courses,id',
            'batch_id' => 'required|exists:batches,id',
            'batch_type' => 'nullable|string',
            'course_fees' => 'required|numeric',
            'payment_mode' => 'required|string',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        // Find or create Contact based on Lead's phone number
        $contact = \App\Models\Contact::firstOrCreate(
            ['caller_number' => $lead->phone],
            [
                'name' => $lead->name,
                'email' => $lead->email,
                'location' => $lead->location,
                'source' => $lead->source ? $lead->source->name : null,
            ]
        );
        
        // Save Admission
        $admission = Admission::updateOrCreate(
            ['lead_id' => $lead->id],
            [
                'contact_id' => $contact->id,
                'course_id' => $request->course_id,
                'batch_id' => $request->batch_id,
                'batch_type' => $request->batch_type,
                'course_fees' => $request->course_fees,
                'payment_mode' => $request->payment_mode,
                'status' => 'pending_fee',
            ]
        );

        // Also add a "Registered" tag if not exists
        $registeredTag = LeadTag::firstOrCreate(
            ['name' => 'Registered'],
            ['slug' => \Illuminate\Support\Str::slug('Registered')]
        );
        if (!$lead->tags->contains($registeredTag->id)) {
            $lead->tags()->attach($registeredTag->id);
            
            // Log pipeline change
            LeadPipelineLog::create([
                'lead_id' => $lead->id,
                'new_tag_id' => $registeredTag->id,
                'changed_by' => auth()->id(),
                'action' => 'tag_added',
                'notes' => 'Admission details saved, marked as Registered.'
            ]);
        }

        // Send Registration Confirmation Email
        $studentEmail = ($contact && $contact->email) ? $contact->email : ($lead->email ?? null);
        $studentName = ($contact && $contact->name) ? $contact->name : ($lead->name ?? 'Student');

        if ($studentEmail) {
            try {
                $details = [
                    'student_name' => $studentName,
                    'course_name' => $admission->course->title ?? 'Selected Course',
                    'batch_type' => $admission->batch_type ?? 'Standard',
                    'course_fees' => number_format($admission->course_fees, 2),
                ];
                \Illuminate\Support\Facades\Mail::mailer('brevo')->to($studentEmail)->send(new \App\Mail\RegistrationConfirmationMail($details));
            } catch (\Throwable $e) {
                // Log the error but don't fail the admission process
                \Illuminate\Support\Facades\Log::error('Failed to send registration email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Admission details saved successfully.',
            'admission_id' => $admission->id,
            'admission' => $admission
        ]);
    }
}
