<?php

/**
 * Feature Toggle Helper Functions
 * Provides feature toggle functionality for the application
 */

if (!function_exists('isFeatureEnabled')) {
    /**
     * Check if a feature is enabled for a specific role
     * 
     * @param string $feature Feature name
     * @param string|null $role User role (optional)
     * @return bool
     */
    function isFeatureEnabled(string $feature, ?string $role = null): bool
    {
        try {
            // Load model
            $model = new \App\Models\FeatureToggleModel();
            
            // Check from database
            return $model->isEnabled($feature, $role);
            
        } catch (\Exception $e) {
            log_message('error', 'Feature toggle error: ' . $e->getMessage());
            // Fallback: return true for critical features
            $criticalFeatures = [
                'dashboard_statistics_cards',
                'dashboard_waste_summary',
                'waste_management'
            ];
            return in_array($feature, $criticalFeatures);
        }
    }
}

if (!function_exists('getFeatureConfig')) {
    /**
     * Get configuration for a specific feature
     * 
     * @param string $feature Feature name
     * @return array
     */
    function getFeatureConfig(string $feature): array
    {
        try {
            $model = new \App\Models\FeatureToggleModel();
            $featureData = $model->getByKey($feature);
            
            if ($featureData && !empty($featureData['config'])) {
                return json_decode($featureData['config'], true) ?? [];
            }
            
            return [];
            
        } catch (\Exception $e) {
            log_message('error', 'Get feature config error: ' . $e->getMessage());
            return [];
        }
    }
}

if (!function_exists('getAllFeatures')) {
    /**
     * Get all available features with their status
     * 
     * @param string|null $role Filter by role
     * @return array
     */
    function getAllFeatures(?string $role = null): array
    {
        try {
            $model = new \App\Models\FeatureToggleModel();
            
            if ($role) {
                $features = $model->getByRole($role);
            } else {
                $features = $model->findAll();
            }
            
            // Convert to associative array with feature_key as key
            $result = [];
            foreach ($features as $feature) {
                $key = $feature['feature_key'];
                $result[$key] = [
                    'name' => $feature['feature_name'],
                    'description' => $feature['description'],
                    'enabled' => (bool)$feature['is_enabled'],
                    'roles' => json_decode($feature['target_roles'], true) ?? [],
                    'config' => json_decode($feature['config'], true) ?? []
                ];
            }
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Get all features error: ' . $e->getMessage());
            return [];
        }
    }
}