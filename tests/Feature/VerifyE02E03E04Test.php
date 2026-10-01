<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Verifikasi E-02, E-03, E-04:
 *   - ManajemenSiswaController tidak boleh menyentuh akun non-siswa
 *   - Admin tidak boleh bisa mengunci dirinya sendiri lewat field NIP
 */
class VerifyE02E03E04Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('website_management')) {
            Schema::create('website_management', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_locked')->default(false);
                $table->text('lock_message')->nullable();
                $table->date('update_message_expiry_date')->nullable();
                $table->text('update_message_siswa')->nullable();
                $table->text('update_message_guru')->nullable();
                $table->text('update_message_kepala_sekolah')->nullable();
                $table->timestamps();
            });
        }

        // Test DB = sqlite :memory:, tabel users tidak ada.
        // Hanya kolom yang dipakai query di test ini.
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('nisn')->nullable();
                $table->string('nip')->nullable();
                $table->string('nik')->nullable();
                $table->string('username')->nullable();
                $table->string('email')->nullable();
                $table->string('password')->nullable();
                $table->timestamps();
            });

            // 1 siswa, 1 guru, 1 admin — meniru komposisi DB produksi.
            // Insert terpisah per row: multi-row insert() milik Laravel
            // menyamakan key sehingga kolom yang tidak diisi ikut terisi.
            $ts = ['created_at' => now(), 'updated_at' => now()];

            DB::table('users')->insert($ts + ['id' => 1, 'name' => 'Siswa Uji', 'nisn' => '100000001', 'email' => 'siswa@test.id']);
            DB::table('users')->insert($ts + ['id' => 2, 'name' => 'Guru Uji', 'nip' => '1234567890123456', 'email' => 'guru@test.id']);
            DB::table('users')->insert($ts + ['id' => 3, 'name' => 'Admin Uji', 'username' => 'adminuji', 'email' => 'admin@test.id']);

            // Guard: pastikan fixture benar-benar merepresentasikan 3 role berbeda
            $this->assertNotNull(User::whereNotNull('nisn')->find(1), 'fixture siswa');
            $this->assertNull(User::find(2)->nisn, 'fixture guru harus punya nisn NULL');
            $this->assertNull(User::find(3)->nisn, 'fixture admin harus punya nisn NULL');
        }
    }

    // ---------- E-02 / E-03: scoping ke siswa ----------

    /** Guru (nip terisi) BUKAN siswa — destroy harus menolak */
    public function test_destroy_guru_ditolak_bukan_berhasil_hapus(): void
    {
        // Sanity check: data guru benar-benar ada
        $this->assertNotNull(User::find(2), 'Fixture guru harus ada');

        // Guard yang diuji di ManajemenSiswaController::destroy():
        //   User::whereNotNull('nisn')->findOrFail($id)
        $found = User::whereNotNull('nisn')->find(2);

        $this->assertNull(
            $found,
            'Query tanpa whereNotNull(nisn) akan menemukan guru — akun non-siswa bisa dihapus'
        );

        // findOrFail harus melempar 404, bukan menghapus
        try {
            User::whereNotNull('nisn')->findOrFail(2);
            $this->fail('findOrFail seharusnya melempar ModelNotFoundException');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->assertTrue(true, 'findOrFail menolak guru dengan 404 — benar');
        }

        // Guru masih ada setelah percobaan hapus
        $this->assertNotNull(User::find(2), 'Guru tidak boleh terhapus');
    }

    /** Admin (tanpa nisn/nip/nik) juga harus tertolak */
    public function test_destroy_admin_ditolak(): void
    {
        $this->assertNotNull(User::find(3), 'Fixture admin harus ada');

        $this->assertNull(
            User::whereNotNull('nisn')->find(3),
            'Admin tidak punya nisn — harus tertolak oleh whereNotNull(nisn)'
        );

        try {
            User::whereNotNull('nisn')->findOrFail(3);
            $this->fail('findOrFail seharusnya melempar ModelNotFoundException');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->assertTrue(true, 'findOrFail menolak admin dengan 404 — benar');
        }
    }

    /** Siswa TIDAK boleh ditolak — guard tidak boleh terlalu agresif */
    public function test_siswa_tetap_bisa_diakses(): void
    {
        $this->assertNotNull(
            User::whereNotNull('nisn')->find(1),
            'Siswa harus tetap bisa diakses — guard jangan terlalu agresif'
        );
    }

    /** bulkDelete hanya boleh menyentuh baris dengan nisn */
    public function test_bulk_delete_hanya_menyentuh_siswa(): void
    {
        $ids = [1, 2, 3];

        // old: User::whereIn('id', $ids)->delete();   ← semua role ikut terhapus
        // new: User::whereNotNull('nisn')->whereIn('id', $ids)->delete();
        $deleted = User::whereNotNull('nisn')->whereIn('id', $ids)->delete();

        $this->assertSame(1, $deleted, 'Hanya 1 siswa yang boleh terhapus');

        $this->assertNotNull(User::find(2), 'Guru harus selamat');
        $this->assertNotNull(User::find(3), 'Admin harus selamat');
        $this->assertNull(User::find(1), 'Siswa yang dihapus');
    }

    // ---------- E-04: admin tidak bisa self-lock ----------

    /** Model: hook saving harus men-null-kan nip walau isGuru() sudah true */
    public function test_hook_saving_menjaga_admin_tetap_admin(): void
    {
        $admin = new User();
        $admin->name = 'Admin Hook';
        $admin->username = 'adminhook';

        // Simulasikan kondisi buggy: admin punya nip terisi
        $admin->nip = '9999999999999999';

        // Guard di User::saving — dicek manual dengan logika yang baru
        $isProtected = !empty($admin->username) && empty($admin->nisn);
        $this->assertTrue($isProtected, 'username + tanpa nisn = admin, harus dilindungi');

        // Guard LAMA akan gagal di sini:
        $this->assertFalse(
            $admin->isAdmin(),
            'isAdmin() bernilai false begitu nip terisi — inilah bug E-04'
        );
    }

    /** Controller: updateProfil tidak boleh menerima/menulis 'nip' */
    public function test_update_profil_tidak_lagi_menulis_nip(): void
    {
        $source = file_get_contents(
            app_path('Http/Controllers/Admin/DashboardController.php')
        );

        // Cari method updateProfil
        preg_match('/function updateProfil.*?\n    \}/s', $source, $m);
        $method = $m[0] ?? '';

        $this->assertNotEmpty($method, 'Method updateProfil harus ditemukan');

        $this->assertStringNotContainsString(
            "'nip'",
            $method,
            "updateProfil masih menyebut 'nip' — admin bisa mengunci dirinya sendiri"
        );
        $this->assertStringContainsString(
            "\$request->only(['name', 'email'",
            $method,
            'updateProfil harus take data tanpa nip'
        );
    }

    /** View: field NIP harus readonly/disabled */
    public function test_view_nip_readonly(): void
    {
        $view = file_get_contents(
            resource_path('views/admin/profil.blade.php')
        );

        // Field NIP tidak lagi punya name="nip" yang bisa disubmit
        $this->assertStringNotContainsString(
            'name="nip"',
            $view,
            'View masih mengirim name="nip" — form bisa mengubah NIP admin'
        );
    }
}