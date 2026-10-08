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
        Schema::create('placement_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->integer('test_timing')->comment('Duration in minutes');
            $table->enum('correction_type', ['auto', 'tutor'])->default('auto');
            $table->timestamps();
        });

        Schema::create('placement_evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('placement_evaluation_id')->constrained('placement_evaluations')->onDelete('cascade');
            $table->text('question');
            $table->string('type')->default('mcq')->comment('mcq, coding, upload, etc.');
            $table->integer('marks')->default(1);
            $table->timestamps();
        });

        Schema::create('placement_evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('placement_evaluation_question_id');
            $table->text('answer');
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->foreign('placement_evaluation_question_id', 'pea_peq_id_fk')
                  ->references('id')
                  ->on('placement_evaluation_questions')
                  ->onDelete('cascade');
        });

        Schema::create('placement_evaluation_student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('placement_evaluation_id');
            $table->unsignedBigInteger('placement_evaluation_question_id');
            $table->unsignedBigInteger('selected_answer_id')->nullable();
            $table->text('text_answer')->nullable();
            $table->integer('marks_obtained')->nullable();
            $table->timestamps();

            $table->foreign('placement_evaluation_id', 'pesa_pe_id_fk')
                  ->references('id')
                  ->on('placement_evaluations')
                  ->onDelete('cascade');
            $table->foreign('placement_evaluation_question_id', 'pesa_peq_id_fk')
                  ->references('id')
                  ->on('placement_evaluation_questions')
                  ->onDelete('cascade');
            $table->foreign('selected_answer_id', 'pesa_pea_id_fk')
                  ->references('id')
                  ->on('placement_evaluation_answers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_evaluation_student_answers');
        Schema::dropIfExists('placement_evaluation_answers');
        Schema::dropIfExists('placement_evaluation_questions');
        Schema::dropIfExists('placement_evaluations');
    }
};
