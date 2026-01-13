<?php
/**
 * Dashboard User - UI GreenMetric POLBAN
 * Dashboard untuk user waste management
 */

// Safety checks for variables
$user = $user ?? ['nama_lengkap' => 'User'];
$unit = $unit ?? ['nama_unit' => 'Unit'];
$stats = $stats ?? [];
$wasteOverallStats = $wasteOverallStats ?? [];
$wasteStats = $wasteStats ?? [];
$recentActivities = $recentActivities ?? [];
$featureData = $featureData ?? [];

// Helper function untuk display stats
if (!function_exists('displayStat')) {
    function displayStat($stats, $key) {
        return $stats[$key] ?? 0;
    }
}

// Load feature helper
helper('feature');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard User' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_user') ?>
    
    <div class="main-content">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard User Waste Management</h1>
            <p>Kelola data waste management unit Anda</p>
            
            <!-- Development Info -->
            <?php if (ENVIRONMENT === 'development'): ?>
            <div class="feature-indicator mt-2">
                <small class="text-muted">
                    <i class="fas fa-cog text-info"></i> 
                    Dashboard dengan feature toggles aktif
                </small>
            </div>
            <?php endif; ?>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards - Feature Toggle: dashboard_statistics_cards -->
        <?php if (isFeatureEnabled('dashboard_statistics_cards', 'user')): ?>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'disetujui') ?></h3>
                    <p>Data Disetujui</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'perlu_revisi') ?></h3>
                    <p>Perlu Revisi</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'dikirim') ?></h3>
                    <p>Menunggu Review</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fas fa-save"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'draft') ?></h3>
                    <p>Draft</p>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Dashboard statistics are currently disabled by administrator.
        </div>
        <?php endif; ?>

        <!-- Waste Management Summary - Feature Toggle: dashboard_waste_summary -->
        <?php if (isFeatureEnabled('dashboard_waste_summary', 'user')): ?>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-trash-alt"></i>
                <h3>Ringkasan Waste Management</h3>
                
                <!-- Quick Actions -->
                <div class="quick-actions">
                    <a href="<?= base_url('/user/waste') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input Data
                    </a>
                    <a href="<?= base_url('/user/waste/export') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="waste-grid">
                    <?php if (!empty($wasteStats)): ?>
                        <?php foreach ($wasteStats as $jenis => $stats): ?>
                        <div class="waste-item">
                            <div class="waste-header">
                                <h5><?= $jenis ?></h5>
                                <span class="waste-total"><?= $stats['total'] ?> data</span>
                            </div>
                            
                            <div class="waste-stats">
                                <div class="waste-stat">
                                    <span class="stat-label">Disetujui</span>
                                    <span class="stat-value text-success"><?= $stats['disetujui'] ?></span>
                                </div>
                                <div class="waste-stat">
                                    <span class="stat-label">Revisi</span>
                                    <span class="stat-value text-warning"><?= $stats['perlu_revisi'] ?></span>
                                </div>
                                <div class="waste-stat">
                                    <span class="stat-label">Draft</span>
                                    <span class="stat-value text-muted"><?= $stats['draft'] ?></span>
                                </div>
                            </div>
                            
                            <a href="<?= base_url('/user/waste/' . urlencode($jenis)) ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> Lihat Data
                            </a>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Belum ada data waste management. Mulai dengan menginput data baru.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Waste management summary is currently disabled by administrator.
        </div>
        <?php endif; ?>

        <!-- Recent Activity - Feature Toggle: dashboard_recent_activity -->
        <?php if (isFeatureEnabled('dashboard_recent_activity', 'user') && !empty($recentActivities)): ?>
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h3>Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <?php foreach ($recentActivities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-<?= $activity['icon'] ?>"></i>
                        </div>
                        <div class="activity-content">
                            <p><?= $activity['message'] ?></p>
                            <small class="text-muted"><?= $activity['time'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Help Section -->
        <div class="help-section mt-4">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fas fa-question-circle"></i> Bantuan
                    </h6>
                    <p class="card-text">
                        Gunakan menu <strong>Waste Management</strong> untuk input data sampah. 
                        Data akan otomatis dihitung nilainya berdasarkan harga yang berlaku.
                    </p>
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Dashboard terakhir diperbarui: <?= date('d/m/Y H:i') ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Real-time Updates -->
    <?php if (isFeatureEnabled('real_time_updates', 'user')): ?>
    <script>
        // Auto refresh data based on feature configuration
        const realTimeConfig = <?= json_encode($featureData['real_time_config'] ?? ['enabled' => false]) ?>;
        
        if (realTimeConfig.enabled) {
            const refreshInterval = (realTimeConfig.refresh_interval || 30) * 1000;
            
            console.log('Real-time updates enabled. Refresh interval:', refreshInterval / 1000, 'seconds');
            
            setInterval(function() {
                fetch('<?= base_url('/user/dashboard/api-stats') ?>')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateDashboardStats(data.stats);
                            console.log('Dashboard stats updated at', data.stats.timestamp);
                        }
                    })
                    .catch(error => console.log('Auto-refresh failed:', error));
            }, refreshInterval);
        }
        
        function updateDashboardStats(stats) {
            // Update stat cards with new data
            const statCards = document.querySelectorAll('.stat-content h3');
            if (stats.wasteOverallStats) {
                const overall = stats.wasteOverallStats;
                if (statCards[0]) statCards[0].textContent = overall.disetujui || 0;
                if (statCards[1]) statCards[1].textContent = overall.perlu_revisi || 0;
                if (statCards[2]) statCards[2].textContent = overall.dikirim || 0;
                if (statCards[3]) statCards[3].textContent = overall.draft || 0;
            }
        }
    </script>
    <?php endif; ?>
</body>
</html>

<style>
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
}

.dashboard-header {
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #e9ecef;
}

.dashboard-header h1 {
    color: #2c3e50;
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 5px;
}

.dashboard-header p {
    color: #7f8c8d;
    font-size: 16px;
    margin: 0;
}

.feature-indicator {
    margin-top: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    display: flex;
    align-items: center;
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 24px;
    color: white;
}

.stat-icon.green { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-icon.orange { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
.stat-icon.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon.purple { background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); }

.stat-content h3 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.stat-content p {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
    font-weight: 500;
}

.card {
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 15px 15px 0 0 !important;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.quick-actions {
    display: flex;
    gap: 10px;
}

.waste-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.waste-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.waste-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.waste-header h5 {
    margin: 0;
    color: #2c3e50;
}

.waste-total {
    background: #007bff;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.waste-stats {
    margin-bottom: 15px;
}

.waste-stat {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
}

.activity-list {
    max-height: 300px;
    overflow-y: auto;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.activity-item:last-child {
    border-bottom: none;
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
    margin-right: 15px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content p {
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 10px;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeaa7;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .waste-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        flex-direction: column;
        gap: 5px;
    }
}
</style>