# 🚀 PANDUAN INSTALASI BACKUP DATABASE UIGM POLBAN

## 📋 Daftar Isi

1. [Persiapan XAMPP](#persiapan-xampp)
2. [Import Database](#import-database)
3. [Konfigurasi Environment](#konfigurasi-environment)
4. [Testing Login](#testing-login)
5. [Troubleshooting](#troubleshooting)

---

## 🔧 Persiapan XAMPP

### 1. Install XAMPP Baru (Jika Diperlukan)

- Download XAMPP terbaru dari: https://www.apachefriends.org/
- Install dengan komponen: Apache, MySQL, PHP, phpMyAdmin
- Pastikan port 80 (Apache) dan 3306 (MySQL) tidak bentrok

### 2. Start Services

```bash
# Buka XAMPP Control Panel
# Start Apache
# Start MySQL
```

### 3. Test XAMPP

- Buka browser: `http://localhost`
- Pastikan halaman XAMPP muncul
- Buka phpMyAdmin: `http://localhost/phpmyadmin`

---

## 💾 Import Database

### 1. Buka phpMyAdmin

- URL: `http://localhost/phpmyadmin`
- Login dengan user: `root`, password: (kosong)

### 2. Create Database

```sql
CREATE DATABASE uigm_polban CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Import File Backup

1. Pilih database `uigm_polban`
2. Klik tab **"Import"**
3. Klik **"Choose File"** dan pilih file `database_backup_uigm_polban.sql`
4. Pastikan format: **SQL**
5. Klik **"Go"** untuk import

### 4. Verifikasi Import

Pastikan tabel-tabel berikut berhasil dibuat:

- ✅ `users` (6 records)
- ✅ `unit` (5 records)
- ✅ `tahun_penilaian` (1 record)
- ✅ `indikator` (6 records)
- ✅ `pengiriman_unit` (5 records)
- ✅ `review_kategori` (30 records)
- ✅ `notifikasi` (5 records)
- ✅ `jenis_sampah` (9 records)
- ✅ `migrations` (9 records)
- ✅ `riwayat_versi` (0 records)

---

## ⚙️ Konfigurasi Environment

### 1. Copy File Environment

```bash
# Copy file env_backup.txt ke .env di root project
cp env_backup.txt .env
```

### 2. Edit File .env

Sesuaikan konfigurasi berikut:

```env
# APP
app.baseURL = 'http://localhost/eksperimen/'

# DATABASE
database.default.hostname = localhost
database.default.database = uigm_polban
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### 3. Set Permissions (Linux/Mac)

```bash
chmod 644 .env
```

---

## 🔐 Testing Login

### 1. Akses Website

- URL: `http://localhost/eksperimen/`
- Pastikan halaman login muncul

### 2. Login Credentials

#### Super Admin

- **Username:** `superadmin`
- **Password:** `superadmin123`
- **Akses:** Semua fitur sistem

#### Admin Pusat

- **Username:** `adminpusat`
- **Password:** `adminpusat123`
- **Akses:** Dashboard Admin Pusat, Review data unit

#### Admin Unit JTE

- **Username:** `adminjte`
- **Password:** `adminjte123`
- **Akses:** Dashboard Admin Unit JTE

#### Admin Unit JTM

- **Username:** `adminjtm`
- **Password:** `adminjtm123`
- **Akses:** Dashboard Admin Unit JTM

#### Admin Unit JTS

- **Username:** `adminjts`
- **Password:** `adminjts123`
- **Akses:** Dashboard Admin Unit JTS

#### Admin Unit JTIK

- **Username:** `adminjtik`
- **Password:** `adminjtik123`
- **Akses:** Dashboard Admin Unit JTIK

### 3. Test Fitur Dropdown

1. Login sebagai admin unit (contoh: `adminjte`)
2. Masuk ke kategori **"Waste (WS)"**
3. Pilih **"Sampah Organik"** di dropdown Jenis Sampah
4. Pastikan dropdown Area Sampah muncul
5. Pilih **"Kantin"** atau **"Lingkungan Kampus"**
6. Pastikan dropdown Detail Sampah muncul dengan opsi yang sesuai

---

## 🛠️ Troubleshooting

### Error: Unknown column 'unit.tipe_unit'

```bash
# Jika muncul error kolom tipe_unit tidak ditemukan:
# 1. Jalankan patch database
# 2. Import file database_patch_add_missing_columns.sql
# 3. Atau drop database dan import ulang backup lengkap
```

### Error: Database Connection Failed

```bash
# Cek MySQL service
# Pastikan database uigm_polban sudah dibuat
# Cek username/password di .env
```

### Error: 404 Not Found

```bash
# Pastikan mod_rewrite Apache aktif
# Cek file .htaccess di root project
# Pastikan app.baseURL di .env sesuai
```

### Error: Permission Denied

```bash
# Set permission folder writable
chmod -R 755 writable/
chmod -R 755 public/
```

### Dropdown Tidak Muncul

```bash
# Buka Developer Tools (F12)
# Cek Console untuk error JavaScript
# Pastikan jQuery dan Bootstrap JS ter-load
# Clear browser cache
```

### XAMPP Apache Tidak Start

```bash
# Cek port 80 tidak digunakan aplikasi lain
# Matikan IIS jika ada (Windows)
# Ganti port Apache ke 8080 jika perlu
```

### MySQL Tidak Start

```bash
# Cek port 3306 tidak digunakan
# Restart XAMPP sebagai Administrator
# Cek log error di xampp/mysql/data/
```

---

## 📁 Struktur File Backup

```
📦 Backup Files
├── 📄 database_backup_uigm_polban.sql    # Database lengkap
├── 📄 env_backup.txt                     # Konfigurasi environment
├── 📄 PANDUAN_INSTALASI_BACKUP.md        # Panduan ini
└── 📄 test_dropdown_fix.html             # Test dropdown functionality
```

---

## ✅ Checklist Instalasi

- [ ] XAMPP terinstall dan berjalan
- [ ] Database `uigm_polban` berhasil dibuat
- [ ] File SQL berhasil di-import
- [ ] File `.env` sudah dikonfigurasi
- [ ] Website bisa diakses di browser
- [ ] Login berhasil dengan salah satu akun
- [ ] Dropdown sampah organik berfungsi
- [ ] Tidak ada error di console browser

---

## 🆘 Bantuan Tambahan

Jika masih ada masalah:

1. **Cek Log Error:**

   - Apache: `xampp/apache/logs/error.log`
   - MySQL: `xampp/mysql/data/*.err`
   - PHP: `xampp/php/logs/php_error_log`

2. **Test Database Connection:**

   ```php
   <?php
   $conn = new mysqli("localhost", "root", "", "uigm_polban");
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   echo "Connected successfully";
   ?>
   ```

3. **Backup Ulang:**
   - Jika ada masalah, bisa import ulang file SQL
   - Database akan di-reset ke kondisi awal

---

**🎉 Selamat! Database UIGM POLBAN berhasil di-restore!**

_Backup dibuat pada: 31 Desember 2024_  
_Versi: Complete System with Fixed Dropdown_
