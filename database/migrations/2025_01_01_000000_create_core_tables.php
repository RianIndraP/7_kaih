<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Baseline: tabel inti yang TIDAK pernah punya migration create_* di repo.
 *
 * Timestamp migration ini (2025_01_01) sengaja diletak DI DEPAN
 * 2025_04_30_000001_create_virtual_pets_table, karena:
 *   - virtual_pets punya FK ke users
 *   - 2026_03_28_183130 menambah FK users.kelas_id -> kelas
 *   - 2026_05_20_085551 menambah FK kelas.guru_id -> guru
 * Tanpa tabel ini dibuat lebih dulu, fresh install gagal di migration pertama.
 *
 * SEMUA operasi guarded. Di database yang sudah berisi (produksi / DBngin),
 * migration ini no-op TOTAL — tidak menyentuh tabel maupun data yang ada.
 * Sumber kebenaran schema: database/sql/Export 1.sql
 *
 * Kolom yang "dimiliki" migration ALTER lain sengaja TIDAK dicreate di sini
 * agar ALTER tersebut tetap bekerja pada fresh install:
 *   angkatan, kelas_id, is_alumni, tanggal_masuk, teman_terbaik_json,
 *   streak_*, fcm_token, last_login_at, status_akademik, color_index(kelas)
 *
 * Sebaliknya, kolom yang akan di-DROP oleh ALTER tetap dicreate di sini:
 *   users.kelas          (di-drop 2026_03_28_183130)
 *   guru.{nip,nik,jenis_kelamin,no_telepon,email_pribadi} (di-drop 2026_03_28_185000)
 *   absensi_siswa.tidak_ada_pertemuan (di-drop 2026_04_13_205033)
 */
return new class extends Migration
{
    /** @var array<string,true> tabel yang dibuat oleh migration ini */
    private array $created = [];

    public function up(): void
    {
        $this->createUsers();
        $this->createGuru();
        $this->createKelas();
        $this->createKebiasaanHarian();
        $this->createAbsensiSiswa();
        $this->createPesanGuruSiswa();
        $this->createPesanGuru();
        $this->createPesanGuruReads();
        $this->createPesanBantuan();
        $this->createPasswordResetTokens();
        $this->addCrossForeignKeys();
    }

    private function createUsers(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nisn')->nullable()->unique();
            $table->string('nip')->nullable()->unique();
            $table->string('nik')->nullable()->unique();
            $table->string('username')->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('foto')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Kolom profil — dibuat manual, tidak ada migration-nya
            $table->string('ttl')->nullable();
            $table->string('hobi')->nullable();
            $table->string('cita_cita')->nullable();
            $table->string('teman_terbaik')->nullable();
            $table->string('makanan_kesukaan')->nullable();
            $table->string('warna_kesukaan')->nullable();
            $table->string('gender')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('no_ortu')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('alamat')->nullable();
            $table->string('tempat_lahir')->nullable();

            $table->unsignedBigInteger('guru_wali_id')->nullable()->index();

            // Di-drop oleh 2026_03_28_183130. Dibuat di sini supaya
            // migration tersebut tidak error saat fresh install.
            $table->string('kelas')->nullable();
        });

        $this->created['users'] = true;
    }

    private function createGuru(): void
    {
        if (Schema::hasTable('guru')) {
            return;
        }

        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();

            // Di-drop oleh 2026_03_28_185000 (sudah ada di tabel users)
            $table->string('nip')->nullable();
            $table->string('nik')->nullable();
            $table->string('jenis_kelamin')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('email_pribadi')->nullable();

            $table->string('status_pegawai')->nullable();
            $table->string('unit_kerja')->nullable();
            $table->timestamps();
        });

        $this->created['guru'] = true;
    }

    private function createKelas(): void
    {
        // 2026_03_28_182155 sudah buat kelas + nama_kelas unique (dengan guard).
        // color_index & guru_id ditambahkan 2026_04_20 & 2026_05_20.
        // Di sini hanya dijamin tabelnya ada bila migration tsb tidak dijalankan.
        if (Schema::hasTable('kelas')) {
            return;
        }

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas')->unique();
            $table->timestamps();
        });

        $this->created['kelas'] = true;
    }

    private function createKebiasaanHarian(): void
    {
        if (Schema::hasTable('kebiasaan_harian')) {
            return;
        }

        Schema::create('kebiasaan_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('tanggal');

            // 1. Bangun pagi
            $table->boolean('bangun_pagi')->nullable();
            $table->time('jam_bangun')->nullable();
            $table->string('bangun_catatan')->nullable();

            // 2. Beribadah — kolom sholat_* NOT NULL DEFAULT 0,
            // berbeda dari kebiasaan lain yang nullable
            $table->boolean('sholat_subuh')->default(false);
            $table->time('jam_sholat_subuh')->nullable();
            $table->boolean('sholat_dzuhur')->default(false);
            $table->time('jam_sholat_dzuhur')->nullable();
            $table->boolean('sholat_ashar')->default(false);
            $table->time('jam_sholat_ashar')->nullable();
            $table->boolean('sholat_maghrib')->default(false);
            $table->time('jam_sholat_maghrib')->nullable();
            $table->boolean('sholat_isya')->default(false);
            $table->time('jam_sholat_isya')->nullable();
            $table->boolean('baca_quran')->nullable();
            $table->string('quran_surah')->nullable();
            $table->string('ibadah_catatan')->nullable();

            // 3. Berolahraga
            $table->boolean('berolahraga')->nullable();
            $table->text('jenis_olahraga')->nullable();
            $table->string('olahraga_catatan')->nullable();

            // 4. Makan sehat
            $table->boolean('makan_sehat')->nullable();
            $table->string('makan_pagi')->nullable();
            $table->boolean('makan_pagi_done')->default(false);
            $table->string('makan_siang')->nullable();
            $table->boolean('makan_siang_done')->default(false);
            $table->string('makan_malam')->nullable();
            $table->boolean('makan_malam_done')->default(false);
            $table->string('makan_catatan')->nullable();

            // 5. Gemar belajar
            $table->boolean('gemar_belajar')->nullable();
            $table->string('materi_belajar')->nullable();
            $table->string('belajar_catatan')->nullable();

            // 6. Bermasyarakat — kolom longtext, di-cast 'array' di model
            $table->text('bersama')->nullable();
            $table->string('masyarakat_catatan')->nullable();

            // 7. Tidur cepat
            $table->boolean('tidur_cepat')->nullable();
            $table->time('jam_tidur')->nullable();
            $table->string('tidur_catatan')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'tanggal']);
        });

        Schema::table('kebiasaan_harian', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $this->created['kebiasaan_harian'] = true;
    }

    private function createAbsensiSiswa(): void
    {
        if (Schema::hasTable('absensi_siswa')) {
            return;
        }

        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('siswa_id');
            $table->integer('pertemuan_ke');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_absen')->nullable();
            $table->enum('status', ['hadir', 'sakit', 'izin', 'tidak_hadir', 'libur'])
                ->default('tidak_hadir');
            $table->text('keterangan')->nullable();

            // Di-drop oleh 2026_04_13_205033
            $table->boolean('tidak_ada_pertemuan')->nullable();

            $table->timestamps();

            // Dipakai updateOrCreate() di AbsensiController
            $table->unique(['siswa_id', 'pertemuan_ke', 'tanggal_mulai'], 'unique_absensi');
        });

        Schema::table('absensi_siswa', function (Blueprint $table) {
            $table->foreign('guru_id')->references('id')->on('guru')->cascadeOnDelete();
            $table->foreign('siswa_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $this->created['absensi_siswa'] = true;
    }

    private function createPesanGuruSiswa(): void
    {
        if (Schema::hasTable('pesan_guru_siswa')) {
            return;
        }

        Schema::create('pesan_guru_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('siswa_id');
            $table->string('judul');
            $table->text('isi');
            $table->enum('periode', ['harian', 'mingguan', 'pertemuan', 'bulanan']);
            $table->date('tanggal')->nullable();
            $table->string('minggu')->nullable();
            $table->integer('pertemuan')->nullable();
            $table->string('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->timestamps();

            $table->index(['guru_id', 'siswa_id'], 'pesan_guru_siswa_guru_id_siswa_id_index');
            $table->index(['periode', 'siswa_id'], 'pesan_guru_siswa_periode_siswa_id_index');
        });

        Schema::table('pesan_guru_siswa', function (Blueprint $table) {
            $table->foreign('guru_id')->references('id')->on('guru')->cascadeOnDelete();
            $table->foreign('siswa_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $this->created['pesan_guru_siswa'] = true;
    }

    private function createPesanGuru(): void
    {
        // Tabel legacy — semua controller aktif memakai pesan_guru_siswa.
        // Tetap dicreate agar schema tidak bolong.
        if (Schema::hasTable('pesan_guru')) {
            return;
        }

        Schema::create('pesan_guru', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('siswa_id');
            $table->string('judul');
            $table->text('isi');
            $table->timestamps();

            $table->foreign('guru_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('siswa_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $this->created['pesan_guru'] = true;
    }

    private function createPesanGuruReads(): void
    {
        if (Schema::hasTable('pesan_guru_reads')) {
            return;
        }

        Schema::create('pesan_guru_reads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pesan_id');
            $table->unsignedBigInteger('siswa_id');
            $table->timestamp('dibaca_at')->useCurrent();

            $table->unique(['pesan_id', 'siswa_id']);
        });

        Schema::table('pesan_guru_reads', function (Blueprint $table) {
            $table->foreign('siswa_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // FK ke pesan_guru_siswa ditambahkan di addCrossForeignKeys(),
        // karena dua tabel ini saling merujuk.

        $this->created['pesan_guru_reads'] = true;
    }

    private function createPesanBantuan(): void
    {
        if (Schema::hasTable('pesan_bantuan')) {
            return;
        }

        Schema::create('pesan_bantuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nama_pengirim');
            $table->string('kategori');
            $table->string('judul');
            $table->text('isi');
            $table->string('status')->default('belum_ditangani');
            $table->text('balasan')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        $this->created['pesan_bantuan'] = true;
    }

    private function createPasswordResetTokens(): void
    {
        // config/auth.php menunjuk tabel ini, tapi tidak ada migration-nya.
        if (Schema::hasTable('password_reset_tokens')) {
            return;
        }

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        $this->created['password_reset_tokens'] = true;
    }

    /**
     * FK yang saling merujuk, ditambahkan setelah semua tabel ada:
     *   guru.user_id -> users.id          (butuh tabel users)
     *   users.guru_wali_id -> guru.id      (butuh tabel guru)
     *   pesan_guru_reads.pesan_id -> pesan_guru_siswa.id
     *
     * Hanya dijalankan untuk tabel yang DIBUAT migration ini. Kalau tabel sudah
     * ada sebelumnya, FK-nya sudah ada juga — memanggil dropForeign/addForeign
     * lagi akan menghasilkan Duplicate foreign key constraint name.
     */
    private function addCrossForeignKeys(): void
    {
        if (isset($this->created['guru'])) {
            Schema::table('guru', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }

        if (isset($this->created['users']) && isset($this->created['guru'])) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('guru_wali_id')->references('id')->on('guru')->nullOnDelete();
            });
        }

        if (isset($this->created['pesan_guru_reads']) && isset($this->created['pesan_guru_siswa'])) {
            Schema::table('pesan_guru_reads', function (Blueprint $table) {
                $table->foreign('pesan_id')->references('id')->on('pesan_guru_siswa')->cascadeOnDelete();
            });
        }
    }
};