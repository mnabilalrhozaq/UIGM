-- Cek struktur tabel waste_management
DESCRIBE waste_management;

-- Cek sample data dengan semua kolom
SELECT 
    id,
    user_id,
    unit_id,
    jenis_sampah,
    berat_kg,
    status,
    tanggal,
    created_at
FROM waste_management
LIMIT 10;

-- Cek apakah ada kolom user_id
SHOW COLUMNS FROM waste_management LIKE 'user_id';

-- Cek data dengan JOIN untuk melihat apakah user_id ada
SELECT 
    wm.id,
    wm.user_id,
    wm.unit_id,
    wm.jenis_sampah,
    wm.status,
    u.nama_unit,
    us.nama_lengkap,
    us.username
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
LEFT JOIN users us ON us.id = wm.user_id
LIMIT 10;
