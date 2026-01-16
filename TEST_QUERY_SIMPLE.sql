-- Test query sederhana untuk waste management

-- 1. Cek total data
SELECT COUNT(*) as total FROM waste_management;

-- 2. Cek data dengan JOIN unit (query yang digunakan service)
SELECT 
    waste_management.*,
    unit.nama_unit
FROM waste_management
LEFT JOIN unit ON unit.id = waste_management.unit_id
ORDER BY waste_management.created_at DESC
LIMIT 10;

-- 3. Cek apakah ada data dengan status draft, dikirim, review
SELECT 
    waste_management.*,
    unit.nama_unit
FROM waste_management
LEFT JOIN unit ON unit.id = waste_management.unit_id
WHERE waste_management.status IN ('draft', 'dikirim', 'review')
ORDER BY waste_management.created_at DESC
LIMIT 10;

-- 4. Cek struktur data yang dikembalikan
SELECT 
    waste_management.id,
    waste_management.unit_id,
    waste_management.jenis_sampah,
    waste_management.berat_kg,
    waste_management.status,
    waste_management.tanggal,
    unit.nama_unit
FROM waste_management
LEFT JOIN unit ON unit.id = waste_management.unit_id
LIMIT 5;
