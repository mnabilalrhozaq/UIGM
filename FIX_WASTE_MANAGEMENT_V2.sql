-- ============================================
-- SCRIPT PERBAIKAN WASTE MANAGEMENT V2
-- Versi yang aman tanpa asumsi kolom
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

SELECT 'Tabel laporan_waste berhasil dibuat' as status;

-- LANGKAH 2: Cek data yang akan dimigrasi
-- ============================================
SELECT 
    'Data yang akan dipindahkan:' as info,
    status,
    COUNT(*) as jumlah
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi')
GROUP BY status;

-- LANGKAH 3: Migrasi data APPROVED (dengan kolom yang ada)
-- ============================================
INSERT INTO `laporan_waste` (
    `waste_id`,
    `unit_id`,
    `jenis_sampah`,
    `berat_kg`,
    `satuan`,
    `jumlah`,
    `nilai_rupiah`,
    `tanggal_input`,
    `status`,
    `review_notes`,
    `created_at`
)
SELECT 
    `id` as waste_id,
    `unit_id`,
    COALESCE(`jenis_sampah`, 'N/A') as jenis_sampah,
    COALESCE(`berat_kg`, 0) as berat_kg,
    COALESCE(`satuan`, 'kg') as satuan,
    COALESCE(`jumlah`, `berat_kg`, 0) as jumlah,
    COALESCE(`nilai_rupiah`, 0) as nilai_rupiah,
    COALESCE(DATE(`created_at`), CURDATE()) as tanggal_input,
    'approved' as status,
    'Disetujui - Data lama' as review_notes,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui');

SELECT 'Data APPROVED berhasil dimigrasi' as status;

-- LANGKAH 4: Migrasi data REJECTED
-- ============================================
INSERT INTO `laporan_waste` (
    `waste_id`,
    `unit_id`,
    `jenis_sampah`,
    `berat_kg`,
    `satuan`,
    `jumlah`,
    `nilai_rupiah`,
    `tanggal_input`,
    `status`,
    `review_notes`,
    `created_at`
)
SELECT 
    `id` as waste_id,
    `unit_id`,
    COALESCE(`jenis_sampah`, 'N/A') as jenis_sampah,
    COALESCE(`berat_kg`, 0) as berat_kg,
    COALESCE(`satuan`, 'kg') as satuan,
    COALESCE(`jumlah`, `berat_kg`, 0) as jumlah,
    0 as nilai_rupiah,
    COALESCE(DATE(`created_at`), CURDATE()) as tanggal_input,
    'rejected' as status,
    'Ditolak - Data lama' as review_notes,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

SELECT 'Data REJECTED berhasil dimigrasi' as status;

-- LANGKAH 5: Hapus data yang sudah direview
-- ============================================
DELETE FROM `waste_management`
WHERE `status` IN (
    'approved', 'disetujui', 'Disetujui', 
    'rejected', 'ditolak', 'Ditolak', 
    'perlu_revisi', 'Perlu Revisi'
);

SELECT 'Data yang sudah direview berhasil dihapus' as status;

-- LANGKAH 6: Verifikasi hasil
-- ============================================
SELECT 
    'waste_management' as tabel,
    COALESCE(status, 'NULL') as status,
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

SELECT '✅ SELESAI! Refresh halaman Waste Management' as status;
