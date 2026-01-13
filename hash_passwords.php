<?php
/**
 * Script untuk hash password yang masih plain text di database
 * Jalankan sekali saja setelah import database
 * 
 * Cara menjalankan:
 * php hash_passwords.php
 */

require 'vendor/autoload.php';

// Load CodeIgniter
$pathsConfig = new Config\Paths();
$bootstrap = rtrim(realpath(ROOTPATH . 'system/bootstrap.php') ?: ROOTPATH . 'system/bootstrap.php', '\\/ ');
require $bootstrap;

$app = Config\Services::codeigniter();
$app->initialize();

// Connect to database
$db = \Config\Database::connect();

echo "=== Hash Password Script ===\n\n";

// Get all users
$query = $db->query("SELECT id, username, password FROM users");
$users = $query->getResultArray();

$updated = 0;
$skipped = 0;

foreach ($users as $user) {
    // Check if password is already hashed (bcrypt starts with $2y$)
    if (substr($user['password'], 0, 4) === '$2y$') {
        echo "✓ User '{$user['username']}' - Password sudah di-hash, skip\n";
        $skipped++;
        continue;
    }
    
    // Hash the plain text password
    $plainPassword = $user['password'];
    $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
    
    // Update database
    $db->query("UPDATE users SET password = ? WHERE id = ?", [$hashedPassword, $user['id']]);
    
    echo "✓ User '{$user['username']}' - Password di-hash (plain: {$plainPassword})\n";
    $updated++;
}

echo "\n=== Selesai ===\n";
echo "Total updated: $updated\n";
echo "Total skipped: $skipped\n";
echo "\nCATATAN: Simpan password plain text di atas untuk login!\n";
