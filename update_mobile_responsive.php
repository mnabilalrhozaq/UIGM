<?php
/**
 * Script untuk menambahkan Mobile Responsive CSS dan JS ke semua view files
 * Jalankan: php update_mobile_responsive.php
 */

$viewFiles = [
    // Admin Pusat
    'app/Views/admin_pusat/dashboard.php',
    'app/Views/admin_pusat/waste_management.php',
    'app/Views/admin_pusat/manajemen_harga/index.php',
    'app/Views/admin_pusat/user_management.php',
    'app/Views/admin_pusat/laporan_waste/index.php',
    'app/Views/admin_pusat/feature_toggle/index.php',
    
    // User
    'app/Views/user/dashboard.php',
    'app/Views/user/waste.php',
    
    // TPS
    'app/Views/pengelola_tps/dashboard.php',
    'app/Views/pengelola_tps/waste.php',
    
    // Auth
    'app/Views/auth/login.php',
];

$cssToAdd = '    <!-- Mobile Responsive CSS -->' . PHP_EOL . 
            '    <link href="<?= base_url(\'/css/mobile-responsive.css\') ?>" rel="stylesheet">' . PHP_EOL;

$jsToAdd = '    <!-- Mobile Menu JS -->' . PHP_EOL . 
           '    <script src="<?= base_url(\'/js/mobile-menu.js\') ?>"></script>' . PHP_EOL;

$updated = 0;
$skipped = 0;
$errors = 0;

echo "=== Mobile Responsive Update Script ===" . PHP_EOL . PHP_EOL;

foreach ($viewFiles as $file) {
    echo "Processing: $file ... ";
    
    if (!file_exists($file)) {
        echo "SKIP (file not found)" . PHP_EOL;
        $skipped++;
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Check if already has mobile CSS
    if (strpos($content, 'mobile-responsive.css') !== false) {
        echo "SKIP (already has mobile CSS)" . PHP_EOL;
        $skipped++;
        continue;
    }
    
    // Add CSS before </head>
    if (strpos($content, '</head>') !== false) {
        $content = str_replace('</head>', $cssToAdd . '</head>', $content);
    } else {
        echo "ERROR (no </head> tag found)" . PHP_EOL;
        $errors++;
        continue;
    }
    
    // Add JS before </body>
    if (strpos($content, '</body>') !== false) {
        $content = str_replace('</body>', $jsToAdd . '</body>', $content);
    } else {
        echo "WARNING (no </body> tag found, JS not added)" . PHP_EOL;
    }
    
    // Save file
    if (file_put_contents($file, $content)) {
        echo "OK" . PHP_EOL;
        $updated++;
    } else {
        echo "ERROR (failed to write file)" . PHP_EOL;
        $errors++;
    }
}

echo PHP_EOL . "=== Summary ===" . PHP_EOL;
echo "Updated: $updated files" . PHP_EOL;
echo "Skipped: $skipped files" . PHP_EOL;
echo "Errors: $errors files" . PHP_EOL;
echo PHP_EOL;

if ($updated > 0) {
    echo "✅ Mobile responsive CSS and JS added successfully!" . PHP_EOL;
    echo "📱 Test your website on mobile devices now." . PHP_EOL;
} else {
    echo "ℹ️  No files were updated." . PHP_EOL;
}
