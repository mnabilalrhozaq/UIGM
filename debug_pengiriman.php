<?php

// Debug script to check pengiriman status
require_once 'vendor/autoload.php';

// Initialize CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    $db = \Config\Database::connect();

    echo "=== PENGIRIMAN STATUS DEBUG ===\n\n";

    // Check pengiriman_unit table
    $query = $db->query("SELECT * FROM pengiriman_unit ORDER BY id DESC LIMIT 5");
    $pengiriman = $query->getResultArray();

    echo "Recent pengiriman records:\n";
    foreach ($pengiriman as $p) {
        echo "ID: {$p['id']}, Unit: {$p['unit_id']}, Status: {$p['status_pengiriman']}, Progress: {$p['progress_persen']}%\n";
    }

    echo "\n";

    // Check users
    $query = $db->query("SELECT id, username, role, unit_id FROM users WHERE role = 'admin_unit'");
    $users = $query->getResultArray();

    echo "Admin Unit users:\n";
    foreach ($users as $u) {
        echo "ID: {$u['id']}, Username: {$u['username']}, Unit: {$u['unit_id']}\n";
    }

    echo "\n";

    // Check if there's a draft pengiriman for admin_unit
    if (!empty($users)) {
        $adminUnit = $users[0];
        $query = $db->query("SELECT * FROM pengiriman_unit WHERE unit_id = ? AND status_pengiriman IN ('draft', 'perlu_revisi')", [$adminUnit['unit_id']]);
        $draftPengiriman = $query->getResultArray();

        echo "Draft/Revisi pengiriman for admin_unit:\n";
        if (empty($draftPengiriman)) {
            echo "No draft or revision pengiriman found!\n";

            // Check active year
            $query = $db->query("SELECT * FROM tahun_penilaian WHERE status_aktif = 1");
            $tahunAktif = $query->getRowArray();

            if ($tahunAktif) {
                echo "Active year found: {$tahunAktif['tahun']}\n";
                echo "Creating draft pengiriman...\n";

                $db->query(
                    "INSERT INTO pengiriman_unit (unit_id, tahun_penilaian_id, status_pengiriman, progress_persen, created_at, updated_at) VALUES (?, ?, 'draft', 0.0, NOW(), NOW())",
                    [$adminUnit['unit_id'], $tahunAktif['id']]
                );

                echo "Draft pengiriman created!\n";
            } else {
                echo "No active year found!\n";
            }
        } else {
            foreach ($draftPengiriman as $dp) {
                echo "ID: {$dp['id']}, Status: {$dp['status_pengiriman']}, Progress: {$dp['progress_persen']}%\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\nDebug complete.\n";
