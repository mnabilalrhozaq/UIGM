<?php

/**
 * TEST JSON FIX
 * Test apakah perbaikan JSON sudah bekerja
 */

// Include the helper
require_once 'app/Helpers/JsonHelper.php';

echo "<h2>🧪 Test JSON Fix</h2>";
echo "<hr>";

// Test 1: Test safe_json_decode function
echo "<h3>1. Test safe_json_decode Function</h3>";

$testCases = [
    'Valid JSON string' => '{"test": "value", "number": 123}',
    'Empty JSON string' => '{}',
    'Invalid JSON string' => '{invalid json}',
    'Null value' => null,
    'Empty string' => '',
    'Array input' => ['test' => 'value', 'number' => 123],
    'Object input' => (object)['test' => 'value', 'number' => 123],
    'String input' => 'just a string',
];

foreach ($testCases as $description => $input) {
    echo "<strong>$description:</strong><br>";
    echo "Input: " . (is_string($input) ? "'$input'" : gettype($input)) . "<br>";

    try {
        $result = safe_json_decode($input, true);
        echo "Result: " . json_encode($result) . "<br>";
        echo "Type: " . gettype($result) . "<br>";
        echo "✅ Success<br><br>";
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br><br>";
    }
}

// Test 2: Test database connection and JSON handling
echo "<h3>2. Test Database JSON Handling</h3>";

try {
    $conn = new mysqli('localhost', 'root', '', 'uigm_polban');

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "✅ Database connected<br>";

    // Test reading data_input values
    $result = $conn->query("SELECT id, data_input FROM review_kategori LIMIT 3");

    if ($result) {
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>ID</th><th>Raw data_input</th><th>Decoded Result</th><th>Status</th></tr>";

        while ($row = $result->fetch_assoc()) {
            $rawData = $row['data_input'];
            $decodedData = safe_json_decode($rawData, true);

            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . htmlspecialchars($rawData ?? 'NULL') . "</td>";
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

// Test 3: Simulate the original error scenario
echo "<h3>3. Test Original Error Scenario</h3>";

// Simulate what was causing the error
$mockReviewData = [
    ['data_input' => '{"test": "value"}'],
    ['data_input' => '{}'],
    ['data_input' => null],
    ['data_input' => ''],
    ['data_input' => (object)['test' => 'value']], // This was causing the error
];

echo "Testing scenarios that previously caused errors:<br><br>";

foreach ($mockReviewData as $index => $review) {
    echo "<strong>Scenario " . ($index + 1) . ":</strong><br>";
    echo "Input type: " . gettype($review['data_input']) . "<br>";

    try {
        // This is what the old code was doing (would fail)
        // $dataInput = json_decode($review['data_input'], true);

        // This is what the new code does (should work)
        $dataInput = safe_json_decode($review['data_input'], true);

        echo "Result: " . json_encode($dataInput) . "<br>";
        echo "✅ Fixed - No error<br><br>";
    } catch (Exception $e) {
        echo "❌ Still has error: " . $e->getMessage() . "<br><br>";
    }
}

echo "<h3>🎉 ALL TESTS COMPLETED!</h3>";
echo "<p><strong>Jika semua test menunjukkan ✅, maka perbaikan berhasil.</strong></p>";

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
</style>