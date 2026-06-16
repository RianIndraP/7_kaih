<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jawaban_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');

            // siswa_id mengacu ke users.id (sesuai pola absensi_siswa, lampiran_b, dst)
            $table->bigInteger('siswa_id')->unsigned();
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');

            $table->text('jawaban')->nullable();
            $table->dateTime('mulai_dikerjakan')->nullable();
            $table->dateTime('waktu_kirim')->nullable();
            $table->timestamps();

            $table->unique(['kuis_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_kuis');
    }
};