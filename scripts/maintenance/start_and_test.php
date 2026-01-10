<?php

/**
 * Script untuk start server dan test sistem
 */

echo "🚀 STARTING UIGM SYSTEM SERVER & TESTING\n";
echo "========================================\n\n";

// Function untuk cek apakah server sudah berjalan
function isServerRunning($host = 'localhost', $port = 8080) {
    $connection = @fsockopen($host, $port, $errno, $errstr, 1);
    if ($connection) {
        fclose($connection);
        return true;
    }
    return false;
}

// Cek apakah server sudah berjalan
if (isServerRunning()) {
    echo "✅ Server sudah berjalan di http://localhost:8080\n\n";
} else {
    echo "🔄 Starting development server...\n";
    echo "Command: php spark serve --host=localhost --port=8080\n";
    echo "Server akan berjalan di: http://localhost:8080\n\n";
    
    // Informasi untuk user
    echo "📋 LANGKAH SELANJUTNYA:\n";
    echo "1. Buka terminal baru\n";
    echo "2. Jalankan: php spark serve --host=localhost --port=8080\n";
    echo "3. Biarkan server berjalan\n";
    echo "4. Jalankan: php test_system_endpoints.php\n\n";
    
    echo "🌐 AKSES SISTEM:\n";
    echo "• Homepage: http://localhost:8080\n";
    echo "• Login: http://localhost:8080/auth/login\n";
    echo "• Demo Admin Unit: http://localhost:8080/demo/admin-unit\n";
    echo "• Demo Login Info: http://localhost:8080/demo/info\n\n";
    
    echo "👥 AKUN DEFAULT:\n";
    echo "• Super Admin: superadmin / password123\n";
    echo "• Admin Pusat: adminpusat / password123\n";
    echo "• Admin Unit: adminjtik / password123 (Teknik Informatika)\n";
    echo "• Admin Unit: adminjte / password123 (Teknik Elektro)\n\n";
    
    exit(0);
}

// Jika server sudah berjalan, lakukan testing
echo "🧪 Running endpoint tests...\n\n";

// Include dan jalankan test
include 'test_system_endpoints.php';