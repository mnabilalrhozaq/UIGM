# ERD Simplified - UI GreenMetric POLBAN

## Diagram Relasi Utama

```mermaid
graph TB
    subgraph "User Management"
        U[USERS<br/>id, username, email, role, unit_id]
        UN[UNIT<br/>id, nama_unit, kode_unit, parent_id]
        U -->|belongs to| UN
        UN -->|has many| U
    end
    
    subgraph "Waste Management Core"
        WM[WASTE_MANAGEMENT<br/>id, user_id, unit_id, jenis_sampah<br/>status, berat_kg, nilai_rupiah]
        MHS[MASTER_HARGA_SAMPAH<br/>id, jenis_sampah, harga_per_kg]
        JS[JENIS_SAMPAH<br/>id, kategori, nama_jenis]
        
        U -->|creates| WM
        WM -->|references| MHS
        MHS -->|categorizes| JS
    end
    
    subgraph "Approval Flow"
        WM -->|reviewed by TPS| U
        WM -->|reviewed by Admin| U
        N[NOTIFIKASI<br/>id, user_id, waste_id, pesan]
        WM -->|triggers| N
        N -->|sent to| U
    end
    
    subgraph "Audit & Logs"
        LPH[LOG_PERUBAHAN_HARGA<br/>id, master_harga_id, harga_lama, harga_baru]
        CL[CHANGE_LOGS<br/>id, table_name, record_id, action]
        MHS -->|has logs| LPH
        U -->|creates| CL
    end
    
    subgraph "Feature Management"
        FT[FEATURE_TOGGLES<br/>id, feature_key, is_enabled]
        FTL[FEATURE_TOGGLE_LOGS<br/>id, feature_toggle_id, changed_by]
        FT -->|has logs| FTL
    end
    
    style U fill:#e1f5ff
    style WM fill:#fff3e0
    style MHS fill:#f3e5f5
    style N fill:#e8f5e9
```

## Workflow Waste Management

```mermaid
stateDiagram-v2
    [*] --> Draft: User creates
    Draft --> DikirimKeTPS: User submits
    DikirimKeTPS --> DisetujuiTPS: TPS approves
    DikirimKeTPS --> DitolakTPS: TPS rejects
    DisetujuiTPS --> DikirimKeAdmin: Auto forward
    DitolakTPS --> [*]: End (rejected by TPS)
    DikirimKeAdmin --> Disetujui: Admin approves
    DikirimKeAdmin --> Ditolak: Admin rejects
    Disetujui --> [*]: End (approved)
    Ditolak --> [*]: End (rejected by Admin)
    
    note right of DikirimKeTPS
        TPS reviews waste data
        from users
    end note
    
    note right of DisetujuiTPS
        Data masuk ke sistem TPS
        Bisa dihitung nilai jual
    end note
```

## Database Tables Summary

| Table | Purpose | Key Fields |
|-------|---------|------------|
| **users** | User accounts | id, username, email, role, unit_id |
| **unit** | Organizational units | id, nama_unit, kode_unit, parent_id |
| **waste_management** | Waste data records | id, user_id, jenis_sampah, status, berat_kg |
| **master_harga_sampah** | Waste pricing master | id, jenis_sampah, harga_per_kg |
| **jenis_sampah** | Waste type details | id, kategori, nama_jenis |
| **notifikasi** | User notifications | id, user_id, waste_id, pesan |
| **log_perubahan_harga** | Price change audit | id, master_harga_id, harga_lama, harga_baru |
| **change_logs** | System audit trail | id, table_name, record_id, action |
| **feature_toggles** | Feature flags | id, feature_key, is_enabled |
| **dashboard_settings** | Dashboard config | id, setting_key, setting_value |

## User Roles & Permissions

```mermaid
graph LR
    A[admin_pusat] -->|Full Access| ALL[All Features]
    B[pengelola_tps] -->|Review| WM[Waste Management]
    C[user] -->|Input| WD[Waste Data]
    D[admin_unit] -->|Manage| UD[Unit Data]
    
    style A fill:#ff6b6b
    style B fill:#4ecdc4
    style C fill:#95e1d3
    style D fill:#f38181
```

## Key Relationships

### 1. User → Waste Management
- One user can create many waste records
- Each waste record belongs to one user

### 2. Unit → Users
- One unit has many users
- Each user belongs to one unit

### 3. Waste Management → Master Harga
- Waste records reference master pricing
- Pricing determines if waste can be sold

### 4. TPS Approval Flow
- User submits → TPS reviews → Admin reviews
- Each step has reviewer tracking

### 5. Notifications
- Triggered by status changes
- Sent to relevant users (submitter, reviewers)

## Status Values

### Waste Management Status
- `draft` - Initial state
- `dikirim_ke_tps` - Sent to TPS for review
- `disetujui_tps` - Approved by TPS
- `ditolak_tps` - Rejected by TPS
- `dikirim_ke_admin` - Sent to Admin
- `disetujui` - Final approval
- `ditolak` - Final rejection

### User Roles
- `admin_pusat` - Central admin
- `pengelola_tps` - TPS manager
- `user` - Regular user
- `admin_unit` - Unit admin

## Important Notes

1. **Waste Flow**: User → TPS → Admin (two-level approval)
2. **Pricing**: Dynamic pricing from master_harga_sampah
3. **Audit Trail**: All changes logged in change_logs
4. **Notifications**: Real-time updates for status changes
5. **Feature Toggles**: Enable/disable features per role
