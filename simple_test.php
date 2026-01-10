<?php

echo "=== SIMPLE DASHBOARD TEST ===\n\n";

// Test 1: Check if files exist
$files = [
    'app/Controllers/Admin/Dashboard.php',
    'app/Controllers/User/Dashboard.php', 
    'app/Controllers/TPS/Dashboard.php',
    'app/Services/Admin/DashboardService.php',
    'app/Services/User/DashboardService.php',
    'app/Services/TPS/DashboardService.php',
    'app/Models/WasteModel.php',
    'app/Models/UserModel.php',
    'app/Models/UnitModel.php',
    'app/Models/HargaSampahModel.php',
    'app/Models/HargaLogModel.php',
    'app/Helpers/feature_helper.php',
    'app/Views/admin_pusat/dashboard.php',
    'app/Views/user/dashboard.php',
    'app/Views/pengelola_tps/dashboard.php'
];

echo "=== FILE EXISTENCE CHECK ===\n";
foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✓ {$file}\n";
    } else {
        echo "✗ {$file} MISSING\n";
    }
}

// Test 2: Check PHP syntax
echo "\n=== SYNTAX CHECK ===\n";
$phpFiles = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'php';
});

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = [];
        $return = 0;
        exec("php -l \"{$file}\" 2>&1", $output, $return);
        
        if ($return === 0) {
            echo "✓ {$file} - Syntax OK\n";
        } else {
            echo "✗ {$file} - Syntax Error:\n";
            echo "  " . implode("\n  ", $output) . "\n";
        }
    }
}

// Test 3: Check routes file
echo "\n=== ROUTES CHECK ===\n";
if (file_exists('app/Config/Routes.php')) {
    $routesContent = file_get_contents('app/Config/Routes.php');
    
    $requiredRoutes = [
        'admin-pusat/dashboard',
        'user/dashboard', 
        'pengelola-tps/dashboard'
    ];
    
    foreach ($requiredRoutes as $route) {
        if (strpos($routesContent, $route) !== false) {
            echo "✓ Route '{$route}' configured\n";
        } else {
            echo "✗ Route '{$route}' missing\n";
        }
    }
} else {
    echo "✗ Routes.php file missing\n";
}

// Test 4: Check helper function
echo "\n=== HELPER FUNCTION CHECK ===\n";
if (file_exists('app/Helpers/feature_helper.php')) {
    $helperContent = file_get_contents('app/Helpers/feature_helper.php');
    
    if (strpos($helperContent, 'function isFeatureEnabled') !== false) {
        echo "✓ isFeatureEnabled function defined\n";
    } else {
        echo "✗ isFeatureEnabled function missing\n";
    }
    
    if (strpos($helperContent, 'function getFeatureConfig') !== false) {
        echo "✓ getFeatureConfig function defined\n";
    } else {
        echo "✗ getFeatureConfig function missing\n";
    }
} else {
    echo "✗ feature_helper.php missing\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "If all items show ✓, the dashboard system should work correctly.\n";
echo "You can now test by accessing:\n";
echo "- http://localhost:8080/auth/login\n";
echo "- Use test credentials from the previous session\n";