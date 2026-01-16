# Penyimpanan Data Waste yang Sudah Disetujui/Ditolak

## Jawaban Singkat
Data waste yang sudah disetujui atau ditolak **TETAP DISIMPAN DI TABEL YANG SAMA**, yaitu tabel `waste_management`. Yang berubah hanya **field `status`** nya.

## Detail Penyimpanan

### Nama Tabel Database
```
waste_management
```

### Lokasi
Semua data waste (draft, dikirim, disetujui, ditolak) disimpan di **SATU TABEL** yang sama.

### Field Status
```sql
status ENUM('draft', 'dikirim', 'review', 'disetujui', 'perlu_revisi')
```

## Alur Status Data

```
┌─────────────────────────────────────────────────────────┐
│                  TABEL: waste_management                 │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  User/TPS Input Data                                    │
│  ↓                                                       │
│  status = 'draft'        (Data masih draft)             │
│  ↓                                                       │
│  User/TPS Submit                                        │
│  ↓                                                       │
│  status = 'dikirim'      (Menunggu review admin)        │
│  ↓                                                       │
│  Admin Review                                           │
│  ↓                                                       │
│  ┌─────────────┬─────────────┐                         │
│  │             │             │                          │
│  ↓             ↓             ↓                          │
│  APPROVE      REJECT      (Tidak ada aksi)             │
│  ↓             ↓                                        │
│  status =     status =                                  │
│  'disetujui'  'perlu_revisi'                           │
│                                                          │
│  SEMUA TETAP DI TABEL YANG SAMA!                        │
└─────────────────────────────────────────────────────────┘
```

## Struktur Tabel `waste_management`

### Field Utama:
```sql
CREATE TABLE waste_management (
    id INT PRIMARY KEY AUTO_INCREMENT,
    unit_id INT,
    tps_id INT,
    kategori_id INT,
    created_by INT,
    tanggal DATE,
    jenis_sampah VARCHAR(50),
    satuan VARCHAR(10),
    jumlah DECIMAL(10,2),
    berat_kg DECIMAL(10,2),
    gedung VARCHAR(50),
    pengirim_gedung VARCHAR(100),
    kategori_sampah VARCHAR(50),
    nilai_rupiah DECIMAL(15,2),
    status ENUM('draft', 'dikirim', 'review', 'disetujui', 'perlu_revisi'),
    catatan_admin TEXT,
    created_at DATETIME,
    updated_at DATETIME
);
```

### Field Penting untuk Status:
1. **status** - Status data (draft/dikirim/disetujui/perlu_revisi)
2. **catatan_admin** - Catatan dari admin (alasan reject, dll)
3. **updated_at** - Waktu terakhir diupdate (termasuk saat approve/reject)

## Contoh Data di Database

### Sebelum Approve/Reject:
```sql
id | unit_id | jenis_sampah | berat_kg | status    | catatan_admin | created_at          | updated_at
---|---------|--------------|----------|-----------|---------------|---------------------|--------------------
1  | 5       | Plastik      | 10.5     | dikirim   | NULL          | 2024-01-10 08:00:00 | 2024-01-10 08:00:00
2  | 3       | Kertas       | 5.2      | dikirim   | NULL          | 2024-01-10 09:00:00 | 2024-01-10 09:00:00
```

### Setelah Approve (ID 1):
```sql
id | unit_id | jenis_sampah | berat_kg | status     | catatan_admin      | created_at          | updated_at
---|---------|--------------|----------|------------|--------------------|---------------------|--------------------
1  | 5       | Plastik      | 10.5     | disetujui  | Data sudah sesuai  | 2024-01-10 08:00:00 | 2024-01-10 10:00:00
2  | 3       | Kertas       | 5.2      | dikirim    | NULL               | 2024-01-10 09:00:00 | 2024-01-10 09:00:00
```

### Setelah Reject (ID 2):
```sql
id | unit_id | jenis_sampah | berat_kg | status        | catatan_admin           | created_at          | updated_at
---|---------|--------------|----------|---------------|-------------------------|---------------------|--------------------
1  | 5       | Plastik      | 10.5     | disetujui     | Data sudah sesuai       | 2024-01-10 08:00:00 | 2024-01-10 10:00:00
2  | 3       | Kertas       | 5.2      | perlu_revisi  | Data tidak lengkap      | 2024-01-10 09:00:00 | 2024-01-10 10:30:00
```

## Query untuk Mengambil Data

### Data yang Disetujui:
```sql
SELECT * FROM waste_management 
WHERE status = 'disetujui'
ORDER BY updated_at DESC;
```

### Data yang Ditolak/Perlu Revisi:
```sql
SELECT * FROM waste_management 
WHERE status = 'perlu_revisi'
ORDER BY updated_at DESC;
```

### Data Menunggu Review:
```sql
SELECT * FROM waste_management 
WHERE status = 'dikirim'
ORDER BY created_at ASC;
```

### Semua Data (Untuk Admin):
```sql
SELECT * FROM waste_management 
ORDER BY created_at DESC;
```

## Keuntungan Sistem Ini

### 1. Satu Sumber Data
- ✅ Tidak perlu tabel terpisah untuk approved/rejected
- ✅ Mudah tracking history
- ✅ Mudah query dan reporting

### 2. Audit Trail
- ✅ Semua perubahan status tercatat
- ✅ Timestamp approve/reject tersimpan di `updated_at`
- ✅ Catatan admin tersimpan di `catatan_admin`

### 3. Fleksibilitas
- ✅ Data bisa di-revisi dan submit ulang
- ✅ Admin bisa ubah status kapan saja
- ✅ History lengkap tersimpan

### 4. Efisiensi
- ✅ Tidak ada duplikasi data
- ✅ Query lebih sederhana
- ✅ Storage lebih efisien

## Cara Kerja di Code

### Model: WasteModel.php
```php
protected $table = 'waste_management';  // Satu tabel untuk semua

protected $allowedFields = [
    'unit_id',
    'jenis_sampah',
    'berat_kg',
    'status',           // Field status
    'catatan_admin',    // Field catatan admin
    // ... fields lainnya
];
```

### Approve di Controller:
```php
public function approve($id)
{
    $wasteModel = new WasteModel();
    
    // Update status menjadi 'disetujui'
    $wasteModel->update($id, [
        'status' => 'disetujui',
        'catatan_admin' => 'Data sudah sesuai',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    // Data TETAP di tabel waste_management
    // Hanya status yang berubah!
}
```

### Reject di Controller:
```php
public function reject($id)
{
    $wasteModel = new WasteModel();
    
    // Update status menjadi 'perlu_revisi'
    $wasteModel->update($id, [
        'status' => 'perlu_revisi',
        'catatan_admin' => 'Data tidak lengkap, mohon dilengkapi',
        'updated_at' => date('Y-m-d H:i:s')
    ]);
    
    // Data TETAP di tabel waste_management
    // Hanya status yang berubah!
}
```

## Laporan Waste

### Data yang Muncul di Laporan:
Hanya data dengan **status = 'disetujui'** yang muncul di laporan waste.

```php
// Di LaporanWaste Controller
$approvedWaste = $wasteModel
    ->where('status', 'disetujui')  // Filter hanya yang disetujui
    ->orderBy('tanggal', 'DESC')
    ->findAll();
```

### Data yang Tidak Muncul di Laporan:
- ❌ status = 'draft'
- ❌ status = 'dikirim'
- ❌ status = 'perlu_revisi'

## Statistik

### Dashboard Admin:
```php
// Menunggu Review
$menunggu = $wasteModel->where('status', 'dikirim')->countAllResults();

// Disetujui
$disetujui = $wasteModel->where('status', 'disetujui')->countAllResults();

// Perlu Revisi
$perluRevisi = $wasteModel->where('status', 'perlu_revisi')->countAllResults();
```

## Backup & Recovery

### Backup Data:
```sql
-- Backup semua data
SELECT * FROM waste_management 
INTO OUTFILE '/backup/waste_management_backup.csv';

-- Backup hanya yang disetujui
SELECT * FROM waste_management 
WHERE status = 'disetujui'
INTO OUTFILE '/backup/waste_approved.csv';
```

### Recovery:
Karena semua data di satu tabel, recovery lebih mudah:
```sql
-- Restore dari backup
LOAD DATA INFILE '/backup/waste_management_backup.csv'
INTO TABLE waste_management;
```

## Kesimpulan

### Jawaban Singkat:
**Data yang sudah disetujui atau ditolak TETAP DISIMPAN DI TABEL `waste_management`**

### Yang Berubah:
1. **Field `status`**:
   - Approve → `status = 'disetujui'`
   - Reject → `status = 'perlu_revisi'`

2. **Field `catatan_admin`**:
   - Diisi dengan catatan/alasan dari admin

3. **Field `updated_at`**:
   - Timestamp saat approve/reject

### Tidak Ada Tabel Terpisah:
- ❌ Tidak ada tabel `waste_approved`
- ❌ Tidak ada tabel `waste_rejected`
- ✅ Semua di tabel `waste_management`

### Keuntungan:
- ✅ Satu sumber data
- ✅ Mudah tracking
- ✅ Mudah reporting
- ✅ Efisien storage
- ✅ Audit trail lengkap

**Jadi, data yang sudah disetujui/ditolak tetap di tabel `waste_management`, hanya statusnya yang berubah!** 📊
