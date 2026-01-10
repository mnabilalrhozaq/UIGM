<?php
// Test API endpoints for jenis sampah

// Simulate CodeIgniter environment
require_once 'vendor/autoload.php';

// Test database connection first
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'uigm_polban';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== Testing Database Connection ===\n";
    echo "✓ Connected to database successfully\n\n";

    // Check if jenis_sampah table exists and has data
    echo "=== Checking jenis_sampah table ===\n";

    $stmt = $pdo->query("SHOW TABLES LIKE 'jenis_sampah'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Table jenis_sampah exists\n";

        $stmt = $pdo->query("SELECT COUNT(*) as count FROM jenis_sampah");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "Records count: $count\n";

        if ($count > 0) {
            echo "\n=== Sample Data ===\n";
            $stmt = $pdo->query("SELECT * FROM jenis_sampah ORDER BY level, urutan LIMIT 5");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($data as $row) {
                echo "ID: {$row['id']}, Level: {$row['level']}, Nama: {$row['nama']}\n";
            }

            echo "\n=== Testing Area Query (parent_id = 1) ===\n";
            $stmt = $pdo->prepare("SELECT * FROM jenis_sampah WHERE parent_id = ? AND level = 2 AND status_aktif = 1 ORDER BY urutan");
            $stmt->execute([1]);
            $areas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "Found " . count($areas) . " areas:\n";
            foreach ($areas as $area) {
                echo "- ID: {$area['id']}, Nama: {$area['nama']}\n";
            }

            if (count($areas) > 0) {
                $firstAreaId = $areas[0]['id'];
                echo "\n=== Testing Detail Query (parent_id = $firstAreaId) ===\n";
                $stmt = $pdo->prepare("SELECT * FROM jenis_sampah WHERE parent_id = ? AND level = 3 AND status_aktif = 1 ORDER BY urutan");
                $stmt->execute([$firstAreaId]);
                $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo "Found " . count($details) . " details:\n";
                foreach ($details as $detail) {
                    echo "- ID: {$detail['id']}, Nama: {$detail['nama']}\n";
                }
            }
        } else {
            echo "❌ No data found in jenis_sampah table\n";
            echo "Creating sample data...\n";

            // Create sample data
            $insertData = [
                [1, null, 'organik', 'Sampah Organik', 1, 1, 1],
                [2, 1, 'kantin', 'Sampah dari Kantin', 2, 1, 1],
                [3, 1, 'lingkungan_kampus', 'Sampah dari Lingkungan Kampus', 2, 2, 1],
                [4, 2, 'sisa_makanan_sayuran', 'Sisa Makanan atau Sayuran', 3, 1, 1],
                [5, 2, 'sisa_buah_buahan', 'Sisa Buah-buahan', 3, 2, 1],
                [6, 2, 'produk_sisa_dapur', 'Produk Sisa Dapur (Ampas Kopi/Teh, Kulit Telur)', 3, 3, 1],
                [7, 3, 'daun_kering_gugur', 'Daun-daun Kering yang Gugur', 3, 1, 1],
                [8, 3, 'rumput_dipotong', 'Rumput yang Dipotong', 3, 2, 1],
                [9, 3, 'ranting_pohon_kecil', 'Ranting-ranting Pohon Kecil', 3, 3, 1]
            ];

            $sql = "INSERT IGNORE INTO jenis_sampah (id, parent_id, kode, nama, level, urutan, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $stmt = $pdo->prepare($sql);

            foreach ($insertData as $data) {
                $stmt->execute($data);
            }

            echo "✓ Sample data created\n";
        }
    } else {
        echo "❌ Table jenis_sampah does not exist\n";
        echo "Creating table and data...\n";

        // Create table
        $createSQL = "
        CREATE TABLE IF NOT EXISTS `jenis_sampah` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `parent_id` int(11) unsigned DEFAULT NULL,
            `kode` varchar(50) NOT NULL,
            `nama` varchar(255) NOT NULL,
            `level` tinyint(1) NOT NULL COMMENT '1=kategori utama, 2=area, 3=detail',
            `urutan` int(3) NOT NULL DEFAULT 0,
            `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
            `created_at` datetime DEFAULT NULL,
            `updated_at` datetime DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `kode` (`kode`),
            KEY `parent_id` (`parent_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        $pdo->exec($createSQL);
        echo "✓ Table created\n";

        // Insert data
        $insertData = [
            [1, null, 'organik', 'Sampah Organik', 1, 1, 1],
            [2, 1, 'kantin', 'Sampah dari Kantin', 2, 1, 1],
            [3, 1, 'lingkungan_kampus', 'Sampah dari Lingkungan Kampus', 2, 2, 1],
            [4, 2, 'sisa_makanan_sayuran', 'Sisa Makanan atau Sayuran', 3, 1, 1],
            [5, 2, 'sisa_buah_buahan', 'Sisa Buah-buahan', 3, 2, 1],
            [6, 2, 'produk_sisa_dapur', 'Produk Sisa Dapur (Ampas Kopi/Teh, Kulit Telur)', 3, 3, 1],
            [7, 3, 'daun_kering_gugur', 'Daun-daun Kering yang Gugur', 3, 1, 1],
            [8, 3, 'rumput_dipotong', 'Rumput yang Dipotong', 3, 2, 1],
            [9, 3, 'ranting_pohon_kecil', 'Ranting-ranting Pohon Kecil', 3, 3, 1]
        ];

        $sql = "INSERT INTO jenis_sampah (id, parent_id, kode, nama, level, urutan, status_aktif, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $stmt = $pdo->prepare($sql);

        foreach ($insertData as $data) {
            $stmt->execute($data);
        }

        echo "✓ Data inserted\n";
    }
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
echo "If everything looks good, the API endpoints should work.\n";
echo "Next step: Test the actual API endpoints via HTTP requests.\n";
