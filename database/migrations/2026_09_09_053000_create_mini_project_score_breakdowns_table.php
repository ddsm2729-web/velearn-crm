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
        Schema::create('mini_project_score_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mini_project_submission_id');
            $table->string('criterion');
            $table->integer('max_score');
            $table->integer('obtained_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mini_project_score_breakdowns');
    }
};