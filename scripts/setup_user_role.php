<?php

// File: scripts/setup_user_role.php

/**
 * Script untuk setup Role User dan fitur Waste Management
 * Menjalankan migration dan seeder untuk fitur user
 */

// Load CodeIgniter
require_once __DIR__ . '/../vendor/autoload.php';

// Bootstrap CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

echo "🚀 SETUP ROLE USER - WASTE MANAGEMENT UIGM\n";
echo "==========================================\n\n";

try {
    // 1. Jalankan Migration untuk tabel kriteria_uigm
    echo "📊 1. Menjalankan Migration untuk tabel kriteria_uigm...\n";
    $migrate = \Config\Services::migrations();
    
    // Jalankan migration AddUserFields terlebih dahulu
    echo "   - Menambahkan field baru ke tabel users...\n";
    $result = $migrate->setNamespace(null)->latest();
    
    if ($result === false) {
        $errors = $migrate->getCliMessages();
        if (!empty($errors)) {
            echo "   ❌ Error: " . implode(', ', $errors) . "\n";
        } else {
            echo "   ✅ Migration sudah up-to-date\n";
        }
    } else {
        echo "   ✅ Migration berhasil dijalankan\n";
    }
    
    // 2. Jalankan Seeder untuk User
    echo "\n👥 2. Menjalankan Seeder untuk User...\n";
    $seeder = \Config\Database::seeder();
    $seeder->call('UserSeeder');
    echo "   ✅ User seeder berhasil dijalankan\n";
    
    // 3. Jalankan Seeder untuk Kriteria
    echo "\n📋 3. Menjalankan Seeder untuk Sample Data Kriteria...\n";
    $seeder->call('KriteriaSeeder');
    echo "   ✅ Kriteria seeder berhasil dijalankan\n";
    
    // 4. Verifikasi Setup
    echo "\n🔍 4. Verifikasi Setup...\n";
    
    // Cek tabel kriteria_uigm
    $db = \Config\Database::connect();
    
    if ($db->tableExists('kriteria_uigm')) {
        $count = $db->table('kriteria_uigm')->countAll();
        echo "   ✅ Tabel kriteria_uigm: {$count} records\n";
    } else {
        echo "   ❌ Tabel kriteria_uigm tidak ditemukan\n";
    }
    
    // Cek user dengan role 'user'
    $userCount = $db->table('users')->where('role', 'user')->countAllResults();
    echo "   ✅ User dengan role 'user': {$userCount} users\n";
    
    // 5. Tampilkan informasi akun
    echo "\n👤 5. Informasi Akun Testing:\n";
    echo "   ================================\n";
    
    $users = $db->table('users')->where('role', 'user')->get()->getResultArray();
    
    foreach ($users as $user) {
        echo "   Username: {$user['username']}\n";
        echo "   Password: password123\n";
        echo "   Email: {$user['email']}\n";
        echo "   Role: {$user['role']}\n";
        echo "   Unit/Prodi: " . ($user['unit_prodi'] ?? 'N/A') . "\n";
        echo "   --------------------------------\n";
    }
    
    echo "\n🎯 6. URL Testing:\n";
    echo "   Login Page: http://localhost:8080/auth/login\n";
    echo "   User Kriteria: http://localhost:8080/user/kriteria\n";
    echo "   (Login dengan salah satu akun user di atas)\n";
    
    echo "\n✅ SETUP BERHASIL COMPLETED!\n";
    echo "==========================================\n";
    echo "Fitur Role User untuk Waste Management siap digunakan.\n";
    echo "Silakan login menggunakan akun user dan akses halaman kriteria.\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    
    echo "🔧 TROUBLESHOOTING:\n";
    echo "1. Pastikan database sudah dibuat\n";
    echo "2. Periksa konfigurasi database di .env\n";
    echo "3. Pastikan tabel users sudah ada\n";
    echo "4. Jalankan: php spark migrate\n";
    echo "5. Coba jalankan script ini lagi\n\n";
}