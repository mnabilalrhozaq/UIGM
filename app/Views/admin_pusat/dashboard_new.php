<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kampus POLBAN - Admin Pusat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            color: #333;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            height: 100vh;
            background: linear-gradient(180deg, #4a90e2 0%, #357abd 100%);
            color: white;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }

        .sidebar-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .sidebar-subtitle {
            font-size: 12px;
            opacity: 0.8;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover,
        .menu-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }

        .menu-item i {
            width: 20px;
            margin-right: 15px;
            font-size: 16px;
        }

        .menu-item span {
            font-size: 14px;
            font-weight: 500;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 1px solid #e0e0e0;
            border-radius: 25px;
            width: 300px;
            font-size: 14px;
            background: #f8f9fa;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .notification-icon {
            position: relative;
            background: #f8f9fa;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-icon:hover {
            background: #e9ecef;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: #4a90e2;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .user-name {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Content Area */
        .content {
            padding: 30px;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 25px;
        }

        /* Stats Cards */
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.blue {
            background: #4a90e2;
        }

        .stat-icon.green {
            background: #28a745;
        }

        .stat-icon.orange {
            background: #fd7e14;
        }

        .stat-icon.purple {
            background: #6f42c1;
        }

        .stat-content h4 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-content p {
            font-size: 14px;
            color: #666;
            margin: 0;
        }

        /* Activity Feed */
        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 35px;
            height: 35px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4a90e2;
            font-size: 14px;
        }

        .activity-content h5 {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .activity-content p {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 12px;
            color: #999;
        }

        /* Shortcuts */
        .shortcuts-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .shortcut-item {
            background: white;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .shortcut-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            color: #4a90e2;
        }

        .shortcut-icon {
            width: 60px;
            height: 60px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: #4a90e2;
            transition: all 0.3s ease;
        }

        .shortcut-item:hover .shortcut-icon {
            background: #4a90e2;
            color: white;
        }

        .shortcut-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .shortcut-desc {
            font-size: 12px;
            color: #666;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .table td {
            font-size: 14px;
            color: #666;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-lengkap {
            background: #d4edda;
            color: #155724;
        }

        .status-proses {
            background: #fff3cd;
            color: #856404;
        }

        .status-perlu-revisi {
            background: #f8d7da;
            color: #721c24;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #4a90e2;
            color: white;
        }

        .btn-primary:hover {
            background: #357abd;
        }

        /* Progress Bars */
        .progress-item {
            margin-bottom: 20px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .progress-label {
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        .progress-value {
            font-size: 14px;
            font-weight: 600;
            color: #4a90e2;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #f0f0f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4a90e2, #357abd);
            border-radius: 4px;
            transition: width 0.8s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .header {
                padding: 15px 20px;
            }

            .search-box input {
                width: 200px;
            }

            .content {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .shortcuts-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-leaf"></i>
            </div>
            <div class="sidebar-title">GreenMetric Dashboard</div>
            <div class="sidebar-subtitle">POLBAN</div>
        </div>

        <nav class="sidebar-menu">
            <a href="/admin-pusat/dashboard" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="/admin-pusat/monitoring" class="menu-item">
                <i class="fas fa-building"></i>
                <span>Profil Kampus</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-database"></i>
                <span>Data Penilaian</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-chart-line"></i>
                <span>Indikator GreenMetric</span>
            </a>
            <a href="/report" class="menu-item">
                <i class="fas fa-file-alt"></i>
                <span>Laporan & Dokumen</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-history"></i>
                <span>Riwayat Penilaian</span>
            </a>
            <a href="#" class="menu-item">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <a href="/auth/logout" class="menu-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <h1 class="header-title">Dashboard Kampus POLBAN</h1>
            <div class="header-right">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
                <div class="notification-icon">
                    <i class="fas fa-bell"></i>
                    <?php if (!empty($notifikasi)): ?>
                        <span class="notification-badge"><?= count($notifikasi) ?></span>
                    <?php endif; ?>
                </div>
                <div class="user-profile">
                    <div class="user-avatar">AP</div>
                    <span class="user-name">Admin POLBAN</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <h4>7,850.75</h4>
                        <p>Total Skor GreenMetric<br><small>dari 10000 | +5.2 in terakhir</small></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-content">
                        <h4>#12</h4>
                        <p>Peringkat Nasional<br><small>dari 0+ Institut Global</small></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange">
                        <i class="fas fa-earth-americas"></i>
                    </div>
                    <div class="stat-content">
                        <h4>#220</h4>
                        <p>Peringkat Dunia<br><small>dari 0+ di Dunia</small></p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="stat-content">
                        <h4>2024</h4>
                        <p>Tahun Penilaian Aktif<br><small>Status Verifikasi Data</small></p>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Left Column -->
                <div>
                    <!-- Activity & Notifications -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-bell"></i>
                            <h3>Aktivitas Terbaru & Notifikasi</h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($notifikasi)): ?>
                                <?php foreach (array_slice($notifikasi, 0, 4) as $notif): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <div class="activity-content">
                                            <h5>Pembaruan data energi terbaru</h5>
                                            <p><?= date('d M Y H:i', strtotime($notif['created_at'])) ?> lalu</p>
                                            <p><?= $notif['pesan'] ?></p>
                                        </div>
                                        <div class="activity-time">17:04</div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5>Pembaruan data energi terbaru</h5>
                                        <p>29 Dec 2025 17:04 lalu</p>
                                        <p>Energy & Climate Change - Penggunaan Energi Terbarukan</p>
                                    </div>
                                    <div class="activity-time">17:04</div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5>Pembaruan data energi terbaru</h5>
                                        <p>29 Dec 2025 17:04 lalu</p>
                                        <p>Waste - Program Daur Ulang Sampah</p>
                                    </div>
                                    <div class="activity-time">17:04</div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5>Pembaruan data energi terbaru</h5>
                                        <p>29 Dec 2025 17:04 lalu</p>
                                        <p>Transportation - Kendaraan Ramah Lingkungan</p>
                                    </div>
                                    <div class="activity-time">17:04</div>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-edit"></i>
                                    </div>
                                    <div class="activity-content">
                                        <h5>Pembaruan data energi terbaru</h5>
                                        <p>29 Dec 2025 17:04 lalu</p>
                                        <p>Setting & Infrastructure - Rasio Ruang Terbuka</p>
                                    </div>
                                    <div class="activity-time">17:04</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Detail Penilaian -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            <h3>Detail Penilaian POLBAN</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Indikator</th>
                                            <th>Sub-Indikator</th>
                                            <th>Skor POLBAN</th>
                                            <th>Target</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Setting & Infrastructure</td>
                                            <td>2 sub-indikator</td>
                                            <td><strong>265</strong></td>
                                            <td>300</td>
                                            <td><span class="status-badge status-lengkap">LENGKAP</span></td>
                                            <td><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Energy & Climate Change</td>
                                            <td>1 sub-indikator</td>
                                            <td><strong>180</strong></td>
                                            <td>250</td>
                                            <td><span class="status-badge status-proses">PROSES</span></td>
                                            <td><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Waste</td>
                                            <td>1 sub-indikator</td>
                                            <td><strong>270</strong></td>
                                            <td>300</td>
                                            <td><span class="status-badge status-lengkap">LENGKAP</span></td>
                                            <td><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                        <tr>
                                            <td>Transportation</td>
                                            <td>1 sub-indikator</td>
                                            <td><strong>120</strong></td>
                                            <td>200</td>
                                            <td><span class="status-badge status-perlu-revisi">PERLU REVISI</span></td>
                                            <td><a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Shortcuts -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-rocket"></i>
                            <h3>Shortcut & Bantuan Cepat</h3>
                        </div>
                        <div class="card-body">
                            <div class="shortcuts-grid">
                                <a href="#" class="shortcut-item">
                                    <div class="shortcut-icon">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <div class="shortcut-title">Input Data Baru</div>
                                </a>
                                <a href="/report" class="shortcut-item">
                                    <div class="shortcut-icon">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div class="shortcut-title">Lihat Laporan</div>
                                </a>
                                <a href="#" class="shortcut-item">
                                    <div class="shortcut-icon">
                                        <i class="fas fa-question-circle"></i>
                                    </div>
                                    <div class="shortcut-title">Panduan Pengisian</div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Status Kelengkapan -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-chart-pie"></i>
                            <h3>Status Kelengkapan Data</h3>
                            <span style="margin-left: auto; font-size: 12px; color: #666;">Progress per Indikator</span>
                        </div>
                        <div class="card-body">
                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Setting & Infrastructure</span>
                                    <span class="progress-value">100.0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 100%"></div>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">2/2 sub-indikator lengkap</p>
                            </div>

                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Energy & Climate Change</span>
                                    <span class="progress-value">0.0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 0%"></div>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">0/1 sub-indikator lengkap</p>
                            </div>

                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Waste</span>
                                    <span class="progress-value">100.0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 100%"></div>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">1/1 sub-indikator lengkap</p>
                            </div>

                            <div class="progress-item">
                                <div class="progress-header">
                                    <span class="progress-label">Transportation</span>
                                    <span class="progress-value">0.0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 0%"></div>
                                </div>
                                <p style="font-size: 12px; color: #666; margin-top: 5px;">0/1 sub-indikator lengkap</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Pendukung -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-folder"></i>
                            <h3>Dokumen Pendukung</h3>
                        </div>
                        <div class="card-body">
                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                    <div>
                                        <div style="font-weight: 500; font-size: 14px;">Panduan Energi.pdf</div>
                                        <div style="font-size: 12px; color: #666;">Terverifikasi oleh GreenMetric</div>
                                    </div>
                                    <i class="fas fa-check-circle" style="color: #28a745; margin-left: auto;"></i>
                                </div>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <i class="fas fa-file-excel" style="color: #28a745;"></i>
                                    <div>
                                        <div style="font-weight: 500; font-size: 14px;">Data Konsumsi Energi.xlsx</div>
                                        <div style="font-size: 12px; color: #666;">Menunggu verifikasi</div>
                                    </div>
                                    <i class="fas fa-clock" style="color: #ffc107; margin-left: auto;"></i>
                                </div>
                            </div>

                            <div style="text-align: center; margin-top: 20px;">
                                <div style="border: 2px dashed #ddd; padding: 30px; border-radius: 8px;">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 24px; color: #999; margin-bottom: 10px;"></i>
                                    <p style="color: #666; margin: 0; font-size: 14px;">Drag and drop files here<br>or</p>
                                    <button class="btn btn-primary" style="margin-top: 10px;">
                                        <i class="fas fa-plus"></i> Upload Dokumen Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animate progress bars on load
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
        }

        // Auto refresh notifications
        setInterval(() => {
            // Check for new notifications
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.unread_count > 0) {
                        document.querySelector('.notification-badge').textContent = data.unread_count;
                    }
                })
                .catch(error => console.log('Notification check failed'));
        }, 60000);
    </script>
</body>

</html>