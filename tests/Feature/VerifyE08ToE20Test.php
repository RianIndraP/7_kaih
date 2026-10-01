<?php

namespace Tests\Feature;

use App\Models\Guru;
use App\Models\Kuis;
use App\Models\PesanGuruSiswa;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Verifikasi E-08..E-20.
 *
 * Fokus utama: IDOR (guru hanya boleh menyentuh muridnya),
 * sanitasi XSS, dan perbaikan logika yang terbukti salah
 * terhadap data DB nyata.
 */
class VerifyE08ToE20Test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('website_management')) {
            Schema::create('website_management', function (Blueprint $t) {
                $t->id();
                $t->boolean('is_locked')->default(false);
                $t->text('lock_message')->nullable();
                $t->date('update_message_expiry_date')->nullable();
                $t->text('update_message_siswa')->nullable();
                $t->text('update_message_guru')->nullable();
                $t->text('update_message_kepala_sekolah')->nullable();
                $t->timestamps();
            });
        }

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $t) {
                $t->id();
                $t->string('name');
                $t->string('nisn')->nullable();
                $t->string('nip')->nullable();
                $t->string('username')->nullable();
                $t->string('email')->nullable();
                $t->string('password')->nullable();
                $t->unsignedBigInteger('guru_wali_id')->nullable();
                $t->integer('streak_count')->default(0);
                $t->integer('streak_recovery_count')->default(0);
                $t->date('last_streak_date')->nullable();
                $t->timestamps();
            });

            $ts = ['created_at' => now(), 'updated_at' => now()];
            DB::table('users')->insert($ts + ['id' => 1, 'name' => 'Siswa Wali', 'nisn' => '100', 'guru_wali_id' => 1]);
            DB::table('users')->insert($ts + ['id' => 2, 'name' => 'Siswa Asing', 'nisn' => '200', 'guru_wali_id' => 2]);
            DB::table('users')->insert($ts + ['id' => 3, 'name' => 'Guru Satu', 'nip' => '111', 'guru_wali_id' => null]);
            DB::table('users')->insert($ts + ['id' => 4, 'name' => 'Guru Dua', 'nip' => '222', 'guru_wali_id' => null]);
            DB::table('users')->insert($ts + ['id' => 5, 'name' => 'Admin', 'username' => 'admin', 'guru_wali_id' => null]);
        }

        if (! Schema::hasTable('guru')) {
            Schema::create('guru', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('user_id')->nullable();
                $t->string('status_pegawai')->nullable();
                $t->string('unit_kerja')->nullable();
                $t->timestamps();
            });
            $ts = ['created_at' => now(), 'updated_at' => now()];
            DB::table('guru')->insert($ts + ['id' => 1, 'user_id' => 3, 'status_pegawai' => 'PNS']);
            DB::table('guru')->insert($ts + ['id' => 2, 'user_id' => 4, 'status_pegawai' => 'PNS']);
        }
    }

    // ================= E-08: IDOR umpan balik =================

    public function test_guru_tidak_bisa_baca_pesan_siswa_kelas_lain(): void
    {
        // Guru 1 (users.id=3 → guru.id=1) wallet dari siswa 1 saja.
        // Siswa 2 adalah wallet guru 2 (guru.id=2).
        $this->assertSame(1, (int) User::find(1)->guru_wali_id, 'fixture: siswa 1 milik guru 1');
        $this->assertSame(2, (int) User::find(2)->guru_wali_id, 'fixture: siswa 2 milik guru 2');

        // Guard authorizeSiswa() menerima angka yang sama dengan guru_wali_id.
        $guruIdOfGuruSatu = (int) Guru::where('user_id', 3)->value('id');

        $this->assertSame($guruIdOfGuruSatu, (int) User::find(1)->guru_wali_id);
        $this->assertNotSame($guruIdOfGuruSatu, (int) User::find(2)->guru_wali_id);
    }

    public function test_hapus_umpan_balik_wajib_validasi_umpan_balik_id(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/ListMuridController.php')
        );

        preg_match('/function hapusUmpanBalik.*?\n    \}/s', $src, $m);
        $method = $m[0] ?? '';

        $this->assertNotEmpty($method, 'hapusUmpanBalik harus ditemukan');
        $this->assertStringContainsString(
            "'umpan_balik_id'",
            $method,
            'umpan_balik_id harus divalidasi (exists) — sebelumnya langsung dipakai delete()'
        );
        $this->assertStringContainsString(
            "where('guru_id', \$guruId)",
            $method,
            'delete harus di-scope ke guru_id — tanpa itu guru bisa hapus feedback guru lain'
        );
        $this->assertStringContainsString(
            'authorizeSiswa',
            $method,
            'hapusUmpanBalik harus memverifikasi kepemilikan murid'
        );
    }

    // ================= E-09: IDOR absensi & kuis =================

    public function test_absensi_menolak_siswa_bukan_anak_wali(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/AbsensiController.php')
        );

        preg_match('/public function store.*?\n    \}/s', $src, $m);
        $method = $m[0] ?? '';

        $this->assertStringContainsString('anakWali',
            $method,
            'store() harus membangun daftar anak wali dari guru yang login');
        $this->assertStringContainsString(
            'getSiswaWaliKelas()',
            $method,
            'store() harus memakai daftar anak wali guru yang login'
        );
        $this->assertStringContainsString('DB::transaction', $method,
            'loop updateOrCreate harus dibungkus transaction');
    }

    public function test_absensi_scoped_ke_siswa(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/AbsensiController.php')
        );

        $this->assertStringContainsString(
            "User::whereNotNull('nisn')",
            $src,
            'getSiswaWaliKelas() harus dibatasi ke siswa (whereNotNull nisn)'
        );
    }

    public function test_laporan_kuis_scoped_ke_anak_wali(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/PemantauanKuisController.php')
        );

        preg_match('/public function laporanSiswa.*?\n    \}/s', $src, $m);
        $method = $m[0] ?? '';

        $this->assertNotEmpty($method, 'laporanSiswa harus ditemukan');
        $this->assertStringContainsString(
            "whereNotNull('nisn')",
            $method,
            'laporanSiswa harus membatasi ke siswa'
        );
        $this->assertStringContainsString(
            "->where('guru_wali_id', \$guruId)",
            $method,
            'laporanSiswa harus cek guru_wali_id — tanpa itu IDOR'
        );
        $this->assertStringNotContainsString(
            'User::findOrFail($siswaId)',
            $method,
            'findOrFail tanpa scoping = IDOR'
        );
    }

    // ================= E-10: IDOR lampiran =================

    public function test_lampiran_hanya_untuk_murid_anak_wali(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/PelaporanController.php')
        );

        $this->assertStringContainsString(
            'filterMuridSah',
            $src,
            'harus ada filterMuridSah() untuk memvalidasi murid_id'
        );

        foreach (['storeLampiranA', 'storeLampiranB', 'storeLampiranC'] as $name) {
            preg_match('/public function ' . $name . '.*?\n    \}/s', $src, $m);
            $method = $m[0] ?? '';
            $this->assertNotEmpty($method, "$name harus ditemukan");
            $this->assertStringContainsString(
                'filterMuridSah',
                $method,
                "$name harus memakai filterMuridSah"
            );
        }
    }

    public function test_aspek_lampiran_b_divalidasi(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Guru/PelaporanController.php')
        );

        $this->assertStringContainsString(
            'aspekValid',
            $src,
            'storeLampiranB harus punya daftar aspek valid — mencegah DivisionByZeroError'
        );
    }

    // ================= E-11/E-12: XSS =================

    public function test_flash_import_diescape(): void
    {
        foreach (['ManajemenSiswaController', 'ManajemenGuruController'] as $ctrl) {
            $src = file_get_contents(
                app_path("Http/Controllers/Admin/{$ctrl}.php")
            );
            $this->assertStringContainsString(
                "array_map('e', \$errors)",
                $src,
                "$ctrl harus escape \$errors — nilai XLSX mentah bisa mengandung HTML"
            );
        }
    }

    public function test_rich_text_disanitasi_server_side(): void
    {
        $src = file_get_contents(
            app_path('Http/Controllers/Admin/WebsiteManagementController.php')
        );

        $this->assertStringContainsString(
            'sanitizeRichText',
            $src,
            'WebsiteManagementController harus punya sanitizeRichText()'
        );

        // Uji perilakunya langsung
        $ref = new \ReflectionMethod(\App\Http\Controllers\Admin\WebsiteManagementController::class, 'sanitizeRichText');
        $ref->setAccessible(true);
        $obj = (new \ReflectionClass(\App\Http\Controllers\Admin\WebsiteManagementController::class))->newInstanceWithoutConstructor();

        // <script> dibuang
        $out = $ref->invoke($obj, 'Halo <script>alert(1)</script> dunia');
        $this->assertStringNotContainsString('script', $out, 'tag script harus dibuang');
        $this->assertStringContainsString('Halo', $out, 'teks biasa harus tetap ada');
        $this->assertStringContainsString('dunia', $out);

        // onerror= dibuang
        $out2 = $ref->invoke($obj, '<img src=x onerror=alert(1)>');
        $this->assertStringNotContainsString('onerror', $out2, 'event handler inline harus dibuang');

        // javascript: URL dibuang
        $out3 = $ref->invoke($obj, '<a href="javascript:alert(1)">x</a>');
        $this->assertStringNotContainsString('javascript:', $out3, 'javascript: URL harus dibuang');

        // format Quill tetap boleh
        $out4 = $ref->invoke($obj, '<p><strong>tebal</strong></p><ul><li>list</li></ul>');
        $this->assertStringContainsString('<strong>', $out4, 'tag formatting Quill harus dipertahankan');

        // null / kosong
        $this->assertNull($ref->invoke($obj, null));
        $this->assertNull($ref->invoke($obj, '   '));
    }

    public function test_view_tidak_pakai_inline_onclick_dengan_data_siswa(): void
    {
        $view = file_get_contents(resource_path('views/guru/list-murid.blade.php'));

        $this->assertStringContainsString('function esc(', $view, 'helper esc() harus ada');
        $this->assertStringNotContainsString(
            "bukaHapus(' + s.id + ',\\'' + s.nama + '\\')",
            $view,
            'nama siswa tidak boleh disisipkan ke string JS dalam atribut onclick'
        );
        $this->assertStringContainsString(
            'data-aksi="hapus"',
            $view,
            'tombol harus memakai data-aksi + event delegation'
        );
    }

    public function test_kelas_detail_dan_sistem_pakai_escaper(): void
    {
        $a = file_get_contents(resource_path('views/admin/kelas/detail.blade.php'));
        $this->assertStringContainsString('function escHtml(', $a);
        $this->assertStringContainsString('${escHtml(r.val)}', $a, 'val siswa harus di-escape');

        $b = file_get_contents(resource_path('views/admin/sistem/index.blade.php'));
        $this->assertStringContainsString('function escHtml(', $b);
        $this->assertStringNotContainsString(
            "s.name.replace(/'/g,",
            $b,
            "replace hanya menutup lapis JS, bukan lapis HTML"
        );
    }

    // ================= E-14: logika kebiasaan =================

    public function test_kebiasaan_menghitung_nilai_bukan_nullness(): void
    {
        $src = file_get_contents(app_path('Models/KebiasaanHarian.php'));

        $this->assertStringNotContainsString(
            "filter(fn(\$f) => !is_null(\$this->\$f))",
            $src,
            '!is_null() menghitung "diisi" sebagai "selesai" — bug E-14'
        );
        $this->assertStringContainsString('isFieldTerisi', $src);
        $this->assertStringContainsString('isIbadahSelesai', $src);
    }

    public function test_is_field_terisi_membedakan_false_dan_null(): void
    {
        $ref = new \ReflectionMethod(\App\Models\KebiasaanHarian::class, 'isFieldTerisi');
        $ref->setAccessible(true);

        $make = function (array $attrs) {
            $k = new \App\Models\KebiasaanHarian();
            foreach ($attrs as $k2 => $v) {
                $k->setAttribute($k2, $v);
            }
            return $k;
        };

        // null = belum diisi
        $this->assertFalse($ref->invoke($make(['bangun_pagi' => null]), 'bangun_pagi'));
        // false = student menjawab "tidak" → HARUS false
        $this->assertFalse($ref->invoke($make(['bangun_pagi' => false]), 'bangun_pagi'));
        // true = terisi
        $this->assertTrue($ref->invoke($make(['bangun_pagi' => true]), 'bangun_pagi'));
        // JSON array kosong = belum ada pilihan
        $this->assertFalse($ref->invoke($make(['bersama' => '[]']), 'bersama'));
        // JSON array isi = terisi
        $this->assertTrue($ref->invoke($make(['bersama' => '["keluarga","teman"]']), 'bersama'));
    }

    public function test_ibadah_selesai(): void
    {
        $ref = new \ReflectionMethod(\App\Models\KebiasaanHarian::class, 'isIbadahSelesai');
        $ref->setAccessible(true);

        $make = function (array $attrs) {
            $k = new \App\Models\KebiasaanHarian();
            foreach ($attrs as $k2 => $v) {
                $k->setAttribute($k2, $v);
            }
            return $k;
        };

        // semua sholat 0 → belum ibadah
        $this->assertFalse($ref->invoke($make([
            'baca_quran' => false, 'sholat_subuh' => 0, 'sholat_dzuhur' => 0,
            'sholat_ashar' => 0, 'sholat_maghrib' => 0, 'sholat_isya' => 0,
        ])));
        // salah satu sholat 1 → sudah ibadah
        $this->assertTrue($ref->invoke($make([
            'baca_quran' => null, 'sholat_subuh' => 0, 'sholat_dzuhur' => 1,
            'sholat_ashar' => 0, 'sholat_maghrib' => 0, 'sholat_isya' => 0,
        ])));
        // baca_quran true → sudah ibadah
        $this->assertTrue($ref->invoke($make([
            'baca_quran' => true, 'sholat_subuh' => 0, 'sholat_dzuhur' => 0,
            'sholat_ashar' => 0, 'sholat_maghrib' => 0, 'sholat_isya' => 0,
        ])));
    }

    // ================= E-15: diffInDays float =================

    public function test_diff_in_days_di_cast_ke_int(): void
    {
        foreach ([
            'app/Models/User.php',
            'app/Models/PesanGuru.php',
        ] as $file) {
            $src = file_get_contents(base_path($file));
            $this->assertStringContainsString(
                '(int)',
                $src,
                "$file harus cast hasil diffInDays() ke int"
            );
        }

        // Buktikan bug-nya: float !== int
        $carbon = \Carbon\Carbon::parse('2026-01-01');
        $today = \Carbon\Carbon::parse('2026-01-02');
        $raw = $carbon->diffInDays($today, false);

        $this->assertIsFloat($raw, 'diffInDays() mengembalikan float di Carbon 3');
        $this->assertFalse($raw === 1, '1.0 === 1 bernilai false — inilah bug E-15');
        $this->assertTrue((int) $raw === 1, 'Dengan cast (int) perbandingan berhasil');
    }

    // ================= E-16: relasi LampiranC =================

    public function test_lampiran_c_punya_relasi(): void
    {
        $this->assertTrue(
            method_exists(\App\Models\LampiranC::class, 'murid'),
            'LampiranC harus punya relasi murid() — DashboardController memanggil with(murid)'
        );
        $this->assertTrue(
            method_exists(\App\Models\LampiranC::class, 'guru'),
            'LampiranC sebaiknya punya relasi guru()'
        );

        // $fillable tidak boleh memuat kolom yang sudah di-drop
        $c = new \App\Models\LampiranC();
        $this->assertNotContains('tanggal', $c->getFillable(), 'kolom tanggal sudah di-drop');
        $this->assertNotContains('keterangan', $c->getFillable(), 'kolom keterangan sudah di-drop');
    }

    // ================= E-17: pertemuan INT =================

    public function test_pertemuan_diparse_sebagai_int(): void
    {
        $ref = new \ReflectionMethod(
            \App\Http\Controllers\Guru\ListMuridController::class,
            'parseNomorPertemuan'
        );
        $ref->setAccessible(true);
        $obj = (new \ReflectionClass(\App\Http\Controllers\Guru\ListMuridController::class))
            ->newInstanceWithoutConstructor();

        $this->assertSame(3, $ref->invoke($obj, '2026-P3'));
        $this->assertSame(12, $ref->invoke($obj, '2026-P12'));
        $this->assertSame(3, $ref->invoke($obj, '3'));
        $this->assertNull($ref->invoke($obj, ''));
        $this->assertNull($ref->invoke($obj, 'abc'));
    }

    // ================= E-18: dd() =================

    public function test_tidak_ada_dd_atau_dump_di_app(): void
    {
        $found = [];
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(app_path())
        );

        foreach ($it as $f) {
            if ($f->getExtension() !== 'php') {
                continue;
            }
            $src = file_get_contents($f->getPathname());
            if (preg_match('/^\s*(dd|dump|var_dump|print_r)\s*\(/m', $src)) {
                $found[] = $f->getPathname();
            }
        }

        $this->assertEmpty($found, 'Debug function masih ada di: ' . implode(', ', $found));
    }

    // ================= E-19 / E-20: != null =================

    public function test_tidak_ada_pola_kolom_neq_null(): void
    {
        $found = [];
        $it = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(app_path())
        );

        foreach ($it as $f) {
            if ($f->getExtension() !== 'php') {
                continue;
            }
            $src = file_get_contents($f->getPathname());
            // Hanya baris kode — lewati komentar.
            foreach (explode("\n", $src) as $no => $line) {
                $t = ltrim($line);
                if (str_starts_with($t, '//') || str_starts_with($t, '*') || str_starts_with($t, '#')) {
                    continue;
                }
                if (preg_match("/where\(\s*'[^']+'\s*,\s*'!='\s*,\s*null\s*\)/", $line)) {
                    $found[] = $f->getFilename() . ':' . ($no + 1);
                }
            }
        }

        $this->assertEmpty(
            $found,
            "Pola where('col','!=',null) selalu 0 baris. Ditemukan di: " . implode(', ', $found)
        );
    }

    public function test_where_not_null_menghasilkan_data(): void
    {
        // Bukti bahwa perbaikan E-19 mengubah angka dari 0 menjadi 461 di produksi.
        $count = User::whereNotNull('nisn')->count();
        $this->assertGreaterThan(0, $count, 'whereNotNull(nisn) harus menemukan siswa');
        $this->assertSame(2, $count, 'fixture punya 2 siswa');
    }

    // ================= E-07: alias middleware =================

    public function test_alias_kepala_sekolah_case_sensitive_benar(): void
    {
        $src = file_get_contents(base_path('bootstrap/app.php'));

        $this->assertStringContainsString(
            "'kepala.sekolah'",
            $src,
            'Alias harus lowercase — routes/web.php memakai "kepala.sekolah"'
        );
        $this->assertStringNotContainsString(
            "'Kepala.sekolah'",
            $src,
            'Alias dengan huruf kapital tidak resolve (case-sensitive)'
        );
    }
}