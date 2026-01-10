<?php

/**
 * Script untuk memperbaiki struktur tabel pengiriman_unit
 * Menambahkan kolom yang hilang sesuai struktur minimal yang diperlukan
 */

// Database configuration - sesuaikan dengan .env Anda
$config = [
    'hostname' => 'localhost',
    'database' => 'uigm_polban',
    'username' => 'root',
    'password' => '',
    'port'     => 3306,
];

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

    echo "✅ Koneksi database berhasil\n";
    echo "Database: {$config['database']}\n\n";

    // Cek struktur tabel saat ini
    echo "📋 Struktur tabel pengiriman_unit saat ini:\n";
    $stmt = $pdo->query("DESCRIBE pengiriman_unit");
    $columns = $stmt->fetchAll();
    
    $existingColumns = [];
    foreach ($columns as $column) {
        $existingColumns[] = $column['Field'];
        echo "   - {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']} {$column['Default']}\n";
    }
    echo "\n";

    // Kolom yang diperlukan sesuai struktur minimal
    $requiredColumns = [
        'id' => 'int(11) UNSIGNED NOT NULL AUTO_INCREMENT',
        'unit_id' => 'int(11) UNSIGNED NOT NULL',
        'tahun_penilaian_id' => 'int(11) UNSIGNED NOT NULL', // Seharusnya tahun_id tapi sudah ada sebagai tahun_penilaian_id
        'status_pengiriman' => "enum('draft','dikirim','review','perlu_revisi','disetujui','ditolak') NOT NULL DEFAULT 'draft'",
        'created_by' => 'int(11) UNSIGNED DEFAULT NULL',
        'created_at' => 'datetime DEFAULT current_timestamp()',
        'updated_at' => 'datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()'
    ];

    // Kolom tambahan yang berguna
    $additionalColumns = [
        'progress_persen' => 'decimal(5,2) NOT NULL DEFAULT 0.00',
        'versi' => 'int(11) NOT NULL DEFAULT 1',
        'tanggal_kirim' => 'datetime DEFAULT NULL',
        'tanggal_review' => 'datetime DEFAULT NULL',
        'tanggal_disetujui' => 'datetime DEFAULT NULL',
        'catatan_admin' => 'text DEFAULT NULL',
        'catatan_revisi' => 'text DEFAULT NULL',
        'reviewer_id' => 'int(11) UNSIGNED DEFAULT NULL'
    ];

    $allColumns = array_merge($requiredColumns, $additionalColumns);

    // Cek kolom yang hilang
    $missingColumns = [];
    foreach ($allColumns as $columnName => $columnDef) {
        if (!in_array($columnName, $existingColumns)) {
            $missingColumns[$columnName] = $columnDef;
        }
    }

    if (empty($missingColumns)) {
        echo "✅ Semua kolom yang diperlukan sudah ada!\n\n";
    } else {
        echo "⚠️  Kolom yang hilang:\n";
        foreach ($missingColumns as $columnName => $columnDef) {
            echo "   - $columnName\n";
        }
        echo "\n";

        // Tambahkan kolom yang hilang
        echo "🔧 Menambahkan kolom yang hilang...\n";
        
        foreach ($missingColumns as $columnName => $columnDef) {
            try {
                $sql = "ALTER TABLE `pengiriman_unit` ADD COLUMN `$columnName` $columnDef";
                echo "   Menambahkan kolom: $columnName... ";
                $pdo->exec($sql);
                echo "✅\n";
            } catch (PDOException $e) {
                echo "❌ Error: " . $e->getMessage() . "\n";
            }
        }
        echo "\n";
    }

    // Cek dan update enum status_pengiriman
    echo "🔧 Memperbarui enum status_pengiriman...\n";
    try {
        $sql = "ALTER TABLE `pengiriman_unit` MODIFY COLUMN `status_pengiriman` enum('draft','dikirim','review','perlu_revisi','disetujui','ditolak') NOT NULL DEFAULT 'draft'";
        $pdo->exec($sql);
        echo "✅ Enum status_pengiriman diperbarui\n\n";
    } catch (PDOException $e) {
        echo "⚠️  Enum sudah up-to-date atau error: " . $e->getMessage() . "\n\n";
    }

    // Tambahkan foreign key constraints jika belum ada
    echo "🔧 Menambahkan foreign key constraints...\n";
    
    $constraints = [
        'fk_pengiriman_created_by' => "ALTER TABLE `pengiriman_unit` ADD CONSTRAINT `fk_pengiriman_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE",
        'fk_pengiriman_reviewer' => "ALTER TABLE `pengiriman_unit` ADD CONSTRAINT `fk_pengiriman_reviewer` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE"
    ];

    foreach ($constraints as $constraintName => $sql) {
        try {
            echo "   Menambahkan constraint: $constraintName... ";
            $pdo->exec($sql);
            echo "✅\n";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "⚠️  Sudah ada\n";
            } else {
                echo "❌ Error: " . $e->getMessage() . "\n";
            }
        }
    }
    echo "\n";

    // Update existing records untuk set created_by
    echo "🔧 Memperbarui data existing...\n";
    try {
        $sql = "UPDATE pengiriman_unit pu 
                JOIN users u ON u.unit_id = pu.unit_id AND u.role = 'admin_unit' 
                SET pu.created_by = u.id 
                WHERE pu.created_by IS NULL";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $affected = $stmt->rowCount();
        echo "✅ Updated $affected records dengan created_by\n\n";
    } catch (PDOException $e) {
        echo "⚠️  Error updating existing records: " . $e->getMessage() . "\n\n";
    }

    // Tampilkan struktur tabel setelah update
    echo "📋 Struktur tabel pengiriman_unit setelah update:\n";
    $stmt = $pdo->query("DESCRIBE pengiriman_unit");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $column) {
        $status = in_array($column['Field'], array_keys($requiredColumns)) ? '✅' : '📝';
        echo "   $status {$column['Field']} ({$column['Type']}) {$column['Null']} {$column['Key']} {$column['Default']}\n";
    }
    echo "\n";

    // Tampilkan sample data
    echo "📊 Sample data pengiriman_unit:\n";
    $stmt = $pdo->query("
        SELECT 
            pu.id,
            pu.unit_id,
            u.nama_unit,
            pu.tahun_penilaian_id,
            pu.status_pengiriman,
            pu.progress_persen,
            pu.versi,
            pu.created_by,
            creator.username as created_by_user,
            pu.reviewer_id,
            reviewer.username as reviewer_user,
            pu.created_at
        FROM pengiriman_unit pu
        LEFT JOIN unit u ON u.id = pu.unit_id
        LEFT JOIN users creator ON creator.id = pu.created_by
        LEFT JOIN users reviewer ON reviewer.id = pu.reviewer_id
        ORDER BY pu.id
        LIMIT 5
    ");
    
    $data = $stmt->fetchAll();
    if (empty($data)) {
        echo "   Tidak ada data\n";
    } else {
        foreach ($data as $row) {
            echo "   ID: {$row['id']} | Unit: {$row['nama_unit']} | Status: {$row['status_pengiriman']} | Created by: {$row['created_by_user']}\n";
        }
    }
    echo "\n";

    // Verifikasi struktur minimal
    echo "✅ VERIFIKASI STRUKTUR MINIMAL:\n";
    $requiredFields = ['id', 'unit_id', 'tahun_penilaian_id', 'status_pengiriman', 'created_by', 'created_at'];
    $currentColumns = array_column($columns, 'Field');
    
    $allRequired = true;
    foreach ($requiredFields as $field) {
        $exists = in_array($field, $currentColumns);
        echo "   " . ($exists ? '✅' : '❌') . " $field\n";
        if (!$exists) $allRequired = false;
    }
    
    if ($allRequired) {
        echo "\n🎉 STRUKTUR MINIMAL LENGKAP!\n";
        echo "Tabel pengiriman_unit sudah memiliki semua kolom yang diperlukan:\n";
        echo "- id (Primary Key)\n";
        echo "- unit_id (FK ke unit.id)\n";
        echo "- tahun_penilaian_id (FK ke tahun_penilaian.id) - sebagai tahun_id\n";
        echo "- status_pengiriman (dikirim | diterima | ditolak)\n";
        echo "- created_by (user unit)\n";
        echo "- created_at (timestamp)\n";
    } else {
        echo "\n❌ STRUKTUR BELUM LENGKAP!\n";
        echo "Masih ada kolom yang hilang.\n";
    }

} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Script selesai dijalankan!\n";