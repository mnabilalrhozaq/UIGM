<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route
$routes->get('/', 'Home::index');

// Auth Routes (Public)
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->get('test-login', 'Auth::testLogin');
    $routes->post('process-login', 'Auth::processLogin');
    $routes->get('logout', 'Auth::logout');
});

// ================================================
// ADMIN ROUTES (Role: admin_pusat, super_admin)
// ================================================
$routes->group('admin-pusat', ['filter' => 'role:admin_pusat,super_admin'], function ($routes) {
    // Load all admin routes from separate files
    require APPPATH . 'Config/Routes/Admin/dashboard.php';
    require APPPATH . 'Config/Routes/Admin/harga.php';
    require APPPATH . 'Config/Routes/Admin/feature_toggle.php';
    require APPPATH . 'Config/Routes/Admin/user_management.php';
    require APPPATH . 'Config/Routes/Admin/waste.php';
    require APPPATH . 'Config/Routes/Admin/review.php';
    require APPPATH . 'Config/Routes/Admin/laporan.php';
    require APPPATH . 'Config/Routes/Admin/laporan_waste.php';
    require APPPATH . 'Config/Routes/Admin/profil.php';
    require APPPATH . 'Config/Routes/Admin/pengaturan.php';
});

// ================================================
// USER ROUTES (Role: user)
// ================================================
$routes->group('user', ['filter' => 'role:user'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'User\\Dashboard::index');
    $routes->get('/', 'User\\Dashboard::index');
    
    // Waste Management
    $routes->get('waste', 'User\\Waste::index');
    $routes->get('waste/get/(:num)', 'User\\Waste::get/$1');
    $routes->post('waste/save', 'User\\Waste::save');
    $routes->post('waste/edit/(:num)', 'User\\Waste::edit/$1');
    $routes->post('waste/delete/(:num)', 'User\\Waste::delete/$1'); // Changed from DELETE to POST
    $routes->delete('waste/delete/(:num)', 'User\\Waste::delete/$1'); // Keep DELETE for backward compatibility
    $routes->get('waste/export', 'User\\Waste::export');
    
    // Dashboard API
    $routes->get('dashboard/api-stats', 'User\\Dashboard::apiStats');
});

// ================================================
// TPS ROUTES (Role: pengelola_tps)
// ================================================
$routes->group('pengelola-tps', ['filter' => 'role:pengelola_tps'], function ($routes) {
    // Dashboard
    $routes->get('dashboard', 'TPS\\Dashboard::index');
    $routes->get('/', 'TPS\\Dashboard::index');
    
    // Waste Management
    $routes->get('waste', 'TPS\\Waste::index');
    $routes->get('waste/get/(:num)', 'TPS\\Waste::get/$1');
    $routes->post('waste/save', 'TPS\\Waste::save');
    $routes->post('waste/edit/(:num)', 'TPS\\Waste::edit/$1');
    $routes->post('waste/delete/(:num)', 'TPS\\Waste::delete/$1'); // Changed from DELETE to POST
    $routes->delete('waste/delete/(:num)', 'TPS\\Waste::delete/$1'); // Keep DELETE for backward compatibility
    $routes->get('waste/export', 'TPS\\Waste::export');
});

// ================================================
// API ROUTES (Protected)
// ================================================
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard/stats', 'Api\\DashboardApi::getStats');
    $routes->get('waste/summary', 'Api\\WasteApi::getSummary');
    $routes->post('notifications/mark-read/(:num)', 'Api\\NotificationController::markAsRead/$1');
});

// ================================================
// FALLBACK & ERROR HANDLING
// ================================================
$routes->set404Override(function() {
    $user = session()->get('user');
    
    if (!$user || !session()->get('isLoggedIn')) {
        return redirect()->to('/auth/login')->with('error', 'Halaman tidak ditemukan. Silakan login terlebih dahulu.');
    }
    
    // Redirect to appropriate dashboard based on role
    $role = $user['role'] ?? null;
    switch ($role) {
        case 'admin_pusat':
        case 'super_admin':
            return redirect()->to('/admin-pusat/dashboard')->with('error', 'Halaman tidak ditemukan. Anda dialihkan ke dashboard.');
        case 'user':
            return redirect()->to('/user/dashboard')->with('error', 'Halaman tidak ditemukan. Anda dialihkan ke dashboard.');
        case 'pengelola_tps':
            return redirect()->to('/pengelola-tps/dashboard')->with('error', 'Halaman tidak ditemukan. Anda dialihkan ke dashboard.');
        default:
            return redirect()->to('/auth/login')->with('error', 'Halaman tidak ditemukan dan role tidak valid.');
    }
});