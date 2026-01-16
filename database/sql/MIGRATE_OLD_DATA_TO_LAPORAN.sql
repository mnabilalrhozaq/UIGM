-- Script untuk migrasi data lama yang sudah direview ke laporan_waste
-- Dan menghapus data yang sudah direview dari waste_management

-- 1. Pastikan tabel laporan_waste sudah ada
-- Jika belum, jalankan CREATE_LAPORAN_WASTE_TABLE.sql terlebih dahulu

-- 2. Migrasi data yang sudah approved
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
    COALESCE(`tanggal`, `created_at`) as tanggal_input,
    'approved' as status,
    `reviewed_by`,
    `reviewed_at`,
    COALESCE(`review_notes`, 'Disetujui') as review_notes,
    `created_by`,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui')
AND `id` NOT IN (SELECT `waste_id` FROM `laporan_waste` WHERE `waste_id` IS NOT NULL);

-- 3. Migrasi data yang sudah rejected
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
    COALESCE(`tanggal`, `created_at`) as tanggal_input,
    'rejected' as status,
    `reviewed_by`,
    `reviewed_at`,
    COALESCE(`review_notes`, 'Ditolak') as review_notes,
    `created_by`,
    NOW() as created_at
FROM `waste_management`
WHERE `status` IN ('rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi')
AND `id` NOT IN (SELECT `waste_id` FROM `laporan_waste` WHERE `waste_id` IS NOT NULL);

-- 4. Hapus data yang sudah direview dari waste_management
DELETE FROM `waste_management`
WHERE `status` IN ('approved', 'disetujui', 'Disetujui', 'rejected', 'ditolak', 'Ditolak', 'perlu_revisi', 'Perlu Revisi');

-- 5. Tampilkan hasil
SELECT 
    'Data berhasil dimigrasi' as status,
    (SELECT COUNT(*) FROM `laporan_waste`) as total_laporan,
    (SELECT COUNT(*) FROM `waste_management`) as total_waste_management,
    (SELECT COUNT(*) FROM `waste_management` WHERE `status` = 'draft') as total_draft,
    (SELECT COUNT(*) FROM `waste_management` WHERE `status` IN ('dikirim', 'pending')) as total_pending;
