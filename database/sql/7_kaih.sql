/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `absensi_siswa`;
CREATE TABLE `absensi_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guru_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `status` enum('hadir','sakit','izin','tidak_hadir','libur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tidak_hadir',
  `tidak_ada_pertemuan` tinyint(1) NOT NULL DEFAULT '0',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_absensi` (`siswa_id`,`pertemuan_ke`,`tanggal_mulai`),
  KEY `absensi_siswa_guru_id_foreign` (`guru_id`),
  CONSTRAINT `absensi_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `absensi_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `guru`;
CREATE TABLE `guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_pribadi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pegawai` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis_kelamin` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guru_nip_unique` (`nip`),
  UNIQUE KEY `guru_nik_unique` (`nik`),
  KEY `guru_user_id_foreign` (`user_id`),
  CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `kebiasaan_harian`;
CREATE TABLE `kebiasaan_harian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `bangun_pagi` tinyint(1) DEFAULT NULL,
  `jam_bangun` time DEFAULT NULL,
  `bangun_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sholat_subuh` tinyint(1) NOT NULL DEFAULT '0',
  `jam_sholat_subuh` time DEFAULT NULL,
  `sholat_dzuhur` tinyint(1) NOT NULL DEFAULT '0',
  `jam_sholat_dzuhur` time DEFAULT NULL,
  `sholat_ashar` tinyint(1) NOT NULL DEFAULT '0',
  `jam_sholat_ashar` time DEFAULT NULL,
  `sholat_maghrib` tinyint(1) NOT NULL DEFAULT '0',
  `jam_sholat_maghrib` time DEFAULT NULL,
  `sholat_isya` tinyint(1) NOT NULL DEFAULT '0',
  `jam_sholat_isya` time DEFAULT NULL,
  `baca_quran` tinyint(1) DEFAULT NULL,
  `quran_surah` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ibadah_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `berolahraga` tinyint(1) DEFAULT NULL,
  `jenis_olahraga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `olahraga_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `makan_sehat` tinyint(1) DEFAULT NULL,
  `makan_pagi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `makan_pagi_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_siang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `makan_siang_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_malam` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `makan_malam_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gemar_belajar` tinyint(1) DEFAULT NULL,
  `materi_belajar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `belajar_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bersama` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `masyarakat_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tidur_cepat` tinyint(1) DEFAULT NULL,
  `jam_tidur` time DEFAULT NULL,
  `tidur_catatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kebiasaan_harian_user_id_tanggal_unique` (`user_id`,`tanggal`),
  CONSTRAINT `kebiasaan_harian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kebiasaan_harian_chk_1` CHECK (json_valid(`jenis_olahraga`)),
  CONSTRAINT `kebiasaan_harian_chk_2` CHECK (json_valid(`bersama`))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pesan_bantuan`;
CREATE TABLE `pesan_bantuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama_pengirim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_ditangani',
  `balasan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_bantuan_user_id_foreign` (`user_id`),
  CONSTRAINT `pesan_bantuan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pesan_guru`;
CREATE TABLE `pesan_guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guru_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_guru_guru_id_foreign` (`guru_id`),
  KEY `pesan_guru_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `pesan_guru_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_guru_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pesan_guru_reads`;
CREATE TABLE `pesan_guru_reads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pesan_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `dibaca_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pesan_guru_reads_pesan_id_siswa_id_unique` (`pesan_id`,`siswa_id`),
  KEY `pesan_guru_reads_siswa_id_foreign` (`siswa_id`),
  CONSTRAINT `pesan_guru_reads_pesan_id_foreign` FOREIGN KEY (`pesan_id`) REFERENCES `pesan_guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_guru_reads_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `pesan_guru_siswa`;
CREATE TABLE `pesan_guru_siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `guru_id` bigint unsigned NOT NULL,
  `siswa_id` bigint unsigned NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` enum('harian','mingguan','pertemuan','bulanan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `minggu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pertemuan` int DEFAULT NULL,
  `bulan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesan_guru_siswa_siswa_id_foreign` (`siswa_id`),
  KEY `pesan_guru_siswa_guru_id_siswa_id_index` (`guru_id`,`siswa_id`),
  KEY `pesan_guru_siswa_periode_siswa_id_index` (`periode`,`siswa_id`),
  CONSTRAINT `pesan_guru_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesan_guru_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guru_wali_id` bigint unsigned DEFAULT NULL,
  `ttl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hobi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cita_cita` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teman_terbaik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `makanan_kesukaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna_kesukaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ortu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nisn_unique` (`nisn`),
  UNIQUE KEY `users_nip_unique` (`nip`),
  UNIQUE KEY `users_nik_unique` (`nik`),
  UNIQUE KEY `users_username_unique` (`username`),
  KEY `users_guru_wali_id_index` (`guru_wali_id`),
  CONSTRAINT `users_guru_wali_id_foreign` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `absensi_siswa` (`id`, `guru_id`, `siswa_id`, `pertemuan_ke`, `tanggal_mulai`, `tanggal_selesai`, `tanggal_absen`, `status`, `tidak_ada_pertemuan`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 6, '2026-03-12', '2026-03-25', '2026-03-15', 'hadir', 0, NULL, '2026-03-15 16:53:48', '2026-03-15 16:53:53'),
(2, 1, 1, 6, '2026-03-14', '2026-03-21', '2026-03-15', 'hadir', 0, NULL, '2026-03-15 17:02:01', '2026-03-15 17:02:01'),
(3, 1, 1, 7, '2026-03-28', '2026-04-04', NULL, 'libur', 1, NULL, '2026-03-25 13:26:08', '2026-03-25 13:26:08'),
(4, 1, 4, 7, '2026-03-28', '2026-04-04', NULL, 'libur', 1, NULL, '2026-03-25 13:26:08', '2026-03-25 13:26:08');
INSERT INTO `guru` (`id`, `user_id`, `nip`, `nik`, `no_telepon`, `email_pribadi`, `status_pegawai`, `unit_kerja`, `created_at`, `updated_at`, `jenis_kelamin`) VALUES
(1, 3, '1234567890123456', NULL, NULL, 'budi@7kaih.sch.id', 'Pegawai Negeri', 'SMKN 5 Banda Aceh', '2026-03-15 00:13:16', '2026-03-15 00:47:43', 'Laki-laki');
INSERT INTO `kebiasaan_harian` (`id`, `user_id`, `tanggal`, `bangun_pagi`, `jam_bangun`, `bangun_catatan`, `sholat_subuh`, `jam_sholat_subuh`, `sholat_dzuhur`, `jam_sholat_dzuhur`, `sholat_ashar`, `jam_sholat_ashar`, `sholat_maghrib`, `jam_sholat_maghrib`, `sholat_isya`, `jam_sholat_isya`, `baca_quran`, `quran_surah`, `ibadah_catatan`, `berolahraga`, `jenis_olahraga`, `olahraga_catatan`, `makan_sehat`, `makan_pagi`, `makan_pagi_done`, `makan_siang`, `makan_siang_done`, `makan_malam`, `makan_malam_done`, `makan_catatan`, `gemar_belajar`, `materi_belajar`, `belajar_catatan`, `bersama`, `masyarakat_catatan`, `tidur_cepat`, `jam_tidur`, `tidur_catatan`, `created_at`, `updated_at`) VALUES
(5, 1, '2026-03-25', 0, NULL, 'Haikal', 0, NULL, 1, '12:30:00', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'Anjay cuma Dzuhur', 0, NULL, 'Males brok', 0, NULL, 0, NULL, 0, NULL, 0, 'izin lapar', 0, NULL, 'Alergi buku', '[\"teman\"]', 'Wong saya suka kok', 0, NULL, 'Ngapain cepet cepet, cepet tua nanti', '2026-03-25 00:40:52', '2026-03-25 01:37:30'),
(6, 1, '2026-03-27', 1, '05:00:00', 'Sudah bangun saya', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Bikin nih website', 'Udh kah', '[\"keluarga\",\"publik\"]', 'Berbicara tentang banyak hal seperti', NULL, NULL, NULL, '2026-03-27 18:19:14', '2026-03-27 18:20:16');
INSERT INTO `pesan_bantuan` (`id`, `user_id`, `nama_pengirim`, `kategori`, `judul`, `isi`, `status`, `balasan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Muhammad Haikal Faiz', 'lainnya', 'Aku mau ayam', 'ayam enak', 'belum_ditangani', NULL, '2026-03-14 23:36:13', '2026-03-14 23:36:13'),
(2, 1, 'Muhammad Haikal', 'teknis', 'test', 'lorem123', 'belum_ditangani', NULL, '2026-03-24 14:06:42', '2026-03-24 14:06:42');


INSERT INTO `pesan_guru_siswa` (`id`, `guru_id`, `siswa_id`, `judul`, `isi`, `periode`, `tanggal`, `minggu`, `pertemuan`, `bulan`, `tahun`, `created_at`, `updated_at`) VALUES
(7, 1, 1, 'Umpan Balik Dari Guru Wali Perhari', 'Halo nak', 'harian', '2026-03-15', NULL, NULL, NULL, NULL, '2026-03-15 16:15:16', '2026-03-15 16:15:16'),
(8, 1, 1, 'Umpan Balik Dari Guru Wali Perminggu', 'Di isi donggg', 'mingguan', NULL, '2026-03-W2', NULL, NULL, NULL, '2026-03-15 16:16:33', '2026-03-15 16:16:33'),
(9, 1, 1, 'Umpan Balik Dari Guru Wali Perhari', 'test1234', 'harian', '2026-03-25', NULL, NULL, NULL, NULL, '2026-03-25 13:17:47', '2026-03-25 13:17:47'),
(10, 1, 4, 'Umpan Balik Dari Guru Wali Perhari', 'lorem1234567890', 'harian', '2026-03-25', NULL, NULL, NULL, NULL, '2026-03-25 13:18:21', '2026-03-25 13:18:21');
INSERT INTO `users` (`id`, `name`, `nisn`, `nip`, `nik`, `username`, `email`, `foto`, `email_verified_at`, `birth_date`, `password`, `remember_token`, `created_at`, `updated_at`, `kelas`, `guru_wali_id`, `ttl`, `hobi`, `cita_cita`, `teman_terbaik`, `makanan_kesukaan`, `warna_kesukaan`, `gender`, `no_telepon`, `no_ortu`, `latitude`, `longitude`, `alamat`, `tempat_lahir`) VALUES
(1, 'Muhammad Haikal F.', '00823117233', NULL, NULL, NULL, 'rianindrapratama2008@gmail.com', NULL, NULL, '2008-08-31', '$2y$12$sN3WmYRLCm1YfwwdJhn0h.jgPAtXqWEvN/P/N51yeCmd4PSzAIL9y', NULL, '2026-03-14 15:53:13', '2026-03-27 18:44:40', 'XII RPL 1', 1, 'Banda Aceh, 31 August 2008', 'ngoding', 'Test', 'Ga Ada', 'Ga Ada', 'Biru', 'Laki-laki', '087817956703', '085262731415', '5.54026257', '95.34856886', 'Pango Raya, Ulee Kareng, Banda Aceh, Aceh, Sumatra, 23246, Indonesia', 'Banda Aceh'),
(2, 'Admin System', NULL, NULL, NULL, 'admin', 'admin@7kaih.sch.id', NULL, NULL, '2008-08-31', '$2y$12$MEQL7AHuluQBthfnFuWiqO3uJeYtCql8tYPFRoWWImxrQ8jvHedRi', NULL, '2026-03-14 15:53:14', '2026-03-14 15:53:14', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 'Mr. Rian', NULL, '200801312020011001', NULL, NULL, 'budi@7kaih.sch.id', NULL, NULL, '2008-08-31', '$2y$12$2rX4BZrGC64qR.ORSRFuw.Lpflc6UINcNZ5CaTO89uEi9T4LF1HSO', NULL, '2026-03-14 15:53:14', '2026-03-25 01:33:29', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '5.54991712', '95.31364918', 'Jl. Test No. 123, Banda Aceh', 'Banda Aceh'),
(4, 'Rian Indra Pratama', '00812345678', NULL, NULL, NULL, 'rian2008@gmail.com', NULL, NULL, '2026-03-25', '$2y$12$XAd2a5iXwUy3Fdb6kbtrLOrUYSTw993ICeTiiGkvox1IFeNgnjmCe', NULL, NULL, '2026-03-25 13:16:27', 'X RPL 1', 1, ', 25 March 2026', NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, '5.54890000', '95.32380000', NULL, NULL);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;