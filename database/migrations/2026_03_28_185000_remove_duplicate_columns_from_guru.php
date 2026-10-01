<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom ini sudah dihapus di database yang sekarang. Guard mencegah
        // error "Can't DROP" baik di fresh install maupun database lama.
        $columns = ['nip', 'nik', 'jenis_kelamin', 'no_telepon', 'email_pribadi'];

        $existing = array_values(array_filter(
            $columns,
            fn (string $column) => Schema::hasColumn('guru', $column)
        ));

        if (empty($existing)) {
            return;
        }

        Schema::table('guru', function (Blueprint $table) use ($existing) {
            $table->dropColumn($existing);
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
