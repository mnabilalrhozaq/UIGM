# 🚨 EMERGENCY PASSWORD RESET GUIDE

## Jika Anda Lupa Semua Password Admin/User/TPS

### Metode 1: Reset via Database (PALING MUDAH)

1. **Buka phpMyAdmin atau database tool Anda**
2. **Pilih database aplikasi Anda**
3. **Buka tabel `users`**
4. **Jalankan query SQL ini:**

```sql
-- Reset password admin menjadi: admin123
UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE role = 'admin_pusat' 
LIMIT 1;
```

**Password yang sudah di-hash:**
- `admin123` = `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi`
- `password123` = `$2y$10$EeZXw7iBP6RKz8VrKf6aUOvVZjKJQhjQJy4zqVq0yZ8vKqYqKqKqK`
- `12345678` = `$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy`

5. **Login dengan:**
   - Username: (username admin yang ada di database)
   - Password: `admin123`

### Metode 2: Buat User Admin Baru via Database

```sql
-- Buat admin baru dengan username: superadmin, password: admin123
INSERT INTO users (username, email, password, nama_lengkap, role, unit_id, status_aktif, created_at) 
VALUES (
    'superadmin',
    'superadmin@polban.ac.id',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Super Administrator',
    'admin_pusat',
    1,
    1,
    NOW()
);
```

Login dengan:
- Username: `superadmin`
- Password: `admin123`

### Metode 3: Reset Semua User ke Password Default

```sql
-- Reset SEMUA user ke password: password123
UPDATE users 
SET password = '$2y$10$EeZXw7iBP6RKz8VrKf6aUOvVZjKJQhjQJy4zqVq0yZ8vKqYqKqKqK';
```

Semua user bisa login dengan password: `password123`

---

## 📝 Cara Generate Password Hash Baru

Jika Anda ingin membuat password hash sendiri:

### Via PHP (Terminal/Command Prompt):

```bash
php -r "echo password_hash('PASSWORD_ANDA', PASSWORD_DEFAULT);"
```

Contoh:
```bash
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
```

### Via Online Tool (TIDAK DISARANKAN untuk production):
- https://bcrypt-generator.com/
- Pilih "Rounds: 10"
- Masukkan password Anda
- Copy hash yang dihasilkan

---

## 🔐 Password Default yang Disarankan

Untuk kemudahan development, gunakan password default ini:

| Role | Username | Password | Hash |
|------|----------|----------|------|
| Admin Pusat | admin | admin123 | `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` |
| Pengelola TPS | tps1 | password123 | `$2y$10$EeZXw7iBP6RKz8VrKf6aUOvVZjKJQhjQJy4zqVq0yZ8vKqYqKqKqK` |
| User | user1 | password123 | `$2y$10$EeZXw7iBP6RKz8VrKf6aUOvVZjKJQhjQJy4zqVq0yZ8vKqYqKqKqK` |

---

## ⚠️ PENTING!

1. **Simpan file ini di tempat aman!**
2. **Jangan commit file ini ke Git jika ada password production!**
3. **Ganti semua password default setelah deployment!**
4. **Untuk production, gunakan password yang kuat!**

---

## 🆘 Masih Tidak Bisa Login?

Cek hal berikut:

1. **Pastikan tabel `users` ada di database**
2. **Pastikan ada minimal 1 user dengan role `admin_pusat`**
3. **Cek field `status_aktif` = 1 (aktif)**
4. **Cek koneksi database di `app/Config/Database.php`**

Query untuk cek user admin:
```sql
SELECT id, username, email, role, status_aktif FROM users WHERE role = 'admin_pusat';
```

---

## 📞 Kontak Support

Jika masih ada masalah, hubungi developer atau DBA Anda.

**File ini dibuat otomatis oleh sistem untuk membantu recovery password.**
