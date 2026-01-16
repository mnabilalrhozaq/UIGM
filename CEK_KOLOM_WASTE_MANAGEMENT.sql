-- Cek struktur lengkap tabel waste_management
DESCRIBE waste_management;

-- Atau gunakan SHOW COLUMNS
SHOW COLUMNS FROM waste_management;

-- Cek apakah ada kolom yang berhubungan dengan user
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_KEY
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'eksperimen'
AND TABLE_NAME = 'waste_management'
AND (COLUMN_NAME LIKE '%user%' OR COLUMN_NAME LIKE '%created%' OR COLUMN_NAME LIKE '%by%');
