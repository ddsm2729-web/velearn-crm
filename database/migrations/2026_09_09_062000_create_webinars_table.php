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
        Schema::create('webinars', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('title');
            $table->string('category')->nullable();
            $table->string('course_level')->nullable();
            $table->string('date')->nullable();
            $table->string('from_time')->nullable();
            $table->string('to_time')->nullable();
            $table->text('meeting_link')->nullable();
            $table->integer('seats')->default(0);
            $table->text('description')->nullable();
            $table->string('professor')->nullable();
            $table->string('professor_position')->nullable();
            $table->string('professor_experience')->nullable();
            $table->text('requirements')->nullable();
            $table->integer('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webinars');
    }
};