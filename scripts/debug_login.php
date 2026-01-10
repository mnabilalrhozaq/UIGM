<?php

/**
 * Login Debug Script
 * Helps identify login issues
 */

require_once __DIR__ . '/../vendor/autoload.php';

echo "🔍 LOGIN DEBUG SCRIPT\n";
echo "=====================\n\n";

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

// Get database connection
$db = \Config\Database::connect();

echo "1. ✅ Checking Database Connection...\n";
try {
    $db->query('SELECT 1');
    echo "   ✅ Database connection successful\n";
} catch (Exception $e) {
    echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n2. ✅ Checking Users Table...\n";
try {
    // Check if users table exists
    $query = $db->query("SHOW TABLES LIKE 'users'");
    if ($query->getNumRows() > 0) {
        echo "   ✅ Users table exists\n";
        
        // Check table structure
        $structure = $db->query("DESCRIBE users")->getResultArray();
        echo "   📋 Table structure:\n";
        foreach ($structure as $column) {
            echo "      - {$column['Field']} ({$column['Type']})\n";
        }
        
        // Count users
        $userCount = $db->query("SELECT COUNT(*) as count FROM users")->getRow()->count;
        echo "   📊 Total users: {$userCount}\n";
        
        if ($userCount > 0) {
            // Show sample users (without passwords)
            $users = $db->query("SELECT id, username, email, nama_lengkap, role, status_aktif FROM users LIMIT 5")->getResultArray();
            echo "   👥 Sample users:\n";
            foreach ($users as $user) {
                $status = $user['status_aktif'] ? 'Active' : 'Inactive';
                echo "      - ID: {$user['id']}, Username: {$user['username']}, Email: {$user['email']}, Role: {$user['role']}, Status: {$status}\n";
            }
            
            // Check password format
            $passwordSample = $db->query("SELECT username, LENGTH(password) as pwd_length, LEFT(password, 10) as pwd_sample FROM users LIMIT 3")->getResultArray();
            echo "   🔐 Password format analysis:\n";
            foreach ($passwordSample as $pwd) {
                echo "      - {$pwd['username']}: Length={$pwd['pwd_length']}, Sample='{$pwd['pwd_sample']}...'\n";
            }
        }
        
    } else {
        echo "   ❌ Users table does not exist\n";
        
        // Check what tables exist
        $tables = $db->query("SHOW TABLES")->getResultArray();
        echo "   📋 Available tables:\n";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "      - {$tableName}\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error checking users table: " . $e->getMessage() . "\n";
}

echo "\n3. ✅ Testing UserModel Methods...\n";
try {
    $userModel = new \App\Models\UserModel();
    
    // Test getUserForLogin method
    echo "   🔍 Testing getUserForLogin method...\n";
    
    // Get first user for testing
    $firstUser = $db->query("SELECT username, email FROM users WHERE status_aktif = 1 LIMIT 1")->getRow();
    
    if ($firstUser) {
        echo "   📝 Testing with username: {$firstUser->username}\n";
        $userByUsername = $userModel->getUserForLogin($firstUser->username);
        
        if ($userByUsername) {
            echo "      ✅ getUserForLogin by username works\n";
            echo "      📊 Returned data: ID={$userByUsername['id']}, Role='{$userByUsername['role']}', Status={$userByUsername['status_aktif']}\n";
        } else {
            echo "      ❌ getUserForLogin by username failed\n";
        }
        
        if ($firstUser->email) {
            echo "   📧 Testing with email: {$firstUser->email}\n";
            $userByEmail = $userModel->getUserForLogin($firstUser->email);
            
            if ($userByEmail) {
                echo "      ✅ getUserForLogin by email works\n";
            } else {
                echo "      ❌ getUserForLogin by email failed\n";
            }
        }
    } else {
        echo "   ⚠️  No active users found for testing\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error testing UserModel: " . $e->getMessage() . "\n";
}

echo "\n4. ✅ Password Verification Analysis...\n";
try {
    // Check if passwords are hashed or plain text
    $passwordCheck = $db->query("SELECT username, password FROM users WHERE status_aktif = 1 LIMIT 3")->getResultArray();
    
    foreach ($passwordCheck as $user) {
        $password = $user['password'];
        $isHashed = false;
        
        // Check if it looks like a hash
        if (strlen($password) >= 60 && (strpos($password, '$2y$') === 0 || strpos($password, '$2a$') === 0)) {
            $isHashed = true;
            echo "   🔐 {$user['username']}: HASHED (bcrypt)\n";
        } elseif (strlen($password) === 32 && ctype_xdigit($password)) {
            echo "   🔐 {$user['username']}: HASHED (MD5)\n";
            $isHashed = true;
        } elseif (strlen($password) === 40 && ctype_xdigit($password)) {
            echo "   🔐 {$user['username']}: HASHED (SHA1)\n";
            $isHashed = true;
        } else {
            echo "   🔓 {$user['username']}: PLAIN TEXT (Length: " . strlen($password) . ")\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Error analyzing passwords: " . $e->getMessage() . "\n";
}

echo "\n5. ✅ Recommendations...\n";

// Get password info again for recommendations
try {
    $passwordInfo = $db->query("SELECT password FROM users WHERE status_aktif = 1 LIMIT 1")->getRow();
    
    if ($passwordInfo) {
        $samplePassword = $passwordInfo->password;
        
        if (strlen($samplePassword) >= 60 && strpos($samplePassword, '$2') === 0) {
            echo "   💡 Passwords appear to be hashed with bcrypt\n";
            echo "   🔧 Update UserModel->verifyPassword() to use password_verify()\n";
            echo "   📝 Current method uses plain text comparison\n";
        } elseif (strlen($samplePassword) < 20) {
            echo "   💡 Passwords appear to be plain text\n";
            echo "   ✅ Current UserModel->verifyPassword() should work\n";
            echo "   ⚠️  Consider hashing passwords for security\n";
        } else {
            echo "   💡 Passwords appear to be hashed with unknown method\n";
            echo "   🔧 Need to identify hash method and update verifyPassword()\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Error getting password info: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "🎯 DEBUG COMPLETE\n";
echo str_repeat("=", 60) . "\n";

echo "\n📋 SUMMARY:\n";
echo "1. Check if users table exists and has data\n";
echo "2. Verify getUserForLogin method works\n";
echo "3. Identify password format (plain text vs hashed)\n";
echo "4. Update verifyPassword method accordingly\n";

echo "\n💡 NEXT STEPS:\n";
echo "1. If passwords are hashed, update verifyPassword() method\n";
echo "2. If no users exist, create test users\n";
echo "3. Test login with correct credentials\n";

echo "\n🔧 QUICK FIX:\n";
echo "If passwords are bcrypt hashed, replace in UserModel:\n";
echo "return password_verify(\$password, \$storedPassword);\n";

echo "\n🎉 LOGIN DEBUG COMPLETE!\n\n";