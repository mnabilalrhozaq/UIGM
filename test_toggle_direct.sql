-- Test toggle feature langsung di database

-- 1. Cek status sekarang
SELECT feature_key, feature_name, is_enabled FROM feature_toggles WHERE feature_key = 'help_tooltips';

-- 2. Toggle manual (ubah 1 jadi 0 atau sebaliknya)
UPDATE feature_toggles SET is_enabled = 0, updated_by = 1 WHERE feature_key = 'help_tooltips';

-- 3. Cek lagi
SELECT feature_key, feature_name, is_enabled FROM feature_toggles WHERE feature_key = 'help_tooltips';

-- 4. Toggle balik
UPDATE feature_toggles SET is_enabled = 1, updated_by = 1 WHERE feature_key = 'help_tooltips';

-- 5. Cek lagi
SELECT feature_key, feature_name, is_enabled FROM feature_toggles WHERE feature_key = 'help_tooltips';
