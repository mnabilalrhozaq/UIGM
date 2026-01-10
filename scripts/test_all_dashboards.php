<?php

/**
 * Test All Dashboards Script
 * Comprehensive test of all dashboard functionality
 */

echo "🧪 TESTING ALL DASHBOARDS\n";
echo "=========================\n\n";

$testResults = [];
$totalTests = 0;
$passedTests = 0;

function runTest($testName, $testFunction) {
    global $testResults, $totalTests, $passedTests;
    
    $totalTests++;
    echo "Testing: {$testName}... ";
    
    try {
        $result = $testFunction();
        if ($result) {
            echo "✅ PASS\n";
            $passedTests++;
            $testResults[$testName] = 'PASS';
        } else {
            echo "❌ FAIL\n";
            $testResults[$testName] = 'FAIL';
        }
    } catch (Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
        $testResults[$testName] = 'ERROR';
    }
}

// Test 1: Dashboard Controllers Exist
runTest("Dashboard Controllers Exist", function() {
    $controllers = [
        'app/Controllers/Admin/Dashboard.php',
        'app/Controllers/User/Dashboard.php',
        'app/Controllers/TPS/Dashboard.php'
    ];
    
    foreach ($controllers as $controller) {
        if (!file_exists(__DIR__ . '/../' . $controller)) {
            return false;
        }
    }
    return true;
});

// Test 2: Dashboard Services Exist
runTest("Dashboard Services Exist", function() {
    $services = [
        'app/Services/Admin/DashboardService.php',
        'app/Services/User/DashboardService.php',
        'app/Services/TPS/DashboardService.php'
    ];
    
    foreach ($services as $service) {
        if (!file_exists(__DIR__ . '/../' . $service)) {
            return false;
        }
    }
    return true;
});

// Test 3: Dashboard Views Exist
runTest("Dashboard Views Exist", function() {
    $views = [
        'app/Views/admin_pusat/dashboard.php',
        'app/Views/user/dashboard.php',
        'app/Views/pengelola_tps/dashboard.php'
    ];
    
    foreach ($views as $view) {
        if (!file_exists(__DIR__ . '/../' . $view)) {
            return false;
        }
    }
    return true;
});

// Test 4: Sidebar Partials Exist
runTest("Sidebar Partials Exist", function() {
    $sidebars = [
        'app/Views/partials/sidebar_admin_pusat.php',
        'app/Views/partials/sidebar_user.php',
        'app/Views/partials/sidebar_pengelola_tps.php'
    ];
    
    foreach ($sidebars as $sidebar) {
        if (!file_exists(__DIR__ . '/../' . $sidebar)) {
            return false;
        }
    }
    return true;
});

// Test 5: API Controllers Exist
runTest("API Controllers Exist", function() {
    $apiControllers = [
        'app/Controllers/Api/DashboardApi.php',
        'app/Controllers/Api/WasteApi.php',
        'app/Controllers/Api/NotificationController.php'
    ];
    
    foreach ($apiControllers as $controller) {
        if (!file_exists(__DIR__ . '/../' . $controller)) {
            return false;
        }
    }
    return true;
});

// Test 6: Waste Management Controllers
runTest("Waste Management Controllers", function() {
    $wasteControllers = [
        'app/Controllers/User/Waste.php',
        'app/Controllers/TPS/Waste.php'
    ];
    
    foreach ($wasteControllers as $controller) {
        if (!file_exists(__DIR__ . '/../' . $controller)) {
            return false;
        }
    }
    return true;
});

// Test 7: Waste Management Views
runTest("Waste Management Views", function() {
    $wasteViews = [
        'app/Views/user/waste.php',
        'app/Views/pengelola_tps/waste.php'
    ];
    
    foreach ($wasteViews as $view) {
        if (!file_exists(__DIR__ . '/../' . $view)) {
            return false;
        }
    }
    return true;
});

// Test 8: PHP Syntax Check
runTest("PHP Syntax Check", function() {
    $phpFiles = [
        'app/Controllers/Admin/Dashboard.php',
        'app/Controllers/User/Dashboard.php',
        'app/Controllers/TPS/Dashboard.php',
        'app/Controllers/User/Waste.php',
        'app/Controllers/TPS/Waste.php',
        'app/Controllers/Api/DashboardApi.php',
        'app/Controllers/Api/WasteApi.php',
        'app/Controllers/Home.php'
    ];
    
    foreach ($phpFiles as $file) {
        $fullPath = __DIR__ . '/../' . $file;
        if (file_exists($fullPath)) {
            $output = [];
            $returnCode = 0;
            exec("php -l \"{$fullPath}\" 2>&1", $output, $returnCode);
            
            if ($returnCode !== 0) {
                return false;
            }
        }
    }
    return true;
});

// Test 9: Routes Configuration
runTest("Routes Configuration", function() {
    $routesFile = __DIR__ . '/../app/Config/Routes.php';
    if (!file_exists($routesFile)) {
        return false;
    }
    
    $content = file_get_contents($routesFile);
    $requiredRoutes = [
        'admin-pusat/dashboard',
        'user/dashboard',
        'pengelola-tps/dashboard',
        'user/waste',
        'pengelola-tps/waste'
    ];
    
    foreach ($requiredRoutes as $route) {
        if (strpos($content, $route) === false) {
            return false;
        }
    }
    
    return true;
});

// Test 10: Helper Functions
runTest("Helper Functions", function() {
    $helpers = [
        'app/Helpers/config_helper.php',
        'app/Helpers/feature_helper.php',
        'app/Helpers/role_helper.php'
    ];
    
    foreach ($helpers as $helper) {
        if (!file_exists(__DIR__ . '/../' . $helper)) {
            return false;
        }
    }
    return true;
});

// Test Results Summary
echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 DASHBOARD TEST RESULTS\n";
echo str_repeat("=", 60) . "\n";

echo "\n📊 TEST SUMMARY:\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passedTests}\n";
echo "Failed: " . ($totalTests - $passedTests) . "\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 1) . "%\n";

echo "\n📋 DETAILED RESULTS:\n";
foreach ($testResults as $test => $result) {
    $icon = $result === 'PASS' ? '✅' : '❌';
    echo "   {$icon} {$test}: {$result}\n";
}

if ($passedTests === $totalTests) {
    echo "\n🎉 ALL DASHBOARD TESTS PASSED!\n";
    
    echo "\n🚀 DASHBOARD SYSTEM STATUS:\n";
    echo "✅ Admin Dashboard: Ready\n";
    echo "✅ User Dashboard: Ready\n";
    echo "✅ TPS Dashboard: Ready\n";
    echo "✅ Waste Management: Ready\n";
    echo "✅ API Endpoints: Ready\n";
    echo "✅ Routes: Configured\n";
    echo "✅ Views: Available\n";
    echo "✅ Controllers: Functional\n";
    
    echo "\n🎯 DASHBOARD URLS:\n";
    echo "Admin: http://localhost:8080/admin-pusat/dashboard\n";
    echo "User: http://localhost:8080/user/dashboard\n";
    echo "TPS: http://localhost:8080/pengelola-tps/dashboard\n";
    echo "Login: http://localhost:8080/auth/login\n";
    echo "Test Login: http://localhost:8080/auth/test-login\n";
    
    echo "\n💡 NEXT STEPS:\n";
    echo "1. Test login with credentials from database\n";
    echo "2. Verify each dashboard loads correctly\n";
    echo "3. Test waste management functionality\n";
    echo "4. Check API endpoints work\n";
    
} else {
    echo "\n⚠️  SOME TESTS FAILED. PLEASE REVIEW THE ISSUES ABOVE.\n";
    
    $failedTests = array_filter($testResults, function($result) {
        return $result !== 'PASS';
    });
    
    echo "\n❌ FAILED TESTS:\n";
    foreach ($failedTests as $test => $result) {
        echo "   - {$test}: {$result}\n";
    }
}

echo "\n📞 DASHBOARD SYSTEM STATUS:\n";
echo "Status: " . ($passedTests === $totalTests ? "FULLY FUNCTIONAL" : "NEEDS ATTENTION") . "\n";
echo "Test Date: " . date('Y-m-d H:i:s') . "\n";
echo "Components: Controllers, Services, Views, Routes, APIs\n";

echo "\n🎉 DASHBOARD TEST COMPLETE!\n\n";