<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'SuperAdmin',
            'Tele Caller',
            'Admission Officer',
            'Support Executive',
            'Technical Staff',
            'Technical Head',
            'Learning Coordinator',
            'Dues collector',
            'Digital Marketing',
            'Operation Executive',
            'Placement Coordinator',
            'Student Coordinator',
            'Student',
        ];

        foreach ($roles as $roleName) {
            // Create user
            $email = strtolower(str_replace(' ', '', $roleName)) . '@velearn.com';
            
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $roleName . ' User',
                    'password' => Hash::make('password'),
                ]
            );

            // Assign role
            $user->assignRole($roleName);

            // Create Staff record if not Student
            if ($roleName !== 'Student') {
                $role = Role::where('name', $roleName)->first();
                Staff::updateOrCreate(
                    ['auth_id' => $user->id],
                    [
                        'first_name' => $roleName,
                        'last_name' => 'User',
                        'email' => $user->email,
                        'primary_phone' => '1234567890',
                        'role_id' => $role ? $role->id : null,
                        'status' => 1,
                    ]
                );
            }
        }
    }
}
