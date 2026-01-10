<?php

echo "=== QUICK TEST - ADMIN PUSAT ===\n\n";

// Test 1: Check all view files exist
echo "1. CHECKING VIEW FILES:\n";
$views = [
    'dashboard' => 'app/Views/admin_pusat/dashboard.php',
    'manajemen_harga' => 'app/Views/admin_pusat/manajemen_harga/index.php',
    'feature_toggle' => 'app/Views/admin_pusat/feature_toggle/index.php',
    'user_management' => 'app/Views/admin_pusat/user_management.php',
    'review' => 'app/Views/admin_pusat/review.php',
    'waste' => 'app/Views/admin_pusat/waste.php',
    'laporan' => 'app/Views/admin_pusat/laporan.php',
    'pengaturan' => 'app/Views/admin_pusat/pengaturan.php'
];

$allViewsExist = true;
foreach ($views as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name}\n";
    } else {
        echo "✗ {$name} MISSING: {$path}\n";
        $allViewsExist = false;
    }
}
echo "\n";

// Test 2: Check all controllers exist
echo "2. CHECKING CONTROLLERS:\n";
$controllers = [
    'Dashboard' => 'app/Controllers/Admin/Dashboard.php',
    'Harga' => 'app/Controllers/Admin/Harga.php',
    'FeatureToggle' => 'app/Controllers/Admin/FeatureToggle.php',
    'UserManagement' => 'app/Controllers/Admin/UserManagement.php',
    'Review' => 'app/Controllers/Admin/Review.php',
    'Waste' => 'app/Controllers/Admin/Waste.php',
    'Laporan' => 'app/Controllers/Admin/Laporan.php',
    'Pengaturan' => 'app/Controllers/Admin/Pengaturan.php'
];

$allControllersExist = true;
foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        echo "✓ {$name}\n";
    } else {
        echo "✗ {$name} MISSING\n";
        $allControllersExist = false;
    }
}
echo "\n";

// Test 3: Check syntax errors
echo "3. CHECKING SYNTAX:\n";
$hasErrors = false;
foreach ($controllers as $name => $path) {
    if (file_exists($path)) {
        $output = [];
        $return = 0;
        exec("php -l \"{$path}\" 2>&1", $output, $return);
        
        if ($return === 0) {
            echo "✓ {$name} - No syntax errors\n";
        } else {
            echo "✗ {$name} - Syntax error!\n";
            $hasErrors = true;
        }
    }
}
echo "\n";

// Test 4: Check database and user
echo "4. CHECKING DATABASE:\n";
try {
    $pdo = new PDO("mysql:host=localhost;dbname=eksperimen;charset=utf8mb4", "root", "");
    echo "✓ Database connection OK\n";
    
    $stmt = $pdo->prepare("SELECT username, role, status_aktif FROM users WHERE role = 'admin_pusat'");
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        echo "✓ Admin user exists: {$admin['username']}\n";
        echo "  Status: " . ($admin['status_aktif'] ? 'Active' : 'Inactive') . "\n";
    } else {
        echo "✗ No admin_pusat user found!\n";
    }
} catch (PDOException $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}
echo "\n";

// Summary
echo "=== SUMMARY ===\n";
if ($allViewsExist && $allControllersExist && !$hasErrors) {
    echo "✅ ALL CHECKS PASSED!\n\n";
    echo "Dashboard admin_pusat siap digunakan:\n";
    echo "1. Login: http://localhost:8080/auth/login\n";
    echo "2. Username: admin\n";
    echo "3. Password: admin123\n";
    echo "4. Dashboard: http://localhost:8080/admin-pusat/dashboard\n";
} else {
    echo "❌ SOME CHECKS FAILED!\n";
    if (!$allViewsExist) echo "- Some views are missing\n";
    if (!$allControllersExist) echo "- Some controllers are missing\n";
    if ($hasErrors) echo "- Some files have syntax errors\n";
}
