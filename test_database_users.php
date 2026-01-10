<?php

require_once 'vendor/autoload.php';

// Bootstrap CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SYSTEMPATH', __DIR__ . '/vendor/codeigniter4/framework/system/');
define('APPPATH', __DIR__ . '/app/');
define('ROOTPATH', __DIR__ . '/');
define('WRITEPATH', __DIR__ . '/writable/');

// Load environment
$env = new \CodeIgniter\Config\DotEnv(ROOTPATH);
$env->load();

echo "=== DATABASE & USER TEST ===\n\n";

try {
    // Test database connection
    $db = \Config\Database::connect();
    echo "✓ Database connection successful\n";
    echo "  Database: " . $db->database . "\n\n";
    
    // Check if users table exists
    if (!$db->tableExists('users')) {
        echo "✗ Table 'users' does not exist!\n";
        exit(1);
    }
    echo "✓ Table 'users' exists\n\n";
    
    // Get all users
    $query = $db->query("SELECT id, username, email, nama_lengkap, role, unit_id, status_aktif, password FROM users ORDER BY id");
    $users = $query->getResultArray();
    
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
        echo "Password Hash: " . substr($user['password'], 0, 20) . "...\n";
        echo "Password Length: " . strlen($user['password']) . " chars\n";
        
        // Detect password type
        if (strlen($user['password']) >= 60 && (strpos($user['password'], '$2y$') === 0 || strpos($user['password'], '$2a$') === 0)) {
            echo "Password Type: Bcrypt (Hashed)\n";
        } elseif (strlen($user['password']) === 32 && ctype_xdigit($user['password'])) {
            echo "Password Type: MD5 (Hashed)\n";
        } elseif (strlen($user['password']) === 40 && ctype_xdigit($user['password'])) {
            echo "Password Type: SHA1 (Hashed)\n";
        } else {
            echo "Password Type: Plain Text or Unknown\n";
        }
        
        echo "---\n\n";
    }
    
    // Test password verification for each user
    echo "=== PASSWORD VERIFICATION TEST ===\n\n";
    
    $testPasswords = [
        'admin' => 'admin123',
        'userjti' => 'user123',
        'pengelolatps' => 'password123'
    ];
    
    foreach ($testPasswords as $username => $testPassword) {
        $query = $db->query("SELECT * FROM users WHERE username = ?", [$username]);
        $user = $query->getRowArray();
        
        if ($user) {
            echo "Testing: {$username} / {$testPassword}\n";
            
            // Test bcrypt
            if (password_verify($testPassword, $user['password'])) {
                echo "✓ Bcrypt verification: SUCCESS\n";
            } else {
                echo "✗ Bcrypt verification: FAILED\n";
            }
            
            // Test MD5
            if (md5($testPassword) === $user['password']) {
                echo "✓ MD5 verification: SUCCESS\n";
            } else {
                echo "✗ MD5 verification: FAILED\n";
            }
            
            // Test SHA1
            if (sha1($testPassword) === $user['password']) {
                echo "✓ SHA1 verification: SUCCESS\n";
            } else {
                echo "✗ SHA1 verification: FAILED\n";
            }
            
            // Test plain text
            if ($testPassword === $user['password']) {
                echo "✓ Plain text verification: SUCCESS\n";
            } else {
                echo "✗ Plain text verification: FAILED\n";
            }
            
            echo "---\n\n";
        } else {
            echo "✗ User '{$username}' not found in database\n\n";
        }
    }
    
    // Recommendations
    echo "=== RECOMMENDATIONS ===\n\n";
    
    $plainTextUsers = [];
    $inactiveUsers = [];
    $noRoleUsers = [];
    
    foreach ($users as $user) {
        // Check for plain text passwords
        if (strlen($user['password']) < 32) {
            $plainTextUsers[] = $user['username'];
        }
        
        // Check for inactive users
        if (!$user['status_aktif']) {
            $inactiveUsers[] = $user['username'];
        }
        
        // Check for users without role
        if (empty($user['role'])) {
            $noRoleUsers[] = $user['username'];
        }
    }
    
    if (!empty($plainTextUsers)) {
        echo "⚠ Users with plain text passwords: " . implode(', ', $plainTextUsers) . "\n";
        echo "  Recommendation: Hash these passwords using password_hash()\n\n";
    }
    
    if (!empty($inactiveUsers)) {
        echo "⚠ Inactive users: " . implode(', ', $inactiveUsers) . "\n";
        echo "  Recommendation: Activate these users or remove them\n\n";
    }
    
    if (!empty($noRoleUsers)) {
        echo "⚠ Users without role: " . implode(', ', $noRoleUsers) . "\n";
        echo "  Recommendation: Assign roles to these users\n\n";
    }
    
    if (empty($plainTextUsers) && empty($inactiveUsers) && empty($noRoleUsers)) {
        echo "✓ All users are properly configured!\n\n";
    }
    
    echo "=== TEST COMPLETE ===\n";
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
