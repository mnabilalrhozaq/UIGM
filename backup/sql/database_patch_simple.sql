-- =====================================================
-- DATABASE PATCH SIMPLE - ADD MISSING COLUMNS
-- Generated: 2024-12-31
-- Description: Patch sederhana untuk menambahkan kolom yang hilang
-- =====================================================

USE `uigm_polban`;

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- ADD MISSING COLUMNS TO UNIT TABLE (SIMPLE VERSION)
-- =====================================================

-- Add tipe_unit column
ALTER TABLE `unit` ADD COLUMN `tipe_unit` ENUM('fakultas','jurusan','unit_kerja','lembaga') NOT NULL DEFAULT 'fakultas' AFTER `nama_unit`;

-- Add parent_id column
ALTER TABLE `unit` ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL AFTER `tipe_unit`;

-- Add admin_unit_id column
ALTER TABLE `unit` ADD COLUMN `admin_unit_id` INT(11) UNSIGNED DEFAULT NULL AFTER `parent_id`;

-- =====================================================
-- UPDATE EXISTING DATA WITH TIPE_UNIT
-- =====================================================

-- Update existing units with appropriate tipe_unit
UPDATE `unit` SET `tipe_unit` = 'lembaga' WHERE `kode_unit` = 'POLBAN';
UPDATE `unit` SET `tipe_unit` = 'jurusan', `parent_id` = 1 WHERE `kode_unit` IN ('JTE', 'JTM', 'JTS', 'JTIK');

-- =====================================================
-- ADD FOREIGN KEY CONSTRAINTS
-- =====================================================

-- Add foreign key for parent_id
ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_parent` FOREIGN KEY (`parent_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Add foreign key for admin_unit_id
ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_admin` FOREIGN KEY (`admin_unit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- VERIFY CHANGES
-- =====================================================

-- Show updated table structure
DESCRIBE `unit`;

-- Show updated data
SELECT id, kode_unit, nama_unit, tipe_unit, parent_id, admin_unit_id, status_aktif 
FROM `unit` 
ORDER BY id;

SELECT 'Database patch applied successfully!' as status;