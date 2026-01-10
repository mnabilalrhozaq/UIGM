-- Fix pengiriman_unit table structure
-- Menambahkan kolom yang hilang sesuai struktur minimal yang diperlukan

-- Cek struktur tabel saat ini
DESCRIBE pengiriman_unit;

-- Tambahkan kolom created_by jika belum ada
ALTER TABLE `pengiriman_unit` 
ADD COLUMN `created_by` int(11) UNSIGNED DEFAULT NULL COMMENT 'User yang membuat pengiriman' AFTER `catatan_admin`;

-- Tambahkan kolom reviewer_id jika belum ada (untuk tracking siapa yang review)
ALTER TABLE `pengiriman_unit` 
ADD COLUMN `reviewer_id` int(11) UNSIGNED DEFAULT NULL COMMENT 'Admin pusat yang melakukan review' AFTER `created_by`;

-- Tambahkan kolom tanggal_disetujui jika belum ada
ALTER TABLE `pengiriman_unit` 
ADD COLUMN `tanggal_disetujui` datetime DEFAULT NULL COMMENT 'Tanggal pengiriman disetujui' AFTER `tanggal_review`;

-- Tambahkan kolom catatan_revisi jika belum ada
ALTER TABLE `pengiriman_unit` 
ADD COLUMN `catatan_revisi` text DEFAULT NULL COMMENT 'Catatan untuk revisi dari admin pusat' AFTER `catatan_admin`;

-- Tambahkan kolom versi jika belum ada
ALTER TABLE `pengiriman_unit` 
ADD COLUMN `versi` int(11) NOT NULL DEFAULT 1 COMMENT 'Versi pengiriman' AFTER `progress_persen`;

-- Update foreign key constraints
ALTER TABLE `pengiriman_unit` 
ADD CONSTRAINT `fk_pengiriman_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `pengiriman_unit` 
ADD CONSTRAINT `fk_pengiriman_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Pastikan status enum sudah lengkap
ALTER TABLE `pengiriman_unit` 
MODIFY COLUMN `status_pengiriman` enum('draft','dikirim','review','perlu_revisi','disetujui','ditolak') NOT NULL DEFAULT 'draft';

-- Update existing records untuk set created_by dari admin unit yang sesuai
UPDATE pengiriman_unit pu 
JOIN users u ON u.unit_id = pu.unit_id AND u.role = 'admin_unit' 
SET pu.created_by = u.id 
WHERE pu.created_by IS NULL;

-- Tampilkan struktur tabel setelah update
DESCRIBE pengiriman_unit;

-- Tampilkan data sample untuk verifikasi
SELECT 
    pu.id,
    pu.unit_id,
    u.nama_unit,
    pu.tahun_penilaian_id,
    pu.status_pengiriman,
    pu.progress_persen,
    pu.versi,
    pu.created_by,
    creator.username as created_by_user,
    pu.reviewer_id,
    reviewer.username as reviewer_user,
    pu.tanggal_kirim,
    pu.tanggal_review,
    pu.tanggal_disetujui,
    pu.created_at,
    pu.updated_at
FROM pengiriman_unit pu
LEFT JOIN unit u ON u.id = pu.unit_id
LEFT JOIN users creator ON creator.id = pu.created_by
LEFT JOIN users reviewer ON reviewer.id = pu.reviewer_id
ORDER BY pu.id;