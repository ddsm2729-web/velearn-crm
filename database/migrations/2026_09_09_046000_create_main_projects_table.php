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
        Schema::create('main_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('batch_id')->nullable();
            $table->string('module');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document')->nullable();
            $table->string('review_1_title')->nullable();
            $table->string('review_1_deadline')->nullable();
            $table->string('review_2_title')->nullable();
            $table->string('review_2_deadline')->nullable();
            $table->string('review_3_title')->nullable();
            $table->string('review_3_deadline')->nullable();
            $table->string('reveal_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_projects');
    }
};