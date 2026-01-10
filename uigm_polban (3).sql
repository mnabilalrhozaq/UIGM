-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 09 Jan 2026 pada 19.59
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
-- Basis data: `uigm_polban`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dashboard_settings`
--

CREATE TABLE `dashboard_settings` (
  `id` int UNSIGNED NOT NULL,
  `role` enum('user','tps') COLLATE utf8mb4_general_ci NOT NULL,
  `widget_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `urutan` int NOT NULL DEFAULT '0',
  `custom_label` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `custom_color` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `widget_config` json DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dashboard_settings`
--

INSERT INTO `dashboard_settings` (`id`, `role`, `widget_key`, `is_active`, `urutan`, `custom_label`, `custom_color`, `widget_config`, `created_at`, `updated_at`) VALUES
(1, 'user', 'stat_cards', 1, 1, 'Statistik Data', '#3498db', '{\"show_draft\": true, \"show_pending\": true, \"show_approved\": true, \"show_revision\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(2, 'user', 'waste_summary', 1, 2, 'Ringkasan Waste Management', '#2ecc71', '{\"show_details\": true, \"show_value_calculation\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(3, 'user', 'recent_activity', 1, 3, 'Aktivitas Terbaru', '#f39c12', '{\"max_items\": 5}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(4, 'user', 'quick_actions', 1, 4, 'Aksi Cepat', '#9b59b6', '{\"show_export\": true, \"show_reports\": true, \"show_input_form\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(5, 'user', 'price_info', 1, 5, 'Informasi Harga', '#1abc9c', '{\"show_price_trends\": false, \"show_current_prices\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(6, 'tps', 'stat_cards', 1, 1, 'Statistik TPS', '#3498db', '{\"show_draft\": true, \"show_pending\": true, \"show_approved\": true, \"show_revision\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(7, 'tps', 'waste_summary', 1, 2, 'Ringkasan TPS', '#2ecc71', '{\"show_details\": true, \"show_value_calculation\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(8, 'tps', 'recent_activity', 1, 3, 'Aktivitas TPS', '#f39c12', '{\"max_items\": 5}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(9, 'tps', 'quick_actions', 1, 4, 'Aksi Cepat TPS', '#9b59b6', '{\"show_export\": true, \"show_reports\": true, \"show_input_form\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(10, 'tps', 'tps_operations', 1, 5, 'Operasional TPS', '#e74c3c', '{\"show_capacity\": true, \"show_schedule\": true}', '2026-01-08 06:41:28', '2026-01-08 06:41:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feature_toggles`
--

CREATE TABLE `feature_toggles` (
  `id` int UNSIGNED NOT NULL,
  `feature_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `feature_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `category` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'general',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=enabled, 0=disabled',
  `target_roles` json DEFAULT NULL COMMENT 'JSON array of roles that can use this feature',
  `config` json DEFAULT NULL COMMENT 'JSON configuration for the feature',
  `created_by` int UNSIGNED DEFAULT NULL,
  `updated_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `feature_toggles`
--

INSERT INTO `feature_toggles` (`id`, `feature_key`, `feature_name`, `description`, `category`, `is_enabled`, `target_roles`, `config`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Show/hide statistics cards on dashboard', 'dashboard', 1, '[\"user\", \"pengelola_tps\", \"admin_pusat\"]', '{\"show_draft\": true, \"show_pending\": true, \"show_approved\": true, \"show_revision\": true}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(2, 'dashboard_waste_summary', 'Dashboard Waste Summary', 'Show/hide waste management summary on dashboard', 'dashboard', 1, '[\"user\", \"pengelola_tps\"]', '{\"show_details\": true, \"show_value_calculation\": true}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(3, 'dashboard_recent_activity', 'Dashboard Recent Activity', 'Show/hide recent activity feed on dashboard', 'dashboard', 1, '[\"user\", \"pengelola_tps\", \"admin_pusat\"]', '{\"max_items\": 5}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(4, 'waste_value_calculation', 'Waste Value Calculation', 'Enable/disable automatic waste value calculation', 'waste_management', 1, '[\"user\", \"pengelola_tps\"]', '{\"auto_calculate\": true, \"show_price_breakdown\": true}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(5, 'sidebar_quick_actions', 'Sidebar Quick Actions', 'Show/hide quick action buttons in sidebar', 'ui_components', 1, '[\"user\", \"pengelola_tps\"]', '{\"show_export\": true, \"show_reports\": true, \"show_input_form\": true}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(6, 'help_tooltips', 'Help Tooltips', 'Show/hide help tooltips throughout the application', 'ui_components', 1, '[\"user\", \"pengelola_tps\"]', '{\"show_advanced_help\": true}', NULL, NULL, '2026-01-08 06:41:28', '2026-01-08 06:41:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `feature_toggle_logs`
--

CREATE TABLE `feature_toggle_logs` (
  `id` int UNSIGNED NOT NULL,
  `feature_key` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `old_value` json DEFAULT NULL,
  `new_value` json DEFAULT NULL,
  `admin_id` int UNSIGNED NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `reason` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `indikator`
--

CREATE TABLE `indikator` (
  `id` int UNSIGNED NOT NULL,
  `kode_kategori` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_kategori` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `bobot_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `urutan` int NOT NULL DEFAULT '1',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `indikator`
--

INSERT INTO `indikator` (`id`, `kode_kategori`, `nama_kategori`, `deskripsi`, `bobot_persen`, `urutan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'SI', 'Setting and Infrastructure', 'Pengaturan dan Infrastruktur kampus hijau', 15.00, 1, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(2, 'EC', 'Energy and Climate Change', 'Energi dan Perubahan Iklim', 21.00, 2, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(3, 'WS', 'Waste', 'Pengelolaan Sampah', 18.00, 3, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(4, 'WR', 'Water', 'Pengelolaan Air', 10.00, 4, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(5, 'TR', 'Transportation', 'Transportasi', 18.00, 5, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(6, 'ED', 'Education', 'Pendidikan Lingkungan', 18.00, 6, 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id` int UNSIGNED NOT NULL,
  `nama_jenis` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `satuan_default` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id`, `nama_jenis`, `kategori`, `satuan_default`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'Kertas', 'Recyclable', 'kg', 'Sampah kertas yang dapat didaur ulang', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(2, 'Plastik', 'Recyclable', 'kg', 'Sampah plastik yang dapat didaur ulang', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(3, 'Organik', 'Organic', 'kg', 'Sampah organik yang dapat dikompos', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(4, 'Anorganik', 'Non-Organic', 'kg', 'Sampah anorganik', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(5, 'Limbah Cair', 'Liquid Waste', 'L', 'Limbah cair yang perlu penanganan khusus', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(6, 'B3', 'Hazardous', 'kg', 'Bahan Berbahaya dan Beracun', 1, '2026-01-02 08:00:00', '2026-01-02 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria_uigm`
--

CREATE TABLE `kriteria_uigm` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `unit_id` int UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `unit_prodi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_sampah` enum('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3') COLLATE utf8mb4_general_ci NOT NULL,
  `satuan` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `gedung` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `status_review` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reviewed_by` int UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `catatan_review` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria_uigm`
--

INSERT INTO `kriteria_uigm` (`id`, `user_id`, `unit_id`, `tanggal`, `unit_prodi`, `jenis_sampah`, `satuan`, `jumlah`, `gedung`, `status_review`, `reviewed_by`, `reviewed_at`, `catatan_review`, `created_at`) VALUES
(1, 3, NULL, '2026-01-01', 'Teknik Informatika', 'Kertas', 'kg', 5.50, 'Gedung A', 'approved', 2, '2026-01-02 09:00:00', 'Data sudah sesuai dan lengkap', '2026-01-01 14:30:00'),
(2, 3, NULL, '2026-01-01', 'Teknik Informatika', 'Plastik', 'kg', 2.30, 'Gedung A', 'approved', 2, '2026-01-02 09:00:00', 'Data sudah sesuai dan lengkap', '2026-01-01 14:35:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_perubahan_harga`
--

CREATE TABLE `log_perubahan_harga` (
  `id` int UNSIGNED NOT NULL,
  `master_harga_id` int UNSIGNED NOT NULL,
  `jenis_sampah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `harga_lama` decimal(10,2) DEFAULT NULL,
  `harga_baru` decimal(10,2) NOT NULL,
  `perubahan_harga` decimal(10,2) NOT NULL DEFAULT '0.00',
  `persentase_perubahan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `alasan_perubahan` text COLLATE utf8mb4_general_ci,
  `status_perubahan` enum('pending','approved','rejected') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `tanggal_berlaku` date NOT NULL,
  `created_by` int UNSIGNED NOT NULL,
  `approved_by` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_harga_sampah`
--

CREATE TABLE `master_harga_sampah` (
  `id` int UNSIGNED NOT NULL,
  `jenis_sampah` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jenis` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `harga_per_satuan` decimal(10,2) NOT NULL DEFAULT '0.00',
  `harga_per_kg` decimal(10,2) NOT NULL DEFAULT '0.00',
  `satuan` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'kg',
  `dapat_dijual` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=dapat dijual, 0=tidak dapat dijual',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=aktif, 0=nonaktif',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `tanggal_berlaku` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `master_harga_sampah`
--

INSERT INTO `master_harga_sampah` (`id`, `jenis_sampah`, `nama_jenis`, `harga_per_satuan`, `harga_per_kg`, `satuan`, `dapat_dijual`, `status_aktif`, `deskripsi`, `tanggal_berlaku`, `created_at`, `updated_at`) VALUES
(1, 'Plastik', 'Plastik (Botol, Kemasan)', 2000.00, 2000.00, 'kg', 1, 1, 'Plastik yang dapat didaur ulang', '2026-01-08', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(2, 'Kertas', 'Kertas (HVS, Koran, Kardus)', 1500.00, 1500.00, 'kg', 1, 1, 'Kertas yang dapat didaur ulang', '2026-01-08', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(3, 'Logam', 'Logam (Kaleng, Aluminium)', 5000.00, 5000.00, 'kg', 1, 1, 'Logam yang dapat didaur ulang', '2026-01-08', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(4, 'Organik', 'Sampah Organik', 0.00, 0.00, 'kg', 0, 1, 'Sampah organik untuk kompos', '2026-01-08', '2026-01-08 06:41:28', '2026-01-08 06:41:28'),
(5, 'Residu', 'Sampah Residu', 0.00, 0.00, 'kg', 0, 1, 'Sampah yang tidak dapat didaur ulang', '2026-01-08', '2026-01-08 06:41:28', '2026-01-08 06:41:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-12-30-040226', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1735635342, 1),
(2, '2025-12-30-040248', 'App\\Database\\Migrations\\CreateTahunPenilaianTable', 'default', 'App', 1735635342, 1),
(3, '2025-12-30-040305', 'App\\Database\\Migrations\\CreateIndikatorTable', 'default', 'App', 1735635342, 1),
(4, '2025-12-30-040336', 'App\\Database\\Migrations\\CreateUnitTable', 'default', 'App', 1735635342, 1),
(5, '2025-12-30-040353', 'App\\Database\\Migrations\\CreatePengirimanUnitTable', 'default', 'App', 1735635342, 1),
(6, '2025-12-30-040412', 'App\\Database\\Migrations\\CreateReviewKategoriTable', 'default', 'App', 1735635342, 1),
(7, '2025-12-30-040433', 'App\\Database\\Migrations\\CreateNotifikasiTable', 'default', 'App', 1735635342, 1),
(8, '2025-12-30-040519', 'App\\Database\\Migrations\\CreateRiwayatVersiTable', 'default', 'App', 1735635342, 1),
(9, '2024-12-31-100000', 'App\\Database\\Migrations\\CreateJenisSampahTable', 'default', 'App', 1735717074, 2),
(10, '2026-01-02-100000', 'App\\Database\\Migrations\\CreateKriteriaUigmTable', 'default', 'App', 1735717074, 2),
(11, '2024_01_02_000001', 'App\\Database\\Migrations\\AddUnitIdToKriteriaUigm', 'default', 'App', 1735800348, 3),
(12, '2024-01-07-000001', 'App\\Database\\Migrations\\CreateDashboardSettings', 'default', 'App', 1767854488, 4),
(13, '2024-01-08-000001', 'App\\Database\\Migrations\\CreateMasterHargaSampahTable', 'default', 'App', 1767854488, 4),
(14, '2024-01-08-000002', 'App\\Database\\Migrations\\CreateFeatureTogglesTable', 'default', 'App', 1767854488, 4),
(15, '2024-01-08-000003', 'App\\Database\\Migrations\\CreateLogPerubahanHargaTable', 'default', 'App', 1767854488, 4),
(16, '2026-01-02-110000', 'App\\Database\\Migrations\\AddUserFields', 'default', 'App', 1767854488, 4),
(17, '2026-01-06-120000', 'App\\Database\\Migrations\\AddGedungToUsers', 'default', 'App', 1767854488, 4),
(18, '2026-01-06-130000', 'App\\Database\\Migrations\\AddTpsFieldsToWasteManagement', 'default', 'App', 1767854489, 4),
(19, '2026-01-06-140000', 'App\\Database\\Migrations\\AddPengelolaTpsRole', 'default', 'App', 1767854489, 4),
(20, '2024-01-08-000004', 'App\\Database\\Migrations\\CreateFeatureToggleLogsTable', 'default', 'App', 1767857040, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('info','success','warning','danger') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'info',
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
(1, 1, 'Selamat Datang!', 'Sistem UI GreenMetric POLBAN siap digunakan.', 'success', NULL, 0, NULL, '2026-01-05 14:50:26', '2026-01-05 14:50:26'),
(2, 2, 'Data Waste Baru', 'Unit Fakultas Teknik Informatika dan Komputer telah mengirim data waste Kertas untuk direview', 'info', '{\"waste_id\": 6, \"unit_name\": \"Fakultas Teknik Informatika dan Komputer\", \"action_url\": \"/admin-pusat/waste\", \"jenis_sampah\": \"Kertas\"}', 0, NULL, '2026-01-05 07:50:52', '2026-01-05 07:50:52'),
(3, 7, 'Data Waste Baru', 'Unit Fakultas Teknik Informatika dan Komputer telah mengirim data waste Kertas untuk direview', 'info', '{\"waste_id\": 6, \"unit_name\": \"Fakultas Teknik Informatika dan Komputer\", \"action_url\": \"/admin-pusat/waste\", \"jenis_sampah\": \"Kertas\"}', 0, NULL, '2026-01-05 07:50:52', '2026-01-05 07:50:52'),
(4, 2, 'Data Waste Baru', 'Unit Fakultas Teknik Informatika dan Komputer telah mengirim data waste Limbah Cair untuk direview', 'info', '{\"waste_id\": 7, \"unit_name\": \"Fakultas Teknik Informatika dan Komputer\", \"action_url\": \"/admin-pusat/waste\", \"jenis_sampah\": \"Limbah Cair\"}', 0, NULL, '2026-01-05 09:02:31', '2026-01-05 09:02:31'),
(5, 7, 'Data Waste Baru', 'Unit Fakultas Teknik Informatika dan Komputer telah mengirim data waste Limbah Cair untuk direview', 'info', '{\"waste_id\": 7, \"unit_name\": \"Fakultas Teknik Informatika dan Komputer\", \"action_url\": \"/admin-pusat/waste\", \"jenis_sampah\": \"Limbah Cair\"}', 0, NULL, '2026-01-05 09:02:31', '2026-01-05 09:02:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_notifikasi` enum('info','success','warning','error','approval','rejection') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'info',
  `data_tambahan` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `judul`, `pesan`, `tipe_notifikasi`, `data_tambahan`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 3, 'Data Waste Management Disetujui', 'Data sampah Anda untuk tanggal 01/01/2026 telah disetujui oleh Admin Pusat.', 'approval', '{\"action\": \"approved\", \"kriteria_id\": 1}', 0, '2026-01-02 09:00:00', '2026-01-02 09:00:00'),
(3, 2, 'Data Baru Menunggu Review', 'Ada 3 data waste management baru yang menunggu review Anda.', 'info', '{\"pending_count\": 3}', 0, '2026-01-02 11:00:00', '2026-01-02 11:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengiriman_unit`
--

CREATE TABLE `pengiriman_unit` (
  `id` int UNSIGNED NOT NULL,
  `unit_id` int UNSIGNED NOT NULL,
  `tahun_penilaian_id` int UNSIGNED NOT NULL,
  `status_pengiriman` enum('draft','dikirim','review','perlu_revisi','disetujui') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `progress_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tanggal_kirim` datetime DEFAULT NULL,
  `tanggal_disetujui` datetime DEFAULT NULL,
  `disetujui_oleh` int UNSIGNED DEFAULT NULL,
  `catatan_admin_pusat` text COLLATE utf8mb4_general_ci,
  `catatan_revisi` text COLLATE utf8mb4_general_ci,
  `reviewer_id` int UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian_unit`
--

CREATE TABLE `penilaian_unit` (
  `id` int UNSIGNED NOT NULL,
  `unit_id` int UNSIGNED NOT NULL,
  `kategori_uigm` enum('SI','EC','WS','WR','TR','ED') COLLATE utf8mb4_general_ci NOT NULL,
  `indikator` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nilai_input` decimal(5,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','dikirim','review','disetujui','perlu_revisi') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `catatan_admin` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `review_kategori`
--

CREATE TABLE `review_kategori` (
  `id` int UNSIGNED NOT NULL,
  `pengiriman_id` int UNSIGNED NOT NULL,
  `indikator_id` int UNSIGNED NOT NULL,
  `status_review` enum('pending','disetujui','perlu_revisi') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `skor_review` decimal(5,2) DEFAULT NULL,
  `catatan_review` text COLLATE utf8mb4_general_ci,
  `data_input` json DEFAULT NULL,
  `reviewer_id` int UNSIGNED DEFAULT NULL,
  `tanggal_review` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_versi`
--

CREATE TABLE `riwayat_versi` (
  `id` int UNSIGNED NOT NULL,
  `pengiriman_id` int UNSIGNED NOT NULL,
  `versi` int NOT NULL DEFAULT '1',
  `data_snapshot` json NOT NULL,
  `perubahan_oleh` int UNSIGNED NOT NULL,
  `catatan_perubahan` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tahun_penilaian`
--

CREATE TABLE `tahun_penilaian` (
  `id` int UNSIGNED NOT NULL,
  `tahun` year NOT NULL,
  `nama_periode` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tahun_penilaian`
--

INSERT INTO `tahun_penilaian` (`id`, `tahun`, `nama_periode`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, '2024', 'Periode Penilaian UIGM 2024', '2024-01-01', '2024-12-31', 0, 'Periode penilaian UI Green Metric tahun 2024', '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(2, '2025', 'Periode Penilaian UIGM 2025', '2025-01-01', '2025-12-31', 0, 'Periode penilaian UI Green Metric tahun 2025', '2026-01-02 08:00:00', '2026-01-02 08:00:00'),
(3, '2026', 'Periode Penilaian UIGM 2026', '2026-01-01', '2026-12-31', 1, 'Periode penilaian UI Green Metric tahun 2026 (Aktif)', '2026-01-02 08:00:00', '2026-01-02 08:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `id` int UNSIGNED NOT NULL,
  `kode_unit` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_unit` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tipe_unit` enum('fakultas','jurusan','unit_kerja','lainnya') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'unit_kerja',
  `deskripsi` text COLLATE utf8mb4_general_ci,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `unit`
--

INSERT INTO `unit` (`id`, `kode_unit`, `nama_unit`, `tipe_unit`, `deskripsi`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, 'GDA', 'Gedung A – Gedung Kuliah', 'unit_kerja', 'Gedung kuliah utama untuk berbagai program studi', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(2, 'GDB', 'Gedung B – Administrasi Niaga', 'unit_kerja', 'Gedung untuk program studi Administrasi Niaga', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(3, 'GDC', 'Gedung C – Gedung Kuliah', 'unit_kerja', 'Gedung kuliah untuk kegiatan akademik', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(4, 'GDD', 'Gedung D – Teknik Komputer dan Informatika', 'unit_kerja', 'Gedung untuk Jurusan Teknik Komputer dan Informatika', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(5, 'GDE', 'Gedung E – Akuntansi', 'unit_kerja', 'Gedung untuk program studi Akuntansi', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(6, 'GDF', 'Gedung F – Gedung Kuliah Baru', 'unit_kerja', 'Gedung kuliah baru untuk ekspansi akademik', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(7, 'GDH', 'Gedung H – Pascasarjana', 'unit_kerja', 'Gedung untuk program Pascasarjana', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(8, 'LTM', 'Gedung Laboratorium Teknik Mesin', 'unit_kerja', 'Laboratorium untuk Jurusan Teknik Mesin', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(9, 'LTS', 'Gedung Laboratorium Teknik Sipil', 'unit_kerja', 'Laboratorium untuk Jurusan Teknik Sipil', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(10, 'LTK', 'Gedung Laboratorium Teknik Kimia', 'unit_kerja', 'Laboratorium untuk Jurusan Teknik Kimia', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(11, 'LRTU', 'Gedung Laboratorium Teknik Refrigerasi dan Tata Udara', 'unit_kerja', 'Laboratorium Teknik Refrigerasi dan Tata Udara', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(12, 'HAE', 'Hanggar Aeronautika', 'unit_kerja', 'Hanggar untuk program studi Aeronautika', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11'),
(13, 'DIR', 'Gedung Direktorat', 'unit_kerja', 'Gedung administrasi dan manajemen POLBAN', 1, '2026-01-06 09:15:11', '2026-01-06 09:15:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `unit_prodi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gedung` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Gedung default untuk user ini',
  `role` enum('admin_pusat','admin_unit','super_admin','user','pengelola_tps') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `unit_id` int UNSIGNED DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `full_name`, `unit_prodi`, `gedung`, `role`, `unit_id`, `status_aktif`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'superadmin@polban.ac.id', 'superadmin123', 'Super Administrator', NULL, NULL, NULL, 'super_admin', NULL, 1, '2026-01-05 06:47:56', '2026-01-02 08:00:00', '2026-01-05 06:47:56'),
(2, 'admin', 'adminpusat@polban.ac.id', 'admin12345678', 'Admin Pusat UIGM', NULL, NULL, NULL, 'admin_pusat', NULL, 1, '2026-01-08 02:39:15', '2026-01-02 08:00:00', '2026-01-08 02:39:15'),
(3, 'user1', 'user1@polban.ac.id', 'user12345', 'John Doe', NULL, NULL, NULL, 'user', 4, 1, '2026-01-07 02:37:26', '2026-01-02 08:00:00', '2026-01-07 02:37:26'),
(12, 'admin_test', 'admin.test@polban.ac.id', 'admintest123', 'Admin Test POLBAN', NULL, NULL, NULL, 'admin_pusat', NULL, 1, NULL, '2026-01-06 09:50:57', '2026-01-06 09:50:57'),
(13, 'madsky', 'madski@gmail.com', 'password12345', 'Ahmad Hidayat', NULL, NULL, NULL, 'user', 4, 1, '2026-01-06 04:23:01', '2026-01-06 04:22:44', '2026-01-06 04:23:01'),
(14, 'pengelolatps', 'nabilalrhozaq@polban.ac.id', 'password12345', 'M Nabil Alrhozaq', NULL, NULL, NULL, 'user', 1, 1, NULL, '2026-01-06 05:16:04', '2026-01-06 05:16:04'),
(16, 'tpsbaru', 'info@polban.ac.id', 'tps12345', 'petugas', NULL, NULL, NULL, '', 1, 1, '2026-01-07 02:12:08', '2026-01-06 06:45:35', '2026-01-07 02:12:08');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_admin_dashboard`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_admin_dashboard` (
`metric` varchar(14)
,`value` bigint
,`description` varchar(33)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_waste_stats`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_waste_stats` (
`tanggal` date
,`jenis_sampah` enum('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3')
,`nama_unit` varchar(100)
,`jumlah_data` bigint
,`total_berat` decimal(32,2)
,`status_review` enum('pending','approved','rejected')
,`pending_count` bigint
,`approved_count` bigint
,`rejected_count` bigint
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `waste_management`
--

CREATE TABLE `waste_management` (
  `id` int UNSIGNED NOT NULL,
  `unit_id` int UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_sampah` enum('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3') COLLATE utf8mb4_general_ci NOT NULL,
  `satuan` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `gedung` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `pengirim_gedung` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Gedung pengirim sampah untuk TPS',
  `kategori_sampah` enum('bisa_dijual','tidak_bisa_dijual') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Kategori sampah untuk TPS',
  `nilai_rupiah` decimal(15,2) DEFAULT NULL COMMENT 'Nilai rupiah untuk sampah yang bisa dijual',
  `status` enum('draft','dikirim','review','disetujui','perlu_revisi') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'draft',
  `catatan_admin` text COLLATE utf8mb4_general_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `dashboard_settings`
--
ALTER TABLE `dashboard_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_widget_key` (`role`,`widget_key`),
  ADD KEY `urutan` (`urutan`);

--
-- Indeks untuk tabel `feature_toggles`
--
ALTER TABLE `feature_toggles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `feature_key` (`feature_key`),
  ADD KEY `category_is_enabled` (`category`,`is_enabled`);

--
-- Indeks untuk tabel `feature_toggle_logs`
--
ALTER TABLE `feature_toggle_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feature_key_created_at` (`feature_key`,`created_at`),
  ADD KEY `admin_id_created_at` (`admin_id`,`created_at`);

--
-- Indeks untuk tabel `indikator`
--
ALTER TABLE `indikator`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_kategori` (`kode_kategori`),
  ADD KEY `urutan` (`urutan`),
  ADD KEY `status_aktif` (`status_aktif`);

--
-- Indeks untuk tabel `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_jenis` (`nama_jenis`),
  ADD KEY `kategori` (`kategori`),
  ADD KEY `status_aktif` (`status_aktif`);

--
-- Indeks untuk tabel `kriteria_uigm`
--
ALTER TABLE `kriteria_uigm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `user_id_tanggal` (`user_id`,`tanggal`),
  ADD KEY `jenis_sampah` (`jenis_sampah`),
  ADD KEY `status_review` (`status_review`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `idx_kriteria_tanggal` (`tanggal`),
  ADD KEY `idx_kriteria_status_tanggal` (`status_review`,`tanggal`);

--
-- Indeks untuk tabel `log_perubahan_harga`
--
ALTER TABLE `log_perubahan_harga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_harga_id` (`master_harga_id`),
  ADD KEY `jenis_sampah_tanggal_berlaku` (`jenis_sampah`,`tanggal_berlaku`),
  ADD KEY `status_perubahan_created_at` (`status_perubahan`,`created_at`);

--
-- Indeks untuk tabel `master_harga_sampah`
--
ALTER TABLE `master_harga_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jenis_sampah` (`jenis_sampah`),
  ADD KEY `status_aktif_dapat_dijual` (`status_aktif`,`dapat_dijual`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `is_read` (`is_read`),
  ADD KEY `tipe_notifikasi` (`tipe_notifikasi`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `idx_notifikasi_user_read` (`user_id`,`is_read`);

--
-- Indeks untuk tabel `pengiriman_unit`
--
ALTER TABLE `pengiriman_unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unit_tahun` (`unit_id`,`tahun_penilaian_id`),
  ADD KEY `tahun_penilaian_id` (`tahun_penilaian_id`),
  ADD KEY `status_pengiriman` (`status_pengiriman`),
  ADD KEY `disetujui_oleh` (`disetujui_oleh`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `idx_pengiriman_status_tanggal` (`status_pengiriman`,`tanggal_kirim`);

--
-- Indeks untuk tabel `penilaian_unit`
--
ALTER TABLE `penilaian_unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `kategori_uigm` (`kategori_uigm`),
  ADD KEY `status` (`status`);

--
-- Indeks untuk tabel `review_kategori`
--
ALTER TABLE `review_kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pengiriman_indikator` (`pengiriman_id`,`indikator_id`),
  ADD KEY `indikator_id` (`indikator_id`),
  ADD KEY `status_review` (`status_review`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indeks untuk tabel `riwayat_versi`
--
ALTER TABLE `riwayat_versi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengiriman_id` (`pengiriman_id`),
  ADD KEY `perubahan_oleh` (`perubahan_oleh`),
  ADD KEY `versi` (`versi`);

--
-- Indeks untuk tabel `tahun_penilaian`
--
ALTER TABLE `tahun_penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tahun` (`tahun`),
  ADD KEY `status_aktif` (`status_aktif`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_unit` (`kode_unit`),
  ADD KEY `tipe_unit` (`tipe_unit`),
  ADD KEY `status_aktif` (`status_aktif`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `role` (`role`),
  ADD KEY `idx_users_role_status` (`role`,`status_aktif`);

--
-- Indeks untuk tabel `waste_management`
--
ALTER TABLE `waste_management`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `jenis_sampah` (`jenis_sampah`),
  ADD KEY `status` (`status`),
  ADD KEY `tanggal` (`tanggal`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dashboard_settings`
--
ALTER TABLE `dashboard_settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `feature_toggles`
--
ALTER TABLE `feature_toggles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `feature_toggle_logs`
--
ALTER TABLE `feature_toggle_logs`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `indikator`
--
ALTER TABLE `indikator`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kriteria_uigm`
--
ALTER TABLE `kriteria_uigm`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `log_perubahan_harga`
--
ALTER TABLE `log_perubahan_harga`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `master_harga_sampah`
--
ALTER TABLE `master_harga_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengiriman_unit`
--
ALTER TABLE `pengiriman_unit`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `penilaian_unit`
--
ALTER TABLE `penilaian_unit`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `review_kategori`
--
ALTER TABLE `review_kategori`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `riwayat_versi`
--
ALTER TABLE `riwayat_versi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tahun_penilaian`
--
ALTER TABLE `tahun_penilaian`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `waste_management`
--
ALTER TABLE `waste_management`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_admin_dashboard`
--
DROP TABLE IF EXISTS `v_admin_dashboard`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_admin_dashboard`  AS SELECT 'total_data' AS `metric`, count(0) AS `value`, 'Total semua data waste management' AS `description` FROM `kriteria_uigm`union all select 'pending_review' AS `metric`,count(0) AS `value`,'Data menunggu review' AS `description` from `kriteria_uigm` where (`kriteria_uigm`.`status_review` = 'pending') union all select 'approved_data' AS `metric`,count(0) AS `value`,'Data yang disetujui' AS `description` from `kriteria_uigm` where (`kriteria_uigm`.`status_review` = 'approved') union all select 'rejected_data' AS `metric`,count(0) AS `value`,'Data yang ditolak' AS `description` from `kriteria_uigm` where (`kriteria_uigm`.`status_review` = 'rejected')  ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_waste_stats`
--
DROP TABLE IF EXISTS `v_waste_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_waste_stats`  AS SELECT cast(`k`.`tanggal` as date) AS `tanggal`, `k`.`jenis_sampah` AS `jenis_sampah`, `u`.`nama_unit` AS `nama_unit`, count(0) AS `jumlah_data`, sum(`k`.`jumlah`) AS `total_berat`, `k`.`status_review` AS `status_review`, count((case when (`k`.`status_review` = 'pending') then 1 end)) AS `pending_count`, count((case when (`k`.`status_review` = 'approved') then 1 end)) AS `approved_count`, count((case when (`k`.`status_review` = 'rejected') then 1 end)) AS `rejected_count` FROM (`kriteria_uigm` `k` left join `unit` `u` on((`k`.`unit_id` = `u`.`id`))) GROUP BY cast(`k`.`tanggal` as date), `k`.`jenis_sampah`, `u`.`nama_unit`, `k`.`status_review` ;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kriteria_uigm`
--
ALTER TABLE `kriteria_uigm`
  ADD CONSTRAINT `kriteria_uigm_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kriteria_uigm_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kriteria_uigm_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengiriman_unit`
--
ALTER TABLE `pengiriman_unit`
  ADD CONSTRAINT `pengiriman_unit_disetujui_oleh_foreign` FOREIGN KEY (`disetujui_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pengiriman_unit_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pengiriman_unit_tahun_penilaian_id_foreign` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengiriman_unit_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penilaian_unit`
--
ALTER TABLE `penilaian_unit`
  ADD CONSTRAINT `penilaian_unit_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `review_kategori`
--
ALTER TABLE `review_kategori`
  ADD CONSTRAINT `review_kategori_indikator_id_foreign` FOREIGN KEY (`indikator_id`) REFERENCES `indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_kategori_pengiriman_id_foreign` FOREIGN KEY (`pengiriman_id`) REFERENCES `pengiriman_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_kategori_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_versi`
--
ALTER TABLE `riwayat_versi`
  ADD CONSTRAINT `riwayat_versi_pengiriman_id_foreign` FOREIGN KEY (`pengiriman_id`) REFERENCES `pengiriman_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `riwayat_versi_perubahan_oleh_foreign` FOREIGN KEY (`perubahan_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `waste_management`
--
ALTER TABLE `waste_management`
  ADD CONSTRAINT `waste_management_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
