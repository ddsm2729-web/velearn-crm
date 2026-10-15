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
        Schema::create('followups', function (Blueprint $table) {
            $table->id();
            $table->string('followup_code')->unique();
            $table->string('type')->default('student'); // 'student' or 'company'
            $table->unsignedBigInteger('student_id')->nullable();
            $table->string('student_name');
            $table->string('phone')->nullable();
            $table->string('course')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name');
            $table->string('hr_name')->nullable();
            $table->string('hr_phone')->nullable();
            $table->string('role')->nullable();
            $table->string('purpose');
            $table->date('call_date');
            $table->text('notes')->nullable();
            $table->string('status')->default('completed');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followups');
    }
};
