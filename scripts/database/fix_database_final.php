<?php

/**
 * FINAL DATABASE FIX
 * Script untuk memperbaiki semua masalah database
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uigm_polban';

echo "<h2>🔧 Final Database Fix</h2>";
echo "<hr>";

try {
    // Koneksi database
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }

    echo "<h3>1. ✅ Koneksi Database Berhasil</h3>";

    // Fix 1: Update NULL data_input values
    echo "<h3>2. 🔧 Fix NULL data_input Values</h3>";
    $result = $conn->query("UPDATE review_kategori SET data_input = '{}' WHERE data_input IS NULL");

    if ($result) {
        $affected = $conn->affected_rows;
        echo "✅ Updated $affected records with NULL data_input<br>";
    } else {
        echo "❌ Error updating data_input: " . $conn->error . "<br>";
    }

    // Fix 2: Check tipe_unit column
    echo "<h3>3. 🔧 Check tipe_unit Column</h3>";
    $result = $conn->query("SHOW COLUMNS FROM unit LIKE 'tipe_unit'");

    if ($result && $result->num_rows > 0) {
        echo "✅ Column tipe_unit exists<br>";
    } else {
        echo "⚠️ Column tipe_unit missing, adding it...<br>";

        $alterResult = $conn->query("ALTER TABLE unit ADD COLUMN tipe_unit ENUM('fakultas','jurusan','unit_kerja','lembaga') NOT NULL DEFAULT 'fakultas' AFTER nama_unit");

        if ($alterResult) {
            echo "✅ Column tipe_unit added successfully<br>";

            // Update existing data
            $conn->query("UPDATE unit SET tipe_unit = 'lembaga' WHERE kode_unit = 'POLBAN'");
            $conn->query("UPDATE unit SET tipe_unit = 'jurusan' WHERE kode_unit IN ('JTE', 'JTM', 'JTS', 'JTIK')");
            echo "✅ Updated existing unit data<br>";
        } else {
            echo "❌ Error adding tipe_unit column: " . $conn->error . "<br>";
        }
    }

    // Fix 3: Verify data integrity
    echo "<h3>4. 🔍 Verify Data Integrity</h3>";

    // Check review_kategori
    $result = $conn->query("SELECT COUNT(*) as total, SUM(CASE WHEN data_input IS NULL THEN 1 ELSE 0 END) as null_count FROM review_kategori");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📊 review_kategori: {$row['total']} total, {$row['null_count']} NULL data_input<br>";
    }

    // Check unit
    $result = $conn->query("SELECT COUNT(*) as total FROM unit WHERE tipe_unit IS NOT NULL");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📊 unit: {$row['total']} records with tipe_unit<br>";
    }

    // Check users
    $result = $conn->query("SELECT COUNT(*) as total FROM users WHERE status_aktif = 1");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "📊 users: {$row['total']} active users<br>";
    }

    echo "<br><h3>🎉 DATABASE FIX COMPLETED!</h3>";
    echo "<p><strong>Semua masalah database sudah diperbaiki.</strong></p>";
    echo "<p>Silakan test website: <a href='http://localhost/eksperimen/'>http://localhost/eksperimen/</a></p>";

    $conn->close();
} catch (Exception $e) {
    echo "<h3>❌ ERROR</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><em>Fix completed at: " . date('Y-m-d H:i:s') . "</em></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f5f5f5;
    }

    h2,
    h3 {
        color: #333;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>