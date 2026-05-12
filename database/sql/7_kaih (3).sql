-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2026 at 02:40 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `7_kaih`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi_pertemuan`
--

CREATE TABLE `absensi_pertemuan` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `foto_dokumentasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_pertemuan`
--

INSERT INTO `absensi_pertemuan` (`id`, `guru_id`, `pertemuan_ke`, `tanggal_mulai`, `tanggal_selesai`, `foto_dokumentasi`, `created_at`, `updated_at`) VALUES
(1, 1, 9, '2026-04-25', '2026-05-02', 'dokumentasi/SglITSDsG8P6tITnlaC2VFRKbwG2xbfojaKrMnCX.png', '2026-04-23 18:38:58', '2026-04-23 18:38:58');

-- --------------------------------------------------------

--
-- Table structure for table `absensi_siswa`
--

CREATE TABLE `absensi_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `pertemuan_ke` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `tanggal_absen` date DEFAULT NULL,
  `status` enum('hadir','sakit','izin','tidak_hadir','libur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'tidak_hadir',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `absensi_siswa`
--

INSERT INTO `absensi_siswa` (`id`, `guru_id`, `siswa_id`, `pertemuan_ke`, `tanggal_mulai`, `tanggal_selesai`, `tanggal_absen`, `status`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 6, '2026-03-12', '2026-03-25', '2026-03-15', 'hadir', NULL, '2026-03-15 09:53:48', '2026-03-15 09:53:53'),
(2, 1, 1, 6, '2026-03-14', '2026-03-21', '2026-03-29', 'hadir', NULL, '2026-03-15 10:02:01', '2026-03-28 17:14:20'),
(3, 1, 1, 7, '2026-03-28', '2026-04-04', NULL, 'libur', NULL, '2026-03-25 06:26:08', '2026-03-25 06:26:08'),
(4, 1, 4, 7, '2026-03-28', '2026-04-04', NULL, 'libur', NULL, '2026-03-25 06:26:08', '2026-03-25 06:26:08'),
(5, 1, 1, 5, '2026-02-28', '2026-03-07', NULL, 'libur', NULL, '2026-03-28 07:08:34', '2026-03-28 07:08:34'),
(6, 1, 4, 5, '2026-02-28', '2026-03-07', NULL, 'libur', NULL, '2026-03-28 07:08:34', '2026-03-28 07:08:34'),
(7, 1, 4, 6, '2026-03-14', '2026-03-21', '2026-03-29', 'hadir', NULL, '2026-03-28 17:14:20', '2026-03-28 17:14:20'),
(8, 1, 35, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:10'),
(9, 1, 36, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(10, 1, 37, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(11, 1, 40, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(12, 1, 41, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(13, 1, 42, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(14, 1, 1, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(15, 1, 34, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44'),
(16, 1, 4, 9, '2026-04-25', '2026-05-02', '2026-04-24', 'hadir', NULL, '2026-04-23 18:38:10', '2026-04-23 18:38:44');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

CREATE TABLE `fcm_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status_pegawai` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_kerja` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `user_id`, `status_pegawai`, `unit_kerja`, `created_at`, `updated_at`) VALUES
(1, 3, 'Pegawai Aku', 'SMKN 5 Banda Aceh', '2026-03-14 17:13:16', '2026-04-01 15:29:59'),
(2, 21, 'Pegawai Plenger', 'Bahasa Jawak', '2026-03-28 14:13:31', '2026-04-01 02:54:22'),
(9, 32, 'Pegawai Kontrak', 'SMK Negeri 5 Telkom Banda Aceh', '2026-04-12 14:26:29', '2026-04-12 14:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `kebiasaan_harian`
--

CREATE TABLE `kebiasaan_harian` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `bangun_pagi` tinyint(1) DEFAULT NULL,
  `jam_bangun` time DEFAULT NULL,
  `bangun_catatan` varchar(255) DEFAULT NULL,
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
  `quran_surah` varchar(255) DEFAULT NULL,
  `ibadah_catatan` varchar(255) DEFAULT NULL,
  `berolahraga` tinyint(1) DEFAULT NULL,
  `jenis_olahraga` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `olahraga_catatan` varchar(255) DEFAULT NULL,
  `makan_sehat` tinyint(1) DEFAULT NULL,
  `makan_pagi` varchar(255) DEFAULT NULL,
  `makan_pagi_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_siang` varchar(255) DEFAULT NULL,
  `makan_siang_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_malam` varchar(255) DEFAULT NULL,
  `makan_malam_done` tinyint(1) NOT NULL DEFAULT '0',
  `makan_catatan` varchar(255) DEFAULT NULL,
  `gemar_belajar` tinyint(1) DEFAULT NULL,
  `materi_belajar` varchar(255) DEFAULT NULL,
  `belajar_catatan` varchar(255) DEFAULT NULL,
  `bersama` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `masyarakat_catatan` varchar(255) DEFAULT NULL,
  `tidur_cepat` tinyint(1) DEFAULT NULL,
  `jam_tidur` time DEFAULT NULL,
  `tidur_catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kebiasaan_harian`
--

INSERT INTO `kebiasaan_harian` (`id`, `user_id`, `tanggal`, `bangun_pagi`, `jam_bangun`, `bangun_catatan`, `sholat_subuh`, `jam_sholat_subuh`, `sholat_dzuhur`, `jam_sholat_dzuhur`, `sholat_ashar`, `jam_sholat_ashar`, `sholat_maghrib`, `jam_sholat_maghrib`, `sholat_isya`, `jam_sholat_isya`, `baca_quran`, `quran_surah`, `ibadah_catatan`, `berolahraga`, `jenis_olahraga`, `olahraga_catatan`, `makan_sehat`, `makan_pagi`, `makan_pagi_done`, `makan_siang`, `makan_siang_done`, `makan_malam`, `makan_malam_done`, `makan_catatan`, `gemar_belajar`, `materi_belajar`, `belajar_catatan`, `bersama`, `masyarakat_catatan`, `tidur_cepat`, `jam_tidur`, `tidur_catatan`, `created_at`, `updated_at`) VALUES
(5, 1, '2026-03-25', 0, NULL, 'Haikal', 0, NULL, 1, '12:30:00', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'Anjay cuma Dzuhur', 0, NULL, 'Males brok', 0, NULL, 0, NULL, 0, NULL, 0, 'izin lapar', 0, NULL, 'Alergi buku', '[\"teman\"]', 'Wong saya suka kok', 0, NULL, 'Ngapain cepet cepet, cepet tua nanti', '2026-03-24 17:40:52', '2026-03-24 18:37:30'),
(6, 1, '2026-03-27', 1, '05:00:00', 'Sudah bangun saya', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 1, 'Bikin nih website', 'Udh kah', '[\"keluarga\",\"publik\"]', 'Berbicara tentang banyak hal seperti', NULL, NULL, NULL, '2026-03-27 11:19:14', '2026-03-27 11:20:16'),
(7, 1, '2026-03-28', 1, '05:30:00', 'awedadsasd', 1, '04:30:00', 1, '12:30:00', 1, '15:30:00', 1, '18:15:00', 1, '19:30:00', 1, '8', 'qwdwdqwdq', 1, '[{\"jenis\":\"padel\",\"catatan\":\"beuh padel\"}]', NULL, 1, 'nasi goyeng', 1, 'apa yang ada', 1, 'roti enak sih', 1, 'sdgsdg', 1, 'cara tidur cepat', 'fdh', '[\"keluarga\",\"teman\",\"tetangga\",\"publik\"]', 'semua aja ga sih', 1, '21:30:00', 'beuh pembelajaran nya forks', '2026-03-28 15:35:02', '2026-03-28 15:36:34'),
(8, 1, '2026-03-29', 1, '05:30:00', 'sd', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-28 17:11:04', '2026-03-28 17:11:04'),
(9, 1, '2026-04-21', 0, NULL, 'sadsad', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, 'afdsgsdg', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 16:12:15', '2026-04-21 16:51:05'),
(10, 1, '2026-04-22', 1, '05:30:00', 'wrfsdf', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-21 18:58:24', '2026-04-21 18:58:24'),
(11, 34, '2026-04-22', 1, '05:30:00', 'tyju', 0, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-22 16:38:20', '2026-04-22 16:38:20'),
(12, 1, '2026-04-23', 1, '05:30:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', 1, '04:30:00', 1, '12:30:00', 1, '15:30:00', 1, '18:15:00', 1, '19:30:00', 1, '11', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', 1, '[{\"jenis\":\"padel\",\"catatan\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate\"},{\"jenis\":\"lari\",\"catatan\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate\"},{\"jenis\":\"badminton\",\"catatan\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate\"},{\"jenis\":\"basket\",\"catatan\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate\"}]', NULL, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', 1, 'Astronomi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', '[\"keluarga\",\"teman\",\"tetangga\",\"publik\"]', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', 1, '13:00:00', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In tempor laoreet justo, non accumsan elit. Phasellus accumsan augue felis. Sed semper eros leo, id dictum tortor condimentum a. Donec elementum quam ac diam vehicula finibus. Proin vulputate', '2026-04-22 18:33:28', '2026-04-22 19:33:19');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `nama_kelas`, `color_index`, `created_at`, `updated_at`) VALUES
(1, 'X RPL 1', 4, NULL, '2026-04-21 15:57:27'),
(2, 'X RPL 2', 0, NULL, NULL),
(3, 'XI RPL 1', 0, NULL, NULL),
(4, 'XI RPL 2', 0, NULL, NULL),
(5, 'XII RPL 1', 0, NULL, NULL),
(6, 'XII RPL 2', 0, NULL, NULL),
(7, 'X TKJ 1', 0, NULL, NULL),
(8, 'X TKJ 2', 0, NULL, NULL),
(9, 'XI TKJ 1', 0, NULL, NULL),
(10, 'XI TKJ 2', 0, NULL, NULL),
(11, 'XII TKJ 1', 0, NULL, NULL),
(12, 'XII TKJ 2', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lampiran_a`
--

CREATE TABLE `lampiran_a` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `murid_id` bigint UNSIGNED NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tahun_ajaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lampiran_b`
--

CREATE TABLE `lampiran_b` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `murid_id` bigint UNSIGNED NOT NULL,
  `bulan` int NOT NULL,
  `tahun` int NOT NULL,
  `aspek` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai` decimal(3,2) DEFAULT NULL,
  `deskripsi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tindak_lanjut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lampiran_c`
--

CREATE TABLE `lampiran_c` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `murid_id` bigint UNSIGNED NOT NULL,
  `pertemuan` int NOT NULL,
  `topik` text COLLATE utf8mb4_unicode_ci,
  `tindak_lanjut` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_03_28_131838_create_sessions_table', 1),
(2, '2026_03_28_133241_update_pesan_guru_reads_foreign_key', 2),
(3, '2026_03_28_133539_update_pesan_guru_reads_foreign_key', 3),
(4, '2026_03_28_182155_add_angkatan_to_users_table', 4),
(5, '2026_03_28_183130_change_kelas_to_foreign_key_in_users_table', 5),
(7, '2026_03_28_185000_remove_duplicate_columns_from_guru', 6),
(8, '2026_03_28_204954_add_alumni_columns_to_users_table', 7),
(9, '2026_04_02_020536_create_cache_table', 8),
(10, '2026_04_05_222625_add_fcm_token_to_users_table', 8),
(11, '2026_04_07_005226_create_fcm_tokens_table', 8),
(12, '2026_04_12_012109_create_lampiran_a_table', 9),
(13, '2026_04_12_033016_create_lampiran_b_table', 9),
(14, '2026_04_13_082909_create_lampiran_c_table', 10),
(15, '2026_04_13_094447_alter_lampiran_c_table', 10),
(16, '2026_04_13_205033_drop_tidak_ada_pertemuan_from_absensi_siswa', 10),
(17, '2026_04_14_073612_create_absensi_pertemuan_table', 10),
(18, '2026_04_20_005342_add_color_index_to_kelas_table', 11),
(19, '2026_04_22_014339_add_teman_terbaik_json_to_users_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `pesan_bantuan`
--

CREATE TABLE `pesan_bantuan` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_pengirim` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_ditangani',
  `balasan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesan_bantuan`
--

INSERT INTO `pesan_bantuan` (`id`, `user_id`, `nama_pengirim`, `kategori`, `judul`, `isi`, `status`, `balasan`, `created_at`, `updated_at`) VALUES
(1, 1, 'Muhammad Haikal Faiz', 'lainnya', 'Aku mau ayam', 'ayam enak', 'belum_ditangani', NULL, '2026-03-14 16:36:13', '2026-03-14 16:36:13'),
(2, 1, 'Muhammad Haikal', 'teknis', 'test', 'lorem123', 'belum_ditangani', NULL, '2026-03-24 07:06:42', '2026-03-24 07:06:42'),
(3, 3, 'Mr. Rian', 'siswa', 'Siswa ini gini kali', 'Tolong diapakan dulu', 'belum_ditangani', NULL, '2026-04-14 02:06:48', '2026-04-14 02:06:48'),
(4, 3, 'Mr. Rian', 'teknis', 'ewrggherg', 'sdafgrstg', 'belum_ditangani', NULL, '2026-04-22 18:21:24', '2026-04-22 18:21:24'),
(5, 3, 'Mr. Rian', 'akun', 'wetgsdgs', 'sdgewryger', 'belum_ditangani', NULL, '2026-04-22 18:21:30', '2026-04-22 18:21:30'),
(6, 3, 'Mr. Rian', 'pelaporan', 'sdgferwgtdfg', 'dfgdrgyretgredfhh', 'belum_ditangani', NULL, '2026-04-22 18:21:35', '2026-04-22 18:21:35'),
(7, 3, 'Mr. Rian', 'akun', 'ewtewryrth', 'fghhrtyererg', 'belum_ditangani', NULL, '2026-04-22 18:21:41', '2026-04-22 18:21:41'),
(8, 3, 'Mr. Rian', 'teknis', 'eryrtutyjjj', 'ghjgyhikreyt', 'belum_ditangani', NULL, '2026-04-22 18:21:46', '2026-04-22 18:21:46');

-- --------------------------------------------------------

--
-- Table structure for table `pesan_guru`
--

CREATE TABLE `pesan_guru` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pesan_guru_reads`
--

CREATE TABLE `pesan_guru_reads` (
  `id` bigint UNSIGNED NOT NULL,
  `pesan_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `dibaca_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesan_guru_reads`
--

INSERT INTO `pesan_guru_reads` (`id`, `pesan_id`, `siswa_id`, `dibaca_at`) VALUES
(5, 11, 1, '2026-04-22 18:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `pesan_guru_siswa`
--

CREATE TABLE `pesan_guru_siswa` (
  `id` bigint UNSIGNED NOT NULL,
  `guru_id` bigint UNSIGNED NOT NULL,
  `siswa_id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` enum('harian','mingguan','pertemuan','bulanan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `minggu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pertemuan` int DEFAULT NULL,
  `bulan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pesan_guru_siswa`
--

INSERT INTO `pesan_guru_siswa` (`id`, `guru_id`, `siswa_id`, `judul`, `isi`, `periode`, `tanggal`, `minggu`, `pertemuan`, `bulan`, `tahun`, `created_at`, `updated_at`) VALUES
(7, 1, 1, 'Umpan Balik Dari Guru Wali Perhari', 'Halo nak', 'harian', '2026-03-15', NULL, NULL, NULL, NULL, '2026-03-15 09:15:16', '2026-03-15 09:15:16'),
(8, 1, 1, 'Umpan Balik Dari Guru Wali Perminggu', 'Di isi donggg', 'mingguan', NULL, '2026-03-W2', NULL, NULL, NULL, '2026-03-15 09:16:33', '2026-03-15 09:16:33'),
(9, 1, 1, 'Umpan Balik Dari Guru Wali Perhari', 'test1234', 'harian', '2026-03-25', NULL, NULL, NULL, NULL, '2026-03-25 06:17:47', '2026-03-25 06:17:47'),
(10, 1, 4, 'Umpan Balik Dari Guru Wali Perhari', 'lorem1234567890', 'harian', '2026-03-25', NULL, NULL, NULL, NULL, '2026-03-25 06:18:21', '2026-03-25 06:18:21'),
(11, 1, 1, 'Umpan Balik Dari Guru Wali Perhari', 'oi bro', 'harian', '2026-04-23', NULL, NULL, NULL, NULL, '2026-04-22 17:59:41', '2026-04-22 17:59:41');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('cHXo7lITEpPJS9GtY0YuEergGIrq3WDc799uFWVY', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWWRIUVE0cmdzYUY2SE1CeHJpQ3lWVjk5aFNmUUliRTNaZjNBTGd5TSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQva2ViaWFzYWFuIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L2tlYmlhc2FhbiI7czo1OiJyb3V0ZSI7czoxNzoic3R1ZGVudC5rZWJpYXNhYW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1777112474),
('I7LWbqAATyWE66ZYdMZo2CwaUy24Kn14IQ2Z82Vn', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoibEtBdExIVWtVV3VtTmNoOUw4Zzd1U1NFa0lhVE9UZUdia3N6RFZMbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0dWRlbnQvZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L2tpcmltLXBlc2FuLWJhbnR1YW4iO3M6NToicm91dGUiO3M6Mjc6InN0dWRlbnQua2lyaW0tcGVzYW4tYmFudHVhbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1777126164);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nisn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `angkatan` year DEFAULT NULL,
  `kelas_id` bigint UNSIGNED DEFAULT NULL,
  `is_alumni` tinyint(1) NOT NULL DEFAULT '0',
  `tanggal_masuk` date DEFAULT NULL,
  `ttl` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hobi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cita_cita` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teman_terbaik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `teman_terbaik_json` json DEFAULT NULL,
  `makanan_kesukaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna_kesukaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_ortu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guru_wali_id` bigint UNSIGNED DEFAULT NULL,
  `fcm_token` text COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nisn`, `nip`, `nik`, `username`, `email`, `foto`, `email_verified_at`, `birth_date`, `password`, `remember_token`, `created_at`, `updated_at`, `angkatan`, `kelas_id`, `is_alumni`, `tanggal_masuk`, `ttl`, `hobi`, `cita_cita`, `teman_terbaik`, `teman_terbaik_json`, `makanan_kesukaan`, `warna_kesukaan`, `gender`, `no_telepon`, `no_ortu`, `latitude`, `longitude`, `alamat`, `tempat_lahir`, `guru_wali_id`, `fcm_token`, `last_login_at`) VALUES
(1, 'Muhammad Haikal f', '00823117233', NULL, NULL, NULL, 'haikalfaiz3231@gmail.com', 'profile_photos/vv7tDqW15hH9GkDxHZTIwcgC5WmhX5RJd5Nqa2Vi.jpg', NULL, '2008-08-28', '$2y$12$SRnBWlus9UZ1Qt17o5ZQje89Q7Czy7k23Z83o.J0DLcGXpUFsmwja', NULL, '2026-03-14 08:53:13', '2026-04-22 18:33:10', '2025', 6, 0, NULL, 'Banda Aceh, 28 August 2008', NULL, NULL, NULL, '[{\"nama\": \"sdgdfgdf\", \"nomor\": \"08346457576\"}]', NULL, NULL, 'Laki-laki', '0878179567035', '0852627314154', 5.54026257, 95.34856886, 'Pango Raya, Ulee Kareng, Banda Aceh, Aceh, Sumatra, 23246, Indonesia', 'Banda Aceh', 1, NULL, NULL),
(2, 'Admin System', NULL, NULL, NULL, 'admin', 'admin@7kaih.sch.id', 'profile-photos/JXPYxcAexXt7iY7xlVpA9Tx9EtrD3ea76Gs1iaUi.jpg', NULL, '2008-08-31', '$2y$12$zQDmDo.oDtvYFpNGwsv/cOdIcbZpenmZJ3L54oHsHPw7Hw0yh.Ql2', NULL, '2026-03-14 08:53:14', '2026-04-22 18:15:20', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '085262745330', NULL, NULL, NULL, NULL, 'Banda Aceh', NULL, NULL, NULL),
(3, 'Mr. Rian', NULL, '200801312020011001', NULL, NULL, 'budi@7kaih.sch.id', 'profile_photos/ghuN7v17eWniR2KkuGIL9EiYpm54JzWFI4eXBfKq.jpg', NULL, '2008-08-30', '$2y$12$Bv3xHMSoX2CA2uL7HKcLzOb6i.U27lP/AyCnrnQiXzmde/v5Mr9Fq', NULL, '2026-03-14 08:53:14', '2026-04-22 19:57:50', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', '033385943334545', NULL, 5.50467895, 95.46751356, 'Jalan Blang Bintang-Krueng Raya, Montasik, Aceh Besar, Aceh, Sumatra, Indonesia', 'Banda Aceh', NULL, NULL, NULL),
(4, 'Rian Indra Pratama', '00812345678', NULL, NULL, NULL, 'rian2008@gmail.com', NULL, NULL, '2026-03-24', '$2y$12$XAd2a5iXwUy3Fdb6kbtrLOrUYSTw993ICeTiiGkvox1IFeNgnjmCe', NULL, NULL, '2026-03-28 16:08:37', '2026', 1, 0, NULL, ', 25 March 2026', NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, 5.54890000, 95.32380000, NULL, NULL, 1, NULL, NULL),
(21, 'aku', NULL, '123456789', NULL, NULL, 'saya@exam.vom', NULL, NULL, '2026-03-25', '$2y$12$BMUU3YvU0SmmGVqiUaoefO5WEuhumvxpqEZM1.pfeiUolvOvkEnYG', NULL, '2026-03-28 14:13:31', '2026-04-01 02:54:22', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', '089099088', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 'Muhammad Haikal Faiz', NULL, NULL, '111111', NULL, '0099999910@guru.7kaih.sch.id', NULL, NULL, '2008-08-30', '$2y$12$0AMo/SkiMtiuu/1sicSorOzwhY9BrTefKPvzw.r0ZQN7o7Xmdw/pW', NULL, '2026-04-12 14:26:29', '2026-04-23 10:34:13', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 'Muhammad Haikal Fa', '0099999910', NULL, NULL, NULL, '0099999910@student.7kaih.sch.id', NULL, NULL, '2008-08-30', '$2y$12$8BUuVkV7hxDMOR.eJ5k.6utoxx8oBrAzrARJM51u8HQ/xulHdoZXO', NULL, '2026-04-12 14:29:50', '2026-04-22 16:45:19', '2025', 5, 0, NULL, 'sdfdsgdsg, 30 August 2008', NULL, NULL, NULL, '[{\"nama\": \"dsghhfdhfgdh\", \"nomor\": \"1334335\"}]', NULL, NULL, 'Laki-laki', '0878179567035', '0852627314154', 5.54906497, 95.31380367, 'Jalan Sultan Iskandar Muda, Suka Ramai, Baiturrahman, Banda Aceh, Aceh, Sumatra, 23241, Indonesia', 'sdfdsgdsg', 1, NULL, NULL),
(35, 'Achda Sabilla Laqaz', '0102555000', NULL, NULL, NULL, '0102555000@student.7kaih.sch.id', NULL, NULL, '2010-01-04', '$2y$12$vr.A1YOXyFa.0hXc33X1qOLwKF5HqKVhOKafEm.FdE.hwlzIS4.Wm', NULL, '2026-04-21 19:33:00', '2026-04-21 20:25:42', '2026', 10, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(36, 'AFKARUL AHZA', '0105928862', NULL, NULL, NULL, '0105928862@student.7kaih.sch.id', NULL, NULL, '2012-04-05', '$2y$12$jBDArWYXyrhLcGJ.YavZIeUGSpVtvvI51hclnmOrc9jopCFnysfle', NULL, '2026-04-21 19:33:00', '2026-04-21 20:25:33', '2025', 11, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(37, 'Ahmad Fadil Agara', '3104476112', NULL, NULL, NULL, '3104476112@student.7kaih.sch.id', NULL, NULL, '2011-12-06', '$2y$12$rorU5uLrvtkqD25QKYqhbu2VFCGJkSk8t1pPjFxAA.XmT03LEpaRy', NULL, '2026-04-21 19:33:00', '2026-04-21 20:25:26', '2024', 11, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(40, 'BUNGA RAISATUL ADILLA', '0105819171', NULL, NULL, NULL, '0105819171@student.7kaih.sch.id', NULL, NULL, '2010-08-04', '$2y$12$xJH7B/4zZQUjW4OK4tOQm.reG7iyZ9Gz2./7HxkxALvvo/8clzi8C', NULL, '2026-04-21 19:33:01', '2026-04-21 20:26:02', '2025', 11, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(41, 'CUT FHAIRA GHINA BUSRI', '3103266711', NULL, NULL, NULL, '3103266711@student.7kaih.sch.id', NULL, NULL, '2012-05-08', '$2y$12$eHpTLDBF9ShVweeSnVj0re4lQxrO/jTMFiu29o8KRmRqq3sqt5VLS', NULL, '2026-04-21 19:33:01', '2026-04-21 20:25:49', '2025', 3, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(42, 'Denna Humaira', '0106537904', NULL, NULL, NULL, '0106537904@student.7kaih.sch.id', NULL, NULL, '2011-01-03', '$2y$12$s6SU8RHiFyqXQCi9YCxbdODLRVsQ4zTxLDmGkd3cqQe1xH5wc7hR2', NULL, '2026-04-21 19:33:01', '2026-04-21 20:25:55', '2025', 5, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Perempuan', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL),
(723, 'sdgdfbvcngfjhg', '121344365756', NULL, NULL, NULL, '121344365756@student.7kaih.sch.id', NULL, NULL, '2026-04-22', '$2y$12$KdaYi4wIu5tLooFiRpXnJuqJDvR3AvLj.3mp7inOh18xy7B22iSJK', NULL, '2026-04-22 16:43:41', '2026-04-22 17:14:25', '2024', 10, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Laki-laki', NULL, NULL, NULL, NULL, NULL, NULL, 2, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi_pertemuan`
--
ALTER TABLE `absensi_pertemuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `absensi_pertemuan_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_absensi` (`siswa_id`,`pertemuan_ke`,`tanggal_mulai`),
  ADD KEY `absensi_siswa_guru_id_foreign` (`guru_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fcm_tokens_user_id_foreign` (`user_id`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guru_user_id_foreign` (`user_id`);

--
-- Indexes for table `kebiasaan_harian`
--
ALTER TABLE `kebiasaan_harian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kebiasaan_harian_user_id_tanggal_unique` (`user_id`,`tanggal`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kelas_nama_kelas_unique` (`nama_kelas`);

--
-- Indexes for table `lampiran_a`
--
ALTER TABLE `lampiran_a`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lampiran_b`
--
ALTER TABLE `lampiran_b`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lampiran_b_guru_id_murid_id_bulan_tahun_aspek_unique` (`guru_id`,`murid_id`,`bulan`,`tahun`,`aspek`),
  ADD KEY `lampiran_b_murid_id_foreign` (`murid_id`);

--
-- Indexes for table `lampiran_c`
--
ALTER TABLE `lampiran_c`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_lampiran_c` (`guru_id`,`murid_id`,`pertemuan`),
  ADD KEY `lampiran_c_murid_id_foreign` (`murid_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan_bantuan`
--
ALTER TABLE `pesan_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesan_bantuan_user_id_foreign` (`user_id`);

--
-- Indexes for table `pesan_guru`
--
ALTER TABLE `pesan_guru`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesan_guru_guru_id_foreign` (`guru_id`),
  ADD KEY `pesan_guru_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `pesan_guru_reads`
--
ALTER TABLE `pesan_guru_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pesan_guru_reads_pesan_id_siswa_id_unique` (`pesan_id`,`siswa_id`),
  ADD KEY `pesan_guru_reads_siswa_id_foreign` (`siswa_id`);

--
-- Indexes for table `pesan_guru_siswa`
--
ALTER TABLE `pesan_guru_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pesan_guru_siswa_siswa_id_foreign` (`siswa_id`),
  ADD KEY `pesan_guru_siswa_guru_id_siswa_id_index` (`guru_id`,`siswa_id`),
  ADD KEY `pesan_guru_siswa_periode_siswa_id_index` (`periode`,`siswa_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_nisn_unique` (`nisn`),
  ADD UNIQUE KEY `users_nip_unique` (`nip`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_guru_wali_id_index` (`guru_wali_id`),
  ADD KEY `users_kelas_id_foreign` (`kelas_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi_pertemuan`
--
ALTER TABLE `absensi_pertemuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kebiasaan_harian`
--
ALTER TABLE `kebiasaan_harian`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `lampiran_a`
--
ALTER TABLE `lampiran_a`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lampiran_b`
--
ALTER TABLE `lampiran_b`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lampiran_c`
--
ALTER TABLE `lampiran_c`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pesan_bantuan`
--
ALTER TABLE `pesan_bantuan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `pesan_guru`
--
ALTER TABLE `pesan_guru`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pesan_guru_reads`
--
ALTER TABLE `pesan_guru_reads`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pesan_guru_siswa`
--
ALTER TABLE `pesan_guru_siswa`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=724;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi_pertemuan`
--
ALTER TABLE `absensi_pertemuan`
  ADD CONSTRAINT `absensi_pertemuan_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `absensi_siswa`
--
ALTER TABLE `absensi_siswa`
  ADD CONSTRAINT `absensi_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `absensi_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD CONSTRAINT `fcm_tokens_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guru`
--
ALTER TABLE `guru`
  ADD CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kebiasaan_harian`
--
ALTER TABLE `kebiasaan_harian`
  ADD CONSTRAINT `kebiasaan_harian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lampiran_b`
--
ALTER TABLE `lampiran_b`
  ADD CONSTRAINT `lampiran_b_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lampiran_b_murid_id_foreign` FOREIGN KEY (`murid_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `lampiran_c`
--
ALTER TABLE `lampiran_c`
  ADD CONSTRAINT `lampiran_c_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lampiran_c_murid_id_foreign` FOREIGN KEY (`murid_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesan_bantuan`
--
ALTER TABLE `pesan_bantuan`
  ADD CONSTRAINT `pesan_bantuan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesan_guru`
--
ALTER TABLE `pesan_guru`
  ADD CONSTRAINT `pesan_guru_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesan_guru_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesan_guru_reads`
--
ALTER TABLE `pesan_guru_reads`
  ADD CONSTRAINT `pesan_guru_reads_pesan_id_foreign` FOREIGN KEY (`pesan_id`) REFERENCES `pesan_guru_siswa` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesan_guru_reads_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pesan_guru_siswa`
--
ALTER TABLE `pesan_guru_siswa`
  ADD CONSTRAINT `pesan_guru_siswa_guru_id_foreign` FOREIGN KEY (`guru_id`) REFERENCES `guru` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pesan_guru_siswa_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_guru_wali_id_foreign` FOREIGN KEY (`guru_wali_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
