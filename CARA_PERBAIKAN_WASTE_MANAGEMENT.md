# Cara Memperbaiki Waste Management

## Masalah
Data yang sudah "Disetujui" atau "Ditolak" masih muncul di halaman Waste Management.

## Penyebab
1. Tabel `laporan_waste` belum dibuat
2. Data lama dengan status approved/rejected masih ada di `waste_management`

## Solusi - Ikuti Langkah Berikut:

### Langkah 1: Buat Tabel laporan_waste

Buka **phpMyAdmin** atau **MySQL Workbench**, pilih database `eksperimen`, lalu jalankan SQL ini:

```sql
-- File: database/sql/CREATE_LAPORAN_WASTE_TABLE.sql

CREATE TABLE IF NOT EXISTS `laporan_waste` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `waste_id` INT(11) UNSIGNED NULL COMMENT 'ID dari waste_management sebelum dipindah',
  `unit_id` INT(11) UNSIGNED NOT NULL COMMENT 'ID unit yang mengirim',
  `kategori_id` INT(11) UNSIGNED NULL COMMENT 'ID kategori dari master_harga_sampah',
  `jenis_sampah` VARCHAR(100) NOT NULL COMMENT 'Jenis sampah (Plastik, Kertas, dll)',
  `berat_kg` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Berat dalam kilogram',
  `satuan` VARCHAR(20) NOT NULL DEFAULT 'kg' COMMENT 'Satuan (kg, gram, ton, dll)',
  `jumlah` DECIMAL(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Jumlah sesuai satuan',
  `nilai_rupiah` DECIMAL(15,2) NOT NULL DEFAULT 0.00 COMMENT 'Nilai dalam rupiah',
  `tanggal_input` DATE NOT NULL COMMENT 'Tanggal input data',
  `status` ENUM('approved', 'rejected') NOT NULL COMMENT 'Status review',
  `reviewed_by` INT(11) UNSIGNED NULL COMMENT 'ID admin yang mereview',
  `reviewed_at` DATETIME NULL COMMENT 'Waktu review',
  `review_notes` TEXT NULL COMMENT 'Catatan review',
  `created_by` INT(11) UNSIGNED NULL COMMENT 'ID user yang membuat',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_unit_id` (`unit_id`),
  INDEX `idx_kategori_id` (`kategori_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_tanggal_input` (`tanggal_input`),
  INDEX `idx_reviewed_at` (`reviewed_at`),
  INDEX `idx_created_by` (`created_by`),
  INDEX `idx_reviewed_by` (`reviewed_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Laporan waste yang sudah direview';
```

### Langkah 2: Cek Data yang Perlu Dimigrasi

Jalankan query ini untuk melihat data yang perlu dipindahkan:

```sql
-- File: database/sql/CHECK_AND_FIX_WASTE_STATUS.sql

SELECT 
    id,
    jenis_sampah,
    berat_kg,
    status,
    created_at
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi')
ORDER BY created_at DESC;
```

### Langkah 3: Migrasi Data Lama

Jalankan SQL ini untuk memindahkan data lama ke `laporan_waste` dan menghapusnya dari `waste_management`:

```sql
-- File: database/sql/MIGRATE_OLD_DATA_TO_LAPORAN.sql

-- Migrasi data approved
INSERT INTO `laporan_waste` (
    `waste_id`, `unit_id`, `kategori_id`, `jenis_sampah`, `berat_kg`, 
    `satuan`, `jumlah`, `nilai_rupiah`, `tanggal_input`, `status`, 
    `reviewed_by`, `reviewed_at`, `review_notes`, `created_by`, `created_at`
)
SELECT 
    `id`, `unit_id`, `kategori_id`, `jenis_sampah`, `berat_kg`,
    COALESCE(`satuan`, 'kg'), COALESCE(`jumlah`, `berat_kg`), 
    COALESCE(`nilai_rupiah`, 0), COALESCE(`tanggal`, `created_at`), 
    'approved', `reviewed_by`, `reviewed_at`, 
    COALESCE(`review_notes`, 'Disetujui'), `created_by`, NOW()
FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui');

-- Migrasi data rejected
INSERT INTO `laporan_waste` (
    `waste_id`, `unit_id`, `kategori_id`, `jenis_sampah`, `berat_kg`, 
    `satuan`, `jumlah`, `nilai_rupiah`, `tanggal_input`, `status`, 
    `reviewed_by`, `reviewed_at`, `review_notes`, `created_by`, `created_at`
)
SELECT 
    `id`, `unit_id`, `kategori_id`, `jenis_sampah`, `berat_kg`,
    COALESCE(`satuan`, 'kg'), COALESCE(`jumlah`, `berat_kg`), 
    0, COALESCE(`tanggal`, `created_at`), 
    'rejected', `reviewed_by`, `reviewed_at`, 
    COALESCE(`review_notes`, 'Ditolak'), `created_by`, NOW()
FROM `waste_management`
WHERE `status` IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

-- Hapus data yang sudah direview
DELETE FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');
```

### Langkah 4: Verifikasi Hasil

Jalankan query ini untuk memastikan data sudah bersih:

```sql
-- Cek data di waste_management (seharusnya hanya draft dan pending)
SELECT status, COUNT(*) as jumlah
FROM waste_management
GROUP BY status;

-- Cek data di laporan_waste (seharusnya ada data approved dan rejected)
SELECT status, COUNT(*) as jumlah
FROM laporan_waste
GROUP BY status;
```

### Langkah 5: Test Sistem Baru

1. **Login sebagai User**
   - Tambah data sampah baru
   - Kirim data (status: dikirim)

2. **Login sebagai Admin**
   - Buka halaman "Review Data Sampah"
   - Approve atau Reject data

3. **Cek Hasil**
   - Data seharusnya **HILANG** dari Waste Management
   - Data seharusnya **MUNCUL** di Laporan Waste

4. **Test Data Draft**
   - Login sebagai User
   - Tambah data tapi JANGAN kirim (biarkan draft)
   - Data seharusnya **TETAP ADA** di Waste Management
   - User masih bisa edit/hapus

## Hasil yang Diharapkan

### Waste Management (admin-pusat/waste)
Hanya menampilkan data dengan status:
- ✅ **Draft** - Data baru yang belum dikirim
- ✅ **Dikirim/Pending** - Data yang menunggu review

TIDAK menampilkan:
- ❌ **Disetujui** - Sudah pindah ke Laporan
- ❌ **Ditolak** - Sudah pindah ke Laporan

### Laporan Waste (admin-pusat/laporan-waste)
Menampilkan data dengan status:
- ✅ **Approved** - Data yang disetujui
- ✅ **Rejected** - Data yang ditolak

## Troubleshooting

### Jika masih muncul data "Disetujui" di Waste Management:

1. Cek apakah tabel `laporan_waste` sudah dibuat:
   ```sql
   SHOW TABLES LIKE 'laporan_waste';
   ```

2. Cek apakah ada data dengan status approved/rejected:
   ```sql
   SELECT * FROM waste_management WHERE status IN ('approved', 'disetujui', 'Disetujui');
   ```

3. Jika ada, jalankan lagi script migrasi (Langkah 3)

### Jika error saat approve/reject:

1. Cek log error di `writable/logs/log-2026-01-14.log`
2. Pastikan kolom di tabel `waste_management` sesuai dengan yang dibutuhkan
3. Pastikan tabel `laporan_waste` sudah dibuat dengan benar

## Catatan Penting

⚠️ **BACKUP DATABASE** sebelum menjalankan script SQL!

```bash
# Backup via command line
mysqldump -u root -p eksperimen > backup_eksperimen_$(date +%Y%m%d).sql

# Atau backup via phpMyAdmin: Export > Go
```

---

Tanggal: 2026-01-14
Oleh: Kiro AI Assistant
