<?php
/**
 * Dashboard Admin Pusat - UI GreenMetric POLBAN
 * Dashboard untuk admin pusat
 */

// Safety checks for variables
$stats = $stats ?? [];
$recentSubmissions = $recentSubmissions ?? [];
$recentPriceChanges = $recentPriceChanges ?? [];
$wasteByType = $wasteByType ?? [];

/**
 * Helper function untuk menampilkan nilai stats dengan fallback
 */
function displayStat($stats, $key, $default = 0) {
    return isset($stats[$key]) ? $stats[$key] : $default;
}

/**
 * Format number untuk display
 */
function formatNumber($number) {
    return number_format($number, 0, ',', '.');
}

/**
 * Format currency untuk display
 */
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Admin Pusat' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_admin_pusat') ?>
    
    <div class="main-content">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin Pusat</h1>
            <p>Monitoring dan Kelola Sistem Waste Management POLBAN</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($stats, 'total_users') ?></h3>
                    <p>Total Users Aktif</p>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($stats, 'menunggu_review') ?></h3>
                    <p>Menunggu Review</p>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($stats, 'disetujui') ?></h3>
                    <p>Data Disetujui</p>
                </div>
            </div>
            
            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($stats, 'perlu_revisi') ?></h3>
                    <p>Perlu Revisi</p>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-weight-hanging"></i>
                </div>
                <div class="stat-content">
                    <h3><?= formatNumber(displayStat($stats, 'total_berat')) ?> kg</h3>
                    <p>Total Berat Sampah</p>
                </div>
            </div>
            
            <div class="stat-card secondary">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <h3><?= formatCurrency(displayStat($stats, 'total_nilai')) ?></h3>
                    <p>Total Nilai Jual</p>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="content-grid">
            <!-- Recent Waste Submissions -->
            <div class="content-main">
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-inbox"></i> Data Waste Terbaru Menunggu Review</h3>
                        <div class="card-actions">
                            <a href="<?= base_url('/admin-pusat/review') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentSubmissions)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Jenis Sampah</th>
                                            <th>Berat (kg)</th>
                                            <th>Nilai Jual</th>
                                            <th>User</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentSubmissions as $submission): ?>
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary"><?= $submission['jenis_sampah'] ?></span>
                                            </td>
                                            <td><?= number_format($submission['berat_kg'], 2) ?> kg</td>
                                            <td><?= formatCurrency($submission['nilai_jual']) ?></td>
                                            <td><?= $submission['nama_lengkap'] ?? $submission['username'] ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($submission['created_at'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('/admin-pusat/review/detail/' . $submission['id']) ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada data menunggu review</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="content-sidebar">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    </div>
                    <div class="card-body">
                        <div class="quick-actions-vertical">
                            <a href="<?= base_url('/admin-pusat/manajemen-harga') ?>" class="quick-action-btn">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Kelola Harga Sampah</span>
                            </a>
                            <a href="<?= base_url('/admin-pusat/feature-toggle') ?>" class="quick-action-btn">
                                <i class="fas fa-toggle-on"></i>
                                <span>Feature Toggle</span>
                            </a>
                            <a href="<?= base_url('/admin-pusat/review') ?>" class="quick-action-btn">
                                <i class="fas fa-clipboard-check"></i>
                                <span>Review Data Waste</span>
                            </a>
                            <a href="<?= base_url('/admin-pusat/laporan') ?>" class="quick-action-btn">
                                <i class="fas fa-chart-bar"></i>
                                <span>Generate Laporan</span>
                            </a>
                            <a href="<?= base_url('/admin-pusat/user-management') ?>" class="quick-action-btn">
                                <i class="fas fa-users-cog"></i>
                                <span>Kelola Users</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Price Changes -->
                <div class="card">
                    <div class="card-header">
                        <h3><i class="fas fa-history"></i> Perubahan Harga Terbaru</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recentPriceChanges)): ?>
                            <div class="activity-list">
                                <?php foreach ($recentPriceChanges as $change): ?>
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        <i class="fas fa-<?= $change['aksi'] === 'update' ? 'edit' : 'plus' ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <p><strong><?= $change['jenis_sampah'] ?></strong></p>
                                        <small class="text-muted">
                                            <?= $change['admin_nama'] ?> • <?= date('d/m/Y H:i', strtotime($change['created_at'])) ?>
                                        </small>
                                        <br>
                                        <span class="badge bg-<?= $change['aksi'] === 'update' ? 'warning' : 'success' ?>">
                                            <?= ucfirst($change['aksi']) ?>
                                        </span>
                                        <div class="mt-2">
                                            <?php if ($change['harga_lama']): ?>
                                                <small class="text-muted text-decoration-line-through">
                                                    <?= formatCurrency($change['harga_lama']) ?>
                                                </small>
                                                <br>
                                            <?php endif; ?>
                                            <strong class="text-success">
                                                <?= formatCurrency($change['harga_baru']) ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="<?= base_url('/admin-pusat/manajemen-harga/logs') ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-history"></i> Lihat Semua Log
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">Belum ada perubahan harga</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waste by Type Summary -->
        <?php if (!empty($wasteByType)): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-chart-pie"></i> Ringkasan Waste Management per Jenis</h3>
            </div>
            <div class="card-body">
                <div class="waste-grid">
                    <?php foreach ($wasteByType as $waste): ?>
                    <div class="waste-item">
                        <div class="waste-header">
                            <h5><?= $waste['jenis_sampah'] ?></h5>
                            <span class="waste-total"><?= $waste['total_records'] ?> records</span>
                        </div>
                        <div class="waste-stats">
                            <div class="waste-stat">
                                <span class="stat-label">Total Berat:</span>
                                <strong><?= number_format($waste['total_berat'], 2) ?> kg</strong>
                            </div>
                            <div class="waste-stat">
                                <span class="stat-label">Total Nilai:</span>
                                <strong><?= formatCurrency($waste['total_nilai']) ?></strong>
                            </div>
                            <div class="waste-stat">
                                <span class="stat-label">Disetujui:</span>
                                <span class="badge bg-success"><?= $waste['disetujui'] ?></span>
                            </div>
                            <div class="waste-stat">
                                <span class="stat-label">Menunggu:</span>
                                <span class="badge bg-warning"><?= $waste['menunggu_review'] ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto refresh every 5 minutes
        setTimeout(function() {
            location.reload();
        }, 300000);

        // Animate progress bars on load
        document.addEventListener('DOMContentLoaded', function() {
            const progressBars = document.querySelectorAll('.progress-bar');
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

<style>
/* ===== MAIN LAYOUT ===== */
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f8f9fa;
}

.main-content {
    margin-left: 280px;
    padding: 30px;
    min-height: 100vh;
    max-width: calc(100vw - 280px);
    overflow-x: hidden;
}

/* ===== DASHBOARD HEADER ===== */
.dashboard-header {
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #e9ecef;
}

.dashboard-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 28px;
    font-weight: 700;
}

.dashboard-header p {
    color: #6c757d;
    font-size: 16px;
    margin: 0;
}

/* ===== STATISTICS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
    min-height: 100px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-card.primary { border-left-color: #007bff; }
.stat-card.success { border-left-color: #28a745; }
.stat-card.warning { border-left-color: #ffc107; }
.stat-card.danger { border-left-color: #dc3545; }
.stat-card.info { border-left-color: #17a2b8; }
.stat-card.secondary { border-left-color: #6c757d; }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.danger .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.stat-card.info .stat-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.stat-card.secondary .stat-icon { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

.stat-content {
    flex: 1;
}

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.stat-content p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
    font-size: 14px;
}

/* ===== CONTENT GRID ===== */
.content-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 30px;
    margin-bottom: 30px;
}

.content-main {
    min-width: 0; /* Prevents overflow */
}

.content-sidebar {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ===== CARDS ===== */
.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 20px 25px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border: none;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-actions .btn {
    border-radius: 8px;
    font-weight: 500;
}

.card-body {
    padding: 25px;
}

/* ===== TABLES ===== */
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #2c3e50;
    padding: 15px;
}

.table td {
    border: none;
    padding: 15px;
    vertical-align: middle;
}

.table tbody tr {
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

/* ===== QUICK ACTIONS ===== */
.quick-actions-vertical {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    text-decoration: none;
    color: #2c3e50;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.quick-action-btn:hover {
    background: white;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.2);
    color: #007bff;
    text-decoration: none;
}

.quick-action-btn i {
    width: 20px;
    text-align: center;
    font-size: 16px;
}

.quick-action-btn span {
    font-weight: 500;
    font-size: 14px;
}

/* ===== ACTIVITY LIST ===== */
.activity-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.activity-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.activity-icon {
    width: 40px;
    height: 40px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 5px 0;
    font-size: 14px;
}

.activity-content small {
    font-size: 12px;
}

/* ===== WASTE GRID ===== */
.waste-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.waste-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #007bff;
}

.waste-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e9ecef;
}

.waste-header h5 {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
}

.waste-total {
    font-size: 12px;
    color: #6c757d;
    background: white;
    padding: 4px 8px;
    border-radius: 12px;
}

.waste-stats {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.waste-stat {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.stat-label {
    color: #6c757d;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0;
    font-size: 16px;
}

/* ===== BUTTONS ===== */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr 300px;
    }
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .content-sidebar {
        order: -1;
    }
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
        max-width: 100vw;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .dashboard-header h1 {
        font-size: 24px;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .waste-grid {
        grid-template-columns: 1fr;
    }
}
</style>