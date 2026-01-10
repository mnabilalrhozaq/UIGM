<?php
/**
 * Helper function untuk menampilkan nilai stats dengan fallback
 */
function displayStat($stats, $key, $default = 0) {
    return isset($stats[$key]) ? $stats[$key] : $default;
}

// Load feature toggle helper
helper('FeatureToggleHelper');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard User' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_user') ?>
    
    <div class="main-content">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard User Waste Management</h1>
            <p>Kelola data waste management unit Anda</p>
            
            <!-- Feature Toggle Indicator (for demo) -->
            <div class="feature-indicator">
                <small class="text-muted">
                    <i class="fas fa-toggle-on text-success"></i> 
                    Feature toggles active - controlled by Admin Pusat
                </small>
            </div>
        </div>

        <!-- Statistics Cards - Feature Toggle: dashboard_statistics_cards -->
        <?php if (isFeatureEnabled('dashboard_statistics_cards', 'user')): ?>
        <div class="stats-grid">
            <?php 
            $config = getFeatureConfig('dashboard_statistics_cards');
            $showTotal = $config['show_total'] ?? true;
            $showApproved = $config['show_approved'] ?? true;
            $showPending = $config['show_pending'] ?? true;
            ?>
            
            <?php if ($showApproved): ?>
            <div class="stat-card">
                <div class="stat-icon green">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'disetujui') ?></h3>
                    <p>Data Disetujui</p>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'perlu_revisi') ?></h3>
                    <p>Perlu Revisi</p>
                </div>
            </div>
            
            <?php if ($showPending): ?>
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-content">
                    <h3><?= displayStat($wasteOverallStats, 'dikirim') ?></h3>
                    <p>Menunggu Review</p>
                </div>
            </div>
            <?php endif; ?>
            
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
                
                <!-- Quick Actions - Feature Toggle: sidebar_quick_actions -->
                <?php if (isFeatureEnabled('sidebar_quick_actions', 'user')): ?>
                <div class="quick-actions">
                    <?php if (canPerformAction('waste_input_form', 'allow_edit', 'user')): ?>
                    <a href="<?= base_url('/user/waste') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Input Data
                    </a>
                    <?php endif; ?>
                    
                    <?php if (isFeatureEnabled('waste_export_feature', 'user')): ?>
                    <a href="<?= base_url('/user/waste/export') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php 
                $wasteConfig = getFeatureConfig('dashboard_waste_summary');
                $showCharts = $wasteConfig['show_charts'] ?? true;
                $showDetails = $wasteConfig['show_details'] ?? true;
                ?>
                
                <div class="waste-grid">
                    <?php foreach ($wasteStats as $jenis => $stats): ?>
                    <div class="waste-item">
                        <div class="waste-header">
                            <h5><?= $jenis ?></h5>
                            <span class="waste-total"><?= $stats['total'] ?> data</span>
                        </div>
                        
                        <?php if ($showDetails): ?>
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
                        <?php endif; ?>
                        
                        <!-- Value Calculation - Feature Toggle: waste_value_calculation -->
                        <?php if (isFeatureEnabled('waste_value_calculation', 'user')): ?>
                        <div class="waste-value">
                            <small class="text-muted">Total Nilai: 
                                <strong class="text-success">Rp <?= number_format($stats['total_nilai'] ?? 0, 0, ',', '.') ?></strong>
                            </small>
                            
                            <?php if (getFeatureConfigValue('waste_value_calculation', 'show_price_breakdown', false)): ?>
                            <div class="price-breakdown mt-2">
                                <small class="text-info">
                                    <i class="fas fa-info-circle"></i>
                                    Harga per kg: Rp <?= number_format($stats['harga_per_kg'] ?? 0, 0, ',', '.') ?>
                                </small>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        
                        <a href="<?= base_url('/user/waste/' . urlencode($jenis)) ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i> Lihat Data
                        </a>
                    </div>
                    <?php endforeach; ?>
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
        <?php if (isFeatureEnabled('dashboard_recent_activity', 'user')): ?>
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h3>Aktivitas Terbaru</h3>
            </div>
            <div class="card-body">
                <?php 
                $maxItems = getMaxItems('dashboard_recent_activity', 5);
                // Sample recent activities for demo
                $recentActivities = [
                    ['icon' => 'plus', 'message' => 'Data plastik 15kg berhasil ditambahkan', 'time' => '2 jam yang lalu'],
                    ['icon' => 'check', 'message' => 'Data kertas 10kg disetujui admin', 'time' => '5 jam yang lalu'],
                    ['icon' => 'edit', 'message' => 'Data logam 3kg perlu revisi', 'time' => '1 hari yang lalu'],
                ];
                $recentActivities = array_slice($recentActivities, 0, $maxItems);
                ?>
                
                <?php if (!empty($recentActivities)): ?>
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
                <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada aktivitas terbaru</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Detailed Statistics - Feature Toggle: detailed_statistics -->
        <?php if (isFeatureEnabled('detailed_statistics', 'user')): ?>
        <div class="card mt-4">
            <div class="card-header">
                <i class="fas fa-chart-bar"></i>
                <h3>Statistik Detail</h3>
            </div>
            <div class="card-body">
                <?php 
                $statsConfig = getFeatureConfig('detailed_statistics');
                $showTrends = $statsConfig['show_trends'] ?? true;
                $showComparisons = $statsConfig['show_comparisons'] ?? true;
                ?>
                
                <div class="row">
                    <?php if ($showTrends): ?>
                    <div class="col-md-6">
                        <h6>Trend Bulanan</h6>
                        <div class="trend-chart">
                            <canvas id="trendChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($showComparisons): ?>
                    <div class="col-md-6">
                        <h6>Perbandingan Jenis Sampah</h6>
                        <div class="comparison-chart">
                            <canvas id="comparisonChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Help Tooltips - Feature Toggle: help_tooltips -->
        <?php if (isFeatureEnabled('help_tooltips', 'user')): ?>
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
                    
                    <?php if (isFeatureEnabled('advanced_menu_items', 'user')): ?>
                    <div class="advanced-help mt-3">
                        <h6 class="text-warning">
                            <i class="fas fa-star"></i> Fitur Lanjutan
                        </h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Bulk operations untuk input data massal</li>
                            <li><i class="fas fa-check text-success"></i> Advanced filters untuk pencarian detail</li>
                            <li><i class="fas fa-check text-success"></i> Export data dalam berbagai format</li>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Feature Toggle Status (for demo purposes) -->
        <div class="card mt-4 border-secondary">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-toggle-on"></i> Feature Toggle Status
                    <small class="text-muted">(Demo - akan disembunyikan di production)</small>
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Enabled Features:</h6>
                        <ul class="list-unstyled">
                            <?php if (isFeatureEnabled('dashboard_statistics_cards', 'user')): ?>
                            <li><i class="fas fa-check text-success"></i> Dashboard Statistics Cards</li>
                            <?php endif; ?>
                            <?php if (isFeatureEnabled('dashboard_waste_summary', 'user')): ?>
                            <li><i class="fas fa-check text-success"></i> Waste Management Summary</li>
                            <?php endif; ?>
                            <?php if (isFeatureEnabled('dashboard_recent_activity', 'user')): ?>
                            <li><i class="fas fa-check text-success"></i> Recent Activity Feed</li>
                            <?php endif; ?>
                            <?php if (isFeatureEnabled('waste_value_calculation', 'user')): ?>
                            <li><i class="fas fa-check text-success"></i> Value Calculation</li>
                            <?php endif; ?>
                            <?php if (isFeatureEnabled('help_tooltips', 'user')): ?>
                            <li><i class="fas fa-check text-success"></i> Help Tooltips</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Configuration:</h6>
                        <ul class="list-unstyled">
                            <li><small>Max recent items: <?= getMaxItems('dashboard_recent_activity', 5) ?></small></li>
                            <li><small>Show price breakdown: <?= getFeatureConfigValue('waste_value_calculation', 'show_price_breakdown', false) ? 'Yes' : 'No' ?></small></li>
                            <li><small>Real-time updates: <?= isFeatureEnabled('real_time_updates', 'user') ? 'Enabled' : 'Disabled' ?></small></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Real-time Updates - Feature Toggle: real_time_updates -->
    <?php if (isFeatureEnabled('real_time_updates', 'user')): ?>
    <script>
        // Auto refresh data every configured interval
        const refreshInterval = <?= getFeatureConfigValue('real_time_updates', 'refresh_interval', 30) ?> * 1000;
        
        console.log('Real-time updates enabled. Refresh interval:', refreshInterval / 1000, 'seconds');
        
        setInterval(function() {
            // Refresh statistics without full page reload
            fetch('<?= base_url('/user/api/dashboard-stats') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateDashboardStats(data.stats);
                        console.log('Dashboard stats updated');
                    }
                })
                .catch(error => console.log('Auto-refresh failed:', error));
        }, refreshInterval);
        
        function updateDashboardStats(stats) {
            // Update stat cards with new data
            // Implementation depends on your specific needs
            console.log('New stats received:', stats);
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

.waste-value {
    margin-bottom: 15px;
    padding: 10px;
    background: #e8f5e8;
    border-radius: 5px;
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

.price-breakdown {
    padding: 5px 10px;
    background: #e3f2fd;
    border-radius: 3px;
}

.feature-disabled {
    opacity: 0.5;
    pointer-events: none;
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