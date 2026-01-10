# 📋 Panduan Import Database - POLBAN UI GreenMetric

## 🎯 Urutan Import yang BENAR

### 1️⃣ **Database Utama (WAJIB)**
```sql
-- Import database utama terlebih dahulu
SOURCE database/sql/exports/database_export.sql;
```

### 2️⃣ **Patches Database (URUTAN PENTING!)**
Import patches sesuai urutan nomor:

```sql
-- 1. Tambah tabel notifications
SOURCE database/sql/patches/001_add_notifications.sql;

-- 2. Perbaiki field nilai_input
SOURCE database/sql/patches/002_fix_nilai_input.sql;

-- 3. Tambah kolom warna
SOURCE database/sql/patches/003_add_warna.sql;

-- 4. Tambah tabel user
SOURCE database/sql/patches/004_user_tables.sql;
```

## 🚀 Cara Import (Step by Step)

### Method 1: Via phpMyAdmin
1. Buka phpMyAdmin
2. Pilih database project Anda
3. Klik tab "Import"
4. Upload file SQL sesuai urutan di atas
5. Klik "Go" untuk setiap file

### Method 2: Via MySQL Command Line
```bash
# Masuk ke MySQL
mysql -u username -p

# Pilih database
USE nama_database_anda;

# Import sesuai urutan
SOURCE database/sql/exports/database_export.sql;
SOURCE database/sql/patches/001_add_notifications.sql;
SOURCE database/sql/patches/002_fix_nilai_input.sql;
SOURCE database/sql/patches/003_add_warna.sql;
SOURCE database/sql/patches/004_user_tables.sql;
```

### Method 3: Via Laragon Terminal
```bash
# Buka Laragon Terminal
# Navigate ke project folder
cd F:\laragon\www\eksperimen

# Import database
mysql -u root -p nama_database < database/sql/exports/database_export.sql
mysql -u root -p nama_database < database/sql/patches/001_add_notifications.sql
mysql -u root -p nama_database < database/sql/patches/002_fix_nilai_input.sql
mysql -u root -p nama_database < database/sql/patches/003_add_warna.sql
mysql -u root -p nama_database < database/sql/patches/004_user_tables.sql
```

## ✅ Verifikasi Import

Setelah import, jalankan query ini untuk memastikan semua tabel ada:

```sql
-- Check semua tabel
SHOW TABLES;

-- Verifikasi tabel utama
DESCRIBE users;
DESCRIBE unit;
DESCRIBE penilaian_unit;
DESCRIBE waste_management;
DESCRIBE notifications;

-- Check data sample
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_units FROM unit;
SELECT COUNT(*) as total_notifications FROM notifications;
```

## 🔍 Troubleshooting

### Error: Table already exists
```sql
-- Jika ada error "table already exists", drop dulu
DROP TABLE IF EXISTS notifications;
-- Lalu import ulang patch yang error
```

### Error: Column doesn't exist
```sql
-- Check struktur tabel
DESCRIBE nama_tabel;
-- Bandingkan dengan yang seharusnya ada di patch
```

### Error: Foreign key constraint
```sql
-- Disable foreign key check sementara
SET FOREIGN_KEY_CHECKS = 0;
-- Import file
-- Enable kembali
SET FOREIGN_KEY_CHECKS = 1;
```

## 📊 Expected Tables After Import

Setelah import berhasil, Anda harus memiliki tabel-tabel ini:

### Core Tables
- ✅ `users` - User accounts
- ✅ `unit` - Unit/Department data
- ✅ `tahun_penilaian` - Assessment years

### UIGM Tables  
- ✅ `penilaian_unit` - UIGM assessments
- ✅ `indikator` - UIGM indicators (jika ada)

### Waste Management Tables
- ✅ `waste_management` - Waste data

### System Tables
- ✅ `notifications` - Notification system
- ✅ `riwayat_versi` - Version history (jika ada)

## 🎯 Post-Import Tasks

### 1. Update Environment
Pastikan file `.env` sudah benar:
```env
database.default.hostname = localhost
database.default.database = nama_database_anda
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
```

### 2. Test Database Connection
```php
// Test via CI4 spark
php spark db:table users
```

### 3. Create Default Admin User
```sql
-- Insert admin user untuk testing
INSERT INTO users (username, email, password, nama_lengkap, role, status_aktif) 
VALUES ('admin', 'admin@polban.ac.id', '$2y$10$hash_password_here', 'Administrator', 'admin_pusat', 1);
```

### 4. Test Login
- Buka aplikasi
- Coba login dengan user admin
- Check dashboard admin pusat

## ⚠️ PENTING!

### Backup Database
```sql
-- Backup sebelum import
mysqldump -u root -p nama_database > backup_before_import.sql
```

### Urutan Import HARUS Diikuti
1. **database_export.sql** - Database utama
2. **001_add_notifications.sql** - Tabel notifications
3. **002_fix_nilai_input.sql** - Fix field nilai_input
4. **003_add_warna.sql** - Tambah kolom warna
5. **004_user_tables.sql** - Update tabel user

### Jangan Skip Patch
Setiap patch memiliki dependency, jangan skip atau ubah urutan!

## 🆘 Need Help?

Jika ada masalah saat import:
1. Check error message di MySQL
2. Pastikan urutan import benar
3. Backup dan coba import ulang
4. Check file `.env` database config
5. Pastikan MySQL service running

---

**💡 Tip:** Selalu backup database sebelum import dan test di environment development dulu!