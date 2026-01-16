-- Debug: Cek apakah ada data di waste_management
SELECT COUNT(*) as total_data FROM waste_management;

-- Debug: Cek data dengan semua kolom
SELECT * FROM waste_management LIMIT 5;

-- Debug: Cek data per status
SELECT status, COUNT(*) as jumlah 
FROM waste_management 
GROUP BY status;

-- Debug: Test query yang digunakan service (tanpa filter)
SELECT 
    waste_management.*,
    unit.nama_unit
FROM waste_management
LEFT JOIN unit ON unit.id = waste_management.unit_id
ORDER BY waste_management.created_at DESC
LIMIT 10;

-- Debug: Cek apakah ada error di query
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
