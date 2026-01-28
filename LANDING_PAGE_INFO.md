# Landing Page - UI GreenMetric POLBAN

## 📍 Akses
- **URL**: `http://localhost:8080/` atau `http://localhost:8080/index.php`
- **File View**: `app/Views/landing.php`
- **Controller**: `app/Controllers/Home.php`

## 🎨 Desain
- **Warna Utama**: `#5C8CBF` (sama dengan form login)
- **Style**: Modern, clean, responsive
- **Font**: Inter (Google Fonts)

## 📊 Fitur Landing Page

### 1. Hero Section
- Judul besar dengan highlight warna biru
- Deskripsi singkat sistem
- Button CTA "Mulai Sekarang" → redirect ke login
- **Statistik Real-time**:
  - Total Data Sampah
  - Kg Sampah Terkumpul
  - TPS Aktif

### 2. Fitur Unggulan (3 Cards)
- **Multi-Role System**: Penjelasan 3 role (Admin, User, TPS)
- **Real-time Monitoring**: Dashboard & statistik
- **Export & Reporting**: Ekspor PDF

### 3. Statistik Section
Background biru dengan 4 statistik:
- Data Disetujui
- Kg Sampah Terkumpul
- TPS Aktif
- Sampah Bisa Dijual

### 4. Call-to-Action
- Ajakan untuk login
- Button besar "Login ke Dashboard"

### 5. Footer
- Info kontak POLBAN
- Quick links
- Copyright

## 🔧 Data Statistik
Data diambil dari database secara real-time:
```php
- total_data: COUNT dari waste_management
- total_berat: SUM berat_kg dari waste_management
- total_tps: COUNT unit dengan jenis_unit = 'TPS'
- disetujui: COUNT waste_management dengan status = 'disetujui'
- bisa_dijual: COUNT waste_management dengan kategori = 'bisa_dijual'
```

## ✨ Animasi & Interaksi
- Navbar berubah saat scroll
- Smooth scroll untuk navigasi
- Counter animation untuk angka statistik
- Hover effects pada cards
- Responsive untuk mobile

## 📱 Responsive Design
- Desktop: Full layout dengan grid
- Tablet: 2 kolom untuk cards
- Mobile: 1 kolom, stack layout

## 🚀 Cara Akses
1. Buka browser
2. Akses: `http://localhost:8080/`
3. Klik tombol "Login" di navbar atau "Mulai Sekarang" untuk ke halaman login

## 🎯 Tujuan Landing Page
- Showcase sistem kepada pengunjung umum
- Menampilkan pencapaian pengelolaan sampah kampus
- Memberikan overview fitur sebelum login
- Meningkatkan kredibilitas sistem
- Transparansi data untuk stakeholder eksternal

---
**Note**: Landing page ini bersifat public (tidak perlu login) dan menampilkan statistik agregat dari database.
