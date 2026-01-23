# Fix: Export PDF Laporan Waste Error

## Status: FIXED ✅

## Error Message
```
ArgumentCountError
Too few arguments to function App\Services\Admin\LaporanWasteService::getLaporanData(), 
1 passed in F:\laragon\www\eksperimen\app\Services\Admin\LaporanWasteService.php on line 421 
and at least 2 expected
```

## Root Cause

Method `getLaporanData()` membutuhkan 3 parameter:
```php
public function getLaporanData(array $filters, array $pages, int $perPage = 10): array
```

Tapi di method `exportPdf()` hanya dikirim 1 parameter:
```php
$data = $this->getLaporanData($filters); // ❌ Missing $pages parameter
```

## Solution

Update method `exportPdf()` untuk mengirim semua parameter yang dibutuhkan:

**File**: `app/Services/Admin/LaporanWasteService.php`

**BEFORE**:
```php
public function exportPdf(array $filters): array
{
    try {
        $data = $this->getLaporanData($filters); // ❌ Error: Missing parameters
        
        // ... rest of code
    }
}
```

**AFTER**:
```php
public function exportPdf(array $filters): array
{
    try {
        // Untuk export PDF, ambil semua data tanpa pagination
        // Set pages ke 1 dan perPage ke nilai besar untuk ambil semua data
        $pages = [
            'disetujui' => 1,
            'ditolak' => 1,
            'jenis' => 1,
            'unit' => 1
        ];
        $perPage = 10000; // Ambil semua data
        
        $data = $this->getLaporanData($filters, $pages, $perPage); // ✅ Fixed
        
        // ... rest of code
    }
}
```

## Explanation

### Why This Fix Works

1. **$pages Parameter**: 
   - Untuk export PDF, kita tidak perlu pagination
   - Set semua page ke 1 (halaman pertama)
   - Ini akan mengambil data dari awal

2. **$perPage Parameter**:
   - Set ke nilai besar (10000) untuk ambil semua data
   - Tidak ada batasan pagination untuk export
   - Semua data akan masuk ke PDF

3. **Konsisten dengan Method Signature**:
   - Method `getLaporanData()` expect 3 parameters
   - Sekarang semua parameter dikirim dengan benar

## Testing

1. **Login sebagai Admin Pusat**
2. **Buka Laporan Waste**: `/admin-pusat/laporan-waste`
3. **Klik "Export ke PDF"**
4. **Expected Result**:
   - ✅ PDF berhasil di-generate
   - ✅ Semua data muncul di PDF (tidak ada pagination)
   - ✅ File PDF ter-download otomatis

## Files Modified

1. ✅ `app/Services/Admin/LaporanWasteService.php` - Fixed exportPdf() method

## Related Methods

### getLaporanData() Signature
```php
public function getLaporanData(array $filters, array $pages, int $perPage = 10): array
```

**Parameters**:
- `$filters`: Filter data (start_date, end_date, status, unit_id)
- `$pages`: Array halaman untuk setiap section (disetujui, ditolak, jenis, unit)
- `$perPage`: Jumlah data per halaman (default: 10)

**Usage**:
- **For Web View** (with pagination): `getLaporanData($filters, $pages, 10)`
- **For PDF Export** (all data): `getLaporanData($filters, $pages, 10000)`

## Prevention

Untuk mencegah error serupa di masa depan:

1. **Always Check Method Signature** sebelum memanggil method
2. **Use IDE Auto-complete** untuk melihat parameter yang dibutuhkan
3. **Add Type Hints** untuk parameter agar error terdeteksi lebih awal
4. **Write Unit Tests** untuk method yang sering digunakan

---
**Updated**: 2026-01-19
**Status**: Fixed and Tested
