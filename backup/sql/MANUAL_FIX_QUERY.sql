-- =====================================================
-- MANUAL FIX - COPY PASTE KE PHPMYADMIN
-- Jalankan query ini satu per satu di phpMyAdmin
-- =====================================================

-- 1. Pilih database
USE `uigm_polban`;

-- 2. Matikan foreign key check
SET FOREIGN_KEY_CHECKS = 0;

-- 3. Tambah kolom tipe_unit
ALTER TABLE `unit` ADD COLUMN `tipe_unit` ENUM('fakultas','jurusan','unit_kerja','lembaga') NOT NULL DEFAULT 'fakultas' AFTER `nama_unit`;

-- 4. Tambah kolom parent_id
ALTER TABLE `unit` ADD COLUMN `parent_id` INT(11) UNSIGNED DEFAULT NULL AFTER `tipe_unit`;

-- 5. Tambah kolom admin_unit_id
ALTER TABLE `unit` ADD COLUMN `admin_unit_id` INT(11) UNSIGNED DEFAULT NULL AFTER `parent_id`;

-- 6. Update data existing
UPDATE `unit` SET `tipe_unit` = 'lembaga' WHERE `kode_unit` = 'POLBAN';

-- 7. Update jurusan
UPDATE `unit` SET `tipe_unit` = 'jurusan', `parent_id` = 1 WHERE `kode_unit` IN ('JTE', 'JTM', 'JTS', 'JTIK');

-- 8. Tambah foreign key parent_id
ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_parent` FOREIGN KEY (`parent_id`) REFERENCES `unit` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 9. Tambah foreign key admin_unit_id
ALTER TABLE `unit` ADD CONSTRAINT `fk_unit_admin` FOREIGN KEY (`admin_unit_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 10. Nyalakan kembali foreign key check
SET FOREIGN_KEY_CHECKS = 1;

-- 11. Cek hasil
SELECT id, kode_unit, nama_unit, tipe_unit, parent_id, admin_unit_id FROM `unit`;