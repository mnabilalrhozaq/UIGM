-- Test Query untuk Verifikasi Database Setelah Import
-- Jalankan query ini di phpMyAdmin setelah import eksperimen_fixed.sql

-- 1. Cek jumlah tabel (harus 15)
SELECT COUNT(*) as total_tables 
FROM information_schema.tables 
WHERE table_schema = 'eksperimen';
-- Expected: 15

-- 2. Cek collation semua tabel (harus utf8mb4_unicode_ci)
SELECT table_name, table_collation 
FROM information_schema.tables 
WHERE table_schema = 'eksperimen' 
ORDER BY table_name;
-- Expected: Semua utf8mb4_unicode_ci

-- 3. Cek data users (harus 6 rows)
SELECT COUNT(*) as total_users FROM users;
-- Expected: 6

-- 4. Cek data master_harga_sampah (harus 6 rows)
SELECT COUNT(*) as total_harga FROM master_harga_sampah;
-- Expected: 6

-- 5. Cek data waste_management (harus 19 rows)
SELECT COUNT(*) as total_waste FROM waste_management;
-- Expected: 19

-- 6. Test login admin
SELECT id, username, email, role 
FROM users 
WHERE username = 'admin';
-- Expected: 1 row (admin_pusat)

-- 7. Test query ORDER BY (yang digunakan di aplikasi)
SELECT jenis_sampah, nama_jenis, harga_per_satuan 
FROM master_harga_sampah 
WHERE status_aktif = 1 
ORDER BY jenis_sampah ASC;
-- Expected: Data terurut alfabetis

-- 8. Test JOIN query
SELECT 
    w.id,
    w.tanggal,
    w.jenis_sampah,
    w.jumlah,
    u.nama_lengkap as user_name
FROM waste_management w
LEFT JOIN users u ON w.user_id = u.id
LIMIT 5;
-- Expected: Data dengan join berhasil

-- 9. Cek log perubahan harga
SELECT COUNT(*) as total_logs FROM log_perubahan_harga;
-- Expected: 4 rows

-- 10. Test search (case insensitive)
SELECT * FROM master_harga_sampah 
WHERE jenis_sampah LIKE '%plastik%';
-- Expected: Tetap bisa search case insensitive

-- ✅ Jika semua query di atas berhasil, database sudah OK!
