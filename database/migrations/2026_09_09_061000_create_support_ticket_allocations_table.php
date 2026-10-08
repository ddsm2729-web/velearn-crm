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
        Schema::create('support_ticket_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_ticket_id');
            $table->foreignId('staff_id');
            $table->string('allocated_date');
            $table->string('allocated_slot');
            $table->text('notes')->nullable();
            $table->string('status')->default('Scheduled');
            $table->text('zoom_meet_link')->nullable();
            $table->string('zoom_meeting_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_ticket_allocations');
    }
};