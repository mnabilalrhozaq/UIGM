-- ============================================
-- FEATURE TOGGLE INSTALLATION
-- Jalankan di phpMyAdmin untuk install Feature Toggle
-- ============================================

-- 1. Create table
CREATE TABLE IF NOT EXISTS `feature_toggles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feature_key` varchar(100) NOT NULL,
  `feature_name` varchar(255) NOT NULL,
  `description` text,
  `is_enabled` tinyint(1) DEFAULT 1,
  `target_roles` varchar(255) DEFAULT NULL COMMENT 'JSON array of roles',
  `config` text COMMENT 'JSON configuration',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `feature_key` (`feature_key`),
  KEY `is_enabled` (`is_enabled`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Insert default features
INSERT INTO `feature_toggles` (`feature_key`, `feature_name`, `description`, `is_enabled`, `target_roles`, `config`) VALUES
-- Dashboard Features
('dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Tampilkan kartu statistik di dashboard', 1, '["admin_pusat","user","pengelola_tps"]', '{"show_approved":true,"show_pending":true,"show_rejected":true}'),
('dashboard_waste_summary', 'Dashboard Waste Summary', 'Tampilkan ringkasan waste management', 1, '["admin_pusat","user","pengelola_tps"]', '{}'),
('dashboard_recent_activity', 'Dashboard Recent Activity', 'Tampilkan aktivitas terbaru', 1, '["admin_pusat","user","pengelola_tps"]', '{"max_items":10}'),
('detailed_statistics', 'Detailed Statistics', 'Statistik detail di dashboard', 1, '["admin_pusat","user"]', '{}'),

-- Waste Management Features
('waste_management', 'Waste Management', 'Fitur kelola data sampah', 1, '["user","pengelola_tps"]', '{}'),
('waste_value_calculation', 'Waste Value Calculation', 'Hitung nilai ekonomis sampah', 1, '["user","pengelola_tps"]', '{}'),
('export_data', 'Export Data', 'Export data ke Excel/PDF', 1, '["admin_pusat","user","pengelola_tps"]', '{"formats":["excel","pdf","csv"]}'),

-- Management Features
('price_management', 'Price Management', 'Kelola harga sampah', 1, '["admin_pusat"]', '{}'),
('user_management', 'User Management', 'Kelola user sistem', 1, '["admin_pusat","super_admin"]', '{}'),
('review_system', 'Review System', 'Review dan approve data', 1, '["admin_pusat"]', '{}'),
('reporting', 'Reporting & Analytics', 'Laporan dan analitik', 1, '["admin_pusat"]', '{}'),

-- System Features
('real_time_updates', 'Real-time Updates', 'Update data real-time', 0, '["user","pengelola_tps"]', '{"refresh_interval":30}'),
('sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat di sidebar', 1, '["user","pengelola_tps"]', '{}'),
('help_tooltips', 'Help Tooltips', 'Tooltip bantuan di UI', 1, '["user","pengelola_tps"]', '{}');

-- 3. Verify installation
SELECT 
    COUNT(*) as total_features,
    SUM(is_enabled) as enabled_features,
    COUNT(*) - SUM(is_enabled) as disabled_features
FROM feature_toggles;

-- 4. Show all features
SELECT 
    feature_key,
    feature_name,
    CASE WHEN is_enabled = 1 THEN 'ON' ELSE 'OFF' END as status,
    target_roles
FROM feature_toggles
ORDER BY feature_key;

-- ============================================
-- INSTALLATION COMPLETE!
-- 
-- Next steps:
-- 1. Login sebagai Admin Pusat
-- 2. Buka menu "Feature Toggle"
-- 3. Test toggle ON/OFF fitur
-- 4. Login sebagai User untuk lihat perubahan
-- ============================================
