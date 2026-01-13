-- ============================================
-- RESET & REINSTALL FEATURE TOGGLE
-- Hapus semua data lama dan install ulang
-- UPDATED: Sesuai dengan fitur yang ada di web
-- ============================================

-- 1. Hapus semua data lama
TRUNCATE TABLE feature_toggles;

-- 2. Insert data baru (sesuai dengan yang dipakai di web)
INSERT INTO feature_toggles (feature_key, feature_name, description, is_enabled, target_roles, config) VALUES

-- Dashboard Features (dipakai di user/dashboard.php)
('dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Tampilkan kartu statistik (Disetujui, Perlu Revisi, dll)', 1, '["admin_pusat","user","pengelola_tps"]', '{"show_approved":true,"show_pending":true,"show_rejected":true,"show_draft":true}'),
('dashboard_waste_summary', 'Dashboard Waste Summary', 'Tampilkan ringkasan waste management di dashboard', 1, '["admin_pusat","user","pengelola_tps"]', '{}'),
('dashboard_recent_activity', 'Dashboard Recent Activity', 'Tampilkan aktivitas terbaru di dashboard', 1, '["admin_pusat","user","pengelola_tps"]', '{"max_items":10}'),
('detailed_statistics', 'Detailed Statistics', 'Statistik detail dan grafik di dashboard', 1, '["admin_pusat","user"]', '{}'),

-- Waste Management Features
('waste_management', 'Waste Management', 'Fitur kelola data sampah (input, edit, delete)', 1, '["user","pengelola_tps"]', '{}'),
('waste_value_calculation', 'Waste Value Calculation', 'Hitung dan tampilkan nilai ekonomis sampah', 1, '["user","pengelola_tps"]', '{"show_price_breakdown":true}'),
('waste_export_feature', 'Waste Export Feature', 'Tombol export data waste ke Excel/PDF', 1, '["admin_pusat","user","pengelola_tps"]', '{"formats":["excel","pdf","csv"]}'),

-- Admin Features (menu di sidebar admin)
('price_management', 'Price Management', 'Menu Manajemen Harga sampah', 1, '["admin_pusat"]', '{}'),
('user_management', 'User Management', 'Menu User Management', 1, '["admin_pusat","super_admin"]', '{}'),
('review_system', 'Review System', 'Review dan approve data waste', 1, '["admin_pusat"]', '{}'),
('reporting', 'Reporting & Analytics', 'Menu Laporan & Monitoring', 1, '["admin_pusat"]', '{}'),

-- UI Features
('sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat (Input Data, Export) di dashboard', 1, '["user","pengelola_tps"]', '{}'),
('help_tooltips', 'Help Tooltips', 'Tooltip dan bantuan di UI', 1, '["user","pengelola_tps"]', '{}'),
('advanced_menu_items', 'Advanced Menu Items', 'Menu dan fitur advanced untuk user', 0, '["user","pengelola_tps"]', '{}'),

-- System Features
('real_time_updates', 'Real-time Updates', 'Auto refresh data dashboard secara real-time', 0, '["user","pengelola_tps"]', '{"refresh_interval":30,"enabled":false}');

-- 3. Verify
SELECT 
    COUNT(*) as total_features,
    SUM(is_enabled) as enabled_features,
    COUNT(*) - SUM(is_enabled) as disabled_features
FROM feature_toggles;

-- 4. Show all features
SELECT 
    feature_key,
    feature_name,
    CASE WHEN is_enabled = 1 THEN '✓ ON' ELSE '✗ OFF' END as status,
    target_roles
FROM feature_toggles
ORDER BY 
    CASE 
        WHEN feature_key LIKE 'dashboard%' THEN 1
        WHEN feature_key LIKE 'waste%' THEN 2
        WHEN feature_key IN ('price_management','user_management','review_system','reporting') THEN 3
        ELSE 4
    END,
    feature_key;

-- ============================================
-- DONE! Total 15 features installed
-- 
-- Features yang AKTIF (ON):
-- - Dashboard: 4 fitur
-- - Waste Management: 3 fitur  
-- - Admin Features: 4 fitur
-- - UI Features: 2 fitur
-- 
-- Features yang NONAKTIF (OFF):
-- - Advanced Menu Items (untuk user advanced)
-- - Real-time Updates (auto refresh)
-- ============================================
