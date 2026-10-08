<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sources = [
            'MyOperator', 'App', 'Website', 'Instagram', 'Facebook', 
            'LinkedIn', 'Whatsapp', 'Youtube', 'Justdial'
        ];

        foreach ($sources as $sourceName) {
            $source = \App\Models\LeadSource::create([
                'name' => $sourceName,
                'slug' => \Illuminate\Support\Str::slug($sourceName),
            ]);

            // Assign all sources to all relevant roles by default
            $roles = \Spatie\Permission\Models\Role::whereIn('name', ['Tele Caller', 'Admission Officer', 'SuperAdmin'])->get();
            foreach ($roles as $role) {
                $source->roles()->attach($role);
            }
        }
    }
}
