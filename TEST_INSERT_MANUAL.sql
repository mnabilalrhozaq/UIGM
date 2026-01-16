-- Test insert manual untuk jenis sampah baru
USE eksperimen;

-- Test 1: Insert jenis sampah LAMA (Limbah Cair) - harusnya berhasil
INSERT INTO waste_management 
(unit_id, tanggal, jenis_sampah, berat_kg, satuan, jumlah, gedung, kategori_sampah, nilai_rupiah, status, created_at) 
VALUES 
(1, '2026-01-15', 'Limbah Cair', 10, 'liter', 10, 'Test Manual', 'tidak_dijual', 0, 'draft', NOW());

SELECT 'Test 1 BERHASIL - Limbah Cair (jenis lama) bisa diinsert' AS hasil;

-- Test 2: Insert jenis sampah BARU (Kaca 1) - ini yang bermasalah
INSERT INTO waste_management 
(unit_id, tanggal, jenis_sampah, berat_kg, satuan, jumlah, gedung, kategori_sampah, nilai_rupiah, status, created_at) 
VALUES 
(1, '2026-01-15', 'Kaca 1', 9.8, 'pcs', 9.8, 'Test Manual', 'bisa_dijual', 68600, 'draft', NOW());

SELECT 'Test 2 BERHASIL - Kaca 1 (jenis baru) bisa diinsert!' AS hasil;

-- Lihat data yang baru diinsert
SELECT * FROM waste_management WHERE gedung = 'Test Manual' ORDER BY id DESC;

-- Hapus data test (opsional)
-- DELETE FROM waste_management WHERE gedung = 'Test Manual';
