-- Script SQL untuk membuat tabel yang dibutuhkan oleh Role USER
-- Jalankan script ini jika tabel belum ada

-- Tabel penilaian_unit untuk data UIGM
CREATE TABLE IF NOT EXISTS `penilaian_unit` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) UNSIGNED NOT NULL,
  `kategori_uigm` enum('SI','EC','WS','WR','TR','ED') NOT NULL,
  `indikator` varchar(255) NOT NULL,
  `nilai_input` decimal(10,2) DEFAULT NULL,
  `status` enum('draft','dikirim','review','disetujui','perlu_revisi') NOT NULL DEFAULT 'draft',
  `catatan_admin` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `kategori_uigm` (`kategori_uigm`),
  KEY `status` (`status`),
  CONSTRAINT `penilaian_unit_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel waste_management untuk data sampah (jika belum ada, gunakan kriteria_uigm yang sudah ada)
CREATE TABLE IF NOT EXISTS `waste_management` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unit_id` int(11) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_sampah` enum('Kertas','Plastik','Organik','Anorganik','Limbah Cair','B3') NOT NULL,
  `satuan` varchar(10) NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `gedung` varchar(50) NOT NULL,
  `status` enum('draft','dikirim','review','disetujui','perlu_revisi') NOT NULL DEFAULT 'draft',
  `catatan_admin` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `jenis_sampah` (`jenis_sampah`),
  KEY `status` (`status`),
  KEY `tanggal` (`tanggal`),
  CONSTRAINT `waste_management_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data untuk testing (opsional)
-- Sample indikator untuk unit dengan ID 2 (sesuaikan dengan unit yang ada)
INSERT IGNORE INTO `penilaian_unit` (`unit_id`, `kategori_uigm`, `indikator`, `nilai_input`, `status`, `created_at`) VALUES
(2, 'SI', 'Rasio Ruang Terbuka Hijau', NULL, 'draft', NOW()),
(2, 'SI', 'Jumlah Gedung Hijau', NULL, 'draft', NOW()),
(2, 'SI', 'Kebijakan Lingkungan', NULL, 'draft', NOW()),
(2, 'SI', 'Program Konservasi', NULL, 'draft', NOW()),
(2, 'EC', 'Penggunaan Energi Terbarukan', NULL, 'draft', NOW()),
(2, 'EC', 'Efisiensi Energi Gedung', NULL, 'draft', NOW()),
(2, 'EC', 'Program Mitigasi Karbon', NULL, 'draft', NOW()),
(2, 'EC', 'Emisi Gas Rumah Kaca', NULL, 'draft', NOW());

-- Sample data waste management untuk unit dengan ID 2
INSERT IGNORE INTO `waste_management` (`unit_id`, `tanggal`, `jenis_sampah`, `satuan`, `jumlah`, `gedung`, `status`, `created_at`) VALUES
(2, '2026-01-05', 'Kertas', 'kg', 15.50, 'Gedung A', 'draft', NOW()),
(2, '2026-01-05', 'Plastik', 'kg', 8.25, 'Gedung A', 'draft', NOW()),
(2, '2026-01-04', 'Organik', 'kg', 25.75, 'Kantin', 'dikirim', NOW());

SELECT 'Tabel untuk Role USER berhasil dibuat!' as Status;