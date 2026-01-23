<?php
// Cek format password di database
$db = new mysqli('localhost', 'root', '', 'eksperimen');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "=== CEK FORMAT PASSWORD ===\n\n";

// Ambil sample user TPS
$result = $db->query("SELECT id, username, password, role FROM users WHERE role = 'pengelola_tps' LIMIT 3");

while ($row = $result->fetch_assoc()) {
    echo "Username: " . $row['username'] . "\n";
    echo "Role: " . $row['role'] . "\n";
    echo "Password: " . $row['password'] . "\n";
    echo "Length: " . strlen($row['password']) . "\n";
    
    // Cek apakah password di-hash
    if (strlen($row['password']) == 60 && substr($row['password'], 0, 4) == '$2y$') {
        echo "Format: HASHED (bcrypt) ✅\n";
    } else {
        echo "Format: PLAIN TEXT ❌\n";
    }
    echo "\n";
}

$db->close();
