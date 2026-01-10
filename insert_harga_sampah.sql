-- Insert data master harga sampah
INSERT INTO master_harga_sampah (jenis_sampah, nama_jenis, harga_per_satuan, harga_per_kg, satuan, dapat_dijual, status_aktif, deskripsi, tanggal_berlaku, created_at, updated_at) VALUES
('Plastik', 'Plastik (Botol, Kemasan)', 2000.00, 2000.00, 'kg', 1, 1, 'Plastik yang dapat didaur ulang', CURDATE(), NOW(), NOW()),
('Kertas', 'Kertas (HVS, Koran, Kardus)', 1500.00, 1500.00, 'kg', 1, 1, 'Kertas yang dapat didaur ulang', CURDATE(), NOW(), NOW()),
('Logam', 'Logam (Kaleng, Aluminium)', 5000.00, 5000.00, 'kg', 1, 1, 'Logam yang dapat didaur ulang', CURDATE(), NOW(), NOW()),
('Organik', 'Sampah Organik', 0.00, 0.00, 'kg', 0, 1, 'Sampah organik untuk kompos', CURDATE(), NOW(), NOW()),
('Residu', 'Sampah Residu', 0.00, 0.00, 'kg', 0, 1, 'Sampah yang tidak dapat didaur ulang', CURDATE(), NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    nama_jenis = VALUES(nama_jenis),
    harga_per_satuan = VALUES(harga_per_satuan),
    harga_per_kg = VALUES(harga_per_kg),
    updated_at = NOW();
