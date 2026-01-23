-- Cek Feature Toggle untuk Dashboard
SELECT 
    id,
    feature_key,
    feature_name,
    category,
    is_enabled,
    description
FROM feature_toggle 
WHERE feature_key LIKE '%dashboard%' 
   OR feature_key LIKE '%widget%'
   OR category = 'dashboard'
ORDER BY category, feature_key;
