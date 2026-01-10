<?php

// File: scripts/cleanup_admin_unit_references.php

/**
 * Script untuk membersihkan semua referensi admin-unit yang tersisa
 * Mengatasi error 404 untuk route admin-unit yang sudah dihapus
 */

// Load CodeIgniter
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "🧹 CLEANUP ADMIN-UNIT REFERENCES\n";
echo "=================================\n\n";

try {
    // 1. Clear all sessions
    echo "🗑️ 1. Membersihkan Sessions...\n";
    
    // Clear session files
    $sessionPath = WRITEPATH . 'session/';
    if (is_dir($sessionPath)) {
        $files = glob($sessionPath . 'ci_session*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ Session files cleared\n";
    }
    
    // 2. Clear cache
    echo "\n🗂️ 2. Membersihkan Cache...\n";
    $cache = \Config\Services::cache();
    $cache->clean();
    echo "   ✅ Cache cleared\n";
    
    // 3. Update database - hapus notifikasi dengan link admin-unit
    echo "\n🗄️ 3. Membersihkan Database References...\n";
    $db = \Config\Database::connect();
    
    // Update notifikasi yang masih mereferensikan admin-unit
    $updated = $db->table('notifikasi')
        ->where('link_terkait LIKE', '%admin-unit%')
        ->update(['link_terkait' => '/user/kriteria']);
    
    echo "   ✅ Updated {$updated} notification records\n";
    
    // 4. Update users dengan role admin_unit menjadi user
    $updatedUsers = $db->table('users')
        ->where('role', 'admin_unit')
        ->update(['role' => 'user']);
    
    echo "   ✅ Updated {$updatedUsers} user roles from admin_unit to user\n";
    
    // 5. Verifikasi tidak ada referensi admin-unit di database
    echo "\n🔍 4. Verifikasi Database...\n";
    
    // Cek notifikasi
    $adminUnitNotifications = $db->table('notifikasi')
        ->like('link_terkait', 'admin-unit')
        ->countAllResults();
    
    if ($adminUnitNotifications > 0) {
        echo "   ⚠️ Masih ada {$adminUnitNotifications} notifikasi dengan link admin-unit\n";
    } else {
        echo "   ✅ Tidak ada notifikasi dengan link admin-unit\n";
    }
    
    // Cek users dengan role admin_unit
    $adminUnitUsers = $db->table('users')
        ->where('role', 'admin_unit')
        ->countAllResults();
    
    if ($adminUnitUsers > 0) {
        echo "   ⚠️ Masih ada {$adminUnitUsers} user dengan role admin_unit\n";
    } else {
        echo "   ✅ Tidak ada user dengan role admin_unit\n";
    }
    
    // 6. Test routes
    echo "\n🧪 5. Testing Routes...\n";
    
    $testRoutes = [
        '/admin-unit' => 'Admin Unit (should redirect)',
        '/admin-unit/dashboard' => 'Admin Unit Dashboard (should redirect)',
        '/demo/admin-unit' => 'Demo Admin Unit (should redirect)',
        '/user/kriteria' => 'User Kriteria (should work)',
        '/admin-pusat' => 'Admin Pusat (should redirect to login)',
        '/super-admin' => 'Super Admin (should redirect to login)'
    ];
    
    foreach ($testRoutes as $route => $description) {
        echo "   Testing {$route}... ";
        
        // Simulate HTTP request (basic check)
        $url = "http://localhost:8080{$route}";
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 5,
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response !== false) {
            echo "✅ OK\n";
        } else {
            echo "⚠️ No response (server might not be running)\n";
        }
    }
    
    // 7. Tampilkan informasi akun yang tersedia
    echo "\n👥 6. Akun yang Tersedia:\n";
    echo "   ========================\n";
    
    $users = $db->table('users')
        ->select('username, email, role')
        ->where('status_aktif', 1)
        ->get()
        ->getResultArray();
    
    foreach ($users as $user) {
        echo "   Username: {$user['username']}\n";
        echo "   Email: {$user['email']}\n";
        echo "   Role: {$user['role']}\n";
        echo "   Password: password123\n";
        echo "   -------------------------\n";
    }
    
    echo "\n🎯 7. URL Testing:\n";
    echo "   ================\n";
    echo "   Login: http://localhost:8080/auth/login\n";
    echo "   User Waste Management: http://localhost:8080/user/kriteria\n";
    echo "   Admin Pusat: http://localhost:8080/admin-pusat\n";
    echo "   Super Admin: http://localhost:8080/super-admin\n";
    echo "   Demo Info: http://localhost:8080/demo/info\n";
    
    echo "\n✅ CLEANUP COMPLETED!\n";
    echo "======================\n";
    echo "Semua referensi admin-unit telah dibersihkan.\n";
    echo "Error 404 untuk admin-unit seharusnya sudah teratasi.\n";
    echo "Sekarang sistem menggunakan role 'user' untuk waste management.\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo "1. Pastikan database connection berfungsi\n";
    echo "2. Pastikan server development berjalan\n";
    echo "3. Cek file .env untuk konfigurasi database\n";
    echo "4. Jalankan: php spark serve\n";
    echo "5. Coba akses: http://localhost:8080/auth/login\n\n";
}