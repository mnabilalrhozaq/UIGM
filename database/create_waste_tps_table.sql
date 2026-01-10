-- Tabel khusus untuk data waste TPS
CREATE TABLE waste_tps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    sampah_dari_gedung VARCHAR(100) NOT NULL,
    jumlah_berat DECIMAL(10,2) NOT NULL,
    satuan ENUM('kg', 'liter') NOT NULL,
    nilai_rupiah DECIMAL(15,2) NULL COMMENT 'Nullable - hanya diisi jika sampah bisa dijual',
    pengelola_id INT NOT NULL COMMENT 'ID user pengelola_tps yang input',
    status ENUM('draft', 'submitted', 'approved') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_tanggal (tanggal),
    INDEX idx_gedung (sampah_dari_gedung),
    INDEX idx_pengelola (pengelola_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;