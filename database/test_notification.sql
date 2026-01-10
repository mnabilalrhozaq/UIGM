-- =====================================================
-- TEST NOTIFICATION SYSTEM
-- =====================================================

-- 1. Check apakah tabel notifications ada
SELECT 'Checking notifications table...' as info;
DESCRIBE notifications;

-- 2. Check apakah ada admin_pusat users
SELECT 'Admin Pusat Users:' as info;
SELECT id, username, nama_lengkap, role FROM users WHERE role = 'admin_pusat';

-- 3. Check existing notifications
SELECT 'Existing Notifications:' as info;
SELECT 
    n.id,
    n.user_id,
    n.title,
    n.message,
    n.type,
    n.is_read,
    u.nama_lengkap as recipient,
    n.created_at
FROM notifications n
LEFT JOIN users u ON u.id = n.user_id
ORDER BY n.created_at DESC;

-- 4. Manual insert test notification
INSERT INTO notifications (user_id, title, message, type) 
SELECT u.id, 'Test Notification', 'This is a test notification for waste submission.', 'info'
FROM users u 
WHERE u.role = 'admin_pusat'
LIMIT 1;

-- 5. Check hasil
SELECT 'After Test Insert:' as info;
SELECT COUNT(*) as total_notifications FROM notifications;
SELECT COUNT(*) as unread_notifications FROM notifications WHERE is_read = 0;