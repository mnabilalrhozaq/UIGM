<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Laporan Waste Management' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_admin_pusat') ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="header-content">
                <h1><i class="fas fa-chart-bar"></i> Laporan Waste Management</h1>
                <p>Analisis data waste yang sudah disetujui</p>
            </div>
            
            <div class="header-actions">
                <a href="<?= base_url('/admin-pusat/laporan-waste/export-csv?' . http_build_query($filters)) ?>" 
                   class="btn btn-success">
                    <i class="fas fa-download"></i> Export CSV
                </a>
                <a href="<?= base_url('/admin-pusat/laporan-waste/export-pdf?' . http_build_query($filters)) ?>" 
                   class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="stats-grid">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $summary['total_disetujui'] ?></h3>
                    <p>Data Disetujui</p>
                </div>
            </div>
            
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-weight"></i>
                </div>
                <div class="stat-content">
                    <h3><?= number_format($summary['total_berat'], 2) ?></h3>
                    <p>Total Berat (kg)</p>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $summary['total_unit'] ?></h3>
                    <p>Unit Aktif</p>
                </div>
            </div>
            
            <div class="stat-card danger">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $summary['total_perlu_revisi'] ?? 0 ?></h3>
                    <p>Perlu Revisi</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-filter"></i>
                <h3>Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form method="GET" action="<?= base_url('/admin-pusat/laporan-waste') ?>" class="filter-form">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="periode" class="form-label">Periode</label>
                            <select name="periode" id="periode" class="form-select">
                                <option value="harian" <?= ($filters['periode'] ?? 'harian') == 'harian' ? 'selected' : '' ?>>Per Hari</option>
                                <option value="bulanan" <?= ($filters['periode'] ?? '') == 'bulanan' ? 'selected' : '' ?>>Per Bulan</option>
                                <option value="tahunan" <?= ($filters['periode'] ?? '') == 'tahunan' ? 'selected' : '' ?>>Per Tahun</option>
                                <option value="custom" <?= ($filters['periode'] ?? '') == 'custom' ? 'selected' : '' ?>>Custom Range</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="filter-tanggal">
                            <label for="tanggal" class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control" 
                                   value="<?= $filters['tanggal'] ?? date('Y-m-d') ?>">
                        </div>
                        
                        <div class="col-md-3" id="filter-bulan">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <?php 
                                $bulanList = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', 
                                              '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                              '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                                foreach ($bulanList as $num => $nama):
                                ?>
                                <option value="<?= $num ?>" <?= ($filters['bulan'] ?? date('m')) == $num ? 'selected' : '' ?>>
                                    <?= $nama ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <?php for ($i = date('Y'); $i >= 2020; $i--): ?>
                                <option value="<?= $i ?>" <?= ($filters['tahun'] ?? date('Y')) == $i ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="filter-custom" style="display: none;">
                        <div class="col-md-4">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" 
                                   value="<?= $filters['tanggal_mulai'] ?? '' ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" 
                                   value="<?= $filters['tanggal_selesai'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="unit" class="form-label">Unit</label>
                            <select name="unit" id="unit" class="form-select">
                                <option value="">Semua Unit</option>
                                <?php foreach ($allUnits as $unit): ?>
                                <option value="<?= $unit['id'] ?>" <?= ($filters['unit'] ?? '') == $unit['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($unit['nama_unit']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="jenis" class="form-label">Jenis Sampah</label>
                            <select name="jenis" id="jenis" class="form-select">
                                <option value="">Semua Jenis</option>
                                <?php foreach ($allJenis as $jenis): ?>
                                <option value="<?= $jenis ?>" <?= ($filters['jenis'] ?? '') == $jenis ? 'selected' : '' ?>>
                                    <?= $jenis ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tampilkan Laporan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
        // Toggle filter fields based on periode selection
        document.getElementById('periode').addEventListener('change', function() {
            const periode = this.value;
            const filterTanggal = document.getElementById('filter-tanggal');
            const filterBulan = document.getElementById('filter-bulan');
            const filterCustom = document.getElementById('filter-custom');
            
            // Hide all first
            filterTanggal.style.display = 'none';
            filterBulan.style.display = 'none';
            filterCustom.style.display = 'none';
            
            // Show based on selection
            if (periode === 'harian') {
                filterTanggal.style.display = 'block';
            } else if (periode === 'bulanan') {
                filterBulan.style.display = 'block';
            } else if (periode === 'custom') {
                filterCustom.style.display = 'flex';
            }
        });
        
        // Trigger on page load
        document.getElementById('periode').dispatchEvent(new Event('change'));
        </script>

        <!-- Rekap per Jenis Sampah -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chart-pie"></i>
                <h3>Rekap per Jenis Sampah (Data Disetujui)</h3>
            </div>
            <div class="card-body">
                <div class="waste-summary-grid">
                    <?php foreach ($rekapJenis as $jenis => $data): ?>
                    <div class="waste-summary-item">
                        <div class="waste-icon">
                            <i class="fas fa-<?= getWasteIcon($jenis) ?>"></i>
                        </div>
                        <div class="waste-info">
                            <h5><?= $jenis ?></h5>
                            <div class="waste-stats">
                                <div class="stat-row">
                                    <span>Data:</span>
                                    <strong><?= $data['total_data'] ?></strong>
                                </div>
                                <div class="stat-row">
                                    <span>Berat:</span>
                                    <strong><?= number_format($data['total_berat'], 2) ?> <?= ($jenis === 'Limbah Cair') ? 'L' : 'kg' ?></strong>
                                </div>
                                <div class="stat-row">
                                    <span>Unit:</span>
                                    <strong><?= $data['total_unit'] ?> unit</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Rekap per Unit -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building"></i>
                <h3>Rekap per Unit (Data Disetujui)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Total Data</th>
                                <th>Total Berat (kg)</th>
                                <th>Jenis Terbanyak</th>
                                <th>Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rekapUnit as $unit): ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($unit['nama_unit']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?= $unit['total_data'] ?></span>
                                </td>
                                <td>
                                    <strong><?= number_format($unit['total_berat'], 2) ?></strong>
                                </td>
                                <td>
                                    <?= $unit['jenis_terbanyak'] ?? '-' ?>
                                </td>
                                <td>
                                    <?= $unit['last_update'] ? date('d/m/Y', strtotime($unit['last_update'])) : '-' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php
        function getWasteIcon($jenis) {
            $icons = [
                'Kertas' => 'file-alt',
                'Plastik' => 'wine-bottle',
                'Organik' => 'seedling',
                'Anorganik' => 'cube',
                'Limbah Cair' => 'flask',
                'B3' => 'exclamation-triangle'
            ];
            return $icons[$jenis] ?? 'trash';
        }
        ?>
    </script>
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
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 0;
    border-bottom: 2px solid #e9ecef;
}

.header-content h1 {
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 28px;
    font-weight: 700;
}

.header-content p {
    color: #6c757d;
    margin: 0;
    font-size: 16px;
}

.header-actions {
    display: flex;
    gap: 10px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
    border-left: 4px solid;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.success { border-left-color: #28a745; }
.stat-card.primary { border-left-color: #007bff; }
.stat-card.warning { border-left-color: #ffc107; }
.stat-card.info { border-left-color: #17a2b8; }
.stat-card.danger { border-left-color: #dc3545; }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-card.success .stat-icon { background: #28a745; }
.stat-card.primary .stat-icon { background: #007bff; }
.stat-card.warning .stat-icon { background: #ffc107; }
.stat-card.info .stat-icon { background: #17a2b8; }
.stat-card.danger .stat-icon { background: #dc3545; }

.stat-content h3 {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.stat-content p {
    margin: 0;
    color: #6c757d;
    font-weight: 500;
    font-size: 14px;
}

.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 20px 25px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.card-body {
    padding: 25px;
}

.filter-form .row {
    align-items: end;
}

.waste-summary-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.waste-summary-item {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
}

.waste-icon {
    width: 50px;
    height: 50px;
    background: #007bff;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.waste-info h5 {
    margin: 0 0 10px 0;
    font-size: 16px;
    font-weight: 600;
    color: #2c3e50;
}

.waste-stats {
    font-size: 14px;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.table td {
    vertical-align: middle;
    font-size: 14px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.chart-container {
    position: relative;
    height: 300px;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
    }
    
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .waste-summary-grid {
        grid-template-columns: 1fr;
    }
}
</style>