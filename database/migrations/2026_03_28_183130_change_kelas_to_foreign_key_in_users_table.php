<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom `kelas` (string) sudah tidak ada di database yang sekarang.
        // Guard ini mencegah error "Can't DROP 'kelas'" baik di fresh install
        // maupun database lama yang kolomnya sudah dihapus manual.
        if (Schema::hasColumn('users', 'kelas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kelas');
            });
        }

        if (Schema::hasColumn('users', 'kelas_id')) {
            return;
        }

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
