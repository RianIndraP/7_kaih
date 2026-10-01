<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Migration ini duplikat dari 2026_03_28_133241 (tanpa guard hasTable).
        // Di database yang sudah ada, keduanya sudah tercatat di tabel migrations
        // sehingga tidak dijalankan ulang. Guard ini menjaga agar fresh install
        // tidak gagal, dan agar tidak menambah FK yang sama dua kali.
        if (!Schema::hasTable('pesan_guru_reads')) {
            return;
        }

        Schema::table('pesan_guru_reads', function (Blueprint $table) {
            $table->dropForeign('pesan_guru_reads_pesan_id_foreign');

            $table->foreign('pesan_id')
                  ->references('id')
                  ->on('pesan_guru_siswa')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pesan_guru_reads', function (Blueprint $table) {
            $table->dropForeign(['pesan_id']);

            $table->foreign('pesan_id')
                  ->references('id')
                  ->on('pesan_guru')
                  ->cascadeOnDelete();
        });
    }
};