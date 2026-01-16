-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 13 Jan 2026 pada 07.29
-- Versi server: 8.4.3
-- Versi PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `eksperimen`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dashboard_settings`
--

CREATE TABLE `dashboard_settings` (
  `id` int NOT NULL,
  `role` enum('user','pengelola_tps','admin_pusat','super_admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_type` enum('stat_cards','waste_summary','recent_activity','quick_actions','price_info','tps_operations') COLLATE utf8mb4_unicode_ci NOT NULL,
  `widget_config` json DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `dashboard_settings`
--

INSERT INTO `dashboard_settings` (`id`, `role`, `widget_key`, `widget_name`, `widget_type`, `widget_config`, `is_enabled`, `display_order`, `created_at`, `updated_at`) VALUES
(1, 'pengelola_tps', 'stat_cards', 'Statistics Cards', 'stat_cards', '{\"show_draft\": true, \"show_pending\": true, \"show_approved\": true, \"show_revision\": true}', 1, 1, '2026-01-09 01:47:10', '2026-01-09 01:47:10'),
(2, 'pengelola_tps', 'waste_summary', 'Waste Summary', 'waste_summary', '{\"show_details\": true, \"show_value_calculation\": true}', 1, 2, '2026-01-09 01:47:10', '2026-01-09 01:47:10'),
(3, 'pengelola_tps', 'recent_activity', 'Recent Activity', 'recent_activity', '{\"max_items\": 5}', 1, 3, '2026-01-09 01:47:10', '2026-01-09 01:47:10'),
(4, 'pengelola_tps', 'quick_actions', 'Quick Actions', 'quick_actions', '{\"show_export\": true, \"show_reports\": true, \"show_input_form\": true}', 1, 4, '2026-01-09 01:47:10', '2026-01-09 01:47:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feature_toggles`
--

CREATE TABLE `feature_toggles` (
  `id` int UNSIGNED NOT NULL,
  `feature_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Unique identifier for feature',
  `feature_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Human readable feature name',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'Feature description',
  `category` enum('dashboard','waste_management','statistics','ui_components','navigation','reports') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dashboard',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Feature enabled/disabled',
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `target_roles` text COLLATE utf8mb4_unicode_ci,
  `config` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `feature_toggles`
--

INSERT INTO `feature_toggles` (`id`, `feature_key`, `feature_name`, `description`, `category`, `is_enabled`, `created_by`, `updated_by`, `created_at`, `updated_at`, `target_roles`, `config`) VALUES
(1, 'dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Tampilkan kartu statistik (Disetujui, Perlu Revisi, dll)', 'dashboard', 1, NULL, 1, '2026-01-12 04:10:21', '2026-01-12 19:12:05', '[\"admin_pusat\",\"user\",\"pengelola_tps\"]', '{\"show_approved\":true,\"show_pending\":true,\"show_rejected\":true,\"show_draft\":true}'),
(2, 'dashboard_waste_summary', 'Dashboard Waste Summary', 'Tampilkan ringkasan waste management di dashboard', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\",\"user\",\"pengelola_tps\"]', '{}'),
(3, 'dashboard_recent_activity', 'Dashboard Recent Activity', 'Tampilkan aktivitas terbaru di dashboard', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\",\"user\",\"pengelola_tps\"]', '{\"max_items\":10}'),
(4, 'detailed_statistics', 'Detailed Statistics', 'Statistik detail dan grafik di dashboard', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\",\"user\"]', '{}'),
(5, 'waste_management', 'Waste Management', 'Fitur kelola data sampah (input, edit, delete)', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"user\",\"pengelola_tps\"]', '{}'),
(6, 'waste_value_calculation', 'Waste Value Calculation', 'Hitung dan tampilkan nilai ekonomis sampah', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"user\",\"pengelola_tps\"]', '{\"show_price_breakdown\":true}'),
(7, 'waste_export_feature', 'Waste Export Feature', 'Tombol export data waste ke Excel/PDF', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\",\"user\",\"pengelola_tps\"]', '{\"formats\":[\"excel\",\"pdf\",\"csv\"]}'),
(8, 'price_management', 'Price Management', 'Menu Manajemen Harga sampah', 'dashboard', 1, NULL, 1, '2026-01-12 04:10:21', '2026-01-11 22:41:20', '[\"admin_pusat\"]', '{}'),
(9, 'user_management', 'User Management', 'Menu User Management', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\",\"super_admin\"]', '{}'),
(10, 'review_system', 'Review System', 'Review dan approve data waste', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\"]', '{}'),
(11, 'reporting', 'Reporting & Analytics', 'Menu Laporan & Monitoring', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"admin_pusat\"]', '{}'),
(12, 'sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat (Input Data, Export) di dashboard', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"user\",\"pengelola_tps\"]', '{}'),
(13, 'help_tooltips', 'Help Tooltips', 'Tooltip dan bantuan di UI', 'dashboard', 1, NULL, NULL, '2026-01-12 04:10:21', '2026-01-12 04:10:21', '[\"user\",\"pengelola_tps\"]', '{}'),
(14, 'advanced_menu_items', 'Advanced Menu Items', 'Menu dan fitur advanced untuk user', 'dashboard', 1, NULL, 1, '2026-01-12 04:10:21', '2026-01-12 19:12:18', '[\"user\",\"pengelola_tps\"]', '{}'),
(15, 'real_time_updates', 'Real-time Updates', 'Auto refresh data dashboard secara real-time', 'dashboard', 1, NULL, 1, '2026-01-12 04:10:21', '2026-01-11 22:41:30', '[\"user\",\"pengelola_tps\"]', '{\"refresh_interval\":30,\"enabled\":false}');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feature_toggle_logs`
--

CREATE TABLE `feature_toggle_logs` (
  `id` int UNSIGNED NOT NULL,
  `feature_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` enum('enabled','disabled','updated','created') COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` json DEFAULT NULL,
  `new_value` json DEFAULT NULL,
  `admin_id` int UNSIGNED NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_perubahan_harga`
--

CREATE TABLE `log_perubahan_harga` (
  `id` int NOT NULL,
  `master_harga_id` int NOT NULL COMMENT 'ID dari tabel harga_sampah',
  `jenis_sampah` varchar(100) NOT NULL,
  `harga_lama` decimal(10,2) DEFAULT NULL,
  `harga_baru` decimal(10,2) NOT NULL,
  `perubahan_harga` decimal(10,2) DEFAULT NULL COMMENT 'Selisih harga',
  `persentase_perubahan` decimal(5,2) DEFAULT NULL COMMENT 'Persentase perubahan',
  `alasan_perubahan` text,
  `status_perubahan` enum('pending','approved','rejected') DEFAULT 'approved',
  `tanggal_berlaku` date NOT NULL,
  `created_by` int NOT NULL COMMENT 'User ID yang mengubah',
  `approved_by` int DEFAULT NULL COMMENT 'User ID yang approve',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log perubahan harga sampah';

--
-- Dumping data untuk tabel `log_perubahan_harga`
--

INSERT INTO `log_perubahan_harga` (`id`, `master_harga_id`, `jenis_sampah`, `harga_lama`, `harga_baru`, `perubahan_harga`, `persentase_perubahan`, `alasan_perubahan`, `status_perubahan`, `tanggal_berlaku`, `created_by`, `approved_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Plastik', 4000.00, 5000.00, 1000.00, 25.00, 'Test perubahan harga', 'approved', '2026-01-12', 1, 1, '2026-01-12 08:49:52', '2026-01-12 08:49:52'),
(2, 3, 'Logam', 5000.00, 7000.00, 2000.00, 40.00, 'Update harga dari Rp 5.000 ke Rp 7.000', 'approved', '2026-01-12', 1, 1, '2026-01-12 01:50:36', '2026-01-12 01:50:36'),
(3, 6, 'Kertas HVS', 0.00, 1000.00, 1000.00, 0.00, 'Jenis sampah baru ditambahkan: Kertas bekas', 'approved', '2026-01-13', 1, 1, '2026-01-13 00:03:24', '2026-01-13 00:03:24'),
(4, 7, 'Elektronik Kabel', 0.00, 4000.00, 4000.00, 0.00, 'Jenis sampah baru ditambahkan: Kabel', 'approved', '2026-01-13', 1, 1, '2026-01-13 00:22:07', '2026-01-13 00:22:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_harga_sampah`
--

CREATE TABLE `master_harga_sampah` (
  `id` int UNSIGNED NOT NULL,
  `jenis_sampah` enum('Plastik','Kertas','Logam','Organik','Residu') NOT NULL,
  `nama_jenis` varchar(100) NOT NULL,
  `harga_per_satuan` decimal(15,2) NOT NULL DEFAULT '0.00',
  `satuan` varchar(20) NOT NULL DEFAULT 'kg',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dapat_dijual` tinyint(1) NOT NULL DEFAULT '0',
  `deskripsi` text,
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `master_harga_sampah`
--

INSERT INTO `master_harga_sampah` (`id`, `jenis_sampah`, `nama_jenis`, `harga_per_satuan`, `satuan`, `status_aktif`, `dapat_dijual`, `deskripsi`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'Plastik', 'Sampah Plastik', 5000.00, 'pcs', 1, 1, 'Botol plastik, kemasan plastik, dll', NULL, NULL, '2026-01-07 05:11:23', '2026-01-12 01:45:07'),
(2, 'Kertas', 'Sampah Kertas', 3000.00, 'kg', 1, 1, 'Kertas bekas, kardus, koran, dll', NULL, NULL, '2026-01-07 05:11:23', '2026-01-12 00:41:21'),
(3, 'Logam', 'Sampah Logam', 7000.00, 'kg', 1, 1, 'Kaleng, besi, aluminium, dll', NULL, NULL, '2026-01-07 05:11:23', '2026-01-12 01:50:36'),
(4, 'Organik', 'Sampah Organik', 0.00, 'kg', 1, 0, 'Sisa makanan, daun, ranting, dll', NULL, NULL, '2026-01-07 05:11:23', '2026-01-07 05:11:23'),
(5, 'Residu', 'Sampah Residu', 0.00, 'kg', 1, 0, 'Sampah yang tidak dapat didaur ulang', NULL, NULL, '2026-01-07 05:11:23', '2026-01-07 05:11:23'),
(7, '', 'Kabel', 4000.00, 'pcs', 1, 1, 'Kabel rusak. dll', NULL, NULL, '2026-01-13 00:22:07', '2026-01-13 00:22:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') NOT NULL DEFAULT 'info',
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `data`, `is_read`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Selamat Datang!', 'Sistem UI GreenMetric POLBAN siap digunakan.', 'success', NULL, 0, NULL, '2026-01-06 14:13:08', '2026-01-06 14:13:08'),
(2, 3, 'Data TPS Baru', 'Data sampah TPS baru telah dikirim untuk review.', 'info', NULL, 0, NULL, '2026-01-06 14:13:08', '2026-01-06 14:13:08'),
(3, 1, 'Review Diperlukan', 'Terdapat 2 data sampah yang memerlukan review.', 'warning', NULL, 0, NULL, '2026-01-06 14:13:08', '2026-01-06 14:13:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian_unit`
--

CREATE TABLE `penilaian_unit` (
  `id` int NOT NULL,
  `unit_id` int NOT NULL,
  `kategori_uigm` varchar(100) NOT NULL,
  `nilai_input` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','submitted','approved','rejected') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `id` int NOT NULL,
  `nama_unit` varchar(255) NOT NULL,
  `kode_unit` varchar(50) NOT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `unit`
--

INSERT INTO `unit` (`id`, `nama_unit`, `kode_unit`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'TPS Kampus Utama', 'TPS001', 1, '2026-01-06 07:13:08', '2026-01-06 07:13:08'),
(2, 'Jurusan Teknik Informatika', 'JTI', 1, '2026-01-06 07:13:08', '2026-01-06 07:13:08'),
(3, 'Jurusan Teknik Sipil', 'JTS', 1, '2026-01-06 07:13:08', '2026-01-06 07:13:08'),
(4, 'Jurusan Teknik Mesin', 'JTM', 1, '2026-01-06 07:13:08', '2026-01-06 07:13:08'),
(5, 'Administrasi Niaga', 'AN', 1, '2026-01-06 07:13:08', '2026-01-06 07:13:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `units`
--

CREATE TABLE `units` (
  `id` int NOT NULL,
  `kode_unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gedung` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `units`
--

INSERT INTO `units` (`id`, `kode_unit`, `nama_unit`, `gedung`, `created_at`, `updated_at`) VALUES
(1, 'TPS', 'TPS Kampus Utama', 'TPS', '2026-01-09 07:08:30', '2026-01-09 07:08:30'),
(2, 'JTI', 'Jurusan Teknik Informatika', 'Gedung JTI', '2026-01-09 07:08:30', '2026-01-09 07:08:30'),
(3, 'JTE', 'Jurusan Teknik Elektro', 'Gedung JTE', '2026-01-09 07:08:30', '2026-01-09 07:08:30'),
(4, 'JTM', 'Jurusan Teknik Mesin', 'Gedung JTM', '2026-01-09 07:08:30', '2026-01-09 07:08:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `role` enum('admin_pusat','admin_unit','super_admin','user','pengelola_tps') NOT NULL DEFAULT 'user',
  `unit_id` int DEFAULT NULL,
  `status_aktif` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `unit_id`, `status_aktif`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@polban.ac.id', 'admin12345678', 'Administrator Pusat', 'admin_pusat', 1, 1, '2026-01-13 00:16:30', '2026-01-06 07:13:08', '2026-01-13 00:16:30'),
(2, 'superadmin', 'superadmin@polban.ac.id', 'superadmin123', 'Super Administrator', 'super_admin', 1, 1, NULL, '2026-01-06 07:13:08', '2026-01-12 02:53:58'),
(3, 'pengelolatps', 'tps@polban.ac.id', 'tps12345', 'Pengelola TPS Kampus', 'pengelola_tps', 1, 1, '2026-01-11 19:02:29', '2026-01-06 07:13:08', '2026-01-12 02:53:58'),
(4, 'userjti', 'jti@polban.ac.id', 'user12345', 'User JTI', 'user', 2, 0, '2026-01-08 20:16:24', '2026-01-06 07:13:08', '2026-01-11 20:11:28'),
(6, 'Nabila', 'nabila@polban.ac.id', 'user12345', 'Nabila User', 'user', 2, 1, '2026-01-13 00:12:41', '2026-01-06 07:13:08', '2026-01-13 00:12:41'),
(7, 'habibtino', 'habibtino83@gmail.com', 'habib12345', 'Habib Tino', 'pengelola_tps', 2, 1, '2026-01-11 18:56:53', '2026-01-11 18:56:28', '2026-01-12 02:53:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `waste_approved`
--

CREATE TABLE `waste_approved` (
  `id` int NOT NULL,
  `waste_id` int NOT NULL COMMENT 'ID dari tabel waste_management',
  `unit_id` int DEFAULT NULL,
  `tps_id` int DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL COMMENT 'User yang input data',
  `admin_id` int DEFAULT NULL COMMENT 'Admin yang approve',
  `tanggal` date NOT NULL,
  `jenis_sampah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL COMMENT 'Berat dalam kg (hasil konversi)',
  `harga_per_kg` decimal(15,2) DEFAULT '0.00',
  `nilai_ekonomis` decimal(15,2) DEFAULT '0.00',
  `gedung` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori_sampah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `dapat_dijual` tinyint(1) DEFAULT '0',
  `catatan_admin` text COLLATE utf8mb4_general_ci,
  `tanggal_approve` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `waste_management`
--

CREATE TABLE `waste_management` (
  `id` int NOT NULL,
  `unit_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_sampah` enum('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3','Logam','Residu') NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `nilai_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `satuan` varchar(10) NOT NULL DEFAULT 'kg',
  `jumlah` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gedung` varchar(100) DEFAULT NULL,
  `pengirim_gedung` varchar(100) DEFAULT NULL COMMENT 'Gedung pengirim untuk TPS',
  `kategori_sampah` enum('bisa_dijual','tidak_bisa_dijual') DEFAULT 'tidak_bisa_dijual' COMMENT 'Kategori sampah untuk TPS',
  `nilai_rupiah` decimal(15,2) DEFAULT '0.00' COMMENT 'Nilai rupiah untuk sampah yang bisa dijual',
  `status` enum('draft','dikirim','disetujui','perlu_revisi') DEFAULT 'draft',
  `catatan_admin` text COMMENT 'Catatan dari admin untuk revisi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `waste_management`
--

INSERT INTO `waste_management` (`id`, `unit_id`, `tanggal`, `jenis_sampah`, `berat_kg`, `nilai_jual`, `satuan`, `jumlah`, `gedung`, `pengirim_gedung`, `kategori_sampah`, `nilai_rupiah`, `status`, `catatan_admin`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 1, '2026-01-06', 'Plastik', 55.00, 0.00, 'kg', 55.00, 'TPS', 'Gedung A', 'bisa_dijual', 165000.00, 'draft', NULL, '2026-01-06 07:13:08', '2026-01-09 11:36:42', 0),
(2, 1, '2026-01-06', 'Kertas', 0.00, 0.00, 'kg', 15.00, 'TPS', 'Gedung B', 'bisa_dijual', 22500.00, 'perlu_revisi', 'Testing', '2026-01-06 07:13:08', '2026-01-12 23:46:01', 0),
(3, 1, '2026-01-05', 'Organik', 0.00, 0.00, 'kg', 50.00, 'TPS', 'Kantin', 'tidak_bisa_dijual', 0.00, 'disetujui', NULL, '2026-01-06 07:13:08', '2026-01-06 07:13:08', 0),
(4, 2, '2026-01-06', 'Plastik', 0.00, 0.00, 'kg', 10.00, 'Gedung JTI', NULL, 'bisa_dijual', 20000.00, 'draft', NULL, '2026-01-06 07:13:08', '2026-01-06 07:13:08', 0),
(5, 3, '2026-01-06', 'Kertas', 0.00, 0.00, 'kg', 8.50, 'Gedung JTS', NULL, 'bisa_dijual', 12750.00, 'disetujui', NULL, '2026-01-06 07:13:08', '2026-01-12 23:57:17', 0),
(10, 1, '2026-01-09', 'Plastik', 32.00, 0.00, 'kg', 32.00, 'TPS', NULL, 'bisa_dijual', 96000.00, 'draft', NULL, '2026-01-09 11:36:10', '2026-01-09 11:36:55', NULL),
(11, 1, '2026-01-09', 'Plastik', 54.00, 0.00, 'kg', 54.00, 'TPS', NULL, 'bisa_dijual', 162000.00, 'disetujui', NULL, '2026-01-09 11:37:24', '2026-01-12 23:18:55', NULL),
(13, 1, '2026-01-09', 'Logam', 21.00, 0.00, 'kg', 21.00, 'TPS', NULL, 'bisa_dijual', 105000.00, 'draft', NULL, '2026-01-09 11:52:05', '2026-01-09 11:52:17', NULL),
(14, 1, '2026-01-09', 'Plastik', 11.00, 0.00, 'kg', 11.00, 'TPS', NULL, 'bisa_dijual', 33000.00, 'draft', NULL, '2026-01-09 11:52:31', '2026-01-09 11:52:31', NULL),
(15, 2, '2026-01-09', 'Plastik', 33.00, 0.00, 'kg', 33.00, 'User Unit', NULL, 'bisa_dijual', 99000.00, 'disetujui', NULL, '2026-01-09 11:55:41', '2026-01-12 02:07:49', NULL),
(16, 2, '2026-01-09', 'Plastik', 21.00, 0.00, 'kg', 21.00, 'User Unit', NULL, 'bisa_dijual', 63000.00, 'draft', NULL, '2026-01-09 11:55:49', '2026-01-09 11:55:49', NULL),
(17, 2, '2026-01-09', 'Organik', 55.00, 0.00, 'kg', 55.00, 'User Unit', NULL, '', 0.00, 'disetujui', NULL, '2026-01-09 11:56:03', '2026-01-12 02:06:59', NULL),
(18, 2, '2026-01-10', 'Logam', 32.00, 0.00, 'kg', 32.00, 'User Unit', NULL, 'bisa_dijual', 160000.00, 'draft', NULL, '2026-01-10 01:58:56', '2026-01-11 19:00:39', NULL),
(19, 2, '2026-01-12', 'Kertas', 9.00, 0.00, 'kg', 9.00, 'User Unit', NULL, 'bisa_dijual', 18000.00, 'draft', NULL, '2026-01-11 19:01:53', '2026-01-11 19:01:53', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `waste_rejected`
--

CREATE TABLE `waste_rejected` (
  `id` int NOT NULL,
  `waste_id` int NOT NULL COMMENT 'ID dari tabel waste_management',
  `unit_id` int DEFAULT NULL,
  `tps_id` int DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL COMMENT 'User yang input data',
  `admin_id` int DEFAULT NULL COMMENT 'Admin yang reject',
  `tanggal` date NOT NULL,
  `jenis_sampah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL,
  `alasan_reject` text COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_reject` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `waste_tps`
--

CREATE TABLE `waste_tps` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_sampah` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sampah_dari_gedung` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_berat` decimal(10,2) NOT NULL,
  `berat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `satuan` enum('kg','liter','ton') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `nilai_rupiah` decimal(12,2) DEFAULT NULL,
  `status` enum('draft','dikirim','disetujui','perlu_revisi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` int DEFAULT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `waste_tps`
--

INSERT INTO `waste_tps` (`id`, `user_id`, `tanggal`, `jenis_sampah`, `sampah_dari_gedung`, `jumlah_berat`, `berat`, `satuan`, `nilai_rupiah`, `status`, `created_by`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-01-08', 'Plastik', 'Gedung A', 15.50, 15.50, 'kg', 46500.00, 'dikirim', NULL, NULL, '2026-01-09 01:47:09', '2026-01-09 01:47:09'),
(2, 1, '2026-01-08', 'Kertas', 'Gedung B', 12.00, 12.00, 'kg', 30000.00, 'disetujui', NULL, NULL, '2026-01-09 01:47:09', '2026-01-09 01:47:09'),
(3, 1, '2026-01-07', 'Organik', 'Gedung C', 8.50, 8.50, 'kg', 0.00, 'draft', NULL, NULL, '2026-01-09 01:47:10', '2026-01-09 01:47:10');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `dashboard_settings`
--
ALTER TABLE `dashboard_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_widget` (`role`,`widget_key`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_enabled` (`is_enabled`);

--
-- Indeks untuk tabel `feature_toggles`
--
ALTER TABLE `feature_toggles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feature_key` (`feature_key`),
  ADD UNIQUE KEY `unique_feature_key` (`feature_key`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_enabled` (`is_enabled`);

--
-- Indeks untuk tabel `feature_toggle_logs`
--
ALTER TABLE `feature_toggle_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_feature_key` (`feature_key`),
  ADD KEY `idx_admin_id` (`admin_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `log_perubahan_harga`
--
ALTER TABLE `log_perubahan_harga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_harga_id` (`master_harga_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `tanggal_berlaku` (`tanggal_berlaku`);

--
-- Indeks untuk tabel `master_harga_sampah`
--
ALTER TABLE `master_harga_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nama_jenis` (`nama_jenis`),
  ADD KEY `idx_jenis_sampah` (`jenis_sampah`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `penilaian_unit`
--
ALTER TABLE `penilaian_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_penilaian_status` (`status`),
  ADD KEY `idx_penilaian_unit_kategori` (`unit_id`,`kategori_uigm`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_unit` (`kode_unit`);

--
-- Indeks untuk tabel `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_unit` (`kode_unit`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `waste_approved`
--
ALTER TABLE `waste_approved`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_waste_id` (`waste_id`),
  ADD KEY `idx_unit_id` (`unit_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_jenis_sampah` (`jenis_sampah`),
  ADD KEY `idx_tanggal_approve` (`tanggal_approve`),
  ADD KEY `idx_laporan` (`tanggal`,`jenis_sampah`,`unit_id`),
  ADD KEY `idx_stats` (`tanggal_approve`,`dapat_dijual`);

--
-- Indeks untuk tabel `waste_management`
--
ALTER TABLE `waste_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_unit_id` (`unit_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_jenis_sampah` (`jenis_sampah`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_pengirim_gedung` (`pengirim_gedung`);

--
-- Indeks untuk tabel `waste_rejected`
--
ALTER TABLE `waste_rejected`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_waste_id` (`waste_id`),
  ADD KEY `idx_unit_id` (`unit_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_jenis_sampah` (`jenis_sampah`),
  ADD KEY `idx_tanggal_reject` (`tanggal_reject`);

--
-- Indeks untuk tabel `waste_tps`
--
ALTER TABLE `waste_tps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_jenis_sampah` (`jenis_sampah`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dashboard_settings`
--
ALTER TABLE `dashboard_settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `feature_toggles`
--
ALTER TABLE `feature_toggles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `feature_toggle_logs`
--
ALTER TABLE `feature_toggle_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `log_perubahan_harga`
--
ALTER TABLE `log_perubahan_harga`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `master_harga_sampah`
--
ALTER TABLE `master_harga_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penilaian_unit`
--
ALTER TABLE `penilaian_unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `units`
--
ALTER TABLE `units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `waste_approved`
--
ALTER TABLE `waste_approved`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `waste_management`
--
ALTER TABLE `waste_management`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `waste_rejected`
--
ALTER TABLE `waste_rejected`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `waste_tps`
--
ALTER TABLE `waste_tps`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

