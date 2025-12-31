<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 4px solid #5c8cbf;
        }

        .stat-card .icon {
            font-size: 32px;
            color: #5c8cbf;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background: #5c8cbf;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        .role-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .role-stat {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #5c8cbf;
        }

        .role-stat .number {
            font-size: 24px;
            font-weight: bold;
            color: #5c8cbf;
        }

        .role-stat .label {
            color: #666;
            font-size: 14px;
            margin-top: 5px;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            padding: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-info h4 {
            color: #333;
            margin-bottom: 5px;
            font-size: 16px;
        }

        .activity-info p {
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-draft {
            background: #f5f5f5;
            color: #666;
        }

        .status-dikirim {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-review {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-disetujui {
            background: #e8f5e8;
            color: #388e3c;
        }

        .status-perlu_revisi {
            background: #ffebee;
            color: #d32f2f;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .empty-state i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .admin-tools {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .admin-tool {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .admin-tool:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: #5c8cbf;
        }

        .admin-tool .icon {
            font-size: 32px;
            color: #5c8cbf;
            margin-bottom: 10px;
        }

        .admin-tool .title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .admin-tool .desc {
            font-size: 14px;
            color: #666;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-crown"></i> Dashboard Super Admin</h1>
                <p>Tahun Penilaian: <?= $tahun['tahun'] ?> - <?= $tahun['nama_periode'] ?></p>
            </div>
            <div class="header-info">
                <div class="user-info">
                    <i class="fas fa-user-crown"></i>
                    <span><?= $user['nama_lengkap'] ?></span>
                </div>
                <a href="/auth/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- System Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="number"><?= $stats['total_users'] ?></div>
                <div class="label">Total Pengguna</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-building"></i></div>
                <div class="number"><?= $stats['total_units'] ?></div>
                <div class="label">Total Unit</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-file-alt"></i></div>
                <div class="number"><?= $stats['total_submissions'] ?></div>
                <div class="label">Total Pengiriman</div>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fas fa-list"></i></div>
                <div class="number"><?= $stats['total_categories'] ?></div>
                <div class="label">Kategori UIGM</div>
            </div>
        </div>

        <!-- Admin Tools -->
        <div class="admin-tools">
            <a href="/admin-pusat/dashboard" class="admin-tool">
                <div class="icon"><i class="fas fa-tachometer-alt"></i></div>
                <div class="title">Dashboard Admin Pusat</div>
                <div class="desc">Akses dashboard admin pusat</div>
            </a>
            <a href="/admin-pusat/monitoring" class="admin-tool">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="title">Monitoring Unit</div>
                <div class="desc">Monitor progress semua unit</div>
            </a>
            <a href="/super-admin/users" class="admin-tool">
                <div class="icon"><i class="fas fa-users-cog"></i></div>
                <div class="title">Kelola Pengguna</div>
                <div class="desc">Manajemen akun pengguna</div>
            </a>
            <a href="/super-admin/units" class="admin-tool">
                <div class="icon"><i class="fas fa-building"></i></div>
                <div class="title">Kelola Unit</div>
                <div class="desc">Manajemen unit/fakultas</div>
            </a>
            <a href="/super-admin/tahun-penilaian" class="admin-tool">
                <div class="icon"><i class="fas fa-calendar"></i></div>
                <div class="title">Tahun Penilaian</div>
                <div class="desc">Kelola periode penilaian</div>
            </a>
            <a href="/report" class="admin-tool">
                <div class="icon"><i class="fas fa-file-export"></i></div>
                <div class="title">Laporan</div>
                <div class="desc">Export dan cetak laporan</div>
            </a>
        </div>

        <!-- User Statistics by Role -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i> Statistik Pengguna per Role
            </div>
            <div class="card-body">
                <div class="role-stats">
                    <div class="role-stat">
                        <div class="number"><?= $stats['role_stats']['admin_pusat'] ?></div>
                        <div class="label">Admin Pusat</div>
                    </div>
                    <div class="role-stat">
                        <div class="number"><?= $stats['role_stats']['admin_unit'] ?></div>
                        <div class="label">Admin Unit</div>
                    </div>
                    <div class="role-stat">
                        <div class="number"><?= $stats['role_stats']['super_admin'] ?></div>
                        <div class="label">Super Admin</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history"></i> Aktivitas Terbaru
            </div>
            <div class="card-body">
                <?php if (empty($activities)): ?>
                    <div class="empty-state">
                        <i class="fas fa-history"></i>
                        <p>Belum ada aktivitas sistem</p>
                    </div>
                <?php else: ?>
                    <ul class="activity-list">
                        <?php foreach ($activities as $activity): ?>
                            <li class="activity-item">
                                <div class="activity-info">
                                    <h4><?= $activity['nama_unit'] ?: 'Unit Tidak Diketahui' ?></h4>
                                    <p>Progress: <?= number_format($activity['progress_persen'], 1) ?>%</p>
                                    <p>Terakhir diupdate: <?= date('d/m/Y H:i', strtotime($activity['updated_at'])) ?></p>
                                </div>
                                <div>
                                    <span class="status-badge status-<?= $activity['status_pengiriman'] ?>">
                                        <?= ucfirst(str_replace('_', ' ', $activity['status_pengiriman'])) ?>
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh setiap 10 menit
        setTimeout(function() {
            location.reload();
        }, 600000);
    </script>
</body>

</html>