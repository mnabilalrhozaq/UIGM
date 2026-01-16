-- Cek semua kolom waste_management
USE eksperimen;

DESCRIBE waste_management;

-- Fokus ke kolom yang bermasalah
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen' 
AND TABLE_NAME = 'waste_management' 
AND COLUMN_NAME IN ('jenis_sampah', 'kategori_sampah', 'nilai_rupiah', 'berat_kg', 'jumlah', 'status');
