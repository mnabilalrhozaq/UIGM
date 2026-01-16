# 📦 Aplikasi Waste Management - Setup untuk XAMPP

## 🎯 Yang Harus Kamu Lakukan

### 1️⃣ Import Database (5 menit)

1. Buka XAMPP → Start Apache & MySQL
2. Buka http://localhost/phpmyadmin
3. Buat database baru: `eksperimen`
4. Import file: **`eksperimen_fixed.sql`** ✅
5. Tunggu sampai selesai

### 2️⃣ Copy Project (2 menit)

1. Copy folder project ke:
   ```
   C:\xampp\htdocs\eksperimen\
   ```

### 3️⃣ Setup .env (3 menit)

1. Copy file `.env.xampp` menjadi `.env`
2. Edit `.env`:
   ```env
   app.baseURL = 'http://localhost/eksperimen/'
   database.default.password = 
   # ↑ Password KOSONG untuk XAMPP!
   ```
3. Save

### 4️⃣ Akses Aplikasi

Buka browser: **http://localhost/eksperimen/**

---

## 🔐 Login

**Admin:**
- Username: `admin`
- Password: `admin12345678`

**User:**
- Username: `Nabila`
- Password: `user12345`

---

## ❌ Jika Ada Error

### Error: "Unknown collation"
- ✅ Pastikan pakai file **`eksperimen_fixed.sql`** (bukan yang lain!)

### Error: "Database connection failed"
- ✅ Cek MySQL sudah running di XAMPP
- ✅ Cek password di `.env` KOSONG (tidak ada spasi)

### Error: "404 Not Found"
- ✅ Cek base URL di `.env` ada slash (/) di akhir
- ✅ Cek nama folder di htdocs sama dengan base URL

### Error lainnya?
- 📖 Baca file: **`PANDUAN_INSTALL_XAMPP.md`** (lengkap!)

---

## 📁 File yang Penting

| File | Fungsi |
|------|--------|
| `eksperimen_fixed.sql` | Database (WAJIB import ini!) |
| `.env.xampp` | Konfigurasi untuk XAMPP |
| `PANDUAN_INSTALL_XAMPP.md` | Panduan lengkap + troubleshooting |
| `PERBEDAAN_XAMPP_LARAGON.md` | Penjelasan kenapa error |

---

## ✅ Checklist

- [ ] XAMPP Apache & MySQL running
- [ ] Database `eksperimen` sudah dibuat
- [ ] File `eksperimen_fixed.sql` sudah diimport (15 tabel)
- [ ] Project sudah di `C:\xampp\htdocs\eksperimen\`
- [ ] File `.env` sudah dikonfigurasi
- [ ] Bisa akses http://localhost/eksperimen/
- [ ] Bisa login

---

## 🎉 Selesai!

Kalau semua checklist di atas sudah ✅, aplikasi siap dipakai!

**Selamat mencoba! 🚀**
