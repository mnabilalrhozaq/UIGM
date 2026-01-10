-- Tabel untuk Feature Toggle Management
CREATE TABLE IF NOT EXISTS `feature_toggles` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `feature_key` varchar(100) NOT NULL UNIQUE,
    `feature_name` varchar(255) NOT NULL,
    `description` text,
    `is_enabled` tinyint(1) NOT NULL DEFAULT 0,
    `role_config` json,
    `created_by` int(11),
    `updated_by` int(11),
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_feature_key` (`feature_key`),
    KEY `idx_is_enabled` (`is_enabled`),
    KEY `fk_feature_created_by` (`created_by`),
    KEY `fk_feature_updated_by` (`updated_by`),
    CONSTRAINT `fk_feature_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_feature_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel untuk Log Perubahan Harga
CREATE TABLE IF NOT EXISTS `harga_logs` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `harga_id` int(11) NOT NULL,
    `action` varchar(50) NOT NULL,
    `old_data` json,
    `new_data` json,
    `user_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_harga_id` (`harga_id`),
    KEY `idx_action` (`action`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_created_at` (`created_at`),
    CONSTRAINT `fk_harga_logs_harga_id` FOREIGN KEY (`harga_id`) REFERENCES `harga_sampah` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_harga_logs_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default feature toggles
INSERT INTO `feature_toggles` (`feature_key`, `feature_name`, `description`, `is_enabled`, `role_config`) VALUES
('waste_management', 'Manajemen Sampah', 'Fitur untuk mengelola data sampah', 1, '{"admin_pusat": true, "super_admin": true, "user": true, "pengelola_tps": true}'),
('harga_management', 'Manajemen Harga', 'Fitur untuk mengelola harga sampah', 1, '{"admin_pusat": true, "super_admin": true, "user": false, "pengelola_tps": false}'),
('user_management', 'Manajemen User', 'Fitur untuk mengelola user', 1, '{"admin_pusat": true, "super_admin": true, "user": false, "pengelola_tps": false}'),
('dashboard_stats', 'Statistik Dashboard', 'Fitur statistik di dashboard', 1, '{"admin_pusat": true, "super_admin": true, "user": true, "pengelola_tps": true}'),
('export_data', 'Export Data', 'Fitur untuk export data', 1, '{"admin_pusat": true, "super_admin": true, "user": true, "pengelola_tps": true}'),
('review_system', 'Sistem Review', 'Fitur untuk review data sampah', 1, '{"admin_pusat": true, "super_admin": true, "user": false, "pengelola_tps": false}'),
('reporting', 'Laporan', 'Fitur laporan dan analisis', 1, '{"admin_pusat": true, "super_admin": true, "user": false, "pengelola_tps": false}');

-- Update tabel waste jika belum ada kolom status
ALTER TABLE `waste` 
ADD COLUMN IF NOT EXISTS `status` enum('pending','approved','rejected') DEFAULT 'pending' AFTER `keterangan`,
ADD COLUMN IF NOT EXISTS `reviewed_by` int(11) NULL AFTER `status`,
ADD COLUMN IF NOT EXISTS `reviewed_at` timestamp NULL AFTER `reviewed_by`,
ADD COLUMN IF NOT EXISTS `review_notes` text NULL AFTER `reviewed_at`;

-- Add foreign key untuk reviewed_by jika belum ada
ALTER TABLE `waste` 
ADD CONSTRAINT `fk_waste_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;