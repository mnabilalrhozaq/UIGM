-- Cek struktur kolom jenis_sampah
USE eksperimen;

-- Cara 1: SHOW COLUMNS
SHOW COLUMNS FROM waste_management LIKE 'jenis_sampah';

-- Cara 2: DESCRIBE
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen' 
AND TABLE_NAME = 'waste_management' 
AND COLUMN_NAME = 'jenis_sampah';

-- Cek jenis sampah yang ada di data
SELECT DISTINCT jenis_sampah FROM waste_management ORDER BY jenis_sampah;
