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
        Schema::table('schedule_interviews', function (Blueprint $table) {
            $table->string('attendance')->nullable()->after('status');
            $table->text('reason')->nullable()->after('attendance');
            $table->string('result')->nullable()->after('reason');
            $table->text('hr_feedback')->nullable()->after('result');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_interviews', function (Blueprint $table) {
            $table->dropColumn(['attendance', 'reason', 'result', 'hr_feedback']);
        });
    }
};
