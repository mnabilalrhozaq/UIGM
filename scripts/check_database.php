<?php

/**
 * Simple Database Check Script
 * Checks database and users without CodeIgniter dependencies
 */

echo "🔍 DATABASE CHECK SCRIPT\n";
echo "========================\n\n";

// Database configuration (adjust as needed)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eksperimen'; // Change this to your database name

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful\n";
    echo "📊 Database: $database\n\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "❌ Users table does not exist\n";
        
        // Show available tables
        echo "📋 Available tables:\n";
        $stmt = $pdo->query("SHOW TABLES");
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            echo "   - {$row[0]}\n";
        }
        exit(1);
    }
    
    echo "✅ Users table exists\n\n";
    
    // Check table structure
    echo "📋 Users table structure:\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   - {$row['Field']} ({$row['Type']}) " . ($row['Null'] == 'YES' ? 'NULL' : 'NOT NULL') . "\n";
    }
    
    // Count users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n📊 Total users: $userCount\n\n";
    
    if ($userCount > 0) {
        // Show users
        echo "👥 Current users:\n";
        $stmt = $pdo->query("SELECT id, username, email, nama_lengkap, role, status_aktif, LENGTH(password) as pwd_length FROM users ORDER BY id");
        
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $user['status_aktif'] ? 'Active' : 'Inactive';
            echo "   - ID: {$user['id']}\n";
            echo "     Username: {$user['username']}\n";
            echo "     Email: {$user['email']}\n";
            echo "     Name: {$user['nama_lengkap']}\n";
            echo "     Role: {$user['role']}\n";
            echo "     Status: {$status}\n";
            echo "     Password Length: {$user['pwd_length']}\n\n";
        }
        
        // Analyze password format
        echo "🔐 Password format analysis:\n";
        $stmt = $pdo->query("SELECT username, password FROM users WHERE status_aktif = 1 LIMIT 3");
        
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $password = $user['password'];
            $length = strlen($password);
            
            if ($length >= 60 && (strpos($password, '$2y$') === 0 || strpos($password, '$2a$') === 0)) {
                echo "   - {$user['username']}: BCRYPT HASH (Length: $length)\n";
            } elseif ($length === 32 && ctype_xdigit($password)) {
                echo "   - {$user['username']}: MD5 HASH (Length: $length)\n";
            } elseif ($length === 40 && ctype_xdigit($password)) {
                echo "   - {$user['username']}: SHA1 HASH (Length: $length)\n";
            } else {
                echo "   - {$user['username']}: PLAIN TEXT (Length: $length)\n";
            }
        }
        
    } else {
        echo "⚠️  No users found in database\n";
        echo "💡 Run create_test_user.php to create test users\n";
    }
    
    echo "\n🎯 LOGIN TEST RECOMMENDATIONS:\n";
    
    if ($userCount == 0) {
        echo "1. ❌ No users exist - create test users first\n";
        echo "2. 🔧 Run: php scripts/create_test_user.php\n";
    } else {
        echo "1. ✅ Users exist in database\n";
        echo "2. 🔧 Check password format and update UserModel accordingly\n";
        echo "3. 🧪 Test login with existing credentials\n";
        echo "4. 📝 Check application logs for detailed error messages\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    
    echo "💡 Common issues:\n";
    echo "1. Database doesn't exist\n";
    echo "2. Wrong database credentials\n";
    echo "3. MySQL server not running\n";
    echo "4. Wrong host/port configuration\n\n";
    
    echo "🔧 To fix:\n";
    echo "1. Check database name in this script\n";
    echo "2. Verify MySQL is running\n";
    echo "3. Check database credentials\n";
    echo "4. Create database if it doesn't exist\n";
}

echo "\n🎉 DATABASE CHECK COMPLETE!\n\n";