-- Script untuk memastikan kolom user_id ada di tabel waste_management

-- 1. Cek apakah kolom user_id sudah ada
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen'
AND TABLE_NAME = 'waste_management'
AND COLUMN_NAME = 'user_id';

-- 2. Jika kolom user_id belum ada, tambahkan kolom
-- Uncomment baris berikut jika kolom belum ada:
/*
ALTER TABLE waste_management
ADD COLUMN user_id INT(11) NULL AFTER id,
ADD INDEX idx_user_id (user_id);
*/

-- 3. Update user_id berdasarkan unit_id (jika data sudah ada tapi user_id NULL)
-- Ambil user pertama dari unit yang sama
/*
UPDATE waste_management wm
LEFT JOIN users u ON u.unit_id = wm.unit_id
SET wm.user_id = u.id
WHERE wm.user_id IS NULL
AND u.id IS NOT NULL;
*/

-- 4. Verifikasi hasil
SELECT 
    wm.id,
    wm.user_id,
    wm.unit_id,
    wm.jenis_sampah,
    wm.status,
    u.nama_unit,
    us.nama_lengkap
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
LEFT JOIN users us ON us.id = wm.user_id
LIMIT 10;

-- 5. Cek berapa banyak data yang user_id nya NULL
SELECT 
    COUNT(*) as total_data,
    SUM(CASE WHEN user_id IS NULL THEN 1 ELSE 0 END) as user_id_null,
    SUM(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as user_id_ada
FROM waste_management;
