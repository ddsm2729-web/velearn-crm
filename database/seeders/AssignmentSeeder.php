<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\Course;
use Carbon\Carbon;

class AssignmentSeeder extends Seeder
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

        Assignment::create([
            'course_id' => $course->id,
            'module' => 'Module 1',
            'title' => 'HTML Basics Assignment',
            'description' => 'Create a simple HTML page with headers and paragraphs.',
            'due_date' => Carbon::now()->addDays(7),
            'reveal_date' => Carbon::now()->subDays(1),
        ]);

        Assignment::create([
            'course_id' => $course->id,
            'module' => 'Module 2',
            'title' => 'CSS Styling Assignment',
            'description' => 'Style the HTML page created in the previous assignment.',
            'due_date' => Carbon::now()->addDays(14),
            'reveal_date' => Carbon::now()->subDays(1),
        ]);
    }
}
