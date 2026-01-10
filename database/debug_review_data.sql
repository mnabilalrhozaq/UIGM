-- =====================================================
-- DEBUG SCRIPT - Check Review Data
-- =====================================================

-- Check data waste dengan status dikirim
SELECT 'WASTE DATA WITH STATUS DIKIRIM:' as info;
SELECT 
    w.id,
    w.tanggal,
    w.jenis_sampah,
    w.jumlah,
    w.satuan,
    w.gedung,
    w.status,
    u.nama_unit,
    us.nama_lengkap,
    w.created_at,
    w.updated_at
FROM waste_management w
JOIN unit u ON u.id = w.unit_id
JOIN users us ON us.unit_id = w.unit_id
WHERE w.status = 'dikirim'
AND us.role = 'user'
ORDER BY w.updated_at DESC;

-- Check data penilaian dengan status dikirim
SELECT 'PENILAIAN DATA WITH STATUS DIKIRIM:' as info;
SELECT 
    p.id,
    p.kategori_uigm,
    p.indikator,
    p.nilai_input,
    p.status,
    u.nama_unit,
    us.nama_lengkap,
    p.created_at,
    p.updated_at
FROM penilaian_unit p
JOIN unit u ON u.id = p.unit_id
JOIN users us ON us.unit_id = p.unit_id
WHERE p.status = 'dikirim'
AND us.role = 'user'
ORDER BY p.updated_at DESC;

-- Check notifications
SELECT 'NOTIFICATIONS:' as info;
SELECT 
    n.id,
    n.title,
    n.message,
    n.type,
    n.is_read,
    u.nama_lengkap as recipient,
    n.created_at
FROM notifications n
JOIN users u ON u.id = n.user_id
ORDER BY n.created_at DESC
LIMIT 10;

-- Summary
SELECT 'SUMMARY:' as info;
SELECT 
    (SELECT COUNT(*) FROM waste_management WHERE status = 'dikirim') as waste_dikirim,
    (SELECT COUNT(*) FROM penilaian_unit WHERE status = 'dikirim') as penilaian_dikirim,
    (SELECT COUNT(*) FROM notifications WHERE is_read = 0) as unread_notifications;