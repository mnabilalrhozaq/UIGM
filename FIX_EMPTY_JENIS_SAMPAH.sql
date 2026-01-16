-- ============================================
-- FIX EMPTY JENIS SAMPAH
-- Update data yang jenis_sampah nya kosong
-- ============================================

-- Cek data yang bermasalah
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, status_aktif
FROM master_harga_sampah
WHERE jenis_sampah IS NULL OR jenis_sampah = '' OR TRIM(jenis_sampah) = '';

-- Opsi 1: Hapus data yang jenis_sampah nya kosong
-- DELETE FROM master_harga_sampah 
-- WHERE jenis_sampah IS NULL OR jenis_sampah = '' OR TRIM(jenis_sampah) = '';

-- Opsi 2: Update jenis_sampah dari nama_jenis (jika nama_jenis ada)
UPDATE master_harga_sampah 
SET jenis_sampah = SUBSTRING_INDEX(nama_jenis, ' ', 2)
WHERE (jenis_sampah IS NULL OR jenis_sampah = '' OR TRIM(jenis_sampah) = '')
  AND nama_jenis IS NOT NULL 
  AND nama_jenis != '';

-- Verifikasi hasil
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, status_aktif
FROM master_harga_sampah
ORDER BY id DESC
LIMIT 10;

-- ============================================
-- CATATAN
-- ============================================
-- Pilih salah satu opsi:
-- - Opsi 1: Hapus data yang bermasalah (recommended jika data baru/test)
-- - Opsi 2: Update jenis_sampah dari nama_jenis (jika ingin keep data)
-- 
-- Setelah fix, admin bisa tambah ulang jenis sampah dengan benar
