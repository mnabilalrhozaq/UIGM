-- =====================================================
-- BACKUP DATABASE UIGM POLBAN
-- Generated: 2024-12-31
-- Description: Complete database backup for UIGM Dashboard System
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS `uigm_polban` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `uigm_polban`;

-- Disable foreign key checks for import
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- TABLE STRUCTURE
-- =====================================================

-- Table: migrations
CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: users
CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `role` enum('admin_pusat','admin_unit','super_admin') NOT NULL DEFAULT 'admin_unit',
  `unit_id` int(11) UNSIGNED DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_unit` (`unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: unit
CREATE TABLE `unit` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_unit` varchar(20) NOT NULL,
  `nama_unit` varchar(255) NOT NULL,
  `tipe_unit` enum('fakultas','jurusan','unit_kerja','lembaga') NOT NULL DEFAULT 'fakultas',
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `admin_unit_id` int(11) UNSIGNED DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kontak_person` varchar(255) DEFAULT NULL,
  `email_unit` varchar(255) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_unit` (`kode_unit`),
  KEY `fk_unit_parent` (`parent_id`),
  KEY `fk_unit_admin` (`admin_unit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: tahun_penilaian
CREATE TABLE `tahun_penilaian` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun` year(4) NOT NULL,
  `nama_periode` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `tahun` (`tahun`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: indikator
CREATE TABLE `indikator` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode_kategori` varchar(10) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `bobot` decimal(5,2) NOT NULL DEFAULT 0.00,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `warna` varchar(7) DEFAULT '#5c8cbf',
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_kategori` (`kode_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: pengiriman_unit
CREATE TABLE `pengiriman_unit` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) UNSIGNED NOT NULL,
  `tahun_penilaian_id` int(11) UNSIGNED NOT NULL,
  `status_pengiriman` enum('draft','dikirim','review','perlu_revisi','disetujui') NOT NULL DEFAULT 'draft',
  `progress_persen` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tanggal_kirim` datetime DEFAULT NULL,
  `tanggal_review` datetime DEFAULT NULL,
  `catatan_admin` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_tahun_unique` (`unit_id`,`tahun_penilaian_id`),
  KEY `fk_pengiriman_tahun` (`tahun_penilaian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: review_kategori
CREATE TABLE `review_kategori` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengiriman_id` int(11) UNSIGNED NOT NULL,
  `indikator_id` int(11) UNSIGNED NOT NULL,
  `data_input` longtext DEFAULT NULL,
  `skor` decimal(8,2) DEFAULT NULL,
  `catatan_review` text DEFAULT NULL,
  `status_review` enum('pending','approved','rejected','revision') NOT NULL DEFAULT 'pending',
  `reviewed_by` int(11) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengiriman_indikator_unique` (`pengiriman_id`,`indikator_id`),
  KEY `fk_review_indikator` (`indikator_id`),
  KEY `fk_review_reviewer` (`reviewed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: notifikasi
CREATE TABLE `notifikasi` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` enum('info','success','warning','danger') NOT NULL DEFAULT 'info',
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal_dibaca` datetime DEFAULT NULL,
  `link_terkait` varchar(500) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_notifikasi_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: riwayat_versi
CREATE TABLE `riwayat_versi` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pengiriman_id` int(11) UNSIGNED NOT NULL,
  `versi` int(11) NOT NULL DEFAULT 1,
  `data_snapshot` longtext NOT NULL,
  `perubahan` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_riwayat_pengiriman` (`pengiriman_id`),
  KEY `fk_riwayat_user` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table: jenis_sampah
CREATE TABLE `jenis_sampah` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) UNSIGNED DEFAULT NULL,
  `kode` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 1,
  `urutan` int(11) NOT NULL DEFAULT 0,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `fk_jenis_sampah_parent` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- FOREIGN KEY CONSTRAINTS
-- =====================================================

ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `unit`
  ADD CONSTRAINT `fk_unit_parent` FOREIGN KEY (`parent_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_unit_admin` FOREIGN KEY (`admin_unit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pengiriman_unit`
  ADD CONSTRAINT `fk_pengiriman_unit` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengiriman_tahun` FOREIGN KEY (`tahun_penilaian_id`) REFERENCES `tahun_penilaian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `review_kategori`
  ADD CONSTRAINT `fk_review_pengiriman` FOREIGN KEY (`pengiriman_id`) REFERENCES `pengiriman_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_indikator` FOREIGN KEY (`indikator_id`) REFERENCES `indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_review_reviewer` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `notifikasi`
  ADD CONSTRAINT `fk_notifikasi_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `riwayat_versi`
  ADD CONSTRAINT `fk_riwayat_pengiriman` FOREIGN KEY (`pengiriman_id`) REFERENCES `pengiriman_unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_riwayat_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `jenis_sampah`
  ADD CONSTRAINT `fk_jenis_sampah_parent` FOREIGN KEY (`parent_id`) REFERENCES `jenis_sampah` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- =====================================================
-- SAMPLE DATA
-- =====================================================

-- Insert migration records
INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2024-01-01-000001', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1704067200, 1),
(2, '2024-01-01-000002', 'App\\Database\\Migrations\\CreateUnitTable', 'default', 'App', 1704067200, 1),
(3, '2024-01-01-000003', 'App\\Database\\Migrations\\CreateTahunPenilaianTable', 'default', 'App', 1704067200, 1),
(4, '2024-01-01-000004', 'App\\Database\\Migrations\\CreateIndikatorTable', 'default', 'App', 1704067200, 1),
(5, '2024-01-01-000005', 'App\\Database\\Migrations\\CreatePengirimanUnitTable', 'default', 'App', 1704067200, 1),
(6, '2024-01-01-000006', 'App\\Database\\Migrations\\CreateReviewKategoriTable', 'default', 'App', 1704067200, 1),
(7, '2024-01-01-000007', 'App\\Database\\Migrations\\CreateNotifikasiTable', 'default', 'App', 1704067200, 1),
(8, '2024-01-01-000008', 'App\\Database\\Migrations\\CreateRiwayatVersiTable', 'default', 'App', 1704067200, 1),
(9, '2024-01-01-000009', 'App\\Database\\Migrations\\CreateJenisSampahTable', 'default', 'App', 1704067200, 1);

-- Insert tahun penilaian
INSERT INTO `tahun_penilaian` (`id`, `tahun`, `nama_periode`, `tanggal_mulai`, `tanggal_selesai`, `status_aktif`, `deskripsi`) VALUES
(1, 2024, 'Penilaian UIGM 2024', '2024-01-01', '2024-12-31', 1, 'Periode penilaian UI GreenMetric untuk tahun 2024');

-- Insert unit data
INSERT INTO `unit` (`id`, `kode_unit`, `nama_unit`, `tipe_unit`, `parent_id`, `admin_unit_id`, `deskripsi`, `alamat`, `kontak_person`, `email_unit`, `telepon`, `status_aktif`) VALUES
(1, 'POLBAN', 'Politeknik Negeri Bandung', 'lembaga', NULL, NULL, 'Politeknik Negeri Bandung - Kampus Utama', 'Jl. Gegerkalong Hilir, Ds. Ciwaruga, Bandung 40012', 'Admin POLBAN', 'admin@polban.ac.id', '022-2013789', 1),
(2, 'JTE', 'Jurusan Teknik Elektro', 'jurusan', 1, NULL, 'Jurusan Teknik Elektro POLBAN', 'Gedung A POLBAN', 'Ketua JTE', 'jte@polban.ac.id', '022-2013789', 1),
(3, 'JTM', 'Jurusan Teknik Mesin', 'jurusan', 1, NULL, 'Jurusan Teknik Mesin POLBAN', 'Gedung B POLBAN', 'Ketua JTM', 'jtm@polban.ac.id', '022-2013789', 1),
(4, 'JTS', 'Jurusan Teknik Sipil', 'jurusan', 1, NULL, 'Jurusan Teknik Sipil POLBAN', 'Gedung C POLBAN', 'Ketua JTS', 'jts@polban.ac.id', '022-2013789', 1),
(5, 'JTIK', 'Jurusan Teknik Informatika dan Komputer', 'jurusan', 1, NULL, 'Jurusan Teknik Informatika dan Komputer POLBAN', 'Gedung D POLBAN', 'Ketua JTIK', 'jtik@polban.ac.id', '022-2013789', 1);

-- Insert indikator (categories)
INSERT INTO `indikator` (`id`, `kode_kategori`, `nama_kategori`, `deskripsi`, `bobot`, `urutan`, `warna`, `status_aktif`) VALUES
(1, 'SI', 'Setting and Infrastructure', 'Pengaturan dan Infrastruktur kampus yang mendukung keberlanjutan', 15.00, 1, '#2E8B57', 1),
(2, 'EC', 'Energy and Climate Change', 'Penggunaan energi dan upaya mitigasi perubahan iklim', 21.00, 2, '#FF6B35', 1),
(3, 'WS', 'Waste', 'Pengelolaan sampah dan limbah kampus', 18.00, 3, '#4ECDC4', 1),
(4, 'WR', 'Water', 'Pengelolaan dan konservasi air', 10.00, 4, '#45B7D1', 1),
(5, 'TR', 'Transportation', 'Sistem transportasi berkelanjutan', 18.00, 5, '#96CEB4', 1),
(6, 'ED', 'Education and Research', 'Pendidikan dan penelitian berkelanjutan', 18.00, 6, '#FFEAA7', 1);

-- Insert users
INSERT INTO `users` (`id`, `username`, `email`, `password`, `nama_lengkap`, `role`, `unit_id`, `status_aktif`, `last_login`) VALUES
(1, 'superadmin', 'superadmin@polban.ac.id', 'superadmin123', 'Super Administrator', 'super_admin', NULL, 1, NULL),
(2, 'adminpusat', 'adminpusat@polban.ac.id', 'adminpusat123', 'Admin Pusat POLBAN', 'admin_pusat', 1, 1, NULL),
(3, 'adminjte', 'adminjte@polban.ac.id', 'adminjte123', 'Admin Jurusan Teknik Elektro', 'admin_unit', 2, 1, NULL),
(4, 'adminjtm', 'adminjtm@polban.ac.id', 'adminjtm123', 'Admin Jurusan Teknik Mesin', 'admin_unit', 3, 1, NULL),
(5, 'adminjts', 'adminjts@polban.ac.id', 'adminjts123', 'Admin Jurusan Teknik Sipil', 'admin_unit', 4, 1, NULL),
(6, 'adminjtik', 'adminjtik@polban.ac.id', 'adminjtik123', 'Admin Jurusan Teknik Informatika', 'admin_unit', 5, 1, NULL);

-- Insert jenis sampah hierarchy
INSERT INTO `jenis_sampah` (`id`, `parent_id`, `kode`, `nama`, `level`, `urutan`, `status_aktif`) VALUES
(1, NULL, 'organik', 'Sampah Organik', 1, 1, 1),
(2, 1, 'kantin', 'Sampah dari Kantin', 2, 1, 1),
(3, 1, 'lingkungan_kampus', 'Sampah dari Lingkungan Kampus', 2, 2, 1),
(4, 2, 'sisa_makanan', 'Sisa Makanan atau Sayuran', 3, 1, 1),
(5, 2, 'sisa_buah', 'Sisa Buah-buahan', 3, 2, 1),
(6, 2, 'produk_sisa_dapur', 'Produk Sisa Dapur (Ampas Kopi/Teh, Kulit Telur)', 3, 3, 1),
(7, 3, 'daun_kering', 'Daun-daun Kering yang Gugur', 3, 1, 1),
(8, 3, 'rumput_dipotong', 'Rumput yang Dipotong', 3, 2, 1),
(9, 3, 'ranting_pohon', 'Ranting-ranting Pohon Kecil', 3, 3, 1);

-- Insert sample pengiriman_unit
INSERT INTO `pengiriman_unit` (`id`, `unit_id`, `tahun_penilaian_id`, `status_pengiriman`, `progress_persen`, `tanggal_kirim`, `tanggal_review`, `catatan_admin`) VALUES
(1, 1, 1, 'draft', 0.00, NULL, NULL, NULL),
(2, 2, 1, 'draft', 0.00, NULL, NULL, NULL),
(3, 3, 1, 'draft', 0.00, NULL, NULL, NULL),
(4, 4, 1, 'draft', 0.00, NULL, NULL, NULL),
(5, 5, 1, 'draft', 0.00, NULL, NULL, NULL);

-- Insert sample review_kategori (empty data for each unit and category)
INSERT INTO `review_kategori` (`pengiriman_id`, `indikator_id`, `data_input`, `skor`, `catatan_review`, `status_review`) VALUES
-- Unit POLBAN (id=1)
(1, 1, NULL, NULL, NULL, 'pending'),
(1, 2, NULL, NULL, NULL, 'pending'),
(1, 3, NULL, NULL, NULL, 'pending'),
(1, 4, NULL, NULL, NULL, 'pending'),
(1, 5, NULL, NULL, NULL, 'pending'),
(1, 6, NULL, NULL, NULL, 'pending'),
-- Unit JTE (id=2)
(2, 1, NULL, NULL, NULL, 'pending'),
(2, 2, NULL, NULL, NULL, 'pending'),
(2, 3, NULL, NULL, NULL, 'pending'),
(2, 4, NULL, NULL, NULL, 'pending'),
(2, 5, NULL, NULL, NULL, 'pending'),
(2, 6, NULL, NULL, NULL, 'pending'),
-- Unit JTM (id=3)
(3, 1, NULL, NULL, NULL, 'pending'),
(3, 2, NULL, NULL, NULL, 'pending'),
(3, 3, NULL, NULL, NULL, 'pending'),
(3, 4, NULL, NULL, NULL, 'pending'),
(3, 5, NULL, NULL, NULL, 'pending'),
(3, 6, NULL, NULL, NULL, 'pending'),
-- Unit JTS (id=4)
(4, 1, NULL, NULL, NULL, 'pending'),
(4, 2, NULL, NULL, NULL, 'pending'),
(4, 3, NULL, NULL, NULL, 'pending'),
(4, 4, NULL, NULL, NULL, 'pending'),
(4, 5, NULL, NULL, NULL, 'pending'),
(4, 6, NULL, NULL, NULL, 'pending'),
-- Unit JTIK (id=5)
(5, 1, NULL, NULL, NULL, 'pending'),
(5, 2, NULL, NULL, NULL, 'pending'),
(5, 3, NULL, NULL, NULL, 'pending'),
(5, 4, NULL, NULL, NULL, 'pending'),
(5, 5, NULL, NULL, NULL, 'pending'),
(5, 6, NULL, NULL, NULL, 'pending');

-- Insert sample notifications
INSERT INTO `notifikasi` (`user_id`, `judul`, `pesan`, `tipe`, `is_read`, `link_terkait`) VALUES
(2, 'Selamat Datang', 'Selamat datang di Dashboard Admin Pusat UIGM POLBAN', 'info', 0, '/admin-pusat'),
(3, 'Selamat Datang', 'Selamat datang di Dashboard Admin Unit JTE', 'info', 0, '/admin-unit'),
(4, 'Selamat Datang', 'Selamat datang di Dashboard Admin Unit JTM', 'info', 0, '/admin-unit'),
(5, 'Selamat Datang', 'Selamat datang di Dashboard Admin Unit JTS', 'info', 0, '/admin-unit'),
(6, 'Selamat Datang', 'Selamat datang di Dashboard Admin Unit JTIK', 'info', 0, '/admin-unit');

-- Enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- AUTO INCREMENT RESET
-- =====================================================

ALTER TABLE `migrations` AUTO_INCREMENT = 10;
ALTER TABLE `users` AUTO_INCREMENT = 7;
ALTER TABLE `unit` AUTO_INCREMENT = 6;
ALTER TABLE `tahun_penilaian` AUTO_INCREMENT = 2;
ALTER TABLE `indikator` AUTO_INCREMENT = 7;
ALTER TABLE `pengiriman_unit` AUTO_INCREMENT = 6;
ALTER TABLE `review_kategori` AUTO_INCREMENT = 31;
ALTER TABLE `notifikasi` AUTO_INCREMENT = 6;
ALTER TABLE `riwayat_versi` AUTO_INCREMENT = 1;
ALTER TABLE `jenis_sampah` AUTO_INCREMENT = 10;

-- =====================================================
-- BACKUP COMPLETED
-- =====================================================

-- Login credentials for testing:
-- Super Admin: superadmin / superadmin123
-- Admin Pusat: adminpusat / adminpusat123  
-- Admin JTE: adminjte / adminjte123
-- Admin JTM: adminjtm / adminjtm123
-- Admin JTS: adminjts / adminjts123
-- Admin JTIK: adminjtik / adminjtik123

SELECT 'Database backup completed successfully!' as status;