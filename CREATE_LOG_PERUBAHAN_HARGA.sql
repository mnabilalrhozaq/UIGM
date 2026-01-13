-- ============================================
-- CREATE TABLE LOG PERUBAHAN HARGA
-- Tabel untuk menyimpan riwayat perubahan harga
-- ============================================

CREATE TABLE IF NOT EXISTS `log_perubahan_harga` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `master_harga_id` int(11) NOT NULL COMMENT 'ID dari tabel harga_sampah',
  `jenis_sampah` varchar(100) NOT NULL,
  `harga_lama` decimal(10,2) DEFAULT NULL,
  `harga_baru` decimal(10,2) NOT NULL,
  `perubahan_harga` decimal(10,2) DEFAULT NULL COMMENT 'Selisih harga',
  `persentase_perubahan` decimal(5,2) DEFAULT NULL COMMENT 'Persentase perubahan',
  `alasan_perubahan` text DEFAULT NULL,
  `status_perubahan` enum('pending','approved','rejected') DEFAULT 'approved',
  `tanggal_berlaku` date NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'User ID yang mengubah',
  `approved_by` int(11) DEFAULT NULL COMMENT 'User ID yang approve',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `master_harga_id` (`master_harga_id`),
  KEY `created_by` (`created_by`),
  KEY `created_at` (`created_at`),
  KEY `tanggal_berlaku` (`tanggal_berlaku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Log perubahan harga sampah';

-- ============================================
-- Verify table created
-- ============================================
SHOW TABLES LIKE 'log_perubahan_harga';

DESCRIBE log_perubahan_harga;

-- ============================================
-- DONE! Tabel log_perubahan_harga sudah dibuat
-- 
-- Sekarang coba:
-- 1. Edit harga sampah
-- 2. Lihat counter "Perubahan Hari Ini" bertambah
-- 3. Klik "Log Perubahan" untuk lihat riwayat
-- ============================================
