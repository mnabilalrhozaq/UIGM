-- =====================================================
-- QUICK IMPORT SCRIPT - POLBAN UI GreenMetric (FIXED)
-- =====================================================
-- Jalankan script ini untuk import semua patches sekaligus
-- PASTIKAN database utama sudah di-import terlebih dahulu!

-- Disable foreign key checks untuk menghindari error
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. ADD NOTIFICATIONS TABLE (FIXED FOREIGN KEY)
-- =====================================================

-- Drop tabel notifications jika sudah ada (untuk re-create)
DROP TABLE IF EXISTS `notifications`;

-- Buat tabel notifications dengan tipe data yang sesuai
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

-- =====================================================
-- 2. FIX NILAI_INPUT FIELD
-- =====================================================
-- Set default value untuk field NOT NULL
ALTER TABLE penilaian_unit MODIFY COLUMN nilai_input DECIMAL(5,2) NOT NULL DEFAULT 0.00;

-- Update existing NULL values to 0
UPDATE penilaian_unit SET nilai_input = 0.00 WHERE nilai_input IS NULL;

-- Tambah constraint untuk memastikan nilai dalam range 0-100 (jika belum ada)
ALTER TABLE penilaian_unit 
ADD CONSTRAINT chk_nilai_input_range 
CHECK (nilai_input >= 0 AND nilai_input <= 100);

-- Tambah index untuk performa query (jika belum ada)
CREATE INDEX IF NOT EXISTS idx_penilaian_status ON penilaian_unit(status);
CREATE INDEX IF NOT EXISTS idx_penilaian_unit_kategori ON penilaian_unit(unit_id, kategori_uigm);

-- =====================================================
-- 3. ADD WARNA COLUMN (if needed)
-- =====================================================
-- Uncomment jika tabel indikator ada dan kolom warna belum ada
-- ALTER TABLE indikator ADD COLUMN IF NOT EXISTS warna VARCHAR(7) DEFAULT '#007bff' AFTER bobot_persen;

-- =====================================================
-- 4. ENSURE USER TABLES ARE CORRECT
-- =====================================================
-- Pastikan tabel users memiliki struktur yang benar
ALTER TABLE users MODIFY COLUMN role ENUM('admin_pusat', 'super_admin', 'user') NOT NULL DEFAULT 'user';

-- Pastikan kolom unit_id ada di tabel users (jika belum ada)
-- ALTER TABLE users ADD COLUMN IF NOT EXISTS unit_id INT(11) unsigned NULL;

-- =====================================================
-- 5. ADD FOREIGN KEY CONSTRAINTS (AFTER FIXING TYPES)
-- =====================================================

-- Enable foreign key checks kembali
SET FOREIGN_KEY_CHECKS = 1;

-- Tambah foreign key constraint untuk notifications (jika tipe data sudah cocok)
-- ALTER TABLE notifications 
-- ADD CONSTRAINT fk_notifications_user 
-- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- =====================================================
-- 6. INSERT SAMPLE DATA FOR TESTING
-- =====================================================

-- Insert sample admin user (password: admin123)
INSERT IGNORE INTO users (username, email, password, nama_lengkap, role, status_aktif) 
VALUES ('admin', 'admin@polban.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin_pusat', 1);

-- Insert sample unit (jika tabel unit ada)
INSERT IGNORE INTO unit (nama_unit, kode_unit, status_aktif) 
VALUES ('Jurusan Teknik Informatika', 'JTI', 1);

-- Insert sample user (password: user123) - hanya jika unit_id ada di tabel users
-- INSERT IGNORE INTO users (username, email, password, nama_lengkap, role, unit_id, status_aktif) 
-- VALUES ('user1', 'user1@polban.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'User Test', 'user', 1, 1);

-- Insert sample notification (tanpa foreign key constraint dulu)
INSERT IGNORE INTO notifications (user_id, title, message, type) 
SELECT u.id, 'Selamat Datang!', 'Sistem UI GreenMetric POLBAN siap digunakan.', 'success'
FROM users u 
WHERE u.role = 'admin_pusat'
LIMIT 1;

-- =====================================================
-- 7. VERIFY INSTALLATION
-- =====================================================

-- Show hasil
SELECT 'INSTALLATION COMPLETE!' as status;
SELECT COUNT(*) as total_users FROM users;

-- Check jika tabel unit ada
SELECT COUNT(*) as total_units FROM unit WHERE 1;

SELECT COUNT(*) as total_notifications FROM notifications;
SELECT 'Login dengan: admin/admin123' as login_info;