# Implementasi Sistem Laporan Waste

## Konsep

Sistem ini memisahkan data waste menjadi 2 tabel:

1. **waste_management** - Data yang masih dalam proses (draft, dikirim)
2. **laporan_waste** - Data yang sudah direview (approved, rejected)

## Alur Kerja

### 1. User/TPS Menginput Data
- Data disimpan di `waste_management` dengan status `draft`
- User bisa edit/hapus data yang masih draft

### 2. User/TPS Mengirim Data
- Status berubah menjadi `dikirim` atau `pending`
- Data tidak bisa diedit/dihapus lagi
- Data muncul di halaman Review Admin

### 3. Admin Mereview Data

#### A. Jika DISETUJUI (Approved):
1. Data disalin ke tabel `laporan_waste` dengan status `approved`
2. Data dihapus dari `waste_management`
3. Data muncul di Laporan Waste
4. User mendapat notifikasi (jika ada sistem notifikasi)

#### B. Jika DITOLAK (Rejected):
1. Data disalin ke tabel `laporan_waste` dengan status `rejected`
2. Data dihapus dari `waste_management`
3. Data muncul di Laporan Waste dengan status ditolak
4. User mendapat notifikasi dengan alasan penolakan

### 4. Data Draft
- Data dengan status `draft` tetap ada di `waste_management`
- User bisa edit atau hapus kapan saja
- Tidak muncul di halaman Review Admin

## Struktur Tabel laporan_waste

```sql
- id: Primary key
- waste_id: ID asli dari waste_management (untuk tracking)
- unit_id: Unit yang mengirim data
- kategori_id: Kategori sampah
- jenis_sampah: Jenis sampah (Plastik, Kertas, dll)
- berat_kg: Berat dalam kg
- satuan: Satuan (kg, gram, ton, dll)
- jumlah: Jumlah sesuai satuan
- nilai_rupiah: Nilai ekonomis (0 jika rejected)
- tanggal_input: Tanggal input data
- status: approved/rejected
- reviewed_by: Admin yang mereview
- reviewed_at: Waktu review
- review_notes: Catatan review
- created_by: User yang membuat
- created_at: Waktu dibuat
```

## File yang Dimodifikasi

### 1. ReviewService.php
**Lokasi:** `app/Services/Admin/ReviewService.php`

**Perubahan:**
- `approveWaste()`: Insert ke laporan_waste, delete dari waste_management
- `rejectWaste()`: Insert ke laporan_waste, delete dari waste_management
- `getPendingReviews()`: Hanya ambil data dengan status dikirim/pending
- `getRecentReviews()`: Ambil dari laporan_waste
- `getReviewStats()`: Hitung dari kedua tabel
- `getAverageReviewTime()`: Hitung dari laporan_waste

### 2. LaporanWasteService.php
**Lokasi:** `app/Services/Admin/LaporanWasteService.php`

**Perubahan yang Diperlukan:**
- Ubah query untuk mengambil data dari `laporan_waste` bukan `waste_management`
- Filter berdasarkan status (approved/rejected)
- Tambahkan informasi reviewer

## Cara Install

### 1. Buat Tabel
Jalankan SQL script:
```bash
mysql -u root -p eksperimen < database/sql/CREATE_LAPORAN_WASTE_TABLE.sql
```

Atau jalankan manual di phpMyAdmin/MySQL Workbench.

### 2. Test Alur Kerja

#### Test 1: Approve Data
1. Login sebagai User
2. Tambah data sampah baru
3. Kirim data (status: dikirim)
4. Login sebagai Admin
5. Buka halaman Review
6. Approve data
7. Cek: Data hilang dari waste management
8. Cek: Data muncul di Laporan Waste dengan status approved

#### Test 2: Reject Data
1. Login sebagai User
2. Tambah data sampah baru
3. Kirim data (status: dikirim)
4. Login sebagai Admin
5. Buka halaman Review
6. Reject data dengan alasan
7. Cek: Data hilang dari waste management
8. Cek: Data muncul di Laporan Waste dengan status rejected

#### Test 3: Data Draft
1. Login sebagai User
2. Tambah data sampah baru
3. JANGAN kirim (biarkan status: draft)
4. Cek: Data tetap ada di waste management
5. Cek: Data TIDAK muncul di halaman Review Admin
6. User masih bisa edit/hapus data

## Keuntungan Sistem Ini

1. **Pemisahan Data yang Jelas**
   - Data aktif (draft, pending) di waste_management
   - Data historis (approved, rejected) di laporan_waste

2. **Performa Lebih Baik**
   - Tabel waste_management lebih kecil (hanya data aktif)
   - Query lebih cepat

3. **Audit Trail**
   - Semua data yang direview tersimpan di laporan_waste
   - Bisa tracking siapa yang review dan kapan

4. **Fleksibilitas**
   - User bisa edit data draft kapan saja
   - Data yang sudah direview tidak bisa diubah

5. **Laporan Lebih Akurat**
   - Laporan hanya menampilkan data yang sudah direview
   - Tidak tercampur dengan data draft

## Catatan Penting

1. **Backup Data**: Selalu backup database sebelum implementasi
2. **Testing**: Test di development environment dulu
3. **Migration**: Jika ada data lama dengan status approved/rejected, perlu migrasi ke laporan_waste
4. **Notifikasi**: Implementasi sistem notifikasi untuk user (opsional)

## Status Waste Management

- `draft`: Data baru, belum dikirim (bisa edit/hapus)
- `dikirim` atau `pending`: Data sudah dikirim, menunggu review (tidak bisa edit/hapus)
- Data dengan status `approved` atau `rejected` akan dipindah ke `laporan_waste`

---

Tanggal: 2026-01-14
Oleh: Kiro AI Assistant
