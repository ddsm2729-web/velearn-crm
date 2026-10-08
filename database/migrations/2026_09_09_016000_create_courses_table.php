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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->nullable();
            $table->string('syllabus_pdf')->nullable();
            $table->string('release_date')->nullable();
            $table->string('image')->nullable();
            $table->string('course_pdf')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->text('sub_description')->nullable();
            $table->integer('with_certificate')->default(0);
            $table->integer('recorded_content')->default(0);
            $table->string('language')->nullable();
            $table->string('cour_language')->nullable();
            $table->text('categories')->nullable();
            $table->longText('about_course')->nullable();
            $table->string('mrp_price');
            $table->string('buy_price');
            $table->string('single_student_price');
            $table->string('group_student_price');
            $table->string('offer_price');
            $table->string('course_type');
            $table->string('combo_course_type')->nullable();
            $table->boolean('status')->default(1);
            $table->string('duration')->nullable();
            $table->string('end_card_title')->nullable();
            $table->text('end_card_description')->nullable();
            $table->string('live_course_type')->nullable();
            $table->integer('split_count')->nullable();
            $table->string('combo_type')->nullable();
            $table->string('learning_title')->nullable();
            $table->text('learning_description')->nullable();
            $table->string('why_us_title')->nullable();
            $table->text('why_us_description')->nullable();
            $table->string('why_us_heading_1')->nullable();
            $table->text('why_us_desc_1')->nullable();
            $table->string('why_us_heading_2')->nullable();
            $table->text('why_us_desc_2')->nullable();
            $table->string('why_us_heading_3')->nullable();
            $table->text('why_us_desc_3')->nullable();
            $table->string('why_us_heading_4')->nullable();
            $table->text('why_us_desc_4')->nullable();
            $table->string('skills_title')->nullable();
            $table->text('skills_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};