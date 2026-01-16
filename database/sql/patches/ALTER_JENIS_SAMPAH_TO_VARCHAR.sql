-- =====================================================
-- ALTER TABLE: Ubah jenis_sampah dari ENUM ke VARCHAR
-- =====================================================
-- Tujuan: Memungkinkan admin menambahkan kategori sampah baru secara bebas
-- Tanggal: 2026-01-14
-- =====================================================

USE `eksperimen`;

-- Backup data terlebih dahulu (opsional, untuk keamanan)
-- CREATE TABLE master_harga_sampah_backup AS SELECT * FROM master_harga_sampah;

-- Ubah field jenis_sampah dari ENUM ke VARCHAR(100)
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL COMMENT 'Kategori sampah (bebas diisi)';

-- Verifikasi perubahan
DESCRIBE `master_harga_sampah`;

-- Tampilkan data yang ada
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual, status_aktif 
FROM `master_harga_sampah` 
ORDER BY jenis_sampah, nama_jenis;

-- =====================================================
-- CATATAN:
-- =====================================================
-- Setelah menjalankan script ini:
-- 1. Field jenis_sampah bisa menerima nilai bebas (tidak terbatas ENUM)
-- 2. Data yang sudah ada tetap aman
-- 3. Admin bisa menambahkan kategori baru seperti: "Elektronik", "Kaca", "Tekstil", dll
-- 4. Tidak perlu mengubah kode aplikasi lagi
-- =====================================================
