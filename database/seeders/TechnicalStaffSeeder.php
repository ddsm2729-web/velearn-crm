<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TechnicalStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staffData = [
            ['first_name' => 'Suresh', 'last_name' => 'M', 'email' => 'suresh.m@example.com'],
            ['first_name' => 'Anitha', 'last_name' => 'R', 'email' => 'anitha.r@example.com'],
            ['first_name' => 'Karthik', 'last_name' => 'V', 'email' => 'karthik.v@example.com'],
            ['first_name' => 'Divya', 'last_name' => 'P', 'email' => 'divya.p@example.com'],
        ];

        $timeSlots = [
            '11:30:00', '12:00:00', '12:30:00', '13:00:00', 
            '14:00:00', '14:45:00', '15:30:00', '16:15:00', 
            '17:00:00', '17:45:00'
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        foreach ($staffData as $data) {
            // 1. Create User
            $user = \App\Models\User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => bcrypt('password123'),
                    'phonenumber' => '98765432' . rand(10, 99),
                ]
            );

            // Assign role
            if (\Spatie\Permission\Models\Role::where('name', 'Technical Staff')->exists()) {
                $user->assignRole('Technical Staff');
            }

            // 2. Create Staff profile
            $techRole = \Spatie\Permission\Models\Role::where('name', 'Technical Staff')->first();
            $staff = \App\Models\Staff::firstOrCreate(
                ['auth_id' => $user->id],
                [
                    'first_name'    => $data['first_name'],
                    'last_name'     => $data['last_name'],
                    'email'         => $data['email'],
                    'primary_phone' => $user->phonenumber,
                    'status'        => 1,
                    'role_id'       => $techRole?->id,
                ]
            );
            // Ensure role_id is always set (in case staff already existed without it)
            if ($staff->role_id === null && $techRole) {
                $staff->update(['role_id' => $techRole->id]);
            }

            // 3. Create Schedules
            foreach ($days as $day) {
                foreach ($timeSlots as $time) {
                    \App\Models\StaffSchedule::firstOrCreate([
                        'staff_id' => $staff->id,
                        'day' => $day,
                        'schedule_time' => $time,
                    ], [
                        'status' => 'available'
                    ]);
                }
            }
        }

        // Add schedules to ANY existing technical staff who might be missing them
        $existingStaff = \App\Models\Staff::whereHas('auth', function($q) {
            $q->role('Technical Staff');
        })->get();

        foreach ($existingStaff as $staff) {
            foreach ($days as $day) {
                foreach ($timeSlots as $time) {
                    \App\Models\StaffSchedule::firstOrCreate([
                        'staff_id' => $staff->id,
                        'day' => $day,
                        'schedule_time' => $time,
                    ], [
                        'status' => 'available'
                    ]);
                }
            }
        }
    }
}
