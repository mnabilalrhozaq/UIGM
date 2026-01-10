<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <title><?= $title ?? 'Manajemen Harga Sampah' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_admin_pusat') ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="header-content">
                <h1><i class="fas fa-money-bill-wave"></i> Manajemen Harga Sampah</h1>
                <p>Kelola harga sampah secara terpusat untuk seluruh sistem</p>
            </div>
            
            <div class="header-actions">
                <a href="<?= base_url('/admin-pusat/manajemen-harga/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Harga Baru
                </a>
                <a href="<?= base_url('/admin-pusat/manajemen-harga/logs') ?>" class="btn btn-outline-info">
                    <i class="fas fa-history"></i> Log Perubahan
                </a>
            </div>
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

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['total'] ?></h3>
                    <p>Total Jenis Sampah</p>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['aktif'] ?></h3>
                    <p>Harga Aktif</p>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $statistics['bisa_dijual'] ?></h3>
                    <p>Bisa Dijual</p>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $logStatistics['changes_today'] ?></h3>
                    <p>Perubahan Hari Ini</p>
                </div>
            </div>
        </div>

        <!-- Harga Sampah Table -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-table"></i>
                <h3>Daftar Harga Sampah</h3>
            </div>
            <div class="card-body">
                <?php if (empty($hargaSampah)): ?>
                <div class="empty-state">
                    <i class="fas fa-money-bill-wave"></i>
                    <p>Belum ada data harga sampah</p>
                    <a href="<?= base_url('/admin-pusat/manajemen-harga/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Harga Pertama
                    </a>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Jenis Sampah</th>
                                <th>Nama Lengkap</th>
                                <th>Harga per Satuan</th>
                                <th>Satuan</th>
                                <th>Status Jual</th>
                                <th>Status Aktif</th>
                                <th>Terakhir Update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hargaSampah as $item): ?>
                            <tr>
                                <td>
                                    <div class="jenis-info">
                                        <i class="fas fa-<?= getJenisIcon($item['jenis_sampah']) ?> text-primary"></i>
                                        <strong><?= htmlspecialchars($item['jenis_sampah']) ?></strong>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($item['nama_jenis']) ?></td>
                                <td>
                                    <div class="harga-display">
                                        <strong class="text-success">
                                            Rp <?= number_format($item['harga_per_satuan'], 0, ',', '.') ?>
                                        </strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($item['satuan']) ?></span>
                                </td>
                                <td>
                                    <?php if ($item['dapat_dijual']): ?>
                                        <span class="badge bg-success">
                                            <i class="fas fa-money-bill-wave"></i> Bisa Dijual
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times"></i> Tidak Bisa Dijual
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               data-id="<?= $item['id'] ?>"
                                               <?= $item['status_aktif'] ? 'checked' : '' ?>>
                                        <label class="form-check-label">
                                            <?= $item['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($item['updated_at'])) ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-warning" 
                                                onclick="editHarga(<?= $item['id'] ?>)" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Changes -->
        <?php if (!empty($recentChanges)): ?>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-history"></i>
                <h3>Perubahan Terbaru</h3>
                <a href="<?= base_url('/admin-pusat/manajemen-harga/logs') ?>" class="btn btn-sm btn-outline-info ms-auto">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($recentChanges as $change): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker">
                            <i class="fas fa-<?= getActionIcon($change['status_perubahan'] ?? 'update') ?>"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-header">
                                <strong><?= htmlspecialchars($change['admin_nama'] ?? 'Admin') ?></strong>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($change['created_at'])) ?></small>
                            </div>
                            <div class="timeline-body">
                                <?= htmlspecialchars($change['jenis_sampah']) ?>: 
                                Rp <?= number_format($change['harga_lama'] ?? 0, 0, ',', '.') ?> → 
                                Rp <?= number_format($change['harga_baru'], 0, ',', '.') ?>
                                <?php if (!empty($change['alasan_perubahan'])): ?>
                                    <br><small class="text-muted"><?= htmlspecialchars($change['alasan_perubahan']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Edit Harga Modal -->
    <div class="modal fade" id="editHargaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Harga Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editHargaForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_jenis_sampah" class="form-label">Jenis Sampah *</label>
                            <input type="text" class="form-control" id="edit_jenis_sampah" name="jenis_sampah" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_nama_jenis" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="edit_nama_jenis" name="nama_jenis">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_harga_per_satuan" class="form-label">Harga per Satuan *</label>
                                    <input type="number" class="form-control" id="edit_harga_per_satuan" name="harga_per_satuan" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_satuan" class="form-label">Satuan *</label>
                                    <select class="form-select" id="edit_satuan" name="satuan" required>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="ton">Ton</option>
                                        <option value="liter">Liter</option>
                                        <option value="pcs">Pieces (pcs)</option>
                                        <option value="karung">Karung</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_dapat_dijual" name="dapat_dijual" value="1" checked>
                            <label class="form-check-label" for="edit_dapat_dijual">Dapat Dijual</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_status_aktif" name="status_aktif" value="1" checked>
                            <label class="form-check-label" for="edit_status_aktif">Status Aktif</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit Harga function
        function editHarga(id) {
            fetch(`<?= base_url('/admin-pusat/manajemen-harga/get/') ?>${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const harga = data.data;
                    // Populate edit modal fields
                    document.getElementById('edit_id').value = harga.id;
                    document.getElementById('edit_jenis_sampah').value = harga.jenis_sampah;
                    document.getElementById('edit_nama_jenis').value = harga.nama_jenis || '';
                    document.getElementById('edit_harga_per_satuan').value = harga.harga_per_satuan;
                    document.getElementById('edit_satuan').value = harga.satuan;
                    document.getElementById('edit_dapat_dijual').checked = harga.dapat_dijual;
                    document.getElementById('edit_status_aktif').checked = harga.status_aktif;
                    document.getElementById('edit_deskripsi').value = harga.deskripsi || '';
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editHargaModal'));
                    modal.show();
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat mengambil data');
            });
        }

        // Handle edit form submit
        document.getElementById('editHargaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            
            // Add CSRF token
            const csrfName = document.querySelector('meta[name="csrf-name"]')?.getAttribute('content');
            const csrfHash = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (csrfName && csrfHash) {
                formData.append(csrfName, csrfHash);
            }
            
            // Debug: log form data
            console.log('Submitting edit for ID:', id);
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            fetch(`<?= base_url('/admin-pusat/manajemen-harga/update/') ?>${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('editHargaModal')).hide();
                    showAlert('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message || 'Gagal menyimpan perubahan');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Terjadi kesalahan saat menyimpan data');
            });
        });

        // Handle status toggle
        document.querySelectorAll('.status-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const id = this.dataset.id;
                const isChecked = this.checked;
                
                fetch(`<?= base_url('/admin-pusat/manajemen-harga/toggle-status/') ?>${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update label
                        const label = this.nextElementSibling;
                        label.textContent = isChecked ? 'Aktif' : 'Nonaktif';
                        
                        // Show success message
                        showAlert('success', data.message);
                    } else {
                        // Revert toggle
                        this.checked = !isChecked;
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    // Revert toggle
                    this.checked = !isChecked;
                    showAlert('error', 'Terjadi kesalahan sistem');
                });
            });
        });

        // Show alert function
        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show">
                    <i class="fas ${iconClass}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Insert at top of main content
            const mainContent = document.querySelector('.main-content');
            const pageHeader = document.querySelector('.page-header');
            pageHeader.insertAdjacentHTML('afterend', alertHtml);
            
            // Auto dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    </script>
</body>
</html>

<?php
// Helper functions
function getJenisIcon($jenis) {
    $icons = [
        'Plastik' => 'wine-bottle',
        'Kertas' => 'file-alt',
        'Logam' => 'cog',
        'Organik' => 'seedling',
        'Residu' => 'trash-alt'
    ];
    return $icons[$jenis] ?? 'recycle';
}

function getActionIcon($status) {
    $icons = [
        'pending' => 'clock',
        'approved' => 'check',
        'rejected' => 'times',
        'update' => 'edit'
    ];
    return $icons[$status] ?? 'history';
}
?>

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

.stat-card.primary { border-left-color: #007bff; }
.stat-card.success { border-left-color: #28a745; }
.stat-card.warning { border-left-color: #ffc107; }
.stat-card.info { border-left-color: #17a2b8; }

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

.stat-card.primary .stat-icon { background: #007bff; }
.stat-card.success .stat-icon { background: #28a745; }
.stat-card.warning .stat-icon { background: #ffc107; }
.stat-card.info .stat-icon { background: #17a2b8; }

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
    flex: 1;
}

.card-body {
    padding: 25px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
    color: #007bff;
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

.jenis-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.harga-display {
    font-size: 16px;
}

.action-buttons {
    display: flex;
    gap: 5px;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.timeline-body {
    color: #6c757d;
    font-size: 14px;
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
    
    .header-actions {
        width: 100%;
        justify-content: flex-start;
    }
}
</style>