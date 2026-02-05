# Entity Relationship Diagram (ERD)
## UI GreenMetric POLBAN - Waste Management System

```mermaid
erDiagram
    %% Core Tables
    USERS ||--o{ WASTE_MANAGEMENT : creates
    USERS }o--|| UNIT : belongs_to
    UNIT ||--o{ USERS : has_many
    UNIT ||--o{ WASTE_MANAGEMENT : manages
    UNIT }o--o| UNIT : parent_child
    
    %% Waste Management Flow
    WASTE_MANAGEMENT }o--|| MASTER_HARGA_SAMPAH : references
    WASTE_MANAGEMENT }o--o| USERS : reviewed_by_tps
    WASTE_MANAGEMENT }o--o| USERS : reviewed_by_admin
    
    %% Pricing & Logs
    MASTER_HARGA_SAMPAH ||--o{ LOG_PERUBAHAN_HARGA : has_logs
    MASTER_HARGA_SAMPAH ||--o{ JENIS_SAMPAH : categorizes
    
    %% Notifications & Changes
    USERS ||--o{ NOTIFIKASI : receives
    WASTE_MANAGEMENT ||--o{ NOTIFIKASI : triggers
    USERS ||--o{ CHANGE_LOGS : creates
    
    %% Feature Management
    FEATURE_TOGGLES ||--o{ FEATURE_TOGGLE_LOGS : has_logs
    
    %% Assessment System
    TAHUN_PENILAIAN ||--o{ PENGIRIMAN_UNIT : has_submissions
    UNIT ||--o{ PENGIRIMAN_UNIT : submits
    PENGIRIMAN_UNIT }o--|| USERS : submitted_by
    PENGIRIMAN_UNIT ||--o{ REVIEW_KATEGORI : has_reviews
    KRITERIA_UIGM ||--o{ INDIKATOR : has_indicators
    
    %% Dashboard Settings
    DASHBOARD_SETTINGS }o--|| USERS : configured_by

    %% ========================================
    %% TABLE DEFINITIONS
    %% ========================================
    
    USERS {
        int id PK
        varchar username UK
        varchar email UK
        varchar password
        varchar nama_lengkap
        enum role "admin_pusat, admin_unit, user, pengelola_tps"
        int unit_id FK
        varchar gedung "Gedung user"
        boolean status_aktif
        datetime last_login
        datetime created_at
        datetime updated_at
    }
    
    UNIT {
        int id PK
        varchar nama_unit
        varchar kode_unit UK
        enum tipe_unit "fakultas, jurusan, unit_kerja, lembaga"
        int parent_id FK "Self-referencing"
        int admin_unit_id FK
        boolean status_aktif
        datetime created_at
        datetime updated_at
    }
    
    WASTE_MANAGEMENT {
        int id PK
        int unit_id FK
        int user_id FK "User yang input"
        int created_by FK
        date tanggal
        varchar jenis_sampah "Plastik, Kertas, Logam, dll"
        varchar nama_sampah "Detail jenis (Keyboard Bekas, dll)"
        varchar satuan "kg, ton, pcs"
        decimal jumlah
        decimal berat_kg
        varchar gedung
        varchar pengirim_gedung "Untuk TPS"
        enum kategori_sampah "bisa_dijual, tidak_bisa_dijual"
        decimal nilai_rupiah "Nilai jual sampah"
        enum status "draft, dikirim_ke_tps, disetujui_tps, ditolak_tps, dikirim_ke_admin, disetujui, ditolak"
        int tps_reviewed_by FK "User TPS yang review"
        datetime tps_reviewed_at
        text tps_catatan
        int admin_reviewed_by FK "Admin yang review"
        datetime admin_reviewed_at
        text admin_catatan
        varchar batch_id
        varchar bukti_foto
        varchar nama_pelapor
        varchar gedung_pelapor
        datetime created_at
        datetime updated_at
    }
    
    MASTER_HARGA_SAMPAH {
        int id PK
        varchar jenis_sampah UK "Plastik, Kertas, Logam, dll"
        varchar nama_jenis "Deskripsi lengkap"
        decimal harga_per_satuan
        decimal harga_per_kg
        varchar satuan
        tinyint dapat_dijual "1=ya, 0=tidak"
        tinyint status_aktif
        text deskripsi
        date tanggal_berlaku
        datetime created_at
        datetime updated_at
    }
    
    JENIS_SAMPAH {
        int id PK
        varchar kategori "Plastik, Kertas, Logam, dll"
        varchar nama_jenis "Detail jenis sampah"
        varchar satuan_default
        decimal harga_per_kg
        boolean dapat_dijual
        boolean status_aktif
        datetime created_at
        datetime updated_at
    }
    
    LOG_PERUBAHAN_HARGA {
        int id PK
        int master_harga_id FK
        decimal harga_lama
        decimal harga_baru
        int changed_by FK "User yang mengubah"
        text alasan_perubahan
        date tanggal_berlaku
        datetime created_at
    }
    
    NOTIFIKASI {
        int id PK
        int user_id FK "Penerima notifikasi"
        varchar judul
        text pesan
        enum tipe "info, success, warning, error"
        varchar link_terkait
        int waste_id FK "Terkait waste_management"
        boolean sudah_dibaca
        datetime dibaca_pada
        datetime created_at
    }
    
    CHANGE_LOGS {
        int id PK
        varchar table_name "Nama tabel yang diubah"
        int record_id "ID record yang diubah"
        varchar action "create, update, delete"
        text old_values "JSON"
        text new_values "JSON"
        int user_id FK "User yang melakukan perubahan"
        varchar ip_address
        varchar user_agent
        datetime created_at
    }
    
    FEATURE_TOGGLES {
        int id PK
        varchar feature_key UK
        varchar feature_name
        text description
        boolean is_enabled
        enum category "dashboard, waste, report, admin"
        json allowed_roles "Array role yang bisa akses"
        datetime created_at
        datetime updated_at
    }
    
    FEATURE_TOGGLE_LOGS {
        int id PK
        int feature_toggle_id FK
        boolean previous_state
        boolean new_state
        int changed_by FK
        text reason
        datetime created_at
    }
    
    DASHBOARD_SETTINGS {
        int id PK
        varchar setting_key UK
        varchar setting_name
        text setting_value "JSON"
        enum category "widget, layout, theme"
        int user_id FK "NULL = global setting"
        datetime created_at
        datetime updated_at
    }
    
    TAHUN_PENILAIAN {
        int id PK
        int tahun
        date tanggal_mulai
        date tanggal_selesai
        enum status "draft, aktif, selesai"
        datetime created_at
        datetime updated_at
    }
    
    KRITERIA_UIGM {
        int id PK
        varchar kode_kriteria
        varchar nama_kriteria
        text deskripsi
        decimal bobot
        int unit_id FK "NULL = untuk semua unit"
        boolean status_aktif
        datetime created_at
        datetime updated_at
    }
    
    INDIKATOR {
        int id PK
        int kriteria_id FK
        varchar kode_indikator
        varchar nama_indikator
        text deskripsi
        decimal bobot
        enum tipe_input "number, text, file, boolean"
        boolean wajib_diisi
        datetime created_at
        datetime updated_at
    }
    
    PENGIRIMAN_UNIT {
        int id PK
        int unit_id FK
        int tahun_penilaian_id FK
        int submitted_by FK
        datetime submitted_at
        enum status "draft, submitted, reviewed, approved, rejected"
        text catatan
        decimal total_skor
        datetime created_at
        datetime updated_at
    }
    
    REVIEW_KATEGORI {
        int id PK
        int pengiriman_id FK
        int kriteria_id FK
        int reviewer_id FK
        decimal skor
        text feedback
        enum status "pending, approved, revision"
        datetime reviewed_at
        datetime created_at
        datetime updated_at
    }
    
    RIWAYAT_VERSI {
        int id PK
        varchar versi
        text changelog
        date tanggal_rilis
        int released_by FK
        datetime created_at
    }
```

## Penjelasan Relasi Utama

### 1. User Management
- **USERS** → **UNIT**: Many-to-One (Setiap user belongs to satu unit)
- **UNIT** → **UNIT**: Self-referencing (Unit bisa punya parent unit)

### 2. Waste Management Flow
```
User Input → WASTE_MANAGEMENT (status: draft)
    ↓
Kirim ke TPS → (status: dikirim_ke_tps)
    ↓
TPS Review → (status: disetujui_tps / ditolak_tps)
    ↓
Admin Review → (status: disetujui / ditolak)
```

### 3. Pricing System
- **MASTER_HARGA_SAMPAH**: Master data harga sampah
- **JENIS_SAMPAH**: Detail jenis sampah dengan kategori
- **LOG_PERUBAHAN_HARGA**: Audit trail perubahan harga

### 4. Notification System
- **NOTIFIKASI**: Notifikasi untuk user terkait waste management
- Triggered by: approval, rejection, status changes

### 5. Feature Toggle System
- **FEATURE_TOGGLES**: Enable/disable fitur per role
- **FEATURE_TOGGLE_LOGS**: Audit trail perubahan feature

### 6. Assessment System (UI GreenMetric)
- **TAHUN_PENILAIAN**: Periode penilaian
- **KRITERIA_UIGM**: Kriteria penilaian (6 kategori)
- **INDIKATOR**: Indikator per kriteria
- **PENGIRIMAN_UNIT**: Submission dari unit
- **REVIEW_KATEGORI**: Review per kategori

## Status Flow Waste Management

```
draft → dikirim_ke_tps → disetujui_tps/ditolak_tps → dikirim_ke_admin → disetujui/ditolak
```

## User Roles

1. **admin_pusat**: Admin pusat (full access)
2. **admin_unit**: Admin unit (manage unit data)
3. **user**: User biasa (input waste data)
4. **pengelola_tps**: Pengelola TPS (review waste from users)

## Key Features

- ✅ Multi-level unit hierarchy
- ✅ Waste management with TPS approval flow
- ✅ Dynamic pricing system with audit trail
- ✅ Real-time notifications
- ✅ Feature toggle per role
- ✅ Change logs for audit
- ✅ UI GreenMetric assessment system
- ✅ Dashboard customization
