<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batch_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $table->foreignId('batch_schedule_id')->nullable()->constrained('batch_schedules')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_name')->nullable();
            $table->string('student_email')->index();
            $table->string('zoom_meeting_id')->nullable()->index();
            $table->date('date')->index();
            $table->integer('meeting_duration_minutes')->default(0);
            $table->integer('attended_duration_minutes')->default(0);
            $table->integer('attended_duration_seconds')->default(0);
            $table->decimal('attendance_percentage', 5, 2)->default(0.00);
            // Statuses: 'Present' (>= 75%), 'Half Day' (>= 45%), 'Leave' (< 45%)
            $table->enum('status', ['Present', 'Half Day', 'Leave'])->default('Leave');
            $table->text('remarks')->nullable();
            $table->timestamp('join_time')->nullable();
            $table->timestamp('leave_time')->nullable();
            $table->timestamps();

            $table->unique(['batch_id', 'date', 'student_email'], 'unique_batch_date_student');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_attendances');
    }
};
