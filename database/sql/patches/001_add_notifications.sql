-- Tabel untuk sistem notifikasi (FIXED VERSION)
-- Mengatasi masalah foreign key compatibility

-- Drop tabel jika sudah ada
DROP TABLE IF EXISTS `notifications`;

-- Buat tabel notifications dengan tipe data yang kompatibel
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

-- Insert sample notification untuk testing
INSERT INTO notifications (user_id, title, message, type) 
VALUES (1, 'Sistem Notifikasi Aktif', 'Sistem notifikasi telah berhasil diaktifkan.', 'success');

-- Tampilkan hasil
SELECT 'Notifications table created successfully!' as result;
SELECT COUNT(*) as total_notifications FROM notifications;
INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`, `data`, `is_read`) 
SELECT 
    u.id,
    'Selamat Datang di Sistem Notifikasi',
    'Sistem notifikasi telah aktif. Anda akan menerima pemberitahuan untuk data baru yang perlu direview.',
    'info',
    JSON_OBJECT('action_url', '/admin-pusat/dashboard'),
    0
FROM `users` u 
WHERE u.role = 'admin_pusat';