-- Perbaikan field nilai_input di tabel penilaian_unit
-- Pilihan 1: Ubah field menjadi nullable (jika memang dibutuhkan)
-- ALTER TABLE penilaian_unit MODIFY COLUMN nilai_input DECIMAL(5,2) NULL DEFAULT 0.00;

-- Pilihan 2: Set default value untuk field NOT NULL (RECOMMENDED)
ALTER TABLE penilaian_unit MODIFY COLUMN nilai_input DECIMAL(5,2) NOT NULL DEFAULT 0.00;

-- Update existing NULL values to 0
UPDATE penilaian_unit SET nilai_input = 0.00 WHERE nilai_input IS NULL;

-- Tambah constraint untuk memastikan nilai dalam range 0-100
ALTER TABLE penilaian_unit ADD CONSTRAINT chk_nilai_input_range 
CHECK (nilai_input >= 0 AND nilai_input <= 100);

-- Tambah index untuk performa query
CREATE INDEX idx_penilaian_status ON penilaian_unit(status);
CREATE INDEX idx_penilaian_unit_kategori ON penilaian_unit(unit_id, kategori_uigm);

-- Verifikasi perubahan
DESCRIBE penilaian_unit;
SELECT COUNT(*) as total_records, 
       COUNT(nilai_input) as non_null_values,
       MIN(nilai_input) as min_value,
       MAX(nilai_input) as max_value,
       AVG(nilai_input) as avg_value
FROM penilaian_unit;