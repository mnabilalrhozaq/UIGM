<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('process-login', 'Auth::processLogin');
    $routes->get('logout', 'Auth::logout');
});

// Demo Routes
$routes->get('demo/login', function () {
    return view('demo/login_preview');
});
$routes->get('demo/admin-unit', function () {
    return view('demo/admin_unit_preview');
});
$routes->get('demo/info', function () {
    return view('demo/login_info');
});

// Test Routes
$routes->get('test/login', function () {
    return view('test/login_test');
});

// Admin Unit Routes (Protected)
$routes->group('admin-unit', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AdminUnit::index');
    $routes->get('dashboard', 'AdminUnit::index');
    $routes->post('simpan-kategori', 'AdminUnit::simpanKategori');
    $routes->post('kirim-data', 'AdminUnit::kirimData');

    // Debug route untuk testing
    if (ENVIRONMENT === 'development') {
        $routes->get('test-save', function () {
            echo "Test route works!";
        });
    }
});

// Admin Pusat Routes (Protected)
$routes->group('admin-pusat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'AdminPusat::index');
    $routes->get('dashboard', 'AdminPusat::index');
    $routes->get('review-queue', 'AdminPusat::reviewQueue');
    $routes->get('review/(:num)', 'AdminPusat::review/$1');
    $routes->post('update-review', 'AdminPusat::updateReview');
    $routes->post('terima/(:num)', 'AdminPusat::terima/$1');
    $routes->post('tolak/(:num)', 'AdminPusat::tolak/$1');
    $routes->post('finalize-pengiriman', 'AdminPusat::finalizePengiriman');
    $routes->post('return-for-revision', 'AdminPusat::returnForRevision');
    $routes->get('monitoring', 'AdminPusat::monitoring');
    $routes->get('notifikasi', 'AdminPusat::notifikasi');

    // Notification API endpoints
    $routes->get('get-unread-count', 'AdminPusat::getUnreadCount');
    $routes->post('mark-notification-read', 'AdminPusat::markNotificationRead');
    $routes->post('mark-all-notifications-read', 'AdminPusat::markAllNotificationsRead');

    // New routes for sidebar functionality
    $routes->get('data-penilaian', 'AdminPusat::dataPenilaian');
    $routes->get('indikator-greenmetric', 'AdminPusat::indikatorGreenMetric');
    $routes->get('riwayat-penilaian', 'AdminPusat::riwayatPenilaian');
    $routes->get('pengaturan', 'AdminPusat::pengaturan');
    $routes->post('update-tahun-aktif', 'AdminPusat::updateTahunAktif');
});

// Super Admin Routes (Protected)
$routes->group('super-admin', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'SuperAdmin::index');
    $routes->get('dashboard', 'SuperAdmin::index');

    // User Management
    $routes->get('users', 'SuperAdmin::users');
    $routes->post('users/create', 'SuperAdmin::createUser');
    $routes->post('users/update', 'SuperAdmin::updateUser');
    $routes->delete('users/delete/(:num)', 'SuperAdmin::deleteUser/$1');

    // Unit Management
    $routes->get('units', 'SuperAdmin::units');
    $routes->post('units/create', 'SuperAdmin::createUnit');
    $routes->post('units/update', 'SuperAdmin::updateUnit');

    // Tahun Penilaian Management
    $routes->get('tahun-penilaian', 'SuperAdmin::tahunPenilaian');
    $routes->post('tahun-penilaian/create', 'SuperAdmin::createTahunPenilaian');
});

// File Upload Routes (Protected)
$routes->group('file', ['filter' => 'auth'], function ($routes) {
    $routes->post('upload', 'FileController::upload');
    $routes->get('download/(:num)/(:num)/(:any)', 'FileController::download/$1/$2/$3');
    $routes->post('delete', 'FileController::delete');
    $routes->get('list/(:num)/(:num)', 'FileController::listFiles/$1/$2');
});

// API Routes (Protected)
$routes->group('api', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard-stats', 'ApiController::getDashboardStats');
    $routes->get('notifications', 'ApiController::getNotifications');
    $routes->get('notifications/unread-count', 'ApiController::getUnreadCount');
    $routes->post('notifications/(:num)/read', 'ApiController::markNotificationRead/$1');
    $routes->post('notifications/mark-all-read', 'ApiController::markAllNotificationsRead');
    $routes->get('unit-progress/(:num)', 'ApiController::getUnitProgress/$1');
    $routes->get('unit-progress', 'ApiController::getUnitProgress');
    $routes->get('search-units', 'ApiController::searchUnits');
    $routes->get('category/(:num)', 'ApiController::getCategoryDetails/$1');
    $routes->get('jenis-sampah/area/(:num)', 'ApiController::getAreaSampah/$1');
    $routes->get('jenis-sampah/detail/(:num)', 'ApiController::getDetailSampah/$1');
    $routes->get('jenis-sampah/struktur', 'ApiController::getSampahOrganikStructure');
});

// Debug Routes (Development only)
if (ENVIRONMENT === 'development') {
    $routes->get('debug/test-save', 'DebugController::testSave');
    $routes->get('debug/check-session', 'DebugController::checkSession');
    $routes->get('debug/check-database', 'DebugController::checkDatabase');
}

// Report Routes (Protected)
$routes->group('report', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'ReportController::index');
    $routes->get('export-csv', 'ReportController::exportCSV');
    $routes->get('generate-pdf', 'ReportController::generatePDF');
});
