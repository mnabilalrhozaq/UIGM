<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <i class="fas fa-shield-alt"></i>
        </div>
        <div class="sidebar-title">Admin Pusat</div>
        <div class="sidebar-subtitle">UI GreenMetric POLBAN</div>
    </div>

    <nav class="sidebar-menu">
        <a href="<?= base_url('/admin-pusat/dashboard') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/dashboard') !== false) ? 'active' : '' ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <!-- Data Management -->
        <div class="menu-section">
            <div class="menu-section-title">
                <i class="fas fa-database"></i>
                Data Management
            </div>
            
            <a href="<?= base_url('/admin-pusat/waste') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/waste') !== false) ? 'active' : '' ?>">
                <i class="fas fa-trash-alt"></i>
                <span>Waste Management</span>
            </a>
            
            <a href="<?= base_url('/admin-pusat/manajemen-harga') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/manajemen-harga') !== false) ? 'active' : '' ?>">
                <i class="fas fa-money-bill-wave"></i>
                <span>Manajemen Harga</span>
            </a>
            
            <a href="<?= base_url('/admin-pusat/feature-toggle') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/feature-toggle') !== false) ? 'active' : '' ?>">
                <i class="fas fa-toggle-on"></i>
                <span>Feature Toggle</span>
                <small class="menu-subtitle">Control UI Features</small>
            </a>
        </div>

        <!-- Reports & Analytics -->
        <div class="menu-section">
            <div class="menu-section-title">
                <i class="fas fa-chart-bar"></i>
                Reports & Analytics
            </div>
            
            <a href="<?= base_url('/admin-pusat/laporan') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/laporan') !== false && strpos(current_url(), 'laporan-waste') === false) ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span>Laporan & Monitoring</span>
            </a>
            
            <a href="<?= base_url('/admin-pusat/laporan-waste') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/laporan-waste') !== false) ? 'active' : '' ?>">
                <i class="fas fa-chart-pie"></i>
                <span>Laporan Waste</span>
            </a>
        </div>

        <!-- System -->
        <div class="menu-section">
            <div class="menu-section-title">
                <i class="fas fa-cog"></i>
                System
            </div>
            
            <a href="<?= base_url('/admin-pusat/user-management') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/user-management') !== false) ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
            
            <a href="<?= base_url('/admin-pusat/pengaturan') ?>" class="menu-item <?= (strpos(current_url(), '/admin-pusat/pengaturan') !== false) ? 'active' : '' ?>">
                <i class="fas fa-cogs"></i>
                <span>Pengaturan</span>
            </a>
        </div>

        <a href="<?= base_url('/auth/logout') ?>" class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</div>

<style>
.sidebar {
    width: 280px;
    height: 100vh;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar-header {
    padding: 30px 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
}

.sidebar-logo {
    font-size: 40px;
    color: #ffd700;
    margin-bottom: 10px;
}

.sidebar-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 5px;
}

.sidebar-subtitle {
    font-size: 14px;
    opacity: 0.8;
}

.sidebar-menu {
    padding: 20px 0;
}

.menu-section {
    margin-bottom: 30px;
}

.menu-section-title {
    padding: 15px 20px 10px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.7;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.menu-item:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border-left-color: #ffd700;
    transform: translateX(5px);
}

.menu-item.active {
    background: rgba(255, 215, 0, 0.2);
    color: white;
    border-left-color: #ffd700;
}

.menu-item i {
    width: 20px;
    margin-right: 15px;
    font-size: 16px;
}

.menu-item span {
    font-size: 14px;
    font-weight: 500;
    flex: 1;
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

/* Scrollbar styling */
.sidebar::-webkit-scrollbar {
    width: 6px;
}

.sidebar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .sidebar.show {
        transform: translateX(0);
    }
}
</style>