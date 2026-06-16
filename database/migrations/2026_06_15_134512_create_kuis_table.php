<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('kategori', ['literasi', 'numerasi']);
            $table->string('tema');
            $table->text('soal');
            $table->string('file_pdf');
            $table->integer('jumlah_halaman_pdf')->default(1);
            $table->dateTime('waktu_mulai');
            $table->integer('durasi_menit');
            $table->bigInteger('created_by')->unsigned();
            $table->timestamps();

            // created_by mengacu ke users.id (admin/operator)
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};