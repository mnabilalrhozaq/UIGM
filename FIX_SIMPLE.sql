-- ============================================
-- PERBAIKAN SEDERHANA - Jalankan satu per satu
-- ============================================

-- STEP 1: Buat tabel laporan_waste
CREATE TABLE IF NOT EXISTS `laporan_waste` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `waste_id` INT(11) UNSIGNED NULL,
  `unit_id` INT(11) UNSIGNED NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- STEP 2: Cek data yang akan dipindahkan
SELECT COUNT(*) as total_data_approved
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui');

SELECT COUNT(*) as total_data_rejected
FROM waste_management
WHERE status IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

-- STEP 3: Pindahkan data APPROVED
INSERT INTO laporan_waste (
    waste_id, unit_id, jenis_sampah, berat_kg, satuan, jumlah, 
    nilai_rupiah, tanggal_input, status, review_notes, created_at
)
SELECT 
    id, unit_id, 
    COALESCE(jenis_sampah, 'N/A'), 
    COALESCE(berat_kg, 0), 
    COALESCE(satuan, 'kg'), 
    COALESCE(jumlah, berat_kg, 0),
    COALESCE(nilai_rupiah, 0), 
    COALESCE(DATE(created_at), CURDATE()), 
    'approved', 
    'Disetujui - Data lama', 
    NOW()
FROM waste_management
WHERE status IN ('approved', 'disetujui', 'Disetujui');

-- STEP 4: Pindahkan data REJECTED
INSERT INTO laporan_waste (
    waste_id, unit_id, jenis_sampah, berat_kg, satuan, jumlah, 
    nilai_rupiah, tanggal_input, status, review_notes, created_at
)
SELECT 
    id, unit_id, 
    COALESCE(jenis_sampah, 'N/A'), 
    COALESCE(berat_kg, 0), 
    COALESCE(satuan, 'kg'), 
    COALESCE(jumlah, berat_kg, 0),
    0, 
    COALESCE(DATE(created_at), CURDATE()), 
    'rejected', 
    'Ditolak - Data lama', 
    NOW()
FROM waste_management
WHERE status IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

-- STEP 5: Hapus data yang sudah dipindahkan
DELETE FROM waste_management
WHERE status IN (
    'approved', 'disetujui', 'Disetujui', 
    'rejected', 'ditolak', 'Ditolak', 
    'perlu_revisi', 'Perlu Revisi'
);

-- STEP 6: Cek hasil
SELECT 'Data di waste_management:' as info;
SELECT status, COUNT(*) as jumlah
FROM waste_management
GROUP BY status;

SELECT 'Data di laporan_waste:' as info;
SELECT status, COUNT(*) as jumlah
FROM laporan_waste
GROUP BY status;
