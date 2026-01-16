-- ============================================================
-- FIX UNTUK LARAGON - JALANKAN SATU PER SATU!
-- ============================================================
-- Jalankan query ini SATU PER SATU di phpMyAdmin
-- Jika ada error, skip dan lanjut ke query berikutnya
-- ============================================================

USE eksperimen;

-- QUERY 1: Hapus data kosong
-- ============================================================
DELETE FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL OR jenis_sampah = '';

-- QUERY 2: Hapus UNIQUE constraint
-- ============================================================
-- Jika error "Can't DROP", skip query ini
ALTER TABLE master_harga_sampah DROP INDEX unique_jenis_sampah;

-- QUERY 3: Tambah index biasa
-- ============================================================
-- Jika error "Duplicate key name", skip query ini
ALTER TABLE master_harga_sampah ADD INDEX idx_jenis_sampah (jenis_sampah);

-- QUERY 4: Cek duplikasi nama
-- ============================================================
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;

-- QUERY 5: Hapus duplikat (jika ada hasil di query 4)
-- ============================================================
-- Hapus data dengan ID lebih besar (keep yang ID terkecil)
DELETE t1 FROM master_harga_sampah t1
INNER JOIN master_harga_sampah t2 
WHERE t1.id > t2.id AND t1.nama_jenis = t2.nama_jenis;

-- QUERY 6: Tambah UNIQUE pada nama_jenis
-- ============================================================
-- Jika error "Duplicate key name", skip query ini
ALTER TABLE master_harga_sampah ADD UNIQUE KEY unique_nama_jenis (nama_jenis);

-- QUERY 7: Verifikasi hasil
-- ============================================================
SELECT * FROM master_harga_sampah ORDER BY id;
SHOW INDEX FROM master_harga_sampah;

-- ============================================================
-- SELESAI!
-- ============================================================
