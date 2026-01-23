# Panduan Feature Toggle System

## Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Cara Menggunakan](#cara-menggunakan)
3. [Daftar Feature Toggle](#daftar-feature-toggle)
4. [Cara Menambah Feature Baru](#cara-menambah-feature-baru)
5. [Troubleshooting](#troubleshooting)

---

## Pengenalan

Feature Toggle adalah sistem untuk mengaktifkan/menonaktifkan fitur-fitur tertentu di aplikasi tanpa perlu mengubah kode. Ini berguna untuk:
- Mengontrol fitur yang tersedia untuk role tertentu
- Testing fitur baru tanpa mengganggu production
- Maintenance mode untuk fitur tertentu
- A/B testing

---

## Cara Menggunakan

### 1. Akses Feature Toggle Dashboard
- Login sebagai **Admin Pusat**
- Buka menu **Feature Toggle** di sidebar
- Anda akan melihat semua feature yang tersedia

### 2. Mengaktifkan/Menonaktifkan Feature
- Toggle switch di sebelah kanan setiap feature
- Masukkan alasan perubahan (opsional)
- Klik **Toggle** untuk konfirmasi

### 3. Bulk Toggle
- Klik tombol **Enable All** atau **Disable All**
- Pilih category (opsional)
- Masukkan alasan
- Klik **Execute**

### 4. Melihat Log Perubahan
- Klik tombol **View Logs**
- Lihat history perubahan feature toggle
- Filter berdasarkan feature, admin, atau tanggal

---

## Daftar Feature Toggle

### Dashboard Features
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `dashboard_statistics_cards` | Dashboard Statistics Cards | Kartu statistik di dashboard | User, TPS, Admin |
| `dashboard_waste_summary` | Dashboard Waste Summary | Ringkasan waste management | User, TPS |
| `dashboard_recent_activity` | Dashboard Recent Activity | Aktivitas terbaru | User, TPS, Admin |
| `dashboard_charts` | Dashboard Charts | Grafik dan chart | Admin |
| `dashboard_quick_stats` | Dashboard Quick Stats | Statistik cepat | User, TPS, Admin |

### Waste Management Features
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `waste_input_form` | Waste Input Form | Form input data sampah | User, TPS |
| `waste_edit_function` | Waste Edit Function | Edit data sampah | User, TPS |
| `waste_delete_function` | Waste Delete Function | Hapus data sampah | User, TPS |
| `waste_value_calculation` | Waste Value Calculation | Kalkulasi nilai sampah | User, TPS |
| `waste_category_filter` | Waste Category Filter | Filter berdasarkan kategori | User, TPS, Admin |
| `waste_status_filter` | Waste Status Filter | Filter berdasarkan status | User, TPS, Admin |
| `waste_price_info` | Waste Price Information | Info harga sampah | User, TPS |

### Export & Report Features
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `export_data` | Export Data | Export ke CSV/Excel | User, TPS, Admin |
| `export_pdf` | Export PDF | Export ke PDF | User, TPS, Admin |
| `laporan_waste` | Laporan Waste | Laporan waste management | Admin |
| `laporan_filter` | Laporan Filter | Filter laporan | Admin |

### Admin Features
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `admin_review_waste` | Admin Review Waste | Review dan approve data | Admin |
| `admin_user_management` | Admin User Management | Manajemen user | Admin |
| `admin_harga_management` | Admin Harga Management | Manajemen harga sampah | Admin |
| `admin_waste_management` | Admin Waste Management | Manajemen data sampah | Admin |
| `admin_unit_management` | Admin Unit Management | Manajemen unit/TPS | Admin |

### UI Components
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `sidebar_quick_actions` | Sidebar Quick Actions | Tombol aksi cepat | User, TPS |
| `help_tooltips` | Help Tooltips | Tooltip bantuan | User, TPS |
| `notification_alerts` | Notification Alerts | Notifikasi dan alert | User, TPS, Admin |
| `breadcrumb_navigation` | Breadcrumb Navigation | Navigasi breadcrumb | User, TPS, Admin |
| `search_function` | Search Function | Fungsi pencarian | User, TPS, Admin |
| `pagination` | Pagination | Pagination tabel | User, TPS, Admin |

### System Features
| Feature Key | Nama | Deskripsi | Target Roles |
|------------|------|-----------|--------------|
| `auto_logout` | Auto Logout | Logout otomatis | User, TPS, Admin |
| `session_management` | Session Management | Manajemen session | User, TPS, Admin |
| `audit_log` | Audit Log | Log aktivitas | Admin |
| `data_validation` | Data Validation | Validasi data input | User, TPS, Admin |
| `backup_reminder` | Backup Reminder | Pengingat backup | Admin |

---

## Cara Menambah Feature Baru

### 1. Tambah di Database
```sql
INSERT INTO feature_toggles (
    feature_key, 
    feature_name, 
    description, 
    category, 
    is_enabled, 
    target_roles, 
    config, 
    created_at, 
    updated_at
) VALUES (
    'nama_feature_baru',
    'Nama Feature Baru',
    'Deskripsi feature',
    'category_name',
    1,
    '["user", "pengelola_tps"]',
    '{"option1": true}',
    NOW(),
    NOW()
);
```

### 2. Gunakan di Kode
```php
// Di Controller atau View
if (isFeatureEnabled('nama_feature_baru', 'user')) {
    // Tampilkan atau jalankan feature
}

// Dengan config
$config = getFeatureConfig('nama_feature_baru');
if ($config['option1']) {
    // Lakukan sesuatu
}
```

### 3. Tambah di View (Conditional Rendering)
```php
<?php if (isFeatureEnabled('nama_feature_baru')): ?>
    <div class="feature-content">
        <!-- Konten feature -->
    </div>
<?php endif; ?>
```

---

## Troubleshooting

### Feature Tidak Muncul Setelah Diaktifkan
1. **Clear browser cache** (Ctrl+F5)
2. **Logout dan login kembali**
3. **Cek log error** di `writable/logs/`
4. **Verifikasi di database**:
   ```sql
   SELECT * FROM feature_toggles WHERE feature_key = 'nama_feature';
   ```

### Toggle Tidak Berfungsi
1. **Cek role user** - Pastikan role user ada di `target_roles`
2. **Cek helper function** - Pastikan `feature_helper.php` ter-load
3. **Cek database connection** - Pastikan koneksi database OK
4. **Lihat log**:
   ```bash
   tail -f writable/logs/log-*.log
   ```

### Error "Feature toggle error"
1. **Cek tabel `feature_toggles` ada**:
   ```sql
   SHOW TABLES LIKE 'feature_toggles';
   ```
2. **Jalankan migration** jika tabel belum ada:
   ```bash
   php spark migrate
   ```
3. **Insert data awal**:
   ```bash
   mysql -u root -p eksperimen < UPDATE_FEATURE_TOGGLE_LENGKAP.sql
   ```

### Feature Selalu Return True/False
1. **Cek fallback di helper** - Ada list critical features yang selalu true
2. **Cek cache** - Restart PHP atau web server
3. **Debug dengan log**:
   ```php
   log_message('debug', 'Feature check: ' . $feature . ' = ' . (isFeatureEnabled($feature) ? 'true' : 'false'));
   ```

---

## Best Practices

### 1. Naming Convention
- Gunakan `snake_case` untuk feature key
- Prefix dengan category: `dashboard_`, `waste_`, `admin_`, dll
- Nama harus deskriptif dan jelas

### 2. Target Roles
- Gunakan array JSON: `["user", "pengelola_tps", "admin_pusat"]`
- Jangan hardcode role di kode, gunakan feature toggle
- Review role access secara berkala

### 3. Configuration
- Simpan config dalam JSON format
- Gunakan untuk setting yang bisa berubah
- Contoh: `{"max_items": 10, "show_details": true}`

### 4. Testing
- Test feature dalam kondisi enabled dan disabled
- Test untuk setiap role yang berbeda
- Test fallback behavior saat error

### 5. Documentation
- Dokumentasikan setiap feature baru
- Update panduan ini saat menambah feature
- Catat alasan saat toggle feature

---

## Contoh Penggunaan

### Contoh 1: Conditional Button
```php
<?php if (isFeatureEnabled('export_pdf', 'user')): ?>
    <a href="<?= base_url('/user/waste/export-pdf') ?>" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
<?php endif; ?>
```

### Contoh 2: Conditional Section
```php
<?php if (isFeatureEnabled('dashboard_waste_summary')): ?>
    <div class="waste-summary-section">
        <h3>Ringkasan Waste Management</h3>
        <!-- Konten ringkasan -->
    </div>
<?php endif; ?>
```

### Contoh 3: With Configuration
```php
<?php 
$config = getFeatureConfig('pagination');
$itemsPerPage = $config['items_per_page'] ?? 10;
?>

<div class="pagination">
    <!-- Pagination dengan $itemsPerPage -->
</div>
```

### Contoh 4: In Controller
```php
public function index()
{
    if (!isFeatureEnabled('waste_input_form', 'user')) {
        return redirect()->back()->with('error', 'Fitur tidak tersedia');
    }
    
    // Lanjutkan proses normal
}
```

---

## Update History

- **2025-01-19**: Initial feature toggle system dengan 35+ features
- **2025-01-19**: Tambah caching untuk performance
- **2025-01-19**: Tambah fallback untuk critical features
- **2025-01-19**: Update dokumentasi lengkap

---

## Support

Jika ada pertanyaan atau masalah:
1. Cek dokumentasi ini terlebih dahulu
2. Lihat log error di `writable/logs/`
3. Hubungi tim development

---

**Catatan**: Feature toggle adalah tool yang powerful, gunakan dengan bijak. Jangan terlalu banyak toggle yang bisa membuat sistem kompleks dan sulit di-maintain.
