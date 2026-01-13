# Feature Toggle - Panduan Lengkap

## 📋 Daftar Isi
1. [Instalasi](#instalasi)
2. [Cara Kerja](#cara-kerja)
3. [Fitur yang Tersedia](#fitur-yang-tersedia)
4. [Penggunaan](#penggunaan)
5. [Perbedaan Per Role](#perbedaan-per-role)

---

## 🚀 Instalasi

### 1. Buat Tabel Database

Jalankan SQL ini di phpMyAdmin:

```sql
-- Buka file: database/migrations/create_feature_toggles_table.sql
-- Copy semua isi file dan jalankan di phpMyAdmin
```

Atau langsung:
1. Buka phpMyAdmin
2. Pilih database `uigm_polban`
3. Klik tab "SQL"
4. Copy-paste isi file `database/migrations/create_feature_toggles_table.sql`
5. Klik "Go"

### 2. Verifikasi Instalasi

Cek apakah tabel sudah dibuat:
```sql
SELECT * FROM feature_toggles;
```

Seharusnya ada 14 fitur default.

---

## ⚙️ Cara Kerja

### Arsitektur

```
User Dashboard
    ↓
Helper: isFeatureEnabled('feature_key', 'role')
    ↓
Model: FeatureToggleModel
    ↓
Database: feature_toggles table
    ↓
Return: true/false
```

### Flow Toggle

1. **Admin Pusat** buka menu "Feature Toggle"
2. Klik switch ON/OFF pada fitur
3. Status tersimpan di database
4. **User/Pengelola TPS** refresh dashboard
5. Fitur muncul/hilang sesuai status toggle

---

## 📦 Fitur yang Tersedia

### Dashboard Features
| Feature Key | Nama | Target Role | Default |
|------------|------|-------------|---------|
| `dashboard_statistics_cards` | Dashboard Statistics Cards | All | ON |
| `dashboard_waste_summary` | Dashboard Waste Summary | All | ON |
| `dashboard_recent_activity` | Dashboard Recent Activity | All | ON |
| `detailed_statistics` | Detailed Statistics | Admin, User | ON |

### Waste Management
| Feature Key | Nama | Target Role | Default |
|------------|------|-------------|---------|
| `waste_management` | Waste Management | User, TPS | ON |
| `waste_value_calculation` | Waste Value Calculation | User, TPS | ON |
| `export_data` | Export Data | All | ON |

### Management Features
| Feature Key | Nama | Target Role | Default |
|------------|------|-------------|---------|
| `price_management` | Price Management | Admin | ON |
| `user_management` | User Management | Admin | ON |
| `review_system` | Review System | Admin | ON |
| `reporting` | Reporting & Analytics | Admin | ON |

### System Features
| Feature Key | Nama | Target Role | Default |
|------------|------|-------------|---------|
| `real_time_updates` | Real-time Updates | User, TPS | OFF |
| `sidebar_quick_actions` | Sidebar Quick Actions | User, TPS | ON |
| `help_tooltips` | Help Tooltips | User, TPS | ON |

---

## 🎯 Penggunaan

### Untuk Admin Pusat

#### 1. Akses Feature Toggle
- Login sebagai Admin Pusat
- Klik menu "Feature Toggle" di sidebar
- Lihat semua fitur yang tersedia

#### 2. Toggle Fitur Individual
- Klik switch ON/OFF pada fitur yang ingin diubah
- Masukkan alasan perubahan (opsional)
- Klik "Toggle"
- Status langsung berubah

#### 3. Toggle Bulk (Per Kategori)
- Klik "Enable All" untuk aktifkan semua fitur dalam kategori
- Klik "Disable All" untuk nonaktifkan semua fitur dalam kategori

#### 4. Lihat Konfigurasi
- Klik tombol "Config" pada fitur
- Edit konfigurasi JSON
- Klik "Save"

### Untuk Developer

#### Cek Fitur di View

```php
<?php if (isFeatureEnabled('dashboard_statistics_cards', 'user')): ?>
    <!-- Konten yang akan muncul jika fitur aktif -->
    <div class="stats-cards">
        ...
    </div>
<?php endif; ?>
```

#### Cek Fitur di Controller

```php
if (isFeatureEnabled('export_data', $userRole)) {
    // Proses export
    return $this->exportData();
}
```

#### Get Konfigurasi Fitur

```php
$config = getFeatureConfig('real_time_updates');
$refreshInterval = $config['refresh_interval'] ?? 30;
```

---

## 👥 Perbedaan Per Role

### Admin Pusat
**Fitur yang Terlihat:**
- ✅ Dashboard Statistics Cards
- ✅ Dashboard Waste Summary
- ✅ Dashboard Recent Activity
- ✅ Price Management
- ✅ User Management
- ✅ Review System
- ✅ Reporting & Analytics
- ✅ Export Data
- ✅ Detailed Statistics

**Fitur yang TIDAK Terlihat:**
- ❌ Real-time Updates (khusus User/TPS)
- ❌ Waste Management (khusus User/TPS)
- ❌ Sidebar Quick Actions (khusus User/TPS)

### User (Unit)
**Fitur yang Terlihat:**
- ✅ Dashboard Statistics Cards
- ✅ Dashboard Waste Summary
- ✅ Dashboard Recent Activity
- ✅ Waste Management
- ✅ Waste Value Calculation
- ✅ Export Data
- ✅ Real-time Updates
- ✅ Sidebar Quick Actions
- ✅ Help Tooltips
- ✅ Detailed Statistics

**Fitur yang TIDAK Terlihat:**
- ❌ Price Management (khusus Admin)
- ❌ User Management (khusus Admin)
- ❌ Review System (khusus Admin)
- ❌ Reporting (khusus Admin)

### Pengelola TPS
**Fitur yang Terlihat:**
- ✅ Dashboard Statistics Cards
- ✅ Dashboard Waste Summary
- ✅ Dashboard Recent Activity
- ✅ Waste Management
- ✅ Waste Value Calculation
- ✅ Export Data
- ✅ Real-time Updates
- ✅ Sidebar Quick Actions
- ✅ Help Tooltips

**Fitur yang TIDAK Terlihat:**
- ❌ Price Management (khusus Admin)
- ❌ User Management (khusus Admin)
- ❌ Review System (khusus Admin)
- ❌ Reporting (khusus Admin)
- ❌ Detailed Statistics (khusus Admin/User)

---

## 🧪 Testing

### Test 1: Toggle Fitur
1. Login sebagai Admin Pusat
2. Buka Feature Toggle
3. Matikan "Dashboard Statistics Cards"
4. Logout
5. Login sebagai User
6. Cek dashboard - kartu statistik harus hilang

### Test 2: Role-based Access
1. Login sebagai User
2. Cek dashboard - tidak ada menu "Price Management"
3. Login sebagai Admin Pusat
4. Cek dashboard - ada menu "Price Management"

### Test 3: Bulk Toggle
1. Login sebagai Admin Pusat
2. Buka Feature Toggle
3. Klik "Disable All" pada kategori "Dashboard Features"
4. Login sebagai User
5. Dashboard harus kosong (semua fitur dashboard mati)

---

## 🔧 Troubleshooting

### Fitur Tidak Muncul/Hilang

**Cek 1: Status di Database**
```sql
SELECT feature_key, is_enabled FROM feature_toggles WHERE feature_key = 'nama_fitur';
```

**Cek 2: Role User**
```sql
SELECT feature_key, target_roles FROM feature_toggles WHERE feature_key = 'nama_fitur';
```

**Cek 3: Helper Function**
```php
// Di controller atau view
var_dump(isFeatureEnabled('nama_fitur', 'user'));
```

### Error "Table feature_toggles doesn't exist"

Jalankan SQL migration:
```bash
# Buka phpMyAdmin dan jalankan:
source database/migrations/create_feature_toggles_table.sql
```

### Toggle Tidak Berfungsi

1. Cek CSRF token di browser console
2. Cek error log: `writable/logs/log-*.log`
3. Pastikan user login sebagai Admin Pusat

---

## 📝 Catatan Penting

1. **Hanya Admin Pusat** yang bisa toggle fitur
2. **Perubahan langsung berlaku** setelah user refresh halaman
3. **Fitur critical** (waste_management, dashboard_statistics_cards) sebaiknya selalu ON
4. **Backup database** sebelum bulk toggle
5. **Test di development** sebelum production

---

## 🎓 Best Practices

1. **Jangan matikan fitur critical** saat jam kerja
2. **Beri alasan** saat toggle fitur (untuk audit)
3. **Test dulu** dengan 1 fitur sebelum bulk toggle
4. **Komunikasikan** ke user sebelum matikan fitur penting
5. **Monitor log** setelah perubahan besar

---

## 📞 Support

Jika ada masalah:
1. Cek file log: `writable/logs/`
2. Cek database: tabel `feature_toggles`
3. Cek helper: `app/Helpers/feature_helper.php`
4. Cek model: `app/Models/FeatureToggleModel.php`
