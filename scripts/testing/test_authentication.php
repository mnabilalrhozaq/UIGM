<?php

/**
 * Script untuk testing authentication sistem UIGM
 * Mengecek apakah filter authentication berfungsi dengan benar
 */

echo "🔐 TESTING AUTHENTICATION SISTEM UIGM\n";
echo "=====================================\n\n";

$baseUrl = 'http://localhost:8080';

// Function untuk test endpoint dengan analisis response
function testAuthEndpoint($url, $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false); // Jangan follow redirect
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'UIGM Auth Test');
    curl_setopt($ch, CURLOPT_HEADER, true); // Include headers
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo "   ❌ $description - Error: $error\n";
        return false;
    }
    
    // Pisahkan header dan body
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    
    // Analisis response
    if ($httpCode == 302) {
        // Check redirect location
        if (strpos($headers, 'Location:') !== false) {
            preg_match('/Location:\s*(.+)/', $headers, $matches);
            $location = trim($matches[1] ?? '');
            if (strpos($location, '/auth/login') !== false) {
                echo "   ✅ $description - Redirect ke login (HTTP $httpCode)\n";
                return true;
            } else {
                echo "   ⚠️  $description - Redirect ke: $location (HTTP $httpCode)\n";
                return false;
            }
        }
    } elseif ($httpCode == 200) {
        // Check if response contains login form or error message
        if (strpos($body, 'login') !== false || 
            strpos($body, 'Login') !== false ||
            strpos($body, 'Silakan login') !== false ||
            strpos($body, 'authentication') !== false) {
            echo "   ✅ $description - Menampilkan halaman login (HTTP $httpCode)\n";
            return true;
        } else {
            echo "   ❌ $description - Akses tanpa authentication (HTTP $httpCode)\n";
            return false;
        }
    } else {
        echo "   ⚠️  $description - HTTP $httpCode\n";
        return false;
    }
    
    return false;
}

// Test login page (should be accessible)
echo "🌐 1. TESTING LOGIN PAGE\n";
echo "------------------------\n";
$loginTest = testAuthEndpoint($baseUrl . '/auth/login', 'Login Page');

echo "\n🔒 2. TESTING PROTECTED ENDPOINTS\n";
echo "---------------------------------\n";

$protectedEndpoints = [
    '/admin-unit' => 'Admin Unit Dashboard',
    '/admin-unit/dashboard' => 'Admin Unit Dashboard Alt',
    '/admin-pusat' => 'Admin Pusat Dashboard',
    '/admin-pusat/dashboard' => 'Admin Pusat Dashboard Alt',
    '/admin-pusat/monitoring' => 'Monitoring Unit',
    '/admin-pusat/review-queue' => 'Review Queue',
    '/admin-pusat/notifikasi' => 'Notifikasi',
    '/admin-pusat/data-penilaian' => 'Data Penilaian',
    '/admin-pusat/indikator-greenmetric' => 'Indikator GreenMetric',
    '/admin-pusat/riwayat-penilaian' => 'Riwayat Penilaian',
    '/admin-pusat/pengaturan' => 'Pengaturan',
    '/super-admin' => 'Super Admin Dashboard',
    '/super-admin/users' => 'User Management',
    '/super-admin/units' => 'Unit Management',
    '/super-admin/tahun-penilaian' => 'Tahun Penilaian Management'
];

$protectedResults = [];
foreach ($protectedEndpoints as $endpoint => $description) {
    $protectedResults[$endpoint] = testAuthEndpoint($baseUrl . $endpoint, $description);
}

echo "\n📊 3. TESTING API ENDPOINTS\n";
echo "---------------------------\n";

$apiEndpoints = [
    '/api/dashboard-stats' => 'Dashboard Statistics API',
    '/api/notifications' => 'Notifications API',
    '/api/unit-progress' => 'Unit Progress API',
    '/api/jenis-sampah/struktur' => 'Jenis Sampah Structure API'
];

$apiResults = [];
foreach ($apiEndpoints as $endpoint => $description) {
    $apiResults[$endpoint] = testAuthEndpoint($baseUrl . $endpoint, $description);
}

echo "\n📄 4. TESTING REPORT ENDPOINTS\n";
echo "------------------------------\n";

$reportEndpoints = [
    '/report' => 'Report Dashboard',
    '/report/export-csv' => 'Export CSV',
    '/report/generate-pdf' => 'Generate PDF'
];

$reportResults = [];
foreach ($reportEndpoints as $endpoint => $description) {
    $reportResults[$endpoint] = testAuthEndpoint($baseUrl . $endpoint, $description);
}

echo "\n🧪 5. TESTING WITH FAKE SESSION\n";
echo "-------------------------------\n";

// Test dengan cookie session palsu
function testWithFakeSession($url, $description) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'UIGM Auth Test');
    curl_setopt($ch, CURLOPT_COOKIE, 'ci_session=fake_session_data');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "   ℹ️  $description - HTTP $httpCode (dengan fake session)\n";
    return $httpCode;
}

testWithFakeSession($baseUrl . '/admin-unit', 'Admin Unit dengan fake session');

echo "\n📈 6. RINGKASAN HASIL TEST AUTHENTICATION\n";
echo "----------------------------------------\n";

$totalProtected = count($protectedEndpoints);
$successProtected = count(array_filter($protectedResults));

$totalApi = count($apiEndpoints);
$successApi = count(array_filter($apiResults));

$totalReport = count($reportEndpoints);
$successReport = count(array_filter($reportResults));

echo "   🔒 Protected Endpoints: $successProtected/$totalProtected\n";
echo "   📡 API Endpoints: $successApi/$totalApi\n";
echo "   📄 Report Endpoints: $successReport/$totalReport\n";

$totalEndpoints = $totalProtected + $totalApi + $totalReport;
$totalSuccess = $successProtected + $successApi + $successReport;
$successRate = $totalEndpoints > 0 ? round(($totalSuccess / $totalEndpoints) * 100, 1) : 0;

echo "\n   🎯 Authentication Success Rate: $totalSuccess/$totalEndpoints ($successRate%)\n";

if ($successRate >= 90) {
    echo "   🎉 STATUS: AUTHENTICATION BERFUNGSI DENGAN BAIK!\n";
} elseif ($successRate >= 70) {
    echo "   ⚠️  STATUS: AUTHENTICATION BERFUNGSI DENGAN BEBERAPA MASALAH\n";
} else {
    echo "   ❌ STATUS: AUTHENTICATION MEMILIKI MASALAH SERIUS\n";
}

echo "\n💡 7. REKOMENDASI AUTHENTICATION\n";
echo "-------------------------------\n";

if ($successRate < 90) {
    echo "   • Periksa konfigurasi AuthFilter di app/Filters/AuthFilter.php\n";
    echo "   • Pastikan filter 'auth' terdaftar di Routes.php untuk protected routes\n";
    echo "   • Cek session configuration di app/Config/App.php\n";
    echo "   • Verifikasi bahwa routes menggunakan ['filter' => 'auth']\n";
}

echo "   • Untuk testing manual, akses: $baseUrl/auth/login\n";
echo "   • Login dengan akun: superadmin / password123\n";
echo "   • Kemudian test protected endpoints melalui browser\n";

echo "\n✅ Testing authentication selesai!\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";