<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\AssignmentCriterion;
use App\Models\MiniProject;
use App\Models\MiniProjectSubmission;
use App\Models\MiniProjectCriterion;
use App\Models\MainProject;
use App\Models\MainProjectSubmission;
use App\Models\MainProjectCriterion;
use App\Models\User;
use App\Models\Course;
use App\Models\Batch;

class EvaluationDemoSeeder extends Seeder
{
    public function run()
    {
        $student = User::first() ?? User::factory()->create(['name' => 'Demo Student', 'email' => 'student@example.com']);
        $course = Course::first() ?? Course::factory()->create(['title' => 'Demo Course', 'course_type' => 'live']);
        $batch = Batch::first() ?? Batch::create(['batch_name' => 'Demo Batch', 'batch_code' => 'DEMO01', 'start_date' => now()]);

        // 3. Main Project
        $mainProject = MainProject::create([
            'course_id' => $course->id,
            'batch_id' => $batch->id,
            'module' => 'Final Capstone',
            'title' => 'Full-Stack E-commerce Platform',
            'description' => 'Build a complete e-commerce platform with payment integration.',
            'status' => 'active',
        ]);

        $mainProject->criteria()->createMany([
            ['criterion' => 'Backend Logic', 'maximum_mark' => 40],
            ['criterion' => 'Frontend Polish', 'maximum_mark' => 30],
            ['criterion' => 'Database Design', 'maximum_mark' => 20],
            ['criterion' => 'Deployment', 'maximum_mark' => 10],
        ]);

        MainProjectSubmission::create([
            'main_project_id' => $mainProject->id,
            'user_id' => $student->id,
            'status' => 'pending',
            'file_path' => 'ecommerce_capstone.zip',
            'attempts' => 1,
        ]);
        
        echo "Evaluation demo records seeded successfully.\n";
    }
}
