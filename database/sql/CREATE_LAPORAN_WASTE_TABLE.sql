-- Create Laporan Waste Table
-- Tabel untuk menyimpan data waste yang sudah direview (approved/rejected)
-- Data akan dipindahkan dari waste_management ke sini setelah direview

CREATE TABLE IF NOT EXISTS `laporan_waste` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `waste_id` INT(11) UNSIGNED NULL COMMENT 'ID dari waste_management sebelum dipindah',
  `unit_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID unit yang mengirim',
  `kategori_id` INT(11) UNSIGNED NULL COMMENT 'ID kategori dari master_harga_sampah',
  `jenis_sampah` VARCHAR(100) NOT NULL COMMENT 'Jenis sampah (Plastik, Kertas, dll)',
  `berat_kg` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Berat dalam kilogram',
  `satuan` VARCHAR(20) NOT NULL DEFAULT 'kg' COMMENT 'Satuan (kg, gram, ton, dll)',
  `jumlah` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jumlah sesuai satuan',
  `nilai_rupiah` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Nilai dalam rupiah',
  `tanggal_input` DATE NOT NULL COMMENT 'Tanggal input data',
  `status` ENUM('approved', 'rejected') NOT NULL COMMENT 'Status review',
  `reviewed_by` INT(11) UNSIGNED NULL COMMENT 'ID admin yang mereview',
  `reviewed_at` DATETIME NULL COMMENT 'Waktu review',
  `review_notes` TEXT NULL COMMENT 'Catatan review',
  `created_by` INT(11) UNSIGNED NULL COMMENT 'ID user yang membuat',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_unit_id` (`unit_id`),
  INDEX `idx_kategori_id` (`kategori_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_tanggal_input` (`tanggal_input`),
  INDEX `idx_reviewed_at` (`reviewed_at`),
  INDEX `idx_created_by` (`created_by`),
  INDEX `idx_reviewed_by` (`reviewed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laporan waste yang sudah direview';

-- Add foreign keys (optional, uncomment if you want strict referential integrity)
-- ALTER TABLE `laporan_waste`
--   ADD CONSTRAINT `fk_laporan_unit` FOREIGN KEY (`unit_id`) REFERENCES `units`(`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_laporan_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `master_harga_sampah`(`id`) ON DELETE SET NULL,
--   ADD CONSTRAINT `fk_laporan_created_by` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
--   ADD CONSTRAINT `fk_laporan_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL;

-- Selesai
SELECT 'Tabel laporan_waste berhasil dibuat' as status;
