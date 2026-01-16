# 🐛 Bug Fix: Jenis Sampah Kosong Saat Tambah Data

## ❌ Masalah

Ketika admin menambah jenis sampah baru, kolom "Jenis Sampah" menjadi kosong di tabel, hanya "Nama Lengkap" yang terisi.

**Screenshot Masalah:**
```
Jenis Sampah | Nama Lengkap | Harga
-------------|--------------|-------
(kosong)     | Kabel        | Rp 4.000
```

---

## 🔍 Penyebab Masalah

### 1. Struktur Database

Field `jenis_sampah` di tabel `master_harga_sampah` adalah **ENUM**:

```sql
`jenis_sampah` enum('Plastik','Kertas','Logam','Organik','Residu') NOT NULL
```

ENUM hanya menerima nilai: `'Plastik'`, `'Kertas'`, `'Logam'`, `'Organik'`, `'Residu'`

### 2. Form Lama (Salah)

Form menggunakan dropdown dengan value seperti:
- `"Plastik PET"` ❌
- `"Kertas HVS"` ❌
- `"Logam Aluminium"` ❌
- `"Elektronik Kabel"` ❌

```html
<select id="add_kategori_sampah">
    <option value="Plastik PET">Plastik PET (Botol Minuman)</option>
    <option value="Kertas HVS">Kertas HVS/Putih</option>
    <option value="Elektronik Kabel">Kabel Elektronik</option>
</select>
```

### 3. Apa yang Terjadi?

1. User pilih "Elektronik Kabel" di dropdown
2. Value "Elektronik Kabel" dikirim ke server
3. Server coba insert ke field `jenis_sampah` (ENUM)
4. MySQL tolak karena "Elektronik Kabel" bukan nilai ENUM yang valid
5. Field `jenis_sampah` jadi **kosong/NULL**
6. Field `nama_jenis` tetap terisi "Kabel"

**Hasil:** Data tersimpan tapi `jenis_sampah` kosong!

---

## ✅ Solusi

### 1. Pisahkan Kategori Utama dan Sub Kategori

**Form Baru (Benar):**

```html
<!-- Kategori Utama (untuk field jenis_sampah ENUM) -->
<select id="add_jenis_sampah" name="jenis_sampah">
    <option value="">Pilih Kategori Utama</option>
    <option value="Plastik">Plastik</option>
    <option value="Kertas">Kertas</option>
    <option value="Logam">Logam</option>
    <option value="Organik">Organik</option>
    <option value="Residu">Residu</option>
</select>

<!-- Sub Kategori (untuk membantu isi nama_jenis) -->
<select id="add_sub_kategori">
    <option value="">Pilih Sub Kategori</option>
    <!-- Akan diisi dinamis berdasarkan kategori utama -->
</select>

<!-- Nama Lengkap (field nama_jenis) -->
<input type="text" id="add_nama_jenis" name="nama_jenis" 
       placeholder="Contoh: Kabel Elektronik Bekas">
```

### 2. JavaScript untuk Handle Sub Kategori

```javascript
// Data sub kategori
const subKategoriData = {
    'Plastik': [
        'Plastik PET (Botol Minuman)',
        'Plastik HDPE (Botol Detergen, Shampo)',
        'Plastik PVC (Pipa, Kabel)',
        // ...
    ],
    'Kertas': [
        'Kertas HVS/Putih',
        'Kertas Koran',
        // ...
    ],
    'Logam': [
        'Besi/Baja',
        'Aluminium (Kaleng, Foil)',
        // ...
    ],
    // ...
};

// Populate sub kategori saat kategori utama dipilih
document.getElementById('add_jenis_sampah').addEventListener('change', function() {
    const kategori = this.value;
    const subKategoriSelect = document.getElementById('add_sub_kategori');
    
    // Clear sub kategori
    subKategoriSelect.innerHTML = '<option value="">Pilih Sub Kategori</option>';
    
    // Populate sub kategori
    if (kategori && subKategoriData[kategori]) {
        subKategoriData[kategori].forEach(sub => {
            const option = document.createElement('option');
            option.value = sub;
            option.textContent = sub;
            subKategoriSelect.appendChild(option);
        });
    }
});

// Auto fill nama jenis saat sub kategori dipilih
document.getElementById('add_sub_kategori').addEventListener('change', function() {
    const subKategori = this.value;
    if (subKategori) {
        document.getElementById('add_nama_jenis').value = subKategori;
    }
});
```

### 3. Validasi di Controller

```php
public function store()
{
    // Validasi jenis_sampah harus salah satu dari ENUM
    $validJenis = ['Plastik', 'Kertas', 'Logam', 'Organik', 'Residu'];
    if (!in_array($jenisSampah, $validJenis)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Kategori sampah tidak valid'
        ]);
    }
    
    // Check duplikasi berdasarkan nama_jenis (bukan jenis_sampah)
    $existing = $hargaModel->where('nama_jenis', $namaJenis)->first();
    if ($existing) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Nama jenis sampah sudah ada'
        ]);
    }
    
    // Insert data
    $data = [
        'jenis_sampah' => $jenisSampah,  // ✅ Nilai ENUM yang valid
        'nama_jenis' => $namaJenis,      // ✅ Nama lengkap/deskripsi
        // ...
    ];
}
```

---

## 🎯 Cara Kerja Solusi

### Contoh: Tambah "Kabel Elektronik"

**Langkah User:**

1. **Pilih Kategori Utama:** `Logam`
   - Field `jenis_sampah` akan diisi: `"Logam"` ✅

2. **Pilih Sub Kategori:** `Tembaga` (opsional)
   - Auto fill field `nama_jenis`: `"Tembaga"`

3. **Edit Nama Lengkap:** `Kabel Tembaga Bekas`
   - Field `nama_jenis` final: `"Kabel Tembaga Bekas"` ✅

4. **Submit Form**

**Data yang Tersimpan:**

```sql
INSERT INTO master_harga_sampah (
    jenis_sampah,  -- ✅ "Logam" (nilai ENUM valid)
    nama_jenis,    -- ✅ "Kabel Tembaga Bekas"
    harga_per_satuan,
    satuan,
    ...
) VALUES (
    'Logam',
    'Kabel Tembaga Bekas',
    4000,
    'kg',
    ...
);
```

**Hasil di Tabel:**

```
Jenis Sampah | Nama Lengkap           | Harga
-------------|------------------------|-------
Logam        | Kabel Tembaga Bekas    | Rp 4.000
```

✅ **Berhasil! Tidak ada field kosong!**

---

## 🧪 Testing

### Test Case 1: Tambah Data Plastik

**Input:**
- Kategori Utama: `Plastik`
- Sub Kategori: `Plastik PET (Botol Minuman)`
- Nama Lengkap: `Botol Plastik PET Bersih`
- Harga: `2000`
- Satuan: `kg`

**Expected Result:**
```
jenis_sampah: "Plastik" ✅
nama_jenis: "Botol Plastik PET Bersih" ✅
```

### Test Case 2: Tambah Data Kertas

**Input:**
- Kategori Utama: `Kertas`
- Sub Kategori: `Kertas HVS/Putih`
- Nama Lengkap: `Kertas HVS Bekas`
- Harga: `1500`
- Satuan: `kg`

**Expected Result:**
```
jenis_sampah: "Kertas" ✅
nama_jenis: "Kertas HVS Bekas" ✅
```

### Test Case 3: Tambah Data Logam (Kabel)

**Input:**
- Kategori Utama: `Logam`
- Sub Kategori: `Tembaga`
- Nama Lengkap: `Kabel Tembaga Bekas`
- Harga: `4000`
- Satuan: `kg`

**Expected Result:**
```
jenis_sampah: "Logam" ✅
nama_jenis: "Kabel Tembaga Bekas" ✅
```

### Test Case 4: Validasi Kategori Invalid

**Input:**
- Kategori Utama: `Elektronik` (tidak ada di ENUM)

**Expected Result:**
```
Error: "Kategori sampah tidak valid" ❌
Data tidak tersimpan ✅
```

---

## 🔧 Cara Fix Data Lama yang Kosong

Jika ada data lama dengan `jenis_sampah` kosong, Anda bisa:

### Opsi 1: Hapus Data Kosong (Recommended)

```sql
-- Cek data kosong
SELECT * FROM master_harga_sampah WHERE jenis_sampah = '' OR jenis_sampah IS NULL;

-- Hapus data kosong
DELETE FROM master_harga_sampah WHERE jenis_sampah = '' OR jenis_sampah IS NULL;
```

### Opsi 2: Update Manual

```sql
-- Update data "Kabel" menjadi kategori "Logam"
UPDATE master_harga_sampah 
SET jenis_sampah = 'Logam' 
WHERE nama_jenis LIKE '%Kabel%' AND (jenis_sampah = '' OR jenis_sampah IS NULL);

-- Update data "Elektronik" menjadi kategori "Residu"
UPDATE master_harga_sampah 
SET jenis_sampah = 'Residu' 
WHERE nama_jenis LIKE '%Elektronik%' AND (jenis_sampah = '' OR jenis_sampah IS NULL);
```

### Opsi 3: Gunakan Fitur Hapus di UI

1. Login sebagai admin
2. Buka Manajemen Sampah
3. Cari data dengan kolom "Jenis Sampah" kosong
4. Klik tombol **Hapus** (merah)
5. Tambah ulang dengan form yang sudah diperbaiki

---

## 📊 Perbandingan Before & After

### Before (Bug)

```
Form:
[Dropdown] Pilih Kategori: "Elektronik Kabel" ❌

Database:
jenis_sampah: "" (kosong) ❌
nama_jenis: "Kabel" ✅
```

### After (Fixed)

```
Form:
[Dropdown 1] Kategori Utama: "Logam" ✅
[Dropdown 2] Sub Kategori: "Tembaga" (opsional)
[Input] Nama Lengkap: "Kabel Tembaga Bekas" ✅

Database:
jenis_sampah: "Logam" ✅
nama_jenis: "Kabel Tembaga Bekas" ✅
```

---

## ✅ Kesimpulan

### Penyebab Bug:
- Form mengirim nilai yang tidak sesuai dengan ENUM database
- Tidak ada validasi di frontend dan backend

### Solusi:
1. ✅ Pisahkan kategori utama (ENUM) dan sub kategori (helper)
2. ✅ Validasi di frontend (JavaScript)
3. ✅ Validasi di backend (Controller)
4. ✅ Cek duplikasi berdasarkan `nama_jenis` (bukan `jenis_sampah`)

### Hasil:
- ✅ Field `jenis_sampah` selalu terisi dengan nilai ENUM yang valid
- ✅ Field `nama_jenis` terisi dengan deskripsi lengkap
- ✅ Tidak ada lagi data kosong
- ✅ User experience lebih baik dengan sub kategori helper

**Bug Fixed! 🎉**
