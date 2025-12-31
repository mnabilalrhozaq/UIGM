# Perbaikan Error skor_review Null

## Masalah yang Terjadi

**Error:** `CodeIgniter\Exceptions\InvalidArgumentException: Field "skor_review" is not nullable, but null was passed.`

**Penyebab:**

1. Field `skor_review` di database sebenarnya nullable, tetapi casting di model menyebabkan masalah
2. Controller mengirim nilai null tanpa validasi yang proper
3. JavaScript mengirim string kosong yang diinterpretasi sebagai null

## Perbaikan yang Dilakukan

### 1. Controller AdminPusat.php - Method updateReview()

**Sebelum:**

```php
$reviewData = [
    'skor_review' => $skorReview ?: null,  // Problematic
    // ...
];
```

**Sesudah:**

```php
// Validasi input yang lebih ketat
if (!$pengirimanId || !$indikatorId || !$statusReview) {
    return $this->response->setJSON([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
}

// Handle skor_review dengan benar
$reviewData = [
    'pengiriman_id' => (int)$pengirimanId,
    'indikator_id' => (int)$indikatorId,
    'status_review' => $statusReview,
    'catatan_review' => !empty($catatanReview) ? $catatanReview : null,
    'reviewer_id' => (int)$userId,
    'tanggal_review' => date('Y-m-d H:i:s')
];

// Hanya tambahkan skor_review jika ada nilai valid
if (!empty($skorReview) && is_numeric($skorReview)) {
    $reviewData['skor_review'] = (float)$skorReview;
} else {
    if (!$existingReview) {
        $reviewData['skor_review'] = null;
    }
}

// Update dengan handling yang berbeda untuk insert vs update
if ($existingReview) {
    $updateData = $reviewData;
    if (!empty($skorReview) && is_numeric($skorReview)) {
        $updateData['skor_review'] = (float)$skorReview;
    } elseif (empty($skorReview)) {
        $updateData['skor_review'] = null;
    }
    $this->reviewModel->update($existingReview['id'], $updateData);
} else {
    $this->reviewModel->insert($reviewData);
}
```

### 2. Model ReviewKategoriModel.php - Casting

**Sebelum:**

```php
protected array $casts = [
    'skor_review' => 'float',  // Tidak nullable
];
```

**Sesudah:**

```php
protected array $casts = [
    'skor_review' => '?float',  // Nullable float
    'pengiriman_id' => 'int',
    'indikator_id' => 'int',
    'reviewer_id' => '?int'     // Nullable int
];
```

### 3. Model ReviewKategoriModel.php - Method updateCategoryReview()

**Sebelum:**

```php
$reviewData = [
    'skor_review' => $data['skor_review'] ?? null,  // Langsung assign
    // ...
];
```

**Sesudah:**

```php
$reviewData = [
    'pengiriman_id' => $pengirimanId,
    'indikator_id' => $indikatorId,
    'status_review' => $data['status_review'],
    'catatan_review' => $data['catatan_review'] ?? null,
    'reviewer_id' => $data['reviewer_id'] ?? null,
    'tanggal_review' => date('Y-m-d H:i:s')
];

// Handle skor_review dengan validasi
if (isset($data['skor_review'])) {
    if (is_numeric($data['skor_review']) && $data['skor_review'] !== '') {
        $reviewData['skor_review'] = (float)$data['skor_review'];
    } else {
        $reviewData['skor_review'] = null;
    }
}
```

### 4. View review_detail.php - Input Field

**Sebelum:**

```html
<input
  type="number"
  name="skor_review"
  value="<?= $review['skor_review'] ?? '' ?>"
/>
```

**Sesudah:**

```html
<input
  type="number"
  class="form-control"
  name="skor_review"
  step="0.1"
  min="0"
  max="100"
  value="<?= isset($review['skor_review']) && $review['skor_review'] !== null ? number_format($review['skor_review'], 1) : '' ?>"
  placeholder="0-100"
/>
<small class="text-muted"
  >Kosongkan jika tidak ingin memberikan skor numerik</small
>
```

### 5. JavaScript - Validasi dan Pengiriman Data

**Sebelum:**

```javascript
const formData = {
  skor_review: form.find('[name="skor_review"]').val(), // Bisa string kosong
};
```

**Sesudah:**

```javascript
const statusReview = form.find('[name="status_review"]').val();
const catatanReview = form.find('[name="catatan_review"]').val();
const skorReview = form.find('[name="skor_review"]').val();

// Validasi input
if (!statusReview) {
  showToast("Status review harus dipilih", "warning");
  return;
}

if (statusReview === "perlu_revisi" && !catatanReview.trim()) {
  showToast(
    'Catatan review wajib diisi untuk status "Perlu Revisi"',
    "warning"
  );
  return;
}

const formData = {
  pengiriman_id: PENGIRIMAN_ID,
  indikator_id: kategoriId,
  status_review: statusReview,
  catatan_review: catatanReview.trim() || "",
};

// Hanya tambahkan skor_review jika ada nilai dan valid
if (skorReview && skorReview.trim() !== "" && !isNaN(parseFloat(skorReview))) {
  formData.skor_review = parseFloat(skorReview);
}
```

### 6. Controller AdminPusat.php - Insert Review Baru

**Sebelum:**

```php
$this->reviewModel->insert([
    'pengiriman_id' => $pengirimanId,
    'indikator_id' => $kat['id'],
    'status_review' => 'pending'
]);
```

**Sesudah:**

```php
$this->reviewModel->insert([
    'pengiriman_id' => $pengirimanId,
    'indikator_id' => $kat['id'],
    'status_review' => 'pending',
    'skor_review' => null,
    'catatan_review' => null
]);
```

## Penjelasan Teknis

### Masalah Casting

CodeIgniter 4 menggunakan DataCaster untuk mengkonversi data sebelum disimpan ke database. Ketika field dikonfigurasi sebagai `'float'` tanpa nullable (`?float`), sistem akan mencoba mengkonversi nilai null menjadi float, yang menyebabkan error.

### Solusi Nullable Casting

Dengan menggunakan `'?float'`, kita memberitahu DataCaster bahwa field ini bisa menerima nilai null, sehingga tidak akan mencoba mengkonversi null menjadi float.

### Validasi Berlapis

1. **JavaScript**: Validasi di frontend untuk UX yang baik
2. **Controller**: Validasi di backend untuk keamanan
3. **Model**: Casting yang benar untuk konsistensi data

## Testing

Setelah perbaikan ini:

- ✅ Field skor_review bisa menerima nilai null
- ✅ Field skor_review bisa menerima nilai numerik valid
- ✅ Validasi input yang proper di semua layer
- ✅ Error handling yang lebih baik
- ✅ User experience yang lebih baik dengan pesan validasi

## Rekomendasi

1. **Selalu gunakan nullable casting** (`?type`) untuk field yang bisa null
2. **Validasi input di multiple layer** (frontend, backend, database)
3. **Handle empty string vs null** dengan benar
4. **Logging error** untuk debugging yang lebih mudah
5. **User feedback** yang jelas untuk validasi error

## Status Perbaikan

✅ **Error skor_review null** - Teratasi dengan nullable casting
✅ **Validasi input** - Ditambahkan di semua layer
✅ **Error handling** - Diperbaiki dengan logging
✅ **User experience** - Ditingkatkan dengan validasi frontend
✅ **Data consistency** - Dijamin dengan proper casting
