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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('city_location');
            $table->string('industry_sector');
            $table->string('company_website')->nullable();
            $table->string('company_mail_id');
            $table->string('hr_name');
            $table->string('hr_contact_number');
            $table->string('hr_email_id');
            $table->string('linkedin_url')->nullable();
            $table->unsignedBigInteger('source_id');
            $table->boolean('current_openings')->default(0);
            $table->unsignedBigInteger('lead_tag_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
