-- =====================================================
-- FIX NULL DATA_INPUT VALUES
-- Update existing NULL values to empty JSON objects
-- =====================================================

USE `uigm_polban`;

-- Update existing NULL data_input values to empty JSON objects
UPDATE `review_kategori` 
SET `data_input` = '{}' 
WHERE `data_input` IS NULL;

-- Verify the update
SELECT COUNT(*) as total_records, 
       SUM(CASE WHEN data_input IS NULL THEN 1 ELSE 0 END) as null_count,
       SUM(CASE WHEN data_input = '{}' THEN 1 ELSE 0 END) as empty_json_count
FROM `review_kategori`;

SELECT 'NULL data_input values fixed!' as status;