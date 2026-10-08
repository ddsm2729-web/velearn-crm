<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MainProject;
use App\Models\Course;
use Carbon\Carbon;

class MainProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $course = Course::first();
        if (!$course) {
            $course = Course::create([
                'title' => 'Sample Course',
                'slug' => 'sample-course',
                'course_type' => 'live'
            ]);
        }

        MainProject::create([
            'course_id' => $course->id,
            'module' => 'Final Module',
            'title' => 'E-Commerce Website Development',
            'description' => 'Develop a complete e-commerce website using Laravel and React.',
            'due_date' => Carbon::now()->addDays(30),
            'reveal_date' => Carbon::now()->subDays(1),
            'status' => 'active',
        ]);

        MainProject::create([
            'course_id' => $course->id,
            'module' => 'Advanced Module',
            'title' => 'Social Media Dashboard',
            'description' => 'Create a dashboard to track social media metrics.',
            'due_date' => Carbon::now()->addDays(45),
            'reveal_date' => Carbon::now()->subDays(1),
            'status' => 'active',
        ]);
    }
}
