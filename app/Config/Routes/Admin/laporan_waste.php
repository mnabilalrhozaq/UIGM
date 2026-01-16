<?php

// Laporan Waste Routes
$routes->group('admin-pusat/laporan-waste', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'LaporanWaste::index');
    $routes->get('export', 'LaporanWaste::export');
});
