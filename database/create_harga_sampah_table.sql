-- =====================================================
-- CREATE MASTER HARGA SAMPAH TABLE
-- =====================================================
-- Tabel untuk menyimpan harga sampah yang dapat diatur oleh Admin Pusat

USE eksperimen;

-- Drop table jika sudah ada (untuk development)
DROP TABLE IF EXISTS `master_harga_sampah`;

-- Create table master harga sampah
CREATE TABLE `master_harga_sampah` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_sampah` enum('Plastik','Kertas','Logam','Organik','Residu') NOT NULL COMMENT 'Jenis sampah',
  `nama_jenis` varchar(100) NOT NULL COMMENT 'Nama lengkap jenis sampah',
  `harga_per_satuan` decimal(15,2) NOT NULL DEFAULT '0.00' COMMENT 'Harga per satuan dalam rupiah',
  `satuan` varchar(20) NOT NULL DEFAULT 'kg' COMMENT 'Satuan (kg, pcs, liter, dll)',
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status aktif harga (1=aktif, 0=nonaktif)',
  `dapat_dijual` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Apakah jenis sampah ini dapat dijual (1=ya, 0=tidak)',
  `deskripsi` text NULL COMMENT 'Deskripsi atau catatan tambahan',
  `created_by` int(11) unsigned NULL COMMENT 'ID admin yang membuat',
  `updated_by` int(11) unsigned NULL COMMENT 'ID admin yang terakhir update',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_jenis_sampah` (`jenis_sampah`),
  KEY `idx_status_aktif` (`status_aktif`),
  KEY `idx_dapat_dijual` (`dapat_dijual`),
  KEY `idx_created_by` (`created_by`),
  KEY `idx_updated_by` (`updated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Master harga sampah yang dikelola Admin Pusat';

-- =====================================================
-- INSERT DATA AWAL HARGA SAMPAH
-- =====================================================
INSERT INTO `master_harga_sampah` 
(`jenis_sampah`, `nama_jenis`, `harga_per_satuan`, `satuan`, `status_aktif`, `dapat_dijual`, `deskripsi`) 
VALUES
('Plastik', 'Sampah Plastik', 3000.00, 'kg', 1, 1, 'Botol plastik, kemasan plastik, dll'),
('Kertas', 'Sampah Kertas', 2000.00, 'kg', 1, 1, 'Kertas bekas, kardus, koran, dll'),
('Logam', 'Sampah Logam', 5000.00, 'kg', 1, 1, 'Kaleng, besi, aluminium, dll'),
('Organik', 'Sampah Organik', 0.00, 'kg', 1, 0, 'Sisa makanan, daun, ranting, dll'),
('Residu', 'Sampah Residu', 0.00, 'kg', 1, 0, 'Sampah yang tidak dapat didaur ulang');

-- =====================================================
-- CREATE AUDIT LOG TABLE (OPTIONAL)
-- =====================================================
CREATE TABLE IF NOT EXISTS `log_perubahan_harga` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_sampah` varchar(50) NOT NULL,
  `harga_lama` decimal(15,2) NULL,
  `harga_baru` decimal(15,2) NOT NULL,
  `aksi` enum('create','update','delete','activate','deactivate') NOT NULL,
  `admin_id` int(11) unsigned NOT NULL,
  `admin_nama` varchar(255) NOT NULL,
  `keterangan` text NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_jenis_sampah` (`jenis_sampah`),
  KEY `idx_admin_id` (`admin_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Log perubahan harga sampah';

-- =====================================================
-- VERIFIKASI DATA
-- =====================================================
SELECT 'MASTER HARGA SAMPAH CREATED SUCCESSFULLY!' as status;

SELECT 
    jenis_sampah,
    nama_jenis,
    CONCAT('Rp ', FORMAT(harga_per_satuan, 0)) as harga_formatted,
    satuan,
    CASE WHEN dapat_dijual = 1 THEN 'Bisa Dijual' ELSE 'Tidak Bisa Dijual' END as status_jual,
    CASE WHEN status_aktif = 1 THEN 'Aktif' ELSE 'Nonaktif' END as status
FROM master_harga_sampah 
ORDER BY dapat_dijual DESC, harga_per_satuan DESC;