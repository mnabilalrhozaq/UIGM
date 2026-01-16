# Update: Penambahan Satuan Gram

## Deskripsi
Menambahkan satuan "gram" (g) sebagai pilihan satuan untuk input data sampah di semua level pengguna (User, TPS/Pengelola, dan Admin).

## Perubahan yang Dilakukan

### 1. Admin - Manajemen Sampah
**File**: `app/Views/admin_pusat/manajemen_harga/index.php`

#### Form Tambah Jenis Sampah Baru:
```html
<select class="form-select" id="add_satuan" name="satuan" required>
    <option value="">Pilih Satuan</option>
    <option value="kg">Kilogram (kg)</option>
    <option value="gram">Gram (g)</option>  <!-- BARU -->
    <option value="ton">Ton</option>
    <option value="liter">Liter</option>
    <option value="pcs">Pieces (pcs)</option>
    <option value="karung">Karung</option>
</select>
```

#### Form Edit Harga Sampah:
```html
<select class="form-select" id="edit_satuan" name="satuan" required>
    <option value="kg">Kilogram (kg)</option>
    <option value="gram">Gram (g)</option>  <!-- BARU -->
    <option value="ton">Ton</option>
    <option value="liter">Liter</option>
    <option value="pcs">Pieces (pcs)</option>
    <option value="karung">Karung</option>
</select>
```

### 2. User - Input Data Sampah
**File**: `app/Views/user/waste.php`

#### Form Tambah Data Sampah:
```html
<select class="form-select" id="satuan" name="satuan" required>
    <option value="">Pilih Satuan</option>
    <option value="kg">Kilogram (kg)</option>
    <option value="ton">Ton</option>
    <option value="gram">Gram (g)</option>  <!-- SUDAH ADA -->
    <option value="liter">Liter (L)</option>
    <option value="pcs">Pieces (pcs)</option>
    <option value="karung">Karung</option>
</select>
```

#### Form Edit Data Sampah:
```html
<select class="form-select" id="edit_unit_id" name="unit_id" required>
    <option value="kg">Kilogram (kg)</option>
    <option value="ton">Ton</option>
    <option value="gram">Gram</option>  <!-- SUDAH ADA -->
    <option value="liter">Liter</option>
    <option value="pcs">Pieces (pcs)</option>
    <option value="karung">Karung</option>
</select>
```

### 3. TPS/Pengelola - Input Data Sampah
**File**: `app/Views/pengelola_tps/waste.php`

#### Form Tambah Data Sampah:
```html
<select class="form-select" id="satuan" name="satuan" required>
    <option value="">Pilih Satuan</option>
    <option value="kg">Kilogram (kg)</option>
    <option value="ton">Ton</option>
    <option value="gram">Gram (g)</option>  <!-- SUDAH ADA -->
    <option value="liter">Liter (L)</option>
    <option value="pcs">Pieces (pcs)</option>
    <option value="karung">Karung</option>
</select>
```

## Konversi Satuan ke Kilogram

Semua sistem sudah memiliki fungsi konversi otomatis dari berbagai satuan ke kilogram (kg) sebagai satuan standar penyimpanan:

```javascript
function konversiKeKg(jumlah, satuan) {
    const konversi = {
        'kg': 1,
        'ton': 1000,
        'gram': 0.001,      // 1 gram = 0.001 kg
        'liter': 1,         // Asumsi 1 liter = 1 kg
        'pcs': 0.1,         // Asumsi 1 pcs = 0.1 kg
        'karung': 25        // Asumsi 1 karung = 25 kg
    };
    return jumlah * (konversi[satuan] || 1);
}
```

### Contoh Konversi:
- 1000 gram = 1 kg
- 500 gram = 0.5 kg
- 250 gram = 0.25 kg
- 100 gram = 0.1 kg

## Fitur Auto-Calculate

Sistem akan otomatis menghitung:

1. **Konversi ke Kilogram**
   - Input: 500 gram
   - Konversi: 0.5 kg
   - Display: "500 gram = 0.5 kg"

2. **Perhitungan Nilai Ekonomis**
   - Harga: Rp 5.000/kg
   - Jumlah: 500 gram (0.5 kg)
   - Total: Rp 2.500

3. **Info Konversi Real-time**
   - Muncul otomatis saat user mengubah jumlah atau satuan
   - Membantu user memahami konversi yang terjadi

## Use Cases

### Use Case 1: Admin Menambah Jenis Sampah dengan Satuan Gram
```
1. Admin buka Manajemen Sampah
2. Klik "Tambah Jenis Sampah"
3. Isi form:
   - Jenis Sampah: "Plastik Kecil"
   - Nama Lengkap: "Sampah Plastik Ukuran Kecil"
   - Harga per Satuan: 10000
   - Satuan: Gram (g)  ← PILIH GRAM
   - Dapat Dijual: ✓
   - Status Aktif: ✓
4. Simpan
5. Sistem menyimpan dengan satuan "gram"
```

### Use Case 2: User Input Data Sampah dalam Gram
```
1. User buka Manajemen Sampah
2. Klik "Tambah Data Sampah"
3. Pilih kategori: "Plastik Kecil"
4. Input jumlah: 500
5. Pilih satuan: Gram (g)  ← PILIH GRAM
6. Sistem otomatis:
   - Konversi: 500 gram = 0.5 kg
   - Hitung nilai: Rp 10.000/kg × 0.5 kg = Rp 5.000
   - Tampilkan info konversi
7. User simpan data
8. Data tersimpan dengan berat_kg = 0.5
```

### Use Case 3: TPS Input Data Sampah dalam Gram
```
1. Pengelola TPS buka Manajemen Sampah TPS
2. Klik "Tambah Data Sampah"
3. Pilih kategori sampah
4. Input jumlah: 250
5. Pilih satuan: Gram (g)  ← PILIH GRAM
6. Sistem konversi: 250 gram = 0.25 kg
7. Hitung nilai ekonomis otomatis
8. Simpan data
```

## Keuntungan Penambahan Satuan Gram

### 1. Fleksibilitas Input
- User dapat input sampah dalam jumlah kecil
- Cocok untuk sampah dengan berat ringan
- Lebih akurat untuk sampah bernilai tinggi per gram

### 2. Presisi Data
- Menghindari pembulatan yang tidak akurat
- Data lebih detail dan presisi
- Memudahkan tracking sampah kecil

### 3. User Experience
- User tidak perlu konversi manual
- Sistem otomatis konversi ke kg
- Info konversi ditampilkan real-time

### 4. Konsistensi
- Semua level user (User, TPS, Admin) punya opsi yang sama
- Standar satuan konsisten di seluruh sistem
- Database tetap menyimpan dalam kg untuk konsistensi

## Database Storage

Meskipun input bisa dalam gram, data tetap disimpan dalam kilogram (kg) di database:

```sql
-- Contoh data tersimpan
INSERT INTO waste (
    jenis_sampah,
    berat,           -- Disimpan dalam kg
    satuan_input,    -- Satuan yang dipilih user
    nilai_ekonomis
) VALUES (
    'Plastik Kecil',
    0.5,            -- 500 gram dikonversi ke 0.5 kg
    'gram',         -- User input dalam gram
    5000            -- Rp 10.000/kg × 0.5 kg
);
```

## Testing Checklist

### Admin:
- [ ] Tambah jenis sampah baru dengan satuan gram
- [ ] Edit jenis sampah existing, ubah satuan ke gram
- [ ] Verifikasi data tersimpan dengan benar

### User:
- [ ] Input data sampah dengan satuan gram
- [ ] Verifikasi konversi ke kg benar
- [ ] Verifikasi perhitungan nilai ekonomis benar
- [ ] Edit data sampah, ubah satuan ke gram
- [ ] Cek info konversi muncul dengan benar

### TPS:
- [ ] Input data sampah dengan satuan gram
- [ ] Verifikasi konversi otomatis
- [ ] Verifikasi nilai ekonomis terhitung benar
- [ ] Edit data dengan satuan gram

### Perhitungan:
- [ ] Test: 1000 gram = 1 kg
- [ ] Test: 500 gram = 0.5 kg
- [ ] Test: 250 gram = 0.25 kg
- [ ] Test: 100 gram = 0.1 kg
- [ ] Test: Nilai ekonomis dengan gram
- [ ] Test: Mixed satuan dalam satu session

## Catatan Penting

1. **Konversi Otomatis**: Sistem selalu konversi ke kg sebelum menyimpan ke database
2. **Display**: User melihat satuan yang mereka pilih, tapi sistem simpan dalam kg
3. **Backward Compatible**: Data lama tetap valid, tidak perlu migrasi
4. **Konsistensi**: Semua perhitungan menggunakan kg sebagai base unit
5. **Precision**: JavaScript number precision cukup untuk konversi gram ke kg

## File yang Dimodifikasi

1. ✅ `app/Views/admin_pusat/manajemen_harga/index.php`
   - Form tambah jenis sampah: Tambah option gram
   - Form edit harga: Tambah option gram

2. ✅ `app/Views/user/waste.php`
   - Form tambah data: Sudah ada gram
   - Form edit data: Sudah ada gram
   - JavaScript konversi: Sudah support gram

3. ✅ `app/Views/pengelola_tps/waste.php`
   - Form tambah data: Sudah ada gram
   - JavaScript konversi: Sudah support gram

## Tidak Perlu Perubahan

- ❌ Database schema (tetap simpan dalam kg)
- ❌ Controller logic (sudah handle konversi)
- ❌ Model (tidak perlu update)
- ❌ Service layer (sudah generic)
- ❌ API endpoints (tidak berubah)

## Kesimpulan

Penambahan satuan gram telah berhasil diimplementasikan di semua level input (Admin, User, TPS). Sistem tetap menyimpan data dalam kilogram untuk konsistensi, namun memberikan fleksibilitas kepada user untuk input dalam satuan yang mereka inginkan. Konversi dilakukan otomatis dan transparan kepada user.
