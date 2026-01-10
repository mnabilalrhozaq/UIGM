-- =====================================================
-- TEST WASTE ACTIONS
-- =====================================================

-- 1. Check data waste dengan status dikirim
SELECT 'WASTE DATA WITH STATUS DIKIRIM:' as info;
SELECT 
    id,
    unit_id,
    tanggal,
    jenis_sampah,
    jumlah,
    satuan,
    gedung,
    status,
    catatan_admin,
    created_at
FROM waste_management 
WHERE status = 'dikirim'
ORDER BY created_at DESC;

-- 2. Insert sample waste data untuk testing (jika tidak ada)
INSERT IGNORE INTO waste_management (unit_id, tanggal, jenis_sampah, satuan, jumlah, gedung, status) 
VALUES 
(1, '2026-01-05', 'Kertas', 'kg', 10.5, 'Gedung A', 'dikirim'),
(1, '2026-01-05', 'Plastik', 'kg', 5.2, 'Gedung B', 'dikirim');

-- 3. Check hasil setelah insert
SELECT 'AFTER INSERT SAMPLE DATA:' as info;
SELECT 
    id,
    unit_id,
    tanggal,
    jenis_sampah,
    jumlah,
    satuan,
    gedung,
    status,
    created_at
FROM waste_management 
WHERE status = 'dikirim'
ORDER BY created_at DESC;

-- 4. Test approve action (manual simulation)
-- UPDATE waste_management 
-- SET status = 'disetujui', catatan_admin = 'Data disetujui oleh Admin Pusat'
-- WHERE id = 1 AND status = 'dikirim';

-- 5. Test reject action (manual simulation)  
-- UPDATE waste_management 
-- SET status = 'perlu_revisi', catatan_admin = 'Data perlu diperbaiki'
-- WHERE id = 2 AND status = 'dikirim';

-- 6. Summary
SELECT 'SUMMARY:' as info;
SELECT 
    status,
    COUNT(*) as jumlah
FROM waste_management 
GROUP BY status
ORDER BY status;