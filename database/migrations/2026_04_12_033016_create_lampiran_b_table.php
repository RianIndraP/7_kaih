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
        Schema::create('lampiran_b', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->foreignId('murid_id')->constrained('users')->cascadeOnDelete();
            $table->integer('bulan'); // 1 - 12
            $table->integer('tahun'); // 2026
            $table->string('aspek'); // akademik, karakter, dll
            $table->decimal('nilai', 3, 2)->nullable(); // hasil hitung
            $table->string('deskripsi')->nullable(); // hasil otomatis
            $table->string('tindak_lanjut')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // 🔐 anti duplikat data
            $table->unique(['guru_id', 'murid_id', 'bulan', 'tahun', 'aspek']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_b');
    }
};
