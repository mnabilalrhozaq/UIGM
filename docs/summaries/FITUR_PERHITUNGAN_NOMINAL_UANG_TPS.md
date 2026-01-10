# Fitur Perhitungan Nominal Uang untuk Petugas TPS

## 📋 Ringkasan

Implementasi fitur perhitungan otomatis nominal uang berdasarkan berat sampah dan harga per kg untuk form Petugas TPS dalam sistem pengelolaan sampah.

## 🎯 Tujuan Fitur

- Memberikan informasi nilai ekonomis dari sampah yang diterima di TPS
- Memudahkan petugas TPS dalam menghitung potensi pendapatan dari daur ulang
- Tracking nilai ekonomis untuk laporan dan analisis

## ✅ Implementasi yang Dilakukan

### 1. Field Baru di Form Petugas TPS

- **Harga per Kg** - Input harga sampah per kilogram
- **Total Nominal Uang** - Hasil perhitungan otomatis (readonly)

### 2. Sistem Harga Otomatis

Harga per kg akan otomatis terisi berdasarkan jenis sampah yang dipilih:

| Jenis Sampah | Harga per Kg                    |
| ------------ | ------------------------------- |
| Organik      | Rp 500                          |
| Anorganik    | Rp 1.000                        |
| Kertas       | Rp 1.500                        |
| Plastik      | Rp 2.000                        |
| Logam        | Rp 5.000                        |
| Kaca         | Rp 800                          |
| Elektronik   | Rp 10.000                       |
| B3           | Rp 0 (tidak ada nilai ekonomis) |
| Medis        | Rp 0 (tidak ada nilai ekonomis) |
| Campuran     | Rp 300                          |

### 3. Perhitungan Otomatis

**Formula:** `Total Nominal = Berat Sampah × Harga per Kg`

**Contoh:**

- Berat: 25 kg
- Jenis: Plastik (Rp 2.000/kg)
- Total: 25 × 2.000 = Rp 50.000

## 🔧 Cara Kerja

### 1. Auto-Fill Harga

- User pilih jenis sampah
- Sistem otomatis mengisi harga per kg sesuai tabel
- User bisa edit manual jika harga berbeda

### 2. Real-time Calculation

- Setiap kali user mengubah berat atau harga
- Total nominal langsung dihitung ulang
- Hasil ditampilkan dengan format rupiah (pemisah ribuan)

### 3. Validasi Input

- Harga per kg: angka positif
- Total nominal: readonly, tidak bisa diedit manual
- Format tampilan: Rp 50.000 (dengan pemisah ribuan)

## 💾 Struktur Data

### Data yang Disimpan:

```json
{
  "tipe_petugas": "petugas_tps",
  "jenis_sampah": "Plastik",
  "nama_petugas_tps": "Ahmad Fauzi",
  "lokasi_tps": "TPS Utama Gedung A",
  "asal_sampah": "Kantin, Gedung A",
  "waktu_penerimaan": "15:00",
  "status_pembuangan": "Dalam Proses Daur Ulang",
  "harga_per_kg": 2000,
  "total_nominal": "50000",
  "jumlah": 25,
  "satuan": "kg",
  "deskripsi": "Penerimaan sampah plastik untuk daur ulang..."
}
```

## 🚀 Cara Penggunaan

### Untuk Petugas TPS:

1. Pilih **Tipe Petugas**: "Petugas TPS"
2. Pilih **Jenis Sampah** → Harga per kg otomatis terisi
3. Masukkan **Berat Sampah** → Total nominal otomatis terhitung
4. Jika perlu, edit **Harga per Kg** secara manual
5. Lihat **Total Nominal** yang sudah dihitung otomatis
6. Lengkapi field lainnya dan simpan

### Contoh Skenario:

1. **Sampah Plastik 25 kg**

   - Pilih "Plastik" → Harga Rp 2.000/kg otomatis terisi
   - Input berat "25" → Total Rp 50.000 otomatis terhitung

2. **Sampah Logam 10 kg**
   - Pilih "Logam" → Harga Rp 5.000/kg otomatis terisi
   - Input berat "10" → Total Rp 50.000 otomatis terhitung

## 🎨 Tampilan UI

### Field Harga per Kg:

```
[Rp] [____2000____] [/kg]
Harga akan otomatis terisi berdasarkan jenis sampah
```

### Field Total Nominal:

```
[Rp] [____50.000____] (readonly)
Dihitung otomatis: Berat × Harga per Kg
```

## 📊 Manfaat Fitur

### 1. **Transparansi Nilai Ekonomis**

- Petugas TPS tahu nilai sampah yang diterima
- Memudahkan laporan keuangan dari daur ulang

### 2. **Motivasi Petugas**

- Petugas bisa melihat kontribusi ekonomis mereka
- Mendorong pengelolaan sampah yang lebih baik

### 3. **Data untuk Analisis**

- Tracking potensi pendapatan per jenis sampah
- Analisis efektivitas program daur ulang

### 4. **Kemudahan Perhitungan**

- Tidak perlu hitung manual
- Mengurangi kesalahan perhitungan
- Format rupiah yang mudah dibaca

## 🔧 Technical Details

### JavaScript Functions:

- `hitungTotalNominal()` - Fungsi perhitungan utama
- Auto-fill harga berdasarkan jenis sampah
- Real-time calculation saat input berubah
- Format angka dengan `toLocaleString('id-ID')`

### Controller Updates:

- Handling field `harga_per_kg` (float)
- Handling field `total_nominal` (string, remove formatting)
- Sanitasi data untuk penyimpanan

## 📁 File yang Dimodifikasi

1. `app/Views/admin_unit/dashboard_clean.php` - Tambah field dan JavaScript
2. `app/Controllers/AdminUnit.php` - Handling field baru
3. `test_form_petugas_gedung_tps.html` - Update test interface

## 🎉 Status: SELESAI ✅

Fitur perhitungan nominal uang untuk Petugas TPS telah berhasil diimplementasikan dengan:

- ✅ Auto-fill harga berdasarkan jenis sampah
- ✅ Perhitungan real-time
- ✅ Format rupiah yang user-friendly
- ✅ Validasi dan sanitasi data
- ✅ Interface yang intuitif

## 📝 Contoh Penggunaan Lengkap

### Skenario: Penerimaan Sampah Plastik di TPS

1. **Input Data:**

   - Tipe Petugas: Petugas TPS
   - Jenis Sampah: Plastik → Harga Rp 2.000/kg (auto)
   - Berat: 25 kg → Total Rp 50.000 (auto)
   - Nama Petugas: Ahmad Fauzi
   - Lokasi TPS: TPS Utama Gedung A
   - Status: Dalam Proses Daur Ulang

2. **Hasil:**
   - Data tersimpan lengkap dengan nilai ekonomis
   - Petugas tahu kontribusi finansial: Rp 50.000
   - Data siap untuk laporan dan analisis

Sistem ini membuat pengelolaan sampah lebih transparan dan memberikan insight nilai ekonomis dari setiap aktivitas di TPS!
