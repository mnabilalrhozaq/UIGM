<?php

// Laporan Waste Routes
$routes->group('admin-pusat/laporan-waste', ['namespace' => 'App\Controllers\Admin'], function($routes) {
    $routes->get('/', 'LaporanWaste::index');
    $routes->get('export', 'LaporanWaste::export');
    $routes->get('export-csv', 'LaporanWaste::exportCsv');
    $routes->get('export-pdf', 'LaporanWaste::exportPdf');
});
