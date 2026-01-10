<?php

/**
 * Script untuk mengorganisir semua file non-essential di root directory
 * Mengelompokkan berdasarkan tipe dan fungsi
 */

echo "📁 MENGORGANISIR SEMUA FILE DI ROOT DIRECTORY\n";
echo "=============================================\n\n";

// File yang harus tetap di root (essential files)
$essentialFiles = [
    '.env',
    '.gitignore',
    'composer.json',
    'composer.lock',
    'phpunit.xml.dist',
    'preload.php',
    'spark',
    'LICENSE',
    'README.md'
];

echo "📋 1. FILE ESSENTIAL YANG TETAP DI ROOT\n";
echo "---------------------------------------\n";
foreach ($essentialFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file\n";
    } else {
        echo "   ⚠️  $file (tidak ditemukan)\n";
    }
}

// Kategorisasi file non-essential
$fileOrganization = [
    'backup/sql' => [
        'database_backup_clean_fixed.sql' => 'Backup database clean fixed',
        'database_backup_uigm_polban.sql' => 'Backup database UIGM Polban',
        'database_patch_add_missing_columns.sql' => 'Patch untuk kolom yang hilang',
        'database_patch_simple.sql' => 'Patch database sederhana',
        'fix_database_simple.sql' => 'Fix database sederhana',
        'fix_null_data_input.sql' => 'Fix data input null',
        'fix_pengiriman_unit_structure.sql' => 'Fix struktur pengiriman unit',
        'MANUAL_FIX_QUERY.sql' => 'Query manual fix',
        'create_jenis_sampah_table.sql' => 'Create tabel jenis sampah'
    ],
    
    'docs/summaries' => [
        'DASHBOARD_ADMIN_PUSAT_REDESIGN_SUMMARY.md' => 'Summary redesign dashboard admin pusat',
        'DROPDOWN_BERTINGKAT_JENIS_SAMPAH_SUMMARY.md' => 'Summary dropdown bertingkat jenis sampah',
        'DROPDOWN_FIX_SUMMARY.md' => 'Summary perbaikan dropdown',
        'FINAL_FIX_SUMMARY_JAVASCRIPT_CONFLICT.md' => 'Summary fix konflik JavaScript',
        'FINAL_FIX_SUMMARY.md' => 'Summary perbaikan final',
        'FINAL_SYSTEM_SUMMARY.md' => 'Summary sistem final',
        'FIX_TIPE_UNIT_ERROR.md' => 'Summary fix error tipe unit',
        'FITUR_PERHITUNGAN_NOMINAL_UANG_TPS.md' => 'Summary fitur perhitungan nominal TPS',
        'PANDUAN_INSTALASI_BACKUP.md' => 'Panduan instalasi backup',
        'PERBAIKAN_FINAL_SAVE_ISSUE.md' => 'Summary perbaikan save issue',
        'SAVE_ISSUE_FIX_SUMMARY.md' => 'Summary fix save issue',
        'SISTEM_FORM_PETUGAS_GEDUNG_TPS.md' => 'Summary sistem form petugas gedung TPS',
        'WASTE_MANAGEMENT_TPS_USER_FIELDS_SUMMARY.md' => 'Summary field user TPS waste management',
        'WASTE_MANAGEMENT_UPDATE_SUMMARY.md' => 'Summary update waste management'
    ],
    
    'temp/html_tests' => [
        'dashboard_admin_unit.html' => 'Test dashboard admin unit',
        'debug_dropdown.html' => 'Debug dropdown HTML',
        'DEBUG_SAVE_ISSUE.html' => 'Debug save issue HTML',
        'FINAL_FIX_SUMMARY.html' => 'Summary perbaikan final HTML',
        'FINAL_TEST_GUIDE.html' => 'Panduan test final HTML',
        'SIMPLE_TEST_GUIDE.html' => 'Panduan test sederhana HTML',
        'test_admin_unit_login.html' => 'Test login admin unit',
        'test_dropdown_bertingkat.html' => 'Test dropdown bertingkat',
        'test_dropdown_fix.html' => 'Test perbaikan dropdown',
        'test_dropdown_functionality.html' => 'Test fungsi dropdown',
        'test_dropdown_indonesia.html' => 'Test dropdown Indonesia',
        'test_dropdown_kantin.html' => 'Test dropdown kantin',
        'test_fix_verification.html' => 'Test verifikasi perbaikan',
        'test_form_petugas_gedung_tps.html' => 'Test form petugas gedung TPS',
        'test_notifications_fix.html' => 'Test perbaikan notifikasi',
        'test_notifications_simple.html' => 'Test notifikasi sederhana',
        'test_save_functionality_final.html' => 'Test fungsi save final',
        'test_save_functionality.html' => 'Test fungsi save',
        'test_simple_dropdown.html' => 'Test dropdown sederhana',
        'test_waste_management_form.html' => 'Test form waste management',
        'test_waste_management_new_fields.html' => 'Test field baru waste management'
    ],
    
    'temp/misc' => [
        'builds' => 'File builds',
        'env_backup.txt' => 'Backup file environment'
    ]
];

echo "\n📂 2. MENGORGANISIR FILE NON-ESSENTIAL\n";
echo "--------------------------------------\n";

$totalMoved = 0;
foreach ($fileOrganization as $targetDir => $files) {
    echo "\n📁 Direktori: $targetDir\n";
    
    // Buat direktori jika belum ada
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
        echo "   ✅ Dibuat direktori: $targetDir\n";
    }
    
    foreach ($files as $filename => $description) {
        $sourcePath = $filename;
        $targetPath = "$targetDir/$filename";
        
        if (file_exists($sourcePath)) {
            if (!file_exists($targetPath)) {
                if (rename($sourcePath, $targetPath)) {
                    echo "   ✅ Dipindah: $filename\n";
                    echo "      📝 $description\n";
                    $totalMoved++;
                } else {
                    echo "   ❌ Gagal memindah: $filename\n";
                }
            } else {
                echo "   ⚠️  Sudah ada: $targetPath\n";
            }
        } else {
            echo "   ℹ️  Tidak ditemukan: $filename\n";
        }
    }
}

echo "\n📄 3. FILE YANG TERSISA DI ROOT\n";
echo "-------------------------------\n";

$allFiles = scandir('.');
$remainingFiles = [];

foreach ($allFiles as $file) {
    if ($file === '.' || $file === '..') continue;
    if (is_dir($file)) continue;
    if (in_array($file, $essentialFiles)) continue;
    if ($file === 'organize_root_files.php' || $file === 'organize_all_files.php') continue;
    
    $remainingFiles[] = $file;
}

if (!empty($remainingFiles)) {
    foreach ($remainingFiles as $file) {
        echo "   📄 $file\n";
    }
} else {
    echo "   ✅ Hanya file essential yang tersisa\n";
}

echo "\n📊 4. RINGKASAN ORGANISASI LENGKAP\n";
echo "----------------------------------\n";

// Hitung file di setiap kategori
$categories = [
    'scripts/database' => 'Database Scripts',
    'scripts/testing' => 'Testing Scripts', 
    'scripts/maintenance' => 'Maintenance Scripts',
    'backup/sql' => 'SQL Backup Files',
    'docs/summaries' => 'Documentation Files',
    'temp/html_tests' => 'HTML Test Files',
    'temp/misc' => 'Miscellaneous Files'
];

foreach ($categories as $dir => $name) {
    if (is_dir($dir)) {
        $files = array_diff(scandir($dir), ['.', '..']);
        $count = count($files);
        echo "   📁 $name: $count files\n";
    }
}

echo "\n   📈 Total file diorganisir: $totalMoved\n";
echo "   📂 File essential di root: " . count($essentialFiles) . "\n";
echo "   📄 File tersisa di root: " . count($remainingFiles) . "\n";

echo "\n🎯 5. STRUKTUR DIREKTORI FINAL\n";
echo "------------------------------\n";
echo "   📁 Root (hanya essential files)\n";
echo "   ├── 📁 app/ (CodeIgniter MVC)\n";
echo "   ├── 📁 public/ (Web assets)\n";
echo "   ├── 📁 scripts/\n";
echo "   │   ├── 📁 database/ (Database management)\n";
echo "   │   ├── 📁 testing/ (Testing & debugging)\n";
echo "   │   └── 📁 maintenance/ (System maintenance)\n";
echo "   ├── 📁 backup/\n";
echo "   │   └── 📁 sql/ (SQL backup files)\n";
echo "   ├── 📁 docs/\n";
echo "   │   └── 📁 summaries/ (Documentation)\n";
echo "   └── 📁 temp/\n";
echo "       ├── 📁 html_tests/ (HTML test files)\n";
echo "       └── 📁 misc/ (Miscellaneous files)\n";

echo "\n💡 6. CARA PENGGUNAAN\n";
echo "--------------------\n";
echo "   • Database: php scripts/database/[script_name].php\n";
echo "   • Testing: php scripts/testing/[script_name].php\n";
echo "   • Maintenance: php scripts/maintenance/[script_name].php\n";
echo "   • Backup SQL: Lihat di backup/sql/\n";
echo "   • Dokumentasi: Lihat di docs/summaries/\n";

echo "\n✅ Organisasi lengkap selesai!\n";
echo "Root directory sekarang bersih dan terorganisir!\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";