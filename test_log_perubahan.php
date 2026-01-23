<?php
// Test log perubahan harga
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'eksperimen';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== TEST LOG PERUBAHAN HARGA ===\n\n";

// Cek apakah tabel ada
echo "1. Cek tabel log_perubahan_harga:\n";
$result = $conn->query("SHOW TABLES LIKE 'log_perubahan_harga'");
if ($result->num_rows > 0) {
    echo "✓ Tabel ada\n\n";
} else {
    echo "✗ Tabel TIDAK ada!\n\n";
    exit;
}

// Cek struktur tabel
echo "2. Struktur tabel:\n";
$result = $conn->query("DESCRIBE log_perubahan_harga");
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}
echo "\n";

// Cek jumlah data
echo "3. Jumlah data:\n";
$result = $conn->query("SELECT COUNT(*) as total FROM log_perubahan_harga");
$row = $result->fetch_assoc();
echo "Total: {$row['total']} records\n\n";

// Cek 5 data terbaru
echo "4. Data terbaru (5 records):\n";
$sql = "SELECT lph.*, u.nama_lengkap as admin_nama 
        FROM log_perubahan_harga lph
        LEFT JOIN users u ON u.id = lph.created_by
        ORDER BY lph.created_at DESC
        LIMIT 5";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "- ID: {$row['id']}\n";
        echo "  Jenis: {$row['jenis_sampah']}\n";
        echo "  Harga: Rp " . number_format($row['harga_lama'] ?? 0) . " → Rp " . number_format($row['harga_baru']) . "\n";
        echo "  Admin: {$row['admin_nama']}\n";
        echo "  Waktu: {$row['created_at']}\n\n";
    }
} else {
    echo "Tidak ada data\n";
}

$conn->close();
echo "=== END TEST ===\n";
