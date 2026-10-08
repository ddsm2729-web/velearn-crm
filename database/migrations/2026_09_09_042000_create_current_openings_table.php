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
        Schema::create('current_openings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id');
            $table->string('job_role');
            $table->string('company_mail_id')->nullable();
            $table->string('hr_name')->nullable();
            $table->string('hr_contact_number')->nullable();
            $table->string('hr_email_id')->nullable();
            $table->string('job_location');
            $table->string('skills_required')->nullable();
            $table->string('minimum_qualification');
            $table->string('experience_required');
            $table->string('ctc_salary');
            $table->integer('number_of_openings')->default(1);
            $table->string('application_deadline')->nullable();
            $table->string('interview_mode')->default('Online');
            $table->string('interview_process')->nullable();
            $table->string('bond_service_agmt')->nullable();
            $table->string('opening_status')->default('Active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_openings');
    }
};