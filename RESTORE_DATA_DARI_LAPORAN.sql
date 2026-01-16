-- Script untuk restore data dari laporan_waste ke waste_management
-- HANYA untuk data yang statusnya masih draft atau dikirim

-- 1. Cek data di laporan_waste yang seharusnya masih di waste_management
SELECT 
    lw.id,
    lw.waste_id,
    lw.unit_id,
    lw.jenis_sampah,
    lw.status,
    lw.tanggal_input,
    lw.created_by
FROM laporan_waste lw
WHERE lw.status IN ('draft', 'dikirim', 'review')
ORDER BY lw.created_at DESC;

-- 2. Restore data dari laporan_waste ke waste_management
-- HANYA jika data memang terhapus tidak sengaja
/*
INSERT INTO waste_management (
    unit_id,
    created_by,
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
)
SELECT 
    lw.unit_id,
    lw.created_by,
    lw.tanggal_input,
    lw.jenis_sampah,
    lw.satuan,
    lw.jumlah,
    lw.berat_kg,
    'N/A' as gedung,
    CASE 
        WHEN lw.nilai_rupiah > 0 THEN 'bisa_dijual'
        ELSE 'tidak_bisa_dijual'
    END as kategori_sampah,
    lw.nilai_rupiah,
    CASE 
        WHEN lw.status = 'draft' THEN 'draft'
        WHEN lw.status = 'dikirim' THEN 'dikirim'
        WHEN lw.status = 'review' THEN 'review'
        ELSE 'draft'
    END as status,
    lw.created_at,
    NOW() as updated_at
FROM laporan_waste lw
WHERE lw.status IN ('draft', 'dikirim', 'review')
AND NOT EXISTS (
    SELECT 1 FROM waste_management wm 
    WHERE wm.id = lw.waste_id
);
*/

-- 3. Hapus data yang sudah di-restore dari laporan_waste
-- (karena data draft/dikirim seharusnya tidak ada di laporan_waste)
/*
DELETE FROM laporan_waste
WHERE status IN ('draft', 'dikirim', 'review');
*/

-- 4. Verifikasi hasil
SELECT 
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status;
