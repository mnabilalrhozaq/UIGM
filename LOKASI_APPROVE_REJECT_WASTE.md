# Lokasi Fitur Approve/Reject Data Waste

## Jawaban Singkat
Fitur approve (menyetujui) dan reject (menolak) data waste ada di menu **"Waste Management"** di sidebar admin.

## Lokasi di Sidebar

```
📊 Dashboard

📁 Data Management
├── Waste Management     ← DI SINI! (Approve/Reject)
├── Manajemen Sampah
├── User Management
└── Feature Toggle

📈 Reports & Analytics
└── Laporan Waste

⚙️ System
└── Profil Akun

🚪 Logout
```

## URL
```
/admin-pusat/waste
```

## Fungsi Halaman

### 1. Review Data Sampah
Halaman ini menampilkan semua data sampah yang dikirim oleh:
- **User** (dari berbagai unit)
- **TPS** (Tempat Pembuangan Sampah)

### 2. Status Data yang Ditampilkan
- **Menunggu Review** (status: dikirim)
- **Disetujui** (status: disetujui)
- **Perlu Revisi** (status: perlu_revisi)

### 3. Aksi yang Tersedia
Admin dapat melakukan:
- ✅ **Approve** - Menyetujui data waste
- ❌ **Reject** - Menolak data waste (dengan alasan)
- 👁️ **View Detail** - Melihat detail data waste

## File Terkait

### Controller
**File**: `app/Controllers/Admin/Waste.php`

**Methods**:
```php
public function index()           // Menampilkan halaman review
public function approve($id)      // Menyetujui data waste
public function reject($id)       // Menolak data waste
public function export()          // Export data waste
```

### View
**File**: `app/Views/admin_pusat/waste_management.php`

**Konten**:
- Statistics cards (menunggu review, disetujui, perlu revisi)
- Tabel data waste
- Tombol approve/reject per data
- Modal untuk input alasan reject

### Service
**File**: `app/Services/Admin/WasteService.php`

**Methods**:
```php
public function getWasteData()           // Get semua data waste
public function approveWaste($id)        // Logic approve
public function rejectWaste($id)         // Logic reject
public function exportWaste()            // Logic export
```

### Routes
**File**: `app/Config/Routes/Admin/waste.php`

```php
$routes->get('waste', 'Admin\\Waste::index');
$routes->post('waste/approve/(:num)', 'Admin\\Waste::approve/$1');
$routes->post('waste/reject/(:num)', 'Admin\\Waste::reject/$1');
$routes->get('waste/export', 'Admin\\Waste::export');
```

## Flow Approve/Reject

### Flow Approve:
```
1. User/TPS submit data waste (status: dikirim)
   ↓
2. Admin buka menu "Waste Management"
   ↓
3. Admin lihat data dengan status "Menunggu Review"
   ↓
4. Admin klik tombol "Approve" (✓)
   ↓
5. Konfirmasi approve
   ↓
6. Status berubah menjadi "disetujui"
   ↓
7. Data muncul di laporan waste
```

### Flow Reject:
```
1. User/TPS submit data waste (status: dikirim)
   ↓
2. Admin buka menu "Waste Management"
   ↓
3. Admin lihat data dengan status "Menunggu Review"
   ↓
4. Admin klik tombol "Reject" (✗)
   ↓
5. Modal muncul untuk input alasan reject
   ↓
6. Admin isi alasan reject
   ↓
7. Konfirmasi reject
   ↓
8. Status berubah menjadi "perlu_revisi"
   ↓
9. User/TPS dapat melihat alasan dan revisi data
```

## Statistik yang Ditampilkan

### Card 1: Menunggu Review
- Icon: Paper plane
- Warna: Warning (kuning)
- Isi: Jumlah data dengan status "dikirim"

### Card 2: Disetujui
- Icon: Check circle
- Warna: Success (hijau)
- Isi: Jumlah data dengan status "disetujui"

### Card 3: Perlu Revisi
- Icon: Edit
- Warna: Danger (merah)
- Isi: Jumlah data dengan status "perlu_revisi"

### Card 4: Total Bulan Ini
- Icon: Weight
- Warna: Info (biru)
- Isi: Total berat sampah bulan ini (kg)

## Tabel Data Waste

### Kolom yang Ditampilkan:
1. **No** - Nomor urut
2. **Tanggal** - Tanggal input data
3. **Unit** - Nama unit/TPS
4. **Jenis Sampah** - Kategori sampah
5. **Berat** - Berat sampah (kg)
6. **Nilai** - Nilai ekonomis (Rp)
7. **Status** - Badge status (dikirim/disetujui/perlu_revisi)
8. **Aksi** - Tombol approve/reject

### Tombol Aksi:
- **Approve** (✓) - Tombol hijau untuk menyetujui
- **Reject** (✗) - Tombol merah untuk menolak
- **Detail** (👁️) - Tombol biru untuk lihat detail

## API Endpoints

### 1. Approve Waste
```
POST /admin-pusat/waste/approve/{id}

Request Body:
{
  "catatan": "Data sudah sesuai" (optional)
}

Response:
{
  "success": true,
  "message": "Data waste berhasil disetujui"
}
```

### 2. Reject Waste
```
POST /admin-pusat/waste/reject/{id}

Request Body:
{
  "alasan": "Data tidak lengkap, mohon dilengkapi" (required)
}

Response:
{
  "success": true,
  "message": "Data waste ditolak dan perlu revisi"
}
```

## Validasi

### Approve:
- ✅ Session harus valid
- ✅ Role harus admin_pusat atau super_admin
- ✅ Data waste harus ada
- ✅ Status harus "dikirim"

### Reject:
- ✅ Session harus valid
- ✅ Role harus admin_pusat atau super_admin
- ✅ Data waste harus ada
- ✅ Status harus "dikirim"
- ✅ Alasan reject wajib diisi

## Notifikasi

### Setelah Approve:
- ✅ Success message: "Data waste berhasil disetujui"
- ✅ Data pindah ke tab "Disetujui"
- ✅ User/TPS dapat melihat status "Disetujui"

### Setelah Reject:
- ✅ Success message: "Data waste ditolak dan perlu revisi"
- ✅ Data pindah ke tab "Perlu Revisi"
- ✅ User/TPS dapat melihat alasan reject
- ✅ User/TPS dapat revisi dan submit ulang

## Filter & Search

Halaman Waste Management juga memiliki fitur:
- 🔍 **Search** - Cari berdasarkan unit, jenis sampah
- 📅 **Filter Tanggal** - Filter berdasarkan periode
- 📊 **Filter Status** - Filter berdasarkan status (dikirim/disetujui/perlu_revisi)
- 📤 **Export** - Export data ke CSV/Excel

## Tips Penggunaan

### Untuk Admin:
1. Buka menu "Waste Management" secara berkala
2. Review data yang masuk (status "Menunggu Review")
3. Approve data yang sudah sesuai
4. Reject data yang tidak sesuai dengan alasan yang jelas
5. Monitor statistik untuk tracking performa

### Best Practice:
- ✅ Review data maksimal 1x24 jam setelah submit
- ✅ Berikan alasan reject yang jelas dan konstruktif
- ✅ Approve data yang sudah lengkap dan akurat
- ✅ Gunakan filter untuk mempermudah review
- ✅ Export data secara berkala untuk backup

## Troubleshooting

### Data tidak muncul?
- Cek apakah ada data dengan status "dikirim"
- Refresh halaman
- Cek filter yang aktif
- Cek log error di server

### Tombol approve/reject tidak berfungsi?
- Cek koneksi internet
- Cek console browser untuk error
- Cek session masih valid
- Cek role user adalah admin_pusat/super_admin

### Alasan reject tidak tersimpan?
- Pastikan field alasan diisi
- Cek validasi form
- Cek log error di server

## Kesimpulan

Fitur approve/reject data waste ada di menu **"Waste Management"** (`/admin-pusat/waste`). Admin dapat menyetujui atau menolak data waste yang dikirim oleh User dan TPS. Setiap aksi akan mengubah status data dan memberikan notifikasi kepada pengirim data.

**Lokasi**: Sidebar Admin → Data Management → **Waste Management**
