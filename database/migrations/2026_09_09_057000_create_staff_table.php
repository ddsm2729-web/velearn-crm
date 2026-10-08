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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auth_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('gender')->nullable();
            $table->foreignId('role_id')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('education')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('pincode')->nullable();
            $table->foreignId('state_id')->nullable();
            $table->integer('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};