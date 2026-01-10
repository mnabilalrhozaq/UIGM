-- Create jenis_sampah table
CREATE TABLE IF NOT EXISTS `jenis_sampah` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `parent_id` int(11) unsigned DEFAULT NULL,
    `kode` varchar(50) NOT NULL,
    `nama` varchar(255) NOT NULL,
    `level` tinyint(1) NOT NULL COMMENT '1=kategori utama, 2=area, 3=detail',
    `urutan` int(3) NOT NULL DEFAULT 0,
    `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `kode` (`kode`),
    KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert data jenis sampah
INSERT IGNORE INTO `jenis_sampah` (`id`, `parent_id`, `kode`, `nama`, `level`, `urutan`, `status_aktif`, `created_at`, `updated_at`) VALUES
(1, NULL, 'organik', 'Sampah Organik', 1, 1, 1, NOW(), NOW()),
(2, 1, 'kantin', 'Sampah dari Kantin', 2, 1, 1, NOW(), NOW()),
(3, 1, 'lingkungan_kampus', 'Sampah dari Lingkungan Kampus', 2, 2, 1, NOW(), NOW()),
(4, 2, 'sisa_makanan_sayuran', 'Sisa Makanan atau Sayuran', 3, 1, 1, NOW(), NOW()),
(5, 2, 'sisa_buah_buahan', 'Sisa Buah-buahan', 3, 2, 1, NOW(), NOW()),
(6, 2, 'produk_sisa_dapur', 'Produk Sisa Dapur (Ampas Kopi/Teh, Kulit Telur)', 3, 3, 1, NOW(), NOW()),
(7, 3, 'daun_kering_gugur', 'Daun-daun Kering yang Gugur', 3, 1, 1, NOW(), NOW()),
(8, 3, 'rumput_dipotong', 'Rumput yang Dipotong', 3, 2, 1, NOW(), NOW()),
(9, 3, 'ranting_pohon_kecil', 'Ranting-ranting Pohon Kecil', 3, 3, 1, NOW(), NOW());