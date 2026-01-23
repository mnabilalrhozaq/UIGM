<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-recycle"></i>
            <span>User System</span>
        </div>
    </div>
    
    <div class="user-info">
        <div class="user-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="user-details">
            <h4><?= session()->get('user')['nama_lengkap'] ?></h4>
            <p>Petugas Gedung</p>
            <small><?= session()->get('user')['nama_unit'] ?? 'Unit Kampus' ?></small>
        </div>
    </div>
    
    <div class="sidebar-content">
        <nav class="sidebar-nav">
            <ul>
                <li>
                    <a href="<?= base_url('/user/dashboard') ?>" class="<?= (uri_string() == 'user/dashboard' || uri_string() == 'user') ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-section">
                    <span>Waste Management</span>
                </li>
                
                <li>
                    <a href="<?= base_url('/user/waste') ?>" class="<?= (strpos(uri_string(), 'user/waste') === 0) ? 'active' : '' ?>">
                        <i class="fas fa-trash-alt"></i>
                        <span>Data Sampah</span>
                    </a>
                </li>
                
                <li class="nav-section">
                    <span>Akun</span>
                </li>
                
                <li>
                    <a href="<?= base_url('/user/profile') ?>" class="<?= (strpos(uri_string(), 'user/profile') === 0) ? 'active' : '' ?>">
                        <i class="fas fa-user-circle"></i>
                        <span>Edit Profil</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    
    <div class="sidebar-footer">
        <a href="<?= base_url('/auth/logout') ?>" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 280px;
    height: 100vh;
    background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
    color: white;
    display: flex;
    flex-direction: column;
    z-index: 1000;
}

.sidebar-header {
    padding: 25px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 20px;
    font-weight: 700;
}

.logo i {
    font-size: 28px;
    color: #27ae60;
}

.user-info {
    padding: 25px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 15px;
    flex-shrink: 0;
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.user-details h4 {
    margin: 0 0 5px 0;
    font-size: 16px;
    font-weight: 600;
}

.user-details p {
    margin: 0 0 3px 0;
    font-size: 12px;
    color: #27ae60;
    font-weight: 500;
}

.user-details small {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.7);
}

.sidebar-content {
    flex: 1;
    overflow-y: auto;
    padding: 20px 0;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin-bottom: 2px;
}

.nav-section {
    display: block;
    padding: 15px 20px 8px 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.6);
    letter-spacing: 1px;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 14px;
}

.sidebar-nav a:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding-left: 25px;
}

.sidebar-nav a.active {
    background: rgba(39, 174, 96, 0.2);
    color: #27ae60;
    border-right: 3px solid #27ae60;
    font-weight: 600;
}

.sidebar-nav a i {
    width: 18px;
    text-align: center;
    font-size: 16px;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    background: rgba(231, 76, 60, 0.1);
    color: #e74c3c;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
    width: 100%;
    box-sizing: border-box;
}

.logout-btn:hover {
    background: rgba(231, 76, 60, 0.2);
    color: #e74c3c;
    transform: translateX(5px);
}

.logout-btn i {
    font-size: 16px;
}

/* Scrollbar styling */
.sidebar-content::-webkit-scrollbar {
    width: 6px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 3px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}
</style>