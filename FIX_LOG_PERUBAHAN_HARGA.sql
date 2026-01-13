-- ============================================
-- FIX TABLE LOG PERUBAHAN HARGA
-- Drop dan create ulang dengan struktur yang benar
-- ============================================

-- 1. Drop tabel lama (hati-hati, data akan hilang!)
DROP TABLE IF EXISTS `log_perubahan_harga`;

-- 2. Create tabel baru dengan struktur yang benar
CREATE TABLE `log_perubahan_harga` (
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

-- 3. Verify
DESCRIBE log_perubahan_harga;

-- 4. Test insert
INSERT INTO log_perubahan_harga 
(master_harga_id, jenis_sampah, harga_lama, harga_baru, perubahan_harga, persentase_perubahan, alasan_perubahan, tanggal_berlaku, created_by, approved_by)
VALUES
(1, 'Plastik', 4000, 5000, 1000, 25.00, 'Test perubahan harga', CURDATE(), 1, 1);

-- 5. Check data
SELECT * FROM log_perubahan_harga;

-- ============================================
-- DONE! Sekarang coba edit harga lagi
-- ============================================
