-- =====================================================
-- DATABASE PATCH - ADD MISSING COLUMNS
-- Generated: 2024-12-31
-- Description: Patch untuk menambahkan kolom yang hilang
-- =====================================================

USE `uigm_polban`;

-- Disable foreign key checks
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- ADD MISSING COLUMNS TO UNIT TABLE
-- =====================================================

-- Check if tipe_unit column exists, if not add it
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'uigm_polban' 
AND TABLE_NAME = 'unit' 
AND COLUMN_NAME = 'tipe_unit';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `unit` ADD COLUMN `tipe_unit` ENUM(''fakultas'',''jurusan'',''unit_kerja'',''lembaga'') NOT NULL DEFAULT ''fakultas'' AFTER `nama_unit`',
    'SELECT ''Column tipe_unit already exists'' as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check if parent_id column exists, if not add it
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'uigm_polban' 
AND TABLE_NAME = 'unit' 
AND COLUMN_NAME = 'parent_id';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `unit` ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL AFTER `tipe_unit`',
    'SELECT ''Column parent_id already exists'' as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Check if admin_unit_id column exists, if not add it
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'uigm_polban' 
AND TABLE_NAME = 'unit' 
AND COLUMN_NAME = 'admin_unit_id';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `unit` ADD COLUMN `admin_unit_id` INT(11) UNSIGNED DEFAULT NULL AFTER `parent_id`',
    'SELECT ''Column admin_unit_id already exists'' as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- UPDATE EXISTING DATA WITH TIPE_UNIT
-- =====================================================

-- Update existing units with appropriate tipe_unit
UPDATE `unit` SET `tipe_unit` = 'lembaga' WHERE `kode_unit` = 'POLBAN';
UPDATE `unit` SET `tipe_unit` = 'jurusan', `parent_id` = 1 WHERE `kode_unit` IN ('JTE', 'JTM', 'JTS', 'JTIK');

-- =====================================================
-- ADD FOREIGN KEY CONSTRAINTS IF NOT EXISTS
-- =====================================================

-- Add foreign key for parent_id if not exists
SET @fk_exists = 0;
SELECT COUNT(*) INTO @fk_exists 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'uigm_polban' 
AND TABLE_NAME = 'unit' 
AND CONSTRAINT_NAME = 'fk_unit_parent';

SET @sql = IF(@fk_exists = 0, 
    'ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_parent` FOREIGN KEY (`parent_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_unit_parent already exists'' as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add foreign key for admin_unit_id if not exists
SET @fk_exists = 0;
SELECT COUNT(*) INTO @fk_exists 
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
WHERE TABLE_SCHEMA = 'uigm_polban' 
AND TABLE_NAME = 'unit' 
AND CONSTRAINT_NAME = 'fk_unit_admin';

SET @sql = IF(@fk_exists = 0, 
    'ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_admin` FOREIGN KEY (`admin_unit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT ''Foreign key fk_unit_admin already exists'' as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

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