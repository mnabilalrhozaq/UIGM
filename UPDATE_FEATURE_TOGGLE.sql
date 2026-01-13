-- ============================================
-- UPDATE EXISTING FEATURE TOGGLE DATA
-- Jalankan ini jika tabel sudah ada
-- ============================================

-- 1. Cek data yang sudah ada
SELECT feature_key, feature_name, is_enabled FROM feature_toggles;

-- 2. Update data yang sudah ada (jika ada)
UPDATE feature_toggles SET 
    feature_name = 'Dashboard Statistics Cards',
    description = 'Tampilkan kartu statistik di dashboard',
    target_roles = '["admin_pusat","user","pengelola_tps"]',
    config = '{"show_approved":true,"show_pending":true,"show_rejected":true}'
WHERE feature_key = 'dashboard_statistics_cards';

UPDATE feature_toggles SET 
    feature_name = 'Dashboard Waste Summary',
    description = 'Tampilkan ringkasan waste management',
    target_roles = '["admin_pusat","user","pengelola_tps"]',
    config = '{}'
WHERE feature_key = 'dashboard_waste_summary';

UPDATE feature_toggles SET 
    feature_name = 'Dashboard Recent Activity',
    description = 'Tampilkan aktivitas terbaru',
    target_roles = '["admin_pusat","user","pengelola_tps"]',
    config = '{"max_items":10}'
WHERE feature_key = 'dashboard_recent_activity';

-- 3. Insert data baru yang belum ada (menggunakan INSERT IGNORE)
INSERT IGNORE INTO feature_toggles (feature_key, feature_name, description, is_enabled, target_roles, config) VALUES
('detailed_statistics', 'Detailed Statistics', 'Statistik detail di dashboard', 1, '["admin_pusat","user"]', '{}'),
('waste_management', 'Waste Management', 'Fitur kelola data sampah', 1, '["user","pengelola_tps"]', '{}'),
('waste_value_calculation', 'Waste Value Calculation', 'Hitung nilai ekonomis sampah', 1, '["user","pengelola_tps"]', '{}'),
('export_data', 'Export Data', 'Export data ke Excel/PDF', 1, '["admin_pusat","user","pengelola_tps"]', '{"formats":["excel","pdf","csv"]}'),
('price_management', 'Price Management', 'Kelola harga sampah', 1, '["admin_pusat"]', '{}'),
('user_management', 'User Management', 'Kelola user sistem', 1, '["admin_pusat","super_admin"]', '{}'),
('review_system', 'Review System', 'Review dan approve data', 1, '["admin_pusat"]', '{}'),
('reporting', 'Reporting & Analytics', 'Laporan dan analitik', 1, '["admin_pusat"]', '{}'),
('real_time_updates', 'Real-time Updates', 'Update data real-time', 0, '["user","pengelola_tps"]', '{"refresh_interval":30}'),
('sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat di sidebar', 1, '["user","pengelola_tps"]', '{}'),
('help_tooltips', 'Help Tooltips', 'Tooltip bantuan di UI', 1, '["user","pengelola_tps"]', '{}');

-- 4. Verify
SELECT 
    feature_key,
    feature_name,
    CASE WHEN is_enabled = 1 THEN 'ON' ELSE 'OFF' END as status,
    target_roles
FROM feature_toggles
ORDER BY feature_key;

-- ============================================
-- DONE! Sekarang coba akses Feature Toggle
-- ============================================
