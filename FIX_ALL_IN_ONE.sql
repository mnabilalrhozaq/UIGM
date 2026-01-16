-- ============================================================
-- FIX ALL IN ONE - JALANKAN SEMUA QUERY INI SEKALIGUS!
-- ============================================================
-- Copy semua query di bawah ini, paste di phpMyAdmin, klik Go
-- ============================================================

USE eksperimen;

-- STEP 1: Hapus data kosong
-- ============================================================
DELETE FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL OR jenis_sampah = '';

-- STEP 2: Hapus UNIQUE constraint (jika ada)
-- ============================================================
-- Jika error "Can't DROP", abaikan saja, lanjut ke step berikutnya
ALTER TABLE master_harga_sampah DROP INDEX IF EXISTS unique_jenis_sampah;

-- STEP 3: Tambah index biasa
-- ============================================================
-- Cek dulu apakah index sudah ada
SELECT COUNT(*) INTO @index_exists
FROM information_schema.statistics 
WHERE table_schema = 'eksperimen' 
  AND table_name = 'master_harga_sampah' 
  AND index_name = 'idx_jenis_sampah';

-- Tambah index jika belum ada
SET @sql = IF(@index_exists = 0, 
    'ALTER TABLE master_harga_sampah ADD INDEX idx_jenis_sampah (jenis_sampah)',
    'SELECT "Index idx_jenis_sampah sudah ada" as message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- STEP 4: Hapus data duplikat (jika ada)
-- ============================================================
-- Cek duplikasi
SELECT 'Data duplikat:' as info, nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;

-- Hapus duplikat (keep yang ID terkecil)
DELETE t1 FROM master_harga_sampah t1
INNER JOIN master_harga_sampah t2 
WHERE t1.id > t2.id AND t1.nama_jenis = t2.nama_jenis;

-- STEP 5: Tambah UNIQUE pada nama_jenis
-- ============================================================
-- Cek dulu apakah constraint sudah ada
SELECT COUNT(*) INTO @unique_exists
FROM information_schema.statistics 
WHERE table_schema = 'eksperimen' 
  AND table_name = 'master_harga_sampah' 
  AND index_name = 'unique_nama_jenis';

-- Tambah UNIQUE jika belum ada
SET @sql = IF(@unique_exists = 0, 
    'ALTER TABLE master_harga_sampah ADD UNIQUE KEY unique_nama_jenis (nama_jenis)',
    'SELECT "UNIQUE constraint unique_nama_jenis sudah ada" as message');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- STEP 6: Verifikasi hasil
-- ============================================================
SELECT '=== DATA SETELAH FIX ===' as info;
SELECT * FROM master_harga_sampah ORDER BY id;

SELECT '=== INDEX SETELAH FIX ===' as info;
SHOW INDEX FROM master_harga_sampah;

-- ============================================================
-- SELESAI!
-- ============================================================
-- Expected result:
-- - Data kosong terhapus
-- - Index unique_jenis_sampah terhapus
-- - Index idx_jenis_sampah ditambahkan
-- - UNIQUE constraint unique_nama_jenis ditambahkan
-- - Data duplikat terhapus
-- ============================================================
