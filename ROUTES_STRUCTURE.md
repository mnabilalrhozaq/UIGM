# 📁 STRUKTUR ROUTES ADMIN PUSAT

## 🎯 KONSEP

Routes untuk admin pusat sekarang dipisah ke file-file terpisah berdasarkan fitur, kemudian di-include ke `Routes.php` utama. Ini membuat kode lebih rapi dan mudah di-maintain.

---

## 📂 STRUKTUR FILE

```
app/Config/
├── Routes.php (Main routes file)
└── Routes/
    └── Admin/
        ├── dashboard.php          → Dashboard routes
        ├── harga.php              → Manajemen Harga routes
        ├── feature_toggle.php     → Feature Toggle routes
        ├── user_management.php    → User Management routes
        ├── waste.php              → Waste Management routes
        ├── review.php             → Review System routes
        ├── laporan.php            → Laporan routes
        └── pengaturan.php         → Pengaturan routes
```

---

## 📋 DETAIL ROUTES PER FITUR

### 1. **Dashboard** (`dashboard.php`)
```php
GET  /admin-pusat/dashboard       → Admin\Dashboard::index
GET  /admin-pusat/                → Admin\Dashboard::index
```

### 2. **Manajemen Harga** (`harga.php`)
```php
GET    /admin-pusat/manajemen-harga                    → Admin\Harga::index
POST   /admin-pusat/manajemen-harga/store              → Admin\Harga::store
POST   /admin-pusat/manajemen-harga/update/(:num)      → Admin\Harga::update/$1
POST   /admin-pusat/manajemen-harga/toggle-status/(:num) → Admin\Harga::toggleStatus/$1
DELETE /admin-pusat/manajemen-harga/delete/(:num)      → Admin\Harga::delete/$1
GET    /admin-pusat/manajemen-harga/logs               → Admin\Harga::logs
```

### 3. **Feature Toggle** (`feature_toggle.php`)
```php
GET  /admin-pusat/feature-toggle                → Admin\FeatureToggle::index
POST /admin-pusat/feature-toggle/toggle         → Admin\FeatureToggle::toggle
POST /admin-pusat/feature-toggle/bulk-toggle    → Admin\FeatureToggle::bulkToggle
POST /admin-pusat/feature-toggle/update-config  → Admin\FeatureToggle::updateConfig
GET  /admin-pusat/feature-toggle/logs           → Admin\FeatureToggle::logs
```

### 4. **User Management** (`user_management.php`)
```php
GET    /admin-pusat/user-management                    → Admin\UserManagement::index
GET    /admin-pusat/user-management/get/(:num)         → Admin\UserManagement::getUser/$1
POST   /admin-pusat/user-management/create             → Admin\UserManagement::create
POST   /admin-pusat/user-management/update/(:num)      → Admin\UserManagement::update/$1
POST   /admin-pusat/user-management/toggle-status/(:num) → Admin\UserManagement::toggleStatus/$1
DELETE /admin-pusat/user-management/delete/(:num)      → Admin\UserManagement::delete/$1
```

### 5. **Waste Management** (`waste.php`)
```php
GET    /admin-pusat/waste              → Admin\Waste::index
GET    /admin-pusat/waste/export       → Admin\Waste::export
POST   /admin-pusat/waste/store        → Admin\Waste::store
POST   /admin-pusat/waste/update/(:num) → Admin\Waste::update/$1
DELETE /admin-pusat/waste/delete/(:num) → Admin\Waste::delete/$1
```

### 6. **Review System** (`review.php`)
```php
GET  /admin-pusat/review                → Admin\Review::index
POST /admin-pusat/review/approve/(:num) → Admin\Review::approve/$1
POST /admin-pusat/review/reject/(:num)  → Admin\Review::reject/$1
GET  /admin-pusat/review/detail/(:num)  → Admin\Review::detail/$1
```

### 7. **Laporan** (`laporan.php`)
```php
GET /admin-pusat/laporan               → Admin\Laporan::index
GET /admin-pusat/laporan/export        → Admin\Laporan::export
GET /admin-pusat/laporan-waste         → Admin\LaporanWaste::index
GET /admin-pusat/laporan-waste/export  → Admin\LaporanWaste::export
```

### 8. **Pengaturan** (`pengaturan.php`)
```php
GET  /admin-pusat/pengaturan        → Admin\Pengaturan::index
POST /admin-pusat/pengaturan/update → Admin\Pengaturan::update
```

---

## 🔧 CARA KERJA

### Di `Routes.php` utama:
```php
$routes->group('admin-pusat', ['filter' => 'role:admin_pusat,super_admin'], function ($routes) {
    // Load all admin routes from separate files
    require APPPATH . 'Config/Routes/Admin/dashboard.php';
    require APPPATH . 'Config/Routes/Admin/harga.php';
    require APPPATH . 'Config/Routes/Admin/feature_toggle.php';
    require APPPATH . 'Config/Routes/Admin/user_management.php';
    require APPPATH . 'Config/Routes/Admin/waste.php';
    require APPPATH . 'Config/Routes/Admin/review.php';
    require APPPATH . 'Config/Routes/Admin/laporan.php';
    require APPPATH . 'Config/Routes/Admin/pengaturan.php';
});
```

### Di file routes terpisah (contoh `harga.php`):
```php
<?php
/**
 * Manajemen Harga Routes
 * URL: /admin-pusat/manajemen-harga
 */

$routes->get('manajemen-harga', 'Admin\\Harga::index');
$routes->post('manajemen-harga/store', 'Admin\\Harga::store');
// ... dst
```

---

## ✅ KEUNTUNGAN STRUKTUR INI

1. **Lebih Rapi** - Setiap fitur punya file routes sendiri
2. **Mudah Maintain** - Tinggal edit file yang relevan
3. **Mudah Debug** - Langsung tahu route mana yang bermasalah
4. **Scalable** - Mudah tambah fitur baru
5. **Clear Separation** - Setiap fitur terpisah jelas

---

## 🚀 CARA MENAMBAH FITUR BARU

1. Buat file baru di `app/Config/Routes/Admin/nama_fitur.php`
2. Tulis routes untuk fitur tersebut
3. Include di `Routes.php`:
   ```php
   require APPPATH . 'Config/Routes/Admin/nama_fitur.php';
   ```
4. Done!

---

## 🎯 MAPPING CONTROLLER → VIEW

| Controller | View Path | Route |
|------------|-----------|-------|
| Admin\\Dashboard | `admin_pusat/dashboard` | `/admin-pusat/dashboard` |
| Admin\\Harga | `admin_pusat/manajemen_harga/index` | `/admin-pusat/manajemen-harga` |
| Admin\\FeatureToggle | `admin_pusat/feature_toggle/index` | `/admin-pusat/feature-toggle` |
| Admin\\UserManagement | `admin_pusat/user_management` | `/admin-pusat/user-management` |
| Admin\\Waste | `admin_pusat/waste` | `/admin-pusat/waste` |
| Admin\\Review | `admin_pusat/review` | `/admin-pusat/review` |
| Admin\\Laporan | `admin_pusat/laporan` | `/admin-pusat/laporan` |
| Admin\\LaporanWaste | `admin_pusat/laporan_waste` | `/admin-pusat/laporan-waste` |
| Admin\\Pengaturan | `admin_pusat/pengaturan` | `/admin-pusat/pengaturan` |

---

## ✅ STATUS

**Semua routes sudah dipisah dan berfungsi dengan baik!**

- ✅ Dashboard routes
- ✅ Manajemen Harga routes
- ✅ Feature Toggle routes
- ✅ User Management routes
- ✅ Waste Management routes
- ✅ Review System routes
- ✅ Laporan routes
- ✅ Pengaturan routes

**Struktur routes sekarang lebih rapi dan mudah di-maintain!** 🎉
