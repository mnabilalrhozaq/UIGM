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
            // For now, return true for all features to avoid breaking the dashboard
            // In a real implementation, this would check a database or config file
            
            $enabledFeatures = [
                'dashboard_statistics_cards' => true,
                'dashboard_waste_summary' => true,
                'dashboard_recent_activity' => true,
                'real_time_updates' => true,
                'export_data' => true,
                'waste_management' => true,
                'price_management' => true,
                'user_management' => true,
                'feature_toggle' => true,
                'review_system' => true,
                'reporting' => true
            ];
            
            // Check if feature exists in our list
            if (!isset($enabledFeatures[$feature])) {
                log_message('warning', "Unknown feature requested: {$feature}");
                return false;
            }
            
            // Role-specific feature checks
            if ($role) {
                $roleFeatures = [
                    'admin_pusat' => [
                        'dashboard_statistics_cards' => true,
                        'dashboard_waste_summary' => true,
                        'dashboard_recent_activity' => true,
                        'price_management' => true,
                        'user_management' => true,
                        'feature_toggle' => true,
                        'review_system' => true,
                        'reporting' => true
                    ],
                    'user' => [
                        'dashboard_statistics_cards' => true,
                        'dashboard_waste_summary' => true,
                        'dashboard_recent_activity' => true,
                        'real_time_updates' => true,
                        'export_data' => true,
                        'waste_management' => true
                    ],
                    'pengelola_tps' => [
                        'dashboard_statistics_cards' => true,
                        'dashboard_waste_summary' => true,
                        'dashboard_recent_activity' => true,
                        'waste_management' => true,
                        'export_data' => true
                    ]
                ];
                
                if (isset($roleFeatures[$role][$feature])) {
                    return $roleFeatures[$role][$feature];
                }
            }
            
            return $enabledFeatures[$feature] ?? false;
            
        } catch (\Exception $e) {
            log_message('error', 'Feature toggle error: ' . $e->getMessage());
            return false;
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
        $configs = [
            'real_time_updates' => [
                'enabled' => true,
                'refresh_interval' => 30,
                'max_retries' => 3
            ],
            'dashboard_statistics_cards' => [
                'enabled' => true,
                'show_approved' => true,
                'show_pending' => true,
                'show_rejected' => true,
                'show_draft' => true
            ],
            'export_data' => [
                'enabled' => true,
                'formats' => ['excel', 'csv', 'pdf'],
                'max_records' => 10000
            ]
        ];
        
        return $configs[$feature] ?? [];
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
        $features = [
            'dashboard_statistics_cards' => [
                'name' => 'Dashboard Statistics Cards',
                'description' => 'Show statistics cards on dashboard',
                'roles' => ['admin_pusat', 'user', 'pengelola_tps']
            ],
            'dashboard_waste_summary' => [
                'name' => 'Dashboard Waste Summary',
                'description' => 'Show waste management summary on dashboard',
                'roles' => ['admin_pusat', 'user', 'pengelola_tps']
            ],
            'dashboard_recent_activity' => [
                'name' => 'Dashboard Recent Activity',
                'description' => 'Show recent activity feed on dashboard',
                'roles' => ['admin_pusat', 'user', 'pengelola_tps']
            ],
            'real_time_updates' => [
                'name' => 'Real-time Updates',
                'description' => 'Enable real-time dashboard updates',
                'roles' => ['user']
            ],
            'export_data' => [
                'name' => 'Data Export',
                'description' => 'Allow users to export data',
                'roles' => ['admin_pusat', 'user', 'pengelola_tps']
            ],
            'waste_management' => [
                'name' => 'Waste Management',
                'description' => 'Core waste management functionality',
                'roles' => ['admin_pusat', 'user', 'pengelola_tps']
            ],
            'price_management' => [
                'name' => 'Price Management',
                'description' => 'Manage waste prices',
                'roles' => ['admin_pusat']
            ],
            'user_management' => [
                'name' => 'User Management',
                'description' => 'Manage system users',
                'roles' => ['admin_pusat']
            ],
            'feature_toggle' => [
                'name' => 'Feature Toggle',
                'description' => 'Enable/disable system features',
                'roles' => ['admin_pusat']
            ],
            'review_system' => [
                'name' => 'Review System',
                'description' => 'Review and approve waste data',
                'roles' => ['admin_pusat']
            ],
            'reporting' => [
                'name' => 'Reporting',
                'description' => 'Generate system reports',
                'roles' => ['admin_pusat']
            ]
        ];
        
        if ($role) {
            return array_filter($features, function($feature) use ($role) {
                return in_array($role, $feature['roles']);
            });
        }
        
        return $features;
    }
}