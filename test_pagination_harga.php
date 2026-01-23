<?php
// Test pagination untuk harga sampah
require 'vendor/autoload.php';

// Bootstrap CodeIgniter
$pathsConfig = 'app/Config/Paths.php';
require realpath($pathsConfig) ?: $pathsConfig;

$paths = new Config\Paths();
$bootstrap = rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
$app = require realpath($bootstrap) ?: $bootstrap;

// Get database instance
$db = \Config\Database::connect();

echo "=== TEST PAGINATION HARGA SAMPAH ===\n\n";

// Test dengan Model
$hargaModel = new \App\Models\HargaSampahModel();

echo "1. Total data aktif:\n";
$total = $hargaModel->where('status_aktif', 1)->countAllResults(false);
echo "   Total: $total\n\n";

echo "2. Pagination dengan 5 per page:\n";
$perPage = 5;
$categories = $hargaModel->where('status_aktif', 1)
                        ->orderBy('jenis_sampah', 'ASC')
                        ->paginate($perPage, 'harga');

echo "   Data retrieved: " . count($categories) . "\n";
echo "   Pager exists: " . (isset($hargaModel->pager) ? 'YES' : 'NO') . "\n";

if (isset($hargaModel->pager)) {
    $pager = $hargaModel->pager;
    echo "   Pager class: " . get_class($pager) . "\n";
    echo "   Page count (no group): " . $pager->getPageCount() . "\n";
    echo "   Page count (with 'harga'): " . $pager->getPageCount('harga') . "\n";
    echo "   Current page (with 'harga'): " . $pager->getCurrentPage('harga') . "\n";
    echo "   Per page: " . $pager->getPerPage('harga') . "\n";
    echo "   Total: " . $pager->getTotal('harga') . "\n";
}

echo "\n3. Data yang diambil:\n";
foreach ($categories as $i => $cat) {
    echo "   " . ($i+1) . ". " . $cat['jenis_sampah'] . " - " . $cat['nama_jenis'] . "\n";
}

echo "\n=== SELESAI ===\n";
