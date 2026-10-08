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
        Schema::create('mini_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->foreignId('batch_id')->nullable();
            $table->string('module');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document')->nullable();
            $table->string('due_date')->nullable();
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
        Schema::dropIfExists('mini_projects');
    }
};