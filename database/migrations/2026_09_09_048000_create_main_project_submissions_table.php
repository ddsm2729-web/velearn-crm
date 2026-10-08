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
        Schema::create('main_project_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_project_id');
            $table->foreignId('user_id');
            $table->string('review_number')->default(1);
            $table->string('status')->default('uploaded');
            $table->string('file_path')->nullable();
            $table->integer('attempts')->default(1);
            $table->integer('score')->nullable();
            $table->longText('attempts_history')->nullable();
            $table->string('reupload_approved')->default(0);
            $table->string('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->foreignId('evaluated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_project_submissions');
    }
};