-- Fix Database Issues
-- Tanggal: 2026-01-14
-- Deskripsi: Memperbaiki masalah tabel dan kolom yang tidak sesuai

-- 1. Pastikan tabel master_harga_sampah ada (bukan harga_sampah)
-- Jika tabel harga_sampah ada, rename ke master_harga_sampah
-- DROP TABLE IF EXISTS `harga_sampah`;

-- 2. Pastikan kolom unit_id ada di waste_management (bukan tps_id)
-- Cek apakah kolom tps_id ada, jika ada rename atau hapus
-- ALTER TABLE `waste_management` DROP COLUMN IF EXISTS `tps_id`;

-- 3. Pastikan struktur tabel master_harga_sampah benar
SHOW COLUMNS FROM `master_harga_sampah`;

-- 4. Pastikan struktur tabel waste_management benar
SHOW COLUMNS FROM `waste_management`;

-- 5. Jika perlu, tambahkan kolom yang hilang
-- ALTER TABLE `waste_management` ADD COLUMN `unit_id` INT(11) UNSIGNED NULL AFTER `id`;
-- ALTER TABLE `waste_management` ADD COLUMN `kategori_id` INT(11) UNSIGNED NULL AFTER `unit_id`;

-- 6. Pastikan foreign key constraints benar
-- ALTER TABLE `waste_management` 
--   ADD CONSTRAINT `fk_waste_unit` FOREIGN KEY (`unit_id`) REFERENCES `units`(`id`) ON DELETE CASCADE,
--   ADD CONSTRAINT `fk_waste_kategori` FOREIGN KEY (`kategori_id`) REFERENCES `master_harga_sampah`(`id`) ON DELETE SET NULL;

-- 7. Update data jika ada kolom tps_id yang perlu dipindah ke unit_id
-- UPDATE `waste_management` SET `unit_id` = `tps_id` WHERE `unit_id` IS NULL AND `tps_id` IS NOT NULL;

-- Selesai
SELECT 'Database fix script completed' as status;
