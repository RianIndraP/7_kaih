<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_akademik', ['aktif', 'tinggal_kelas', 'dikeluarkan', 'pindah_sekolah'])
                  ->default('aktif')
                  ->after('is_alumni');
        });

        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status_akademik');
        });
        Schema::dropIfExists('pengaturan_sistem');
    }
};
