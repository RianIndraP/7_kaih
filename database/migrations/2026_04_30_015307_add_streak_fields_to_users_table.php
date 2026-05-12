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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('streak_count')->default(0)->after('teman_terbaik_json');
            $table->integer('streak_recovery_count')->default(0)->after('streak_count');
            $table->date('streak_recovery_reset_date')->nullable()->after('streak_recovery_count');
            $table->date('last_streak_date')->nullable()->after('streak_recovery_reset_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['streak_count', 'streak_recovery_count', 'streak_recovery_reset_date', 'last_streak_date']);
        });
    }
};
