-- ============================================
-- UPDATE FEATURE TOGGLE LENGKAP
-- Menambahkan semua fitur yang ada di sistem
-- ============================================

-- Hapus data lama (opsional, uncomment jika ingin reset)
-- TRUNCATE TABLE feature_toggles;

-- Insert/Update Feature Toggles Lengkap
INSERT INTO feature_toggles (feature_key, feature_name, description, category, is_enabled, target_roles, config, created_at, updated_at)
VALUES
-- ============================================
-- DASHBOARD FEATURES
-- ============================================
('dashboard_statistics_cards', 'Dashboard Statistics Cards', 'Tampilkan/sembunyikan kartu statistik di dashboard', 'dashboard', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_approved": true, "show_pending": true, "show_revision": true, "show_draft": true}', NOW(), NOW()),
('dashboard_waste_summary', 'Dashboard Waste Summary', 'Tampilkan/sembunyikan ringkasan waste management di dashboard', 'dashboard', 1, '["user", "pengelola_tps"]', '{"show_details": true, "show_value_calculation": true}', NOW(), NOW()),
('dashboard_recent_activity', 'Dashboard Recent Activity', 'Tampilkan/sembunyikan aktivitas terbaru di dashboard', 'dashboard', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"max_items": 5}', NOW(), NOW()),
('dashboard_charts', 'Dashboard Charts', 'Tampilkan/sembunyikan grafik dan chart di dashboard', 'dashboard', 1, '["admin_pusat"]', '{"chart_type": "line", "show_legend": true}', NOW(), NOW()),
('dashboard_quick_stats', 'Dashboard Quick Stats', 'Tampilkan/sembunyikan statistik cepat di dashboard', 'dashboard', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"refresh_interval": 60}', NOW(), NOW()),

-- ============================================
-- WASTE MANAGEMENT FEATURES
-- ============================================
('waste_input_form', 'Waste Input Form', 'Form input data sampah untuk User dan TPS', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_draft": true, "allow_direct_submit": true}', NOW(), NOW()),
('waste_edit_function', 'Waste Edit Function', 'Fungsi edit data sampah (hanya draft dan perlu revisi)', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_edit_draft": true, "allow_edit_revision": true}', NOW(), NOW()),
('waste_delete_function', 'Waste Delete Function', 'Fungsi hapus data sampah (hanya draft dan perlu revisi)', 'waste', 1, '["user", "pengelola_tps"]', '{"allow_delete_draft": true, "allow_delete_revision": true}', NOW(), NOW()),
('waste_value_calculation', 'Waste Value Calculation', 'Kalkulasi otomatis nilai sampah berdasarkan harga', 'waste', 1, '["user", "pengelola_tps"]', '{"show_price_breakdown": true, "auto_calculate": true}', NOW(), NOW()),
('waste_category_filter', 'Waste Category Filter', 'Filter data sampah berdasarkan kategori', 'waste', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_all_option": true}', NOW(), NOW()),
('waste_status_filter', 'Waste Status Filter', 'Filter data sampah berdasarkan status', 'waste', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_all_option": true}', NOW(), NOW()),
('waste_price_info', 'Waste Price Information', 'Tampilkan informasi harga sampah di halaman input', 'waste', 1, '["user", "pengelola_tps"]', '{"show_price_cards": true, "show_price_history": false}', NOW(), NOW()),

-- ============================================
-- EXPORT & REPORT FEATURES
-- ============================================
('export_data', 'Export Data', 'Fitur export data ke CSV/Excel', 'report', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"allowed_formats": ["csv", "excel"]}', NOW(), NOW()),
('export_pdf', 'Export PDF', 'Fitur export data ke PDF', 'report', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"include_logo": true, "include_signature": false}', NOW(), NOW()),
('laporan_waste', 'Laporan Waste', 'Laporan waste management untuk admin', 'report', 1, '["admin_pusat"]', '{"show_rekap_jenis": true, "show_rekap_unit": true}', NOW(), NOW()),
('laporan_filter', 'Laporan Filter', 'Filter laporan berdasarkan tanggal, status, unit', 'report', 1, '["admin_pusat"]', '{"date_range": true, "status_filter": true, "unit_filter": true}', NOW(), NOW()),

-- ============================================
-- ADMIN FEATURES
-- ============================================
('admin_review_waste', 'Admin Review Waste', 'Fitur review dan approve/reject data sampah', 'management', 1, '["admin_pusat"]', '{"allow_bulk_approve": true, "require_reason": true}', NOW(), NOW()),
('admin_user_management', 'Admin User Management', 'Manajemen user (tambah, edit, hapus, reset password)', 'management', 1, '["admin_pusat"]', '{"allow_delete": true, "allow_reset_password": true}', NOW(), NOW()),
('admin_harga_management', 'Admin Harga Management', 'Manajemen harga sampah (tambah, edit, hapus)', 'management', 1, '["admin_pusat"]', '{"allow_delete": true, "track_price_history": true}', NOW(), NOW()),
('admin_waste_management', 'Admin Waste Management', 'Manajemen data sampah oleh admin', 'management', 1, '["admin_pusat"]', '{"allow_edit_all": true, "allow_delete_all": true}', NOW(), NOW()),
('admin_unit_management', 'Admin Unit Management', 'Manajemen unit/TPS', 'management', 1, '["admin_pusat"]', '{"allow_add": true, "allow_edit": true, "allow_delete": false}', NOW(), NOW()),

-- ============================================
-- UI COMPONENTS
-- ============================================
('sidebar_quick_actions', 'Sidebar Quick Actions', 'Tombol aksi cepat di sidebar', 'ui', 1, '["user", "pengelola_tps"]', '{"show_input_form": true, "show_export": true, "show_reports": true}', NOW(), NOW()),
('help_tooltips', 'Help Tooltips', 'Tooltip bantuan di seluruh aplikasi', 'ui', 1, '["user", "pengelola_tps"]', '{"show_advanced_help": true}', NOW(), NOW()),
('notification_alerts', 'Notification Alerts', 'Notifikasi dan alert untuk user', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_success": true, "show_error": true, "show_warning": true}', NOW(), NOW()),
('breadcrumb_navigation', 'Breadcrumb Navigation', 'Navigasi breadcrumb di halaman', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"show_home_link": true}', NOW(), NOW()),
('search_function', 'Search Function', 'Fungsi pencarian data', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"search_by_date": true, "search_by_category": true}', NOW(), NOW()),
('pagination', 'Pagination', 'Pagination untuk tabel data', 'ui', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"items_per_page": 10, "show_page_numbers": true}', NOW(), NOW()),

-- ============================================
-- SYSTEM FEATURES
-- ============================================
('auto_logout', 'Auto Logout', 'Logout otomatis setelah tidak aktif', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"timeout_minutes": 30}', NOW(), NOW()),
('session_management', 'Session Management', 'Manajemen session user', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"max_sessions": 1, "force_logout_on_new_login": false}', NOW(), NOW()),
('audit_log', 'Audit Log', 'Log aktivitas user untuk audit', 'system', 1, '["admin_pusat"]', '{"log_all_actions": true, "retention_days": 90}', NOW(), NOW()),
('data_validation', 'Data Validation', 'Validasi data input', 'system', 1, '["user", "pengelola_tps", "admin_pusat"]', '{"strict_mode": true, "show_validation_errors": true}', NOW(), NOW()),
('backup_reminder', 'Backup Reminder', 'Pengingat backup data', 'system', 1, '["admin_pusat"]', '{"reminder_frequency": "weekly"}', NOW(), NOW())

ON DUPLICATE KEY UPDATE
    feature_name = VALUES(feature_name),
    description = VALUES(description),
    category = VALUES(category),
    target_roles = VALUES(target_roles),
    config = VALUES(config),
    updated_at = NOW();

-- ============================================
-- VERIFIKASI
-- ============================================
SELECT 
    category,
    COUNT(*) as total_features,
    SUM(is_enabled) as enabled_features,
    SUM(CASE WHEN is_enabled = 0 THEN 1 ELSE 0 END) as disabled_features
FROM feature_toggles
GROUP BY category
ORDER BY category;

-- Tampilkan semua feature toggles
SELECT 
    feature_key,
    feature_name,
    category,
    is_enabled,
    target_roles
FROM feature_toggles
ORDER BY category, feature_key;
