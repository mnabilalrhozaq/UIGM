-- ============================================
-- CREATE TABLE WASTE APPROVED
-- Tabel untuk menyimpan data waste yang sudah disetujui
-- ============================================

CREATE TABLE IF NOT EXISTS `waste_approved` (
  `id` int NOT NULL AUTO_INCREMENT,
  `waste_id` int NOT NULL COMMENT 'ID dari tabel waste_management',
  `unit_id` int DEFAULT NULL,
  `tps_id` int DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL COMMENT 'User yang input data',
  `admin_id` int DEFAULT NULL COMMENT 'Admin yang approve',
  `tanggal` date NOT NULL,
  `jenis_sampah` varchar(100) NOT NULL,
  `nama_jenis` varchar(255) DEFAULT NULL,
  `satuan` varchar(20) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL COMMENT 'Berat dalam kg (hasil konversi)',
  `harga_per_kg` decimal(15,2) DEFAULT 0.00,
  `nilai_ekonomis` decimal(15,2) DEFAULT 0.00,
  `gedung` varchar(100) DEFAULT NULL,
  `kategori_sampah` varchar(50) DEFAULT NULL,
  `dapat_dijual` tinyint(1) DEFAULT 0,
  `catatan_admin` text DEFAULT NULL,
  `tanggal_approve` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_waste_id` (`waste_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_jenis_sampah` (`jenis_sampah`),
  KEY `idx_tanggal_approve` (`tanggal_approve`),
  KEY `idx_laporan` (`tanggal`, `jenis_sampah`, `unit_id`),
  KEY `idx_stats` (`tanggal_approve`, `dapat_dijual`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- CREATE TABLE WASTE REJECTED
-- Tabel untuk menyimpan data waste yang ditolak
-- ============================================

CREATE TABLE IF NOT EXISTS `waste_rejected` (
  `id` int NOT NULL AUTO_INCREMENT,
  `waste_id` int NOT NULL COMMENT 'ID dari tabel waste_management',
  `unit_id` int DEFAULT NULL,
  `tps_id` int DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL COMMENT 'User yang input data',
  `admin_id` int DEFAULT NULL COMMENT 'Admin yang reject',
  `tanggal` date NOT NULL,
  `jenis_sampah` varchar(100) NOT NULL,
  `nama_jenis` varchar(255) DEFAULT NULL,
  `satuan` varchar(20) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `berat_kg` decimal(10,2) NOT NULL,
  `alasan_reject` text NOT NULL,
  `tanggal_reject` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_waste_id` (`waste_id`),
  KEY `idx_unit_id` (`unit_id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_jenis_sampah` (`jenis_sampah`),
  KEY `idx_tanggal_reject` (`tanggal_reject`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================
-- CATATAN
-- ============================================
-- 1. waste_approved: Menyimpan data yang sudah disetujui admin
-- 2. waste_rejected: Menyimpan history data yang ditolak
-- 3. waste_management: Tetap menyimpan data original (draft, dikirim, review)
-- 4. Saat approve: Data dicopy ke waste_approved, status di waste_management = 'disetujui'
-- 5. Saat reject: Data dicopy ke waste_rejected, status di waste_management = 'perlu_revisi'
