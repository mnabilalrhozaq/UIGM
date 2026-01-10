<?php

echo "=== FIX ADMIN_PUSAT VIEWS ===\n\n";

$views = [
    'app/Views/admin_pusat/pengaturan.php',
    'app/Views/admin_pusat/review.php',
    'app/Views/admin_pusat/monitoring.php',
    'app/Views/admin_pusat/notifikasi.php',
    'app/Views/admin_pusat/data_penilaian.php',
    'app/Views/admin_pusat/indikator_greenmetric.php',
    'app/Views/admin_pusat/riwayat_penilaian.php'
];

$fixed = 0;
$skipped = 0;

foreach ($views as $viewPath) {
    if (!file_exists($viewPath)) {
        echo "✗ File not found: {$viewPath}\n";
        continue;
    }
    
    $content = file_get_contents($viewPath);
    
    // Check if already has endSection
    if (strpos($content, '$this->endSection()') !== false) {
        echo "✓ Already fixed: {$viewPath}\n";
        $skipped++;
        continue;
    }
    
    // Check if uses extend and section
    if (strpos($content, '$this->extend(') !== false && strpos($content, '$this->section(') !== false) {
        // Add endSection at the end
        $content = rtrim($content) . "\n\n<?= \$this->endSection() ?>\n";
        
        file_put_contents($viewPath, $content);
        echo "✓ Fixed: {$viewPath}\n";
        $fixed++;
    } else {
        echo "- Skipped (no layout): {$viewPath}\n";
        $skipped++;
    }
}

echo "\n=== SUMMARY ===\n";
echo "Fixed: {$fixed} files\n";
echo "Skipped: {$skipped} files\n";
echo "\nAll views should now render properly!\n";
