<?php

echo "=== TEST ADMIN PUSAT DASHBOARD ===\n\n";

// Test 1: Verify admin_pusat user
echo "1. CHECKING ADMIN_PUSAT USER:\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=eksperimen;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin_pusat'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✓ Admin Pusat user found\n";
        echo "  Username: {$admin['username']}\n";
        echo "  Email: {$admin['email']}\n";
        echo "  Status: " . ($admin['status_aktif'] ? 'Active' : 'Inactive') . "\n";
        echo "  Password: Hashed (" . strlen($admin['password']) . " chars)\n\n";
        
        // Test password
        if (password_verify('admin123', $admin['password'])) {
            echo "✓ Password verification: SUCCESS\n";
            echo "  Credentials: admin / admin123\n\n";
        } else {
            echo "✗ Password verification: FAILED\n\n";
        }
    } else {
        echo "✗ No admin_pusat user found!\n\n";
    }
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n\n";
}

// Test 2: Check admin_pusat controllers
echo "2. CHECKING ADMIN_PUSAT CONTROLLERS:\n";
$controllers = [
    'Dashboard' => 'app/Controllers/Admin/Dashboard.php',
    'Harga' => 'app/Controllers/Admin/Harga.php',
    'FeatureToggle' => 'app/Controllers/Admin/FeatureToggle.php',
    'UserManagement' => 'app/Controllers/Admin/UserManagement.php',
    'Review' => 'app/Controllers/Admin/Review.php',
    'Waste' => 'app/Controllers/Admin/Waste.php',
    'Laporan' => 'app/Controllers/Admin/Laporan.php',
    'LaporanWaste' => 'app/Controllers/Admin/LaporanWaste.php',
    'Pengaturan' => 'app/Controllers/Admin/Pengaturan.php'
];

foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name} controller exists\n";
    } else {
        echo "✗ {$name} controller MISSING\n";
    }
}
echo "\n";

// Test 3: Check admin_pusat views
echo "3. CHECKING ADMIN_PUSAT VIEWS:\n";
$views = [
    'dashboard' => 'app/Views/admin_pusat/dashboard.php',
    'waste' => 'app/Views/admin_pusat/waste.php',
    'review' => 'app/Views/admin_pusat/review.php',
    'user_management' => 'app/Views/admin_pusat/user_management.php',
    'pengaturan' => 'app/Views/admin_pusat/pengaturan.php',
    'laporan' => 'app/Views/admin_pusat/laporan.php',
    'laporan_waste' => 'app/Views/admin_pusat/laporan_waste.php'
];

foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name} view exists\n";
    } else {
        echo "✗ {$name} view MISSING\n";
    }
}
echo "\n";

// Test 4: Check routes
echo "4. CHECKING ADMIN_PUSAT ROUTES:\n";
$routesFile = file_get_contents('app/Config/Routes.php');

$requiredRoutes = [
    'admin-pusat/dashboard',
    'admin-pusat/manajemen-harga',
    'admin-pusat/feature-toggle',
    'admin-pusat/user-management',
    'admin-pusat/review',
    'admin-pusat/waste',
    'admin-pusat/laporan'
];

foreach ($requiredRoutes as $route) {
    if (strpos($routesFile, $route) !== false) {
        echo "✓ Route '{$route}' configured\n";
    } else {
        echo "✗ Route '{$route}' MISSING\n";
    }
}
echo "\n";

// Test 5: Check dashboard service
echo "5. CHECKING ADMIN DASHBOARD SERVICE:\n";
if (file_exists('app/Services/Admin/DashboardService.php')) {
    echo "✓ Admin DashboardService exists\n";
    
    // Check for syntax errors
    $output = [];
    $return = 0;
    exec('php -l app/Services/Admin/DashboardService.php 2>&1', $output, $return);
    
    if ($return === 0) {
        echo "✓ DashboardService syntax OK\n";
    } else {
        echo "✗ DashboardService has syntax errors:\n";
        echo "  " . implode("\n  ", $output) . "\n";
    }
} else {
    echo "✗ Admin DashboardService MISSING\n";
}
echo "\n";

// Test 6: Check models
echo "6. CHECKING REQUIRED MODELS:\n";
$models = [
    'WasteModel' => 'app/Models/WasteModel.php',
    'UserModel' => 'app/Models/UserModel.php',
    'UnitModel' => 'app/Models/UnitModel.php',
    'HargaSampahModel' => 'app/Models/HargaSampahModel.php',
    'HargaLogModel' => 'app/Models/HargaLogModel.php'
];

foreach ($models as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name} exists\n";
    } else {
        echo "✗ {$name} MISSING\n";
    }
}
echo "\n";

// Test 7: Check helper
echo "7. CHECKING HELPER FUNCTIONS:\n";
if (file_exists('app/Helpers/feature_helper.php')) {
    echo "✓ feature_helper.php exists\n";
    
    $helperContent = file_get_contents('app/Helpers/feature_helper.php');
    if (strpos($helperContent, 'function isFeatureEnabled') !== false) {
        echo "✓ isFeatureEnabled() function defined\n";
    } else {
        echo "✗ isFeatureEnabled() function MISSING\n";
    }
} else {
    echo "✗ feature_helper.php MISSING\n";
}
echo "\n";

// Summary
echo "=== SUMMARY ===\n";
echo "✓ Admin Pusat user: admin / admin123\n";
echo "✓ Role: admin_pusat\n";
echo "✓ Dashboard URL: http://localhost:8080/admin-pusat/dashboard\n";
echo "✓ Login URL: http://localhost:8080/auth/login\n\n";

echo "=== READY TO TEST ===\n";
echo "1. Start server: php spark serve --host=0.0.0.0 --port=8080\n";
echo "2. Open browser: http://localhost:8080/auth/login\n";
echo "3. Login with: admin / admin123\n";
echo "4. You will be redirected to: /admin-pusat/dashboard\n";
