# 🔧 Troubleshooting Database Import

## ❌ Error: Foreign Key Constraint Incompatible

### Masalah
```
#3780 - Referencing column 'user_id' and referenced column 'id' in foreign key constraint 'fk_notifications_user' are incompatible.
```

### Penyebab
Tipe data kolom `user_id` di tabel `notifications` tidak cocok dengan tipe data kolom `id` di tabel `users`.

### ✅ Solusi

#### Option 1: Gunakan Safe Import (RECOMMENDED)
```sql
-- Import dengan script yang sudah diperbaiki
SOURCE database/safe_import.sql;
```

#### Option 2: Manual Fix
```sql
-- 1. Check struktur tabel users
DESCRIBE users;

-- 2. Drop tabel notifications jika ada
DROP TABLE IF EXISTS notifications;

-- 3. Buat tabel notifications tanpa foreign key
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
```

## ❌ Error: Table Already Exists

### Solusi
```sql
-- Drop tabel yang sudah ada
DROP TABLE IF EXISTS notifications;
-- Lalu import ulang
```

## ❌ Error: Column Doesn't Exist

### Solusi
```sql
-- Check struktur tabel
DESCRIBE nama_tabel;
-- Bandingkan dengan yang seharusnya
```

## ❌ Error: Constraint Already Exists

### Solusi
```sql
-- Drop constraint yang sudah ada
ALTER TABLE penilaian_unit DROP CONSTRAINT chk_nilai_input_range;
-- Lalu tambah ulang
ALTER TABLE penilaian_unit ADD CONSTRAINT chk_nilai_input_range 
CHECK (nilai_input >= 0 AND nilai_input <= 100);
```

## 🚀 Recommended Import Steps

### Step 1: Import Database Utama
```sql
SOURCE database/sql/exports/database_export.sql;
```

### Step 2: Import dengan Safe Script
```sql
SOURCE database/safe_import.sql;
```

### Step 3: Verifikasi
```sql
SHOW TABLES;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM notifications;
```

## 🔍 Debug Commands

### Check Table Structure
```sql
DESCRIBE users;
DESCRIBE notifications;
DESCRIBE penilaian_unit;
```

### Check Foreign Keys
```sql
SELECT 
  TABLE_NAME,
  COLUMN_NAME,
  CONSTRAINT_NAME,
  REFERENCED_TABLE_NAME,
  REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = 'your_database_name';
```

### Check Data Types
```sql
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'users' 
AND COLUMN_NAME = 'id';
```

## 💡 Tips

1. **Selalu backup** sebelum import
2. **Check struktur tabel** sebelum buat foreign key
3. **Gunakan safe_import.sql** untuk menghindari masalah
4. **Import step by step** jika ada masalah
5. **Disable foreign key checks** sementara jika perlu

## 📞 Still Having Issues?

Jika masih ada masalah:
1. Gunakan `database/safe_import.sql`
2. Check error message di MySQL
3. Pastikan database utama sudah di-import
4. Contact support dengan error message lengkap