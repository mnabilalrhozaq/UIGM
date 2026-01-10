-- =====================================================
-- DEBUG REJECT ISSUE
-- =====================================================

-- 1. Check data waste dengan status dikirim
SELECT 'CURRENT WASTE DATA WITH STATUS DIKIRIM:' as info;
SELECT 
    id,
    unit_id,
    tanggal,
    jenis_sampah,
    jumlah,
    gedung,
    status,
    catatan_admin,
    created_at,
    updated_at
FROM waste_management 
WHERE status = 'dikirim'
ORDER BY id;

-- 2. Check struktur tabel waste_management
SELECT 'WASTE_MANAGEMENT TABLE STRUCTURE:' as info;
DESCRIBE waste_management;

-- 3. Check apakah ada constraint yang menghalangi update
SELECT 'TABLE CONSTRAINTS:' as info;
SELECT 
    CONSTRAINT_NAME,
    CONSTRAINT_TYPE,
    TABLE_NAME
FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
WHERE TABLE_NAME = 'waste_management';

-- 4. Manual test update (uncomment untuk test)
-- UPDATE waste_management 
-- SET status = 'perlu_revisi', catatan_admin = 'Test reject dari SQL'
-- WHERE id = 5 AND status = 'dikirim';

-- 5. Check hasil manual update
SELECT 'AFTER MANUAL UPDATE (if executed):' as info;
SELECT 
    id,
    status,
    catatan_admin,
    updated_at
FROM waste_management 
WHERE id IN (4, 5)
ORDER BY id;

-- 6. Check user permissions
SELECT 'ADMIN PUSAT USERS:' as info;
SELECT id, username, nama_lengkap, role FROM users WHERE role = 'admin_pusat';