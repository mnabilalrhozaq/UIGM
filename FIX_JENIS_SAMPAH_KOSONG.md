# Fix: Jenis Sampah Kosong & Harga Tidak Auto-Calculate

## Masalah

### 1. Nama Jenis Sampah Kosong di Card
Di halaman user/TPS, card informasi harga sampah menampilkan nama kosong untuk jenis sampah yang baru ditambahkan admin.

**Contoh**:
```
┌─────────────────────┐
│ (KOSONG)            │  ← Seharusnya ada nama jenis
│ Kertas bekas        │
│ Harga: Rp 1.000/gram│
└─────────────────────┘
```

### 2. Harga Tidak Auto-Calculate
Saat user input data dengan jenis sampah baru, nilai ekonomis tidak terhitung otomatis.

## Penyebab

### Root Cause:
Field `jenis_sampah` di tabel `master_harga_sampah` kosong karena bug sebelumnya di form admin (sudah diperbaiki).

### Data di Database:
```sql
id | jenis_sampah | nama_jenis    | harga_per_satuan | satuan
---|--------------|---------------|------------------|-------
1  | Plastik      | Sampah Plastik| 5000            | kg
2  | Kertas       | Sampah Kertas | 3000            | kg
3  | (KOSONG)     | Kertas bekas  | 1000            | gram  ← MASALAH!
```

## Solusi

### Step 1: Fix Data yang Sudah Ada

Jalankan SQL untuk fix data yang bermasalah:

**File**: `FIX_EMPTY_JENIS_SAMPAH.sql`

#### Opsi A: Hapus Data Bermasalah (Recommended)
```sql
DELETE FROM master_harga_sampah 
WHERE jenis_sampah IS NULL 
   OR jenis_sampah = '' 
   OR TRIM(jenis_sampah) = '';
```

Kemudian admin tambah ulang jenis sampah dengan benar.

#### Opsi B: Update dari nama_jenis
```sql
UPDATE master_harga_sampah 
SET jenis_sampah = SUBSTRING_INDEX(nama_jenis, ' ', 2)
WHERE (jenis_sampah IS NULL OR jenis_sampah = '' OR TRIM(jenis_sampah) = '')
  AND nama_jenis IS NOT NULL 
  AND nama_jenis != '';
```

### Step 2: Tambah Ulang Jenis Sampah (Jika Pakai Opsi A)

1. Login sebagai admin
2. Buka menu "Manajemen Sampah"
3. Klik "Tambah Jenis Sampah"
4. **PENTING**: Pilih kategori dari dropdown (jangan kosong!)
5. Isi nama lengkap
6. Isi harga dan satuan
7. Simpan

### Step 3: Verifikasi

#### Cek Database:
```sql
SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, status_aktif
FROM master_harga_sampah
WHERE status_aktif = 1
ORDER BY id DESC;
```

**Hasil yang Benar**:
```
id | jenis_sampah  | nama_jenis    | harga_per_satuan | satuan
---|---------------|---------------|------------------|-------
1  | Plastik PET   | Botol Plastik | 3000            | kg
2  | Kertas HVS    | Kertas Putih  | 2000            | kg
3  | Kertas bekas  | Kertas bekas  | 1000            | gram
```

Semua field `jenis_sampah` harus terisi!

#### Cek di User/TPS:
1. Login sebagai user atau TPS
2. Buka menu "Data Sampah"
3. Lihat card "Informasi Harga Sampah"
4. ✅ Semua card harus menampilkan nama jenis sampah
5. ✅ Harga harus muncul dengan benar

#### Test Auto-Calculate:
1. Klik "Tambah Data Sampah"
2. Pilih kategori sampah (yang baru ditambahkan)
3. Input jumlah (misal: 500)
4. Pilih satuan (misal: gram)
5. ✅ Harga per satuan harus muncul otomatis
6. ✅ Total nilai harus terhitung otomatis

**Contoh**:
```
Kategori: Kertas bekas
Jumlah: 500
Satuan: gram

Harga per Satuan: Rp 1.000/gram
Konversi: 500 gram = 0.5 kg
Total Nilai: Rp 500  ← AUTO CALCULATE!
```

## Penjelasan Auto-Calculate

### Cara Kerja:

1. **User pilih kategori** → Dropdown load data dari `master_harga_sampah`
2. **Data attribute di option**:
```html
<option value="3" 
        data-harga="1000"
        data-satuan="gram"
        data-jenis="Kertas bekas"
        data-dapat-dijual="1">
    Kertas bekas
</option>
```

3. **JavaScript ambil data**:
```javascript
const hargaPerKg = parseFloat(selectedOption.getAttribute('data-harga'));
const satuanDefault = selectedOption.getAttribute('data-satuan');
const dapatDijual = selectedOption.getAttribute('data-dapat-dijual');
```

4. **Calculate nilai**:
```javascript
// Konversi ke kg
const jumlahKg = konversiKeKg(jumlah, satuan);

// Hitung nilai
if (dapatDijual && hargaPerKg > 0) {
    const total = hargaPerKg * jumlahKg;
    totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
}
```

### Syarat Auto-Calculate Berfungsi:

1. ✅ Field `jenis_sampah` di database TIDAK KOSONG
2. ✅ Field `harga_per_satuan` > 0
3. ✅ Field `dapat_dijual` = 1
4. ✅ Field `status_aktif` = 1
5. ✅ User input jumlah > 0
6. ✅ User pilih satuan

### Jika Tidak Auto-Calculate:

**Cek di Console Browser** (F12 → Console):
```javascript
// Lihat data yang di-load
console.log('Harga:', hargaPerKg);
console.log('Satuan:', satuanDefault);
console.log('Dapat Dijual:', dapatDijual);
console.log('Jumlah:', jumlah);
console.log('Jumlah KG:', jumlahKg);
```

**Kemungkinan Masalah**:
- ❌ `jenis_sampah` kosong → Data tidak ter-load dengan benar
- ❌ `harga_per_satuan` = 0 → Tidak ada harga
- ❌ `dapat_dijual` = 0 → Sampah tidak bisa dijual
- ❌ JavaScript error → Cek console

## Pencegahan

### Untuk Admin:

1. **SELALU pilih kategori dari dropdown** saat tambah jenis sampah
2. **JANGAN kosongkan** field kategori
3. **Verifikasi** data setelah simpan di tabel

### Untuk Developer:

1. ✅ Validasi `jenis_sampah` di controller (sudah ada)
2. ✅ Hidden field untuk `jenis_sampah` (sudah ditambahkan)
3. ✅ JavaScript update hidden field saat dropdown berubah (sudah ada)
4. ✅ Alert jika kategori kosong saat submit (sudah ada)

## Testing Checklist

### Test 1: Fix Data Lama
- [ ] Jalankan SQL fix
- [ ] Cek database, semua `jenis_sampah` terisi
- [ ] Refresh halaman user/TPS
- [ ] ✅ Semua card menampilkan nama jenis

### Test 2: Tambah Jenis Baru
- [ ] Login sebagai admin
- [ ] Tambah jenis sampah baru
- [ ] Pilih kategori dari dropdown
- [ ] Simpan
- [ ] Cek di tabel, `jenis_sampah` terisi
- [ ] ✅ Data muncul dengan benar

### Test 3: Auto-Calculate
- [ ] Login sebagai user/TPS
- [ ] Tambah data sampah
- [ ] Pilih jenis sampah baru
- [ ] Input jumlah
- [ ] Pilih satuan
- [ ] ✅ Harga muncul otomatis
- [ ] ✅ Total nilai terhitung otomatis

### Test 4: Berbagai Satuan
- [ ] Test dengan satuan: kg
- [ ] Test dengan satuan: gram
- [ ] Test dengan satuan: ton
- [ ] ✅ Konversi bekerja dengan benar
- [ ] ✅ Nilai terhitung akurat

## Kesimpulan

### Masalah:
- ❌ Jenis sampah kosong di card
- ❌ Harga tidak auto-calculate

### Penyebab:
- Field `jenis_sampah` kosong di database

### Solusi:
1. ✅ Fix data lama dengan SQL
2. ✅ Tambah ulang jenis sampah dengan benar
3. ✅ Verifikasi auto-calculate berfungsi

### Hasil:
- ✅ Semua card menampilkan nama jenis
- ✅ Harga auto-calculate saat input data
- ✅ Konversi satuan bekerja dengan benar
- ✅ Total nilai terhitung otomatis

**Bug sudah diperbaiki di code, tinggal fix data yang sudah ada di database!** 🎉
