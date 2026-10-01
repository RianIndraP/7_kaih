<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom ini sudah dihapus di database yang sekarang. Guard mencegah
        // error "Can't DROP" baik di fresh install maupun database lama.
        if (!Schema::hasColumn('absensi_siswa', 'tidak_ada_pertemuan')) {
            return;
        }

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->dropColumn('tidak_ada_pertemuan');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->boolean('tidak_ada_pertemuan')->default(false);
        });
    }
};
