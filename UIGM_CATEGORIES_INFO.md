# UIGM Categories - UI GreenMetric POLBAN

## 📋 Overview
Sistem sekarang memiliki 6 kategori UIGM lengkap di sidebar admin dengan struktur submenu.

## 🗂️ Kategori UIGM

### 1. Setting & Infrastructure
- **Icon**: `fa-building` (gedung)
- **URL Data**: `/admin-pusat/infrastructure`
- **URL Laporan**: `/admin-pusat/infrastructure/laporan`
- **Statistik**: Total Gedung, Area Hijau, Total Jalan, Area Parkir

### 2. Energy & Climate
- **Icon**: `fa-bolt` (listrik)
- **URL Data**: `/admin-pusat/energy`
- **URL Laporan**: `/admin-pusat/energy/laporan`
- **Statistik**: Total Konsumsi (kWh), Energi Terbarukan, Emisi CO2, Efisiensi Energi

### 3. Waste Management ✅ (Sudah Lengkap)
- **Icon**: `fa-trash-alt` (sampah)
- **Submenu**:
  - Manajemen Data Sampah → `/admin-pusat/waste`
  - Manajemen Jenis Sampah → `/admin-pusat/manajemen-harga`
  - Laporan Data Sampah → `/admin-pusat/laporan-waste`

### 4. Water Management
- **Icon**: `fa-tint` (tetesan air)
- **URL Data**: `/admin-pusat/water`
- **URL Laporan**: `/admin-pusat/water/laporan`
- **Statistik**: Konsumsi Air, Air Daur Ulang, Sumber Air, Efisiensi Air

### 5. Transportation
- **Icon**: `fa-car` (mobil)
- **URL Data**: `/admin-pusat/transportation`
- **URL Laporan**: `/admin-pusat/transportation/laporan`
- **Statistik**: Total Kendaraan, Shuttle Bus, Sepeda Kampus, Charging Station

### 6. Education & Research
- **Icon**: `fa-graduation-cap` (topi wisuda)
- **URL Data**: `/admin-pusat/education`
- **URL Laporan**: `/admin-pusat/education/laporan`
- **Statistik**: Mata Kuliah Lingkungan, Penelitian Lingkungan, Publikasi, Organisasi Lingkungan

## 📁 Struktur File

### Controllers
```
app/Controllers/Admin/
├── Infrastructure.php
├── Energy.php
├── Water.php
├── Transportation.php
└── Education.php
```

### Views
```
app/Views/admin_pusat/
├── infrastructure/
│   ├── index.php
│   └── laporan.php
├── energy/
│   ├── index.php
│   └── laporan.php
├── water/
│   ├── index.php
│   └── laporan.php
├── transportation/
│   ├── index.php
│   └── laporan.php
└── education/
    ├── index.php
    └── laporan.php
```

### Routes
```
app/Config/Routes/Admin/uigm_categories.php
```

## 🎨 Fitur UI

### Sidebar
- Menu dengan submenu (collapsible/dropdown)
- Icon chevron yang rotate saat dibuka
- Auto-open submenu jika halaman aktif
- Smooth animation expand/collapse
- Hover effects

### Halaman Data
- Header dengan icon dan deskripsi
- Alert "Coming Soon"
- 4 Statistics cards dengan gradient icons
- Empty state dengan icon besar
- Responsive design

### Halaman Laporan
- Header dengan icon chart
- Alert "Coming Soon"
- Card untuk laporan
- Empty state

## 🚀 Status Pengembangan

| Kategori | Status | Keterangan |
|----------|--------|------------|
| Setting & Infrastructure | 🟡 Template Ready | Siap dikembangkan |
| Energy & Climate | 🟡 Template Ready | Siap dikembangkan |
| Waste Management | 🟢 Complete | Fully functional |
| Water Management | 🟡 Template Ready | Siap dikembangkan |
| Transportation | 🟡 Template Ready | Siap dikembangkan |
| Education & Research | 🟡 Template Ready | Siap dikembangkan |

## 📝 Next Steps

Untuk mengembangkan kategori yang masih template:
1. Buat database table untuk masing-masing kategori
2. Buat Model untuk CRUD operations
3. Buat Service layer untuk business logic
4. Update Controller dengan logic yang sesuai
5. Update View dengan form input dan tabel data
6. Tambahkan fitur export PDF/Excel
7. Implementasikan laporan dengan chart/grafik

## 🎯 Konsistensi Design

Semua kategori menggunakan:
- Warna gradient yang sama untuk stat cards
- Layout yang konsisten
- Icon Font Awesome
- Bootstrap 5
- Responsive design
- Empty state yang informatif

---
**Note**: Kategori Waste Management sudah fully functional dan bisa dijadikan referensi untuk pengembangan kategori lainnya.
