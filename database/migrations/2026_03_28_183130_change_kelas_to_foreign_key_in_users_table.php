<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom kelas lama
            $table->dropColumn('kelas');
        });

        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom kelas_id sebagai foreign key
            $table->foreignId('kelas_id')->nullable()->after('angkatan')->constrained('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key dan kolom kelas_id
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

        Schema::table('users', function (Blueprint $table) {
            // Kembalikan kolom kelas string
            $table->string('kelas')->nullable()->after('angkatan');
        });
    }
};
