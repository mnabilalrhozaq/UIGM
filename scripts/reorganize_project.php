<?php
/**
 * Script Reorganisasi Project CodeIgniter 4
 * Memindahkan file-file yang tercecer ke folder yang sesuai
 */

echo "🚀 Memulai reorganisasi project CodeIgniter 4...\n\n";

// Definisi struktur folder yang diinginkan
$folders = [
    'database/sql',
    'database/sql/patches',
    'database/sql/exports',
    'docs/development',
    'docs/fixes',
    'docs/api',
    'docs/user-guide',
    'scripts/setup',
    'scripts/maintenance',
    'scripts/deployment',
    'public/assets/css',
    'public/assets/js',
    'public/assets/img',
    'public/assets/vendor',
    'writable/uploads/documents',
    'writable/uploads/images'
];

// Buat folder yang belum ada
echo "📁 Membuat struktur folder...\n";
foreach ($folders as $folder) {
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
        echo "   ✅ Created: $folder\n";
    }
}

// Mapping file yang perlu dipindahkan
$fileMoves = [
    // File SQL ke database/sql/
    'database_export.sql' => 'database/sql/exports/database_export.sql',
    'database_fix_nilai_input.sql' => 'database/sql/patches/002_fix_nilai_input.sql',
    'database_notifications_table.sql' => 'database/sql/patches/001_add_notifications.sql',
    'database_patch_add_warna.sql' => 'database/sql/patches/003_add_warna.sql',
    'database_user_tables.sql' => 'database/sql/patches/004_user_tables.sql',
    
    // File dokumentasi ke docs/
    'IMPLEMENTASI_SISTEM_NOTIFIKASI.md' => 'docs/development/implementasi_sistem_notifikasi.md',
    'IMPLEMENTASI_USER_ROLE.md' => 'docs/development/implementasi_user_role.md',
    'INTEGRASI_WASTE_MANAGEMENT.md' => 'docs/development/integrasi_waste_management.md',
    'PERBAIKAN_DASHBOARD_ADMIN_PUSAT.md' => 'docs/fixes/perbaikan_dashboard_admin_pusat.md',
    'PERBAIKAN_DASHBOARD.md' => 'docs/fixes/perbaikan_dashboard.md',
    'PERBAIKAN_FIELD_JUMLAH_NULL.md' => 'docs/fixes/perbaikan_field_jumlah_null.md',
    'PERBAIKAN_NILAI_INPUT_NULL.md' => 'docs/fixes/perbaikan_nilai_input_null.md',
    'PERBAIKAN_PENGATURAN.md' => 'docs/fixes/perbaikan_pengaturan.md',
    'PERBAIKAN_PROGRESS_KATEGORI.md' => 'docs/fixes/perbaikan_progress_kategori.md',
    'PERBAIKAN_ROUTING.md' => 'docs/fixes/perbaikan_routing.md',
    'SISTEM_LENGKAP_SUMMARY.md' => 'docs/development/sistem_lengkap_summary.md',
    
    // File script ke scripts/
    'cleanup_organization_scripts.php' => 'scripts/maintenance/cleanup_organization_scripts.php',
    'organize_all_files.php' => 'scripts/maintenance/organize_all_files.php',
    'organize_root_files.php' => 'scripts/maintenance/organize_root_files.php',
    
    // File test ke tests/
    'simple_route_test.php' => 'tests/integration/simple_route_test.php',
    'test_login_and_dashboard.php' => 'tests/integration/test_login_and_dashboard.php',
];

// Pindahkan file
echo "\n📦 Memindahkan file...\n";
foreach ($fileMoves as $source => $destination) {
    if (file_exists($source)) {
        // Buat folder destination jika belum ada
        $destDir = dirname($destination);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        if (rename($source, $destination)) {
            echo "   ✅ Moved: $source → $destination\n";
        } else {
            echo "   ❌ Failed to move: $source\n";
        }
    } else {
        echo "   ⚠️  File not found: $source\n";
    }
}

// File yang perlu dihapus (jika tidak digunakan)
$filesToDelete = [
    'preload.php', // Jika tidak digunakan
];

echo "\n🗑️  File yang bisa dihapus (manual check):\n";
foreach ($filesToDelete as $file) {
    if (file_exists($file)) {
        echo "   ⚠️  Check if needed: $file\n";
    }
}

// Buat file .gitkeep untuk folder kosong
$emptyFolders = [
    'public/assets/css',
    'public/assets/js',
    'public/assets/img',
    'writable/uploads/documents',
    'writable/uploads/images'
];

echo "\n📝 Membuat .gitkeep untuk folder kosong...\n";
foreach ($emptyFolders as $folder) {
    $gitkeepFile = $folder . '/.gitkeep';
    if (is_dir($folder) && !file_exists($gitkeepFile)) {
        file_put_contents($gitkeepFile, '');
        echo "   ✅ Created: $gitkeepFile\n";
    }
}

// Update .gitignore
echo "\n📋 Updating .gitignore...\n";
$gitignoreContent = "
# CodeIgniter 4 specific
/vendor/
/writable/cache/*
/writable/logs/*
/writable/session/*
/writable/uploads/*
!/writable/uploads/.gitkeep
!/writable/uploads/documents/.gitkeep
!/writable/uploads/images/.gitkeep

# Environment files
.env
.env.*
!.env.example

# IDE files
.vscode/
.idea/
*.swp
*.swo

# OS files
.DS_Store
Thumbs.db

# Temporary files
/temp/*
!/temp/.gitkeep

# Database exports (keep only structure)
/database/sql/exports/*
!/database/sql/exports/.gitkeep

# Node modules (if using npm)
node_modules/

# Composer
composer.phar

# PHPUnit
.phpunit.result.cache
";

file_put_contents('.gitignore', $gitignoreContent);
echo "   ✅ Updated .gitignore\n";

echo "\n🎉 Reorganisasi selesai!\n";
echo "\n📋 Langkah selanjutnya:\n";
echo "   1. Review file yang dipindahkan\n";
echo "   2. Update path di kode jika ada yang hardcoded\n";
echo "   3. Test aplikasi untuk memastikan tidak ada broken link\n";
echo "   4. Commit perubahan ke Git\n";
echo "   5. Update dokumentasi jika diperlukan\n";
?>