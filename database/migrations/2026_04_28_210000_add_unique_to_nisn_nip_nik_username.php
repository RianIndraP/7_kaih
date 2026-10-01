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
        // Di fresh install, unique index sudah dibuat oleh
        // 2025_01_01_000000_create_core_tables. Di database lama, index-nya
        // sudah ada juga. Yang perlu diverifikasi hanya kolumnya.
        foreach (['nisn', 'nip', 'nik', 'username'] as $column) {
            if (!Schema::hasColumn('users', $column)) {
                return;
            }
        }

        // Drop existing unique indexes if they exist
        Schema::table('users', function (Blueprint $table) {
            foreach (['nisn', 'nip', 'nik', 'username'] as $column) {
                $index = "users_{$column}_unique";

                try {
                    $table->dropUnique($index);
                } catch (\Throwable $e) {
                    // Index tidak ada — abaikan, akan dibuat ulang di bawah
                }
            }
        });

        // Re-add unique indexes
        // In MySQL, unique indexes allow multiple NULL values by default
        Schema::table('users', function (Blueprint $table) {
            foreach (['nisn', 'nip', 'nik', 'username'] as $column) {
                $table->unique($column);
            }
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
