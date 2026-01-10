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
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_user') ?>
    
    <div class="main-content">
        <div class="dashboard-header">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard User Waste Management</h1>
            <p>Kelola data waste management unit Anda</p>
            
            <!-- Feature Toggle Status Indicator -->
            <?php if (ENVIRONMENT === 'development'): ?>
            <div class="feature-indicator mt-2">
                <small class="text-muted">
                    <i class="fas fa-toggle-on text-success"></i> 
                    Feature toggles: <?= isFeatureEnabled('dashboard_statistics_cards', 'user') ? 'Active' : 'Inactive' ?>
                </small>
            </div>
            <?php endif; ?>
        </div>

        <!-- Statistics Cards - Feature Toggle: dashboard_statistics_cards -->
        <?php if (isFeatureEnabled('dashboard_statistics_cards', 'user')): ?>
        <div class="stats-grid">
            <?php 
            $statsConfig = $featureData['stats_config'] ?? [];
            $showApproved = $statsConfig['show_approved'] ?? true;
            $showPending = $statsConfig['show_pending'] ?? true;
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
                <h3><i class="fas fa-trash-alt"></i> Ringkasan Waste Management</h3>
                
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
                $wasteConfig = $featureData['waste_config'] ?? [];
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
                            <div class="price-breakdown">
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
        <?php if (isFeatureEnabled('dashboard_recent_activity', 'user') && !empty($recentActivities)): ?>
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-history"></i> Aktivitas Terbaru</h3>
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

        <!-- Help Tooltips - Feature Toggle: help_tooltips -->
        <?php if (isFeatureEnabled('help_tooltips', 'user')): ?>
        <div class="help-section">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-question-circle"></i> Bantuan
                    </h6>
                    <p class="card-text">
                        Gunakan menu <strong>Waste Management</strong> untuk input data sampah. 
                        Data akan otomatis dihitung nilainya berdasarkan harga yang berlaku.
                    </p>
                    
                    <?php if (isFeatureEnabled('advanced_menu_items', 'user')): ?>
                    <div class="advanced-help">
                        <h6>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Real-time Updates - Feature Toggle: real_time_updates -->
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