# 🔧 Cara Fix Error: Duplicate Entry 'Logam'

## ❌ Error yang Muncul

### Error 1: Saat Tambah Jenis Sampah
```
Terjadi kesalahan saat menyimpan data: Duplicate entry 'Logam' 
for key 'master_harga_sampah.unique_jenis_sampah'
```

### Error 2: Saat Refresh Halaman
```
Halaman tidak ditemukan. Anda dialihkan ke dashboard.
```

---

## 🔍 Penyebab Masalah

### Error 1: Duplicate Entry

**Masalah:**
- Database memiliki constraint `UNIQUE` pada field `jenis_sampah`
- Artinya: Hanya boleh ada 1 data dengan `jenis_sampah = 'Logam'`
- Ketika Anda coba tambah data Logam kedua → ERROR!

**Struktur Database Lama:**
```sql
ALTER TABLE master_harga_sampah
  ADD UNIQUE KEY unique_jenis_sampah (jenis_sampah);
  -- ↑ Ini yang bikin error!
```

**Kenapa Ini Masalah?**

Kita ingin bisa menambah beberapa jenis sampah dengan kategori yang sama:
- ✅ Logam: Kabel Tembaga Bekas
- ✅ Logam: Aluminium Kaleng
- ✅ Logam: Besi Tua
- ✅ Plastik: Botol PET
- ✅ Plastik: Kantong Plastik

Tapi dengan constraint UNIQUE, hanya boleh 1 jenis Logam, 1 jenis Plastik, dll. ❌

### Error 2: Halaman Tidak Ditemukan

**Kemungkinan Penyebab:**
1. Browser cache lama
2. Session expired
3. Redirect loop

---

## ✅ Solusi

### Solusi 1: Fix Database (WAJIB)

**Jalankan SQL ini di phpMyAdmin:**

```sql
USE eksperimen;

-- 1. Hapus UNIQUE constraint pada jenis_sampah
ALTER TABLE master_harga_sampah 
DROP INDEX unique_jenis_sampah;

-- 2. Tambah index biasa (untuk performance)
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);

-- 3. Tambah UNIQUE constraint pada nama_jenis
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);

-- 4. Verifikasi
SHOW INDEX FROM master_harga_sampah;
```

**Cara Jalankan:**

1. **Buka phpMyAdmin**
2. **Pilih database `eksperimen`**
3. **Klik tab "SQL"**
4. **Copy-paste query di atas**
5. **Klik "Go"**
6. **Tunggu sampai muncul pesan sukses**

**Expected Result:**
```
✅ Query OK, 0 rows affected
✅ Query OK, 0 rows affected
✅ Query OK, 0 rows affected
```

**Verifikasi:**

Setelah jalankan query, cek index:

```sql
SHOW INDEX FROM master_harga_sampah;
```

Harus ada:
- ✅ `PRIMARY` (id)
- ✅ `unique_nama_jenis` (nama_jenis) ← UNIQUE
- ✅ `idx_jenis_sampah` (jenis_sampah) ← INDEX biasa (bukan UNIQUE)

---

### Solusi 2: Fix Error "Halaman Tidak Ditemukan"

**Cara 1: Clear Browser Cache**

1. **Chrome:**
   - Tekan `Ctrl + Shift + Delete`
   - Pilih "Cached images and files"
   - Pilih "All time"
   - Klik "Clear data"

2. **Hard Refresh:**
   - Tekan `Ctrl + Shift + R` (Chrome)
   - Atau `Ctrl + F5` (Firefox)

**Cara 2: Clear Session**

1. **Logout dari aplikasi**
2. **Close semua tab browser**
3. **Buka browser baru**
4. **Login ulang**

**Cara 3: Clear Writable Cache**

```
Hapus folder:
F:\laragon\www\eksperimen\writable\cache\*
F:\laragon\www\eksperimen\writable\session\*
```

Atau via Command Prompt:
```cmd
cd F:\laragon\www\eksperimen
rmdir /s /q writable\cache
rmdir /s /q writable\session
mkdir writable\cache
mkdir writable\session
```

---

## 🧪 Testing Setelah Fix

### Test 1: Tambah Data Logam Pertama

**Input:**
- Kategori Utama: `Logam`
- Sub Kategori: `Tembaga`
- Nama Lengkap: `Kabel Tembaga Bekas`
- Harga: `4000`
- Satuan: `kg`

**Expected Result:**
```
✅ Jenis sampah berhasil ditambahkan
✅ Data muncul di tabel
```

### Test 2: Tambah Data Logam Kedua

**Input:**
- Kategori Utama: `Logam`
- Sub Kategori: `Aluminium (Kaleng, Foil)`
- Nama Lengkap: `Aluminium Kaleng Bekas`
- Harga: `3000`
- Satuan: `kg`

**Expected Result:**
```
✅ Jenis sampah berhasil ditambahkan
✅ Data muncul di tabel
✅ Tidak ada error "Duplicate entry"!
```

### Test 3: Tambah Data dengan Nama Sama (Harus Error)

**Input:**
- Kategori Utama: `Plastik`
- Nama Lengkap: `Kabel Tembaga Bekas` ← Nama sama dengan data Logam

**Expected Result:**
```
❌ Nama jenis sampah "Kabel Tembaga Bekas" sudah ada. 
   Gunakan nama yang berbeda.
```

Ini benar! Nama harus unik untuk menghindari duplikasi.

### Test 4: Refresh Halaman

**Action:**
- Tekan `F5` atau klik refresh

**Expected Result:**
```
✅ Halaman reload normal
✅ Data tetap muncul
✅ Tidak ada error "Halaman tidak ditemukan"
```

---

## 📊 Perbandingan Before & After

### Before (Error)

**Database:**
```sql
UNIQUE KEY unique_jenis_sampah (jenis_sampah)
-- ↑ Hanya boleh 1 jenis Logam
```

**Hasil:**
```
Data 1: Logam - Kabel Tembaga ✅
Data 2: Logam - Aluminium ❌ ERROR: Duplicate entry 'Logam'
```

### After (Fixed)

**Database:**
```sql
INDEX idx_jenis_sampah (jenis_sampah)
-- ↑ Boleh banyak jenis Logam

UNIQUE KEY unique_nama_jenis (nama_jenis)
-- ↑ Nama harus unik
```

**Hasil:**
```
Data 1: Logam - Kabel Tembaga Bekas ✅
Data 2: Logam - Aluminium Kaleng Bekas ✅
Data 3: Logam - Besi Tua ✅
Data 4: Plastik - Botol PET Bersih ✅
Data 5: Plastik - Kantong Plastik HDPE ✅
```

---

## 🚨 Troubleshooting

### Problem 1: Error "Can't DROP 'unique_jenis_sampah'"

**Error:**
```
Can't DROP 'unique_jenis_sampah'; check that column/key exists
```

**Solution:**

Constraint sudah dihapus atau tidak ada. Lanjut ke step berikutnya:

```sql
-- Skip step 1, langsung ke step 2
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);
```

### Problem 2: Error "Duplicate key name 'unique_nama_jenis'"

**Error:**
```
Duplicate key name 'unique_nama_jenis'
```

**Solution:**

Constraint sudah ada. Cek dengan:

```sql
SHOW INDEX FROM master_harga_sampah;
```

Jika `unique_nama_jenis` sudah ada, skip step 3.

### Problem 3: Error "Duplicate entry for key 'unique_nama_jenis'"

**Error:**
```
Duplicate entry 'Kabel' for key 'unique_nama_jenis'
```

**Solution:**

Ada data dengan nama sama. Cek duplikasi:

```sql
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;
```

Hapus data duplikat:

```sql
-- Lihat data duplikat
SELECT * FROM master_harga_sampah WHERE nama_jenis = 'Kabel';

-- Hapus yang ID lebih besar (data baru)
DELETE FROM master_harga_sampah WHERE id = 7; -- sesuaikan ID
```

Setelah tidak ada duplikasi, jalankan ulang:

```sql
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);
```

### Problem 4: Masih Error Setelah Fix Database

**Solution:**

1. **Clear browser cache** (Ctrl + Shift + Delete)
2. **Logout dan login ulang**
3. **Restart Apache di Laragon**
4. **Clear writable cache:**
   ```cmd
   cd F:\laragon\www\eksperimen
   rmdir /s /q writable\cache
   mkdir writable\cache
   ```

---

## ✅ Checklist Fix

Pastikan semua langkah ini sudah dilakukan:

- [ ] Jalankan SQL fix di phpMyAdmin
- [ ] Verifikasi index dengan `SHOW INDEX`
- [ ] Clear browser cache
- [ ] Logout dan login ulang
- [ ] Test tambah data Logam pertama (harus berhasil)
- [ ] Test tambah data Logam kedua (harus berhasil)
- [ ] Test tambah data dengan nama sama (harus error)
- [ ] Test refresh halaman (harus normal)

---

## 📝 File SQL yang Tersedia

1. **`FIX_DATABASE_SEKARANG.sql`**
   - SQL untuk fix database yang sudah ada
   - Jalankan di phpMyAdmin

2. **`FIX_UNIQUE_CONSTRAINT.sql`**
   - SQL lengkap dengan penjelasan
   - Sama dengan file 1, tapi lebih detail

3. **`eksperimen_fixed.sql`**
   - Database export yang sudah diperbaiki
   - Untuk install baru atau teman Anda

---

## 🎉 Selesai!

Setelah fix database, Anda bisa:

✅ Menambah beberapa jenis sampah dengan kategori yang sama
✅ Tidak ada lagi error "Duplicate entry"
✅ Halaman refresh normal
✅ Sistem berjalan lancar

**Happy coding! 🚀**
