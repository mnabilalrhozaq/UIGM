<?php

/**
 * Laporan Routes
 * URL: /admin-pusat/laporan
 */

$routes->get('laporan', 'Admin\\Laporan::index');
$routes->get('laporan/export', 'Admin\\Laporan::export');
$routes->get('laporan-waste', 'Admin\\LaporanWaste::index');
$routes->get('laporan-waste/export-csv', 'Admin\\LaporanWaste::exportCsv');
$routes->get('laporan-waste/export-pdf', 'Admin\\LaporanWaste::exportPdf');
