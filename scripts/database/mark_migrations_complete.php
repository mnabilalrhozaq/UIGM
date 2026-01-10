<?php

/**
 * Script untuk menandai migration sebagai sudah selesai
 * Karena tabel sudah ada tetapi migration belum tercatat
 */

// Database configuration
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

    // Cek apakah tabel migrations ada
    $stmt = $pdo->query("SHOW TABLES LIKE 'migrations'");
    if ($stmt->rowCount() == 0) {
        echo "📋 Membuat tabel migrations...\n";
        $createMigrationsTable = "
            CREATE TABLE `migrations` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `version` varchar(255) NOT NULL,
                `class` varchar(255) NOT NULL,
                `group` varchar(255) NOT NULL,
                `namespace` varchar(255) NOT NULL,
                `time` int(11) NOT NULL,
                `batch` int(11) unsigned NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        $pdo->exec($createMigrationsTable);
        echo "✅ Tabel migrations dibuat\n";
    }

    // Daftar migration yang perlu ditandai sebagai selesai
    $migrations = [
        [
            'version' => '2024-12-31-100000',
            'class' => 'App\\Database\\Migrations\\CreateJenisSampahTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040226',
            'class' => 'App\\Database\\Migrations\\CreateUsersTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040248',
            'class' => 'App\\Database\\Migrations\\CreateTahunPenilaianTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040305',
            'class' => 'App\\Database\\Migrations\\CreateIndikatorTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040336',
            'class' => 'App\\Database\\Migrations\\CreateUnitTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040353',
            'class' => 'App\\Database\\Migrations\\CreatePengirimanUnitTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040412',
            'class' => 'App\\Database\\Migrations\\CreateReviewKategoriTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040433',
            'class' => 'App\\Database\\Migrations\\CreateNotifikasiTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ],
        [
            'version' => '2025-12-30-040519',
            'class' => 'App\\Database\\Migrations\\CreateRiwayatVersiTable',
            'group' => 'default',
            'namespace' => 'App',
            'time' => time(),
            'batch' => 1
        ]
    ];

    echo "📝 Menandai migration sebagai selesai...\n";

    foreach ($migrations as $migration) {
        // Cek apakah migration sudah ada
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE version = ?");
        $stmt->execute([$migration['version']]);
        $exists = $stmt->fetchColumn();

        if ($exists == 0) {
            $stmt = $pdo->prepare("
                INSERT INTO migrations (version, class, `group`, namespace, time, batch) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $migration['version'],
                $migration['class'],
                $migration['group'],
                $migration['namespace'],
                $migration['time'],
                $migration['batch']
            ]);
            echo "   ✅ {$migration['version']} - {$migration['class']}\n";
        } else {
            echo "   ⚠️  {$migration['version']} sudah ada\n";
        }
    }

    echo "\n📊 Status migration saat ini:\n";
    $stmt = $pdo->query("SELECT version, class, batch FROM migrations ORDER BY version");
    $results = $stmt->fetchAll();

    foreach ($results as $row) {
        echo "   ✅ {$row['version']} - {$row['class']} (Batch: {$row['batch']})\n";
    }

    echo "\n🎉 Semua migration telah ditandai sebagai selesai!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n✅ Script selesai!\n";