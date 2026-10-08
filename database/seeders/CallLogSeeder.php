<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\CallLog;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class CallLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find a Tele Caller staff
        $teleCallerStaff = Staff::whereHas('role', function($q) {
            $q->where('name', 'Tele Caller');
        })->first();

        if (!$teleCallerStaff) {
            $this->command->error('Tele Caller staff not found. Please run UserSeeder first.');
            return;
        }

        // 1. Record for Priya Nair
        $contact1 = Contact::create([
            'name' => 'Priya Nair',
            'caller_number' => '+919840012345',
            'email' => 'priya@example.com',
            'source' => 'MyOperator',
            'location' => 'Chennai',
        ]);

        CallLog::create([
            'contact_id' => $contact1->id,
            'caller_number' => $contact1->caller_number,
            'direction' => 'Inbound',
            'status' => 'Completed',
            'lead_tag' => 'Warm',
            'assigned_staff_id' => $teleCallerStaff->id,
            'staff_id' => $teleCallerStaff->auth_id,
            'followup_date' => now()->toDateString(),
            'followup_time' => '10:30:00',
            'followup_status' => 'Pending',
            'note' => 'Interested in Full Stack course. Asked for a demo.',
            'created_at' => now()->subHours(2),
        ]);

        // 2. Record for Arjun Mehta
        $contact2 = Contact::create([
            'name' => 'Arjun Mehta',
            'caller_number' => '+919000054321',
            'email' => 'arjun@example.com',
            'source' => 'MyOperator',
            'location' => 'Mumbai',
        ]);

        CallLog::create([
            'contact_id' => $contact2->id,
            'caller_number' => $contact2->caller_number,
            'direction' => 'Inbound',
            'status' => 'Completed',
            'lead_tag' => 'Hot',
            'assigned_staff_id' => $teleCallerStaff->id,
            'staff_id' => $teleCallerStaff->auth_id,
            'demo_date' => now()->toDateString(),
            'demo_time' => '14:30:00',
            'demo_status' => 'Booked',
            'followup_date' => now()->toDateString(),
            'followup_time' => '16:00:00',
            'followup_status' => 'Pending',
            'note' => 'High potential lead. Wants to join the next batch.',
            'created_at' => now()->subHours(4),
        ]);

        $this->command->info('2 Call logs seeded successfully for ' . $teleCallerStaff->name);
    }
}
