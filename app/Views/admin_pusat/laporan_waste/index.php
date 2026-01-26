<?php
// Helper functions
if (!function_exists('formatNumber')) {
    function formatNumber($number) {
        return number_format($number, 2, ',', '.');
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laporan Waste' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Mobile Responsive CSS -->
    <link href="<?= base_url('/css/mobile-responsive.css') ?>" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar') ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-chart-bar"></i> Laporan Data Sampah</h1>
            <p>Laporan lengkap data sampah yang sudah disetujui dan ditolak</p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h3><i class="fas fa-filter"></i> Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('/admin-pusat/laporan-waste') ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="<?= $filters['start_date'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="<?= $filters['end_date'] ?? '' ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="unit_id" class="form-label">Unit</label>
                                <select class="form-select" id="unit_id" name="unit_id">
                                    <option value="">Semua Unit</option>
                                    <?php foreach ($units as $unit): ?>
                                    <option value="<?= $unit['id'] ?>" <?= ($filters['unit_id'] ?? '') == $unit['id'] ? 'selected' : '' ?>>
                                        <?= $unit['nama_unit'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="<?= base_url('/admin-pusat/laporan-waste') ?>" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Transaksi</h5>
                        <h2><?= $summary['total_transaksi'] ?? 0 ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Disetujui</h5>
                        <h2><?= $summary['total_disetujui'] ?? 0 ?></h2>
                        <small><?= formatNumber($summary['total_berat_disetujui'] ?? 0) ?> kg</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Ditolak</h5>
                        <h2><?= $summary['total_ditolak'] ?? 0 ?></h2>
                        <small><?= formatNumber($summary['total_berat_ditolak'] ?? 0) ?> kg</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Total Nilai Disetujui</h5>
                        <h2><?= formatCurrency($summary['total_nilai_disetujui'] ?? 0) ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Button -->
        <div class="mb-3">
            <a href="<?= base_url('/admin-pusat/laporan-waste/export-pdf?' . http_build_query($filters)) ?>" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Export ke PDF
            </a>
        </div>

        <!-- Rekap per Jenis Sampah -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3><i class="fas fa-recycle"></i> Rekap per Jenis Sampah</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($rekap_jenis)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jenis Sampah</th>
                                <th>Total Transaksi</th>
                                <th>Disetujui</th>
                                <th>Ditolak</th>
                                <th>Berat Disetujui (kg)</th>
                                <th>Berat Ditolak (kg)</th>
                                <th>Nilai Disetujui</th>
                                <th>Nilai Ditolak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_jenis as $item): ?>
                            <tr>
                                <td><strong><?= $item['jenis_sampah'] ?></strong></td>
                                <td><?= $item['total_transaksi'] ?></td>
                                <td><span class="badge bg-success"><?= $item['total_disetujui'] ?></span></td>
                                <td><span class="badge bg-danger"><?= $item['total_ditolak'] ?></span></td>
                                <td><?= formatNumber($item['total_berat_disetujui']) ?></td>
                                <td><?= formatNumber($item['total_berat_ditolak']) ?></td>
                                <td><?= formatCurrency($item['total_nilai_disetujui']) ?></td>
                                <td><?= formatCurrency($item['total_nilai_ditolak']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Rekap Jenis -->
                <?php if (isset($pagination) && $pagination['total_pages_rekap_jenis'] > 1): ?>
                <nav aria-label="Pagination Rekap Jenis">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $pagination['pages']['rekap_jenis'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['pages']['rekap_jenis'] - 1 ?>&section=rekap_jenis&<?= http_build_query($filters) ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages_rekap_jenis']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['pages']['rekap_jenis'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&section=rekap_jenis&<?= http_build_query($filters) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $pagination['pages']['rekap_jenis'] >= $pagination['total_pages_rekap_jenis'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['pages']['rekap_jenis'] + 1 ?>&section=rekap_jenis&<?= http_build_query($filters) ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="text-center text-muted">
                        <small>Halaman <?= $pagination['pages']['rekap_jenis'] ?> dari <?= $pagination['total_pages_rekap_jenis'] ?> 
                        (Total: <?= $pagination['total_rekap_jenis'] ?> jenis sampah)</small>
                    </div>
                </nav>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data laporan</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Rekap per Unit -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h3><i class="fas fa-building"></i> Rekap per Unit</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($rekap_unit)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Total Transaksi</th>
                                <th>Disetujui</th>
                                <th>Ditolak</th>
                                <th>Berat Disetujui (kg)</th>
                                <th>Berat Ditolak (kg)</th>
                                <th>Nilai Disetujui</th>
                                <th>Nilai Ditolak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekap_unit as $item): ?>
                            <tr>
                                <td><strong><?= $item['nama_unit'] ?></strong></td>
                                <td><?= $item['total_transaksi'] ?></td>
                                <td><span class="badge bg-success"><?= $item['total_disetujui'] ?></span></td>
                                <td><span class="badge bg-danger"><?= $item['total_ditolak'] ?></span></td>
                                <td><?= formatNumber($item['total_berat_disetujui']) ?></td>
                                <td><?= formatNumber($item['total_berat_ditolak']) ?></td>
                                <td><?= formatCurrency($item['total_nilai_disetujui']) ?></td>
                                <td><?= formatCurrency($item['total_nilai_ditolak']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Rekap Unit -->
                <?php if (isset($pagination) && $pagination['total_pages_rekap_unit'] > 1): ?>
                <nav aria-label="Pagination Rekap Unit">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $pagination['pages']['rekap_unit'] <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['pages']['rekap_unit'] - 1 ?>&section=rekap_unit&<?= http_build_query($filters) ?>">
                                <i class="fas fa-chevron-left"></i> Previous
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $pagination['total_pages_rekap_unit']; $i++): ?>
                        <li class="page-item <?= $i == $pagination['pages']['rekap_unit'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&section=rekap_unit&<?= http_build_query($filters) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $pagination['pages']['rekap_unit'] >= $pagination['total_pages_rekap_unit'] ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $pagination['pages']['rekap_unit'] + 1 ?>&section=rekap_unit&<?= http_build_query($filters) ?>">
                                Next <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="text-center text-muted">
                        <small>Halaman <?= $pagination['pages']['rekap_unit'] ?> dari <?= $pagination['total_pages_rekap_unit'] ?> 
                        (Total: <?= $pagination['total_rekap_unit'] ?> unit)</small>
                    </div>
                </nav>
                <?php endif; ?>
                
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data laporan</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tabs for Disetujui and Ditolak -->
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#disetujui">
                            <i class="fas fa-check-circle text-success"></i> Data Disetujui (<?= $pagination['total_disetujui'] ?? 0 ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#ditolak">
                            <i class="fas fa-times-circle text-danger"></i> Data Ditolak (<?= $pagination['total_ditolak'] ?? 0 ?>)
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <!-- Data Disetujui -->
                    <div class="tab-pane fade show active" id="disetujui">
                        <?php if (!empty($data_disetujui)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Unit</th>
                                        <th>Jenis Sampah</th>
                                        <th>Berat (kg)</th>
                                        <th>Satuan</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_disetujui as $item): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                                        <td><?= $item['nama_unit'] ?></td>
                                        <td><span class="badge bg-primary"><?= $item['jenis_sampah'] ?></span></td>
                                        <td><?= formatNumber($item['berat_kg']) ?></td>
                                        <td><?= $item['satuan'] ?></td>
                                        <td><?= formatCurrency($item['nilai_rupiah']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Data Disetujui -->
                        <?php if (isset($pagination) && $pagination['total_pages_disetujui'] > 1): ?>
                        <nav aria-label="Pagination Data Disetujui">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $pagination['pages']['disetujui'] <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $pagination['pages']['disetujui'] - 1 ?>&section=disetujui&<?= http_build_query($filters) ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages_disetujui']; $i++): ?>
                                <li class="page-item <?= $i == $pagination['pages']['disetujui'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&section=disetujui&<?= http_build_query($filters) ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?= $pagination['pages']['disetujui'] >= $pagination['total_pages_disetujui'] ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $pagination['pages']['disetujui'] + 1 ?>&section=disetujui&<?= http_build_query($filters) ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="text-center text-muted">
                                <small>Halaman <?= $pagination['pages']['disetujui'] ?> dari <?= $pagination['total_pages_disetujui'] ?> 
                                (Total: <?= $pagination['total_disetujui'] ?> data)</small>
                            </div>
                        </nav>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data yang disetujui</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Data Ditolak -->
                    <div class="tab-pane fade" id="ditolak">
                        <?php if (!empty($data_ditolak)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Unit</th>
                                        <th>Jenis Sampah</th>
                                        <th>Berat (kg)</th>
                                        <th>Satuan</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_ditolak as $item): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></td>
                                        <td><?= $item['nama_unit'] ?></td>
                                        <td><span class="badge bg-primary"><?= $item['jenis_sampah'] ?></span></td>
                                        <td><?= formatNumber($item['berat_kg']) ?></td>
                                        <td><?= $item['satuan'] ?></td>
                                        <td><?= formatCurrency($item['nilai_rupiah']) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Data Ditolak -->
                        <?php if (isset($pagination) && $pagination['total_pages_ditolak'] > 1): ?>
                        <nav aria-label="Pagination Data Ditolak">
                            <ul class="pagination justify-content-center">
                                <li class="page-item <?= $pagination['pages']['ditolak'] <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $pagination['pages']['ditolak'] - 1 ?>&section=ditolak&<?= http_build_query($filters) ?>">
                                        <i class="fas fa-chevron-left"></i> Previous
                                    </a>
                                </li>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages_ditolak']; $i++): ?>
                                <li class="page-item <?= $i == $pagination['pages']['ditolak'] ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>&section=ditolak&<?= http_build_query($filters) ?>"><?= $i ?></a>
                                </li>
                                <?php endfor; ?>
                                
                                <li class="page-item <?= $pagination['pages']['ditolak'] >= $pagination['total_pages_ditolak'] ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $pagination['pages']['ditolak'] + 1 ?>&section=ditolak&<?= http_build_query($filters) ?>">
                                        Next <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="text-center text-muted">
                                <small>Halaman <?= $pagination['pages']['ditolak'] ?> dari <?= $pagination['total_pages_ditolak'] ?> 
                                (Total: <?= $pagination['total_ditolak'] ?> data)</small>
                            </div>
                        </nav>
                        <?php endif; ?>
                        
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada data yang ditolak</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Mobile Menu JS -->
    <script src="<?= base_url('/js/mobile-menu.js') ?>"></script>
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

.page-header {
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #e9ecef;
}

.page-header h1 {
    color: #2c3e50;
    margin-bottom: 10px;
    font-size: 28px;
    font-weight: 700;
}

.page-header p {
    color: #6c757d;
    font-size: 16px;
    margin: 0;
}

.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
    border: none;
}

.card-header {
    padding: 20px 25px;
    border: none;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.card-body {
    padding: 25px;
}

.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #2c3e50;
    padding: 15px;
    font-size: 14px;
}

.table td {
    border: none;
    padding: 15px;
    vertical-align: middle;
    font-size: 14px;
}

.table tbody tr {
    border-bottom: 1px solid #e9ecef;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

.pagination {
    margin-top: 20px;
    margin-bottom: 10px;
}

.pagination .page-link {
    color: #2c3e50;
    border: 1px solid #dee2e6;
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    background: #007bff;
    border-color: #007bff;
    color: white;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background: #fff;
    border-color: #dee2e6;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .pagination {
        flex-wrap: wrap;
    }
    
    .pagination .page-item {
        margin-bottom: 5px;
    }
}
</style>
