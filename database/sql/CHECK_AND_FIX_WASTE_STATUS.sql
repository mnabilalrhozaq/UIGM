-- Script untuk mengecek dan memperbaiki status waste

-- 1. Cek data di waste_management
SELECT 
    'Data di waste_management' as tabel,
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status
ORDER BY status;

-- 2. Cek apakah tabel laporan_waste ada
SELECT 
    'Tabel laporan_waste' as info,
    CASE 
        WHEN COUNT(*) > 0 THEN 'ADA'
        ELSE 'TIDAK ADA'
    END as status
FROM information_schema.tables 
WHERE table_schema = DATABASE() 
AND table_name = 'laporan_waste';

-- 3. Jika tabel laporan_waste ada, cek isinya
SELECT 
    'Data di laporan_waste' as tabel,
    status,
    COUNT(*) as jumlah
FROM laporan_waste
GROUP BY status
ORDER BY status;

-- 4. Tampilkan data yang perlu dimigrasi
SELECT 
    'Data yang perlu dimigrasi' as info,
    id,
    jenis_sampah,
    berat_kg,
    status,
    created_at
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi')
ORDER BY created_at DESC
LIMIT 20;
