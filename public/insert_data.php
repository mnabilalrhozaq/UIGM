<?php
// Simple script to insert master harga sampah data
// Access via: http://localhost:8080/insert_data.php

// Bootstrap CodeIgniter
$pathsPath = realpath(FCPATH . '../app/Config/Paths.php');
require $pathsPath;

$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Config\Services::codeigniter();
$app->initialize();
$context = is_cli() ? 'php-cli' : 'web';
$app->setContext($context);

// Get database connection
$db = \Config\Database::connect();

// Check if table exists
if (!$db->tableExists('master_harga_sampah')) {
    die("❌ Table master_harga_sampah tidak ditemukan. Jalankan migration terlebih dahulu.");
}

// Check existing data
$existing = $db->table('master_harga_sampah')->countAllResults();

if ($existing > 0) {
    echo "✅ Data sudah ada ({$existing} records).<br><br>";
    echo "<a href='/pengelola-tps/waste' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Lihat Halaman Waste TPS</a>";
    echo " ";
    echo "<a href='/user/waste' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Lihat Halaman Waste User</a>";
    exit;
}

// Insert data
$data = [
    [
        'jenis_sampah' => 'Plastik',
        'nama_jenis' => 'Plastik (Botol, Kemasan)',
        'harga_per_satuan' => 2000.00,
        'harga_per_kg' => 2000.00,
        'satuan' => 'kg',
        'dapat_dijual' => 1,
        'status_aktif' => 1,
        'deskripsi' => 'Plastik yang dapat didaur ulang',
        'tanggal_berlaku' => date('Y-m-d'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'jenis_sampah' => 'Kertas',
        'nama_jenis' => 'Kertas (HVS, Koran, Kardus)',
        'harga_per_satuan' => 1500.00,
        'harga_per_kg' => 1500.00,
        'satuan' => 'kg',
        'dapat_dijual' => 1,
        'status_aktif' => 1,
        'deskripsi' => 'Kertas yang dapat didaur ulang',
        'tanggal_berlaku' => date('Y-m-d'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'jenis_sampah' => 'Logam',
        'nama_jenis' => 'Logam (Kaleng, Aluminium)',
        'harga_per_satuan' => 5000.00,
        'harga_per_kg' => 5000.00,
        'satuan' => 'kg',
        'dapat_dijual' => 1,
        'status_aktif' => 1,
        'deskripsi' => 'Logam yang dapat didaur ulang',
        'tanggal_berlaku' => date('Y-m-d'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'jenis_sampah' => 'Organik',
        'nama_jenis' => 'Sampah Organik',
        'harga_per_satuan' => 0.00,
        'harga_per_kg' => 0.00,
        'satuan' => 'kg',
        'dapat_dijual' => 0,
        'status_aktif' => 1,
        'deskripsi' => 'Sampah organik untuk kompos',
        'tanggal_berlaku' => date('Y-m-d'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    [
        'jenis_sampah' => 'Residu',
        'nama_jenis' => 'Sampah Residu',
        'harga_per_satuan' => 0.00,
        'harga_per_kg' => 0.00,
        'satuan' => 'kg',
        'dapat_dijual' => 0,
        'status_aktif' => 1,
        'deskripsi' => 'Sampah yang tidak dapat didaur ulang',
        'tanggal_berlaku' => date('Y-m-d'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
];

try {
    $db->table('master_harga_sampah')->insertBatch($data);
    echo "<h2>✅ Berhasil menambahkan " . count($data) . " data master harga sampah!</h2>";
    
    echo "<h3>Data yang ditambahkan:</h3>";
    echo "<ul style='font-size: 16px;'>";
    foreach ($data as $item) {
        echo "<li><strong>{$item['jenis_sampah']}</strong> - Rp " . number_format($item['harga_per_satuan'], 0, ',', '.') . "/{$item['satuan']}";
        echo " - " . ($item['dapat_dijual'] ? '<span style="color:green; font-weight:bold;">✓ Bisa Dijual</span>' : '<span style="color:gray;">✗ Tidak Dijual</span>');
        echo "</li>";
    }
    echo "</ul>";
    
    echo "<br><br>";
    echo "<a href='/pengelola-tps/waste' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;'>Lihat Halaman Waste TPS</a>";
    echo "<a href='/user/waste' style='padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Lihat Halaman Waste User</a>";
    
} catch (\Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
