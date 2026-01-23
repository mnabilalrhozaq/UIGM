<?php
// Test query dashboard data - Simple version without Dotenv

// Database connection - hardcoded untuk test
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'eksperimen';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TEST DASHBOARD DATA ===\n\n";

// Test query untuk recent submissions
echo "1. Testing Recent Submissions Query:\n";
echo "-----------------------------------\n";

$sql = "
    SELECT 
        wm.*,
        u.nama_unit,
        u.kode_unit,
        DATE_FORMAT(wm.created_at, '%d/%m/%Y') as tanggal_input
    FROM waste_management wm
    LEFT JOIN unit u ON u.id = wm.unit_id
    WHERE wm.status = 'dikirim'
    ORDER BY wm.created_at DESC
    LIMIT 10
";

$result = $conn->query($sql);

if ($result) {
    echo "Total data ditemukan: " . $result->num_rows . "\n\n";
    
    if ($result->num_rows > 0) {
        echo "Data yang ditemukan:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- ID: {$row['id']}, Unit: {$row['nama_unit']}, Jenis: {$row['jenis_sampah']}, Status: {$row['status']}\n";
        }
    } else {
        echo "Tidak ada data dengan status 'dikirim'\n\n";
        
        // Cek semua status yang ada
        echo "\n2. Cek semua status di waste_management:\n";
        echo "----------------------------------------\n";
        $statusSql = "SELECT status, COUNT(*) as total FROM waste_management GROUP BY status";
        $statusResult = $conn->query($statusSql);
        
        if ($statusResult) {
            while ($row = $statusResult->fetch_assoc()) {
                echo "Status: {$row['status']} = {$row['total']} data\n";
            }
        }
    }
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
echo "\n=== END TEST ===\n";

