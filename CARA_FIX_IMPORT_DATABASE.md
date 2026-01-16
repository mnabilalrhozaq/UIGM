# 🔧 Cara Fix Error Import Database MySQL

## ❌ Error yang Muncul

```
SQL query: C002
-- Struktur dari tabel `log_perubahan_harga`
--

MySQL said:
#1273 - Unknown collation: 'utf8mb4_0900_ai_ci'
```

## 🔍 Penyebab Masalah

**Collation `utf8mb4_0900_ai_ci`** adalah collation baru yang diperkenalkan di **MySQL 8.0**.

Laptop teman Anda kemungkinan menggunakan:
- ❌ MySQL 5.7 atau lebih lama
- ❌ MariaDB (yang tidak support collation MySQL 8.0)

Sedangkan laptop Anda menggunakan:
- ✅ MySQL 8.4.3 (dari screenshot)

## ✅ Solusi

### Solusi 1: Gunakan File SQL yang Sudah Diperbaiki (RECOMMENDED)

Saya sudah membuat file baru: **`eksperimen_fixed.sql`**

File ini sudah mengganti semua `utf8mb4_0900_ai_ci` menjadi `utf8mb4_unicode_ci` yang kompatibel dengan MySQL 5.7+.

**Cara Import:**
1. Buka phpMyAdmin di laptop teman
2. Buat database baru: `eksperimen`
3. Pilih database `eksperimen`
4. Klik tab **Import**
5. Pilih file: **`eksperimen_fixed.sql`**
6. Klik **Go**
7. ✅ Import berhasil!

---

### Solusi 2: Edit Manual File SQL

Jika Anda ingin edit sendiri:

**Cara 1: Menggunakan Text Editor (Notepad++, VS Code, dll)**

1. Buka file `eksperimen (5).sql` dengan text editor
2. Tekan **Ctrl + H** (Find & Replace)
3. Find: `utf8mb4_0900_ai_ci`
4. Replace: `utf8mb4_unicode_ci`
5. Klik **Replace All**
6. Save file dengan nama baru: `eksperimen_fixed.sql`
7. Import file baru ke phpMyAdmin

**Cara 2: Menggunakan Command Line (PowerShell)**

```powershell
(Get-Content "eksperimen (5).sql" -Raw) -replace 'utf8mb4_0900_ai_ci', 'utf8mb4_unicode_ci' | Set-Content "eksperimen_fixed.sql"
```

**Cara 3: Menggunakan Command Line (Linux/Mac)**

```bash
sed 's/utf8mb4_0900_ai_ci/utf8mb4_unicode_ci/g' "eksperimen (5).sql" > eksperimen_fixed.sql
```

---

### Solusi 3: Upgrade MySQL di Laptop Teman

Jika memungkinkan, upgrade MySQL ke versi 8.0+:

**Untuk XAMPP:**
1. Download XAMPP terbaru (dengan MySQL 8.0+)
2. Backup database lama
3. Install XAMPP baru
4. Restore database

**Untuk Laragon:**
1. Download Laragon terbaru
2. Pilih MySQL 8.0+ saat instalasi
3. Import database langsung

---

## 📊 Perbandingan Collation

| Collation | MySQL Version | Kompatibilitas |
|-----------|---------------|----------------|
| `utf8mb4_0900_ai_ci` | MySQL 8.0+ | ❌ Tidak kompatibel dengan MySQL 5.7 |
| `utf8mb4_unicode_ci` | MySQL 5.5+ | ✅ Kompatibel dengan semua versi |
| `utf8mb4_general_ci` | MySQL 5.5+ | ✅ Kompatibel dengan semua versi |

**Rekomendasi:** Gunakan `utf8mb4_unicode_ci` untuk kompatibilitas maksimal.

---

## 🧪 Testing

### Cek Versi MySQL di Laptop Teman

**Cara 1: Via phpMyAdmin**
1. Buka phpMyAdmin
2. Lihat di halaman utama
3. Cari "Server: localhost via TCP/IP"
4. Lihat "Server version: X.X.X"

**Cara 2: Via Command Line**
```bash
mysql --version
```

**Cara 3: Via PHP**
```php
<?php
echo mysqli_get_server_info($connection);
?>
```

### Verifikasi Import Berhasil

Setelah import, cek:

1. **Jumlah Tabel**
   - Harus ada 15 tabel
   - Cek di phpMyAdmin sidebar

2. **Jumlah Data**
   ```sql
   SELECT COUNT(*) FROM users;
   -- Expected: 6 rows
   
   SELECT COUNT(*) FROM master_harga_sampah;
   -- Expected: 6 rows
   
   SELECT COUNT(*) FROM waste_management;
   -- Expected: 19 rows
   ```

3. **Cek Collation**
   ```sql
   SHOW TABLE STATUS WHERE Name = 'master_harga_sampah';
   -- Collation harus: utf8mb4_unicode_ci
   ```

---

## 🚨 Troubleshooting

### Problem 1: Import Masih Error

**Kemungkinan:**
- File SQL corrupt
- Koneksi timeout
- Memory limit PHP terlalu kecil

**Solution:**
1. Cek file size (jika > 2MB, perlu setting php.ini)
2. Edit `php.ini`:
   ```ini
   upload_max_filesize = 64M
   post_max_size = 64M
   max_execution_time = 300
   memory_limit = 256M
   ```
3. Restart Apache/Nginx
4. Import ulang

### Problem 2: Sebagian Tabel Tidak Terimport

**Kemungkinan:**
- Error di tengah proses
- Foreign key constraint

**Solution:**
1. Drop database yang error
2. Buat database baru
3. Import ulang dari awal
4. Jangan import sebagian-sebagian

### Problem 3: Data Tidak Lengkap

**Kemungkinan:**
- Import berhenti di tengah
- Error tidak terlihat

**Solution:**
1. Cek error log di phpMyAdmin
2. Scroll ke bawah setelah import
3. Lihat pesan error detail
4. Fix error sesuai pesan

---

## 📝 Checklist Import Database

Sebelum import, pastikan:

- [ ] MySQL version di laptop teman sudah dicek
- [ ] File SQL sudah diperbaiki (collation)
- [ ] Database baru sudah dibuat
- [ ] PHP settings sudah disesuaikan (jika file besar)
- [ ] Koneksi internet stabil (jika import via web)

Setelah import, verifikasi:

- [ ] Semua tabel terimport (15 tabel)
- [ ] Data lengkap (cek jumlah rows)
- [ ] Tidak ada error di log
- [ ] Aplikasi bisa login
- [ ] Fitur CRUD berfungsi

---

## 🎯 Kesimpulan

**Masalah:** Collation `utf8mb4_0900_ai_ci` tidak kompatibel dengan MySQL versi lama.

**Solusi Tercepat:** Gunakan file **`eksperimen_fixed.sql`** yang sudah saya buat.

**Solusi Jangka Panjang:** 
- Untuk development: Gunakan collation `utf8mb4_unicode_ci`
- Untuk production: Pastikan semua server menggunakan MySQL versi yang sama

---

## 📚 Referensi

- [MySQL 8.0 Collation Changes](https://dev.mysql.com/doc/refman/8.0/en/charset-unicode-sets.html)
- [phpMyAdmin Import Guide](https://docs.phpmyadmin.net/en/latest/import_export.html)
- [MySQL Collation Compatibility](https://dev.mysql.com/doc/refman/8.0/en/charset-collation-compatibility.html)

---

**File yang Sudah Diperbaiki:** `eksperimen_fixed.sql` ✅

Sekarang teman Anda bisa import database tanpa error! 🎉
