-- 🗄️ DATABASE OPTIMIZATION SCRIPT
-- Comprehensive database optimization for waste management system

-- =====================================================
-- 1. CREATE INDEXES FOR BETTER PERFORMANCE
-- =====================================================

-- Waste Management Table Indexes
ALTER TABLE waste_management 
ADD INDEX idx_unit_jenis (unit_id, jenis_sampah),
ADD INDEX idx_unit_status (unit_id, status),
ADD INDEX idx_tanggal (tanggal),
ADD INDEX idx_status (status),
ADD INDEX idx_pengirim_gedung (pengirim_gedung),
ADD INDEX idx_kategori_sampah (kategori_sampah),
ADD INDEX idx_created_at (created_at),
ADD INDEX idx_unit_tanggal (unit_id, tanggal DESC);

-- Users Table Indexes
ALTER TABLE users 
ADD INDEX idx_role (role),
ADD INDEX idx_unit_id (unit_id),
ADD INDEX idx_status_aktif (status_aktif),
ADD INDEX idx_email (email),
ADD INDEX idx_username (username),
ADD INDEX idx_role_status (role, status_aktif);

-- Unit Table Indexes
ALTER TABLE unit 
ADD INDEX idx_status_aktif (status_aktif),
ADD INDEX idx_kode_unit (kode_unit);

-- Notifications Table Indexes (if exists)
-- ALTER TABLE notifikasi 
-- ADD INDEX idx_user_id (user_id),
-- ADD INDEX idx_is_read (is_read),
-- ADD INDEX idx_created_at (created_at),
-- ADD INDEX idx_user_read (user_id, is_read);

-- =====================================================
-- 2. OPTIMIZE TABLE STRUCTURE
-- =====================================================

-- Optimize waste_management table
ALTER TABLE waste_management 
MODIFY COLUMN unit_id INT NOT NULL,
MODIFY COLUMN tanggal DATE NOT NULL,
MODIFY COLUMN jenis_sampah VARCHAR(50) NOT NULL,
MODIFY COLUMN satuan VARCHAR(10) NOT NULL,
MODIFY COLUMN jumlah DECIMAL(10,3) NOT NULL DEFAULT 0,
MODIFY COLUMN gedung VARCHAR(100) NOT NULL,
MODIFY COLUMN status ENUM('draft','dikirim','review','disetujui','perlu_revisi') NOT NULL DEFAULT 'draft',
MODIFY COLUMN pengirim_gedung VARCHAR(100) NULL,
MODIFY COLUMN kategori_sampah ENUM('bisa_dijual','tidak_bisa_dijual') DEFAULT 'tidak_bisa_dijual',
MODIFY COLUMN nilai_rupiah DECIMAL(15,2) DEFAULT 0.00;

-- Add constraints for data integrity
ALTER TABLE waste_management 
ADD CONSTRAINT chk_jumlah_positive CHECK (jumlah > 0),
ADD CONSTRAINT chk_nilai_rupiah_positive CHECK (nilai_rupiah >= 0);

-- =====================================================
-- 3. CREATE VIEWS FOR COMMON QUERIES
-- =====================================================

-- View for waste statistics by unit
CREATE OR REPLACE VIEW v_waste_stats_by_unit AS
SELECT 
    w.unit_id,
    u.nama_unit,
    w.jenis_sampah,
    w.status,
    COUNT(*) as jumlah_record,
    SUM(w.jumlah) as total_berat,
    SUM(CASE WHEN w.nilai_rupiah IS NOT NULL THEN w.nilai_rupiah ELSE 0 END) as total_nilai
FROM waste_management w
JOIN unit u ON w.unit_id = u.id
WHERE u.status_aktif = 1
GROUP BY w.unit_id, u.nama_unit, w.jenis_sampah, w.status;

-- View for TPS waste data
CREATE OR REPLACE VIEW v_tps_waste_data AS
SELECT 
    w.*,
    u.nama_unit,
    u.kode_unit
FROM waste_management w
JOIN unit u ON w.unit_id = u.id
WHERE w.pengirim_gedung IS NOT NULL
  AND u.status_aktif = 1;

-- View for user waste summary
CREATE OR REPLACE VIEW v_user_waste_summary AS
SELECT 
    w.unit_id,
    u.nama_unit,
    COUNT(*) as total_submissions,
    COUNT(CASE WHEN w.status = 'disetujui' THEN 1 END) as approved_count,
    COUNT(CASE WHEN w.status = 'perlu_revisi' THEN 1 END) as revision_count,
    COUNT(CASE WHEN w.status = 'draft' THEN 1 END) as draft_count,
    SUM(w.jumlah) as total_weight,
    MAX(w.tanggal) as last_submission_date
FROM waste_management w
JOIN unit u ON w.unit_id = u.id
WHERE u.status_aktif = 1
GROUP BY w.unit_id, u.nama_unit;

-- =====================================================
-- 4. CREATE STORED PROCEDURES FOR COMMON OPERATIONS
-- =====================================================

DELIMITER //

-- Procedure to get waste statistics for a unit
CREATE OR REPLACE PROCEDURE GetWasteStatsByUnit(IN p_unit_id INT)
BEGIN
    SELECT 
        jenis_sampah,
        status,
        COUNT(*) as count,
        SUM(jumlah) as total_weight,
        SUM(CASE WHEN nilai_rupiah IS NOT NULL THEN nilai_rupiah ELSE 0 END) as total_value
    FROM waste_management 
    WHERE unit_id = p_unit_id
    GROUP BY jenis_sampah, status
    ORDER BY jenis_sampah, status;
END //

-- Procedure to get overall waste statistics
CREATE OR REPLACE PROCEDURE GetOverallWasteStats(IN p_unit_id INT)
BEGIN
    SELECT 
        status,
        COUNT(*) as count,
        SUM(jumlah) as total_weight,
        SUM(CASE WHEN nilai_rupiah IS NOT NULL THEN nilai_rupiah ELSE 0 END) as total_value
    FROM waste_management 
    WHERE unit_id = p_unit_id
    GROUP BY status
    ORDER BY 
        CASE status 
            WHEN 'disetujui' THEN 1
            WHEN 'dikirim' THEN 2
            WHEN 'perlu_revisi' THEN 3
            WHEN 'draft' THEN 4
            ELSE 5
        END;
END //

-- Procedure to bulk update waste status
CREATE OR REPLACE PROCEDURE BulkUpdateWasteStatus(
    IN p_ids TEXT,
    IN p_status VARCHAR(20),
    IN p_catatan_admin TEXT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SET @sql = CONCAT(
        'UPDATE waste_management SET status = "', p_status, '"',
        CASE WHEN p_catatan_admin IS NOT NULL THEN 
            CONCAT(', catatan_admin = "', p_catatan_admin, '"') 
        ELSE '' END,
        ', updated_at = NOW() WHERE id IN (', p_ids, ')'
    );
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    COMMIT;
END //

DELIMITER ;

-- =====================================================
-- 5. OPTIMIZE DATABASE CONFIGURATION
-- =====================================================

-- Enable query cache (if not already enabled)
SET GLOBAL query_cache_type = ON;
SET GLOBAL query_cache_size = 67108864; -- 64MB

-- Optimize InnoDB settings
SET GLOBAL innodb_buffer_pool_size = 134217728; -- 128MB (adjust based on available RAM)
SET GLOBAL innodb_log_file_size = 67108864; -- 64MB
SET GLOBAL innodb_flush_log_at_trx_commit = 2; -- Better performance, slight risk

-- =====================================================
-- 6. CREATE TRIGGERS FOR DATA INTEGRITY
-- =====================================================

DELIMITER //

-- Trigger to automatically calculate nilai_rupiah
CREATE OR REPLACE TRIGGER tr_waste_calculate_nilai
BEFORE INSERT ON waste_management
FOR EACH ROW
BEGIN
    -- Only calculate if kategori_sampah is 'bisa_dijual'
    IF NEW.kategori_sampah = 'bisa_dijual' THEN
        -- Get harga from master_harga_sampah if table exists
        IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'master_harga_sampah') THEN
            SET @harga_per_kg = (
                SELECT harga_per_kg 
                FROM master_harga_sampah 
                WHERE jenis_sampah = NEW.jenis_sampah 
                  AND status_aktif = 1 
                  AND dapat_dijual = 1
                LIMIT 1
            );
            
            IF @harga_per_kg IS NOT NULL AND @harga_per_kg > 0 THEN
                -- Convert to kg if needed
                SET @berat_kg = CASE 
                    WHEN NEW.satuan = 'ton' THEN NEW.jumlah * 1000
                    ELSE NEW.jumlah
                END;
                
                SET NEW.nilai_rupiah = @berat_kg * @harga_per_kg;
            END IF;
        END IF;
    ELSE
        SET NEW.nilai_rupiah = 0;
    END IF;
END //

-- Trigger to update nilai_rupiah on update
CREATE OR REPLACE TRIGGER tr_waste_update_nilai
BEFORE UPDATE ON waste_management
FOR EACH ROW
BEGIN
    -- Only recalculate if relevant fields changed
    IF NEW.kategori_sampah != OLD.kategori_sampah 
       OR NEW.jumlah != OLD.jumlah 
       OR NEW.satuan != OLD.satuan 
       OR NEW.jenis_sampah != OLD.jenis_sampah THEN
        
        IF NEW.kategori_sampah = 'bisa_dijual' THEN
            IF EXISTS (SELECT 1 FROM information_schema.tables WHERE table_name = 'master_harga_sampah') THEN
                SET @harga_per_kg = (
                    SELECT harga_per_kg 
                    FROM master_harga_sampah 
                    WHERE jenis_sampah = NEW.jenis_sampah 
                      AND status_aktif = 1 
                      AND dapat_dijual = 1
                    LIMIT 1
                );
                
                IF @harga_per_kg IS NOT NULL AND @harga_per_kg > 0 THEN
                    SET @berat_kg = CASE 
                        WHEN NEW.satuan = 'ton' THEN NEW.jumlah * 1000
                        ELSE NEW.jumlah
                    END;
                    
                    SET NEW.nilai_rupiah = @berat_kg * @harga_per_kg;
                ELSE
                    SET NEW.nilai_rupiah = 0;
                END IF;
            END IF;
        ELSE
            SET NEW.nilai_rupiah = 0;
        END IF;
    END IF;
END //

DELIMITER ;

-- =====================================================
-- 7. ANALYZE AND OPTIMIZE TABLES
-- =====================================================

ANALYZE TABLE waste_management;
ANALYZE TABLE users;
ANALYZE TABLE unit;

OPTIMIZE TABLE waste_management;
OPTIMIZE TABLE users;
OPTIMIZE TABLE unit;

-- =====================================================
-- 8. CREATE MAINTENANCE PROCEDURES
-- =====================================================

DELIMITER //

-- Procedure for daily maintenance
CREATE OR REPLACE PROCEDURE DailyMaintenance()
BEGIN
    -- Update table statistics
    ANALYZE TABLE waste_management;
    ANALYZE TABLE users;
    ANALYZE TABLE unit;
    
    -- Clean up old sessions (if session table exists)
    -- DELETE FROM ci_sessions WHERE timestamp < UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 7 DAY));
    
    -- Log maintenance
    INSERT INTO maintenance_log (action, executed_at) 
    VALUES ('Daily maintenance completed', NOW())
    ON DUPLICATE KEY UPDATE executed_at = NOW();
END //

-- Procedure for weekly maintenance
CREATE OR REPLACE PROCEDURE WeeklyMaintenance()
BEGIN
    -- Optimize tables
    OPTIMIZE TABLE waste_management;
    OPTIMIZE TABLE users;
    OPTIMIZE TABLE unit;
    
    -- Rebuild indexes if needed
    -- ALTER TABLE waste_management ENGINE=InnoDB;
    
    -- Log maintenance
    INSERT INTO maintenance_log (action, executed_at) 
    VALUES ('Weekly maintenance completed', NOW())
    ON DUPLICATE KEY UPDATE executed_at = NOW();
END //

DELIMITER ;

-- =====================================================
-- 9. CREATE MAINTENANCE LOG TABLE
-- =====================================================

CREATE TABLE IF NOT EXISTS maintenance_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(255) NOT NULL,
    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_action (action)
) ENGINE=InnoDB;

-- =====================================================
-- 10. PERFORMANCE MONITORING QUERIES
-- =====================================================

-- Query to check index usage
-- SELECT 
--     TABLE_NAME,
--     INDEX_NAME,
--     CARDINALITY,
--     SUB_PART,
--     PACKED,
--     NULLABLE,
--     INDEX_TYPE
-- FROM information_schema.STATISTICS 
-- WHERE TABLE_SCHEMA = DATABASE()
--   AND TABLE_NAME IN ('waste_management', 'users', 'unit')
-- ORDER BY TABLE_NAME, INDEX_NAME;

-- Query to check table sizes
-- SELECT 
--     TABLE_NAME,
--     ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2) AS 'Size (MB)',
--     TABLE_ROWS
-- FROM information_schema.TABLES 
-- WHERE TABLE_SCHEMA = DATABASE()
--   AND TABLE_NAME IN ('waste_management', 'users', 'unit')
-- ORDER BY (DATA_LENGTH + INDEX_LENGTH) DESC;

-- =====================================================
-- OPTIMIZATION COMPLETE
-- =====================================================

SELECT 'Database optimization completed successfully!' as Status;