<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ CEK dulu sebelum create
        if (!Schema::hasTable('kelas')) {
            Schema::create('kelas', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kelas')->unique();
                $table->timestamps();
            });

            // insert default hanya kalau tabel baru dibuat
            DB::table('kelas')->insert([
                ['nama_kelas' => 'X RPL 1'],
                ['nama_kelas' => 'X RPL 2'],
                ['nama_kelas' => 'X TKJ 1'],
                ['nama_kelas' => 'X TKJ 2'],
                ['nama_kelas' => 'X TJA 1'],
                ['nama_kelas' => 'X TJA 2'],
                ['nama_kelas' => 'XI RPL 1'],
                ['nama_kelas' => 'XI RPL 2'],
                ['nama_kelas' => 'XII RPL 1'],
                ['nama_kelas' => 'XII RPL 2'],
            ]);
        }

        // ✅ CEK kolom juga
        if (!Schema::hasColumn('users', 'angkatan')) {
            Schema::table('users', function (Blueprint $table) {
                $table->year('angkatan')->nullable(); // jangan pakai after
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });

        Schema::dropIfExists('kelas');
    }
};
