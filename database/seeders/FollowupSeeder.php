<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Followup;
use Carbon\Carbon;

class FollowupSeeder extends Seeder
{
    public function run(): void
    {
        // Student Follow-ups
        $studentFollowups = [
            [
                'followup_code' => 'FS001',
                'type' => 'student',
                'student_name' => 'Arjun Krishnan',
                'phone' => '+91 98765 12345',
                'course' => 'Full Stack Dev',
                'company_name' => 'Zoho Corp',
                'purpose' => 'Inform about Interview',
                'call_date' => Carbon::create(2026, 10, 11),
                'status' => 'completed',
                'notes' => 'Informed about the 1st round technical interview schedule.',
            ],
            [
                'followup_code' => 'FS002',
                'type' => 'student',
                'student_name' => 'Sneha Raghavan',
                'phone' => '+91 98765 44444',
                'course' => 'Full Stack Dev',
                'company_name' => 'Infosys',
                'purpose' => 'Ask Interview Experience',
                'call_date' => Carbon::create(2026, 10, 12),
                'status' => 'completed',
                'notes' => 'Student cleared technical round 1, awaiting HR feedback.',
            ],
            [
                'followup_code' => 'FS003',
                'type' => 'student',
                'student_name' => 'Vikram Singh',
                'phone' => '+91 98765 55555',
                'course' => 'Data Science',
                'company_name' => 'TCS',
                'purpose' => 'Ask Interview Experience',
                'call_date' => Carbon::create(2026, 10, 12),
                'status' => 'completed',
                'notes' => 'Shared positive feedback regarding machine learning questions.',
            ],
        ];

        // Company Follow-ups
        $companyFollowups = [
            [
                'followup_code' => 'FC001',
                'type' => 'company',
                'company_name' => 'Infosys',
                'hr_name' => 'Priya Sharma',
                'hr_phone' => '+91 98765 56789',
                'role' => 'System Engineer',
                'student_name' => 'Sneha Raghavan',
                'purpose' => 'Ask Interview Status',
                'call_date' => Carbon::create(2026, 10, 11),
                'status' => 'completed',
                'notes' => 'HR confirmed candidate performance was good, final result by end of week.',
            ],
            [
                'followup_code' => 'FC002',
                'type' => 'company',
                'company_name' => 'CleverTap',
                'hr_name' => 'Sandeep Iyer',
                'hr_phone' => '+91 90909 80808',
                'role' => 'Frontend Developer',
                'student_name' => 'Mohammed Faisal',
                'purpose' => 'Ask Interview Date',
                'call_date' => Carbon::create(2026, 10, 12),
                'status' => 'completed',
                'notes' => 'Requested interview date confirmation for upcoming batch candidates.',
            ],
            [
                'followup_code' => 'FC003',
                'type' => 'company',
                'company_name' => 'TCS',
                'hr_name' => 'Rajeev Menon',
                'hr_phone' => '+91 90000 11111',
                'role' => 'Data Engineer',
                'student_name' => 'Vikram Singh',
                'purpose' => 'Ask Interview Status',
                'call_date' => Carbon::create(2026, 10, 12),
                'status' => 'completed',
                'notes' => 'Awaiting confirmation on shortlisted students from technical panel.',
            ],
        ];

        foreach (array_merge($studentFollowups, $companyFollowups) as $data) {
            Followup::updateOrCreate(
                ['followup_code' => $data['followup_code']],
                $data
            );
        }
    }
}
