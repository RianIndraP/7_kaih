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
            // Drop existing unique indexes if they exist
            $table->dropUnique(['nisn']);
            $table->dropUnique(['nip']);
            $table->dropUnique(['nik']);
            $table->dropUnique(['username']);
        });

        // Re-add unique indexes
        // In MySQL, unique indexes allow multiple NULL values by default
        Schema::table('users', function (Blueprint $table) {
            $table->unique('nisn');
            $table->unique('nip');
            $table->unique('nik');
            $table->unique('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nisn']);
            $table->dropUnique(['nip']);
            $table->dropUnique(['nik']);
            $table->dropUnique(['username']);
        });
    }
};
