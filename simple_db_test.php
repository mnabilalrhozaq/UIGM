<?php

echo "=== SIMPLE DATABASE TEST ===\n\n";

// Read database config from .env
$envFile = file_get_contents('.env');
preg_match('/database\.default\.hostname\s*=\s*(.+)/', $envFile, $host);
preg_match('/database\.default\.database\s*=\s*(.+)/', $envFile, $database);
preg_match('/database\.default\.username\s*=\s*(.+)/', $envFile, $username);
preg_match('/database\.default\.password\s*=\s*(.+)/', $envFile, $password);

$dbHost = trim($host[1] ?? 'localhost');
$dbName = trim($database[1] ?? '');
$dbUser = trim($username[1] ?? 'root');
$dbPass = trim($password[1] ?? '');

// Handle empty password
if (empty($dbPass) || $dbPass === '') {
    $dbPass = '';
}

echo "Database Config:\n";
echo "  Host: {$dbHost}\n";
echo "  Database: {$dbName}\n";
echo "  Username: {$dbUser}\n";
echo "  Password: " . (empty($dbPass) ? '(empty)' : '***') . "\n\n";

try {
    // Connect to database - handle empty password
    if (empty($dbPass)) {
        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser);
    } else {
        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Database connection successful!\n\n";
    
    // Get all users
    $stmt = $pdo->query("SELECT id, username, email, nama_lengkap, role, unit_id, status_aktif, password FROM users ORDER BY id");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "=== USERS IN DATABASE ===\n";
    echo "Total users: " . count($users) . "\n\n";
    
    foreach ($users as $user) {
        echo "ID: " . $user['id'] . "\n";
        echo "Username: " . $user['username'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Nama: " . $user['nama_lengkap'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Unit ID: " . $user['unit_id'] . "\n";
        echo "Status: " . ($user['status_aktif'] ? 'Active' : 'Inactive') . "\n";
        echo "Password: " . substr($user['password'], 0, 30) . "...\n";
        echo "Password Length: " . strlen($user['password']) . " chars\n";
        
        // Detect password type
        if (strlen($user['password']) >= 60 && (strpos($user['password'], '$2y$') === 0 || strpos($user['password'], '$2a$') === 0)) {
            echo "Password Type: Bcrypt ✓\n";
        } elseif (strlen($user['password']) === 32 && ctype_xdigit($user['password'])) {
            echo "Password Type: MD5\n";
        } elseif (strlen($user['password']) === 40 && ctype_xdigit($user['password'])) {
            echo "Password Type: SHA1\n";
        } else {
            echo "Password Type: Plain Text or Unknown ⚠\n";
        }
        
        echo "---\n\n";
    }
    
    // Test login credentials
    echo "=== PASSWORD VERIFICATION TEST ===\n\n";
    
    $testCredentials = [
        ['username' => 'admin', 'password' => 'admin123'],
        ['username' => 'userjti', 'password' => 'user123'],
        ['username' => 'pengelolatps', 'password' => 'password123']
    ];
    
    foreach ($testCredentials as $cred) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$cred['username']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "Testing: {$cred['username']} / {$cred['password']}\n";
            
            $verified = false;
            
            // Test bcrypt
            if (password_verify($cred['password'], $user['password'])) {
                echo "✓ Bcrypt verification: SUCCESS\n";
                $verified = true;
            }
            
            // Test MD5
            if (md5($cred['password']) === $user['password']) {
                echo "✓ MD5 verification: SUCCESS\n";
                $verified = true;
            }
            
            // Test SHA1
            if (sha1($cred['password']) === $user['password']) {
                echo "✓ SHA1 verification: SUCCESS\n";
                $verified = true;
            }
            
            // Test plain text
            if ($cred['password'] === $user['password']) {
                echo "✓ Plain text verification: SUCCESS\n";
                $verified = true;
            }
            
            if (!$verified) {
                echo "✗ ALL VERIFICATIONS FAILED!\n";
                echo "  Stored password: " . $user['password'] . "\n";
                echo "  Test password: " . $cred['password'] . "\n";
                echo "  Bcrypt hash: " . password_hash($cred['password'], PASSWORD_DEFAULT) . "\n";
                echo "  MD5 hash: " . md5($cred['password']) . "\n";
            }
            
            echo "---\n\n";
        } else {
            echo "✗ User '{$cred['username']}' not found\n\n";
        }
    }
    
    // Fix recommendations
    echo "=== FIX RECOMMENDATIONS ===\n\n";
    
    $needsFix = false;
    
    foreach ($users as $user) {
        // Check if password needs rehashing
        if (strlen($user['password']) < 60 || strpos($user['password'], '$2y$') !== 0) {
            echo "⚠ User '{$user['username']}' needs password rehash\n";
            $needsFix = true;
        }
        
        // Check if inactive
        if (!$user['status_aktif']) {
            echo "⚠ User '{$user['username']}' is inactive\n";
            $needsFix = true;
        }
        
        // Check if no role
        if (empty($user['role'])) {
            echo "⚠ User '{$user['username']}' has no role\n";
            $needsFix = true;
        }
    }
    
    if (!$needsFix) {
        echo "✓ All users are properly configured!\n";
    }
    
    echo "\n=== TEST COMPLETE ===\n";
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
