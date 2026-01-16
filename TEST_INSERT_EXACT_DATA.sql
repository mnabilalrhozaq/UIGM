-- Test insert dengan data PERSIS seperti yang dikirim PHP
USE eksperimen;

-- Data yang dikirim oleh kode PHP (dari log):
-- "unit_id":"1","berat_kg":"33.300000000000004","tanggal":"2026-01-15",
-- "jenis_sampah":"Kaca 1","satuan":"pcs","jumlah":"33.300000000000004",
-- "gedung":"TPS","kategori_sampah":"bisa_dijual","status":"draft",
-- "nilai_rupiah":233100.00000000003

INSERT INTO waste_management 
(unit_id, berat_kg, tanggal, jenis_sampah, satuan, jumlah, gedung, kategori_sampah, status, nilai_rupiah, created_at) 
VALUES 
(1, 33.300000000000004, '2026-01-15', 'Kaca 1', 'pcs', 33.300000000000004, 'TPS', 'bisa_dijual', 'draft', 233100.00000000003, NOW());

SELECT 'Jika berhasil, berarti masalahnya di kode PHP' AS hasil;
SELECT 'Jika gagal, lihat error message nya' AS hasil;

-- Lihat data yang baru diinsert
SELECT * FROM waste_management WHERE gedung = 'TPS' AND jenis_sampah = 'Kaca 1' ORDER BY id DESC LIMIT 1;
