# Perubahan: Jenis Sampah Dropdown dengan Kategori Lengkap

## Tujuan
1. Admin memilih jenis sampah dari dropdown (bukan ketik manual)
2. Menambahkan jenis sampah yang lebih detail dan lengkap
3. Jenis sampah yang ditambahkan admin otomatis tersedia untuk User dan TPS

## Perubahan yang Dilakukan

### 1. Admin - Form Tambah Jenis Sampah

#### Sebelum (Input Text):
```html
<input type="text" name="jenis_sampah" placeholder="Contoh: Plastik, Kertas, Logam">
```

#### Sesudah (Dropdown dengan Kategori):
```html
<select name="kategori_sampah">
    <optgroup label="Plastik">
        <option value="Plastik PET">Plastik PET (Botol Minuman)</option>
        <option value="Plastik HDPE">Plastik HDPE (Botol Detergen)</option>
        ...
    </optgroup>
    <optgroup label="Kertas">
        <option value="Kertas HVS">Kertas HVS/Putih</option>
        ...
    </optgroup>
    ...
</select>
```

### 2. Kategori Jenis Sampah Lengkap

#### Plastik (7 jenis):
1. **Plastik PET** - Botol Minuman
2. **Plastik HDPE** - Botol Detergen, Shampo
3. **Plastik PVC** - Pipa, Kabel
4. **Plastik LDPE** - Kantong Plastik
5. **Plastik PP** - Tutup Botol, Sedotan
6. **Plastik PS** - Styrofoam
7. **Plastik Lainnya** - Plastik campuran

#### Kertas (6 jenis):
1. **Kertas HVS** - Kertas Putih
2. **Kertas Koran** - Koran bekas
3. **Kertas Kardus** - Kardus/Karton
4. **Kertas Majalah** - Kertas Berwarna
5. **Kertas Duplex** - Kertas kemasan
6. **Kertas Arsip** - Dokumen lama

#### Logam (6 jenis):
1. **Logam Besi** - Besi/Baja
2. **Logam Aluminium** - Kaleng, Foil
3. **Logam Tembaga** - Tembaga murni
4. **Logam Kuningan** - Kuningan/Brass
5. **Logam Seng** - Seng/Zinc
6. **Logam Campuran** - Logam tidak teridentifikasi

#### Kaca (3 jenis):
1. **Kaca Bening** - Kaca transparan
2. **Kaca Berwarna** - Kaca gelap
3. **Kaca Botol** - Botol kaca bekas

#### Organik (4 jenis):
1. **Organik Sisa Makanan** - Sisa makanan
2. **Organik Daun** - Daun kering
3. **Organik Ranting** - Ranting/Kayu
4. **Organik Rumput** - Rumput pangkasan

#### Elektronik (4 jenis):
1. **Elektronik Kabel** - Kabel bekas
2. **Elektronik PCB** - PCB/Komponen
3. **Elektronik Baterai** - Baterai bekas
4. **Elektronik Lampu** - Lampu bekas

#### Tekstil (3 jenis):
1. **Tekstil Kain** - Kain/Pakaian
2. **Tekstil Sepatu** - Sepatu/Sandal
3. **Tekstil Tas** - Tas bekas

#### Lainnya (5 jenis):
1. **Karet** - Karet bekas
2. **Ban** - Ban kendaraan
3. **Minyak Jelantah** - Minyak goreng bekas
4. **B3** - Limbah Berbahaya
5. **Residu** - Tidak terpilah

**Total: 38 jenis sampah detail**

### 3. Flow Sistem

```
┌─────────────────────────────────────────────────────────┐
│ ADMIN                                                    │
├─────────────────────────────────────────────────────────┤
│ 1. Buka Manajemen Sampah                                │
│ 2. Klik "Tambah Jenis Sampah"                           │
│ 3. Pilih dari dropdown kategori (38 pilihan)            │
│ 4. Isi nama lengkap/deskripsi                           │
│ 5. Set harga per satuan                                 │
│ 6. Pilih satuan (kg, gram, ton, dll)                    │
│ 7. Set dapat dijual (Ya/Tidak)                          │
│ 8. Simpan                                                │
│                                                          │
│ ↓ Data tersimpan ke database                            │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│ DATABASE: master_harga_sampah                           │
├─────────────────────────────────────────────────────────┤
│ - id                                                     │
│ - jenis_sampah (dari dropdown)                          │
│ - nama_jenis (deskripsi lengkap)                        │
│ - harga_per_satuan                                      │
│ - satuan                                                 │
│ - dapat_dijual                                          │
│ - status_aktif                                          │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│ USER & TPS                                              │
├─────────────────────────────────────────────────────────┤
│ 1. Buka Input Data Sampah                               │
│ 2. Dropdown kategori otomatis terisi dari database      │
│ 3. Pilih jenis sampah yang sudah ditambahkan admin      │
│ 4. Input jumlah dan satuan                              │
│ 5. Sistem auto-calculate nilai ekonomis                 │
│ 6. Simpan data                                          │
└─────────────────────────────────────────────────────────┘
```

### 4. Keuntungan Sistem Baru

#### Untuk Admin:
- ✅ Tidak perlu ketik manual (mengurangi typo)
- ✅ Pilihan terstandarisasi
- ✅ Lebih cepat input data
- ✅ Kategori terorganisir dengan baik

#### Untuk User & TPS:
- ✅ Otomatis dapat jenis sampah baru
- ✅ Tidak perlu update manual
- ✅ Konsistensi data terjaga
- ✅ Dropdown sudah terisi otomatis

#### Untuk Sistem:
- ✅ Data lebih konsisten
- ✅ Tidak ada duplikasi nama
- ✅ Mudah maintenance
- ✅ Laporan lebih akurat

### 5. Contoh Harga per Kategori

| Kategori | Jenis | Harga/kg | Dapat Dijual |
|----------|-------|----------|--------------|
| Plastik | PET | Rp 3.000 | ✅ Ya |
| Plastik | HDPE | Rp 2.500 | ✅ Ya |
| Plastik | LDPE | Rp 1.000 | ✅ Ya |
| Kertas | HVS | Rp 2.000 | ✅ Ya |
| Kertas | Kardus | Rp 1.800 | ✅ Ya |
| Logam | Aluminium | Rp 8.000 | ✅ Ya |
| Logam | Tembaga | Rp 50.000 | ✅ Ya |
| Kaca | Bening | Rp 500 | ✅ Ya |
| Organik | Sisa Makanan | Rp 0 | ❌ Tidak |
| Elektronik | Kabel | Rp 15.000 | ✅ Ya |
| Tekstil | Kain | Rp 1.000 | ✅ Ya |
| Lainnya | Minyak Jelantah | Rp 3.000/L | ✅ Ya |

### 6. File yang Dimodifikasi

#### 1. View Admin
**File**: `app/Views/admin_pusat/manajemen_harga/index.php`

**Perubahan**:
- Input text → Dropdown dengan optgroup
- Tambah 38 pilihan jenis sampah
- JavaScript auto-fill jenis_sampah dari dropdown

#### 2. SQL Insert
**File**: `INSERT_JENIS_SAMPAH_LENGKAP.sql`

**Isi**:
- 38 jenis sampah detail
- Harga per kategori
- Status dapat dijual
- Deskripsi lengkap

#### 3. Controller (Tidak Perlu Diubah)
**File**: `app/Controllers/Admin/Harga.php`

Sudah support karena:
- Menerima `jenis_sampah` dari POST
- Validasi sudah ada
- Insert ke database sudah benar

#### 4. User & TPS View (Tidak Perlu Diubah)
**File**: 
- `app/Views/user/waste.php`
- `app/Views/pengelola_tps/waste.php`

Sudah support karena:
- Menggunakan `foreach ($categories as $category)`
- Data diambil dari database
- Otomatis update saat ada data baru

### 7. Cara Implementasi

#### Step 1: Update View Admin
```bash
# File sudah diupdate: app/Views/admin_pusat/manajemen_harga/index.php
```

#### Step 2: Insert Data Jenis Sampah
```sql
# Jalankan file SQL
mysql -u username -p database_name < INSERT_JENIS_SAMPAH_LENGKAP.sql
```

Atau via phpMyAdmin:
1. Buka phpMyAdmin
2. Pilih database
3. Tab SQL
4. Copy-paste isi file `INSERT_JENIS_SAMPAH_LENGKAP.sql`
5. Execute

#### Step 3: Test
1. Login sebagai admin
2. Buka Manajemen Sampah
3. Klik "Tambah Jenis Sampah"
4. Pilih dari dropdown
5. Simpan
6. Login sebagai user/TPS
7. Cek dropdown kategori sudah terisi

### 8. Testing Checklist

#### Test Admin:
- [ ] Dropdown kategori muncul dengan 38 pilihan
- [ ] Optgroup terorganisir (Plastik, Kertas, dll)
- [ ] Pilih kategori → jenis_sampah terisi otomatis
- [ ] Simpan data berhasil
- [ ] Data muncul di tabel

#### Test User:
- [ ] Login sebagai user
- [ ] Buka Input Data Sampah
- [ ] Dropdown kategori terisi otomatis
- [ ] Pilih jenis sampah yang baru ditambahkan admin
- [ ] Harga dan satuan muncul otomatis
- [ ] Simpan data berhasil

#### Test TPS:
- [ ] Login sebagai TPS
- [ ] Buka Input Data Sampah
- [ ] Dropdown kategori terisi otomatis
- [ ] Pilih jenis sampah yang baru ditambahkan admin
- [ ] Simpan data berhasil

#### Test Sinkronisasi:
- [ ] Admin tambah jenis sampah baru
- [ ] Refresh halaman user → jenis baru muncul
- [ ] Refresh halaman TPS → jenis baru muncul
- [ ] Data konsisten di semua role

### 9. Contoh Penggunaan

#### Scenario 1: Admin Tambah Plastik PET
```
1. Admin login
2. Buka Manajemen Sampah
3. Klik "Tambah Jenis Sampah"
4. Pilih dropdown: Plastik → Plastik PET (Botol Minuman)
5. Nama lengkap: "Botol Plastik PET Bersih"
6. Harga: 3000
7. Satuan: kg
8. Dapat dijual: ✓
9. Simpan
✅ Data tersimpan dengan jenis_sampah = "Plastik PET"
```

#### Scenario 2: User Input Data
```
1. User login
2. Buka Manajemen Sampah
3. Klik "Tambah Data Sampah"
4. Dropdown kategori: Plastik PET (Botol Minuman) - Rp 3.000/kg
5. Jumlah: 10
6. Satuan: kg
7. Sistem hitung: 10 kg × Rp 3.000 = Rp 30.000
8. Simpan
✅ Data tersimpan dengan nilai ekonomis Rp 30.000
```

### 10. Maintenance

#### Menambah Jenis Baru:
Admin dapat menambah jenis sampah baru kapan saja melalui form "Tambah Jenis Sampah"

#### Mengubah Harga:
Admin dapat edit harga melalui tombol edit di tabel

#### Menonaktifkan Jenis:
Admin dapat toggle status aktif untuk menonaktifkan jenis sampah tertentu

### 11. Keamanan

- ✅ Validasi di model tetap berjalan
- ✅ CSRF protection aktif
- ✅ Session validation
- ✅ SQL injection prevention (Query Builder)
- ✅ XSS protection (htmlspecialchars)

### 12. Performance

- ✅ Dropdown di-render sekali saat page load
- ✅ Data di-cache di browser
- ✅ Query database optimal (index pada jenis_sampah)
- ✅ Tidak ada AJAX call berulang

### 13. Backward Compatibility

- ✅ Data existing tetap valid
- ✅ Tidak perlu migrasi data
- ✅ User & TPS tidak perlu update
- ✅ API tidak berubah

## Kesimpulan

Sistem baru menggunakan dropdown dengan 38 jenis sampah yang terorganisir dalam 8 kategori utama. Admin tidak perlu ketik manual, dan jenis sampah yang ditambahkan otomatis tersedia untuk User dan TPS. Sistem lebih konsisten, mudah maintenance, dan data lebih akurat.

### Kategori Utama:
1. **Plastik** (7 jenis)
2. **Kertas** (6 jenis)
3. **Logam** (6 jenis)
4. **Kaca** (3 jenis)
5. **Organik** (4 jenis)
6. **Elektronik** (4 jenis)
7. **Tekstil** (3 jenis)
8. **Lainnya** (5 jenis)

**Total: 38 jenis sampah detail dan terstandarisasi**
