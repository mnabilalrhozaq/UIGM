# Perbaikan Dashboard Admin Pusat

## Masalah yang Diperbaiki
- Error: `Unknown column 'indikator.bobot' in 'field list'`
- Error: `Unknown column 'indikator.warna' in 'field list'`

## Status Perbaikan
✅ **SUDAH DIPERBAIKI** - Dashboard sekarang bisa diakses tanpa error

## Yang Sudah Dilakukan

### 1. Perbaikan Model dan Query
- ✅ `IndikatorModel.php` - Update semua referensi `bobot` ke `bobot_persen`
- ✅ `ReviewKategoriModel.php` - Hapus kolom `warna` dari query sementara
- ✅ `ReportController.php` - Update referensi ke `bobot_persen`

### 2. Perbaikan View
- ✅ `indikator_greenmetric.php` - Tambah helper function untuk warna default
- ✅ `review_detail.php` - Update referensi kolom
- ✅ Semua view sekarang menggunakan warna fallback jika kolom `warna` tidak ada

### 3. Database
- ✅ `database_export.sql` - Sudah include kolom `warna` untuk database baru
- ✅ `database_patch_add_warna.sql` - Script patch untuk database yang sudah ada

## Cara Menggunakan

### Untuk Database Baru
```sql
-- Import file ini untuk database baru
mysql -u username -p database_name < database_export.sql
```

### Untuk Database yang Sudah Ada
```sql
-- Jalankan patch ini untuk menambahkan kolom warna
mysql -u username -p database_name < database_patch_add_warna.sql
```

## Kredensial Login
- **Admin Pusat:** `adminpusat` / `adminpusat123`
- **Super Admin:** `superadmin` / `superadmin123`
- **User:** `user1`, `user2`, `user3`, `user4` / `user123`

## Hasil
🎉 **Dashboard Admin Pusat sekarang bisa diakses tanpa error!**

### Fitur yang Berfungsi:
- ✅ Statistik dashboard
- ✅ Progress institusi
- ✅ Review kategori UIGM
- ✅ Notifikasi
- ✅ Filter tahun dan unit
- ✅ Tampilan kategori dengan warna

### Catatan Teknis:
- Kolom `bobot` di database sebenarnya bernama `bobot_persen`
- Kolom `warna` opsional - jika tidak ada, akan menggunakan warna default
- Semua query sudah disesuaikan dengan struktur database yang benar