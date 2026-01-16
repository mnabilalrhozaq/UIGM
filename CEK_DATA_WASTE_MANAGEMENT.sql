-- Cek apakah masih ada data di waste_management
SELECT COUNT(*) as total_data FROM waste_management;

-- Cek data berdasarkan status
SELECT 
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status;

-- Cek semua data yang ada
SELECT 
    id,
    created_by,
    unit_id,
    jenis_sampah,
    berat_kg,
    status,
    tanggal,
    created_at
FROM waste_management
ORDER BY created_at DESC
LIMIT 20;

-- Cek apakah data ada di laporan_waste
SELECT COUNT(*) as total_laporan FROM laporan_waste;

-- Cek data di laporan_waste
SELECT 
    id,
    waste_id,
    unit_id,
    jenis_sampah,
    status,
    tanggal_input,
    created_at
FROM laporan_waste
ORDER BY created_at DESC
LIMIT 20;
