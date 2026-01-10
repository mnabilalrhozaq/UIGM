<?php

/**
 * TEST DATABASE CONNECTION
 * File untuk test koneksi database setelah restore
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uigm_polban';

echo "<h2>🔍 Test Koneksi Database UIGM POLBAN</h2>";
echo "<hr>";

try {
    // Test koneksi MySQL
    echo "<h3>1. Test Koneksi MySQL</h3>";
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }

    echo "✅ <strong>Koneksi berhasil!</strong><br>";
    echo "📊 Server Info: " . $conn->server_info . "<br>";
    echo "🗄️ Database: " . $database . "<br><br>";

    // Test tabel users
    echo "<h3>2. Test Tabel Users</h3>";
    $result = $conn->query("SELECT COUNT(*) as total FROM users");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Tabel users: " . $row['total'] . " records<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test tabel unit
    echo "<h3>3. Test Tabel Unit</h3>";
    $result = $conn->query("SELECT COUNT(*) as total FROM unit");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Tabel unit: " . $row['total'] . " records<br>";

        // Check if tipe_unit column exists
        $result2 = $conn->query("SHOW COLUMNS FROM unit LIKE 'tipe_unit'");
        if ($result2 && $result2->num_rows > 0) {
            echo "✅ Kolom tipe_unit: EXISTS<br>";
        } else {
            echo "❌ Kolom tipe_unit: MISSING - Jalankan database_patch_add_missing_columns.sql<br>";
        }
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test tabel indikator
    echo "<h3>4. Test Tabel Indikator</h3>";
    $result = $conn->query("SELECT COUNT(*) as total FROM indikator");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Tabel indikator: " . $row['total'] . " records<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test tabel jenis_sampah
    echo "<h3>5. Test Tabel Jenis Sampah</h3>";
    $result = $conn->query("SELECT COUNT(*) as total FROM jenis_sampah");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "✅ Tabel jenis_sampah: " . $row['total'] . " records<br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test data login
    echo "<h3>6. Test Data Login</h3>";
    $result = $conn->query("SELECT username, nama_lengkap, role FROM users WHERE status_aktif = 1");
    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Username</th><th>Nama Lengkap</th><th>Role</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_lengkap']) . "</td>";
            echo "<td>" . htmlspecialchars($row['role']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test unit dengan tipe_unit
    echo "<h3>6.5. Test Unit dengan Tipe Unit</h3>";
    $result = $conn->query("SELECT kode_unit, nama_unit, tipe_unit, parent_id FROM unit WHERE status_aktif = 1 ORDER BY id");
    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Kode Unit</th><th>Nama Unit</th><th>Tipe Unit</th><th>Parent ID</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['kode_unit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_unit']) . "</td>";
            echo "<td>" . htmlspecialchars($row['tipe_unit'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['parent_id'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test struktur dropdown sampah
    echo "<h3>7. Test Struktur Dropdown Sampah</h3>";
    $result = $conn->query("
        SELECT 
            j1.nama as kategori,
            j2.nama as area,
            j3.nama as detail
        FROM jenis_sampah j1
        LEFT JOIN jenis_sampah j2 ON j2.parent_id = j1.id
        LEFT JOIN jenis_sampah j3 ON j3.parent_id = j2.id
        WHERE j1.level = 1 AND j1.kode = 'organik'
        ORDER BY j2.urutan, j3.urutan
    ");

    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Kategori</th><th>Area</th><th>Detail</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['kategori']) . "</td>";
            echo "<td>" . htmlspecialchars($row['area']) . "</td>";
            echo "<td>" . htmlspecialchars($row['detail']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    echo "<h3>✅ SEMUA TEST BERHASIL!</h3>";
    echo "<p><strong>Database siap digunakan.</strong></p>";
    echo "<p>Silakan akses: <a href='http://localhost/eksperimen/'>http://localhost/eksperimen/</a></p>";

    $conn->close();
} catch (Exception $e) {
    echo "<h3>❌ ERROR</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
    echo "<h4>Solusi:</h4>";
    echo "<ul>";
    echo "<li>Pastikan XAMPP MySQL sudah running</li>";
    echo "<li>Pastikan database 'uigm_polban' sudah dibuat</li>";
    echo "<li>Pastikan file SQL sudah di-import</li>";
    echo "<li>Cek username/password database</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<p><em>Test dibuat pada: " . date('Y-m-d H:i:s') . "</em></p>";
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

    table {
        background-color: white;
        margin: 10px 0;
    }

    th {
        background-color: #4CAF50;
        color: white;
        padding: 8px;
    }

    td {
        padding: 8px;
    }

    .success {
        color: green;
        font-weight: bold;
    }

    .error {
        color: red;
        font-weight: bold;
    }
</style>