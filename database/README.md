# 🗄️ Database Setup - POLBAN UI GreenMetric

## 🚀 Quick Start (Recommended)

### Option 1: Import Semua Sekaligus
```sql
-- 1. Import database utama dulu
SOURCE database/sql/exports/database_export.sql;

-- 2. Import semua patches sekaligus
SOURCE database/quick_import.sql;
```

### Option 2: Import Step by Step
```sql
-- 1. Database utama
SOURCE database/sql/exports/database_export.sql;

-- 2. Patches (URUTAN PENTING!)
SOURCE database/sql/patches/001_add_notifications.sql;
SOURCE database/sql/patches/002_fix_nilai_input.sql;
SOURCE database/sql/patches/003_add_warna.sql;
SOURCE database/sql/patches/004_user_tables.sql;
```

## 🔑 Default Login Accounts

Setelah import, gunakan akun ini untuk testing:

### Admin Pusat
- **Username:** `admin`
- **Password:** `admin123`
- **Role:** Admin Pusat

### User Test
- **Username:** `user1`  
- **Password:** `user123`
- **Role:** User (Unit JTI)

## ✅ Verifikasi

Setelah import, check:
```sql
SHOW TABLES;
SELECT COUNT(*) FROM users;
SELECT COUNT(*) FROM notifications;
```

## 📁 File Structure

```
database/
├── sql/
│   ├── exports/
│   │   └── database_export.sql     # Database utama
│   └── patches/
│       ├── 001_add_notifications.sql
│       ├── 002_fix_nilai_input.sql
│       ├── 003_add_warna.sql
│       └── 004_user_tables.sql
├── quick_import.sql                # Import semua patches
├── IMPORT_GUIDE.md                 # Panduan lengkap
└── README.md                       # File ini
```

## 🆘 Troubleshooting

### Error saat import?
1. Pastikan MySQL running
2. Check database name di `.env`
3. Import database utama dulu
4. Gunakan `quick_import.sql` untuk patches

### Tidak bisa login?
1. Check tabel `users` ada data
2. Pastikan password di-hash dengan benar
3. Coba reset password via database

---

**💡 Tip:** Gunakan `quick_import.sql` untuk setup cepat!