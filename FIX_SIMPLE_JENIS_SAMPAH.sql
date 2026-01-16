-- ============================================
-- FIX SIMPLE: Ubah ENUM ke VARCHAR
-- ============================================
-- Database: eksperimen
-- Untuk Laragon dengan user root
-- ============================================

USE eksperimen;

-- Cek struktur sebelum fix
SELECT 'SEBELUM FIX:' AS info;
SHOW COLUMNS FROM waste_management LIKE 'jenis_sampah';

-- Ubah ENUM ke VARCHAR
ALTER TABLE `waste_management` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL;

-- Cek struktur setelah fix
SELECT 'SETELAH FIX:' AS info;
SHOW COLUMNS FROM waste_management LIKE 'jenis_sampah';

-- Cek jenis sampah yang sudah ada
SELECT 'JENIS SAMPAH YANG ADA:' AS info;
SELECT DISTINCT jenis_sampah FROM waste_management ORDER BY jenis_sampah;

SELECT 'FIX SELESAI! Silakan test di browser.' AS status;
