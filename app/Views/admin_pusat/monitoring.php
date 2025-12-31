<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #333;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
            color: white;
            padding: 25px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
        }

        .nav-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .nav-link {
            background: white;
            color: #5c8cbf;
            padding: 20px 25px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(92, 140, 191, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #5c8cbf;
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(92, 140, 191, 0.3);
        }

        .nav-link i {
            font-size: 20px;
            width: 24px;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid rgba(92, 140, 191, 0.1);
        }

        .card-header {
            background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
            color: white;
            padding: 25px 30px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-body {
            padding: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .progress-text {
            font-size: 12px;
            font-weight: 600;
            color: white;
            text-align: center;
            line-height: 20px;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-draft {
            background: #f5f5f5;
            color: #666;
        }

        .status-dikirim {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-review {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-disetujui {
            background: #e8f5e8;
            color: #388e3c;
        }

        .status-perlu_revisi {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #5c8cbf;
            color: white;
        }

        .btn-primary:hover {
            background: #4a7ba7;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-secondary {
            background: #e2e3e5;
            color: #383d41;
        }

        .filter-section {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid rgba(92, 140, 191, 0.1);
        }

        .filter-section h5 {
            color: #5c8cbf;
            margin-bottom: 25px;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e8ed;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            outline: none;
            border-color: #5c8cbf;
            background: white;
            box-shadow: 0 0 0 4px rgba(92, 140, 191, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px 20px;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 5px solid #5c8cbf;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(92, 140, 191, 0.05) 0%, rgba(74, 123, 167, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card .number {
            font-size: 32px;
            font-weight: 800;
            color: #5c8cbf;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
            font-weight: 600;
            position: relative;
            z-index: 1;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-center {
            text-align: center;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .nav-links {
                flex-direction: column;
            }

            .table {
                font-size: 12px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-chart-line"></i> Monitoring Unit</h1>
                <p>Tahun Penilaian: <?= $tahun['tahun'] ?> - <?= $tahun['nama_periode'] ?></p>
            </div>
            <div class="header-info">
                <span><?= $user['nama_lengkap'] ?></span>
                <a href="/auth/logout" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Navigation Links -->
        <div class="nav-links">
            <a href="/admin-pusat/dashboard" class="nav-link">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a href="/admin-pusat/monitoring" class="nav-link active">
                <i class="fas fa-chart-line"></i> Monitoring Unit
            </a>
            <a href="/admin-pusat/notifikasi" class="nav-link">
                <i class="fas fa-bell"></i> Notifikasi
            </a>
            <a href="/report" class="nav-link">
                <i class="fas fa-file-export"></i> Laporan
            </a>
        </div>

        <!-- Statistik -->
        <div class="stats-grid">
            <?php
            $totalUnit = count($units ?? []);
            $dikirim = 0;
            $review = 0;
            $disetujui = 0;
            $perluRevisi = 0;
            $draft = 0;

            if (!empty($units)) {
                foreach ($units as $unit) {
                    $status = $unit['status_pengiriman'] ?? 'draft';
                    switch ($status) {
                        case 'draft':
                            $draft++;
                            break;
                        case 'dikirim':
                            $dikirim++;
                            break;
                        case 'review':
                            $review++;
                            break;
                        case 'disetujui':
                            $disetujui++;
                            break;
                        case 'perlu_revisi':
                            $perluRevisi++;
                            break;
                    }
                }
            }
            ?>
            <div class="stat-card">
                <div class="number"><?= $totalUnit ?></div>
                <div class="label">Total Unit</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $draft ?></div>
                <div class="label">Draft</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $dikirim + $review ?></div>
                <div class="label">Perlu Review</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $disetujui ?></div>
                <div class="label">Disetujui</div>
            </div>
            <div class="stat-card">
                <div class="number"><?= $perluRevisi ?></div>
                <div class="label">Perlu Revisi</div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <h5><i class="fas fa-filter me-2"></i>Filter Data</h5>
            <div class="filter-grid">
                <div>
                    <label>Filter Status:</label>
                    <select id="statusFilter" class="form-control" onchange="filterTable()">
                        <option value="">Semua Status</option>
                        <option value="draft">Draft</option>
                        <option value="dikirim">Dikirim</option>
                        <option value="review">Sedang Review</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="perlu_revisi">Perlu Revisi</option>
                    </select>
                </div>
                <div>
                    <label>Cari Unit:</label>
                    <input type="text" id="searchUnit" class="form-control" placeholder="Nama atau kode unit..." onkeyup="filterTable()">
                </div>
                <div>
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-secondary form-control" onclick="resetFilter()">
                        <i class="fas fa-undo"></i> Reset Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Units Table -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building"></i> Daftar Unit dan Progress
            </div>
            <div class="card-body">
                <table class="table" id="unitsTable">
                    <thead>
                        <tr>
                            <th>Unit</th>
                            <th>Kode</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Tanggal Kirim</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($units)): ?>
                            <?php foreach ($units as $unit): ?>
                                <tr data-status="<?= htmlspecialchars($unit['status_pengiriman'] ?? 'draft', ENT_QUOTES, 'UTF-8') ?>"
                                    data-unit="<?= htmlspecialchars(strtolower(($unit['nama_unit'] ?? '') . ' ' . ($unit['kode_unit'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">
                                    <td>
                                        <strong><?= htmlspecialchars($unit['nama_unit'] ?? 'Nama Unit Tidak Tersedia', ENT_QUOTES, 'UTF-8') ?></strong><br>
                                        <small class="text-muted"><?= htmlspecialchars(ucfirst($unit['tipe_unit'] ?? 'Tidak Diketahui'), ENT_QUOTES, 'UTF-8') ?></small>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary"><?= htmlspecialchars($unit['kode_unit'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $status = $unit['status_pengiriman'] ?? 'draft';
                                        $statusText = '';
                                        switch ($status) {
                                            case 'draft':
                                                $statusText = 'Draft';
                                                break;
                                            case 'dikirim':
                                                $statusText = 'Dikirim';
                                                break;
                                            case 'review':
                                                $statusText = 'Sedang Review';
                                                break;
                                            case 'disetujui':
                                                $statusText = 'Disetujui';
                                                break;
                                            case 'perlu_revisi':
                                                $statusText = 'Perlu Revisi';
                                                break;
                                            default:
                                                $statusText = 'Belum Ada Data';
                                                $status = 'draft';
                                                break;
                                        }
                                        ?>
                                        <span class="status-badge status-<?= $status ?>">
                                            <?= $statusText ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $progress = 0;
                                        if (isset($unit['progress_persen']) && $unit['progress_persen'] !== null) {
                                            $progress = (float)$unit['progress_persen'];
                                        }
                                        ?>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: <?= $progress ?>%">
                                                <?php if ($progress > 0): ?>
                                                    <div class="progress-text"><?= number_format($progress, 1) ?>%</div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($progress == 0): ?>
                                            <small class="text-muted">0%</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        if (!empty($unit['tanggal_kirim'])) {
                                            try {
                                                echo date('d/m/Y H:i', strtotime($unit['tanggal_kirim']));
                                            } catch (Exception $e) {
                                                echo '-';
                                            }
                                        } else {
                                            echo '<span class="text-muted">Belum Dikirim</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($unit['pengiriman_id'])): ?>
                                            <a href="/admin-pusat/review/<?= $unit['pengiriman_id'] ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> Review
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted" style="font-size: 12px;">
                                                <i class="fas fa-info-circle"></i> Belum ada data
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div style="padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                        <p style="color: #666;">Tidak ada data unit yang tersedia</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const searchUnit = document.getElementById('searchUnit').value.toLowerCase();
            const table = document.getElementById('unitsTable');
            const rows = table.getElementsByTagName('tr');
            let visibleRows = 0;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const status = row.getAttribute('data-status') || '';
                const unitText = row.getAttribute('data-unit') || '';

                let showRow = true;

                // Filter berdasarkan status
                if (statusFilter && status !== statusFilter) {
                    showRow = false;
                }

                // Filter berdasarkan pencarian unit
                if (searchUnit && !unitText.includes(searchUnit)) {
                    showRow = false;
                }

                if (showRow) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            }

            // Tampilkan pesan jika tidak ada hasil
            const tbody = table.querySelector('tbody');
            let noResultsRow = tbody.querySelector('.no-results-row');

            if (visibleRows === 0 && (statusFilter || searchUnit)) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.className = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="6" class="text-center">
                            <div style="padding: 40px;">
                                <i class="fas fa-search" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                                <p style="color: #666;">Tidak ada unit yang sesuai dengan filter yang dipilih</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(noResultsRow);
                }
                noResultsRow.style.display = '';
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        }

        // Auto refresh setiap 2 menit
        setInterval(function() {
            console.log('Memperbarui data monitoring...');
            location.reload();
        }, 120000);

        // Tampilkan loading saat tombol review diklik
        document.addEventListener('DOMContentLoaded', function() {
            const reviewButtons = document.querySelectorAll('.btn-primary');
            reviewButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (this.href) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat...';
                        this.style.pointerEvents = 'none';
                    }
                });
            });

            // Tampilkan pesan jika ada error dari session
            <?php if (session()->getFlashdata('error')): ?>
                showAlert('<?= addslashes(session()->getFlashdata('error')) ?>', 'danger');
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                showAlert('<?= addslashes(session()->getFlashdata('success')) ?>', 'success');
            <?php endif; ?>
        });

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.style.position = 'fixed';
            alertDiv.style.top = '20px';
            alertDiv.style.right = '20px';
            alertDiv.style.zIndex = '9999';
            alertDiv.style.minWidth = '300px';
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'danger' ? 'exclamation-triangle' : 'check-circle'}"></i>
                ${message}
                <button type="button" style="float: right; background: none; border: none; font-size: 18px; cursor: pointer;" onclick="this.parentElement.remove()">&times;</button>
            `;

            document.body.appendChild(alertDiv);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // Reset filter
        function resetFilter() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('searchUnit').value = '';
            filterTable();
        }

        // Keyboard shortcut untuk pencarian
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchUnit').focus();
            }
        });
    </script>
</body>

</html>