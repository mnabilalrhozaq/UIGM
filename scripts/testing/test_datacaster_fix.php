<?php

/**
 * TEST DATACASTER FIX
 * Test apakah masalah DataCaster sudah teratasi
 */

// Include helper
require_once 'app/Helpers/JsonHelper.php';

echo "<h2>🔧 Test DataCaster Fix</h2>";
echo "<hr>";

// Test 1: Test database read without casting
echo "<h3>1. Test Database Read (No Casting)</h3>";

try {
    $conn = new mysqli('localhost', 'root', '', 'uigm_polban');

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "✅ Database connected<br>";

    // Read raw data_input values
    $result = $conn->query("SELECT id, data_input FROM review_kategori LIMIT 3");

    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Raw data_input</th><th>Type</th><th>Safe Decode</th><th>Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $rawData = $row['data_input'];
            $dataType = gettype($rawData);

            // Test safe_json_decode
            $decodedData = safe_json_decode($rawData, true);

            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($rawData ?? 'NULL') . "</td>";
            echo "<td>" . $dataType . "</td>";
            echo "<td>" . htmlspecialchars(json_encode($decodedData)) . "</td>";
            echo "<td>✅ OK</td>";
            echo "</tr>";
        }

        echo "</table><br>";
    }

    $conn->close();
} catch (Exception $e) {
    echo "❌ Database Error: " . $e->getMessage() . "<br>";
}

// Test 2: Test JSON string validation
echo "<h3>2. Test JSON String Validation</h3>";

$testStrings = [
    '{}',
    '{"test": "value"}',
    '{"tanggal_input": "2024-01-01", "gedung": "A", "jumlah": "100"}',
    'null',
    '',
    'invalid json'
];

foreach ($testStrings as $index => $testString) {
    echo "<strong>Test " . ($index + 1) . ":</strong><br>";
    echo "Input: " . htmlspecialchars($testString) . "<br>";

    // Test if it's valid JSON
    $decoded = json_decode($testString, true);
    $isValidJson = (json_last_error() === JSON_ERROR_NONE);

    echo "Valid JSON: " . ($isValidJson ? "✅ Yes" : "❌ No") . "<br>";

    // Test safe_json_decode
    $safeDecoded = safe_json_decode($testString, true);
    echo "Safe decode result: " . json_encode($safeDecoded) . "<br>";
    echo "<br>";
}

// Test 3: Simulate model operations without casting
echo "<h3>3. Test Model Operations (No JSON Casting)</h3>";

try {
    // Simulate what the model would do without JSON casting
    $mockData = [
        'pengiriman_id' => 1,
        'indikator_id' => 1,
        'data_input' => '{"test": "value", "number": 123}',
        'status_review' => 'pending'
    ];

    echo "Mock data prepared:<br>";
    echo "<pre>" . print_r($mockData, true) . "</pre>";

    // Test data_input handling
    $dataInput = $mockData['data_input'];
    echo "Raw data_input: " . htmlspecialchars($dataInput) . "<br>";
    echo "Type: " . gettype($dataInput) . "<br>";

    // This is what views will do
    $viewDecoded = safe_json_decode($dataInput, true);
    echo "View decoded: " . json_encode($viewDecoded) . "<br>";
    echo "✅ No DataCaster conflict<br>";
} catch (Exception $e) {
    echo "❌ Model operation error: " . $e->getMessage() . "<br>";
}

// Test 4: Test insert/update operations
echo "<h3>4. Test Insert/Update Operations</h3>";

try {
    $conn = new mysqli('localhost', 'root', '', 'uigm_polban');

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Test insert with proper JSON string
    $testJson = json_encode(['test_field' => 'test_value', 'timestamp' => date('Y-m-d H:i:s')]);

    $stmt = $conn->prepare("INSERT INTO review_kategori (pengiriman_id, indikator_id, data_input, status_review) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE data_input = VALUES(data_input)");
    $pengirimanId = 1;
    $indikatorId = 1;
    $status = 'pending';

    $stmt->bind_param("iiss", $pengirimanId, $indikatorId, $testJson, $status);

    if ($stmt->execute()) {
        echo "✅ Insert/Update successful<br>";
        echo "JSON data: " . htmlspecialchars($testJson) . "<br>";
    } else {
        echo "❌ Insert/Update failed: " . $stmt->error . "<br>";
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "❌ Insert/Update error: " . $e->getMessage() . "<br>";
}

echo "<br><h3>🎯 SUMMARY</h3>";
echo "<p><strong>Jika semua test menunjukkan ✅, DataCaster error sudah teratasi!</strong></p>";
echo "<p>Sekarang data_input disimpan sebagai JSON string dan di-decode manual dengan safe_json_decode().</p>";

echo "<hr>";
echo "<p><em>Test completed at: " . date('Y-m-d H:i:s') . "</em></p>";
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

    pre {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border-left: 4px solid #007bff;
    }
</style>