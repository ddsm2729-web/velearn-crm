<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'hot', 'warm', 'cold', 'contacted', 'mismatch', 
            'confirmed', 'not interested',
            'Demo booked', 'Demo Attended', 'Demo Missed', 
            'Demo converted', 'Demo lost', 'converted'
        ];

        foreach ($tags as $tagName) {
            $tag = \App\Models\LeadTag::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tagName)],
                ['name' => $tagName]
            );

            // Default assignments
            if (in_array(strtolower($tagName), ['hot', 'warm', 'cold', 'contacted', 'mismatch'])) {
                $role = \Spatie\Permission\Models\Role::where('name', 'Tele Caller')->first();
                if ($role) $tag->roles()->attach($role);
            }

            // Admission Officer and SuperAdmin get all
            $roles = \Spatie\Permission\Models\Role::whereIn('name', ['Admission Officer', 'SuperAdmin'])->get();
            foreach ($roles as $role) {
                $tag->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
