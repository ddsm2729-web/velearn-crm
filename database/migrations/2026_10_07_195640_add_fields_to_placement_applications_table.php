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
        Schema::table('placement_applications', function (Blueprint $table) {
            $table->unsignedBigInteger('opening_id')->nullable()->index()->after('id');
            $table->unsignedBigInteger('user_id')->nullable()->index()->after('opening_id');
            $table->unsignedBigInteger('course_user_id')->nullable()->index()->after('user_id');
            $table->string('status')->default('applied')->after('course_user_id');
            $table->boolean('is_new')->default(true)->after('status');
            $table->timestamp('sent_to_hr_at')->nullable()->after('is_new');
            $table->text('notes')->nullable()->after('sent_to_hr_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_applications', function (Blueprint $table) {
            $table->dropColumn([
                'opening_id',
                'user_id',
                'course_user_id',
                'status',
                'is_new',
                'sent_to_hr_at',
                'notes',
            ]);
        });
    }
};
