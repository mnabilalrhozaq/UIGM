-- ============================================
-- SCRIPT PERBAIKAN WASTE MANAGEMENT
-- Jalankan script ini di phpMyAdmin atau MySQL Workbench
-- Database: eksperimen
-- ============================================

-- LANGKAH 1: Buat tabel laporan_waste
-- ============================================
CREATE TABLE IF NOT EXISTS `laporan_waste` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `waste_id` INT(11) UNSIGNED NULL,
  `unit_id` INT(11) UNSIGNED NOT NULL,
  `kategori_id` INT(11) UNSIGNED NULL,
  `jenis_sampah` VARCHAR(100) NOT NULL,
  `berat_kg` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `satuan` VARCHAR(20) NOT NULL DEFAULT 'kg',
  `jumlah` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `nilai_rupiah` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
  `tanggal_input` DATE NOT NULL,
  `status` ENUM('approved', 'rejected') NOT NULL,
  `reviewed_by` INT(11) UNSIGNED NULL,
  `reviewed_at` DATETIME NULL,
  `review_notes` TEXT NULL,
  `created_by` INT(11) UNSIGNED NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_unit_id` (`unit_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_tanggal_input` (`tanggal_input`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SELECT 'Tabel laporan_waste berhasil dibuat atau sudah ada' as status;

-- LANGKAH 2: Cek data yang akan dimigrasi
-- ============================================
SELECT 
    'Data yang akan dipindahkan ke laporan_waste:' as info,
    COUNT(*) as jumlah
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

-- LANGKAH 3: Migrasi data APPROVED ke laporan_waste
-- ============================================
INSERT INTO `laporan_waste` (
    `waste_id`,
    `unit_id`,
    `kategori_id`,
    `jenis_sampah`,
    `berat_kg`,
    `satuan`,
    `jumlah`,
    `nilai_rupiah`,
    `tanggal_input`,
    `status`,
    `reviewed_by`,
    `reviewed_at`,
    `review_notes`,
    `created_by`,
    `created_at`
)
SELECT 
    `id` as waste_id,
    `unit_id`,
    `kategori_id`,
    `jenis_sampah`,
    `berat_kg`,
    COALESCE(`satuan`, 'kg') as satuan,
    COALESCE(`jumlah`, `berat_kg`) as jumlah,
    COALESCE(`nilai_rupiah`, 0) as nilai_rupiah,
    COALESCE(DATE(`tanggal`), DATE(`created_at`)) as tanggal_input,
    'approved' as status,
    `reviewed_by`,
    `reviewed_at`,
    COALESCE(`review_notes`, 'Disetujui oleh admin') as review_notes,
    `created_by`,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui')
AND `id` NOT IN (SELECT COALESCE(`waste_id`, 0) FROM `laporan_waste`);

SELECT 'Data APPROVED berhasil dimigrasi' as status;

-- LANGKAH 4: Migrasi data REJECTED ke laporan_waste
-- ============================================
INSERT INTO `laporan_waste` (
    `waste_id`,
    `unit_id`,
    `kategori_id`,
    `jenis_sampah`,
    `berat_kg`,
    `satuan`,
    `jumlah`,
    `nilai_rupiah`,
    `tanggal_input`,
    `status`,
    `reviewed_by`,
    `reviewed_at`,
    `review_notes`,
    `created_by`,
    `created_at`
)
SELECT 
    `id` as waste_id,
    `unit_id`,
    `kategori_id`,
    `jenis_sampah`,
    `berat_kg`,
    COALESCE(`satuan`, 'kg') as satuan,
    COALESCE(`jumlah`, `berat_kg`) as jumlah,
    0 as nilai_rupiah,
    COALESCE(DATE(`tanggal`), DATE(`created_at`)) as tanggal_input,
    'rejected' as status,
    `reviewed_by`,
    `reviewed_at`,
    COALESCE(`review_notes`, 'Ditolak oleh admin') as review_notes,
    `created_by`,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi')
AND `id` NOT IN (SELECT COALESCE(`waste_id`, 0) FROM `laporan_waste`);

SELECT 'Data REJECTED berhasil dimigrasi' as status;

-- LANGKAH 5: Hapus data yang sudah direview dari waste_management
-- ============================================
DELETE FROM `waste_management`
WHERE `status` IN (
    'approved', 'disetujui', 'Disetujui', 
    'rejected', 'ditolak', 'Ditolak', 
    'perlu_revisi', 'Perlu Revisi'
);

SELECT 'Data yang sudah direview berhasil dihapus dari waste_management' as status;

-- LANGKAH 6: Verifikasi hasil
-- ============================================
SELECT '=== HASIL MIGRASI ===' as info;

SELECT 
    'waste_management' as tabel,
    status,
    COUNT(*) as jumlah
FROM waste_management
GROUP BY status
UNION ALL
SELECT 
    'laporan_waste' as tabel,
    status,
    COUNT(*) as jumlah
FROM laporan_waste
GROUP BY status
ORDER BY tabel, status;

-- SELESAI
SELECT '✅ PERBAIKAN SELESAI!' as status,
       'Refresh halaman Waste Management untuk melihat hasilnya' as instruksi;
