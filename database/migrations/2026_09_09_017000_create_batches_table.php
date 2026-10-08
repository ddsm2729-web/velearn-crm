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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->string('batch_name');
            $table->string('batch_code');
            $table->string('batch_type')->default('Weekday');
            $table->string('batch_track')->default('Normal');
            $table->foreignId('instructor_id')->nullable();
            $table->string('start_date');
            $table->string('end_date')->nullable();
            $table->string('batch_from_time');
            $table->string('batch_to_time');
            $table->string('class_duration')->nullable();
            $table->string('join_deadline')->nullable();
            $table->integer('joined_student_count')->default(0);
            $table->string('meeting_link')->nullable();
            $table->integer('max_students')->default(50);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};