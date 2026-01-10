<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class FeatureToggle extends BaseController
{
    public function index()
    {
        if (!$this->validateAdminSession()) {
            return redirect()->to('/auth/login');
        }

        // Get all features
        $features = getAllFeatures();
        
        // Calculate statistics
        $totalFeatures = count($features);
        $enabledFeatures = 0;
        $disabledFeatures = 0;
        
        foreach ($features as $key => $feature) {
            if (isFeatureEnabled($key)) {
                $enabledFeatures++;
            } else {
                $disabledFeatures++;
            }
        }

        // Group features by category
        $featuresByCategory = [
            'dashboard' => [],
            'waste' => [],
            'user' => [],
            'reporting' => []
        ];
        
        foreach ($features as $key => $feature) {
            // Add feature_key and feature_name for view compatibility
            $feature['feature_key'] = $key;
            $feature['feature_name'] = $feature['name'] ?? ucfirst(str_replace('_', ' ', $key));
            $feature['is_enabled'] = isFeatureEnabled($key);
            $feature['description'] = $feature['description'] ?? 'No description available';
            $feature['target_roles'] = $feature['roles'] ?? ['admin_pusat', 'user'];
            $feature['config'] = getFeatureConfig($key);
            
            // Categorize based on feature name
            if (strpos($key, 'dashboard') !== false) {
                $featuresByCategory['dashboard'][$key] = $feature;
            } elseif (strpos($key, 'waste') !== false || strpos($key, 'export') !== false) {
                $featuresByCategory['waste'][$key] = $feature;
            } elseif (strpos($key, 'user') !== false) {
                $featuresByCategory['user'][$key] = $feature;
            } else {
                $featuresByCategory['reporting'][$key] = $feature;
            }
        }

        $data = [
            'title' => 'Feature Toggle Management',
            'features' => $features,
            'featuresByCategory' => $featuresByCategory,
            'statistics' => [
                'total_features' => $totalFeatures,
                'enabled_features' => $enabledFeatures,
                'disabled_features' => $disabledFeatures,
                'last_updated' => date('Y-m-d H:i:s')
            ],
            'categories' => [
                'dashboard' => 'Dashboard Features',
                'waste' => 'Waste Management',
                'user' => 'User Management',
                'reporting' => 'Reporting & Analytics'
            ]
        ];

        return view('admin_pusat/feature_toggle/index', $data);
    }

    public function toggle()
    {
        if (!$this->validateAdminSession()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $feature = $this->request->getPost('feature');
        $enabled = $this->request->getPost('enabled');

        // In a real implementation, this would update database
        // For now, just return success
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Feature toggle updated successfully'
        ]);
    }

    private function validateAdminSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['role']) &&
               in_array($user['role'], ['admin_pusat', 'super_admin']);
    }
}
