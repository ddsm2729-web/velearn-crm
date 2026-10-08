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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('reference_id');
            $table->string('type');
            $table->string('category')->nullable();
            $table->string('subject')->nullable();
            $table->string('title')->nullable();
            $table->text('description');
            $table->string('preferred_date')->nullable();
            $table->string('preferred_slot')->nullable();
            $table->string('attachment')->nullable();
            $table->foreignId('assigned_staff_id')->nullable();
            $table->string('allocated_date')->nullable();
            $table->string('allocated_slot')->nullable();
            $table->text('notes')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};