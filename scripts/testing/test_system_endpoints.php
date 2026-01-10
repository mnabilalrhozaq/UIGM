<?php

/**
 * Script untuk testing endpoint sistem UIGM
 * Mengecek apakah semua routes berfungsi dengan baik
 */

echo "🧪 TESTING SISTEM ENDPOINTS UIGM\n";
echo "=================================\n\n";

// Base URL - sesuaikan dengan server Anda
$baseUrl = 'http://localhost:8080';

// Function untuk test endpoint
function testEndpoint($url, $description, $expectedStatus = 200) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'UIGM System Test');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "   ❌ $description - Error: $error\n";
        return false;
    }
    
    if ($httpCode == $expectedStatus) {
        echo "   ✅ $description - HTTP $httpCode\n";
        return true;
    } else {
        echo "   ⚠️  $description - HTTP $httpCode (expected $expectedStatus)\n";
        return false;
    }
}

// Test endpoints
echo "🌐 1. TESTING PUBLIC ENDPOINTS\n";
echo "------------------------------\n";

$publicEndpoints = [
    '/' => 'Homepage',
    '/auth/login' => 'Login Page',
    '/demo/login' => 'Demo Login',
    '/demo/admin-unit' => 'Demo Admin Unit',
    '/demo/info' => 'Demo Info'
];

$publicResults = [];
foreach ($publicEndpoints as $endpoint => $description) {
    $publicResults[$endpoint] = testEndpoint($baseUrl . $endpoint, $description);
}

echo "\n🔒 2. TESTING PROTECTED ENDPOINTS (Expected 302 Redirect)\n";
echo "---------------------------------------------------------\n";

$protectedEndpoints = [
    '/admin-unit' => 'Admin Unit Dashboard',
    '/admin-unit/dashboard' => 'Admin Unit Dashboard Alt',
    '/admin-pusat' => 'Admin Pusat Dashboard',
    '/admin-pusat/dashboard' => 'Admin Pusat Dashboard Alt',
    '/admin-pusat/monitoring' => 'Monitoring Unit',
    '/admin-pusat/review-queue' => 'Review Queue',
    '/admin-pusat/notifikasi' => 'Notifikasi',
    '/super-admin' => 'Super Admin Dashboard',
    '/super-admin/users' => 'User Management',
    '/super-admin/units' => 'Unit Management'
];

$protectedResults = [];
foreach ($protectedEndpoints as $endpoint => $description) {
    // Protected endpoints should redirect (302) to login
    $protectedResults[$endpoint] = testEndpoint($baseUrl . $endpoint, $description, 302);
}

echo "\n📊 3. TESTING API ENDPOINTS\n";
echo "---------------------------\n";

$apiEndpoints = [
    '/api/dashboard-stats' => 'Dashboard Statistics',
    '/api/notifications' => 'Notifications API',
    '/api/jenis-sampah/struktur' => 'Jenis Sampah Structure'
];

$apiResults = [];
foreach ($apiEndpoints as $endpoint => $description) {
    // API endpoints might return 302 (redirect) or 401 (unauthorized)
    $result = testEndpoint($baseUrl . $endpoint, $description, 302);
    if (!$result) {
        // Try with 401 status
        $result = testEndpoint($baseUrl . $endpoint, $description . ' (Auth Required)', 401);
    }
    $apiResults[$endpoint] = $result;
}

echo "\n📄 4. TESTING REPORT ENDPOINTS\n";
echo "------------------------------\n";

$reportEndpoints = [
    '/report' => 'Report Dashboard'
];

$reportResults = [];
foreach ($reportEndpoints as $endpoint => $description) {
    $reportResults[$endpoint] = testEndpoint($baseUrl . $endpoint, $description, 302);
}

echo "\n🧪 5. TESTING SPECIFIC FUNCTIONALITY\n";
echo "------------------------------------\n";

// Test dengan session/cookie jika diperlukan
echo "   ℹ️  Testing dengan authentication memerlukan login session\n";
echo "   ℹ️  Semua protected endpoints mengarah ke login (302) - NORMAL\n";

// Test database connection melalui endpoint
$testDbUrl = $baseUrl . '/debug/check-database';
echo "   🔍 Testing database connection...\n";
testEndpoint($testDbUrl, 'Database Connection Test');

echo "\n📈 6. RINGKASAN HASIL TEST\n";
echo "-------------------------\n";

$totalPublic = count($publicEndpoints);
$successPublic = count(array_filter($publicResults));

$totalProtected = count($protectedEndpoints);
$successProtected = count(array_filter($protectedResults));

$totalApi = count($apiEndpoints);
$successApi = count(array_filter($apiResults));

$totalReport = count($reportEndpoints);
$successReport = count(array_filter($reportResults));

echo "   📊 Public Endpoints: $successPublic/$totalPublic\n";
echo "   🔒 Protected Endpoints: $successProtected/$totalProtected\n";
echo "   📡 API Endpoints: $successApi/$totalApi\n";
echo "   📄 Report Endpoints: $successReport/$totalReport\n";

$totalEndpoints = $totalPublic + $totalProtected + $totalApi + $totalReport;
$totalSuccess = $successPublic + $successProtected + $successApi + $successReport;
$successRate = round(($totalSuccess / $totalEndpoints) * 100, 1);

echo "\n   🎯 Overall Success Rate: $totalSuccess/$totalEndpoints ($successRate%)\n";

if ($successRate >= 90) {
    echo "   🎉 STATUS: SISTEM BERJALAN DENGAN BAIK!\n";
} elseif ($successRate >= 70) {
    echo "   ⚠️  STATUS: SISTEM BERJALAN DENGAN BEBERAPA MASALAH\n";
} else {
    echo "   ❌ STATUS: SISTEM MEMILIKI MASALAH SERIUS\n";
}

echo "\n💡 7. REKOMENDASI\n";
echo "----------------\n";

if ($successPublic < $totalPublic) {
    echo "   • Periksa routes untuk public endpoints yang gagal\n";
}

if ($successProtected < $totalProtected) {
    echo "   • Periksa authentication filter untuk protected endpoints\n";
}

echo "   • Untuk testing lengkap dengan authentication, gunakan browser atau Postman\n";
echo "   • Pastikan server development berjalan: php spark serve\n";
echo "   • Akses login page: $baseUrl/auth/login\n";

echo "\n✅ Testing endpoints selesai!\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";