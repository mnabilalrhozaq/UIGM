<?php

/**
 * CLEAR CACHE
 * Script untuk membersihkan cache CodeIgniter
 */

echo "<h2>🧹 Clear CodeIgniter Cache</h2>";
echo "<hr>";

// Clear writable cache directories
$cacheDirectories = [
    'writable/cache',
    'writable/session',
    'writable/logs',
    'writable/debugbar'
];

echo "<h3>1. Clear Cache Directories</h3>";

foreach ($cacheDirectories as $dir) {
    if (is_dir($dir)) {
        echo "Clearing $dir...<br>";

        // Get all files in directory
        $files = glob($dir . '/*');
        $cleared = 0;

        foreach ($files as $file) {
            if (is_file($file) && basename($file) !== '.gitkeep' && basename($file) !== 'index.html') {
                if (unlink($file)) {
                    $cleared++;
                }
            }
        }

        echo "✅ Cleared $cleared files from $dir<br>";
    } else {
        echo "⚠️ Directory $dir not found<br>";
    }
}

echo "<br><h3>2. Clear PHP OpCache (if enabled)</h3>";

if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "✅ OpCache cleared<br>";
    } else {
        echo "❌ Failed to clear OpCache<br>";
    }
} else {
    echo "ℹ️ OpCache not enabled<br>";
}

echo "<br><h3>3. Force Session Regeneration</h3>";

// Start session and destroy it to force regeneration
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
    echo "✅ Session destroyed<br>";
} else {
    echo "ℹ️ No active session<br>";
}

echo "<br><h3>4. Verify File Paths</h3>";

$criticalFiles = [
    'app/Controllers/AdminUnit.php',
    'app/Views/admin_unit/dashboard_clean.php',
    'app/Helpers/JsonHelper.php'
];

foreach ($criticalFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

echo "<br><h3>5. Check Controller Content</h3>";

$controllerContent = file_get_contents('app/Controllers/AdminUnit.php');
if (strpos($controllerContent, "dashboard_clean") !== false) {
    echo "✅ Controller uses dashboard_clean<br>";
} else {
    echo "❌ Controller still uses wrong path<br>";
}

echo "<br><h3>🎯 CACHE CLEARED!</h3>";
echo "<p><strong>Cache sudah dibersihkan. Coba akses website lagi:</strong></p>";
echo "<p><a href='http://localhost/eksperimen/' target='_blank'>http://localhost/eksperimen/</a></p>";

echo "<br><h4>Jika masih error, coba:</h4>";
echo "<ul>";
echo "<li>Restart Apache di XAMPP</li>";
echo "<li>Clear browser cache (Ctrl+F5)</li>";
echo "<li>Coba browser incognito/private</li>";
echo "<li>Restart browser completely</li>";
echo "</ul>";

echo "<hr>";
echo "<p><em>Cache cleared at: " . date('Y-m-d H:i:s') . "</em></p>";
?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f5f5f5;
    }

    h2,
    h3,
    h4 {
        color: #333;
    }

    a {
        color: #007bff;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    ul {
        background-color: white;
        padding: 15px;
        border-radius: 5px;
        border-left: 4px solid #ffc107;
    }
</style>