# Ringkasan Perubahan Password Plain Text

## File yang Sudah Diubah

### 1. app/Controllers/Auth.php
- **Fungsi login()**: Password dibandingkan langsung tanpa `password_verify()`
  ```php
  if ($user && $user['password'] === $password) // Direct comparison
  ```

### 2. app/Controllers/Admin/UserManagement.php
- **Fungsi create()**: Password disimpan plain text tanpa `password_hash()`
  ```php
  'password' => $password, // Plain text password, no hash
  ```

- **Fungsi update()**: Password update tanpa hash
  ```php
  $data['password'] = $password; // Plain text password, no hash
  ```

- **Fungsi resetPassword()**: Reset password tanpa hash
  ```php
  'password' => $defaultPassword // Plain text password, no hash
  ```

## Cara Penggunaan

### Login
- Username dan password dibandingkan langsung dengan database
- Tidak ada proses hashing/verifikasi

### Tambah User Baru
- Admin input password → disimpan langsung ke database
- Password yang ditampilkan = password yang disimpan

### Edit User
- Jika password diisi → update dengan plain text
- Jika password kosong → password lama tetap

### Reset Password
- Password direset ke "password123" (plain text)

## File SQL untuk Update Database

File: `UPDATE_PASSWORDS_TO_PLAIN.sql`

Jalankan di phpMyAdmin untuk mengubah password yang sudah di-hash:

```sql
UPDATE users SET password = 'superadmin123' WHERE username = 'superadmin';
UPDATE users SET password = 'admin12345678' WHERE username = 'admin';
UPDATE users SET password = 'user12345' WHERE username = 'user1';
-- dst...
```

## Akun Default

| Username | Password | Role |
|----------|----------|------|
| superadmin | superadmin123 | Super Admin |
| admin | admin12345678 | Admin Pusat |
| user1 | user12345 | User Unit |
| tps1 | tps12345 | Pengelola TPS |

## Catatan Keamanan

⚠️ **PERINGATAN**: Password plain text tidak aman untuk production!
- Gunakan hanya untuk development/testing
- Untuk production, sebaiknya gunakan password hashing (bcrypt/argon2)
