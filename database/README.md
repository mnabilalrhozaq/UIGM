# 📊 Database Documentation

Dokumentasi struktur database untuk UIGM POLBAN System.

## 🗄️ Database Information

- **Database Name**: `eksperimen`
- **Engine**: MySQL 8.0+
- **Charset**: utf8mb4
- **Collation**: utf8mb4_general_ci

## 📋 Tables Overview

### 1. users
Tabel untuk menyimpan data pengguna sistem.

**Columns:**
- `id` (INT, PK, AUTO_INCREMENT)
- `username` (VARCHAR 50, UNIQUE)
- `password` (VARCHAR 255) - Hashed
- `email` (VARCHAR 100)
- `nama_lengkap` (VARCHAR 100)
- `role` (ENUM: 'admin_pusat', 'pengelola_tps', 'user')
- `unit_id` (INT, FK → units.id)
- `status_aktif` (TINYINT, DEFAULT 1)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`username`)
- INDEX (`unit_id`)
- INDEX (`role`)

### 2. units
Tabel untuk menyimpan data unit/fakultas.

**Columns:**
- `id` (INT, PK, AUTO_INCREMENT)
- `kode_unit` (VARCHAR 20, UNIQUE)
- `nama_unit` (VARCHAR 100)
- `jenis_unit` (ENUM: 'fakultas', 'tps', 'lainnya')
- `alamat` (TEXT)
- `pic_nama` (VARCHAR 100)
- `pic_kontak` (VARCHAR 20)
- `status_aktif` (TINYINT, DEFAULT 1)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexes:**
- PRIMARY KEY (`id`)
- UNIQUE KEY (`kode_unit`)

### 3. master_harga_sampah
Tabel master untuk harga sampah per kategori.

**Columns:**
- `id` (INT, PK, AUTO_INCREMENT)
- `jenis_sampah` (VARCHAR 100) - Kategori utama
- `nama_jenis` (VARCHAR 100) - Nama detail
- `harga_per_satuan` (DECIMAL 10,2)
- `satuan` (VARCHAR 20) - kg, gram, pcs, dll
- `dapat_dijual` (TINYINT) - 1=bisa dijual, 0=tidak
- `status_aktif` (TINYINT, DEFAULT 1)
- `created_by` (INT, FK → users.id)
- `updated_by` (INT, FK → users.id)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`jenis_sampah`)
- INDEX (`status_aktif`)

### 4. waste_management
Tabel transaksi data sampah dari unit.

**Columns:**
- `id` (INT, PK, AUTO_INCREMENT)
- `unit_id` (INT, FK → units.id)
- `user_id` (INT, FK → users.id)
- `jenis_sampah` (VARCHAR 100)
- `kategori_sampah` (VARCHAR 50) - bisa_dijual/tidak_dijual
- `berat_kg` (DECIMAL 10,2)
- `jumlah` (DECIMAL 10,2)
- `satuan` (VARCHAR 20)
- `nilai_rupiah` (DECIMAL 12,2)
- `status` (ENUM: 'draft', 'dikirim', 'review', 'disetujui', 'perlu_revisi')
- `catatan_review` (TEXT)
- `reviewed_by` (INT, FK → users.id)
- `reviewed_at` (DATETIME)
- `created_at` (DATETIME)
- `updated_at` (DATETIME)

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`unit_id`)
- INDEX (`user_id`)
- INDEX (`status`)
- INDEX (`created_at`)

### 5. log_perubahan_harga
Tabel log untuk tracking perubahan harga sampah.

**Columns:**
- `id` (INT, PK, AUTO_INCREMENT)
- `harga_sampah_id` (INT, FK → master_harga_sampah.id)
- `jenis_sampah` (VARCHAR 100)
- `harga_lama` (DECIMAL 10,2)
- `harga_baru` (DECIMAL 10,2)
- `satuan` (VARCHAR 20)
- `alasan_perubahan` (TEXT)
- `changed_by` (INT, FK → users.id)
- `created_at` (DATETIME)

**Indexes:**
- PRIMARY KEY (`id`)
- INDEX (`harga_sampah_id`)
- INDEX (`created_at`)

## 🔗 Relationships

```
users
  ├─ 1:N → waste_management (user_id)
  ├─ 1:N → master_harga_sampah (created_by, updated_by)
  └─ 1:N → log_perubahan_harga (changed_by)

units
  ├─ 1:N → users (unit_id)
  └─ 1:N → waste_management (unit_id)

master_harga_sampah
  └─ 1:N → log_perubahan_harga (harga_sampah_id)
```

## 📥 Import Database

### Option 1: Via MySQL Command Line

```bash
mysql -u root -p eksperimen < eksperimen.sql
```

### Option 2: Via phpMyAdmin

1. Buka phpMyAdmin
2. Buat database baru: `eksperimen`
3. Import file `eksperimen.sql`

### Option 3: Via Laragon/XAMPP

1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Create database: `eksperimen`
3. Import SQL file

## 🔐 Default Users

Setelah import, tersedia akun default:

| Role | Username | Password | Unit |
|------|----------|----------|------|
| Admin Pusat | admin | admin123 | - |
| Pengelola TPS | tps1 | tps123 | TPS 1 |
| User | user1 | user123 | Teknik Informatika |

⚠️ **PENTING**: Ganti password setelah login pertama!

## 🔄 Migrations (Future)

Untuk development selanjutnya, gunakan CodeIgniter migrations:

```bash
php spark migrate
php spark db:seed UserSeeder
```

## 📊 Sample Data

Database sudah include sample data untuk:
- ✅ Master harga sampah (14 jenis)
- ✅ Units (fakultas dan TPS)
- ✅ Users (admin, tps, user)
- ✅ Sample waste data

## 🛠️ Maintenance

### Backup Database

```bash
# Full backup
mysqldump -u root -p eksperimen > backup_$(date +%Y%m%d).sql

# Structure only
mysqldump -u root -p --no-data eksperimen > schema.sql
```

### Optimize Tables

```sql
OPTIMIZE TABLE users, units, master_harga_sampah, waste_management, log_perubahan_harga;
```

### Check Table Status

```sql
SHOW TABLE STATUS FROM eksperimen;
```

## 📝 Notes

- Semua password di-hash menggunakan PHP `password_hash()`
- Timestamps menggunakan format MySQL DATETIME
- Foreign keys dengan ON DELETE CASCADE/SET NULL sesuai kebutuhan
- Indexes ditambahkan pada kolom yang sering di-query

## 🔍 Queries Umum

### Total Sampah per Unit

```sql
SELECT 
    u.nama_unit,
    COUNT(w.id) as total_data,
    SUM(w.berat_kg) as total_berat,
    SUM(w.nilai_rupiah) as total_nilai
FROM units u
LEFT JOIN waste_management w ON u.id = w.unit_id
WHERE w.status = 'disetujui'
GROUP BY u.id
ORDER BY total_berat DESC;
```

### Harga Sampah Aktif

```sql
SELECT 
    jenis_sampah,
    nama_jenis,
    harga_per_satuan,
    satuan,
    dapat_dijual
FROM master_harga_sampah
WHERE status_aktif = 1
ORDER BY jenis_sampah ASC;
```

### Log Perubahan Harga Terbaru

```sql
SELECT 
    l.*,
    u.nama_lengkap as changed_by_name
FROM log_perubahan_harga l
JOIN users u ON l.changed_by = u.id
ORDER BY l.created_at DESC
LIMIT 10;
```

---

**Last Updated**: 2026-01-23
