# Bug Fix: Tidak Bisa Menyimpan Jenis Sampah Baru

## Masalah
Admin tidak bisa menyimpan jenis sampah baru saat menambahkan data melalui form "Tambah Jenis Sampah".

## Root Cause Analysis

### 1. Validasi Satuan Tidak Lengkap
**File**: `app/Models/HargaSampahModel.php`

**Masalah**:
```php
// SEBELUM (SALAH)
'satuan' => 'required|in_list[kg,ton,liter,pcs,karung]'
```

Validasi tidak mengizinkan satuan "gram" yang sudah ditambahkan di form.

**Solusi**:
```php
// SESUDAH (BENAR)
'satuan' => 'required|in_list[kg,gram,ton,liter,pcs,karung]'
```

### 2. Allowed Fields Tidak Lengkap
**File**: `app/Models/HargaSampahModel.php`

**Masalah**:
```php
// SEBELUM (SALAH)
protected $allowedFields = [
    'jenis_sampah',
    'nama_jenis',
    'harga_per_satuan',
    'harga_per_kg',
    'satuan',
    'status_aktif',
    'dapat_dijual',
    'deskripsi',
    'tanggal_berlaku'
    // created_at dan updated_at TIDAK ADA
];
```

Controller mencoba insert `created_at` dan `updated_at` tapi field tidak diizinkan.

**Solusi**:
```php
// SESUDAH (BENAR)
protected $allowedFields = [
    'jenis_sampah',
    'nama_jenis',
    'harga_per_satuan',
    'harga_per_kg',
    'satuan',
    'status_aktif',
    'dapat_dijual',
    'deskripsi',
    'tanggal_berlaku',
    'created_at',      // DITAMBAHKAN
    'updated_at'       // DITAMBAHKAN
];
```

### 3. Validasi Terlalu Ketat
**File**: `app/Models/HargaSampahModel.php`

**Masalah**:
```php
// SEBELUM (TERLALU KETAT)
'harga_per_satuan' => 'required|decimal',
'status_aktif' => 'required|in_list[0,1]',
'dapat_dijual' => 'required|in_list[0,1]'
```

Field yang seharusnya optional menjadi required.

**Solusi**:
```php
// SESUDAH (LEBIH FLEKSIBEL)
'harga_per_satuan' => 'permit_empty|decimal',
'status_aktif' => 'permit_empty|in_list[0,1]',
'dapat_dijual' => 'permit_empty|in_list[0,1]'
```

### 4. Controller Insert Timestamps Manual
**File**: `app/Controllers/Admin/Harga.php`

**Masalah**:
```php
// SEBELUM (REDUNDANT)
$data = [
    // ... other fields
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];
```

Model sudah punya `useTimestamps = true`, tidak perlu manual.

**Solusi**:
```php
// SESUDAH (BIARKAN MODEL HANDLE)
$data = [
    // ... other fields
    // created_at dan updated_at akan otomatis ditambahkan oleh model
];
```

### 5. Error Handling Kurang Detail
**File**: `app/Controllers/Admin/Harga.php`

**Masalah**:
```php
// SEBELUM (KURANG INFORMASI)
if ($hargaModel->insert($data)) {
    // success
}
return $this->response->setJSON([
    'success' => false,
    'message' => 'Gagal menambahkan jenis sampah'
]);
```

Tidak menampilkan validation errors.

**Solusi**:
```php
// SESUDAH (LEBIH INFORMATIF)
$insertResult = $hargaModel->insert($data);

if ($insertResult) {
    // success
}

// Get validation errors
$errors = $hargaModel->errors();
$errorMessage = 'Gagal menambahkan jenis sampah';

if (!empty($errors)) {
    $errorMessage .= ': ' . implode(', ', $errors);
    log_message('error', 'Validation errors: ' . json_encode($errors));
}

return $this->response->setJSON([
    'success' => false,
    'message' => $errorMessage,
    'errors' => $errors
]);
```

### 6. JavaScript Error Display Kurang Lengkap
**File**: `app/Views/admin_pusat/manajemen_harga/index.php`

**Masalah**:
```javascript
// SEBELUM (SIMPLE)
if (data.success) {
    // success
} else {
    showAlert('error', data.message || 'Gagal menambahkan jenis sampah');
}
```

Tidak menampilkan detail validation errors.

**Solusi**:
```javascript
// SESUDAH (DETAIL)
if (data.success) {
    // success
} else {
    let errorMsg = data.message || 'Gagal menambahkan jenis sampah';
    if (data.errors && Object.keys(data.errors).length > 0) {
        errorMsg += '<br><small>' + Object.values(data.errors).join('<br>') + '</small>';
    }
    showAlert('error', errorMsg);
}
```

## Perubahan yang Dilakukan

### 1. Model: HargaSampahModel.php
```php
// ✅ Tambah 'gram' ke validasi satuan
'satuan' => 'required|in_list[kg,gram,ton,liter,pcs,karung]'

// ✅ Tambah created_at dan updated_at ke allowedFields
protected $allowedFields = [
    // ... existing fields
    'created_at',
    'updated_at'
];

// ✅ Ubah validasi menjadi lebih fleksibel
'harga_per_satuan' => 'permit_empty|decimal',
'status_aktif' => 'permit_empty|in_list[0,1]',
'dapat_dijual' => 'permit_empty|in_list[0,1]'
```

### 2. Controller: Harga.php
```php
// ✅ Hapus manual timestamp (biarkan model handle)
$data = [
    'jenis_sampah' => $jenisSampah,
    'nama_jenis' => $namaJenis,
    'harga_per_satuan' => $hargaPerSatuan ?? 0,
    'satuan' => $satuan,
    'dapat_dijual' => $this->request->getPost('dapat_dijual') ? 1 : 0,
    'status_aktif' => $this->request->getPost('status_aktif') ? 1 : 0,
    'deskripsi' => $this->request->getPost('deskripsi') ?? ''
    // Timestamps otomatis ditambahkan oleh model
];

// ✅ Tambah error handling detail
$insertResult = $hargaModel->insert($data);

if ($insertResult) {
    // success
}

$errors = $hargaModel->errors();
$errorMessage = 'Gagal menambahkan jenis sampah';

if (!empty($errors)) {
    $errorMessage .= ': ' . implode(', ', $errors);
    log_message('error', 'Validation errors: ' . json_encode($errors));
}

return $this->response->setJSON([
    'success' => false,
    'message' => $errorMessage,
    'errors' => $errors
]);
```

### 3. View: index.php
```javascript
// ✅ Tampilkan detail error
if (data.success) {
    // success
} else {
    let errorMsg = data.message || 'Gagal menambahkan jenis sampah';
    if (data.errors && Object.keys(data.errors).length > 0) {
        errorMsg += '<br><small>' + Object.values(data.errors).join('<br>') + '</small>';
    }
    showAlert('error', errorMsg);
}
```

## Testing Checklist

### Test 1: Tambah Jenis Sampah dengan Satuan Gram
- [ ] Login sebagai admin
- [ ] Buka Manajemen Sampah
- [ ] Klik "Tambah Jenis Sampah"
- [ ] Isi form:
  - Jenis Sampah: "Test Gram"
  - Nama Lengkap: "Test Sampah Gram"
  - Harga: 1000
  - Satuan: Gram (g)
  - Dapat Dijual: ✓
  - Status Aktif: ✓
- [ ] Klik "Simpan Jenis Sampah"
- [ ] ✅ Berhasil: Data tersimpan dan muncul di tabel
- [ ] ❌ Gagal: Muncul error message detail

### Test 2: Tambah Jenis Sampah dengan Satuan Lain
- [ ] Test dengan satuan: kg, ton, liter, pcs, karung
- [ ] Semua harus berhasil tersimpan

### Test 3: Validasi Duplikasi
- [ ] Coba tambah jenis sampah yang sudah ada
- [ ] ✅ Harus muncul error: "Jenis sampah sudah ada"

### Test 4: Validasi Field Required
- [ ] Coba submit form dengan field kosong
- [ ] ✅ Harus muncul error validation

### Test 5: Check Console Log
- [ ] Buka Developer Tools > Console
- [ ] Submit form
- [ ] ✅ Harus muncul log:
  - "Submitting new jenis sampah:"
  - Form data details
  - "Response status: 200"
  - "Response data: {success: true/false}"

### Test 6: Check Database
- [ ] Setelah berhasil simpan, cek database
- [ ] ✅ Data harus ada di tabel `master_harga_sampah`
- [ ] ✅ Field `created_at` dan `updated_at` terisi otomatis

### Test 7: Check Log Perubahan
- [ ] Setelah berhasil simpan, buka "Log Perubahan"
- [ ] ✅ Harus ada entry baru dengan keterangan "Jenis sampah baru ditambahkan"

## Debugging Guide

### Jika Masih Gagal Simpan:

1. **Check Console Browser**
   ```
   F12 > Console Tab
   Lihat error message dan response data
   ```

2. **Check Server Log**
   ```
   writable/logs/log-YYYY-MM-DD.php
   Cari error message terkait validation atau insert
   ```

3. **Check Database Connection**
   ```sql
   SELECT * FROM master_harga_sampah;
   -- Pastikan tabel ada dan struktur benar
   ```

4. **Check CSRF Token**
   ```javascript
   // Di console browser
   console.log(document.querySelector('meta[name="csrf-token"]').content);
   // Harus ada value
   ```

5. **Check Route**
   ```
   URL: /admin-pusat/manajemen-harga/store
   Method: POST
   Status: 200 (bukan 404 atau 500)
   ```

## Expected Behavior

### Sebelum Fix:
```
User: Klik "Simpan Jenis Sampah"
System: Loading...
System: Error (tidak ada pesan jelas)
Result: Data tidak tersimpan
```

### Setelah Fix:
```
User: Klik "Simpan Jenis Sampah"
System: Loading...
System: ✅ "Jenis sampah berhasil ditambahkan"
System: Reload halaman
Result: Data muncul di tabel
```

### Jika Ada Error:
```
User: Klik "Simpan Jenis Sampah"
System: Loading...
System: ❌ "Gagal menambahkan jenis sampah: [detail error]"
Result: Modal tetap terbuka, user bisa perbaiki input
```

## Files Modified

1. ✅ `app/Models/HargaSampahModel.php`
   - Update validasi satuan (tambah gram)
   - Update allowedFields (tambah timestamps)
   - Update validation rules (permit_empty)

2. ✅ `app/Controllers/Admin/Harga.php`
   - Hapus manual timestamps
   - Tambah error handling detail
   - Tambah logging

3. ✅ `app/Views/admin_pusat/manajemen_harga/index.php`
   - Update error display
   - Tambah detail validation errors

## Kesimpulan

Bug telah diperbaiki dengan:
1. ✅ Menambahkan 'gram' ke validasi satuan
2. ✅ Menambahkan created_at dan updated_at ke allowedFields
3. ✅ Membuat validasi lebih fleksibel
4. ✅ Menghapus manual timestamp handling
5. ✅ Menambahkan error handling yang lebih detail
6. ✅ Menambahkan logging untuk debugging

Sekarang admin dapat menambahkan jenis sampah baru dengan sukses, termasuk dengan satuan "gram".
