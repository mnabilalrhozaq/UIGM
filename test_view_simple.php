<?php
// Simple test to check view rendering
require 'vendor/autoload.php';

// Simulate view data
$statistics = [
    'total' => 14,
    'aktif' => 11,
    'bisa_dijual' => 9,
    'perubahan_hari_ini' => 0
];

$hargaSampah = [];
$pager = null;
$recentChanges = [];
$recentChangesCount = 0;
$categories = [];
$title = 'Test';

// Count stats-grid in view file
$viewContent = file_get_contents('app/Views/admin_pusat/manajemen_harga/index.php');
$count = substr_count($viewContent, '<div class="stats-grid">');

echo "Number of '<div class=\"stats-grid\">' in view file: $count\n";

if ($count > 1) {
    echo "ERROR: Found duplicate stats-grid in view file!\n";
    
    // Find line numbers
    $lines = explode("\n", $viewContent);
    foreach ($lines as $num => $line) {
        if (strpos($line, '<div class="stats-grid">') !== false) {
            echo "Found at line: " . ($num + 1) . "\n";
        }
    }
} else {
    echo "OK: Only 1 stats-grid found in view file.\n";
    echo "Problem might be:\n";
    echo "1. Browser cache\n";
    echo "2. JavaScript cloning elements\n";
    echo "3. Server-side caching\n";
}

// Check for JavaScript that might clone
if (strpos($viewContent, '.clone(') !== false) {
    echo "\nWARNING: Found .clone() in JavaScript!\n";
}

if (strpos($viewContent, '.append(') !== false || strpos($viewContent, '.prepend(') !== false) {
    echo "\nWARNING: Found .append() or .prepend() in JavaScript!\n";
}
