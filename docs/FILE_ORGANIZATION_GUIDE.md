# 📁 PANDUAN ORGANISASI FILE SISTEM UIGM

## 🎯 Tujuan Organisasi
Mengorganisir file-file di root directory agar lebih rapi, mudah dikelola, dan mengikuti best practices pengembangan aplikasi.

## 📋 Struktur Direktori Setelah Organisasi

```
📁 Root Directory (Hanya Essential Files)
├── 📄 .env                    # Environment configuration
├── 📄 .gitignore             # Git ignore rules
├── 📄 composer.json          # PHP dependencies
├── 📄 composer.lock          # Locked dependencies
├── 📄 LICENSE                # License file
├── 📄 phpunit.xml.dist       # PHPUnit configuration
├── 📄 preload.php            # PHP preload script
├── 📄 README.md              # Project documentation
├── 📄 spark                  # CodeIgniter CLI tool
│
├── 📁 app/                   # CodeIgniter MVC Structure
│   ├── 📁 Controllers/       # Application controllers
│   ├── 📁 Models/           # Data models
│   ├── 📁 Views/            # View templates
│   ├── 📁 Config/           # Configuration files
│   ├── 📁 Database/         # Migrations & seeds
│   └── 📁 Filters/          # Request filters
│
├── 📁 public/               # Web accessible files
│   ├── 📄 index.php         # Entry point
│   └── 📁 assets/           # CSS, JS, images
│
├── 📁 scripts/              # Utility Scripts
│   ├── 📁 database/         # Database management scripts
│   ├── 📁 testing/          # Testing & debugging scripts
│   ├── 📁 maintenance/      # System maintenance scripts
│   └── 📁 setup/            # Setup & installation scripts
│
├── 📁 backup/               # Backup Files
│   └── 📁 sql/              # SQL backup & patch files
│
├── 📁 docs/                 # Documentation
│   └── 📁 summaries/        # Development summaries & guides
│
└── 📁 temp/                 # Temporary Files
    ├── 📁 html_tests/       # HTML test files
    └── 📁 misc/             # Miscellaneous temporary files
```

## 🗂️ Kategorisasi File

### 📁 scripts/database/ (7 files)
Script untuk manajemen database dan struktur data:

- `check_jenis_sampah.php` - Cek dan setup tabel jenis_sampah
- `fix_database_final.php` - Perbaikan database final
- `fix_pengiriman_structure.php` - Perbaikan struktur pengiriman_unit
- `manual_fix_draft_status.php` - Perbaikan status draft manual
- `mark_migrations_complete.php` - Menandai migration sebagai selesai
- `setup_jenis_sampah.php` - Setup data jenis sampah
- `test_database_connection.php` - Test koneksi database

**Cara Penggunaan:**
```bash
php scripts/database/check_jenis_sampah.php
php scripts/database/fix_pengiriman_structure.php
```

### 📁 scripts/testing/ (13 files)
Script untuk testing dan debugging sistem:

- `debug_notifications.php` - Debug sistem notifikasi
- `debug_pengiriman.php` - Debug sistem pengiriman
- `quick_test.php` - Quick test sistem
- `test_api_endpoints.php` - Test API endpoints
- `test_authentication.php` - Test sistem authentication
- `test_dashboard_fix.php` - Test perbaikan dashboard
- `test_data_input_fix.php` - Test perbaikan input data
- `test_datacaster_fix.php` - Test perbaikan datacaster
- `test_json_fix.php` - Test perbaikan JSON
- `test_new_fields_integration.php` - Test integrasi field baru
- `test_notifications.php` - Test sistem notifikasi
- `test_sidebar_functionality.php` - Test fungsi sidebar
- `test_system_endpoints.php` - Test semua endpoints sistem

**Cara Penggunaan:**
```bash
php scripts/testing/test_authentication.php
php scripts/testing/test_system_endpoints.php
```

### 📁 scripts/maintenance/ (3 files)
Script untuk maintenance dan monitoring sistem:

- `clear_cache.php` - Bersihkan cache sistem
- `start_and_test.php` - Start server dan test sistem
- `system_verification.php` - Verifikasi sistem lengkap

**Cara Penggunaan:**
```bash
php scripts/maintenance/system_verification.php
php scripts/maintenance/start_and_test.php
```

### 📁 backup/sql/ (9 files)
File backup database dan patch SQL:

- `database_backup_clean_fixed.sql` - Backup database clean fixed
- `database_backup_uigm_polban.sql` - Backup database UIGM Polban
- `database_patch_add_missing_columns.sql` - Patch untuk kolom yang hilang
- `database_patch_simple.sql` - Patch database sederhana
- `fix_database_simple.sql` - Fix database sederhana
- `fix_null_data_input.sql` - Fix data input null
- `fix_pengiriman_unit_structure.sql` - Fix struktur pengiriman unit
- `MANUAL_FIX_QUERY.sql` - Query manual fix
- `create_jenis_sampah_table.sql` - Create tabel jenis sampah

### 📁 docs/summaries/ (14 files)
Dokumentasi pengembangan dan summary:

- `DASHBOARD_ADMIN_PUSAT_REDESIGN_SUMMARY.md`
- `DROPDOWN_BERTINGKAT_JENIS_SAMPAH_SUMMARY.md`
- `DROPDOWN_FIX_SUMMARY.md`
- `FINAL_FIX_SUMMARY_JAVASCRIPT_CONFLICT.md`
- `FINAL_FIX_SUMMARY.md`
- `FINAL_SYSTEM_SUMMARY.md`
- `FIX_TIPE_UNIT_ERROR.md`
- `FITUR_PERHITUNGAN_NOMINAL_UANG_TPS.md`
- `PANDUAN_INSTALASI_BACKUP.md`
- `PERBAIKAN_FINAL_SAVE_ISSUE.md`
- `SAVE_ISSUE_FIX_SUMMARY.md`
- `SISTEM_FORM_PETUGAS_GEDUNG_TPS.md`
- `WASTE_MANAGEMENT_TPS_USER_FIELDS_SUMMARY.md`
- `WASTE_MANAGEMENT_UPDATE_SUMMARY.md`

### 📁 temp/html_tests/ (21 files)
File HTML untuk testing dan debugging:

- File-file test HTML untuk berbagai komponen sistem
- File debug untuk troubleshooting
- Template test untuk form dan dropdown

### 📁 temp/misc/ (2 files)
File miscellaneous dan temporary:

- `builds` - File builds
- `env_backup.txt` - Backup file environment

## 🚀 Cara Penggunaan Script

### 1. Database Management
```bash
# Verifikasi struktur database
php scripts/database/check_jenis_sampah.php

# Perbaiki struktur pengiriman_unit
php scripts/database/fix_pengiriman_structure.php

# Test koneksi database
php scripts/database/test_database_connection.php
```

### 2. System Testing
```bash
# Test authentication sistem
php scripts/testing/test_authentication.php

# Test semua endpoints
php scripts/testing/test_system_endpoints.php

# Debug notifikasi
php scripts/testing/debug_notifications.php
```

### 3. System Maintenance
```bash
# Verifikasi sistem lengkap
php scripts/maintenance/system_verification.php

# Start server dan test
php scripts/maintenance/start_and_test.php

# Clear cache
php scripts/maintenance/clear_cache.php
```

## 📊 Statistik Organisasi

- **Total file diorganisir:** 69 files
- **File essential di root:** 9 files
- **Direktori baru dibuat:** 7 directories
- **Kategori utama:** 6 categories

## ✅ Keuntungan Organisasi

1. **Root Directory Bersih** - Hanya file essential yang tersisa
2. **Kategorisasi Jelas** - File dikelompokkan berdasarkan fungsi
3. **Mudah Dicari** - File tersusun dalam struktur yang logis
4. **Maintenance Mudah** - Script terorganisir berdasarkan tujuan
5. **Best Practices** - Mengikuti standar pengembangan aplikasi
6. **Skalabilitas** - Mudah menambah file baru ke kategori yang tepat

## 🔧 Maintenance

### Menambah Script Baru
```bash
# Database script
cp new_script.php scripts/database/

# Testing script  
cp test_script.php scripts/testing/

# Maintenance script
cp maintenance_script.php scripts/maintenance/
```

### Backup Berkala
```bash
# Backup semua script
tar -czf scripts_backup_$(date +%Y%m%d).tar.gz scripts/

# Backup dokumentasi
tar -czf docs_backup_$(date +%Y%m%d).tar.gz docs/
```

## 📝 Catatan Penting

1. **File Essential** - Jangan pindahkan file di root yang essential untuk CodeIgniter
2. **Path Relatif** - Semua script masih bisa dijalankan dengan path relatif dari root
3. **Backup** - File backup SQL tersimpan aman di `backup/sql/`
4. **Dokumentasi** - Semua summary pengembangan ada di `docs/summaries/`
5. **Temporary Files** - File sementara ada di `temp/` dan bisa dihapus jika perlu

---

**Dibuat:** 2026-01-02  
**Versi:** 1.0  
**Status:** ✅ Lengkap dan Terorganisir