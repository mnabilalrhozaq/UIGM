-- ========================================
-- CLEANUP DAN KONEKSI INPUT USER/TPS KE WASTE MANAGEMENT ADMIN
-- ========================================

-- STEP 1: BACKUP DATA LAMA (OPSIONAL)
-- Uncomment jika ingin backup dulu
/*
CREATE TABLE IF NOT EXISTS waste_management_backup AS
SELECT * FROM waste_management;
*/

-- STEP 2: HAPUS SEMUA DATA LAMA DI WASTE_MANAGEMENT
-- Data lama tidak nyambung dengan user/TPS, jadi lebih baik dihapus
TRUNCATE TABLE waste_management;

-- Verifikasi: Seharusnya 0
SELECT COUNT(*) as total_setelah_hapus FROM waste_management;

-- STEP 3: CEK STRUKTUR TABEL WASTE_MANAGEMENT
-- Pastikan kolom yang diperlukan ada
DESCRIBE waste_management;

-- STEP 4: VERIFIKASI DATA USER DAN TPS
-- Cek user yang bisa input data (role: user dan pengelola_tps)
SELECT 
    id,
    username,
    nama_lengkap,
    role,
    unit_id,
    status_aktif
FROM users
WHERE role IN ('user', 'pengelola_tps')
AND status_aktif = 1
ORDER BY unit_id, role;

-- STEP 5: VERIFIKASI UNIT
-- Cek unit yang aktif
SELECT 
    id,
    nama_unit,
    status_aktif
FROM unit
WHERE status_aktif = 1
ORDER BY nama_unit;

-- ========================================
-- SETELAH INI, USER DAN TPS BISA INPUT DATA BARU
-- ========================================

-- Data yang diinput oleh user/TPS akan otomatis masuk ke waste_management
-- dengan struktur:
-- - unit_id: dari user yang login
-- - tanggal: tanggal input
-- - jenis_sampah: jenis sampah yang dipilih
-- - berat_kg: berat sampah
-- - status: 'draft' atau 'dikirim'
-- - created_at: timestamp otomatis

-- Admin bisa lihat data di waste_management dengan query:
SELECT 
    wm.id,
    wm.unit_id,
    wm.jenis_sampah,
    wm.berat_kg,
    wm.status,
    wm.tanggal,
    u.nama_unit,
    wm.created_at
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
ORDER BY wm.created_at DESC;

-- ========================================
-- TESTING
-- ========================================

-- Setelah user/TPS input data baru, cek dengan query ini:
SELECT 
    wm.id,
    wm.unit_id,
    u.nama_unit,
    wm.jenis_sampah,
    wm.berat_kg,
    wm.status,
    wm.tanggal,
    wm.created_at
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
ORDER BY wm.created_at DESC
LIMIT 10;

-- Cek jumlah data per status
SELECT 
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status;

-- Cek jumlah data per unit
SELECT 
    u.nama_unit,
    COUNT(wm.id) as jumlah_data
FROM waste_management wm
LEFT JOIN unit u ON u.id = wm.unit_id
GROUP BY wm.unit_id, u.nama_unit
ORDER BY jumlah_data DESC;
