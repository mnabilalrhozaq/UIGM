# 📦 Panduan Install Aplikasi di XAMPP

## 🎯 File yang Anda Terima

Pastikan Anda sudah menerima file-file ini:

- ✅ `eksperimen_fixed.sql` - Database yang sudah diperbaiki
- ✅ `.env.xampp` - Konfigurasi untuk XAMPP
- ✅ Folder project lengkap (app, public, writable, dll)

---

## 🚀 Langkah-Langkah Install

### Step 1: Persiapan XAMPP

1. **Buka XAMPP Control Panel**
2. **Start Apache**
3. **Start MySQL**
4. Pastikan keduanya berwarna hijau (running)

**Jika MySQL tidak bisa start:**
- Port 3306 mungkin dipakai aplikasi lain
- Solusi: Lihat bagian Troubleshooting di bawah

---

### Step 2: Copy Project ke htdocs

1. **Buka folder XAMPP:**
   ```
   C:\xampp\htdocs\
   ```

2. **Copy folder project ke sini:**
   ```
   C:\xampp\htdocs\eksperimen\
   ```

3. **Struktur folder harus seperti ini:**
   ```
   C:\xampp\htdocs\eksperimen\
   ├── app/
   ├── public/
   ├── writable/
   ├── vendor/
   ├── .env
   ├── spark
   └── ...
   ```

---

### Step 3: Import Database

1. **Buka phpMyAdmin:**
   - URL: http://localhost/phpmyadmin
   - Username: `root`
   - Password: (kosong, langsung klik Go)

2. **Buat Database Baru:**
   - Klik "New" di sidebar kiri
   - Database name: `eksperimen`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

3. **Import Database:**
   - Pilih database `eksperimen` yang baru dibuat
   - Klik tab "Import"
   - Klik "Choose File"
   - Pilih file: `eksperimen_fixed.sql`
   - Scroll ke bawah
   - Klik "Go"
   - Tunggu sampai muncul pesan sukses

4. **Verifikasi Import:**
   - Klik database `eksperimen` di sidebar
   - Harus ada 15 tabel:
     ```
     ✅ dashboard_settings
     ✅ feature_toggles
     ✅ feature_toggle_logs
     ✅ log_perubahan_harga
     ✅ master_harga_sampah
     ✅ notifications
     ✅ penilaian_unit
     ✅ unit
     ✅ units
     ✅ users
     ✅ waste_approved
     ✅ waste_management
     ✅ waste_rejected
     ✅ waste_tps
     ```

---

### Step 4: Konfigurasi .env

1. **Copy file `.env.xampp` menjadi `.env`:**
   ```
   C:\xampp\htdocs\eksperimen\.env.xampp
   →
   C:\xampp\htdocs\eksperimen\.env
   ```

2. **Edit file `.env`:**
   - Buka dengan Notepad++ atau VS Code
   - Pastikan konfigurasi ini:

   ```env
   # Base URL - PENTING!
   app.baseURL = 'http://localhost/eksperimen/'
   # ↑ Sesuaikan dengan nama folder Anda

   # Database
   database.default.hostname = localhost
   database.default.database = eksperimen
   database.default.username = root
   database.default.password = 
   # ↑ Password KOSONG untuk XAMPP default
   ```

3. **Save file**

---

### Step 5: Set Permission Folder (Penting!)

**Untuk Windows:**

1. Klik kanan folder `writable`
2. Properties → Security
3. Edit → Add → Everyone
4. Centang "Full Control"
5. OK → Apply

**Atau via Command Prompt (Run as Administrator):**
```cmd
cd C:\xampp\htdocs\eksperimen
icacls writable /grant Everyone:F /T
```

---

### Step 6: Install Dependencies (Jika Belum Ada)

**Jika folder `vendor` tidak ada atau kosong:**

1. **Install Composer** (jika belum):
   - Download: https://getcomposer.org/download/
   - Install dengan default settings

2. **Buka Command Prompt:**
   ```cmd
   cd C:\xampp\htdocs\eksperimen
   composer install
   ```

3. **Tunggu sampai selesai**

---

### Step 7: Akses Aplikasi

1. **Buka browser**
2. **Akses URL:**
   ```
   http://localhost/eksperimen/
   ```

3. **Jika muncul halaman login, BERHASIL! 🎉**

---

## 🔐 Login Aplikasi

### Akun Admin Pusat
```
Username: admin
Password: admin12345678
```

### Akun Pengelola TPS
```
Username: pengelolatps
Password: tps12345
```

### Akun User
```
Username: Nabila
Password: user12345
```

---

## 🐛 Troubleshooting

### Problem 1: MySQL Port 3306 Sudah Dipakai

**Error:**
```
Port 3306 in use by "Unable to open process"
```

**Solution:**

1. **Buka XAMPP Control Panel**
2. **Klik "Config" di MySQL**
3. **Pilih "my.ini"**
4. **Cari baris:**
   ```ini
   port=3306
   ```
5. **Ubah menjadi:**
   ```ini
   port=3307
   ```
6. **Save dan close**
7. **Start MySQL lagi**
8. **Update `.env`:**
   ```env
   database.default.port = 3307
   ```

---

### Problem 2: Page Not Found / 404

**Error:**
```
404 - File Not Found
```

**Solution:**

1. **Cek Base URL di `.env`:**
   ```env
   app.baseURL = 'http://localhost/eksperimen/'
   # ↑ Harus ada slash (/) di akhir!
   ```

2. **Cek nama folder di htdocs:**
   - Jika folder: `C:\xampp\htdocs\myproject\`
   - Maka base URL: `http://localhost/myproject/`

3. **Enable mod_rewrite di Apache:**
   - Buka XAMPP Control Panel
   - Klik "Config" di Apache
   - Pilih "httpd.conf"
   - Cari baris:
     ```
     #LoadModule rewrite_module modules/mod_rewrite.so
     ```
   - Hapus tanda `#` di depannya:
     ```
     LoadModule rewrite_module modules/mod_rewrite.so
     ```
   - Save dan restart Apache

---

### Problem 3: CSS/JS Tidak Load

**Error:**
- Halaman tampil tapi tidak ada style
- Tombol tidak berfungsi

**Solution:**

1. **Cek Base URL sudah benar**
2. **Clear browser cache:**
   - Chrome: Ctrl + Shift + Delete
   - Pilih "Cached images and files"
   - Clear data
3. **Hard refresh:**
   - Ctrl + Shift + R (Chrome)
   - Ctrl + F5 (Firefox)

---

### Problem 4: Database Connection Failed

**Error:**
```
Unable to connect to the database
```

**Solution:**

1. **Cek MySQL sudah running di XAMPP**
2. **Cek konfigurasi `.env`:**
   ```env
   database.default.hostname = localhost
   database.default.database = eksperimen
   database.default.username = root
   database.default.password = 
   # ↑ Pastikan password KOSONG (tidak ada spasi)
   ```
3. **Test koneksi di phpMyAdmin:**
   - Buka http://localhost/phpmyadmin
   - Jika bisa login, berarti MySQL OK
4. **Cek database `eksperimen` sudah ada**

---

### Problem 5: Session Error

**Error:**
```
Session: Configured save path 'writable/session' is not writable
```

**Solution:**

1. **Buat folder session jika belum ada:**
   ```
   C:\xampp\htdocs\eksperimen\writable\session\
   ```

2. **Set permission folder writable:**
   - Klik kanan folder `writable`
   - Properties → Security
   - Edit → Add → Everyone
   - Centang "Full Control"
   - OK → Apply

3. **Atau via Command Prompt:**
   ```cmd
   cd C:\xampp\htdocs\eksperimen
   mkdir writable\session
   icacls writable /grant Everyone:F /T
   ```

---

### Problem 6: Import Database Timeout

**Error:**
```
Fatal error: Maximum execution time of 300 seconds exceeded
```

**Solution:**

1. **Edit `php.ini`:**
   - Buka XAMPP Control Panel
   - Klik "Config" di Apache
   - Pilih "PHP (php.ini)"
   - Cari dan ubah:
     ```ini
     max_execution_time = 600
     upload_max_filesize = 64M
     post_max_size = 64M
     memory_limit = 256M
     ```
   - Save dan restart Apache

2. **Import ulang database**

---

### Problem 7: Blank Page / White Screen

**Error:**
- Halaman putih kosong
- Tidak ada error message

**Solution:**

1. **Enable error display:**
   - Edit `.env`:
     ```env
     CI_ENVIRONMENT = development
     ```
   - Save dan refresh browser

2. **Cek error log:**
   ```
   C:\xampp\htdocs\eksperimen\writable\logs\
   ```
   - Buka file log terbaru
   - Lihat error message

3. **Cek PHP version:**
   - Buka http://localhost/dashboard
   - Lihat PHP version
   - Aplikasi butuh PHP 7.4 atau lebih baru

---

## ✅ Checklist Instalasi

Pastikan semua langkah ini sudah dilakukan:

- [ ] XAMPP Apache running (hijau)
- [ ] XAMPP MySQL running (hijau)
- [ ] Project sudah di `C:\xampp\htdocs\eksperimen\`
- [ ] Database `eksperimen` sudah dibuat
- [ ] File `eksperimen_fixed.sql` sudah diimport
- [ ] Ada 15 tabel di database
- [ ] File `.env` sudah dikonfigurasi
- [ ] Base URL sudah benar (ada slash di akhir)
- [ ] Folder `writable` sudah ada permission
- [ ] Folder `vendor` sudah ada (atau sudah `composer install`)
- [ ] Bisa akses http://localhost/eksperimen/
- [ ] Bisa login dengan akun admin

---

## 🎉 Selesai!

Jika semua checklist di atas sudah ✅, aplikasi sudah siap digunakan!

**Test fitur-fitur:**
1. Login sebagai admin
2. Buka menu Manajemen Sampah
3. Tambah data baru
4. Edit data
5. Hapus data
6. Logout

**Jika ada masalah, cek bagian Troubleshooting di atas!**

---

## 📞 Butuh Bantuan?

Jika masih ada error:

1. Screenshot error message
2. Cek file log di `writable/logs/`
3. Cek versi MySQL di phpMyAdmin
4. Hubungi yang kasih project ini 😊

**Good luck! 🚀**
