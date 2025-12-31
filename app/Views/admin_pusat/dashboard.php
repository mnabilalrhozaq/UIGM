<?= $this->extend('layouts/admin_pusat_new') ?>

<?= $this->section('content') ?>

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
            <h4><?= isset($tahun['tahun']) ? $tahun['tahun'] : '2024' ?></h4>
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
                <?php if (isset($notifikasi) && !empty($notifikasi)): ?>
                    <?php foreach (array_slice($notifikasi, 0, 4) as $notif): ?>
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div class="activity-content">
                                <h5><?= htmlspecialchars($notif['judul'] ?? 'Pembaruan data energi terbaru', ENT_QUOTES, 'UTF-8') ?></h5>
                                <p><?= date('d M Y H:i', strtotime($notif['created_at'])) ?> lalu</p>
                                <p><?= htmlspecialchars($notif['pesan'] ?? 'Energy & Climate Change - Penggunaan Energi Terbarukan', ENT_QUOTES, 'UTF-8') ?></p>
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
                            <?php if (isset($pengirimanPending) && !empty($pengirimanPending)): ?>
                                <?php foreach ($pengirimanPending as $pengiriman): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($pengiriman['nama_unit'] ?? 'Setting & Infrastructure', ENT_QUOTES, 'UTF-8') ?></td>
                                        <td>2 sub-indikator</td>
                                        <td><strong><?= number_format($pengiriman['progress_persen'] ?? 265, 0) ?></strong></td>
                                        <td>300</td>
                                        <td>
                                            <?php
                                            $status = $pengiriman['status_pengiriman'] ?? 'lengkap';
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($status) {
                                                case 'disetujui':
                                                    $statusClass = 'status-lengkap';
                                                    $statusText = 'LENGKAP';
                                                    break;
                                                case 'dikirim':
                                                case 'review':
                                                    $statusClass = 'status-proses';
                                                    $statusText = 'PROSES';
                                                    break;
                                                case 'perlu_revisi':
                                                    $statusClass = 'status-perlu-revisi';
                                                    $statusText = 'PERLU REVISI';
                                                    break;
                                                default:
                                                    $statusClass = 'status-lengkap';
                                                    $statusText = 'LENGKAP';
                                            }
                                            ?>
                                            <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                        </td>
                                        <td><a href="<?= base_url('/admin-pusat/review/' . ($pengiriman['id'] ?? '1')) ?>" class="btn btn-primary"><i class="fas fa-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
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
                            <?php endif; ?>
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
                    <a href="<?= base_url('/admin-unit/dashboard') ?>" class="shortcut-item">
                        <div class="shortcut-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="shortcut-title">Input Data Baru</div>
                    </a>
                    <a href="<?= base_url('/report') ?>" class="shortcut-item">
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
                <?php if (isset($institutionalProgress) && !empty($institutionalProgress)): ?>
                    <?php foreach ($institutionalProgress as $progress): ?>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="progress-label"><?= htmlspecialchars($progress['nama_kategori'] ?? 'Setting & Infrastructure', ENT_QUOTES, 'UTF-8') ?></span>
                                <span class="progress-value"><?= number_format($progress['progress_percent'] ?? 100, 1) ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $progress['progress_percent'] ?? 100 ?>%"></div>
                            </div>
                            <p style="font-size: 12px; color: #666; margin-top: 5px;">
                                <?= $progress['approved'] ?? 2 ?>/<?= ($progress['approved'] ?? 2) + ($progress['in_review'] ?? 0) + ($progress['needs_revision'] ?? 0) ?> sub-indikator lengkap
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
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
                <?php endif; ?>
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

<?= $this->endSection() ?>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        color: #333;
        min-height: 100vh;
    }

    .header {
        background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
        color: white;
        padding: 25px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
    }

    .header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .header-content {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .header-left h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .header-left p {
        font-size: 16px;
        opacity: 0.9;
        font-weight: 400;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 15px;
        background: rgba(255, 255, 255, 0.15);
        padding: 12px 20px;
        border-radius: 50px;
        backdrop-filter: blur(10px);
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .logout-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 12px 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 25px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .logout-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 30px;
    }

    .nav-section {
        margin-bottom: 40px;
    }

    .nav-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .nav-link {
        background: white;
        color: #5c8cbf;
        padding: 20px 25px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(92, 140, 191, 0.1), transparent);
        transition: left 0.5s ease;
    }

    .nav-link:hover::before {
        left: 100%;
    }

    .nav-link:hover {
        background: #5c8cbf;
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(92, 140, 191, 0.3);
    }

    .nav-link i {
        font-size: 20px;
        width: 24px;
    }

    .badge {
        background: #dc3545;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-left: auto;
    }

    .filter-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
        border: 1px solid rgba(92, 140, 191, 0.1);
    }

    .filter-section h5 {
        color: #5c8cbf;
        margin-bottom: 25px;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        align-items: end;
    }

    .filter-group {
        position: relative;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 10px;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        outline: none;
        border-color: #5c8cbf;
        background: white;
        box-shadow: 0 0 0 4px rgba(92, 140, 191, 0.1);
    }

    .btn {
        padding: 15px 25px;
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        transition: all 0.3s ease;
        transform: translate(-50%, -50%);
    }

    .btn:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-primary {
        background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(92, 140, 191, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(92, 140, 191, 0.4);
    }

    .stats-section {
        margin-bottom: 40px;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 25px;
    }

    .stat-card {
        background: white;
        padding: 30px 25px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        text-align: center;
        border-top: 5px solid #5c8cbf;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(92, 140, 191, 0.05) 0%, rgba(74, 123, 167, 0.05) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card .icon {
        font-size: 40px;
        color: #5c8cbf;
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }

    .stat-card .number {
        font-size: 36px;
        font-weight: 800;
        color: #333;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .stat-card .label {
        color: #666;
        font-size: 14px;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .content-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        margin-bottom: 40px;
    }

    .card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid rgba(92, 140, 191, 0.1);
    }

    .card-header {
        background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
        color: white;
        padding: 25px 30px;
        font-size: 20px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-body {
        padding: 30px;
    }

    .progress-section {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 40px;
    }

    .category-progress-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
    }

    .progress-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 25px;
        border-radius: 15px;
        border-left: 5px solid #5c8cbf;
        transition: all 0.3s ease;
    }

    .progress-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .category-name {
        font-weight: 700;
        color: #333;
        font-size: 16px;
    }

    .progress-percent {
        font-weight: 700;
        color: #5c8cbf;
        font-size: 18px;
    }

    .progress-bar-custom {
        width: 100%;
        height: 12px;
        background: #e9ecef;
        border-radius: 6px;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #5c8cbf, #4a7ba7);
        border-radius: 6px;
        transition: width 0.8s ease;
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .progress-details {
        color: #666;
        font-size: 13px;
        font-weight: 500;
    }

    .pengiriman-list {
        list-style: none;
    }

    .pengiriman-item {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .pengiriman-item:last-child {
        border-bottom: none;
    }

    .pengiriman-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .pengiriman-info h4 {
        color: #333;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .pengiriman-info p {
        color: #666;
        font-size: 14px;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pengiriman-actions {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-dikirim {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        color: #1976d2;
    }

    .status-review {
        background: linear-gradient(135deg, #fff3e0 0%, #ffcc02 100%);
        color: #f57c00;
    }

    .status-disetujui {
        background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
        color: #388e3c;
    }

    .status-perlu_revisi {
        background: linear-gradient(135deg, #ffebee 0%, #ffcdd2 100%);
        color: #d32f2f;
    }

    .review-btn {
        background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .review-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(92, 140, 191, 0.4);
    }

    .notifikasi-list {
        list-style: none;
    }

    .notifikasi-item {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        border-left: 4px solid #5c8cbf;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .notifikasi-item:last-child {
        border-bottom: none;
    }

    .notifikasi-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }

    .notifikasi-item h5 {
        color: #333;
        margin-bottom: 8px;
        font-size: 15px;
        font-weight: 600;
    }

    .notifikasi-item p {
        color: #666;
        font-size: 14px;
        margin-bottom: 8px;
        line-height: 1.5;
    }

    .notifikasi-time {
        color: #999;
        font-size: 12px;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 60px 40px;
        color: #666;
    }

    .empty-state i {
        font-size: 64px;
        color: #ddd;
        margin-bottom: 20px;
        display: block;
    }

    .empty-state p {
        font-size: 16px;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 20px;
            text-align: center;
            padding: 0 20px;
        }

        .container {
            padding: 30px 20px;
        }

        .content-section {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .nav-links {
            grid-template-columns: 1fr;
        }

        .filter-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
        }

        .category-progress-grid {
            grid-template-columns: 1fr;
        }

        .pengiriman-item {
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
            <div class="header-left">
                <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin Pusat</h1>
                <p>Tahun Penilaian: <?= $tahun['tahun'] ?> - <?= $tahun['nama_periode'] ?></p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <span><?= $user['nama_lengkap'] ?></span>
                </div>
                <a href="/auth/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Navigation Section -->
        <div class="nav-section">
            <div class="nav-links">
                <a href="/admin-pusat/dashboard" class="nav-link">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
                <a href="/admin-pusat/monitoring" class="nav-link">
                    <i class="fas fa-chart-line"></i>
                    Monitoring Unit
                </a>
                <a href="/admin-pusat/notifikasi" class="nav-link">
                    <i class="fas fa-bell"></i>
                    Notifikasi
                    <?php if (!empty($notifikasi)): ?>
                        <span class="badge"><?= count($notifikasi) ?></span>
                    <?php endif; ?>
                </a>
                <a href="/report" class="nav-link">
                    <i class="fas fa-file-export"></i>
                    Laporan
                </a>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fas fa-filter"></i>Filter Data</h5>
            <form method="GET" action="/admin-pusat/dashboard" class="filter-form">
                <div class="filter-grid">
                    <div class="filter-group">
                        <label>Tahun Penilaian:</label>
                        <select name="tahun" class="form-control">
                            <option value="">Semua Tahun</option>
                            <?php if (isset($allTahun)): ?>
                                <?php foreach ($allTahun as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= (isset($filters['tahun']) && $filters['tahun'] == $t['id']) ? 'selected' : '' ?>>
                                        <?= $t['tahun'] ?> - <?= $t['nama_periode'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Unit:</label>
                        <select name="unit" class="form-control">
                            <option value="">Semua Unit</option>
                            <?php if (isset($allUnits)): ?>
                                <?php foreach ($allUnits as $u): ?>
                                    <option value="<?= $u['id'] ?>" <?= (isset($filters['unit']) && $filters['unit'] == $u['id']) ? 'selected' : '' ?>>
                                        <?= $u['nama_unit'] ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="draft" <?= (isset($filters['status']) && $filters['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                            <option value="dikirim" <?= (isset($filters['status']) && $filters['status'] == 'dikirim') ? 'selected' : '' ?>>Dikirim</option>
                            <option value="review" <?= (isset($filters['status']) && $filters['status'] == 'review') ? 'selected' : '' ?>>Review</option>
                            <option value="perlu_revisi" <?= (isset($filters['status']) && $filters['status'] == 'perlu_revisi') ? 'selected' : '' ?>>Perlu Revisi</option>
                            <option value="disetujui" <?= (isset($filters['status']) && $filters['status'] == 'disetujui') ? 'selected' : '' ?>>Disetujui</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistics Section -->
        <div class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-building"></i></div>
                    <div class="number"><?= $stats['total_unit'] ?? 0 ?></div>
                    <div class="label">Total Unit</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-paper-plane"></i></div>
                    <div class="number"><?= ($stats['menunggu_review'] ?? 0) ?></div>
                    <div class="label">Menunggu Review</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                    <div class="number"><?= $stats['disetujui'] ?? 0 ?></div>
                    <div class="label">Disetujui</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-edit"></i></div>
                    <div class="number"><?= $stats['perlu_revisi'] ?? 0 ?></div>
                    <div class="label">Perlu Revisi</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-chart-pie"></i></div>
                    <div class="number"><?= $stats['progress_keseluruhan'] ?? 0 ?>%</div>
                    <div class="label">Progress Keseluruhan</div>
                </div>
                <div class="stat-card">
                    <div class="icon"><i class="fas fa-clock"></i></div>
                    <div class="number"><?= $stats['belum_kirim'] ?? 0 ?></div>
                    <div class="label">Belum Kirim</div>
                </div>
            </div>
        </div>

        <!-- Institutional Progress -->
        <?php if (!empty($institutionalProgress)): ?>
            <div class="progress-section">
                <div class="card-header" style="background: none; color: #5c8cbf; padding: 0 0 20px 0;">
                    <i class="fas fa-chart-bar"></i> Progress Institusi per Kategori
                </div>
                <div class="category-progress-grid">
                    <?php foreach ($institutionalProgress as $progress): ?>
                        <div class="progress-item">
                            <div class="progress-header">
                                <span class="category-name"><?= $progress['kode_kategori'] ?> - <?= $progress['nama_kategori'] ?></span>
                                <span class="progress-percent"><?= $progress['progress_percent'] ?>%</span>
                            </div>
                            <div class="progress-bar-custom">
                                <div class="progress-fill" style="width: <?= $progress['progress_percent'] ?>%"></div>
                            </div>
                            <div class="progress-details">
                                <i class="fas fa-check-circle" style="color: #28a745;"></i> Disetujui: <?= $progress['approved'] ?> |
                                <i class="fas fa-clock" style="color: #ffc107;"></i> Review: <?= $progress['in_review'] ?> |
                                <i class="fas fa-edit" style="color: #dc3545;"></i> Revisi: <?= $progress['needs_revision'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="content-section">
            <!-- Pengiriman Pending -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-inbox"></i> Data Perlu Review
                </div>
                <div class="card-body">
                    <?php if (empty($pengirimanPending)): ?>
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>Tidak ada data yang perlu direview</p>
                        </div>
                    <?php else: ?>
                        <ul class="pengiriman-list">
                            <?php foreach ($pengirimanPending as $pengiriman): ?>
                                <li class="pengiriman-item">
                                    <div class="pengiriman-info">
                                        <h4><?= $pengiriman['nama_unit'] ?></h4>
                                        <p>
                                            <i class="fas fa-code"></i><?= $pengiriman['kode_unit'] ?> |
                                            <i class="fas fa-building"></i><?= ucfirst($pengiriman['tipe_unit'] ?? 'Unit') ?>
                                        </p>
                                        <p>
                                            <i class="fas fa-clock"></i>Dikirim: <?= date('d/m/Y H:i', strtotime($pengiriman['tanggal_kirim'])) ?>
                                        </p>
                                        <p>
                                            <i class="fas fa-chart-pie"></i>Progress: <?= number_format($pengiriman['progress_persen'], 1) ?>%
                                        </p>
                                    </div>
                                    <div class="pengiriman-actions">
                                        <span class="status-badge status-<?= $pengiriman['status_pengiriman'] ?>">
                                            <?= ucfirst(str_replace('_', ' ', $pengiriman['status_pengiriman'])) ?>
                                        </span>
                                        <a href="/admin-pusat/review/<?= $pengiriman['id'] ?>" class="review-btn">
                                            <i class="fas fa-eye"></i> Review Detail
                                        </a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Notifikasi -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bell"></i> Notifikasi Terbaru
                </div>
                <div class="card-body">
                    <?php if (empty($notifikasi)): ?>
                        <div class="empty-state">
                            <i class="fas fa-bell-slash"></i>
                            <p>Tidak ada notifikasi baru</p>
                        </div>
                    <?php else: ?>
                        <ul class="notifikasi-list">
                            <?php foreach (array_slice($notifikasi, 0, 5) as $notif): ?>
                                <li class="notifikasi-item">
                                    <h5><?= $notif['judul'] ?></h5>
                                    <p><?= $notif['pesan'] ?></p>
                                    <div class="notifikasi-time">
                                        <i class="fas fa-clock"></i>
                                        <?= date('d/m/Y H:i', strtotime($notif['created_at'])) ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php if (count($notifikasi) > 5): ?>
                            <div style="text-align: center; margin-top: 20px;">
                                <a href="/admin-pusat/notifikasi" class="review-btn">
                                    <i class="fas fa-bell"></i> Lihat Semua Notifikasi
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto refresh setiap 5 menit
        setTimeout(function() {
            location.reload();
        }, 300000);

        // Mark notifications as read when clicked
        document.querySelectorAll('.notifikasi-item').forEach(item => {
            item.addEventListener('click', function() {
                this.style.opacity = '0.7';
            });
        });

        // Auto-submit filter form when selection changes
        document.querySelectorAll('.filter-form select').forEach(select => {
            select.addEventListener('change', function() {
                // Optional: Auto-submit on change
                // this.form.submit();
            });
        });

        // Real-time notification check
        function checkNotifications() {
            fetch('/api/notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.unread_count > 0) {
                        const badge = document.querySelector('.nav-link .badge');
                        if (badge) {
                            badge.textContent = data.unread_count;
                        } else {
                            const notifLink = document.querySelector('a[href="/admin-pusat/notifikasi"]');
                            if (notifLink && !notifLink.querySelector('.badge')) {
                                notifLink.innerHTML += ` <span class="badge">${data.unread_count}</span>`;
                            }
                        }
                    }
                })
                .catch(error => console.log('Notification check failed:', error));
        }

        // Check notifications every 2 minutes
        setInterval(checkNotifications, 120000);

        // Show loading state for review buttons
        document.querySelectorAll('.review-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            });
        });

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards on load
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Animate progress bars
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
</body>

</html>