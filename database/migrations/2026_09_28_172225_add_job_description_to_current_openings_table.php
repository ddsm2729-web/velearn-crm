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
        Schema::table('current_openings', function (Blueprint $table) {
            $table->enum('job_description_type', ['file', 'text'])->nullable();
            $table->string('job_description_file')->nullable();
            $table->text('job_description_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_openings', function (Blueprint $table) {
            $table->dropColumn(['job_description_type', 'job_description_file', 'job_description_text']);
        });
    }
};
