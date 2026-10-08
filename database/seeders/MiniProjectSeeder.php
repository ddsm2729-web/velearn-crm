<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MiniProject;
use App\Models\Course;
use Carbon\Carbon;

class MiniProjectSeeder extends Seeder
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

        MiniProject::create([
            'course_id' => $course->id,
            'module' => 'Module 5',
            'title' => 'To-Do List Application',
            'description' => 'Build a simple to-do list using JavaScript and DOM manipulation.',
            'due_date' => Carbon::now()->addDays(10),
            'reveal_date' => Carbon::now()->subDays(1),
            'status' => 'active',
        ]);

        MiniProject::create([
            'course_id' => $course->id,
            'module' => 'Module 7',
            'title' => 'Weather App API Integration',
            'description' => 'Fetch data from a weather API and display it nicely.',
            'due_date' => Carbon::now()->addDays(15),
            'reveal_date' => Carbon::now()->subDays(1),
            'status' => 'active',
        ]);
    }
}
