<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Guard: kolom sudah ada di database yang sekarang, dan fresh install
        // juga sudah mendapatkannya dari create_core_tables. Tanpa guard ini
        // migration gagal dengan "Duplicate column name".
        $missing = array_values(array_filter(
            ['fcm_token', 'last_login_at'],
            fn (string $column) => !Schema::hasColumn('users', $column)
        ));

        if (empty($missing)) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($missing) {
            foreach ($missing as $column) {
                if ($column === 'fcm_token') {
                    $table->text('fcm_token')->nullable();
                } else {
                    $table->timestamp('last_login_at')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['fcm_token', 'last_login_at']);
        });
    }
};
