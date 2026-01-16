-- ============================================
-- INSERT JENIS SAMPAH LENGKAP
-- Menambahkan jenis sampah yang lebih detail
-- ============================================

-- Hapus data lama (optional, hati-hati jika sudah ada data transaksi)
-- DELETE FROM master_harga_sampah;

-- Reset auto increment (optional)
-- ALTER TABLE master_harga_sampah AUTO_INCREMENT = 1;

-- ============================================
-- PLASTIK
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Plastik PET', 'Plastik PET (Botol Minuman)', 3000.00, 3000.00, 'kg', 1, 1, 'Botol plastik PET bersih, biasa digunakan untuk botol minuman', CURDATE(), NOW(), NOW()),
('Plastik HDPE', 'Plastik HDPE (Botol Detergen, Shampo)', 2500.00, 2500.00, 'kg', 1, 1, 'Plastik HDPE dari botol detergen, shampo, dan produk pembersih', CURDATE(), NOW(), NOW()),
('Plastik PVC', 'Plastik PVC (Pipa, Kabel)', 1500.00, 1500.00, 'kg', 1, 1, 'Plastik PVC dari pipa, kabel, dan material konstruksi', CURDATE(), NOW(), NOW()),
('Plastik LDPE', 'Plastik LDPE (Kantong Plastik)', 1000.00, 1000.00, 'kg', 1, 1, 'Kantong plastik, plastik kemasan tipis', CURDATE(), NOW(), NOW()),
('Plastik PP', 'Plastik PP (Tutup Botol, Sedotan)', 2000.00, 2000.00, 'kg', 1, 1, 'Plastik PP dari tutup botol, sedotan, container', CURDATE(), NOW(), NOW()),
('Plastik PS', 'Plastik PS (Styrofoam)', 500.00, 500.00, 'kg', 1, 1, 'Styrofoam, plastik PS dari kemasan makanan', CURDATE(), NOW(), NOW()),
('Plastik Lainnya', 'Plastik Lainnya', 800.00, 800.00, 'kg', 1, 1, 'Plastik campuran atau jenis lainnya', CURDATE(), NOW(), NOW());

-- ============================================
-- KERTAS
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Kertas HVS', 'Kertas HVS/Putih', 2000.00, 2000.00, 'kg', 1, 1, 'Kertas HVS putih, kertas print, kertas fotokopi', CURDATE(), NOW(), NOW()),
('Kertas Koran', 'Kertas Koran', 1500.00, 1500.00, 'kg', 1, 1, 'Kertas koran bekas', CURDATE(), NOW(), NOW()),
('Kertas Kardus', 'Kardus/Karton', 1800.00, 1800.00, 'kg', 1, 1, 'Kardus bekas, karton tebal', CURDATE(), NOW(), NOW()),
('Kertas Majalah', 'Kertas Majalah/Berwarna', 1200.00, 1200.00, 'kg', 1, 1, 'Kertas majalah, brosur, kertas berwarna', CURDATE(), NOW(), NOW()),
('Kertas Duplex', 'Kertas Duplex', 1600.00, 1600.00, 'kg', 1, 1, 'Kertas duplex dari kemasan', CURDATE(), NOW(), NOW()),
('Kertas Arsip', 'Kertas Arsip', 1700.00, 1700.00, 'kg', 1, 1, 'Kertas arsip, dokumen lama', CURDATE(), NOW(), NOW());

-- ============================================
-- LOGAM
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Logam Besi', 'Besi/Baja', 3000.00, 3000.00, 'kg', 1, 1, 'Besi, baja, logam ferro', CURDATE(), NOW(), NOW()),
('Logam Aluminium', 'Aluminium (Kaleng, Foil)', 8000.00, 8000.00, 'kg', 1, 1, 'Aluminium dari kaleng, foil, profil', CURDATE(), NOW(), NOW()),
('Logam Tembaga', 'Tembaga', 50000.00, 50000.00, 'kg', 1, 1, 'Tembaga murni, kabel tembaga', CURDATE(), NOW(), NOW()),
('Logam Kuningan', 'Kuningan', 35000.00, 35000.00, 'kg', 1, 1, 'Kuningan, brass', CURDATE(), NOW(), NOW()),
('Logam Seng', 'Seng', 4000.00, 4000.00, 'kg', 1, 1, 'Seng, zinc', CURDATE(), NOW(), NOW()),
('Logam Campuran', 'Logam Campuran', 2000.00, 2000.00, 'kg', 1, 1, 'Logam campuran atau tidak teridentifikasi', CURDATE(), NOW(), NOW());

-- ============================================
-- KACA
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Kaca Bening', 'Kaca Bening', 500.00, 500.00, 'kg', 1, 1, 'Kaca bening, transparan', CURDATE(), NOW(), NOW()),
('Kaca Berwarna', 'Kaca Berwarna', 400.00, 400.00, 'kg', 1, 1, 'Kaca berwarna, kaca gelap', CURDATE(), NOW(), NOW()),
('Kaca Botol', 'Botol Kaca', 300.00, 300.00, 'kg', 1, 1, 'Botol kaca bekas', CURDATE(), NOW(), NOW());

-- ============================================
-- ORGANIK
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Organik Sisa Makanan', 'Sisa Makanan', 0.00, 0.00, 'kg', 0, 1, 'Sisa makanan untuk kompos', CURDATE(), NOW(), NOW()),
('Organik Daun', 'Daun Kering', 0.00, 0.00, 'kg', 0, 1, 'Daun kering untuk kompos', CURDATE(), NOW(), NOW()),
('Organik Ranting', 'Ranting/Kayu', 0.00, 0.00, 'kg', 0, 1, 'Ranting, kayu kecil untuk kompos', CURDATE(), NOW(), NOW()),
('Organik Rumput', 'Rumput', 0.00, 0.00, 'kg', 0, 1, 'Rumput hasil pangkasan', CURDATE(), NOW(), NOW());

-- ============================================
-- ELEKTRONIK
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Elektronik Kabel', 'Kabel Elektronik', 15000.00, 15000.00, 'kg', 1, 1, 'Kabel elektronik bekas', CURDATE(), NOW(), NOW()),
('Elektronik PCB', 'PCB/Komponen', 25000.00, 25000.00, 'kg', 1, 1, 'PCB, komponen elektronik', CURDATE(), NOW(), NOW()),
('Elektronik Baterai', 'Baterai', 5000.00, 5000.00, 'kg', 1, 1, 'Baterai bekas (harus ditangani khusus)', CURDATE(), NOW(), NOW()),
('Elektronik Lampu', 'Lampu', 1000.00, 1000.00, 'pcs', 1, 1, 'Lampu bekas', CURDATE(), NOW(), NOW());

-- ============================================
-- TEKSTIL
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Tekstil Kain', 'Kain/Pakaian', 1000.00, 1000.00, 'kg', 1, 1, 'Kain bekas, pakaian layak pakai', CURDATE(), NOW(), NOW()),
('Tekstil Sepatu', 'Sepatu/Sandal', 500.00, 500.00, 'pcs', 1, 1, 'Sepatu, sandal bekas', CURDATE(), NOW(), NOW()),
('Tekstil Tas', 'Tas', 800.00, 800.00, 'pcs', 1, 1, 'Tas bekas', CURDATE(), NOW(), NOW());

-- ============================================
-- LAINNYA
-- ============================================
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Karet', 'Karet', 1500.00, 1500.00, 'kg', 1, 1, 'Karet bekas', CURDATE(), NOW(), NOW()),
('Ban', 'Ban Bekas', 2000.00, 2000.00, 'pcs', 1, 1, 'Ban kendaraan bekas', CURDATE(), NOW(), NOW()),
('Minyak Jelantah', 'Minyak Jelantah', 3000.00, 3000.00, 'liter', 1, 1, 'Minyak goreng bekas', CURDATE(), NOW(), NOW()),
('B3', 'Limbah B3', 0.00, 0.00, 'kg', 0, 1, 'Limbah Bahan Berbahaya dan Beracun (perlu penanganan khusus)', CURDATE(), NOW(), NOW()),
('Residu', 'Residu/Tidak Terpilah', 0.00, 0.00, 'kg', 0, 1, 'Sampah residu yang tidak dapat dipilah', CURDATE(), NOW(), NOW());

-- ============================================
-- CATATAN
-- ============================================
-- Total: 40+ jenis sampah yang lebih detail
-- Harga dapat disesuaikan dengan kondisi pasar lokal
-- Status dapat_dijual: 1 = bisa dijual, 0 = tidak bisa dijual
-- Status aktif: 1 = aktif, 0 = nonaktif
-- Satuan: kg, gram, ton, liter, pcs, karung
