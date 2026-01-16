# Implementasi: Data Approved/Rejected ke Tabel Terpisah

## Overview
Mengubah sistem agar data waste yang sudah disetujui/ditolak disimpan ke tabel terpisah (`waste_approved` dan `waste_rejected`) dan laporan waste mengambil data dari tabel `waste_approved`.

## Perubahan Struktur Database

### Tabel Baru:

#### 1. `waste_approved`
Menyimpan data waste yang sudah disetujui admin.

**Fields**:
- `id` - Primary key
- `waste_id` - Reference ke waste_management
- `unit_id`, `tps_id`, `kategori_id`
- `user_id` - User yang input
- `admin_id` - Admin yang approve
- `tanggal` - Tanggal data waste
- `jenis_sampah`, `nama_jenis`
- `satuan`, `jumlah`, `berat_kg`
- `harga_per_kg`, `nilai_ekonomis`
- `gedung`, `kategori_sampah`
- `dapat_dijual`
- `catatan_admin`
- `tanggal_approve` - Timestamp approve
- `created_at`, `updated_at`

#### 2. `waste_rejected`
Menyimpan history data waste yang ditolak.

**Fields**:
- `id` - Primary key
- `waste_id` - Reference ke waste_management
- `unit_id`, `tps_id`, `kategori_id`
- `user_id` - User yang input
- `admin_id` - Admin yang reject
- `tanggal` - Tanggal data waste
- `jenis_sampah`, `nama_jenis`
- `satuan`, `jumlah`, `berat_kg`
- `alasan_reject` - Alasan ditolak
- `tanggal_reject` - Timestamp reject
- `created_at`, `updated_at`

### SQL File:
```
CREATE_WASTE_APPROVED_TABLE.sql
```

## Model Baru

### 1. WasteApprovedModel.php
**Location**: `app/Models/WasteApprovedModel.php`

**Methods**:
- `getByDateRange()` - Get data by periode
- `getStatistics()` - Get statistik (total data, berat, nilai, unit)
- `getSummaryByJenis()` - Rekap per jenis sampah
- `getSummaryByUnit()` - Rekap per unit
- `getMonthlyTrend()` - Trend bulanan
- `getAllJenisSampah()` - List semua jenis sampah

### 2. WasteRejectedModel.php
**Location**: `app/Models/WasteRejectedModel.php`

**Methods**:
- `getByDateRange()` - Get data rejected by periode
- `getStatistics()` - Get statistik rejected

## Perubahan Logic Approve/Reject

### Flow Approve (BARU):

```php
// Di WasteService::approveWaste()

1. Get data dari waste_management
2. Validasi data
3. Get harga dari master_harga_sampah
4. Calculate nilai ekonomis
5. INSERT ke waste_approved dengan data lengkap:
   - Copy semua field dari waste_management
   - Tambah admin_id (dari session)
   - Tambah tanggal_approve (now)
   - Tambah harga_per_kg
   - Tambah nilai_ekonomis
6. UPDATE waste_management:
   - status = 'disetujui'
   - catatan_admin = catatan dari admin
7. Return success
```

### Flow Reject (BARU):

```php
// Di WasteService::rejectWaste()

1. Get data dari waste_management
2. Validasi data
3. Validasi alasan_reject wajib diisi
4. INSERT ke waste_rejected:
   - Copy field dari waste_management
   - Tambah admin_id (dari session)
   - Tambah alasan_reject
   - Tambah tanggal_reject (now)
5. UPDATE waste_management:
   - status = 'perlu_revisi'
   - catatan_admin = alasan_reject
6. Return success
```

## Update WasteService

**File**: `app/Services/Admin/WasteService.php`

### Method approveWaste() - UPDATE:

```php
public function approveWaste($id, $postData)
{
    try {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // 1. Get waste data
        $wasteModel = new \App\Models\WasteModel();
        $waste = $wasteModel->find($id);
        
        if (!$waste) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }
        
        // 2. Get harga from master
        $hargaModel = new \App\Models\HargaSampahModel();
        $harga = $hargaModel->where('jenis_sampah', $waste['jenis_sampah'])->first();
        
        $hargaPerKg = $harga['harga_per_satuan'] ?? 0;
        $dapatDijual = $harga['dapat_dijual'] ?? 0;
        
        // 3. Calculate nilai ekonomis
        $nilaiEkonomis = 0;
        if ($dapatDijual && $hargaPerKg > 0) {
            $nilaiEkonomis = $waste['berat_kg'] * $hargaPerKg;
        }
        
        // 4. Insert to waste_approved
        $wasteApprovedModel = new \App\Models\WasteApprovedModel();
        $session = session();
        $admin = $session->get('user');
        
        $approvedData = [
            'waste_id' => $waste['id'],
            'unit_id' => $waste['unit_id'],
            'tps_id' => $waste['tps_id'],
            'kategori_id' => $waste['kategori_id'],
            'user_id' => $waste['created_by'],
            'admin_id' => $admin['id'],
            'tanggal' => $waste['tanggal'],
            'jenis_sampah' => $waste['jenis_sampah'],
            'nama_jenis' => $harga['nama_jenis'] ?? $waste['jenis_sampah'],
            'satuan' => $waste['satuan'],
            'jumlah' => $waste['jumlah'],
            'berat_kg' => $waste['berat_kg'],
            'harga_per_kg' => $hargaPerKg,
            'nilai_ekonomis' => $nilaiEkonomis,
            'gedung' => $waste['gedung'],
            'kategori_sampah' => $waste['kategori_sampah'],
            'dapat_dijual' => $dapatDijual,
            'catatan_admin' => $postData['catatan'] ?? 'Data disetujui',
            'tanggal_approve' => date('Y-m-d H:i:s')
        ];
        
        $wasteApprovedModel->insert($approvedData);
        
        // 5. Update waste_management status
        $wasteModel->update($id, [
            'status' => 'disetujui',
            'catatan_admin' => $postData['catatan'] ?? 'Data disetujui'
        ]);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return ['success' => false, 'message' => 'Gagal menyetujui data'];
        }
        
        return ['success' => true, 'message' => 'Data berhasil disetujui'];
        
    } catch (\Exception $e) {
        log_message('error', 'Approve Waste Error: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}
```

### Method rejectWaste() - UPDATE:

```php
public function rejectWaste($id, $postData)
{
    try {
        $db = \Config\Database::connect();
        $db->transStart();
        
        // 1. Validate alasan
        $alasan = $postData['alasan'] ?? '';
        if (empty($alasan)) {
            return ['success' => false, 'message' => 'Alasan reject wajib diisi'];
        }
        
        // 2. Get waste data
        $wasteModel = new \App\Models\WasteModel();
        $waste = $wasteModel->find($id);
        
        if (!$waste) {
            return ['success' => false, 'message' => 'Data tidak ditemukan'];
        }
        
        // 3. Insert to waste_rejected
        $wasteRejectedModel = new \App\Models\WasteRejectedModel();
        $session = session();
        $admin = $session->get('user');
        
        $rejectedData = [
            'waste_id' => $waste['id'],
            'unit_id' => $waste['unit_id'],
            'tps_id' => $waste['tps_id'],
            'kategori_id' => $waste['kategori_id'],
            'user_id' => $waste['created_by'],
            'admin_id' => $admin['id'],
            'tanggal' => $waste['tanggal'],
            'jenis_sampah' => $waste['jenis_sampah'],
            'nama_jenis' => $waste['jenis_sampah'],
            'satuan' => $waste['satuan'],
            'jumlah' => $waste['jumlah'],
            'berat_kg' => $waste['berat_kg'],
            'alasan_reject' => $alasan,
            'tanggal_reject' => date('Y-m-d H:i:s')
        ];
        
        $wasteRejectedModel->insert($rejectedData);
        
        // 4. Update waste_management status
        $wasteModel->update($id, [
            'status' => 'perlu_revisi',
            'catatan_admin' => $alasan
        ]);
        
        $db->transComplete();
        
        if ($db->transStatus() === false) {
            return ['success' => false, 'message' => 'Gagal menolak data'];
        }
        
        return ['success' => true, 'message' => 'Data ditolak, perlu revisi'];
        
    } catch (\Exception $e) {
        log_message('error', 'Reject Waste Error: ' . $e->getMessage());
        return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }
}
```

## Update LaporanWaste Controller

**File**: `app/Controllers/Admin/LaporanWaste.php`

### Method index() - UPDATE:

Ubah query dari `waste_management` ke `waste_approved`:

```php
public function index()
{
    // ... existing code ...
    
    // SEBELUM:
    // $wasteModel = new \App\Models\WasteModel();
    // $builder = $wasteModel->where('status', 'disetujui');
    
    // SESUDAH:
    $wasteApprovedModel = new \App\Models\WasteApprovedModel();
    $approvedWaste = $wasteApprovedModel->getByDateRange($startDate, $endDate, $filters);
    
    // Get statistics
    $summary = $wasteApprovedModel->getStatistics($startDate, $endDate, $filters);
    
    // Get summary by jenis
    $byJenis = $wasteApprovedModel->getSummaryByJenis($startDate, $endDate, $filters);
    
    // Get summary by unit
    $byUnit = $wasteApprovedModel->getSummaryByUnit($startDate, $endDate, $filters);
    
    // Get rejected data
    $wasteRejectedModel = new \App\Models\WasteRejectedModel();
    $rejectedWaste = $wasteRejectedModel->getByDateRange($startDate, $endDate, $filters);
    $rejectedStats = $wasteRejectedModel->getStatistics($startDate, $endDate, $filters);
    
    // ... rest of code ...
}
```

### Method exportCsv() - UPDATE:

```php
public function exportCsv()
{
    // ... existing code ...
    
    // SEBELUM:
    // $wasteModel = new \App\Models\WasteModel();
    // $wasteData = $wasteModel->where('status', 'disetujui')->findAll();
    
    // SESUDAH:
    $wasteApprovedModel = new \App\Models\WasteApprovedModel();
    $wasteData = $wasteApprovedModel->getByDateRange($startDate, $endDate, $filters);
    
    // ... rest of code ...
}
```

### Method exportPdf() - UPDATE:

```php
public function exportPdf()
{
    // ... existing code ...
    
    // SEBELUM:
    // $wasteModel = new \App\Models\WasteModel();
    // $wasteData = $wasteModel->where('status', 'disetujui')->findAll();
    
    // SESUDAH:
    $wasteApprovedModel = new \App\Models\WasteApprovedModel();
    $wasteData = $wasteApprovedModel->getByDateRange($startDate, $endDate, $filters);
    
    // ... rest of code ...
}
```

## Update View Laporan Waste

**File**: `app/Views/admin_pusat/laporan_waste.php`

### Statistik Cards - UPDATE:

```php
<!-- Total Disetujui -->
<h3><?= $summary['total_data'] ?></h3>
<p>Data Disetujui</p>

<!-- Total Berat -->
<h3><?= number_format($summary['total_berat'], 2) ?> kg</h3>
<p>Total Berat</p>

<!-- Total Nilai -->
<h3>Rp <?= number_format($summary['total_nilai'], 0, ',', '.') ?></h3>
<p>Total Nilai Ekonomis</p>

<!-- Total Unit -->
<h3><?= $summary['total_unit'] ?></h3>
<p>Unit Aktif</p>

<!-- Total Perlu Revisi (dari waste_rejected) -->
<h3><?= $rejectedStats['total_rejected'] ?? 0 ?></h3>
<p>Perlu Revisi</p>
```

### Tabel Data - UPDATE:

Field yang ditampilkan sudah sesuai dengan struktur `waste_approved`:
- Tanggal
- Unit (dari join)
- Jenis Sampah
- Berat (kg)
- Nilai Ekonomis (Rp)
- Pelapor (dari join users)

## Keuntungan Sistem Baru

### 1. Performa Laporan Lebih Cepat
- ✅ Query laporan hanya ke `waste_approved` (data lebih sedikit)
- ✅ Tidak perlu filter `WHERE status = 'disetujui'`
- ✅ Index optimal untuk reporting

### 2. Data Lebih Terstruktur
- ✅ Data approved terpisah dari data pending
- ✅ History reject tersimpan di tabel terpisah
- ✅ Mudah audit dan tracking

### 3. Nilai Ekonomis Akurat
- ✅ Harga tersimpan saat approve (freeze price)
- ✅ Tidak terpengaruh perubahan harga di master
- ✅ Laporan konsisten

### 4. Statistik Lebih Lengkap
- ✅ Total data approved
- ✅ Total data rejected
- ✅ Rekap per jenis sampah
- ✅ Rekap per unit
- ✅ Trend bulanan

## Migration Plan

### Step 1: Create Tables
```sql
-- Run SQL file
mysql -u username -p database_name < CREATE_WASTE_APPROVED_TABLE.sql
```

### Step 2: Create Models
- ✅ WasteApprovedModel.php
- ✅ WasteRejectedModel.php

### Step 3: Update WasteService
- Update method `approveWaste()`
- Update method `rejectWaste()`

### Step 4: Update LaporanWaste Controller
- Update method `index()`
- Update method `exportCsv()`
- Update method `exportPdf()`

### Step 5: Update View
- Update statistik cards
- Update tabel data
- Update filter

### Step 6: Migrate Existing Data (Optional)
```sql
-- Migrate existing approved data
INSERT INTO waste_approved (
    waste_id, unit_id, tps_id, kategori_id, user_id,
    tanggal, jenis_sampah, satuan, jumlah, berat_kg,
    nilai_ekonomis, gedung, tanggal_approve
)
SELECT 
    id, unit_id, tps_id, kategori_id, created_by,
    tanggal, jenis_sampah, satuan, jumlah, berat_kg,
    nilai_rupiah, gedung, updated_at
FROM waste_management
WHERE status = 'disetujui';
```

### Step 7: Testing
- Test approve flow
- Test reject flow
- Test laporan waste
- Test export CSV/PDF
- Test statistik

## Rollback Plan

Jika ada masalah, rollback dengan:

1. Drop tabel baru:
```sql
DROP TABLE IF EXISTS waste_approved;
DROP TABLE IF EXISTS waste_rejected;
```

2. Revert code changes
3. Laporan kembali query ke `waste_management`

## Kesimpulan

Dengan sistem baru:
- ✅ Data approved disimpan di `waste_approved`
- ✅ Data rejected disimpan di `waste_rejected`
- ✅ Laporan waste ambil data dari `waste_approved`
- ✅ Performa lebih cepat
- ✅ Data lebih terstruktur
- ✅ Statistik lebih lengkap
