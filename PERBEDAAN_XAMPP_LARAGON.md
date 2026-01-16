# 🔄 Perbedaan XAMPP vs Laragon - Database Compatibility

## 📊 Perbandingan Versi MySQL

| Aspect | XAMPP | Laragon |
|--------|-------|---------|
| **MySQL Version** | 5.7.x atau MariaDB 10.4.x | 8.0.x - 8.4.x |
| **Default Collation** | `utf8mb4_general_ci` atau `utf8mb4_unicode_ci` | `utf8mb4_0900_ai_ci` |
| **Release Year** | 2016-2019 | 2018-2024 |
| **Compatibility** | Lebih luas (support sistem lama) | Lebih modern (fitur terbaru) |

---

## ❌ Kenapa File SQL dari Laragon Error di XAMPP?

### Masalah Utama: Collation

**Laragon (MySQL 8.4.3):**
```sql
CREATE TABLE `master_harga_sampah` (
  ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
```

**XAMPP (MySQL 5.7 / MariaDB 10.4):**
```
Error #1273 - Unknown collation: 'utf8mb4_0900_ai_ci'
```

### Kenapa Terjadi?

1. **Anda membuat tabel di Laragon** → MySQL 8.4 otomatis pakai collation baru
2. **Export database** → File SQL berisi collation `utf8mb4_0900_ai_ci`
3. **Teman import di XAMPP** → MySQL 5.7 tidak kenal collation ini
4. **Import gagal** → Error!

---

## ✅ Solusi: Gunakan File yang Sudah Diperbaiki

### File yang Harus Diberikan ke Teman:

**`eksperimen_fixed.sql`** ✅

File ini sudah saya ubah:
- ❌ `utf8mb4_0900_ai_ci` (tidak kompatibel)
- ✅ `utf8mb4_unicode_ci` (kompatibel dengan semua versi)

---

## 🧪 Cara Cek Versi MySQL di XAMPP

Minta teman Anda cek versi MySQL:

### Cara 1: Via phpMyAdmin
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Lihat di halaman utama
3. Cari "Server: localhost via TCP/IP"
4. Lihat "Database server" → **MySQL 5.7.x** atau **MariaDB 10.4.x**

### Cara 2: Via Command Line
```bash
# Buka XAMPP Control Panel
# Klik "Shell"
# Jalankan:
mysql --version
```

Output contoh:
```
mysql  Ver 15.1 Distrib 10.4.28-MariaDB  # ← MariaDB (XAMPP default)
```

atau

```
mysql  Ver 5.7.33  # ← MySQL 5.7 (XAMPP lama)
```

### Cara 3: Via PHP
Buat file `check_mysql.php`:
```php
<?php
$conn = mysqli_connect("localhost", "root", "", "");
echo "MySQL Version: " . mysqli_get_server_info($conn);
?>
```

Buka: http://localhost/check_mysql.php

---

## 📋 Checklist untuk Teman Anda

### Sebelum Import:

- [ ] Cek versi MySQL/MariaDB di XAMPP
- [ ] Pastikan menggunakan file **`eksperimen_fixed.sql`**
- [ ] Buat database baru: `eksperimen`
- [ ] Pastikan phpMyAdmin bisa diakses

### Cara Import di XAMPP:

1. **Buka XAMPP Control Panel**
2. **Start Apache & MySQL**
3. **Buka phpMyAdmin** (http://localhost/phpmyadmin)
4. **Klik "New"** di sidebar kiri
5. **Buat database:**
   - Database name: `eksperimen`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"
6. **Pilih database `eksperimen`**
7. **Klik tab "Import"**
8. **Choose File:** Pilih `eksperimen_fixed.sql`
9. **Scroll ke bawah**
10. **Klik "Go"**
11. **Tunggu sampai selesai** (muncul pesan sukses)

### Setelah Import:

- [ ] Cek jumlah tabel (harus 15 tabel)
- [ ] Cek data users (harus 6 rows)
- [ ] Test login aplikasi
- [ ] Test fitur CRUD

---

## 🔧 Troubleshooting XAMPP

### Problem 1: Import Timeout

**Error:**
```
Fatal error: Maximum execution time of 300 seconds exceeded
```

**Solution:**
1. Buka `C:\xampp\php\php.ini`
2. Cari dan ubah:
   ```ini
   max_execution_time = 600
   upload_max_filesize = 64M
   post_max_size = 64M
   memory_limit = 256M
   ```
3. Restart Apache di XAMPP Control Panel
4. Import ulang

### Problem 2: File Terlalu Besar

**Error:**
```
No data was received to import
```

**Solution:**
1. Buka `C:\xampp\phpMyAdmin\config.inc.php`
2. Tambahkan:
   ```php
   $cfg['UploadDir'] = '';
   $cfg['SaveDir'] = '';
   $cfg['ExecTimeLimit'] = 600;
   ```
3. Restart Apache
4. Import ulang

### Problem 3: MySQL Tidak Start

**Error:**
```
Port 3306 in use by another process
```

**Solution:**
1. Buka XAMPP Control Panel
2. Klik "Config" di MySQL
3. Pilih "my.ini"
4. Ubah port:
   ```ini
   port=3307
   ```
5. Save dan restart MySQL
6. Update `.env` aplikasi:
   ```
   DB_PORT = 3307
   ```

---

## 🚀 Cara Share Database ke Teman

### Opsi 1: Share File SQL (RECOMMENDED)

**Yang Anda Kirim:**
- ✅ `eksperimen_fixed.sql` (file yang sudah diperbaiki)
- ✅ `CARA_FIX_IMPORT_DATABASE.md` (panduan import)
- ✅ `.env.example` (contoh konfigurasi)

**Cara Kirim:**
- Google Drive
- WhatsApp (jika < 100MB)
- Email (jika < 25MB)
- GitHub (jika ada repo)

### Opsi 2: Share via GitHub

```bash
# Di folder project Anda
git add eksperimen_fixed.sql
git commit -m "Add fixed database for XAMPP compatibility"
git push

# Teman Anda:
git pull
# Import eksperimen_fixed.sql
```

### Opsi 3: Export Ulang dengan Setting Kompatibilitas

Di phpMyAdmin Anda (Laragon):

1. **Pilih database `eksperimen`**
2. **Klik tab "Export"**
3. **Pilih "Custom"**
4. **Scroll ke "Format-specific options"**
5. **Centang:**
   - ✅ Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER statement
   - ✅ Add IF NOT EXISTS
6. **Database system or older MySQL server:**
   - Pilih: **MySQL 5.7** atau **MariaDB 10.4**
7. **Klik "Go"**
8. **Save file:** `eksperimen_xampp_compatible.sql`

File ini akan otomatis kompatibel dengan XAMPP!

---

## 📝 Konfigurasi .env untuk XAMPP

Setelah import database, teman Anda perlu update `.env`:

```env
# Database Configuration for XAMPP
DB_CONNECTION = mysql
DB_HOST = localhost
DB_PORT = 3306
DB_DATABASE = eksperimen
DB_USERNAME = root
DB_PASSWORD = 
# ↑ XAMPP default password kosong!

# Base URL
app.baseURL = 'http://localhost/eksperimen/'
# ↑ Sesuaikan dengan folder project di htdocs
```

**Lokasi project di XAMPP:**
```
C:\xampp\htdocs\eksperimen\
```

---

## ✅ Kesimpulan

### Kenapa Error?

**Laragon (Anda):**
- MySQL 8.4.3 → Collation baru (`utf8mb4_0900_ai_ci`)

**XAMPP (Teman):**
- MySQL 5.7 / MariaDB 10.4 → Tidak support collation baru

### Solusi:

**Berikan file: `eksperimen_fixed.sql`** ✅

File ini sudah kompatibel dengan:
- ✅ XAMPP (MySQL 5.7+)
- ✅ XAMPP (MariaDB 10.4+)
- ✅ Laragon (MySQL 8.0+)
- ✅ MAMP (macOS)
- ✅ WAMP (Windows)

### Langkah untuk Teman:

1. Download `eksperimen_fixed.sql`
2. Buka XAMPP → Start Apache & MySQL
3. Buka phpMyAdmin
4. Buat database `eksperimen`
5. Import `eksperimen_fixed.sql`
6. Update `.env` (password kosong untuk XAMPP)
7. Akses aplikasi: http://localhost/eksperimen/

**Selesai! 🎉**

---

## 🎯 File yang Harus Dikirim ke Teman

```
📦 Package untuk Teman:
├── eksperimen_fixed.sql          ← Database (WAJIB)
├── CARA_FIX_IMPORT_DATABASE.md   ← Panduan import
├── PERBEDAAN_XAMPP_LARAGON.md    ← Penjelasan masalah
├── .env.example                   ← Contoh konfigurasi
└── TEST_DATABASE_FIXED.sql       ← Query untuk test
```

**Kirim semua file ini ke teman Anda!** 📤
