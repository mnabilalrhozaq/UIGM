<?php

/**
 * TEST DATA_INPUT FIX
 * File untuk test apakah masalah data_input sudah teratasi
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uigm_polban';

echo "<h2>🔍 Test Data Input Fix</h2>";
echo "<hr>";

try {
    // Test koneksi MySQL
    echo "<h3>1. Test Koneksi Database</h3>";
    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }

    echo "✅ <strong>Koneksi berhasil!</strong><br><br>";

    // Test struktur tabel review_kategori
    echo "<h3>2. Test Struktur Tabel review_kategori</h3>";
    $result = $conn->query("DESCRIBE review_kategori");
    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        while ($row = $result->fetch_assoc()) {
            $highlight = ($row['Field'] == 'data_input') ? 'style="background-color: #ffffcc;"' : '';
            echo "<tr $highlight>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test data_input values
    echo "<h3>3. Test Data Input Values</h3>";
    $result = $conn->query("
        SELECT 
            COUNT(*) as total_records,
            SUM(CASE WHEN data_input IS NULL THEN 1 ELSE 0 END) as null_count,
            SUM(CASE WHEN data_input = '{}' THEN 1 ELSE 0 END) as empty_json_count,
            SUM(CASE WHEN data_input IS NOT NULL AND data_input != '{}' THEN 1 ELSE 0 END) as has_data_count
        FROM review_kategori
    ");

    if ($result) {
        $stats = $result->fetch_assoc();
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Statistik</th><th>Jumlah</th></tr>";
        echo "<tr><td>Total Records</td><td>" . $stats['total_records'] . "</td></tr>";
        echo "<tr><td>NULL Values</td><td>" . $stats['null_count'] . "</td></tr>";
        echo "<tr><td>Empty JSON ({})</td><td>" . $stats['empty_json_count'] . "</td></tr>";
        echo "<tr><td>Has Data</td><td>" . $stats['has_data_count'] . "</td></tr>";
        echo "</table><br>";

        if ($stats['null_count'] > 0) {
            echo "<p style='color: orange;'>⚠️ <strong>Ada " . $stats['null_count'] . " record dengan data_input NULL</strong></p>";
            echo "<p>Jalankan query: <code>UPDATE review_kategori SET data_input = '{}' WHERE data_input IS NULL;</code></p>";
        } else {
            echo "<p style='color: green;'>✅ <strong>Tidak ada data_input NULL</strong></p>";
        }
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test sample data_input values
    echo "<h3>4. Sample Data Input Values</h3>";
    $result = $conn->query("
        SELECT id, pengiriman_id, indikator_id, data_input, status_review 
        FROM review_kategori 
        LIMIT 5
    ");

    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Pengiriman ID</th><th>Indikator ID</th><th>Data Input</th><th>Status</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pengiriman_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['indikator_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['data_input'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['status_review']) . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Error: " . $conn->error . "<br>";
    }

    // Test insert new record with empty JSON
    echo "<h3>5. Test Insert Record dengan Empty JSON</h3>";
    $testInsert = $conn->query("
        INSERT INTO review_kategori (pengiriman_id, indikator_id, data_input, status_review) 
        VALUES (1, 1, '{}', 'pending')
        ON DUPLICATE KEY UPDATE data_input = '{}'
    ");

    if ($testInsert) {
        echo "✅ <strong>Insert/Update berhasil dengan data_input = '{}'</strong><br>";
    } else {
        echo "❌ Insert Error: " . $conn->error . "<br>";
    }

    // Test JSON validation
    echo "<h3>6. Test JSON Validation</h3>";
    $testJson = '{"test": "value", "number": 123}';
    $testInsert2 = $conn->query("
        INSERT INTO review_kategori (pengiriman_id, indikator_id, data_input, status_review) 
        VALUES (1, 2, '$testJson', 'pending')
        ON DUPLICATE KEY UPDATE data_input = '$testJson'
    ");

    if ($testInsert2) {
        echo "✅ <strong>Insert/Update berhasil dengan JSON data</strong><br>";
        echo "JSON Test: <code>$testJson</code><br>";
    } else {
        echo "❌ JSON Insert Error: " . $conn->error . "<br>";
    }

    echo "<br><h3>✅ SEMUA TEST SELESAI!</h3>";
    echo "<p><strong>Jika tidak ada error di atas, masalah data_input sudah teratasi.</strong></p>";

    $conn->close();
} catch (Exception $e) {
    echo "<h3>❌ ERROR</h3>";
    echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
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

    code {
        background-color: #f0f0f0;
        padding: 2px 4px;
        border-radius: 3px;
        font-family: monospace;
    }
</style>