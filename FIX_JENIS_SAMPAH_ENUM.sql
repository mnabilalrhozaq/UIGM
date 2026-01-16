-- ============================================
-- FIX: Ubah jenis_sampah dari ENUM ke VARCHAR
-- ============================================
-- MASALAH: Kolom jenis_sampah di waste_management adalah ENUM dengan nilai tetap
--          Ketika user input jenis sampah baru (Kaca, Elektronik, dll), database reject
-- SOLUSI: Ubah jenis_sampah menjadi VARCHAR agar bisa menerima jenis sampah apapun

USE eksperimen;

-- Ubah kolom jenis_sampah dari ENUM ke VARCHAR(100)
ALTER TABLE `waste_management` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL;

-- Verifikasi perubahan
DESCRIBE waste_management;

-- Test: Coba lihat data yang ada
SELECT DISTINCT jenis_sampah FROM waste_management ORDER BY jenis_sampah;

SELECT 'FIX SELESAI! Sekarang jenis_sampah bisa menerima nilai apapun dari master_harga_sampah' AS status;
