<?php
/**
 * User Waste Management - UI GreenMetric POLBAN
 * Manajemen sampah untuk user
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
$categories = $categories ?? [];
$unit = $unit ?? ['nama_unit' => 'Unit'];
$stats = $stats ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Manajemen Sampah User' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_user') ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-trash-alt"></i> Manajemen Sampah</h1>
            <p>Kelola data sampah untuk <?= $unit['nama_unit'] ?></p>
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

        <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?= $error ?>
        </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <?php if (!empty($stats)): ?>
        <div class="stats-grid mb-4">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['total_entries'] ?? 0 ?></h3>
                    <p>Total Data</p>
                </div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['pending_count'] ?? 0 ?></h3>
                    <p>Menunggu Review</p>
                </div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['approved_count'] ?? 0 ?></h3>
                    <p>Disetujui</p>
                </div>
            </div>
            
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-weight-hanging"></i>
                </div>
                <div class="stat-content">
                    <h3><?= formatNumber($stats['total_weight'] ?? 0) ?> kg</h3>
                    <p>Total Berat</p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="action-buttons mb-4">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWasteModal">
                <i class="fas fa-plus"></i> Tambah Data Sampah
            </button>
            <a href="<?= base_url('/user/waste/export') ?>" class="btn btn-success">
                <i class="fas fa-download"></i> Export Data
            </a>
        </div>

        <!-- Informasi Harga Sampah -->
        <?php if (!empty($categories)): ?>
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 20px;">
                <h3 style="margin: 0;"><i class="fas fa-money-bill-wave"></i> Informasi Harga Sampah</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($categories as $category): ?>
                    <div class="col-md-4 mb-3">
                        <div class="price-card <?= $category['dapat_dijual'] ? 'sellable' : 'not-sellable' ?>" style="background: white; border-radius: 12px; padding: 20px; border: 2px solid <?= $category['dapat_dijual'] ? '#28a745' : '#6c757d' ?>;">
                            <div class="price-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 2px solid #e9ecef;">
                                <h5 style="margin: 0; color: #2c3e50; font-weight: 700;"><?= htmlspecialchars($category['jenis_sampah']) ?></h5>
                                <?php if ($category['dapat_dijual']): ?>
                                <span class="badge bg-success">Bisa Dijual</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">Tidak Dijual</span>
                                <?php endif; ?>
                            </div>
                            <div class="price-body">
                                <p class="category-name" style="color: #6c757d; font-size: 14px; margin-bottom: 15px;"><?= htmlspecialchars($category['nama_jenis']) ?></p>
                                <?php if ($category['dapat_dijual']): ?>
                                <div class="price-info" style="display: flex; align-items: baseline; gap: 8px; padding: 12px; background: rgba(40, 167, 69, 0.1); border-radius: 8px;">
                                    <span class="price-label" style="font-size: 13px; color: #6c757d;">Harga:</span>
                                    <span class="price-value" style="font-size: 20px; font-weight: 700; color: #28a745;"><?= formatCurrency($category['harga_per_satuan']) ?></span>
                                    <span class="price-unit" style="font-size: 14px; color: #6c757d;">/ <?= htmlspecialchars($category['satuan']) ?></span>
                                </div>
                                <?php else: ?>
                                <div class="price-info text-muted" style="padding: 12px; background: rgba(108, 117, 125, 0.1); border-radius: 8px;">
                                    <small>Sampah ini tidak memiliki nilai jual</small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle"></i>
                    <strong>Catatan:</strong> Harga sampah dapat berubah sewaktu-waktu. 
                    Nilai penjualan akan dihitung otomatis saat Anda menginput data sampah.
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="alert alert-warning mb-4">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian:</strong> Data kategori sampah belum tersedia. Silakan hubungi administrator.
        </div>
        <?php endif; ?>

        <!-- Waste Data Table -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-list"></i> Data Sampah Anda</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($waste_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Berat (kg)</th>
                                    <th>Harga/kg</th>
                                    <th>Total Nilai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($waste_list as $index => $waste): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($waste['created_at'])) ?></td>
                                    <td>
                                        <span class="badge bg-primary"><?= $waste['jenis_sampah'] ?? 'N/A' ?></span>
                                    </td>
                                    <td><?= number_format($waste['berat_kg'], 2) ?></td>
                                    <td>-</td>
                                    <td><?= formatCurrency($waste['nilai_rupiah'] ?? 0) ?></td>
                                    <td>
                                        <?php
                                        $statusClass = match($waste['status'] ?? 'draft') {
                                            'disetujui' => 'success',
                                            'dikirim' => 'info',
                                            'review' => 'warning',
                                            'perlu_revisi' => 'danger',
                                            'draft' => 'secondary',
                                            default => 'secondary'
                                        };
                                        $statusLabel = match($waste['status'] ?? 'draft') {
                                            'disetujui' => 'Disetujui',
                                            'dikirim' => 'Dikirim',
                                            'review' => 'Review',
                                            'perlu_revisi' => 'Perlu Revisi',
                                            'draft' => 'Draft',
                                            default => 'Draft'
                                        };
                                        ?>
                                        <span class="badge bg-<?= $statusClass ?>"><?= $statusLabel ?></span>
                                    </td>
                                    <td>
                                        <?php if (in_array($waste['status'] ?? 'draft', ['draft', 'perlu_revisi'])): ?>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" onclick="editWaste(<?= $waste['id'] ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" onclick="deleteWaste(<?= $waste['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <?php else: ?>
                                        <span class="text-muted">Tidak dapat diedit</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada data sampah. Mulai dengan menambah data baru.</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWasteModal">
                            <i class="fas fa-plus"></i> Tambah Data Pertama
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Waste Modal -->
    <div class="modal fade" id="addWasteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addWasteForm">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori Sampah *</label>
                            <select class="form-select" id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" 
                                        data-harga="<?= $category['harga_per_satuan'] ?>"
                                        data-satuan="<?= $category['satuan'] ?>"
                                        data-jenis="<?= $category['jenis_sampah'] ?>"
                                        data-dapat-dijual="<?= $category['dapat_dijual'] ?>">
                                    <?= $category['jenis_sampah'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah *</label>
                                    <input type="number" class="form-control" id="jumlah" name="jumlah" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="satuan" class="form-label">Satuan *</label>
                                    <select class="form-select" id="satuan" name="satuan" required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="ton">Ton</option>
                                        <option value="gram">Gram (g)</option>
                                        <option value="liter">Liter (L)</option>
                                        <option value="pcs">Pieces (pcs)</option>
                                        <option value="karung">Karung</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Harga per Satuan</label>
                            <input type="text" class="form-control" id="harga_display" readonly value="Rp 0">
                            <small class="text-muted" id="konversi_info"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Nilai</label>
                            <input type="text" class="form-control fw-bold text-success" id="total_nilai_display" readonly value="Rp 0" style="font-size: 1.2em;">
                            <small class="text-muted">* Hanya untuk sampah yang dapat dijual</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary">Simpan sebagai Draft</button>
                        <button type="submit" name="action" value="kirim" class="btn btn-primary">Simpan & Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Waste Modal -->
    <div class="modal fade" id="editWasteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Sampah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editWasteForm">
                    <?= csrf_field() ?>
                    <input type="hidden" id="edit_waste_id" name="waste_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kategori_id" class="form-label">Kategori Sampah</label>
                            <select class="form-select" id="edit_kategori_id_display" disabled>
                                <option value="">Pilih Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>" data-harga="<?= $category['harga_per_satuan'] ?>">
                                    <?= $category['jenis_sampah'] ?> - <?= formatCurrency($category['harga_per_satuan']) ?>/<?= $category['satuan'] ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="edit_kategori_id" name="kategori_id">
                            <small class="text-muted">Kategori sampah tidak dapat diubah saat edit</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_berat" class="form-label">Jumlah</label>
                                    <input type="number" class="form-control" id="edit_berat" name="berat" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_unit_id" class="form-label">Satuan</label>
                                    <select class="form-select" id="edit_unit_id" name="unit_id" required>
                                        <option value="kg">Kilogram (kg)</option>
                                        <option value="ton">Ton</option>
                                        <option value="gram">Gram</option>
                                        <option value="liter">Liter</option>
                                        <option value="pcs">Pieces (pcs)</option>
                                        <option value="karung">Karung</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="alert alert-info">
                                <strong>Berat dalam kg:</strong> <span id="edit_berat_kg_display">0 kg</span><br>
                                <strong>Estimasi Nilai:</strong> <span id="edit_estimasi_nilai">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="action" value="draft" class="btn btn-outline-primary">Simpan sebagai Draft</button>
                        <button type="submit" name="action" value="kirim" class="btn btn-primary">Simpan & Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Konversi satuan ke kg
        function konversiKeKg(jumlah, satuan) {
            const konversi = {
                'kg': 1,
                'ton': 1000,
                'gram': 0.001,
                'liter': 1, // Asumsi 1 liter = 1 kg untuk sampah
                'pcs': 0.1, // Asumsi 1 pcs = 0.1 kg
                'karung': 25 // Asumsi 1 karung = 25 kg
            };
            return jumlah * (konversi[satuan] || 1);
        }

        // Calculate estimated value with auto-update
        function calculateEstimate() {
            const kategoriSelect = document.getElementById('kategori_id');
            const jumlahInput = document.getElementById('jumlah');
            const satuanSelect = document.getElementById('satuan');
            const hargaDisplay = document.getElementById('harga_display');
            const konversiInfo = document.getElementById('konversi_info');
            const totalDisplay = document.getElementById('total_nilai_display');
            
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const hargaPerKg = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const satuanDefault = selectedOption.getAttribute('data-satuan') || 'kg';
            const jenisKategori = selectedOption.getAttribute('data-jenis') || '';
            const dapatDijual = selectedOption.getAttribute('data-dapat-dijual') == '1';
            const jumlah = parseFloat(jumlahInput.value) || 0;
            const satuan = satuanSelect.value;
            
            // Set satuan default jika belum dipilih
            if (!satuan && jenisKategori) {
                satuanSelect.value = satuanDefault;
            }
            
            if (!satuan || !jumlah || !jenisKategori) {
                hargaDisplay.value = 'Rp 0';
                totalDisplay.value = 'Rp 0';
                konversiInfo.textContent = '';
                return;
            }
            
            // Konversi ke kg
            const jumlahKg = konversiKeKg(jumlah, satuan);
            
            // Update displays
            hargaDisplay.value = 'Rp ' + hargaPerKg.toLocaleString('id-ID') + '/kg';
            
            // Info konversi
            if (satuan !== 'kg') {
                konversiInfo.textContent = `${jumlah} ${satuan} = ${jumlahKg.toLocaleString('id-ID')} kg`;
            } else {
                konversiInfo.textContent = '';
            }
            
            if (dapatDijual && jumlahKg > 0) {
                const total = hargaPerKg * jumlahKg;
                totalDisplay.value = 'Rp ' + total.toLocaleString('id-ID');
                totalDisplay.classList.add('text-success');
                totalDisplay.classList.remove('text-muted');
            } else {
                totalDisplay.value = dapatDijual ? 'Rp 0' : 'Tidak dapat dijual';
                totalDisplay.classList.remove('text-success');
                totalDisplay.classList.add('text-muted');
            }
        }

        // Add event listeners
        document.getElementById('kategori_id').addEventListener('change', function() {
            // Set satuan default saat kategori dipilih
            const selectedOption = this.options[this.selectedIndex];
            const satuanDefault = selectedOption.getAttribute('data-satuan') || 'kg';
            document.getElementById('satuan').value = satuanDefault;
            calculateEstimate();
        });
        document.getElementById('jumlah').addEventListener('input', calculateEstimate);
        document.getElementById('satuan').addEventListener('change', calculateEstimate);

        // Submit add waste form
        document.getElementById('addWasteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Konversi jumlah ke kg untuk disimpan
            const jumlah = parseFloat(formData.get('jumlah')) || 0;
            const satuan = formData.get('satuan');
            const beratKg = konversiKeKg(jumlah, satuan);
            
            // Tambahkan berat_kg ke form data
            formData.append('berat_kg', beratKg);
            
            // Get action from clicked button
            const action = e.submitter ? e.submitter.value : 'draft';
            formData.append('status_action', action);
            
            fetch('<?= base_url('/user/waste/save') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan data');
            });
        });

        // Submit edit waste form
        document.getElementById('editWasteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const wasteId = document.getElementById('edit_waste_id').value;
            const formData = new FormData(this);
            
            // Get action from clicked button
            const action = e.submitter ? e.submitter.value : 'draft';
            formData.append('status_action', action);
            
            fetch(`<?= base_url('/user/waste/edit/') ?>${wasteId}`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate data');
            });
        });

        // Edit waste function
        function editWaste(id) {
            // Fetch waste data
            fetch(`<?= base_url('/user/waste/get/') ?>${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const waste = data.data;
                    
                    // Populate form
                    document.getElementById('edit_waste_id').value = waste.id;
                    document.getElementById('edit_kategori_id').value = waste.kategori_id || waste.jenis_sampah;
                    document.getElementById('edit_kategori_id_display').value = waste.kategori_id || waste.jenis_sampah;
                    document.getElementById('edit_berat').value = waste.berat;
                    document.getElementById('edit_unit_id').value = waste.unit_id || 'kg';
                    
                    // Disable kategori if status is draft or submitted
                    const isLocked = ['draft', 'dikirim', 'disetujui'].includes(waste.status);
                    document.getElementById('edit_kategori_id_display').disabled = true; // Always disabled
                    
                    // Calculate estimate
                    hitungEditEstimasi();
                    
                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editWasteModal'));
                    editModal.show();
                } else {
                    alert('Gagal mengambil data: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data');
            });
        }

        // Delete waste function
        function deleteWaste(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data sampah ini?')) {
                fetch(`<?= base_url('/user/waste/delete/') ?>${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
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

        // Edit waste form - calculate estimate
        function hitungEditEstimasi() {
            const kategoriSelect = document.getElementById('edit_kategori_id_display');
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const jumlah = parseFloat(document.getElementById('edit_berat').value) || 0;
            const satuan = document.getElementById('edit_unit_id').value;
            
            const beratKg = konversiKeKg(jumlah, satuan);
            const total = harga * beratKg;
            
            document.getElementById('edit_berat_kg_display').textContent = beratKg.toFixed(2) + ' kg';
            document.getElementById('edit_estimasi_nilai').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        
        document.getElementById('edit_berat').addEventListener('input', hitungEditEstimasi);
        document.getElementById('edit_unit_id').addEventListener('change', hitungEditEstimasi);
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

/* ===== STATISTICS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
    flex-shrink: 0;
}

.stat-card.primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.success .stat-icon { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.stat-card.warning .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.info .stat-icon { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }

.stat-content {
    flex: 1;
}

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

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

/* ===== PRICE CARDS ===== */
.price-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
    height: 100%;
}

.price-card.sellable {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
}

.price-card.not-sellable {
    border-color: #6c757d;
    background: #f8f9fa;
}

.price-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.price-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e9ecef;
}

.price-header h5 {
    margin: 0;
    color: #2c3e50;
    font-weight: 700;
    font-size: 18px;
}

.price-body {
    margin-top: 10px;
}

.category-name {
    color: #6c757d;
    font-size: 14px;
    margin-bottom: 15px;
}

.price-info {
    display: flex;
    align-items: baseline;
    gap: 8px;
    padding: 12px;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 8px;
}

.price-card.not-sellable .price-info {
    background: rgba(108, 117, 125, 0.1);
}

.price-label {
    font-size: 13px;
    color: #6c757d;
    font-weight: 500;
}

.price-value {
    font-size: 20px;
    font-weight: 700;
    color: #28a745;
}

.price-card.not-sellable .price-value {
    color: #6c757d;
}

.price-unit {
    font-size: 14px;
    color: #6c757d;
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
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .page-header h1 {
        font-size: 24px;
    }
    
    .action-buttons {
        flex-direction: column;
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