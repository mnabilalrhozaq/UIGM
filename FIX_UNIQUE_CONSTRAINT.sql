-- ============================================================
-- FIX: Hapus UNIQUE Constraint pada jenis_sampah
-- ============================================================
-- Masalah: Tidak bisa tambah beberapa jenis sampah dengan 
--          kategori yang sama (misal: 2 jenis Logam)
-- Solusi: Hapus constraint UNIQUE, ganti dengan index biasa
-- ============================================================

USE eksperimen;

-- 1. Cek constraint yang ada
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'eksperimen' 
  AND TABLE_NAME = 'master_harga_sampah'
  AND CONSTRAINT_NAME = 'unique_jenis_sampah';

-- 2. Drop UNIQUE constraint
ALTER TABLE master_harga_sampah 
DROP INDEX unique_jenis_sampah;

-- 3. Tambah index biasa (untuk performance)
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);

-- 4. Tambah UNIQUE constraint pada nama_jenis (untuk cegah duplikasi nama)
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);

-- 5. Verifikasi perubahan
SHOW INDEX FROM master_harga_sampah;

-- ============================================================
-- SELESAI!
-- ============================================================
-- Sekarang Anda bisa menambah beberapa jenis sampah dengan
-- kategori yang sama, contoh:
-- - Logam: Kabel Tembaga
-- - Logam: Aluminium Bekas
-- - Logam: Besi Tua
-- - Plastik: Botol PET
-- - Plastik: Kantong Plastik
-- dll.
-- ============================================================
