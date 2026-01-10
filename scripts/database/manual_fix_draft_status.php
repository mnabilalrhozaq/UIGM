<?php

/**
 * Manual script to fix draft status and test database connection
 * Run this file directly in browser: http://localhost/eksperimen/manual_fix_draft_status.php
 */

// Load CodeIgniter
require_once 'app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

// Get database connection
$db = \Config\Database::connect();

echo "<h1>🔧 Manual Fix Draft Status</h1>";
echo "<style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
    .success { background: #d4edda; padding: 10px; border-left: 4px solid #28a745; margin: 10px 0; }
    .error { background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545; margin: 10px 0; }
    .info { background: #d1ecf1; padding: 10px; border-left: 4px solid #17a2b8; margin: 10px 0; }
    .code { background: #f8f9fa; padding: 10px; font-family: monospace; border: 1px solid #ddd; }
</style>";

try {
    echo "<div class='success'>✅ Database connection successful</div>";

    // Check admin_unit user
    $adminUnit = $db->table('users')->where('username', 'admin_unit')->get()->getRowArray();

    if (!$adminUnit) {
        echo "<div class='error'>❌ admin_unit user not found!</div>";

        // Create admin_unit user if not exists
        $unitId = $db->table('unit')->where('nama_unit', 'Unit Test')->get()->getRowArray();
        if (!$unitId) {
            $unitId = $db->table('unit')->insert([
                'nama_unit' => 'Unit Test',
                'kode_unit' => 'UT001',
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            echo "<div class='info'>📝 Created unit with ID: $unitId</div>";
        } else {
            $unitId = $unitId['id'];
        }

        $userId = $db->table('users')->insert([
            'username' => 'admin_unit',
            'password' => 'admin123', // Plain text as requested
            'nama_lengkap' => 'Admin Unit Test',
            'email' => 'admin.unit@test.com',
            'role' => 'admin_unit',
            'unit_id' => $unitId,
            'status_aktif' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        echo "<div class='success'>✅ Created admin_unit user with ID: $userId</div>";
        $adminUnit = $db->table('users')->find($userId);
    } else {
        echo "<div class='success'>✅ Found admin_unit user - ID: {$adminUnit['id']}, Unit ID: {$adminUnit['unit_id']}</div>";
    }

    // Get active year
    $tahunAktif = $db->table('tahun_penilaian')->where('status_aktif', 1)->get()->getRowArray();
    if (!$tahunAktif) {
        $tahunId = $db->table('tahun_penilaian')->insert([
            'tahun' => date('Y'),
            'nama_periode' => 'Periode ' . date('Y'),
            'tanggal_mulai' => date('Y-01-01'),
            'tanggal_selesai' => date('Y-12-31'),
            'status_aktif' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "<div class='info'>📝 Created active year with ID: $tahunId</div>";
        $tahunAktif = ['id' => $tahunId];
    } else {
        echo "<div class='success'>✅ Found active year - ID: {$tahunAktif['id']}, Year: {$tahunAktif['tahun']}</div>";
    }

    // Check or create pengiriman
    $pengiriman = $db->table('pengiriman_unit')
        ->where('unit_id', $adminUnit['unit_id'])
        ->where('tahun_penilaian_id', $tahunAktif['id'])
        ->get()->getRowArray();

    if (!$pengiriman) {
        $pengirimanId = $db->table('pengiriman_unit')->insert([
            'unit_id' => $adminUnit['unit_id'],
            'tahun_penilaian_id' => $tahunAktif['id'],
            'status_pengiriman' => 'draft',
            'progress_persen' => 0.0,
            'versi' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "<div class='info'>📝 Created pengiriman with ID: $pengirimanId</div>";
        $pengiriman = ['id' => $pengirimanId, 'status_pengiriman' => 'draft'];
    } else {
        echo "<div class='success'>✅ Found pengiriman - ID: {$pengiriman['id']}, Status: {$pengiriman['status_pengiriman']}</div>";
    }

    // Force draft status
    $updated = $db->table('pengiriman_unit')
        ->where('unit_id', $adminUnit['unit_id'])
        ->update([
            'status_pengiriman' => 'draft',
            'progress_persen' => 0.0,
            'tanggal_kirim' => null,
            'tanggal_disetujui' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

    echo "<div class='success'>✅ Forced $updated pengiriman records to draft status</div>";

    // Check indikator
    $indikator = $db->table('indikator')->where('status_aktif', 1)->get()->getResultArray();
    echo "<div class='info'>📊 Found " . count($indikator) . " active indicators</div>";

    if (count($indikator) === 0) {
        // Create basic indicators
        $categories = [
            ['SI', 'Setting & Infrastructure', '#2E8B57', 1, 20],
            ['EC', 'Energy & Climate Change', '#FF6B35', 2, 20],
            ['WS', 'Waste', '#4ECDC4', 3, 15],
            ['WR', 'Water', '#45B7D1', 4, 15],
            ['TR', 'Transportation', '#96CEB4', 5, 15],
            ['ED', 'Education', '#FFEAA7', 6, 15]
        ];

        foreach ($categories as $cat) {
            $db->table('indikator')->insert([
                'kode_kategori' => $cat[0],
                'nama_kategori' => $cat[1],
                'warna' => $cat[2],
                'urutan' => $cat[3],
                'bobot' => $cat[4],
                'status_aktif' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        echo "<div class='info'>📝 Created 6 basic indicators</div>";
    }

    // Create review records if not exist
    $indikatorList = $db->table('indikator')->where('status_aktif', 1)->get()->getResultArray();
    foreach ($indikatorList as $ind) {
        $existingReview = $db->table('review_kategori')
            ->where('pengiriman_id', $pengiriman['id'])
            ->where('indikator_id', $ind['id'])
            ->get()->getRowArray();

        if (!$existingReview) {
            $db->table('review_kategori')->insert([
                'pengiriman_id' => $pengiriman['id'],
                'indikator_id' => $ind['id'],
                'status_review' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    echo "<div class='success'>✅ Ensured review records exist for all categories</div>";

    // Final verification
    $finalPengiriman = $db->table('pengiriman_unit')
        ->where('unit_id', $adminUnit['unit_id'])
        ->get()->getResultArray();

    echo "<h2>📋 Final Status</h2>";
    foreach ($finalPengiriman as $p) {
        echo "<div class='info'>Pengiriman ID: {$p['id']}, Status: <strong>{$p['status_pengiriman']}</strong>, Progress: {$p['progress_persen']}%</div>";
    }

    echo "<div class='success'>";
    echo "<h3>🎉 Setup Complete!</h3>";
    echo "<p><strong>Login credentials:</strong></p>";
    echo "<div class='code'>Username: admin_unit<br>Password: admin123</div>";
    echo "<p><strong>Dashboard URL:</strong></p>";
    echo "<div class='code'><a href='/eksperimen/admin-unit/dashboard' target='_blank'>http://localhost/eksperimen/admin-unit/dashboard</a></div>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='error'>❌ Error: " . $e->getMessage() . "</div>";
    echo "<div class='code'>" . $e->getTraceAsString() . "</div>";
}
