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
        Schema::table('session_bookings', function (Blueprint $table) {
            $table->string('zoom_meeting_id')->nullable()->after('status');
            $table->text('zoom_meet_link')->nullable()->after('zoom_meeting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_bookings', function (Blueprint $table) {
            $table->dropColumn(['zoom_meeting_id', 'zoom_meet_link']);
        });
    }
};
