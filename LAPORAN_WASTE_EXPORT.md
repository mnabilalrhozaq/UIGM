# Fitur Export Laporan Waste Management

## Deskripsi
Fitur export laporan waste management memungkinkan admin untuk mengunduh data waste yang sudah disetujui dalam format CSV dan PDF.

## Fitur yang Ditambahkan

### 1. Export CSV
- **URL**: `/admin-pusat/laporan-waste/export-csv`
- **Method**: GET
- **Format**: CSV dengan encoding UTF-8 (BOM)
- **Konten**:
  - Header laporan (judul, periode, tanggal export)
  - Data waste (No, Tanggal, Unit, Jenis Sampah, Berat, Nilai Ekonomis, Status, Pelapor, Keterangan)
  - Otomatis download file dengan nama: `laporan_waste_YYYY-MM-DD_HHMMSS.csv`

### 2. Export PDF
- **URL**: `/admin-pusat/laporan-waste/export-pdf`
- **Method**: GET
- **Format**: HTML yang siap print/save as PDF
- **Konten**:
  - Header laporan dengan logo/judul
  - Info periode dan tanggal cetak
  - Tabel data waste
  - Ringkasan (total data, total berat, total nilai ekonomis)
  - Auto print dialog saat halaman dibuka

## Filter yang Didukung
Kedua export mendukung filter yang sama dengan halaman laporan:
- **Periode**: Harian, Bulanan, Tahunan, Custom Range
- **Unit**: Filter berdasarkan unit tertentu
- **Jenis Sampah**: Filter berdasarkan jenis sampah
- **Tanggal**: Sesuai dengan periode yang dipilih

## Cara Penggunaan

### Export CSV
1. Buka halaman Laporan Waste Management
2. Pilih filter yang diinginkan (periode, unit, jenis sampah)
3. Klik tombol "Export CSV"
4. File CSV akan otomatis terdownload

### Export PDF
1. Buka halaman Laporan Waste Management
2. Pilih filter yang diinginkan (periode, unit, jenis sampah)
3. Klik tombol "Export PDF"
4. Halaman PDF akan terbuka di tab baru
5. Dialog print akan muncul otomatis
6. Pilih "Save as PDF" atau print langsung

## File yang Dimodifikasi/Dibuat

### Controller
- `app/Controllers/Admin/LaporanWaste.php`
  - Method `exportCsv()` - Generate dan download CSV
  - Method `exportPdf()` - Generate PDF view
  - Method `getPeriodeLabel()` - Helper untuk format label periode

### View
- `app/Views/admin_pusat/laporan_waste_pdf.php` (BARU)
  - Template PDF dengan styling print-friendly
  - Auto print script

### Routes
- `app/Config/Routes/Admin/laporan.php`
  - Route: `laporan-waste/export-csv`
  - Route: `laporan-waste/export-pdf`

## Catatan Teknis
- CSV menggunakan UTF-8 BOM untuk kompatibilitas dengan Excel
- PDF menggunakan HTML/CSS dengan auto print
- File CSV disimpan sementara di `writable/uploads/`
- Filter query string diteruskan ke URL export untuk konsistensi data
