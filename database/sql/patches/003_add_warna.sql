-- Patch untuk menambahkan kolom warna ke tabel indikator
-- Jalankan ini jika database sudah ada dan perlu update

-- Cek apakah kolom warna sudah ada
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.columns 
WHERE table_schema = DATABASE() 
AND table_name = 'indikator' 
AND column_name = 'warna';

-- Tambah kolom warna jika belum ada
SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE `indikator` ADD COLUMN `warna` varchar(7) DEFAULT ''#007bff'' AFTER `bobot_persen`', 
    'SELECT ''Kolom warna sudah ada'' as info');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update data warna untuk kategori yang sudah ada
UPDATE `indikator` SET `warna` = '#28a745' WHERE `kode_kategori` = 'SI';
UPDATE `indikator` SET `warna` = '#dc3545' WHERE `kode_kategori` = 'EC';
UPDATE `indikator` SET `warna` = '#ffc107' WHERE `kode_kategori` = 'WS';
UPDATE `indikator` SET `warna` = '#17a2b8' WHERE `kode_kategori` = 'WR';
UPDATE `indikator` SET `warna` = '#6f42c1' WHERE `kode_kategori` = 'TR';
UPDATE `indikator` SET `warna` = '#fd7e14' WHERE `kode_kategori` = 'ED';

SELECT 'Patch berhasil dijalankan! Kolom warna sudah tersedia.' as Status;