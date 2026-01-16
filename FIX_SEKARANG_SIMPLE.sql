-- ============================================================
-- JALANKAN SQL INI DI PHPMYADMIN SEKARANG!
-- ============================================================

USE eksperimen;

-- 1. Hapus data yang nama_jenis nya kosong
DELETE FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL;

-- 2. Hapus UNIQUE constraint pada jenis_sampah
ALTER TABLE master_harga_sampah 
DROP INDEX unique_jenis_sampah;

-- 3. Tambah index biasa
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);

-- 4. Cek apakah ada duplikasi nama_jenis
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;

-- Jika ada hasil di query atas, jalankan ini (sesuaikan ID):
-- DELETE FROM master_harga_sampah WHERE id = 7;

-- 5. Tambah UNIQUE pada nama_jenis (jalankan setelah tidak ada duplikasi)
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);

-- 6. Verifikasi
SELECT * FROM master_harga_sampah ORDER BY id;
SHOW INDEX FROM master_harga_sampah;

-- SELESAI! Sekarang coba tambah data lagi.
