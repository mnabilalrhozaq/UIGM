-- Script untuk mengubah semua password yang sudah di-hash kembali ke plain text
-- Jalankan di phpMyAdmin atau MySQL client

-- Update password untuk akun yang terlihat di screenshot
UPDATE users SET password = 'habib12345' WHERE username = 'habibtino';
UPDATE users SET password = 'admin12345678' WHERE username = 'admin';
UPDATE users SET password = 'superadmin123' WHERE username = 'superadmin';
UPDATE users SET password = 'tps12345' WHERE username = 'pengelolatps';
UPDATE users SET password = 'user12345' WHERE username = 'userjti';
UPDATE users SET password = 'user12345' WHERE username = 'Nabila';

-- Jika ada user lain, tambahkan di sini
-- UPDATE users SET password = 'password_baru' WHERE username = 'username_target';

-- Tampilkan hasil
SELECT username, password, role FROM users ORDER BY username;
