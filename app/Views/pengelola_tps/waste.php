<?php
/**
 * TPS Waste Management - UI GreenMetric POLBAN
 * Manajemen sampah untuk pengelola TPS
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
$tps_info = $tps_info ?? ['nama_unit' => 'TPS'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Manajemen Sampah TPS' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Mobile Responsive CSS -->
    <link href="<?= base_url('/css/mobile-responsive.css') ?>" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar') ?>
    
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-trash-alt"></i> Manajemen Sampah TPS</h1>
            <p>Kelola data sampah untuk <?= $tps_info['nama_unit'] ?></p>
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
            
            <div class="stat-card secondary">
                <div class="stat-icon">
                    <i class="fas fa-file"></i>
                </div>
                <div class="stat-content">
                    <h3><?= $stats['draft_count'] ?? 0 ?></h3>
                    <p>Draft</p>
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
            <a href="<?= base_url('/pengelola-tps/waste/export-pdf') ?>" class="btn btn-danger" target="_blank">
                <i class="fas fa-file-pdf"></i> Export PDF
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
                
                <!-- Pagination untuk Informasi Harga Sampah -->
                <?php if (isset($pagerHarga) && $pagerHarga && $pagerHarga->getPageCount('harga') > 1): ?>
                <div class="mt-4">
                    <nav aria-label="Pagination Harga Sampah">
                        <ul class="pagination justify-content-center">
                            <!-- Previous Button -->
                            <?php if ($pagerHarga->getCurrentPage('harga') > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('/pengelola-tps/waste?page_harga=' . ($pagerHarga->getCurrentPage('harga') - 1)) ?>">
                                        <span>&laquo; Previous</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo; Previous</span>
                                </li>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $pagerHarga->getPageCount('harga'); $i++): ?>
                                <?php if ($i == $pagerHarga->getCurrentPage('harga')): ?>
                                    <li class="page-item active">
                                        <span class="page-link"><?= $i ?></span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= base_url('/pengelola-tps/waste?page_harga=' . $i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <!-- Next Button -->
                            <?php if ($pagerHarga->getCurrentPage('harga') < $pagerHarga->getPageCount('harga')): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('/pengelola-tps/waste?page_harga=' . ($pagerHarga->getCurrentPage('harga') + 1)) ?>">
                                        <span>Next &raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <span class="page-link">Next &raquo;</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
                
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
                <h3><i class="fas fa-list"></i> Data Sampah TPS</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($waste_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover" style="table-layout: auto; min-width: 100%;">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 140px;">Tanggal</th>
                                    <th style="width: 150px;">Jenis Sampah</th>
                                    <th style="width: 80px;">Berat</th>
                                    <th style="width: 70px;">Satuan</th>
                                    <th style="width: 100px;">Harga/Satuan</th>
                                    <th style="width: 120px;">Total Nilai</th>
                                    <th style="width: 100px;">Status</th>
                                    <th style="width: 120px; min-width: 120px;">Aksi</th>
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
                                    <td><?php 
                                        // Format angka tanpa desimal yang tidak perlu
                                        $berat = $waste['berat_kg'] ?? $waste['berat'] ?? $waste['jumlah_berat'] ?? 0;
                                        $jumlah = $waste['jumlah'] ?? $berat;
                                        // Jika angka bulat, tampilkan tanpa desimal
                                        echo ($jumlah == floor($jumlah)) ? number_format($jumlah, 0, ',', '.') : number_format($jumlah, 2, ',', '.');
                                    ?></td>
                                    <td><?= $waste['satuan'] ?? 'kg' ?></td>
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
                                    <td style="white-space: nowrap;">
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
                                        <span class="text-muted" style="font-size: 11px;">Tidak dapat diedit</span>
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
                            <label for="kategori_id" class="form-label">Jenis Sampah *</label>
                            <select class="form-select" id="kategori_id" name="kategori_id" required>
                                <option value="">Pilih Jenis Sampah</option>
                                <?php 
                                // Gunakan allCategories untuk dropdown (semua data tanpa pagination)
                                $dropdownCategories = isset($allCategories) && !empty($allCategories) ? $allCategories : $categories;
                                foreach ($dropdownCategories as $category): 
                                ?>
                                <option value="<?= $category['id'] ?>" 
                                        data-harga="<?= $category['harga_per_satuan'] ?>"
                                        data-satuan="<?= $category['satuan'] ?>"
                                        data-jenis="<?= $category['jenis_sampah'] ?>"
                                        data-dapat-dijual="<?= $category['dapat_dijual'] ?>">
                                    <?= $category['nama_jenis'] ?> (<?= $category['jenis_sampah'] ?>)
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
                                        <option value="gram">Gram (g)</option>
                                        <option value="ton">Ton</option>
                                        <option value="liter">Liter (L)</option>
                                        <option value="pcs">Pieces (pcs)</option>
                                        <option value="karung">Karung</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="harga_display" class="form-label">Harga per Satuan</label>
                            <input type="text" class="form-control" id="harga_display" name="harga_display" readonly value="Rp 0">
                            <small class="text-muted" id="konversi_info"></small>
                        </div>
                        <div class="mb-3">
                            <label for="total_nilai_display" class="form-label">Total Nilai</label>
                            <input type="text" class="form-control fw-bold text-success" id="total_nilai_display" name="total_nilai_display" readonly value="Rp 0" style="font-size: 1.2em;">
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
                            <label for="edit_kategori_id_display" class="form-label">Jenis Sampah *</label>
                            <select class="form-select bg-light" id="edit_kategori_id_display" name="kategori_id_display" disabled style="cursor: not-allowed; opacity: 0.6;">
                                <option value="">Pilih Jenis Sampah</option>
                                <?php 
                                // Gunakan allCategories untuk dropdown (semua data tanpa pagination)
                                $dropdownCategories = isset($allCategories) && !empty($allCategories) ? $allCategories : $categories;
                                foreach ($dropdownCategories as $category): 
                                ?>
                                <option value="<?= $category['id'] ?>" 
                                        data-harga="<?= $category['harga_per_satuan'] ?>"
                                        data-satuan="<?= $category['satuan'] ?>"
                                        data-jenis="<?= $category['jenis_sampah'] ?>"
                                        data-dapat-dijual="<?= $category['dapat_dijual'] ?>">
                                    <?= $category['nama_jenis'] ?> (<?= $category['jenis_sampah'] ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" id="edit_kategori_id" name="kategori_id">
                            <small class="text-muted"><i class="fas fa-lock"></i> Jenis sampah tidak dapat diubah saat edit</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_berat" class="form-label">Jumlah *</label>
                                    <input type="number" class="form-control" id="edit_berat" name="berat_kg" step="0.01" min="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_unit_id" class="form-label">Satuan *</label>
                                    <input type="text" class="form-control bg-light" id="edit_unit_id" name="satuan" readonly style="cursor: not-allowed;">
                                    <small class="text-muted"><i class="fas fa-lock"></i> Satuan mengikuti data awal</small>
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

        // Konversi dari kg ke satuan lain
        function konversiDariKg(beratKg, satuan) {
            const konversi = {
                'kg': 1,
                'ton': 1000,
                'gram': 0.001,
                'liter': 1,
                'pcs': 0.1,
                'karung': 25
            };
            return beratKg / (konversi[satuan] || 1);
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
            const hargaPerSatuan = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const satuanMaster = selectedOption.getAttribute('data-satuan') || 'kg'; // Satuan dari master harga
            const jenisKategori = selectedOption.getAttribute('data-jenis') || '';
            const dapatDijual = selectedOption.getAttribute('data-dapat-dijual') == '1';

            const jumlah = parseFloat(jumlahInput.value) || 0;
            const satuan = satuanSelect.value;

            // Set satuan default jika belum dipilih
            if (!satuan && jenisKategori) {
                satuanSelect.value = satuanMaster;
            }

            if (!satuan || !jumlah || !jenisKategori) {
                hargaDisplay.value = 'Rp 0';
                totalDisplay.value = 'Rp 0';
                konversiInfo.textContent = '';
                return;
            }

            // Konversi ke kg untuk perhitungan
            const jumlahKg = konversiKeKg(jumlah, satuan);
            const hargaPerKg = hargaPerSatuan / konversiKeKg(1, satuanMaster); // Konversi harga ke per kg

            // Update displays - GUNAKAN SATUAN DARI MASTER
            hargaDisplay.value = 'Rp ' + hargaPerSatuan.toLocaleString('id-ID') + '/' + satuanMaster;

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
            
            fetch('<?= base_url('/pengelola-tps/waste/save') ?>', {
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
            
            // Konversi jumlah ke kg untuk disimpan
            const berat = parseFloat(formData.get('berat_kg')) || 0;
            const satuan = formData.get('satuan') || 'kg';
            const beratKg = konversiKeKg(berat, satuan);
            
            // Update berat_kg dengan nilai yang sudah dikonversi
            formData.set('berat_kg', beratKg);
            
            // Get action from clicked button
            const action = e.submitter ? e.submitter.value : 'draft';
            formData.append('status_action', action);
            
            fetch(`<?= base_url('/pengelola-tps/waste/edit/') ?>${wasteId}`, {
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

        // Edit waste form - calculate estimate
        function calculateEditEstimate() {
            const kategoriSelect = document.getElementById('edit_kategori_id_display');
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
            const berat = parseFloat(document.getElementById('edit_berat').value) || 0;
            const satuan = document.getElementById('edit_unit_id').value || 'kg';
            
            // Konversi ke kg
            const beratKg = konversiKeKg(berat, satuan);
            
            // Update displays
            document.getElementById('edit_berat_kg_display').textContent = beratKg.toLocaleString('id-ID') + ' kg';
            
            const total = harga * beratKg;
            document.getElementById('edit_estimasi_nilai').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('edit_berat').addEventListener('input', calculateEditEstimate);

        // Edit waste function
        function editWaste(id) {
            // Fetch waste data
            fetch(`<?= base_url('/pengelola-tps/waste/get/') ?>${id}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const waste = data.data;
                    
                    // Find kategori_id from jenis_sampah
                    const kategoriSelect = document.getElementById('edit_kategori_id_display');
                    let kategoriId = null;
                    
                    // Try to find matching category
                    for (let option of kategoriSelect.options) {
                        const optionText = option.textContent.toLowerCase();
                        const wasteJenis = (waste.jenis_sampah || '').toLowerCase();
                        
                        // Check if option contains the jenis_sampah (in parentheses)
                        if (option.value && optionText.includes(wasteJenis)) {
                            kategoriId = option.value;
                            kategoriSelect.value = option.value;
                            break;
                        }
                    }
                    
                    // Populate form
                    document.getElementById('edit_waste_id').value = waste.id;
                    document.getElementById('edit_kategori_id').value = kategoriId || waste.kategori_id || '';
                    
                    // Konversi berat_kg kembali ke satuan asli
                    const beratKg = waste.berat_kg || waste.jumlah || waste.berat || 0;
                    const satuan = waste.satuan || 'kg';
                    const beratAsli = konversiDariKg(beratKg, satuan);
                    
                    document.getElementById('edit_berat').value = beratAsli;
                    document.getElementById('edit_unit_id').value = satuan;
                    
                    // Calculate estimate
                    calculateEditEstimate();
                    
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
                const formData = new FormData();
                formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                
                fetch(`<?= base_url('/pengelola-tps/waste/delete/') ?>${id}`, {
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
    </script>
    <!-- Mobile Menu JS -->
    <script src="<?= base_url('/js/mobile-menu.js') ?>"></script>
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

/* ===== ACTION BUTTONS ===== */
.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
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

/* ===== STATISTICS CARDS ===== */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.stat-card.primary .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card.warning .stat-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card.success .stat-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-card.secondary .stat-icon {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
}

.stat-card.info .stat-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.stat-card .stat-content {
    flex: 1;
}

.stat-card .stat-content h3 {
    font-size: 32px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: #2c3e50;
}

.stat-card .stat-content p {
    font-size: 14px;
    color: #7f8c8d;
    margin: 0;
    font-weight: 500;
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
    
    .action-buttons {
        flex-direction: column;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .stat-card {
        padding: 20px;
    }
    
    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
        margin-right: 15px;
    }
    
    .stat-card .stat-content h3 {
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