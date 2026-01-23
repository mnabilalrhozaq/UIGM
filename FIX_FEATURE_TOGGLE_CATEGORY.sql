-- ============================================
-- FIX FEATURE TOGGLE CATEGORY
-- Ubah kolom category dari ENUM ke VARCHAR
-- ============================================

-- Ubah kolom category menjadi VARCHAR
ALTER TABLE feature_toggles 
MODIFY COLUMN category VARCHAR(50) NOT NULL DEFAULT 'general';

-- Verifikasi perubahan
DESCRIBE feature_toggles;

-- Sekarang insert/update data
INSERT INTO feature_toggles (feature_key, feature_name, description, category, is_enabled, target_roles, config, created_at, updated_at)
VALUES
-- Dashboard Features
('dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Tampilkan/sembunyikan kartu statistik di dashboard', 'dashboard', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_approved": true, "show_pending": true}', NOW(), NOW()),
('dashboard_waste_summary', 'Dashboard Waste Summary', 'Tampilkan/sembunyikan ringkasan waste management di dashboard', 'dashboard', 1, '["user", "pengelola_tps"]', '{"show_details": true}', NOW(), NOW()),
('dashboard_recent_activity', 'Dashboard Recent Activity', 'Tampilkan/sembunyikan aktivitas terbaru di dashboard', 'dashboard', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"max_items": 5}', NOW(), NOW()),
('dashboard_charts', 'Dashboard Charts', 'Tampilkan/sembunyikan grafik dan chart di dashboard', 'dashboard', 1, '["admin_pusat"]', '{"chart_type": "line"}', NOW(), NOW()),

-- Waste Management Features
('waste_input_form', 'Waste Input Form', 'Form input data sampah untuk User dan TPS', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_draft": true}', NOW(), NOW()),
('waste_edit_function', 'Waste Edit Function', 'Fungsi edit data sampah', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_edit_draft": true}', NOW(), NOW()),
('waste_delete_function', 'Waste Delete Function', 'Fungsi hapus data sampah', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_delete_draft": true}', NOW(), NOW()),
('waste_value_calculation', 'Waste Value Calculation', 'Kalkulasi otomatis nilai sampah', 'waste', 1, '["user", "pengelola_tps"]', '{"auto_calculate": true}', NOW(), NOW()),
('waste_price_info', 'Waste Price Information', 'Tampilkan informasi harga sampah', 'waste', 1, '["user", "pengelola_tps"]', '{"show_price_cards": true}', NOW(), NOW()),

-- Export & Report Features
('export_data', 'Export Data', 'Fitur export data ke CSV/Excel', 'report', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"allowed_formats": ["csv"]}', NOW(), NOW()),
('export_pdf', 'Export PDF', 'Fitur export data ke PDF', 'report', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"include_logo": true}', NOW(), NOW()),
('laporan_waste', 'Laporan Waste', 'Laporan waste management untuk admin', 'report', 1, '["admin_pusat"]', '{"show_rekap_jenis": true}', NOW(), NOW()),

-- Admin Features
('admin_review_waste', 'Admin Review Waste', 'Fitur review dan approve/reject data sampah', 'management', 1, '["admin_pusat"]', '{"allow_bulk_approve": true}', NOW(), NOW()),
('admin_user_management', 'Admin User Management', 'Manajemen user', 'management', 1, '["admin_pusat"]', '{"allow_delete": true}', NOW(), NOW()),
('admin_harga_management', 'Admin Harga Management', 'Manajemen harga sampah', 'management', 1, '["admin_pusat"]', '{"allow_delete": true}', NOW(), NOW()),
('admin_waste_management', 'Admin Waste Management', 'Manajemen data sampah oleh admin', 'management', 1, '["admin_pusat"]', '{"allow_edit_all": true}', NOW(), NOW()),

-- UI Components
('sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat di sidebar', 'ui', 1, '["user", "pengelola_tps"]', '{"show_input_form": true}', NOW(), NOW()),
('help_tooltips', 'Help Tooltips', 'Tooltip bantuan', 'ui', 1, '["user", "pengelola_tps"]', '{"show_advanced_help": true}', NOW(), NOW()),
('notification_alerts', 'Notification Alerts', 'Notifikasi dan alert', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_success": true}', NOW(), NOW()),
('pagination', 'Pagination', 'Pagination untuk tabel data', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"items_per_page": 10}', NOW(), NOW()),

-- System Features
('auto_logout', 'Auto Logout', 'Logout otomatis setelah tidak aktif', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"timeout_minutes": 30}', NOW(), NOW()),
('session_management', 'Session Management', 'Manajemen session user', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"max_sessions": 1}', NOW(), NOW()),
('audit_log', 'Audit Log', 'Log aktivitas user untuk audit', 'system', 1, '["admin_pusat"]', '{"log_all_actions": true}', NOW(), NOW()),
('data_validation', 'Data Validation', 'Validasi data input', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"strict_mode": true}', NOW(), NOW())

ON DUPLICATE KEY UPDATE
    feature_name = VALUES(feature_name),
    description = VALUES(description),
    category = VALUES(category),
    target_roles = VALUES(target_roles),
    config = VALUES(config),
    updated_at = NOW();

-- Verifikasi hasil
SELECT 
    category,
    COUNT(*) as total_features,
    SUM(is_enabled) as enabled_features
FROM feature_toggles
GROUP BY category
ORDER BY category;

SELECT feature_key, feature_name, category, is_enabled
FROM feature_toggles
ORDER BY category, feature_key;
