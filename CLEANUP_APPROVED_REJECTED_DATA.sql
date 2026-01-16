-- Script untuk membersihkan data yang sudah disetujui/ditolak dari waste_management
-- Data ini seharusnya sudah dipindahkan ke laporan_waste

-- 1. Cek data yang akan dihapus
SELECT 
    id,
    jenis_sampah,
    berat_kg,
    status,
    tanggal,
    created_at
FROM waste_management
WHERE status IN ('disetujui', 'ditolak', 'perlu_revisi', 'approved', 'rejected');

-- 2. Backup data ke laporan_waste jika belum ada (opsional, untuk safety)
-- Uncomment jika ingin backup dulu sebelum hapus
/*
INSERT INTO laporan_waste (
    waste_id,
    unit_id,
    jenis_sampah,
    berat_kg,
    satuan,
    jumlah,
    nilai_rupiah,
    tanggal_input,
    status,
    reviewed_by,
    reviewed_at,
    review_notes,
    created_by,
    created_at
)
SELECT 
    wm.id as waste_id,
    wm.unit_id,
    wm.jenis_sampah,
    wm.berat_kg,
    COALESCE(wm.satuan, 'kg') as satuan,
    COALESCE(wm.jumlah, wm.berat_kg) as jumlah,
    COALESCE(wm.nilai_rupiah, 0) as nilai_rupiah,
    COALESCE(wm.tanggal, CURDATE()) as tanggal_input,
    CASE 
        WHEN wm.status IN ('disetujui', 'approved') THEN 'approved'
        WHEN wm.status IN ('ditolak', 'rejected', 'perlu_revisi') THEN 'rejected'
        ELSE 'approved'
    END as status,
    1 as reviewed_by,
    NOW() as reviewed_at,
    'Data cleanup - dipindahkan dari waste_management' as review_notes,
    wm.created_by,
    wm.created_at
FROM waste_management wm
WHERE wm.status IN ('disetujui', 'ditolak', 'perlu_revisi', 'approved', 'rejected')
AND NOT EXISTS (
    SELECT 1 FROM laporan_waste lw 
    WHERE lw.waste_id = wm.id
);
*/

-- 3. Hapus data yang sudah disetujui/ditolak dari waste_management
DELETE FROM waste_management
WHERE status IN ('disetujui', 'ditolak', 'perlu_revisi', 'approved', 'rejected');

-- 4. Verifikasi hasil
SELECT 
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status;

-- Seharusnya hanya tersisa data dengan status: draft, dikirim, review

-- 5. Cek data dengan JOIN untuk memastikan unit dan user ditampilkan
SELECT 
    wm.id,
    wm.created_by,
    wm.unit_id,
    wm.jenis_sampah,
    wm.status,
    u.nama_unit,
    us.nama_lengkap
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
LEFT JOIN users us ON us.id = wm.created_by
WHERE wm.status IN ('draft', 'dikirim', 'review')
LIMIT 10;
