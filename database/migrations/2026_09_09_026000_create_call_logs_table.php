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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->nullable();
            $table->string('caller_number', 20)->nullable();
            $table->string('call_duration', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->text('recording_url')->nullable();
            $table->string('status', 50)->nullable();
            $table->string('call_start')->nullable();
            $table->string('call_end')->nullable();
            $table->longText('full_log')->nullable();
            $table->text('note')->nullable();
            $table->text('zoom_meet_link')->nullable();
            $table->string('zoom_meeting_id')->nullable();
            $table->text('followup_note')->nullable();
            $table->string('demo_date')->nullable();
            $table->string('demo_time', 20)->nullable();
            $table->string('demo_status', 50)->nullable();
            $table->string('followup_date')->nullable();
            $table->string('followup_time', 20)->nullable();
            $table->foreignId('assigned_staff_id')->nullable();
            $table->foreignId('staff_id')->nullable();
            $table->string('followup_status', 50)->nullable();
            $table->string('direction', 50)->nullable();
            $table->string('lead_tag')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};