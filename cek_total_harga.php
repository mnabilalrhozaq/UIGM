<?php
// Database connection
$db = new mysqli(
    'localhost',
    'root',
    '',
    'eksperimen'
);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

echo "=== CEK TOTAL DATA HARGA SAMPAH ===\n\n";

// Total semua
$result = $db->query("SELECT COUNT(*) as total FROM master_harga_sampah");
$row = $result->fetch_assoc();
echo "Total semua data: " . $row['total'] . "\n";

// Total aktif
$result = $db->query("SELECT COUNT(*) as total FROM master_harga_sampah WHERE status_aktif = 1");
$row = $result->fetch_assoc();
echo "Total data aktif: " . $row['total'] . "\n";

// Total nonaktif
$result = $db->query("SELECT COUNT(*) as total FROM master_harga_sampah WHERE status_aktif = 0");
$row = $result->fetch_assoc();
echo "Total data nonaktif: " . $row['total'] . "\n\n";

echo "=== DAFTAR JENIS SAMPAH AKTIF ===\n";
$result = $db->query("SELECT id, jenis_sampah, nama_jenis, harga_per_satuan, satuan, dapat_dijual, status_aktif FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
$no = 1;
while ($row = $result->fetch_assoc()) {
    echo $no++ . ". " . $row['jenis_sampah'] . " - " . $row['nama_jenis'] . " (Rp " . number_format($row['harga_per_satuan']) . "/" . $row['satuan'] . ")\n";
}

$db->close();
