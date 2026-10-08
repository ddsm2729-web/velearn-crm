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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('register_number')->nullable();
            $table->string('my_operator_id', 100)->nullable();
            $table->string('caller_number', 20)->nullable();
            $table->string('secondary_number', 20)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('location')->nullable();
            $table->string('source')->nullable();
            $table->string('conversation_start_date')->nullable();
            $table->string('conversation_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};