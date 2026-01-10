<?php

/**
 * Direct Login Test Script
 * Tests login functionality directly
 */

echo "🧪 DIRECT LOGIN TEST\n";
echo "====================\n\n";

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eksperimen';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connected\n\n";
    
    // Test credentials
    $testCredentials = [
        ['login' => 'admin', 'password' => 'admin123'],
        ['login' => 'admin@polban.ac.id', 'password' => 'admin123'],
        ['login' => 'superadmin', 'password' => 'admin123'],
        ['login' => 'userjti', 'password' => 'user123']
    ];
    
    foreach ($testCredentials as $cred) {
        echo "🔍 Testing login: {$cred['login']} / {$cred['password']}\n";
        
        // Simulate getUserForLogin method
        $stmt = $pdo->prepare("
            SELECT id, username, email, password, nama_lengkap, role, unit_id, status_aktif 
            FROM users 
            WHERE (email = ? OR username = ?) AND status_aktif = 1
        ");
        $stmt->execute([$cred['login'], $cred['login']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            echo "   ❌ User not found\n\n";
            continue;
        }
        
        echo "   ✅ User found: {$user['username']} (Role: {$user['role']})\n";
        echo "   🔐 Stored password: '{$user['password']}'\n";
        echo "   🔑 Input password: '{$cred['password']}'\n";
        
        // Test password verification
        $passwordMatch = ($cred['password'] === $user['password']);
        echo "   🧪 Password match: " . ($passwordMatch ? 'YES' : 'NO') . "\n";
        
        if ($passwordMatch) {
            echo "   ✅ LOGIN SUCCESS!\n";
        } else {
            echo "   ❌ LOGIN FAILED - Password mismatch\n";
        }
        echo "\n";
    }
    
    // Show actual passwords in database
    echo "📋 Actual passwords in database:\n";
    $stmt = $pdo->query("SELECT username, password FROM users WHERE status_aktif = 1");
    while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$user['username']}: '{$user['password']}'\n";
    }
    
    echo "\n💡 RECOMMENDATIONS:\n";
    echo "1. Try logging in with the exact passwords shown above\n";
    echo "2. Check if there are any hidden characters in passwords\n";
    echo "3. Verify the login form is sending data correctly\n";
    echo "4. Check application logs for detailed error messages\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🎉 DIRECT LOGIN TEST COMPLETE!\n\n";