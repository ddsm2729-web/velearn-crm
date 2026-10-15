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
        Schema::table('staff_meetings', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable()->change();
            $table->unsignedBigInteger('student_id')->nullable()->after('staff_id');
            $table->unsignedBigInteger('admission_id')->nullable()->after('student_id');
            $table->string('student_name')->nullable()->after('admission_id');
            $table->string('zoom_meeting_id')->nullable()->after('meet_link');
            $table->text('zoom_meet_link')->nullable()->after('zoom_meeting_id');
            $table->text('zoom_start_url')->nullable()->after('zoom_meet_link');
            $table->string('zoom_password')->nullable()->after('zoom_start_url');
            $table->unsignedBigInteger('created_by')->nullable()->after('zoom_password');
            $table->string('status')->default('upcoming')->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff_meetings', function (Blueprint $table) {
            $table->dropColumn([
                'student_id',
                'admission_id',
                'student_name',
                'zoom_meeting_id',
                'zoom_meet_link',
                'zoom_start_url',
                'zoom_password',
                'created_by',
                'status',
            ]);
        });
    }
};
