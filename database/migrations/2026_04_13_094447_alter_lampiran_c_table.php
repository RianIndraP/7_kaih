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
        // Guard: kolom tanggal/keterangan sudah dihapus di database yang sekarang.
        // Tanpa guard, migration gagal dengan "Can't DROP 'tanggal'".
        $existing = array_values(array_filter(
            ['tanggal', 'keterangan'],
            fn (string $column) => Schema::hasColumn('lampiran_c', $column)
        ));

        if (!empty($existing)) {
            Schema::table('lampiran_c', function (Blueprint $table) use ($existing) {
                $table->dropColumn($existing);
            });
        }

        // Tambahkan unique constraint
        Schema::table('lampiran_c', function (Blueprint $table) {
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
