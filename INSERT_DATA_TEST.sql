-- Insert data test untuk waste_management
-- Pastikan unit_id sesuai dengan unit yang ada di database

-- Cek unit yang tersedia
SELECT id, nama_unit FROM unit WHERE status_aktif = 1;

-- Insert data test (sesuaikan unit_id dengan hasil query di atas)
INSERT INTO waste_management (
    unit_id,
    tanggal,
    jenis_sampah,
    satuan,
    jumlah,
    berat_kg,
    gedung,
    kategori_sampah,
    nilai_rupiah,
    status,
    created_at,
    updated_at
) VALUES 
(1, CURDATE(), 'Plastik', 'kg', 10.5, 10.5, 'Gedung A', 'bisa_dijual', 52500, 'draft', NOW(), NOW()),
(1, CURDATE(), 'Kertas', 'kg', 5.0, 5.0, 'Gedung B', 'bisa_dijual', 15000, 'dikirim', NOW(), NOW()),
(2, CURDATE(), 'Organik', 'kg', 20.0, 20.0, 'Gedung C', 'tidak_bisa_dijual', 0, 'draft', NOW(), NOW());

-- Verifikasi data berhasil diinsert
SELECT COUNT(*) as total FROM waste_management;

-- Cek data dengan JOIN
SELECT 
    wm.id,
    wm.unit_id,
    u.nama_unit,
    wm.jenis_sampah,
    wm.berat_kg,
    wm.status,
    wm.tanggal
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
ORDER BY wm.created_at DESC;
