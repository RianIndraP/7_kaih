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
        Schema::table('lampiran_c', function (Blueprint $table) {

            // hapus kolom yang tidak diperlukan
            $table->dropColumn(['tanggal', 'keterangan']);

            // tambahkan unique constraint
            $table->unique(['guru_id', 'murid_id', 'pertemuan'], 'unique_lampiran_c');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lampiran_c', function (Blueprint $table) {

            // balikin lagi kalau rollback
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();

            // hapus unique
            $table->dropUnique('unique_lampiran_c');
        });
    }
};
