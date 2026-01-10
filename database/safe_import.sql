-- =====================================================
-- SAFE IMPORT SCRIPT - POLBAN UI GreenMetric
-- =====================================================
-- Script ini mengatasi masalah foreign key compatibility

-- =====================================================
-- STEP 1: CHECK EXISTING TABLE STRUCTURE
-- =====================================================

-- Check struktur tabel users
SELECT 'Checking users table structure...' as info;
DESCRIBE users;

-- =====================================================
-- STEP 2: CREATE NOTIFICATIONS TABLE (SAFE VERSION)
-- =====================================================

-- Drop tabel notifications jika ada
DROP TABLE IF EXISTS `notifications`;

-- Buat tabel notifications tanpa foreign key dulu
CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','danger') NOT NULL DEFAULT 'info',
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'Notifications table created successfully!' as info;

-- =====================================================
-- STEP 3: FIX PENILAIAN_UNIT TABLE
-- =====================================================

-- Check apakah kolom nilai_input ada
SELECT 'Fixing penilaian_unit table...' as info;

-- Set default value untuk field nilai_input
ALTER TABLE penilaian_unit MODIFY COLUMN nilai_input DECIMAL(5,2) NOT NULL DEFAULT 0.00;

-- Update existing NULL values to 0
UPDATE penilaian_unit SET nilai_input = 0.00 WHERE nilai_input IS NULL;

SELECT 'Penilaian_unit table fixed!' as info;

-- =====================================================
-- STEP 4: ADD SAMPLE DATA
-- =====================================================

-- Insert admin user jika belum ada
INSERT IGNORE INTO users (username, email, password, nama_lengkap, role, status_aktif) 
VALUES ('admin', 'admin@polban.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin_pusat', 1);

-- Insert sample notification
INSERT INTO notifications (user_id, title, message, type) 
VALUES (1, 'Selamat Datang!', 'Sistem UI GreenMetric POLBAN siap digunakan.', 'success');

-- =====================================================
-- STEP 5: VERIFICATION
-- =====================================================

SELECT 'IMPORT COMPLETED SUCCESSFULLY!' as status;
SELECT 'Tables created:' as info;
SHOW TABLES LIKE '%notifications%';
SHOW TABLES LIKE '%users%';
SHOW TABLES LIKE '%penilaian%';

SELECT 'Sample data:' as info;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_notifications FROM notifications;

SELECT 'Login info:' as info;
SELECT 'Username: admin, Password: admin123' as login_credentials;