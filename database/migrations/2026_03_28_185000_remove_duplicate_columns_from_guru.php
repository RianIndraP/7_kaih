<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            // Hapus kolom yang sudah ada di tabel users
            $table->dropColumn(['nip', 'nik', 'jenis_kelamin', 'no_telepon', 'email_pribadi']);
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            // Tambahkan kembali kolom jika rollback
            $table->string('nip')->nullable()->after('user_id');
            $table->string('nik')->nullable()->after('nip');
            $table->string('jenis_kelamin')->nullable()->after('nik');
            $table->string('no_telepon')->nullable()->after('jenis_kelamin');
            $table->string('email_pribadi')->nullable()->after('no_telepon');
        });
    }
};
