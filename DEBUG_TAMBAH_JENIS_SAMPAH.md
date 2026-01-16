# Debug: Jenis Sampah Tidak Muncul Setelah Ditambahkan

## Langkah-langkah Debug

### 1. Cek Console Browser (WAJIB!)

Saat menambahkan jenis sampah baru:

1. **Buka Developer Tools** (F12 atau Ctrl+Shift+I)
2. **Klik tab "Console"**
3. **Tambahkan jenis sampah baru**
4. **Lihat output di console**

**Yang harus muncul:**
```
Submitting new jenis sampah:
jenis_sampah: Elektronik
nama_jenis: Kabel Tembaga
harga_per_satuan: 50000
satuan: kg
dapat_dijual: 1
status_aktif: 1
Response status: 200
Response data: {success: true, message: "Jenis sampah berhasil ditambahkan", data: {...}}
```

**Jika ada error:**
```
Response data: {success: false, message: "Error message here", errors: {...}}
```

Screenshot error tersebut dan kirim ke developer!

---

### 2. Cek Database Langsung

**Buka phpMyAdmin:**
1. Pilih database `eksperimen`
2. Klik tabel `master_harga_sampah`
3. Klik tab "Browse"
4. **Lihat apakah data baru ada di tabel**

**Query untuk cek:**
```sql
SELECT * FROM master_harga_sampah 
ORDER BY id DESC 
LIMIT 10;
```

**Jika data ADA di database tapi TIDAK MUNCUL di halaman:**
- Masalah di tampilan/view
- Coba refresh halaman (Ctrl+F5)
- Coba clear cache browser

**Jika data TIDAK ADA di database:**
- Masalah di proses insert
- Lanjut ke langkah 3

---

### 3. Cek Log File

**Lokasi log:** `writable/logs/log-[tanggal].log`

**Cari baris ini:**
```
INFO --> Attempting to insert jenis sampah: {"jenis_sampah":"...","nama_jenis":"..."}
INFO --> Insert result: SUCCESS (ID: 123) atau FAILED
```

**Jika FAILED, cari error:**
```
ERROR --> Validation errors: {...}
ERROR --> Insert failed but no validation errors. Check database constraints.
```

---

### 4. Cek Struktur Database

**PENTING! Pastikan field jenis_sampah sudah VARCHAR:**

```sql
DESCRIBE master_harga_sampah;
```

**Harus menunjukkan:**
```
Field: jenis_sampah
Type: varchar(100)  <-- BUKAN enum(...)
```

**Jika masih ENUM:**
```sql
-- Jalankan ini untuk mengubah ke VARCHAR
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL;
```

---

### 5. Cek Validasi Model

**File:** `app/Models/HargaSampahModel.php`

**Pastikan validationRules tidak terlalu ketat:**
```php
protected $validationRules = [
    'jenis_sampah' => 'required|max_length[100]',  // OK
    'harga_per_satuan' => 'permit_empty|decimal',  // OK
    'satuan' => 'required|in_list[kg,gram,ton,liter,pcs,karung]',  // OK
];
```

---

## Kemungkinan Masalah & Solusi

### Masalah 1: Field jenis_sampah masih ENUM
**Gejala:** Error di console atau log: "Data truncated" atau "Invalid value"

**Solusi:**
```sql
ALTER TABLE `master_harga_sampah` 
MODIFY COLUMN `jenis_sampah` VARCHAR(100) NOT NULL;
```

---

### Masalah 2: Validasi gagal
**Gejala:** Response: `{success: false, message: "Semua field wajib diisi"}`

**Solusi:**
- Pastikan semua field required terisi
- Cek console untuk melihat data yang dikirim
- Pastikan checkbox "Dapat Dijual" dan "Status Aktif" tercentang

---

### Masalah 3: Harga tidak diisi untuk sampah yang dapat dijual
**Gejala:** Response: `{success: false, message: "Harga per satuan harus diisi..."}`

**Solusi:**
- Jika "Dapat Dijual" dicentang, harga WAJIB diisi
- Jika tidak dapat dijual, harga otomatis 0

---

### Masalah 4: Nama jenis sudah ada (duplikat)
**Gejala:** Response: `{success: false, message: "Nama jenis sampah ... sudah ada"}`

**Solusi:**
- Gunakan nama yang berbeda
- Atau edit data yang sudah ada

---

### Masalah 5: Data masuk tapi tidak muncul
**Gejala:** Data ada di database, tapi tidak tampil di halaman

**Solusi:**
1. Hard refresh: Ctrl+F5
2. Clear browser cache
3. Cek apakah `status_aktif = 1`
4. Cek query di controller `index()`:
   ```php
   $hargaList = $hargaModel->where('status_aktif', 1)->findAll();
   ```

---

## Cara Test yang Benar

1. **Buka Console Browser** (F12)
2. **Klik "Tambah Jenis Sampah"**
3. **Isi form:**
   - Kategori Sampah: `Elektronik`
   - Jenis Sampah: `Kabel Tembaga Bekas`
   - Deskripsi: `Kabel tembaga dari instalasi listrik`
   - ✅ Dapat Dijual (centang)
   - Harga: `50000`
   - Satuan: `kg`
   - ✅ Status Aktif (centang)
4. **Klik "Simpan"**
5. **Lihat console:**
   - Harus ada: `Response data: {success: true, ...}`
6. **Halaman reload otomatis**
7. **Data baru muncul di tabel**

---

## Jika Masih Gagal

**Kirim informasi berikut ke developer:**

1. **Screenshot console browser** (saat submit form)
2. **Screenshot response data** di console
3. **Screenshot tabel database** (master_harga_sampah)
4. **Copy log file** (writable/logs/log-[tanggal].log)
5. **Hasil query:**
   ```sql
   DESCRIBE master_harga_sampah;
   SELECT * FROM master_harga_sampah ORDER BY id DESC LIMIT 5;
   ```

---

## Quick Fix (Jika Urgent)

**Tambah data manual via SQL:**
```sql
INSERT INTO master_harga_sampah 
(jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual, status_aktif, created_at, updated_at)
VALUES 
('Elektronik', 'Kabel Tembaga Bekas', 50000, 'kg', 1, 1, NOW(), NOW());
```

Tapi ini hanya temporary! Tetap harus fix masalah utamanya.
