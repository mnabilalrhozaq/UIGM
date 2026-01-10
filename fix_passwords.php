<?php

echo "=== FIX USER PASSWORDS ===\n\n";

try {
    $pdo = new PDO("mysql:host=localhost;dbname=eksperimen;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✓ Connected to database\n\n";
    
    // Get users with plain text passwords
    $stmt = $pdo->query("SELECT id, username, password FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($users) . " users\n\n";
    
    $updated = 0;
    
    foreach ($users as $user) {
        // Check if password is already hashed (bcrypt starts with $2y$ and is 60+ chars)
        if (strlen($user['password']) >= 60 && strpos($user['password'], '$2y$') === 0) {
            echo "✓ User '{$user['username']}' already has hashed password\n";
            continue;
        }
        
        // Hash the plain text password
        $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
        
        // Update in database
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->execute([$hashedPassword, $user['id']]);
        
        echo "✓ Updated password for user '{$user['username']}'\n";
        echo "  Old (plain): {$user['password']}\n";
        echo "  New (hash): " . substr($hashedPassword, 0, 30) . "...\n\n";
        
        $updated++;
    }
    
    echo "\n=== SUMMARY ===\n";
    echo "Total users: " . count($users) . "\n";
    echo "Updated: {$updated}\n";
    echo "Already hashed: " . (count($users) - $updated) . "\n\n";
    
    if ($updated > 0) {
        echo "✓ All passwords have been hashed!\n";
        echo "\nYou can now login with:\n";
        echo "- admin / admin123\n";
        echo "- userjti / user123\n";
        echo "- pengelolatps / password123\n";
    }
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
}
