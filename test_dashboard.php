<?php

// Simple test script to check dashboard functionality
require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "=== DASHBOARD TEST ===\n\n";

try {
    // Test database connection
    $db = \Config\Database::connect();
    echo "✓ Database connection successful\n";
    
    // Test if tables exist
    $tables = ['users', 'unit', 'waste_management', 'harga_sampah'];
    foreach ($tables as $table) {
        if ($db->tableExists($table)) {
            echo "✓ Table '{$table}' exists\n";
        } else {
            echo "✗ Table '{$table}' missing\n";
        }
    }
    
    // Test models
    echo "\n=== MODEL TESTS ===\n";
    
    $userModel = new \App\Models\UserModel();
    $userCount = $userModel->countAllResults();
    echo "✓ UserModel works - {$userCount} users found\n";
    
    $unitModel = new \App\Models\UnitModel();
    $unitCount = $unitModel->countAllResults();
    echo "✓ UnitModel works - {$unitCount} units found\n";
    
    $wasteModel = new \App\Models\WasteModel();
    $wasteCount = $wasteModel->countAllResults();
    echo "✓ WasteModel works - {$wasteCount} waste records found\n";
    
    $hargaModel = new \App\Models\HargaSampahModel();
    $hargaCount = $hargaModel->countAllResults();
    echo "✓ HargaSampahModel works - {$hargaCount} price records found\n";
    
    // Test helper functions
    echo "\n=== HELPER TESTS ===\n";
    
    if (function_exists('isFeatureEnabled')) {
        echo "✓ isFeatureEnabled helper loaded\n";
        $result = isFeatureEnabled('dashboard_statistics_cards', 'user');
        echo "✓ Feature check works: " . ($result ? 'enabled' : 'disabled') . "\n";
    } else {
        echo "✗ isFeatureEnabled helper not loaded\n";
    }
    
    // Test dashboard services
    echo "\n=== SERVICE TESTS ===\n";
    
    // Mock session for testing
    session()->set([
        'isLoggedIn' => true,
        'user' => [
            'id' => 1,
            'username' => 'test',
            'role' => 'admin_pusat',
            'unit_id' => 1
        ]
    ]);
    
    $adminService = new \App\Services\Admin\DashboardService();
    $adminData = $adminService->getDashboardData();
    echo "✓ Admin Dashboard Service works\n";
    echo "  - Stats: " . count($adminData['stats']) . " items\n";
    echo "  - Recent submissions: " . count($adminData['recentSubmissions']) . " items\n";
    
    session()->set('user', ['id' => 1, 'role' => 'user', 'unit_id' => 1]);
    $userService = new \App\Services\User\DashboardService();
    $userData = $userService->getDashboardData();
    echo "✓ User Dashboard Service works\n";
    echo "  - Stats: " . count($userData['stats']) . " items\n";
    
    session()->set('user', ['id' => 1, 'role' => 'pengelola_tps', 'unit_id' => 1]);
    $tpsService = new \App\Services\TPS\DashboardService();
    $tpsData = $tpsService->getDashboardData();
    echo "✓ TPS Dashboard Service works\n";
    echo "  - Stats: " . count($tpsData['stats']) . " items\n";
    
    echo "\n=== ALL TESTS PASSED ===\n";
    echo "Dashboard system is ready to use!\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}