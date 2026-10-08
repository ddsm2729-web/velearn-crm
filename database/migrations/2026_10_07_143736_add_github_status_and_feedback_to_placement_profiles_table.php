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
        Schema::table('placement_profiles', function (Blueprint $table) {
            $table->string('github_status')->default('pending')->after('github_url');
            $table->text('github_feedback')->nullable()->after('github_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_profiles', function (Blueprint $table) {
            $table->dropColumn(['github_status', 'github_feedback']);
        });
    }
};
