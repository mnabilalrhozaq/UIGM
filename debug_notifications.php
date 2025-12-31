<?php

// Simple debug script to test notifications
require_once 'vendor/autoload.php';

// Load CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

// Test database connection
try {
    $db = \Config\Database::connect();
    echo "✓ Database connection successful\n";

    // Test notifications table
    $query = $db->query("SELECT COUNT(*) as count FROM notifikasi");
    $result = $query->getRow();
    echo "✓ Notifications table accessible, count: " . $result->count . "\n";

    // Test NotifikasiModel
    $notifikasiModel = new \App\Models\NotifikasiModel();
    $notifications = $notifikasiModel->findAll();
    echo "✓ NotifikasiModel working, found " . count($notifications) . " notifications\n";

    // Test with user filter
    $userNotifications = $notifikasiModel->where('user_id', 1)->findAll();
    echo "✓ User filter working, found " . count($userNotifications) . " notifications for user 1\n";

    // Test field mapping
    if (!empty($notifications)) {
        $firstNotif = $notifications[0];
        echo "✓ First notification fields:\n";
        echo "  - ID: " . ($firstNotif['id'] ?? 'missing') . "\n";
        echo "  - tipe_notifikasi: " . ($firstNotif['tipe_notifikasi'] ?? 'missing') . "\n";
        echo "  - tipe (alias): " . ($firstNotif['tipe'] ?? 'missing') . "\n";
        echo "  - judul: " . ($firstNotif['judul'] ?? 'missing') . "\n";
        echo "  - is_read: " . ($firstNotif['is_read'] ? 'true' : 'false') . "\n";
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "\nDebug complete.\n";
