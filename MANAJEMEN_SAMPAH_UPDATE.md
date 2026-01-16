# Update: Manajemen Harga → Manajemen Sampah

## Perubahan yang Dilakukan

### 1. Perubahan Nama Fitur
- **Sebelumnya**: Manajemen Harga Sampah
- **Sekarang**: Manajemen Sampah
- **Alasan**: Lebih mencerminkan fungsi utama yaitu mengelola jenis sampah beserta harganya

### 2. Fitur Baru: Tambah Jenis Sampah
Ditambahkan kemampuan untuk menambah jenis sampah baru langsung dari interface admin.

#### Form Input Jenis Sampah Baru:
- **Jenis Sampah** (required): Nama singkat jenis sampah (contoh: Plastik, Kertas, Logam)
- **Nama Lengkap** (required): Nama lengkap jenis sampah (contoh: Sampah Plastik)
- **Harga per Satuan** (required): Harga dalam Rupiah
- **Satuan** (required): Pilihan satuan (kg, ton, liter, pcs, karung)
- **Deskripsi** (optional): Deskripsi singkat tentang jenis sampah
- **Dapat Dijual**: Checkbox untuk menandai apakah sampah memiliki nilai ekonomis
- **Status Aktif**: Checkbox untuk mengaktifkan/menonaktifkan jenis sampah

#### Validasi:
- Jenis sampah tidak boleh duplikat
- Semua field required harus diisi
- Harga minimal 0

#### Logging:
- Setiap penambahan jenis sampah baru akan tercatat di log perubahan
- Log mencatat: jenis sampah, harga awal, user yang menambahkan, dan timestamp

### 3. Perubahan UI/UX

#### Header Section:
- Icon berubah dari `fa-money-bill-wave` menjadi `fa-recycle`
- Judul: "Manajemen Sampah"
- Tombol baru: "Tambah Jenis Sampah" (primary button)

#### Sidebar:
- Label menu: "Manajemen Sampah"
- Icon: `fa-recycle`

#### Modal Tambah Jenis Sampah:
- Design modern dengan header berwarna primary
- Form terstruktur dengan grouping yang jelas
- Helper text untuk setiap field
- Icon pada checkbox untuk visual yang lebih baik

#### Empty State:
- Pesan lebih informatif dengan call-to-action
- Icon recycle untuk konsistensi tema

### 4. File yang Dimodifikasi

#### Controller:
**app/Controllers/Admin/Harga.php**
- Method `index()`: Update title menjadi "Manajemen Sampah"
- Method `store()`: Implementasi lengkap untuk menambah jenis sampah baru
  - Validasi input
  - Check duplikasi
  - Insert data
  - Logging otomatis

#### View:
**app/Views/admin_pusat/manajemen_harga/index.php**
- Update title dan header
- Tambah tombol "Tambah Jenis Sampah"
- Tambah modal form untuk input jenis sampah baru
- Tambah JavaScript handler untuk form submission
- Update empty state message

#### Sidebar:
**app/Views/partials/sidebar_admin_pusat.php**
- Update label: "Manajemen Harga" → "Manajemen Sampah"
- Update icon: `fa-money-bill-wave` → `fa-recycle`

### 5. Flow Penambahan Jenis Sampah

```
1. User klik tombol "Tambah Jenis Sampah"
   ↓
2. Modal form muncul dengan field input
   ↓
3. User mengisi form dan submit
   ↓
4. Validasi di controller:
   - Check session
   - Validate required fields
   - Check duplikasi jenis sampah
   ↓
5. Insert data ke database (harga_sampah table)
   ↓
6. Log perubahan ke database (log_perubahan_harga table)
   ↓
7. Return success response
   ↓
8. Modal ditutup dan halaman reload
   ↓
9. Jenis sampah baru muncul di tabel
```

### 6. API Endpoint

**POST** `/admin-pusat/manajemen-harga/store`

**Request Body:**
```json
{
  "jenis_sampah": "Plastik",
  "nama_jenis": "Sampah Plastik",
  "harga_per_satuan": 5000,
  "satuan": "kg",
  "deskripsi": "Sampah plastik berbagai jenis",
  "dapat_dijual": 1,
  "status_aktif": 1
}
```

**Response Success:**
```json
{
  "success": true,
  "message": "Jenis sampah berhasil ditambahkan"
}
```

**Response Error:**
```json
{
  "success": false,
  "message": "Jenis sampah \"Plastik\" sudah ada"
}
```

### 7. Database Impact

#### Table: harga_sampah
- Insert new record dengan semua field yang diisi
- Auto-generate created_at dan updated_at

#### Table: log_perubahan_harga
- Insert log record untuk tracking
- Fields: harga_sampah_id, jenis_sampah, harga_lama (0), harga_baru, admin_id, alasan_perubahan

### 8. Keamanan
- Session validation sebelum akses
- CSRF token protection
- Input sanitization
- SQL injection prevention (menggunakan CodeIgniter Query Builder)

### 9. User Experience Improvements
- Real-time feedback dengan alert messages
- Auto-reload setelah berhasil menambah data
- Loading state pada button submit
- Form validation sebelum submit
- Helper text untuk guidance

### 10. Backward Compatibility
- Semua fitur existing tetap berfungsi
- Edit, toggle status, delete masih bekerja normal
- Log perubahan harga tetap tercatat
- Tidak ada breaking changes pada database structure

## Testing Checklist

- [ ] Buka halaman Manajemen Sampah
- [ ] Klik tombol "Tambah Jenis Sampah"
- [ ] Isi form dengan data valid
- [ ] Submit dan verifikasi data tersimpan
- [ ] Cek log perubahan tercatat
- [ ] Test validasi: jenis sampah duplikat
- [ ] Test validasi: field required kosong
- [ ] Test edit jenis sampah yang baru ditambahkan
- [ ] Test toggle status
- [ ] Verifikasi sidebar menampilkan "Manajemen Sampah"
- [ ] Test responsive design pada mobile

## Notes
- Folder dan route masih menggunakan nama "manajemen-harga" untuk backward compatibility
- Bisa di-refactor di masa depan jika diperlukan
- Database table name tetap "harga_sampah" (tidak perlu diubah)
