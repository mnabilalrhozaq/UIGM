<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple - No Duplication</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background: #f5f5f5;
        }
        .test-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .marker {
            background: #ffeb3b;
            padding: 10px;
            border-left: 4px solid #ff9800;
            margin: 10px 0;
        }
        .data-box {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <!-- UNIQUE MARKER: <?= uniqid('TEST-', true) ?> -->
    
    <div class="test-box">
        <h1>🧪 Test Simple View - Manajemen Harga</h1>
        <p><strong>Request ID:</strong> <?= $requestId ?? 'NO-ID' ?></p>
        <p><strong>Timestamp:</strong> <?= date('Y-m-d H:i:s') ?></p>
        
        <div class="marker">
            <strong>MARKER 1:</strong> Jika ini muncul 2x, berarti view di-render 2x
        </div>
    </div>

    <div class="test-box">
        <h2>📊 Statistics Data</h2>
        <div class="data-box">
            <p><strong>Total Jenis Sampah:</strong> <?= $statistics['total'] ?? 0 ?></p>
            <p><strong>Harga Aktif:</strong> <?= $statistics['aktif'] ?? 0 ?></p>
            <p><strong>Bisa Dijual:</strong> <?= $statistics['bisa_dijual'] ?? 0 ?></p>
            <p><strong>Perubahan Hari Ini:</strong> <?= $statistics['perubahan_hari_ini'] ?? 0 ?></p>
        </div>
    </div>

    <div class="test-box">
        <h2>📝 Recent Changes Data</h2>
        <div class="data-box">
            <p><strong>Count:</strong> <?= count($recentChanges ?? []) ?></p>
            <p><strong>Status:</strong> <?= !empty($recentChanges) ? 'HAS DATA ✅' : 'EMPTY ❌' ?></p>
            
            <?php if (!empty($recentChanges)): ?>
                <h3>Data Preview:</h3>
                <pre><?= print_r($recentChanges, true) ?></pre>
            <?php else: ?>
                <p style="color: red;">⚠️ No recent changes data available</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="test-box">
        <h2>🔗 Navigation</h2>
        <p>
            <a href="<?= base_url('/admin-pusat/manajemen-harga') ?>" style="color: #2196F3;">← Kembali ke View Normal</a>
        </p>
    </div>

    <!-- UNIQUE MARKER END: <?= uniqid('END-', true) ?> -->
    
    <script>
        console.log('Test Simple View Loaded');
        console.log('Request ID:', '<?= $requestId ?? 'NO-ID' ?>');
        console.log('Recent Changes Count:', <?= count($recentChanges ?? []) ?>);
    </script>
</body>
</html>
