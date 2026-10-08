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
        Schema::create('placement_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Resume
            $table->string('upload_resume')->nullable();
            $table->string('resume_status')->default('pending'); // pending, verified, changes_needed
            $table->text('resume_feedback')->nullable();
            
            // Portfolio
            $table->string('portfolio_url')->nullable();
            $table->string('portfolio_status')->default('pending');
            $table->text('portfolio_feedback')->nullable();
            
            // Project Docs
            $table->string('upload_project_docs')->nullable();
            $table->string('project_docs_status')->default('pending');
            $table->text('project_docs_feedback')->nullable();
            
            // Overall Status
            $table->string('overall_status')->default('pending'); // pending, all_verified
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reviewed_by')->nullable(); // Could be a staff ID
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_profiles');
    }
};
