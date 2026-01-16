# Checklist: Tambah Jenis Sampah Baru

## Alur Kerja yang Benar

```
Admin Tambah Jenis Sampah
    ↓
Data masuk ke tabel: master_harga_sampah
    ↓
User & TPS otomatis bisa lihat di dropdown
    ↓
User & TPS bisa input data sampah dengan jenis baru
```

---

## LANGKAH WAJIB (Harus Dilakukan Dulu!)

### 1. Ubah Field jenis_sampah dari ENUM ke VARCHAR

**Buka phpMyAdmin → Database `eksperimen` → Tab SQL**

Jalankan query ini:
```sql
-- Cek struktur tabel dulu
DESCRIBE master_harga_sampah;

-- Jika jenis_sampah masih ENUM, jalankan ini:
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL COMMENT 'Kategori sampah (bebas diisi)';

-- Verifikasi perubahan
DESCRIBE master_harga_sampah;
```

**Hasil yang benar:**
```
Field: jenis_sampah
Type: varchar(100)  ← HARUS INI, BUKAN enum(...)
```

---

## Cara Tambah Jenis Sampah Baru

### 1. Login sebagai Admin
- URL: `http://localhost:8080/admin-pusat/manajemen-harga`

### 2. Klik "Tambah Jenis Sampah"

### 3. Isi Form dengan Benar

**Contoh 1: Sampah yang BISA DIJUAL**
```
Kategori Sampah: Elektronik
Jenis Sampah: Kabel Tembaga Bekas
Deskripsi: Kabel tembaga dari instalasi listrik
✅ Dapat Dijual (CENTANG)
Harga per Satuan: 50000
Satuan: kg
✅ Status Aktif (CENTANG)
```

**Contoh 2: Sampah yang TIDAK BISA DIJUAL**
```
Kategori Sampah: Organik
Jenis Sampah: Sisa Makanan
Deskripsi: Sampah organik dari kantin
❌ Dapat Dijual (JANGAN CENTANG)
(Harga otomatis 0)
Satuan: kg
✅ Status Aktif (CENTANG)
```

### 4. Klik "Simpan Jenis Sampah"

### 5. Cek Console Browser (F12)
Harus muncul:
```javascript
Response data: {
  success: true, 
  message: "Jenis sampah berhasil ditambahkan",
  data: {id: 123, jenis_sampah: "Elektronik", ...}
}
```

---

## Verifikasi Data Masuk

### 1. Cek di Database
```sql
-- Lihat data terbaru
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual, status_aktif, created_at
FROM master_harga_sampah 
ORDER BY id DESC 
LIMIT 5;
```

**Yang harus ada:**
- ✅ Data baru muncul
- ✅ `jenis_sampah` terisi (bukan NULL)
- ✅ `status_aktif = 1`
- ✅ `created_at` = waktu sekarang

### 2. Cek di Halaman Admin
- Refresh halaman (Ctrl+F5)
- Data baru harus muncul di tabel

### 3. Cek di User/TPS
- Login sebagai User atau TPS
- Buka halaman "Data Sampah"
- Klik "Tambah Data Sampah"
- **Jenis sampah baru harus muncul di dropdown!**

---

## Troubleshooting

### ❌ Data tidak masuk ke database

**Cek Console:**
```javascript
Response data: {success: false, message: "..."}
```

**Kemungkinan:**
1. Field `jenis_sampah` masih ENUM → Jalankan ALTER TABLE
2. Validasi gagal → Cek error message di console
3. Nama duplikat → Gunakan nama yang berbeda

---

### ❌ Data masuk tapi tidak muncul di Admin

**Cek:**
```sql
SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY id DESC;
```

**Jika data ada tapi tidak tampil:**
- Hard refresh: Ctrl+F5
- Clear browser cache
- Cek apakah `status_aktif = 1`

---

### ❌ Data muncul di Admin tapi tidak di User/TPS

**Ini TIDAK MUNGKIN terjadi** karena semua mengambil dari tabel yang sama!

**Jika terjadi, cek:**

1. **Apakah User/TPS sudah refresh halaman?**
   - Tekan Ctrl+F5 untuk hard refresh

2. **Cek query di User/TPS Service:**
   ```php
   // app/Services/User/WasteService.php
   // app/Services/TPS/WasteService.php
   
   $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
   ```

3. **Cek apakah data benar-benar ada:**
   ```sql
   SELECT COUNT(*) as total 
   FROM master_harga_sampah 
   WHERE status_aktif = 1;
   ```

4. **Cek browser cache:**
   - Buka Incognito/Private mode
   - Coba lagi

---

## Test Lengkap

### 1. Admin Tambah Jenis Baru
```
Login Admin → Manajemen Sampah → Tambah Jenis Sampah
Kategori: Elektronik
Jenis: Kabel Tembaga Bekas
Harga: 50000
Satuan: kg
✅ Dapat Dijual
✅ Status Aktif
→ Simpan
```

### 2. Verifikasi di Database
```sql
SELECT * FROM master_harga_sampah WHERE jenis_sampah = 'Elektronik';
```
Harus ada 1 row dengan `status_aktif = 1`

### 3. Verifikasi di Admin
- Refresh halaman
- Data "Kabel Tembaga Bekas" muncul di tabel

### 4. Verifikasi di User
- Login sebagai User
- Buka "Data Sampah" → "Tambah Data Sampah"
- Dropdown "Jenis Sampah" harus ada: **"Kabel Tembaga Bekas (Elektronik)"**

### 5. Verifikasi di TPS
- Login sebagai TPS
- Buka "Data Sampah" → "Tambah Data Sampah"
- Dropdown "Jenis Sampah" harus ada: **"Kabel Tembaga Bekas (Elektronik)"**

### 6. Test Input Data
- User/TPS pilih "Kabel Tembaga Bekas"
- Isi jumlah: 10
- Pilih satuan: kg
- Simpan
- **Harus berhasil!**

---

## Jika Semua Gagal

**Kirim informasi ini:**

1. **Screenshot console saat tambah jenis sampah**
2. **Hasil query:**
   ```sql
   DESCRIBE master_harga_sampah;
   SELECT * FROM master_harga_sampah ORDER BY id DESC LIMIT 5;
   ```
3. **Screenshot dropdown di User/TPS**
4. **Log file:** `writable/logs/log-[tanggal].log`

---

## Quick Test (Copy-Paste)

**Jalankan di phpMyAdmin:**
```sql
-- 1. Cek struktur
DESCRIBE master_harga_sampah;

-- 2. Tambah data manual untuk test
INSERT INTO master_harga_sampah 
(jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual, status_aktif, created_at, updated_at)
VALUES 
('Elektronik', 'Kabel Tembaga Bekas TEST', 50000, 'kg', 1, 1, NOW(), NOW());

-- 3. Cek data
SELECT * FROM master_harga_sampah WHERE jenis_sampah = 'Elektronik';

-- 4. Refresh halaman User/TPS
-- 5. Cek dropdown - harus ada "Kabel Tembaga Bekas TEST"
```

Jika data manual muncul di User/TPS, berarti masalahnya di proses insert dari form admin!
