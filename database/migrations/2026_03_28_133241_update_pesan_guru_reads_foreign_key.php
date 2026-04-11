<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pesan_guru_reads')) {
            Schema::table('pesan_guru_reads', function (Blueprint $table) {
                // Drop foreign key lama yang mengarah ke pesan_guru
                $table->dropForeign('pesan_guru_reads_pesan_id_foreign');

                // Tambah foreign key baru ke pesan_guru_siswa
                $table->foreign('pesan_id')
                    ->references('id')
                    ->on('pesan_guru_siswa')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pesan_guru_reads')) {
            Schema::table('pesan_guru_reads', function (Blueprint $table) {
                $table->dropForeign(['pesan_id']);

                $table->foreign('pesan_id')
                    ->references('id')
                    ->on('pesan_guru')
                    ->cascadeOnDelete();
            });
        }
    }
};
