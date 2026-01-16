-- Script untuk mengisi created_by yang NULL di waste_management

-- 1. Cek berapa banyak data yang created_by nya NULL
SELECT 
    COUNT(*) as total_data,
    SUM(CASE WHEN created_by IS NULL THEN 1 ELSE 0 END) as created_by_null,
    SUM(CASE WHEN created_by IS NOT NULL THEN 1 ELSE 0 END) as created_by_ada
FROM waste_management;

-- 2. Cek data yang created_by nya NULL
SELECT 
    wm.id,
    wm.unit_id,
    wm.created_by,
    wm.jenis_sampah,
    wm.status,
    wm.tanggal,
    u.nama_unit
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
WHERE wm.created_by IS NULL
LIMIT 20;

-- 3. Update created_by dengan user pertama dari unit yang sama
-- Ambil user yang role nya 'user' atau 'pengelola_tps' dari unit tersebut
UPDATE waste_management wm
INNER JOIN (
    SELECT 
        u.unit_id,
        MIN(u.id) as first_user_id
    FROM users u
    WHERE u.role IN ('user', 'pengelola_tps')
    AND u.unit_id IS NOT NULL
    GROUP BY u.unit_id
) user_map ON user_map.unit_id = wm.unit_id
SET wm.created_by = user_map.first_user_id
WHERE wm.created_by IS NULL;

-- 4. Verifikasi hasil
SELECT 
    COUNT(*) as total_data,
    SUM(CASE WHEN created_by IS NULL THEN 1 ELSE 0 END) as created_by_null,
    SUM(CASE WHEN created_by IS NOT NULL THEN 1 ELSE 0 END) as created_by_ada
FROM waste_management;

-- 5. Cek data dengan JOIN setelah update
SELECT 
    wm.id,
    wm.created_by,
    wm.unit_id,
    wm.jenis_sampah,
    wm.status,
    u.nama_unit,
    us.nama_lengkap,
    us.username
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
LEFT JOIN users us ON us.id = wm.created_by
WHERE wm.status IN ('draft', 'dikirim', 'review')
ORDER BY wm.created_at DESC
LIMIT 10;
