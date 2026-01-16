# 🚨 LANGKAH FIX ERROR - IKUTI URUTAN INI!

## ⚠️ Masalah yang Terjadi:

1. ❌ Error "Duplicate entry 'Logam'" saat tambah data
2. ❌ Ada data dengan nama kosong yang tidak bisa dihapus
3. ❌ Error "Halaman tidak ditemukan" saat refresh

---

## ✅ SOLUSI - IKUTI LANGKAH INI DENGAN URUT!

### STEP 1: Buka phpMyAdmin

1. Buka browser
2. Ketik: `http://localhost/phpmyadmin`
3. Login (username: `root`, password: kosong)
4. Klik database `eksperimen` di sidebar kiri

---

### STEP 2: Hapus Data Kosong

**Copy-paste query ini di tab SQL:**

```sql
-- Lihat data yang bermasalah
SELECT * FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL OR jenis_sampah = '';
```

**Klik "Go"**

Jika ada data yang muncul, hapus dengan query ini:

```sql
-- Hapus data kosong
DELETE FROM master_harga_sampah 
WHERE nama_jenis = '' OR nama_jenis IS NULL OR jenis_sampah = '';
```

**Klik "Go"**

✅ **Expected Result:** "Query OK, X rows affected"

---

### STEP 3: Hapus UNIQUE Constraint

**Copy-paste query ini:**

```sql
-- Hapus constraint UNIQUE pada jenis_sampah
ALTER TABLE master_harga_sampah 
DROP INDEX unique_jenis_sampah;
```

**Klik "Go"**

✅ **Expected Result:** "Query OK, 0 rows affected"

**Jika error "Can't DROP 'unique_jenis_sampah'":**
- Artinya constraint sudah tidak ada
- Skip step ini, lanjut ke step 4

---

### STEP 4: Tambah Index Biasa

**Copy-paste query ini:**

```sql
-- Tambah index biasa (bukan UNIQUE)
ALTER TABLE master_harga_sampah 
ADD INDEX idx_jenis_sampah (jenis_sampah);
```

**Klik "Go"**

✅ **Expected Result:** "Query OK, 0 rows affected"

**Jika error "Duplicate key name 'idx_jenis_sampah'":**
- Artinya index sudah ada
- Skip step ini, lanjut ke step 5

---

### STEP 5: Cek Duplikasi Nama

**Copy-paste query ini:**

```sql
-- Cek apakah ada nama yang sama
SELECT nama_jenis, COUNT(*) as jumlah
FROM master_harga_sampah
GROUP BY nama_jenis
HAVING COUNT(*) > 1;
```

**Klik "Go"**

**Jika ada hasil:**
- Lihat nama yang duplikat
- Hapus salah satu dengan query:

```sql
-- Lihat data duplikat (ganti 'Kabel' dengan nama yang duplikat)
SELECT * FROM master_harga_sampah WHERE nama_jenis = 'Kabel';

-- Hapus yang ID lebih besar (sesuaikan ID)
DELETE FROM master_harga_sampah WHERE id = 7;
```

**Jika tidak ada hasil:**
- Bagus! Lanjut ke step 6

---

### STEP 6: Tambah UNIQUE pada Nama

**Copy-paste query ini:**

```sql
-- Tambah UNIQUE constraint pada nama_jenis
ALTER TABLE master_harga_sampah 
ADD UNIQUE KEY unique_nama_jenis (nama_jenis);
```

**Klik "Go"**

✅ **Expected Result:** "Query OK, 0 rows affected"

**Jika error "Duplicate key name 'unique_nama_jenis'":**
- Artinya constraint sudah ada
- Bagus! Lanjut ke step 7

**Jika error "Duplicate entry":**
- Kembali ke step 5
- Hapus data duplikat dulu

---

### STEP 7: Verifikasi

**Copy-paste query ini:**

```sql
-- Lihat semua data
SELECT * FROM master_harga_sampah ORDER BY id;

-- Lihat index
SHOW INDEX FROM master_harga_sampah;
```

**Klik "Go"**

✅ **Expected Result:**

**Data:**
- Tidak ada data dengan nama kosong
- Semua data punya jenis_sampah dan nama_jenis

**Index:**
- `PRIMARY` (id)
- `unique_nama_jenis` (nama_jenis) ← UNIQUE
- `idx_jenis_sampah` (jenis_sampah) ← INDEX biasa (bukan UNIQUE)

---

### STEP 8: Clear Browser Cache

1. **Tekan `Ctrl + Shift + Delete`**
2. **Pilih:**
   - ✅ Cached images and files
   - ✅ Cookies and other site data
3. **Time range:** All time
4. **Klik "Clear data"**

5. **Hard Refresh:**
   - Tekan `Ctrl + Shift + R`

---

### STEP 9: Logout dan Login Ulang

1. **Logout dari aplikasi**
2. **Close semua tab browser**
3. **Buka browser baru**
4. **Akses:** `http://localhost:8080/admin-pusat/manajemen-harga`
5. **Login dengan:**
   - Username: `admin`
   - Password: `admin12345678`

---

### STEP 10: Test Tambah Data

1. **Klik tombol "Tambah Jenis Sampah"**

2. **Isi form:**
   - **Kategori Utama:** `Logam`
   - **Sub Kategori:** `Tembaga`
   - **Nama Lengkap:** `Kabel Tembaga Bekas`
   - **Harga:** `4000`
   - **Satuan:** `kg`
   - **Dapat Dijual:** ✅ (centang)
   - **Status Aktif:** ✅ (centang)

3. **Klik "Simpan"**

✅ **Expected Result:**
```
✅ Jenis sampah berhasil ditambahkan
```

4. **Test tambah data Logam kedua:**
   - **Kategori Utama:** `Logam`
   - **Sub Kategori:** `Aluminium (Kaleng, Foil)`
   - **Nama Lengkap:** `Aluminium Kaleng Bekas`
   - **Harga:** `3000`
   - **Satuan:** `kg`

5. **Klik "Simpan"**

✅ **Expected Result:**
```
✅ Jenis sampah berhasil ditambahkan
✅ Tidak ada error "Duplicate entry"!
```

---

### STEP 11: Test Hapus Data

1. **Cari data yang ingin dihapus**
2. **Klik tombol "Hapus" (merah)**
3. **Klik "OK" di dialog konfirmasi**

✅ **Expected Result:**
```
✅ Jenis sampah berhasil dihapus
```

---

### STEP 12: Test Refresh Halaman

1. **Tekan `F5`**

✅ **Expected Result:**
```
✅ Halaman reload normal
✅ Tidak ada error "Halaman tidak ditemukan"
```

---

## 🎯 Checklist - Pastikan Semua Sudah Dilakukan!

- [ ] STEP 1: Buka phpMyAdmin
- [ ] STEP 2: Hapus data kosong
- [ ] STEP 3: Hapus UNIQUE constraint
- [ ] STEP 4: Tambah index biasa
- [ ] STEP 5: Cek duplikasi nama
- [ ] STEP 6: Tambah UNIQUE pada nama
- [ ] STEP 7: Verifikasi data dan index
- [ ] STEP 8: Clear browser cache
- [ ] STEP 9: Logout dan login ulang
- [ ] STEP 10: Test tambah data (harus berhasil)
- [ ] STEP 11: Test hapus data (harus berhasil)
- [ ] STEP 12: Test refresh (harus normal)

---

## 🚨 Jika Masih Error

### Error: "Duplicate entry 'Logam'"

**Artinya:** STEP 3 belum dijalankan atau gagal

**Solusi:**
1. Kembali ke STEP 3
2. Jalankan query DROP INDEX
3. Verifikasi dengan `SHOW INDEX`

### Error: "Can't DROP 'unique_jenis_sampah'"

**Artinya:** Constraint sudah tidak ada (bagus!)

**Solusi:**
- Skip STEP 3
- Lanjut ke STEP 4

### Error: "Duplicate entry for key 'unique_nama_jenis'"

**Artinya:** Ada data dengan nama yang sama

**Solusi:**
1. Kembali ke STEP 5
2. Cari data duplikat
3. Hapus salah satu
4. Lanjut ke STEP 6

### Error: "Halaman tidak ditemukan"

**Artinya:** Browser cache belum clear

**Solusi:**
1. Kembali ke STEP 8
2. Clear cache dengan benar
3. Hard refresh (Ctrl + Shift + R)
4. Logout dan login ulang

### Data Kosong Tidak Bisa Dihapus

**Artinya:** Data bermasalah di database

**Solusi:**
1. Kembali ke STEP 2
2. Hapus via SQL di phpMyAdmin
3. Jangan pakai tombol hapus di UI

---

## 📞 Butuh Bantuan?

Jika masih error setelah ikuti semua langkah:

1. **Screenshot error message**
2. **Jalankan query ini di phpMyAdmin:**
   ```sql
   SHOW INDEX FROM master_harga_sampah;
   SELECT * FROM master_harga_sampah;
   ```
3. **Screenshot hasil query**
4. **Kirim screenshot ke yang kasih project**

---

## ✅ Selesai!

Setelah semua langkah di atas, sistem harus sudah normal:

✅ Bisa tambah beberapa jenis sampah dengan kategori yang sama
✅ Bisa hapus data
✅ Tidak ada error "Duplicate entry"
✅ Tidak ada error "Halaman tidak ditemukan"
✅ Tidak ada data kosong

**Happy coding! 🚀**
