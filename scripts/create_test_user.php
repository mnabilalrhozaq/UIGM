<?php

/**
 * Create Test User Script
 * Creates test users for login testing
 */

echo "🔧 CREATE TEST USER SCRIPT\n";
echo "==========================\n\n";

// Database configuration (adjust as needed)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eksperimen'; // Change this to your database name

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful\n\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Users table does not exist. Please create it first.\n";
        exit(1);
    }
    
    echo "✅ Users table exists\n\n";
    
    // Test users to create
    $testUsers = [
        [
            'username' => 'admin',
            'email' => 'admin@polban.ac.id',
            'password' => 'admin123',
            'nama_lengkap' => 'Administrator',
            'role' => 'admin_pusat',
            'unit_id' => 1,
            'status_aktif' => 1
        ],
        [
            'username' => 'user1',
            'email' => 'user1@polban.ac.id',
            'password' => 'user123',
            'nama_lengkap' => 'User Test 1',
            'role' => 'user',
            'unit_id' => 2,
            'status_aktif' => 1
        ],
        [
            'username' => 'tps1',
            'email' => 'tps1@polban.ac.id',
            'password' => 'tps123',
            'nama_lengkap' => 'TPS Manager 1',
            'role' => 'pengelola_tps',
            'unit_id' => 3,
            'status_aktif' => 1
        ]
    ];
    
    echo "🔧 Creating test users...\n\n";
    
    foreach ($testUsers as $user) {
        // Check if user already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$user['username'], $user['email']]);
        
        if ($stmt->rowCount() > 0) {
            echo "⚠️  User {$user['username']} already exists, skipping...\n";
            continue;
        }
        
        // Insert new user
        $sql = "INSERT INTO users (username, email, password, nama_lengkap, role, unit_id, status_aktif, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            $user['username'],
            $user['email'],
            $user['password'], // Plain text for now
            $user['nama_lengkap'],
            $user['role'],
            $user['unit_id'],
            $user['status_aktif']
        ]);
        
        if ($result) {
            echo "✅ Created user: {$user['username']} ({$user['role']})\n";
            echo "   📧 Email: {$user['email']}\n";
            echo "   🔑 Password: {$user['password']}\n\n";
        } else {
            echo "❌ Failed to create user: {$user['username']}\n\n";
        }
    }
    
    // Show current users
    echo "📋 Current users in database:\n";
    $stmt = $pdo->query("SELECT id, username, email, nama_lengkap, role, status_aktif FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        $status = $user['status_aktif'] ? 'Active' : 'Inactive';
        echo "   - ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Role: {$user['role']}, Status: {$status}\n";
    }
    
    echo "\n🎯 TEST CREDENTIALS:\n";
    echo "Admin Login:\n";
    echo "  Username: admin\n";
    echo "  Password: admin123\n\n";
    echo "User Login:\n";
    echo "  Username: user1\n";
    echo "  Password: user123\n\n";
    echo "TPS Login:\n";
    echo "  Username: tps1\n";
    echo "  Password: tps123\n\n";
    
    echo "✅ Test users created successfully!\n";
    echo "🔗 You can now test login at: http://localhost:8080/auth/login\n\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "\n💡 Please check:\n";
    echo "1. Database connection settings in this script\n";
    echo "2. Database exists and is accessible\n";
    echo "3. Users table exists\n\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "🎉 CREATE TEST USER SCRIPT COMPLETE!\n\n";