-- ============================================================
-- FIX DATABASE YANG SUDAH ADA (Jalankan di phpMyAdmin)
-- ============================================================
-- Jalankan query ini di database eksperimen yang sudah ada
-- untuk memperbaiki constraint UNIQUE
-- ============================================================

USE eksperimen;

-- STEP 1: Hapus UNIQUE constraint pada jenis_sampah
-- ============================================================
ALTER TABLE master_harga_sampah 
DROP INDEX unique_jenis_sampah;

-- STEP 2: Tambah index biasa untuk performance
-- ============================================================
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);

-- STEP 3: Tambah UNIQUE constraint pada nama_jenis
-- ============================================================
-- Cek dulu apakah ada duplikasi nama_jenis
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;

-- Jika ada duplikasi, hapus dulu data duplikat:
-- DELETE FROM master_harga_sampah WHERE id IN (7); -- sesuaikan ID

-- Setelah tidak ada duplikasi, tambah UNIQUE constraint:
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);

-- STEP 4: Verifikasi perubahan
-- ============================================================
SHOW INDEX FROM master_harga_sampah;

-- Expected result:
-- - PRIMARY (id)
-- - unique_nama_jenis (nama_jenis) ← UNIQUE
-- - idx_jenis_sampah (jenis_sampah) ← INDEX biasa

-- ============================================================
-- SELESAI!
-- ============================================================
-- Sekarang Anda bisa menambah beberapa jenis sampah dengan
-- kategori yang sama!
-- 
-- Contoh yang BISA:
-- - Logam: Kabel Tembaga Bekas
-- - Logam: Aluminium Kaleng
-- - Logam: Besi Tua
-- - Plastik: Botol PET Bersih
-- - Plastik: Kantong Plastik HDPE
-- 
-- Yang TIDAK BISA (duplikasi nama):
-- - Logam: Kabel Tembaga Bekas
-- - Plastik: Kabel Tembaga Bekas ← ERROR (nama sama)
-- ============================================================
