# Standardisasi Urutan Satuan di Semua Role

## Tujuan
Menyamakan urutan pilihan satuan di semua role (Admin, User, TPS) untuk konsistensi user experience.

## Masalah Sebelumnya

### Urutan Berbeda-beda:

**Admin** (Manajemen Sampah):
```
1. Kilogram (kg)
2. Gram (g)        ← Posisi 2
3. Ton
4. Liter
5. Pieces (pcs)
6. Karung
```

**User** (Input Data Sampah):
```
1. Kilogram (kg)
2. Ton             ← Posisi 2
3. Gram (g)        ← Posisi 3
4. Liter (L)
5. Pieces (pcs)
6. Karung
```

**TPS** (Input Data Sampah):
```
1. Kilogram (kg)
2. Ton             ← Posisi 2
3. Gram (g)        ← Posisi 3
4. Liter (L)
5. Pieces (pcs)
6. Karung
```

### Masalah:
- ❌ Urutan tidak konsisten antar role
- ❌ User bingung karena posisi "Gram" berbeda
- ❌ Tidak ada standar yang jelas

## Solusi: Urutan Standar

### Urutan Baru (Semua Role):
```
1. Kilogram (kg)   ← Satuan paling umum
2. Gram (g)        ← Satuan kecil, turunan dari kg
3. Ton             ← Satuan besar, kelipatan dari kg
4. Liter           ← Satuan volume
5. Pieces (pcs)    ← Satuan jumlah
6. Karung          ← Satuan kemasan
```

### Alasan Urutan:
1. **Kilogram (kg)** - Satuan standar, paling sering digunakan
2. **Gram (g)** - Turunan kg (1000 gram = 1 kg), untuk sampah kecil
3. **Ton** - Kelipatan kg (1 ton = 1000 kg), untuk sampah besar
4. **Liter** - Satuan volume, untuk sampah cair/volume
5. **Pieces (pcs)** - Satuan jumlah, untuk item individual
6. **Karung** - Satuan kemasan, untuk bulk

## Perubahan yang Dilakukan

### 1. User - Form Tambah Data Sampah
**File**: `app/Views/user/waste.php`

**Sebelum**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="ton">Ton</option>
<option value="gram">Gram (g)</option>
```

**Sesudah**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="gram">Gram (g)</option>
<option value="ton">Ton</option>
```

### 2. User - Form Edit Data Sampah
**File**: `app/Views/user/waste.php`

**Sebelum**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="ton">Ton</option>
<option value="gram">Gram</option>
```

**Sesudah**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="gram">Gram (g)</option>
<option value="ton">Ton</option>
```

### 3. TPS - Form Tambah Data Sampah
**File**: `app/Views/pengelola_tps/waste.php`

**Sebelum**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="ton">Ton</option>
<option value="gram">Gram (g)</option>
```

**Sesudah**:
```html
<option value="kg">Kilogram (kg)</option>
<option value="gram">Gram (g)</option>
<option value="ton">Ton</option>
```

### 4. Admin - Sudah Benar
**File**: `app/Views/admin_pusat/manajemen_harga/index.php`

Tidak perlu diubah, sudah mengikuti urutan standar:
```html
<option value="kg">Kilogram (kg)</option>
<option value="gram">Gram (g)</option>
<option value="ton">Ton</option>
<option value="liter">Liter</option>
<option value="pcs">Pieces (pcs)</option>
<option value="karung">Karung</option>
```

## Konsistensi Label

### Format Label Standar:
```
Nama Satuan (Simbol)
```

### Contoh:
- ✅ Kilogram (kg)
- ✅ Gram (g)
- ✅ Ton
- ✅ Liter (L) atau Liter
- ✅ Pieces (pcs)
- ✅ Karung

### Catatan:
- User form edit: "Gram (g)" ← Sudah disamakan
- Semua form lain: "Gram (g)" ← Konsisten

## Perbandingan Sebelum vs Sesudah

### Admin (Manajemen Sampah)
| Sebelum | Sesudah | Status |
|---------|---------|--------|
| kg → gram → ton | kg → gram → ton | ✅ Tidak berubah |

### User (Input Data)
| Sebelum | Sesudah | Status |
|---------|---------|--------|
| kg → ton → gram | kg → gram → ton | ✅ Diubah |

### User (Edit Data)
| Sebelum | Sesudah | Status |
|---------|---------|--------|
| kg → ton → gram | kg → gram → ton | ✅ Diubah |

### TPS (Input Data)
| Sebelum | Sesudah | Status |
|---------|---------|--------|
| kg → ton → gram | kg → gram → ton | ✅ Diubah |

## Keuntungan Standardisasi

### 1. Konsistensi User Experience
- ✅ Semua role melihat urutan yang sama
- ✅ Muscle memory user terjaga
- ✅ Mengurangi kebingungan

### 2. Logika Urutan
- ✅ Dari kecil ke besar: gram → kg → ton
- ✅ Satuan berat dulu, volume kemudian
- ✅ Satuan umum di atas, khusus di bawah

### 3. Maintenance
- ✅ Mudah maintain karena konsisten
- ✅ Mudah dokumentasi
- ✅ Mudah training user baru

### 4. Profesionalitas
- ✅ Tampilan lebih profesional
- ✅ Sistem terlihat lebih matang
- ✅ User trust meningkat

## Testing Checklist

### Test 1: Admin
- [ ] Login sebagai admin
- [ ] Buka Manajemen Sampah
- [ ] Klik "Tambah Jenis Sampah"
- [ ] Cek dropdown satuan
- [ ] ✅ Urutan: kg → gram → ton → liter → pcs → karung

### Test 2: User - Tambah Data
- [ ] Login sebagai user
- [ ] Buka Manajemen Sampah
- [ ] Klik "Tambah Data Sampah"
- [ ] Cek dropdown satuan
- [ ] ✅ Urutan: kg → gram → ton → liter → pcs → karung

### Test 3: User - Edit Data
- [ ] Login sebagai user
- [ ] Edit data sampah existing
- [ ] Cek dropdown satuan
- [ ] ✅ Urutan: kg → gram → ton → liter → pcs → karung
- [ ] ✅ Label: "Gram (g)" bukan "Gram"

### Test 4: TPS
- [ ] Login sebagai pengelola TPS
- [ ] Buka Manajemen Sampah TPS
- [ ] Klik "Tambah Data Sampah"
- [ ] Cek dropdown satuan
- [ ] ✅ Urutan: kg → gram → ton → liter → pcs → karung

### Test 5: Fungsionalitas
- [ ] Test input dengan satuan gram
- [ ] Test input dengan satuan ton
- [ ] Test konversi ke kg
- [ ] ✅ Semua fungsi tetap bekerja normal

## Visual Comparison

### Sebelum (Tidak Konsisten):
```
Admin:  kg → gram → ton → liter → pcs → karung
User:   kg → ton → gram → liter → pcs → karung  ❌
TPS:    kg → ton → gram → liter → pcs → karung  ❌
```

### Sesudah (Konsisten):
```
Admin:  kg → gram → ton → liter → pcs → karung  ✅
User:   kg → gram → ton → liter → pcs → karung  ✅
TPS:    kg → gram → ton → liter → pcs → karung  ✅
```

## Files Modified

1. ✅ `app/Views/user/waste.php`
   - Form tambah: Ubah urutan satuan
   - Form edit: Ubah urutan satuan dan label

2. ✅ `app/Views/pengelola_tps/waste.php`
   - Form tambah: Ubah urutan satuan

3. ✅ `app/Views/admin_pusat/manajemen_harga/index.php`
   - Tidak diubah (sudah benar)

## Impact Analysis

| Aspek | Sebelum | Sesudah |
|-------|---------|---------|
| Konsistensi | ❌ Berbeda-beda | ✅ Sama semua |
| User Confusion | ⚠️ Tinggi | ✅ Rendah |
| Maintenance | ⚠️ Sulit | ✅ Mudah |
| Profesionalitas | ⚠️ Kurang | ✅ Baik |
| Training Effort | ⚠️ Tinggi | ✅ Rendah |

## Backward Compatibility

### Data Existing:
- ✅ Tidak terpengaruh
- ✅ Tetap valid
- ✅ Tidak perlu migrasi

### Fungsionalitas:
- ✅ Konversi tetap bekerja
- ✅ Perhitungan tetap akurat
- ✅ Validasi tetap jalan

### User Experience:
- ✅ Tidak ada breaking changes
- ✅ Hanya urutan yang berubah
- ✅ Semua satuan tetap tersedia

## Kesimpulan

Standardisasi urutan satuan telah berhasil dilakukan di semua role (Admin, User, TPS). Urutan baru mengikuti logika: **kg → gram → ton → liter → pcs → karung**, yang lebih intuitif dan konsisten.

### Urutan Standar Final:
1. **Kilogram (kg)** - Satuan standar
2. **Gram (g)** - Satuan kecil
3. **Ton** - Satuan besar
4. **Liter** - Satuan volume
5. **Pieces (pcs)** - Satuan jumlah
6. **Karung** - Satuan kemasan

Perubahan ini meningkatkan konsistensi, mengurangi kebingungan user, dan membuat sistem lebih profesional.
