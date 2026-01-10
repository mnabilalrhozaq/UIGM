<?php

/**
 * Script untuk setup tabel jenis_sampah
 * Jalankan dengan: php setup_jenis_sampah.php
 */

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

echo "🔧 Setup Jenis Sampah Database\n";
echo "================================\n\n";

try {
    // Get database connection
    $db = \Config\Database::connect();
    echo "✅ Database connection successful\n";

    // Check if table exists
    if (!$db->tableExists('jenis_sampah')) {
        echo "📋 Creating jenis_sampah table...\n";

        // Create table manually since we can't run migrations directly
        $sql = "
        CREATE TABLE `jenis_sampah` (
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

        $db->query($sql);
        echo "✅ Table jenis_sampah created successfully\n";
    } else {
        echo "✅ Table jenis_sampah already exists\n";
    }

    // Check if data exists
    $count = $db->table('jenis_sampah')->countAll();
    if ($count == 0) {
        echo "📝 Inserting sample data...\n";

        // Insert data
        $data = [
            // Level 1 - Kategori Utama
            [
                'id' => 1,
                'parent_id' => null,
                'kode' => 'organik',
                'nama' => 'Sampah Organik',
                'level' => 1,
                'urutan' => 1,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Level 2 - Area Sampah
            [
                'id' => 2,
                'parent_id' => 1,
                'kode' => 'kantin',
                'nama' => 'Sampah dari Kantin',
                'level' => 2,
                'urutan' => 1,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'parent_id' => 1,
                'kode' => 'lingkungan_kampus',
                'nama' => 'Sampah dari Lingkungan Kampus',
                'level' => 2,
                'urutan' => 2,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Level 3 - Detail Sampah Kantin
            [
                'id' => 4,
                'parent_id' => 2,
                'kode' => 'sisa_makanan_sayuran',
                'nama' => 'Sisa Makanan atau Sayuran',
                'level' => 3,
                'urutan' => 1,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'parent_id' => 2,
                'kode' => 'sisa_buah_buahan',
                'nama' => 'Sisa Buah-buahan',
                'level' => 3,
                'urutan' => 2,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'parent_id' => 2,
                'kode' => 'produk_sisa_dapur',
                'nama' => 'Produk Sisa Dapur (Ampas Kopi/Teh, Kulit Telur)',
                'level' => 3,
                'urutan' => 3,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Level 3 - Detail Sampah Lingkungan Kampus
            [
                'id' => 7,
                'parent_id' => 3,
                'kode' => 'daun_kering_gugur',
                'nama' => 'Daun-daun Kering yang Gugur',
                'level' => 3,
                'urutan' => 1,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'parent_id' => 3,
                'kode' => 'rumput_dipotong',
                'nama' => 'Rumput yang Dipotong',
                'level' => 3,
                'urutan' => 2,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'parent_id' => 3,
                'kode' => 'ranting_pohon_kecil',
                'nama' => 'Ranting-ranting Pohon Kecil',
                'level' => 3,
                'urutan' => 3,
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $db->table('jenis_sampah')->insertBatch($data);
        echo "✅ Inserted " . count($data) . " records successfully\n";
    } else {
        echo "✅ Data already exists ({$count} records)\n";
    }

    // Verify data structure
    echo "\n📊 Data Structure Verification:\n";

    $organik = $db->table('jenis_sampah')->where('kode', 'organik')->get()->getRowArray();
    if ($organik) {
        echo "✅ Kategori Organik: {$organik['nama']}\n";

        $areas = $db->table('jenis_sampah')->where('parent_id', $organik['id'])->get()->getResultArray();
        foreach ($areas as $area) {
            echo "  ├─ {$area['nama']}\n";

            $details = $db->table('jenis_sampah')->where('parent_id', $area['id'])->get()->getResultArray();
            foreach ($details as $detail) {
                echo "     ├─ {$detail['nama']}\n";
            }
        }
    }

    echo "\n🎉 Setup completed successfully!\n";
    echo "\n📋 Next Steps:\n";
    echo "1. Akses dashboard Admin Unit\n";
    echo "2. Pilih kategori Waste (WS)\n";
    echo "3. Pilih 'Sampah Organik' di dropdown Jenis Sampah\n";
    echo "4. Dropdown Area dan Detail akan muncul otomatis\n";
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
