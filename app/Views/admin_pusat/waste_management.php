<?php
/**
 * Admin Pusat - Waste Management
 * Halaman untuk review dan approve data sampah dari User dan TPS
 */

// Helper functions
if (!function_exists('formatNumber')) {
    function formatNumber($number) {
        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

// Safety checks
$waste_list = $waste_list ?? [];
$summary = $summary ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Manajemen Sampah Admin' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_admin_pusat') ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-clipboard-check"></i> Waste Management</h1>
            <p>Kelola dan monitor data sampah dari semua unit</p>
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

        <!-- Waste Data Table -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list"></i> Data Sampah</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($waste_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Unit</th>
                                    <th>Jenis Sampah</th>
                                    <th>Berat (kg)</th>
                                    <th>Nilai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($waste_list as $index => $waste): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= date('d/m/Y', strtotime($waste['tanggal'])) ?></td>
                                    <td><?= $waste['nama_unit'] ?? 'N/A' ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= $waste['jenis_sampah'] ?></span>
                                    </td>
                                    <td><?= number_format($waste['berat_kg'], 2) ?></td>
                                    <td><?= formatCurrency($waste['nilai_rupiah'] ?? 0) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match($waste['status']) {
                                            'disetujui' => 'success',
                                            'dikirim' => 'warning',
                                            'review' => 'info',
                                            'perlu_revisi' => 'danger',
                                            default => 'secondary'
                                        };
                                        $statusLabel = match($waste['status']) {
                                            'disetujui' => 'Disetujui',
                                            'dikirim' => 'Dikirim',
                                            'review' => 'Review',
                                            'perlu_revisi' => 'Perlu Revisi',
                                            default => 'Draft'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <?php if (in_array($waste['status'], ['dikirim', 'review'])): ?>
                                            <button type="button" class="btn btn-success" onclick="approveWaste(<?= $waste['id'] ?>)">
                                                <i class="fas fa-check"></i> Setujui
                                            </button>
                                            <button type="button" class="btn btn-danger" onclick="rejectWaste(<?= $waste['id'] ?>)">
                                                <i class="fas fa-times"></i> Tolak
                                            </button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-danger" onclick="deleteWaste(<?= $waste['id'] ?>, '<?= esc($waste['jenis_sampah']) ?>')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data sampah yang perlu direview.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Data Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="rejectForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="reject_waste_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="reject_catatan" class="form-label">Catatan Penolakan *</label>
                            <textarea class="form-control" id="reject_catatan" name="catatan" rows="4" required placeholder="Jelaskan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function approveWaste(id) {
            if (confirm('Apakah Anda yakin ingin menyetujui data sampah ini?')) {
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                fetch(`<?= base_url('/admin-pusat/waste/approve/') ?>${id}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyetujui data');
                });
            }
        }

        function rejectWaste(id) {
            document.getElementById('reject_waste_id').value = id;
            const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
            rejectModal.show();
        }

        function deleteWaste(id, jenisSampah) {
            if (confirm(`Apakah Anda yakin ingin menghapus data sampah "${jenisSampah}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                fetch(`<?= base_url('/admin-pusat/waste/delete/') ?>${id}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus data');
                });
            }
        }

        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const wasteId = document.getElementById('reject_waste_id').value;
            const formData = new FormData(this);
            
            fetch(`<?= base_url('/admin-pusat/waste/reject/') ?>${wasteId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menolak data');
            });
        });
    </script>
</body>
</html>

<style>
/* ===== MAIN LAYOUT ===== */
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
    max-width: calc(100vw - 280px);
    overflow-x: hidden;
}

/* ===== PAGE HEADER ===== */
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

/* ===== CARDS ===== */
.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
    border: none;
}

.card-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 20px 25px;
    border: none;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

/* ===== TABLES ===== */
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
}

.table {
    margin-bottom: 0;
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

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state p {
    margin: 0 0 25px 0;
    font-size: 18px;
}

/* ===== ALERTS ===== */
.alert {
    border-radius: 10px;
    border: none;
    padding: 15px 20px;
}

/* ===== BUTTONS ===== */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* ===== MODALS ===== */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-title {
    font-weight: 600;
}

.btn-close {
    filter: invert(1);
}

/* ===== FORM ELEMENTS ===== */
.form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

/* ===== RESPONSIVE DESIGN ===== */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 20px;
        max-width: 100vw;
    }
    
    .page-header h1 {
        font-size: 24px;
    }
    
    .card-header {
        padding: 15px 20px;
    }
    
    .card-body {
        padding: 20px;
    }
    
    .table-responsive {
        font-size: 12px;
    }
    
    .btn-group {
        flex-direction: column;
    }
}
</style>
