# Sistem Form Petugas Gedung dan TPS - Pengelolaan Sampah

## 📋 Ringkasan

Implementasi sistem form terpisah untuk 2 tipe petugas dalam pengelolaan sampah (kategori WS):

1. **Form Petugas Gedung** - Untuk petugas yang mengumpulkan sampah dari gedung/area
2. **Form Petugas TPS** - Untuk petugas yang mengelola sampah di Tempat Pembuangan Sementara

## 🎯 Konsep Sistem

### Alur Kerja:

1. **Petugas Gedung** → Mengumpulkan sampah dari berbagai area → Input data pengumpulan
2. **Petugas TPS** → Menerima sampah dari petugas gedung → Input data penerimaan dan status

### Pemisahan Form:

- User memilih **Tipe Petugas** terlebih dahulu
- Form yang sesuai akan muncul secara dinamis
- Setiap form memiliki field yang spesifik untuk tugasnya

## ✅ Implementasi yang Dilakukan

### 1. Frontend - Dashboard Form (app/Views/admin_unit/dashboard_clean.php)

- ✅ Dropdown **Tipe Petugas Input** untuk memilih jenis petugas
- ✅ **Form Petugas Gedung** dengan field khusus pengumpulan
- ✅ **Form Petugas TPS** dengan field khusus penerimaan dan status
- ✅ JavaScript toggle untuk menampilkan form yang sesuai
- ✅ Integrasi dengan sistem readonly dan validasi

### 2. Backend - Controller (app/Controllers/AdminUnit.php)

- ✅ Handling semua field baru dari kedua form
- ✅ Sanitasi dan validasi data untuk semua field
- ✅ Penyimpanan dalam format JSON yang terstruktur
- ✅ Backward compatibility dengan data lama

### 3. JavaScript Functionality

- ✅ Event listener untuk toggle form berdasarkan tipe petugas
- ✅ Integrasi dengan dropdown cascade yang sudah ada
- ✅ Console logging untuk debugging

## 🎯 Detail Form

### Form Petugas Gedung

**Tujuan:** Mencatat data sampah yang dikumpulkan dari gedung/area tertentu

**Field Khusus:**

- **Nama Petugas Gedung** - Nama petugas yang mengumpulkan
- **Area/Gedung yang Dikumpulkan** - Area mana saja yang dikumpulkan
- **Waktu Pengumpulan** - Jam berapa pengumpulan dilakukan

**Field Umum:**

- Jenis Sampah (dengan dropdown cascade untuk organik)
- Area Sampah Organik (Kantin/Lingkungan Kampus)
- Detail Sampah Organik

### Form Petugas TPS

**Tujuan:** Mencatat data sampah yang diterima di Tempat Pembuangan Sementara

**Field Khusus:**

- **Nama Petugas TPS** - Nama petugas TPS yang menerima
- **Lokasi TPS** - Lokasi TPS yang menerima sampah
- **Asal Sampah (Gedung/Area)** - Dari mana sampah berasal
- **Waktu Penerimaan** - Jam berapa sampah diterima
- **Status Pembuangan** - Status pemrosesan sampah

**Field Umum:**

- Jenis Sampah yang Diterima

## 🔧 Struktur Data JSON

### Data Petugas Gedung:

```json
{
  "tanggal_input": "2024-01-02",
  "gedung": "Kantin",
  "tipe_petugas": "petugas_gedung",
  "jenis_sampah": "Organik",
  "area_sampah": "Kantin",
  "detail_sampah": "Sisa Makanan atau Sayuran",
  "nama_petugas_gedung": "Budi Santoso",
  "area_dikumpulkan": "Kantin Utama, Area Makan",
  "waktu_pengumpulan": "14:30",
  "jumlah": 25.5,
  "satuan": "kg",
  "deskripsi": "Pengumpulan sampah organik dari kantin..."
}
```

### Data Petugas TPS:

```json
{
  "tanggal_input": "2024-01-02",
  "gedung": "TPS Utama",
  "tipe_petugas": "petugas_tps",
  "jenis_sampah": "Organik",
  "nama_petugas_tps": "Ahmad Fauzi",
  "lokasi_tps": "TPS Utama Gedung A",
  "asal_sampah": "Kantin, Gedung A, Gedung B",
  "waktu_penerimaan": "15:00",
  "status_pembuangan": "Dalam Proses Daur Ulang",
  "jumlah": 75.0,
  "satuan": "kg",
  "deskripsi": "Penerimaan sampah organik untuk pengomposan..."
}
```

## 🚀 Cara Penggunaan

### Untuk Petugas Gedung:

1. Login ke dashboard Admin Unit
2. Pilih kategori **Pengelolaan Sampah (WS)**
3. Pilih **Tipe Petugas**: "Petugas Gedung"
4. Isi form yang muncul:
   - Pilih jenis sampah
   - Masukkan nama petugas gedung
   - Tentukan area/gedung yang dikumpulkan
   - Set waktu pengumpulan
   - Isi jumlah dan satuan
5. Simpan data

### Untuk Petugas TPS:

1. Login ke dashboard Admin Unit
2. Pilih kategori **Pengelolaan Sampah (WS)**
3. Pilih **Tipe Petugas**: "Petugas TPS"
4. Isi form yang muncul:
   - Pilih jenis sampah yang diterima
   - Masukkan nama petugas TPS
   - Tentukan lokasi TPS
   - Catat asal sampah
   - Set waktu penerimaan
   - Pilih status pembuangan
   - Isi jumlah dan satuan
5. Simpan data

## 📁 File yang Dimodifikasi

1. `app/Views/admin_unit/dashboard_clean.php` - Implementasi 2 form terpisah
2. `app/Controllers/AdminUnit.php` - Handling field baru dari kedua form

## 📁 File yang Dibuat

1. `test_form_petugas_gedung_tps.html` - Interface test untuk kedua form
2. `SISTEM_FORM_PETUGAS_GEDUNG_TPS.md` - Dokumentasi ini

## ✨ Keunggulan Sistem Baru

### 1. **Pemisahan Tugas yang Jelas**

- Petugas gedung fokus pada pengumpulan
- Petugas TPS fokus pada penerimaan dan pemrosesan

### 2. **Data yang Lebih Terstruktur**

- Tracking lengkap dari pengumpulan hingga pembuangan
- Audit trail yang jelas untuk setiap tahap

### 3. **Fleksibilitas Input**

- Form yang sesuai dengan tugas masing-masing petugas
- Field yang relevan untuk setiap tahap proses

### 4. **Monitoring yang Lebih Baik**

- Dapat melacak siapa yang mengumpulkan dari mana
- Dapat melacak status pemrosesan di TPS
- Timeline yang jelas untuk setiap proses

## 🔄 Kompatibilitas

- ✅ Data lama tetap dapat ditampilkan
- ✅ Sistem baru tidak merusak data yang sudah ada
- ✅ Field baru bersifat opsional

## 🎉 Status: SELESAI ✅

Sistem form terpisah untuk Petugas Gedung dan Petugas TPS telah berhasil diimplementasikan dan siap digunakan.

## 📝 Contoh Skenario Penggunaan

### Skenario 1: Pengumpulan Sampah Organik Kantin

1. **Petugas Gedung** (Siti) mengumpulkan sampah organik dari kantin pada jam 14:30
2. Input: Nama "Siti", Area "Kantin Utama", Waktu "14:30", Jenis "Organik", Jumlah "25 kg"

### Skenario 2: Penerimaan di TPS

1. **Petugas TPS** (Ahmad) menerima sampah dari berbagai gedung pada jam 15:00
2. Input: Nama "Ahmad", Lokasi "TPS Utama", Asal "Kantin + Gedung A", Waktu "15:00", Status "Dikompos", Jumlah "75 kg"

Dengan sistem ini, tracking sampah menjadi lebih lengkap dan terorganisir!
