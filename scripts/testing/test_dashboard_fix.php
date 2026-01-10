<?php

/**
 * TEST DASHBOARD FIX
 * Test apakah masalah dashboard file sudah teratasi
 */

echo "<h2>📁 Test Dashboard File Fix</h2>";
echo "<hr>";

// Test 1: Check file existence
echo "<h3>1. Check File Existence</h3>";

$requiredFiles = [
    'app/Views/admin_unit/dashboard_clean.php',
    'app/Controllers/AdminUnit.php',
    'app/Views/layouts/admin_unit.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

// Test 2: Check controller content
echo "<br><h3>2. Check Controller Content</h3>";

if (file_exists('app/Controllers/AdminUnit.php')) {
    $controllerContent = file_get_contents('app/Controllers/AdminUnit.php');

    if (strpos($controllerContent, "view('admin_unit/dashboard_clean'") !== false) {
        echo "✅ Controller uses correct view path: dashboard_clean<br>";
    } else if (strpos($controllerContent, "view('admin_unit/dashboard'") !== false) {
        echo "❌ Controller still uses wrong view path: dashboard<br>";
    } else {
        echo "⚠️ Could not find view() call in controller<br>";
    }
} else {
    echo "❌ AdminUnit controller not found<br>";
}

// Test 3: Check dashboard_clean.php content
echo "<br><h3>3. Check Dashboard Content</h3>";

if (file_exists('app/Views/admin_unit/dashboard_clean.php')) {
    $dashboardContent = file_get_contents('app/Views/admin_unit/dashboard_clean.php');

    // Check for key elements
    $checks = [
        'safe_json_decode' => 'Safe JSON decode function',
        'kategori' => 'Category loop',
        'dropdown' => 'Dropdown functionality',
        'jenis_sampah' => 'Waste type handling'
    ];

    foreach ($checks as $search => $description) {
        if (strpos($dashboardContent, $search) !== false) {
            echo "✅ $description found<br>";
        } else {
            echo "⚠️ $description not found<br>";
        }
    }

    echo "<br>File size: " . number_format(strlen($dashboardContent)) . " characters<br>";
} else {
    echo "❌ dashboard_clean.php not found<br>";
}

// Test 4: Test helper function availability
echo "<br><h3>4. Test Helper Function</h3>";

try {
    require_once 'app/Helpers/JsonHelper.php';

    if (function_exists('safe_json_decode')) {
        echo "✅ safe_json_decode function available<br>";

        // Test the function
        $testResult = safe_json_decode('{"test": "value"}', true);
        if (is_array($testResult)) {
            echo "✅ safe_json_decode function working<br>";
        } else {
            echo "❌ safe_json_decode function not working properly<br>";
        }
    } else {
        echo "❌ safe_json_decode function not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Error loading helper: " . $e->getMessage() . "<br>";
}

// Test 5: Database connection
echo "<br><h3>5. Test Database Connection</h3>";

try {
    $conn = new mysqli('localhost', 'root', '', 'uigm_polban');

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "✅ Database connected<br>";

    // Check critical tables
    $result = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin_unit'");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Admin unit users: {$row['count']}<br>";
    }

    $conn->close();
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

echo "<br><h3>🎯 SUMMARY</h3>";
echo "<p><strong>Jika semua test menunjukkan ✅, dashboard error sudah teratasi!</strong></p>";
echo "<p>Website siap diakses: <a href='http://localhost/eksperimen/'>http://localhost/eksperimen/</a></p>";
echo "<p>Login dengan: <strong>adminjte</strong> / <strong>adminjte123</strong></p>";

echo "<hr>";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f5f5f5;
    }

    h2,
    h3 {
        color: #333;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>