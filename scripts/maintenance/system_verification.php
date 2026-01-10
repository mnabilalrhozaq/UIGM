<?php

/**
 * Script Verifikasi Sistem UIGM Lengkap
 * Mengecek semua komponen: Database, Models, Controllers, Views, Routes
 */

// Database configuration
$config = [
    'hostname' => 'localhost',
    'database' => 'uigm_polban',
    'username' => 'root',
    'password' => '',
    'port'     => 3306,
];

echo "🔍 VERIFIKASI SISTEM UIGM POLBAN\n";
echo "================================\n\n";

try {
    $pdo = new PDO(
        "mysql:host={$config['hostname']};port={$config['port']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    echo "✅ Koneksi Database: BERHASIL\n\n";

    // 1. CEK TABEL DATABASE
    echo "📋 1. VERIFIKASI TABEL DATABASE\n";
    echo "-------------------------------\n";
    
    $requiredTables = [
        'users' => 'Tabel pengguna sistem',
        'unit' => 'Tabel unit/fakultas',
        'tahun_penilaian' => 'Tabel periode penilaian',
        'indikator' => 'Tabel kategori UIGM',
        'pengiriman_unit' => 'Tabel pengiriman data unit',
        'review_kategori' => 'Tabel review admin pusat',
        'notifikasi' => 'Tabel notifikasi sistem',
        'riwayat_versi' => 'Tabel riwayat perubahan',
        'jenis_sampah' => 'Tabel jenis sampah organik',
        'migrations' => 'Tabel tracking migration'
    ];

    $tableStatus = [];
    foreach ($requiredTables as $table => $description) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        $exists = $stmt->rowCount() > 0;
        $tableStatus[$table] = $exists;
        
        if ($exists) {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch()['count'];
            echo "   ✅ $table ($count records) - $description\n";
        } else {
            echo "   ❌ $table - $description\n";
        }
    }

    // 2. CEK DATA PENTING
    echo "\n📊 2. VERIFIKASI DATA PENTING\n";
    echo "-----------------------------\n";

    // Cek users
    $stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    $userRoles = $stmt->fetchAll();
    echo "   👥 Users by Role:\n";
    foreach ($userRoles as $role) {
        echo "      - {$role['role']}: {$role['count']} users\n";
    }

    // Cek tahun aktif
    $stmt = $pdo->query("SELECT * FROM tahun_penilaian WHERE status_aktif = 1");
    $activeTahun = $stmt->fetch();
    if ($activeTahun) {
        echo "   📅 Tahun Aktif: {$activeTahun['tahun']} - {$activeTahun['nama_periode']}\n";
    } else {
        echo "   ⚠️  Tidak ada tahun penilaian aktif\n";
    }

    // Cek indikator UIGM
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM indikator WHERE status_aktif = 1");
    $indikatorCount = $stmt->fetch()['count'];
    echo "   📋 Indikator UIGM Aktif: $indikatorCount kategori\n";

    // Cek pengiriman unit
    $stmt = $pdo->query("SELECT status_pengiriman, COUNT(*) as count FROM pengiriman_unit GROUP BY status_pengiriman");
    $pengirimanStatus = $stmt->fetchAll();
    echo "   📤 Status Pengiriman:\n";
    foreach ($pengirimanStatus as $status) {
        echo "      - {$status['status_pengiriman']}: {$status['count']} pengiriman\n";
    }

    // 3. CEK FILE SISTEM
    echo "\n📁 3. VERIFIKASI FILE SISTEM\n";
    echo "----------------------------\n";

    $requiredFiles = [
        // Controllers
        'app/Controllers/AdminUnit.php' => 'Controller Admin Unit',
        'app/Controllers/AdminPusat.php' => 'Controller Admin Pusat',
        'app/Controllers/SuperAdmin.php' => 'Controller Super Admin',
        'app/Controllers/Auth.php' => 'Controller Authentication',
        'app/Controllers/ApiController.php' => 'Controller API',
        'app/Controllers/ReportController.php' => 'Controller Laporan',
        
        // Models
        'app/Models/UserModel.php' => 'Model User',
        'app/Models/UnitModel.php' => 'Model Unit',
        'app/Models/PengirimanUnitModel.php' => 'Model Pengiriman',
        'app/Models/ReviewKategoriModel.php' => 'Model Review',
        'app/Models/NotifikasiModel.php' => 'Model Notifikasi',
        'app/Models/JenisSampahModel.php' => 'Model Jenis Sampah',
        
        // Views - Admin Unit
        'app/Views/admin_unit/dashboard_clean.php' => 'Dashboard Admin Unit',
        'app/Views/admin_unit/error.php' => 'Error Page Admin Unit',
        
        // Views - Admin Pusat
        'app/Views/admin_pusat/dashboard.php' => 'Dashboard Admin Pusat',
        'app/Views/admin_pusat/monitoring.php' => 'Monitoring Unit',
        'app/Views/admin_pusat/review_queue.php' => 'Antrian Review',
        'app/Views/admin_pusat/review_detail.php' => 'Detail Review',
        'app/Views/admin_pusat/notifikasi.php' => 'Notifikasi',
        'app/Views/admin_pusat/data_penilaian.php' => 'Data Penilaian',
        'app/Views/admin_pusat/indikator_greenmetric.php' => 'Indikator GreenMetric',
        'app/Views/admin_pusat/riwayat_penilaian.php' => 'Riwayat Penilaian',
        'app/Views/admin_pusat/pengaturan.php' => 'Pengaturan',
        
        // Views - Super Admin
        'app/Views/super_admin/dashboard.php' => 'Dashboard Super Admin',
        'app/Views/super_admin/users.php' => 'Manajemen Users',
        'app/Views/super_admin/units.php' => 'Manajemen Units',
        'app/Views/super_admin/tahun_penilaian.php' => 'Manajemen Tahun Penilaian',
        
        // Layouts
        'app/Views/layouts/admin_unit.php' => 'Layout Admin Unit',
        'app/Views/layouts/admin_pusat_new.php' => 'Layout Admin Pusat',
        
        // Config
        'app/Config/Routes.php' => 'Konfigurasi Routes',
    ];

    $fileStatus = [];
    foreach ($requiredFiles as $file => $description) {
        $exists = file_exists($file);
        $fileStatus[$file] = $exists;
        
        if ($exists) {
            $size = round(filesize($file) / 1024, 2);
            echo "   ✅ $file ({$size}KB) - $description\n";
        } else {
            echo "   ❌ $file - $description\n";
        }
    }

    // 4. CEK ROUTES
    echo "\n🛣️  4. VERIFIKASI ROUTES\n";
    echo "----------------------\n";

    $routeFile = 'app/Config/Routes.php';
    if (file_exists($routeFile)) {
        $routeContent = file_get_contents($routeFile);
        
        $requiredRoutes = [
            'auth/login' => 'Login page',
            'admin-unit' => 'Admin Unit dashboard',
            'admin-pusat' => 'Admin Pusat dashboard',
            'super-admin' => 'Super Admin dashboard',
            'api/' => 'API endpoints',
            'report/' => 'Report endpoints'
        ];

        foreach ($requiredRoutes as $route => $description) {
            if (strpos($routeContent, $route) !== false) {
                echo "   ✅ /$route - $description\n";
            } else {
                echo "   ❌ /$route - $description\n";
            }
        }
    }

    // 5. RINGKASAN STATUS
    echo "\n📈 5. RINGKASAN STATUS SISTEM\n";
    echo "-----------------------------\n";

    $totalTables = count($requiredTables);
    $validTables = count(array_filter($tableStatus));
    $tablePercentage = round(($validTables / $totalTables) * 100, 1);

    $totalFiles = count($requiredFiles);
    $validFiles = count(array_filter($fileStatus));
    $filePercentage = round(($validFiles / $totalFiles) * 100, 1);

    echo "   📋 Database Tables: $validTables/$totalTables ($tablePercentage%)\n";
    echo "   📁 System Files: $validFiles/$totalFiles ($filePercentage%)\n";

    if ($tablePercentage >= 90 && $filePercentage >= 90) {
        echo "   🎉 STATUS: SISTEM SIAP DIGUNAKAN!\n";
    } elseif ($tablePercentage >= 70 && $filePercentage >= 70) {
        echo "   ⚠️  STATUS: SISTEM HAMPIR LENGKAP\n";
    } else {
        echo "   ❌ STATUS: SISTEM PERLU PERBAIKAN\n";
    }

    // 6. REKOMENDASI
    echo "\n💡 6. REKOMENDASI TINDAK LANJUT\n";
    echo "-------------------------------\n";

    $recommendations = [];

    // Cek missing tables
    $missingTables = array_keys(array_filter($tableStatus, function($status) { return !$status; }));
    if (!empty($missingTables)) {
        $recommendations[] = "Jalankan migration untuk tabel: " . implode(', ', $missingTables);
    }

    // Cek missing files
    $missingFiles = array_keys(array_filter($fileStatus, function($status) { return !$status; }));
    if (!empty($missingFiles)) {
        $recommendations[] = "Buat file yang hilang: " . count($missingFiles) . " files";
    }

    // Cek tahun aktif
    if (!$activeTahun) {
        $recommendations[] = "Set tahun penilaian aktif melalui Super Admin";
    }

    // Cek data users
    $hasAdminPusat = false;
    foreach ($userRoles as $role) {
        if ($role['role'] === 'admin_pusat' && $role['count'] > 0) {
            $hasAdminPusat = true;
            break;
        }
    }
    if (!$hasAdminPusat) {
        $recommendations[] = "Buat akun Admin Pusat untuk mengelola sistem";
    }

    if (empty($recommendations)) {
        echo "   ✅ Tidak ada rekomendasi - sistem sudah lengkap!\n";
    } else {
        foreach ($recommendations as $i => $rec) {
            echo "   " . ($i + 1) . ". $rec\n";
        }
    }

    // 7. QUICK ACTIONS
    echo "\n⚡ 7. QUICK ACTIONS\n";
    echo "------------------\n";
    echo "   • Akses Admin Unit: http://localhost:8080/admin-unit\n";
    echo "   • Akses Admin Pusat: http://localhost:8080/admin-pusat\n";
    echo "   • Akses Super Admin: http://localhost:8080/super-admin\n";
    echo "   • Login Page: http://localhost:8080/auth/login\n";
    echo "   • API Documentation: http://localhost:8080/api\n";

} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error sistem: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Verifikasi sistem selesai!\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";