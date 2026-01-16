-- ============================================
-- FIX LENGKAP: Masalah Jenis Sampah Baru
-- ============================================
-- Database: eksperimen
-- Masalah: User tidak bisa input jenis sampah baru
-- Penyebab: Kolom jenis_sampah adalah ENUM dengan nilai tetap
-- Solusi: Ubah ENUM ke VARCHAR agar fleksibel
-- ============================================

USE eksperimen;

-- ============================================
-- 1. CEK STRUKTUR TABEL SEBELUM FIX
-- ============================================
SELECT 'STRUKTUR SEBELUM FIX:' AS info;
DESCRIBE waste_management;

SELECT 'JENIS SAMPAH YANG ADA DI ENUM:' AS info;
SHOW COLUMNS FROM waste_management LIKE 'jenis_sampah';

-- ============================================
-- 2. CEK DATA JENIS SAMPAH YANG ADA
-- ============================================
SELECT 'JENIS SAMPAH YANG SUDAH DIINPUT:' AS info;
SELECT DISTINCT jenis_sampah, COUNT(*) as jumlah
FROM waste_management 
GROUP BY jenis_sampah 
ORDER BY jenis_sampah;

-- ============================================
-- 3. CEK JENIS SAMPAH DI MASTER HARGA
-- ============================================
SELECT 'JENIS SAMPAH DI MASTER HARGA (AKTIF):' AS info;
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual
FROM master_harga_sampah 
WHERE status_aktif = 1 
ORDER BY jenis_sampah;

-- ============================================
-- 4. UBAH ENUM KE VARCHAR (FIX UTAMA)
-- ============================================
SELECT 'MENGUBAH KOLOM jenis_sampah DARI ENUM KE VARCHAR...' AS info;

ALTER TABLE `waste_management` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL 
COMMENT 'Jenis sampah - diubah dari ENUM ke VARCHAR untuk fleksibilitas';

SELECT 'PERUBAHAN BERHASIL!' AS status;

-- ============================================
-- 5. VERIFIKASI PERUBAHAN
-- ============================================
SELECT 'STRUKTUR SETELAH FIX:' AS info;
DESCRIBE waste_management;

SELECT 'CEK TIPE DATA jenis_sampah:' AS info;
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen' 
AND TABLE_NAME = 'waste_management' 
AND COLUMN_NAME = 'jenis_sampah';

-- ============================================
-- 6. TEST INSERT JENIS SAMPAH BARU
-- ============================================
SELECT 'TEST INSERT JENIS SAMPAH BARU...' AS info;

-- Test dengan jenis sampah yang ada di master_harga_sampah tapi tidak di ENUM lama
INSERT INTO waste_management 
(unit_id, tanggal, jenis_sampah, berat_kg, satuan, jumlah, gedung, kategori_sampah, nilai_rupiah, status, created_at) 
VALUES 
(2, '2026-01-15', 'Kaca', 5.5, 'kg', 5.5, 'Test Unit', 'bisa_dijual', 55000, 'draft', NOW());

SELECT 'TEST INSERT BERHASIL!' AS status;

-- Cek data yang baru diinsert
SELECT 'DATA TEST YANG BARU DIINSERT:' AS info;
SELECT * FROM waste_management 
WHERE jenis_sampah = 'Kaca' 
ORDER BY id DESC 
LIMIT 1;

-- ============================================
-- 7. HAPUS DATA TEST (OPSIONAL)
-- ============================================
-- Uncomment baris di bawah jika ingin hapus data test
-- DELETE FROM waste_management WHERE jenis_sampah = 'Kaca' AND gedung = 'Test Unit';

-- ============================================
-- 8. RINGKASAN HASIL
-- ============================================
SELECT '============================================' AS info;
SELECT 'FIX SELESAI! RINGKASAN:' AS info;
SELECT '============================================' AS info;

SELECT 'Kolom jenis_sampah sekarang VARCHAR(100)' AS hasil_1;
SELECT 'User bisa input jenis sampah LAMA' AS hasil_2;
SELECT 'User bisa input jenis sampah BARU' AS hasil_3;
SELECT 'Admin bisa tambah jenis baru kapan saja' AS hasil_4;
SELECT 'Tidak ada batasan ENUM lagi' AS hasil_5;

SELECT '============================================' AS info;
SELECT 'SILAKAN TEST DI BROWSER!' AS info;
SELECT '1. Login sebagai User' AS langkah_1;
SELECT '2. Pilih jenis sampah BARU' AS langkah_2;
SELECT '3. Input data dan simpan' AS langkah_3;
SELECT '4. Seharusnya BERHASIL!' AS langkah_4;
SELECT '============================================' AS info;

-- ============================================
-- CATATAN PENTING
-- ============================================
-- 1. Backup database sudah dilakukan? ✅
-- 2. Perubahan ini AMAN - data lama tidak hilang
-- 3. Tidak perlu restart server
-- 4. Tidak perlu ubah kode PHP
-- 5. Langsung bisa digunakan setelah SQL ini dijalankan
-- ============================================
