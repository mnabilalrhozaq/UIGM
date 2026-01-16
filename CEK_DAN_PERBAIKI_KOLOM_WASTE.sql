-- Script untuk mengecek dan memperbaiki kolom di tabel waste_management

-- 1. Cek struktur tabel waste_management
DESCRIBE waste_management;

-- 2. Cek kolom apa saja yang ada
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen' 
AND TABLE_NAME = 'waste_management'
ORDER BY ORDINAL_POSITION;

-- 3. Tambahkan kolom yang mungkin kurang (jika belum ada)

-- Tambah kolom created_by jika belum ada
ALTER TABLE waste_management 
ADD COLUMN IF NOT EXISTS created_by INT(11) UNSIGNED NULL AFTER status;

-- Tambah kolom updated_by jika belum ada
ALTER TABLE waste_management 
ADD COLUMN IF NOT EXISTS updated_by INT(11) UNSIGNED NULL AFTER created_by;

-- Tambah kolom reviewed_by jika belum ada
ALTER TABLE waste_management 
ADD COLUMN IF NOT EXISTS reviewed_by INT(11) UNSIGNED NULL AFTER updated_by;

-- Tambah kolom reviewed_at jika belum ada
ALTER TABLE waste_management 
ADD COLUMN IF NOT EXISTS reviewed_at DATETIME NULL AFTER reviewed_by;

-- Tambah kolom review_notes jika belum ada
ALTER TABLE waste_management 
ADD COLUMN IF NOT EXISTS review_notes TEXT NULL AFTER reviewed_at;

-- 4. Cek lagi struktur setelah penambahan
DESCRIBE waste_management;

-- 5. Tampilkan kolom yang penting
SELECT 
    'Kolom penting di waste_management:' as info,
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen' 
AND TABLE_NAME = 'waste_management'
AND COLUMN_NAME IN ('id', 'unit_id', 'jenis_sampah', 'berat_kg', 'satuan', 'jumlah', 'nilai_rupiah', 'status', 'created_by', 'created_at', 'updated_at')
ORDER BY ORDINAL_POSITION;
