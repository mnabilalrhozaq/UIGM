-- FIX MINIMAL: Ubah jenis_sampah dari ENUM ke VARCHAR
-- Copy-paste query ini di tab SQL phpMyAdmin

USE eksperimen;

ALTER TABLE `waste_management` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL;

-- Selesai! Cek hasilnya:
DESCRIBE waste_management;
