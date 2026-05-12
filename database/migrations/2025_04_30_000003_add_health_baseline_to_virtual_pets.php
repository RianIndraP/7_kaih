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
        Schema::table('virtual_pets', function (Blueprint $table) {
            $table->date('health_baseline_date')->nullable()->after('health');
            $table->json('health_baseline_habits')->nullable()->after('health_baseline_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('virtual_pets', function (Blueprint $table) {
            $table->dropColumn(['health_baseline_date', 'health_baseline_habits']);
        });
    }
};
