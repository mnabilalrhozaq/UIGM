# Test Tombol Hapus di Manajemen Sampah

## Masalah
Tombol hapus tidak bisa diklik atau tidak berfungsi.

## Kemungkinan Penyebab
1. **Tidak ada data di database** - Tombol tidak muncul karena tabel kosong
2. **JavaScript error** - Fungsi deleteWaste() tidak berjalan
3. **Route error** - Endpoint delete tidak ditemukan
4. **CSRF token error** - Token tidak valid

## Langkah Testing

### 1. Pastikan Ada Data di Database

Jalankan query ini di phpMyAdmin:
```sql
-- Cek apakah ada data
SELECT COUNT(*) as total FROM waste_management;

-- Jika 0, insert data test
INSERT INTO waste_management (
    unit_id, tanggal, jenis_sampah, satuan, jumlah, berat_kg, 
    gedung, kategori_sampah, nilai_rupiah, status, created_at, updated_at
) VALUES 
(1, CURDATE(), 'Plastik', 'kg', 10, 10, 'Gedung A', 'bisa_dijual', 50000, 'draft', NOW(), NOW());

-- Verifikasi
SELECT * FROM waste_management;
```

### 2. Refresh Halaman Waste Management

1. Buka halaman waste management admin
2. Tekan **Ctrl + Shift + R** (hard refresh)
3. Lihat apakah data muncul
4. Lihat apakah tombol "Hapus" muncul

### 3. Cek Console Browser untuk Error

1. Tekan **F12** untuk buka Developer Tools
2. Klik tab **Console**
3. Klik tombol "Hapus"
4. Lihat apakah ada error di console

**Error yang mungkin muncul:**
- `deleteWaste is not defined` → Fungsi JavaScript tidak ditemukan
- `404 Not Found` → Route tidak ada
- `403 Forbidden` → CSRF token error
- `500 Internal Server Error` → Error di server

### 4. Test Manual dengan Console

Buka Console (F12) dan jalankan:
```javascript
// Test apakah fungsi ada
console.log(typeof deleteWaste);
// Seharusnya: "function"

// Test panggil fungsi
deleteWaste(1, 'Test');
// Seharusnya: muncul confirm dialog
```

### 5. Cek Network Tab

1. Buka Developer Tools (F12)
2. Klik tab **Network**
3. Klik tombol "Hapus"
4. Klik "OK" di confirm dialog
5. Lihat request yang dikirim:
   - URL: `/admin-pusat/waste/delete/[ID]`
   - Method: `POST`
   - Status: `200 OK` (berhasil) atau error

### 6. Cek Response

Di Network tab, klik request `delete/[ID]` dan lihat **Response**:

**Response berhasil:**
```json
{
    "success": true,
    "message": "Data sampah berhasil dihapus"
}
```

**Response error:**
```json
{
    "success": false,
    "message": "Data sampah tidak ditemukan"
}
```

## Quick Fix

### Jika Tombol Tidak Muncul:
```sql
-- Insert data test
INSERT INTO waste_management (
    unit_id, tanggal, jenis_sampah, satuan, jumlah, berat_kg, 
    gedung, kategori_sampah, nilai_rupiah, status, created_at, updated_at
) VALUES 
(1, CURDATE(), 'Plastik', 'kg', 10, 10, 'Gedung A', 'bisa_dijual', 50000, 'draft', NOW(), NOW());
```
Kemudian refresh halaman (Ctrl + Shift + R).

### Jika Tombol Ada Tapi Tidak Berfungsi:
1. Buka Console (F12)
2. Lihat error yang muncul
3. Screenshot error dan kirim

### Jika Muncul Error 404:
Route mungkin belum ter-load. Coba:
1. Clear cache: `php spark cache:clear`
2. Restart server (jika pakai Laragon, restart Apache)

### Jika Muncul Error CSRF:
Refresh halaman untuk dapat token baru.

## Struktur Tombol yang Benar

Tombol hapus seharusnya muncul di setiap baris data:

```
| No | Tanggal | Unit | Jenis | Berat | Nilai | Status | Aksi                    |
|----|---------|------|-------|-------|-------|--------|-------------------------|
| 1  | ...     | ...  | ...   | ...   | ...   | Draft  | [Hapus]                 |
| 2  | ...     | ...  | ...   | ...   | ...   | Dikirim| [Setujui][Tolak][Hapus] |
```

- Status **Draft**: Hanya tombol Hapus
- Status **Dikirim/Review**: Tombol Setujui, Tolak, dan Hapus

## Jika Masih Tidak Bisa

Kirim screenshot:
1. Halaman waste management (tampilkan data dan tombol)
2. Console error (F12 → Console)
3. Network tab saat klik hapus (F12 → Network)
4. Hasil query `SELECT * FROM waste_management LIMIT 5;`
