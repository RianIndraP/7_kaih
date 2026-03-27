CREATE TABLE `absensi_siswa`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `guru_id` BIGINT UNSIGNED NOT NULL,
    `siswa_id` BIGINT UNSIGNED NOT NULL,
    `pertemuan_ke` INT NOT NULL,
    `tanggal_mulai` DATE NOT NULL,
    `tanggal_selesai` DATE NOT NULL,
    `tanggal_absen` DATE NULL,
    `status` ENUM(
        'hadir',
        'sakit',
        'izin',
        'tidak_hadir',
        'libur'
    ) NOT NULL DEFAULT 'tidak_hadir',
    `tidak_ada_pertemuan` TINYINT(1) NOT NULL,
    `keterangan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
ALTER TABLE
    `absensi_siswa` ADD UNIQUE `absensi_siswa_siswa_id_pertemuan_ke_tanggal_mulai_unique`(
        `siswa_id`,
        `pertemuan_ke`,
        `tanggal_mulai`
    );
ALTER TABLE
    `absensi_siswa` ADD INDEX `absensi_siswa_guru_id_index`(`guru_id`);
CREATE TABLE `guru`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `nip` VARCHAR(255) NULL,
    `nik` VARCHAR(255) NULL,
    `no_telepon` VARCHAR(255) NULL,
    `email_pribadi` VARCHAR(255) NULL,
    `status_pegawai` VARCHAR(255) NULL,
    `unit_kerja` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `jenis_kelamin` VARCHAR(40) NOT NULL
);
ALTER TABLE
    `guru` ADD INDEX `guru_user_id_index`(`user_id`);
ALTER TABLE
    `guru` ADD UNIQUE `guru_nip_unique`(`nip`);
ALTER TABLE
    `guru` ADD UNIQUE `guru_nik_unique`(`nik`);
CREATE TABLE `kebiasaan_harian`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `tanggal` DATE NOT NULL,
    `bangun_pagi` TINYINT(1) NULL,
    `jam_bangun` TIME NULL,
    `bangun_catatan` VARCHAR(255) NULL,
    `sholat_subuh` TINYINT(1) NOT NULL,
    `jam_sholat_subuh` TIME NULL,
    `sholat_dzuhur` TINYINT(1) NOT NULL,
    `jam_sholat_dzuhur` TIME NULL,
    `sholat_ashar` TINYINT(1) NOT NULL,
    `jam_sholat_ashar` TIME NULL,
    `sholat_maghrib` TINYINT(1) NOT NULL,
    `jam_sholat_maghrib` TIME NULL,
    `sholat_isya` TINYINT(1) NOT NULL,
    `jam_sholat_isya` TIME NULL,
    `baca_quran` TINYINT(1) NULL,
    `quran_surah` VARCHAR(255) NULL,
    `ibadah_catatan` VARCHAR(255) NULL,
    `berolahraga` TINYINT(1) NULL,
    `jenis_olahraga` LONGTEXT NULL,
    `olahraga_catatan` VARCHAR(255) NULL,
    `makan_sehat` TINYINT(1) NULL,
    `makan_pagi` VARCHAR(255) NULL,
    `makan_pagi_done` TINYINT(1) NOT NULL,
    `makan_siang` VARCHAR(255) NULL,
    `makan_siang_done` TINYINT(1) NOT NULL,
    `makan_malam` VARCHAR(255) NULL,
    `makan_malam_done` TINYINT(1) NOT NULL,
    `makan_catatan` VARCHAR(255) NULL,
    `gemar_belajar` TINYINT(1) NULL,
    `materi_belajar` VARCHAR(255) NULL,
    `belajar_catatan` VARCHAR(255) NULL,
    `bersama` LONGTEXT NULL,
    `masyarakat_catatan` VARCHAR(255) NULL,
    `tidur_cepat` TINYINT(1) NULL,
    `jam_tidur` TIME NULL,
    `tidur_catatan` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
ALTER TABLE
    `kebiasaan_harian` ADD UNIQUE `kebiasaan_harian_user_id_tanggal_unique`(`user_id`, `tanggal`);
CREATE TABLE `pesan_bantuan`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `nama_pengirim` VARCHAR(255) NOT NULL,
    `kategori` VARCHAR(255) NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `isi` TEXT NOT NULL,
    `status` VARCHAR(255) NOT NULL DEFAULT 'belum_ditangani',
    `balasan` TEXT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
ALTER TABLE
    `pesan_bantuan` ADD INDEX `pesan_bantuan_user_id_index`(`user_id`);
CREATE TABLE `pesan_guru`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `guru_id` BIGINT UNSIGNED NOT NULL,
    `siswa_id` BIGINT UNSIGNED NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `isi` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
ALTER TABLE
    `pesan_guru` ADD INDEX `pesan_guru_guru_id_index`(`guru_id`);
ALTER TABLE
    `pesan_guru` ADD INDEX `pesan_guru_siswa_id_index`(`siswa_id`);
CREATE TABLE `pesan_guru_reads`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pesan_id` BIGINT UNSIGNED NOT NULL,
    `siswa_id` BIGINT UNSIGNED NOT NULL,
    `dibaca_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP());
ALTER TABLE
    `pesan_guru_reads` ADD UNIQUE `pesan_guru_reads_pesan_id_siswa_id_unique`(`pesan_id`, `siswa_id`);
ALTER TABLE
    `pesan_guru_reads` ADD INDEX `pesan_guru_reads_siswa_id_index`(`siswa_id`);
CREATE TABLE `pesan_guru_siswa`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `guru_id` BIGINT UNSIGNED NOT NULL,
    `siswa_id` BIGINT UNSIGNED NOT NULL,
    `judul` VARCHAR(255) NOT NULL,
    `isi` TEXT NOT NULL,
    `periode` ENUM(
        'harian',
        'mingguan',
        'pertemuan',
        'bulanan'
    ) NOT NULL,
    `tanggal` DATE NULL,
    `minggu` VARCHAR(255) NULL,
    `pertemuan` INT NULL,
    `bulan` VARCHAR(255) NULL,
    `tahun` INT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL
);
ALTER TABLE
    `pesan_guru_siswa` ADD INDEX `pesan_guru_siswa_periode_siswa_id_index`(`periode`, `siswa_id`);
ALTER TABLE
    `pesan_guru_siswa` ADD INDEX `pesan_guru_siswa_guru_id_siswa_id_index`(`guru_id`, `siswa_id`);
ALTER TABLE
    `pesan_guru_siswa` ADD INDEX `pesan_guru_siswa_siswa_id_index`(`siswa_id`);
CREATE TABLE `users`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `nisn` VARCHAR(255) NULL,
    `nip` VARCHAR(255) NULL,
    `nik` VARCHAR(255) NULL,
    `username` VARCHAR(255) NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL,
    `foto` VARCHAR(255) NULL,
    `birth_date` DATE NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `kelas` VARCHAR(255) NULL,
    `guru_wali_id` BIGINT UNSIGNED NULL,
    `ttl` VARCHAR(255) NULL,
    `hobi` VARCHAR(255) NULL,
    `cita_cita` VARCHAR(255) NULL,
    `teman_terbaik` VARCHAR(255) NULL,
    `makanan_kesukaan` VARCHAR(255) NULL,
    `warna_kesukaan` VARCHAR(255) NULL,
    `gender` VARCHAR(255) NULL,
    `no_telepon` VARCHAR(255) NULL,
    `no_ortu` VARCHAR(255) NULL,
    `latitude` DECIMAL(10, 8) NULL,
    `longitude` DECIMAL(11, 8) NULL,
    `alamat` TEXT NULL,
    `tempat_lahir` VARCHAR(255) NULL
);
ALTER TABLE
    `users` ADD UNIQUE `users_nisn_unique`(`nisn`);
ALTER TABLE
    `users` ADD UNIQUE `users_nip_unique`(`nip`);
ALTER TABLE
    `users` ADD UNIQUE `users_nik_unique`(`nik`);
ALTER TABLE
    `users` ADD UNIQUE `users_username_unique`(`username`);
ALTER TABLE
    `users` ADD UNIQUE `users_email_unique`(`email`);
ALTER TABLE
    `users` ADD INDEX `users_guru_wali_id_index`(`guru_wali_id`);
ALTER TABLE
    `pesan_guru_reads` ADD CONSTRAINT `pesan_guru_reads_siswa_id_foreign` FOREIGN KEY(`siswa_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `kebiasaan_harian` ADD CONSTRAINT `kebiasaan_harian_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `absensi_siswa` ADD CONSTRAINT `absensi_siswa_siswa_id_foreign` FOREIGN KEY(`siswa_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `pesan_guru_siswa` ADD CONSTRAINT `pesan_guru_siswa_guru_id_foreign` FOREIGN KEY(`guru_id`) REFERENCES `guru`(`id`);
ALTER TABLE
    `pesan_guru` ADD CONSTRAINT `pesan_guru_siswa_id_foreign` FOREIGN KEY(`siswa_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `pesan_bantuan` ADD CONSTRAINT `pesan_bantuan_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `users` ADD CONSTRAINT `users_guru_wali_id_foreign` FOREIGN KEY(`guru_wali_id`) REFERENCES `guru`(`id`);
ALTER TABLE
    `pesan_guru_siswa` ADD CONSTRAINT `pesan_guru_siswa_siswa_id_foreign` FOREIGN KEY(`siswa_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `absensi_siswa` ADD CONSTRAINT `absensi_siswa_guru_id_foreign` FOREIGN KEY(`guru_id`) REFERENCES `guru`(`id`);
ALTER TABLE
    `guru` ADD CONSTRAINT `guru_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`);
ALTER TABLE
    `pesan_guru_reads` ADD CONSTRAINT `pesan_guru_reads_pesan_id_foreign` FOREIGN KEY(`pesan_id`) REFERENCES `pesan_guru`(`id`);
ALTER TABLE
    `pesan_guru` ADD CONSTRAINT `pesan_guru_guru_id_foreign` FOREIGN KEY(`guru_id`) REFERENCES `users`(`id`);