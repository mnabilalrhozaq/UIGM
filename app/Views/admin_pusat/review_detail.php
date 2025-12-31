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

        .back-btn {
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

        .back-btn:hover {
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

        .unit-summary {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 40px;
            border: 1px solid rgba(92, 140, 191, 0.1);
        }

        .unit-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .unit-info h2 {
            color: #5c8cbf;
            margin-bottom: 8px;
            font-size: 24px;
            font-weight: 700;
        }

        .unit-meta {
            color: #666;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .status-badge {
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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

        .review-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #5c8cbf;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .category-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .category-review {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .category-header {
            background: #5c8cbf;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .category-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .category-body {
            padding: 25px;
        }

        .review-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .data-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }

        .section-title {
            font-weight: 600;
            color: #5c8cbf;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .data-item {
            margin-bottom: 15px;
        }

        .data-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .data-value {
            color: #666;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #5c8cbf;
            box-shadow: 0 0 0 2px rgba(92, 140, 191, 0.2);
        }

        .review-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 12px;
        }

        .btn-approve {
            background: #28a745;
            color: white;
        }

        .btn-approve:hover {
            background: #218838;
        }

        .btn-reject {
            background: #dc3545;
            color: white;
        }

        .btn-reject:hover {
            background: #c82333;
        }

        .review-status {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .status-pending {
            color: #ffc107;
        }

        .status-approved {
            color: #28a745;
        }

        .status-rejected {
            color: #dc3545;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .close:hover {
            color: #333;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .unit-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .review-content {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
            }

            .review-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-clipboard-check"></i> Review Detail Data UIGM</h1>
                <p><?= $pengiriman['nama_unit'] ?> - <?= $pengiriman['tahun'] ?></p>
            </div>
            <div class="header-info">
                <span><?= $user['nama_lengkap'] ?></span>
                <a href="/admin-pusat/monitoring" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Unit Summary -->
        <div class="unit-summary">
            <div class="unit-header">
                <div class="unit-info">
                    <h2><?= $pengiriman['nama_unit'] ?></h2>
                    <div class="unit-meta">
                        <span><i class="fas fa-code"></i> <?= $pengiriman['kode_unit'] ?></span> |
                        <span><i class="fas fa-building"></i> <?= ucfirst($pengiriman['tipe_unit']) ?></span> |
                        <span><i class="fas fa-calendar"></i> <?= $pengiriman['nama_periode'] ?></span>
                    </div>
                </div>
                <div>
                    <span class="status-badge status-<?= $pengiriman['status_pengiriman'] ?>">
                        <?= ucfirst(str_replace('_', ' ', $pengiriman['status_pengiriman'])) ?>
                    </span>
                </div>
            </div>

            <div class="review-stats">
                <div class="stat-item">
                    <div class="stat-number"><?= $reviewStats['total'] ?></div>
                    <div class="stat-label">Total Kategori</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $reviewStats['disetujui'] ?></div>
                    <div class="stat-label">Disetujui</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $reviewStats['perlu_revisi'] ?></div>
                    <div class="stat-label">Perlu Revisi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?= $reviewStats['pending'] ?></div>
                    <div class="stat-label">Belum Review</div>
                </div>
            </div>

            <div class="action-buttons">
                <?php if ($canFinalize): ?>
                    <button type="button" class="btn btn-success" id="btn-finalize">
                        <i class="fas fa-check-double"></i>
                        Setujui Semua & Finalisasi
                    </button>
                <?php endif; ?>

                <button type="button" class="btn btn-danger" id="btn-return-revision">
                    <i class="fas fa-undo"></i>
                    Kembalikan untuk Revisi
                </button>
            </div>
        </div>

        <!-- Category Reviews -->
        <div class="category-grid">
            <?php foreach ($kategori as $kat): ?>
                <?php
                $review = $reviewData[$kat['id']];
                $dataInput = !empty($review['data_input']) ? json_decode($review['data_input'], true) : [];
                $statusClass = $review['status_review'];
                ?>

                <div class="category-review" data-kategori="<?= $kat['id'] ?>">
                    <div class="category-header">
                        <div class="category-title">
                            <div class="category-icon">
                                <i class="fas fa-<?= getCategoryIcon($kat['kode_kategori']) ?>"></i>
                            </div>
                            <div>
                                <h3><?= $kat['nama_kategori'] ?></h3>
                                <div><?= $kat['kode_kategori'] ?> - Bobot: <?= $kat['bobot'] ?>%</div>
                            </div>
                        </div>
                        <div class="review-status status-<?= $statusClass ?>">
                            <i class="fas fa-<?= $statusClass === 'disetujui' ? 'check-circle' : ($statusClass === 'perlu_revisi' ? 'times-circle' : 'clock') ?>"></i>
                            <?= ucfirst(str_replace('_', ' ', $statusClass)) ?>
                        </div>
                    </div>

                    <div class="category-body">
                        <div class="review-content">
                            <!-- Data dari Admin Unit -->
                            <div class="data-section">
                                <div class="section-title">
                                    <i class="fas fa-upload"></i>
                                    Data dari Admin Unit
                                </div>

                                <?php if (!empty($dataInput)): ?>
                                    <div class="data-item">
                                        <div class="data-label">Tanggal Input:</div>
                                        <div class="data-value">
                                            <?= isset($dataInput['tanggal_input']) ? date('d/m/Y', strtotime($dataInput['tanggal_input'])) : '-' ?>
                                        </div>
                                    </div>

                                    <div class="data-item">
                                        <div class="data-label">Gedung/Lokasi:</div>
                                        <div class="data-value"><?= htmlspecialchars($dataInput['gedung'] ?? '-') ?></div>
                                    </div>

                                    <?php if (!empty($dataInput['jenis_sampah'])): ?>
                                        <div class="data-item">
                                            <div class="data-label">Jenis Sampah:</div>
                                            <div class="data-value"><?= htmlspecialchars($dataInput['jenis_sampah']) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="data-item">
                                        <div class="data-label">Jumlah/Nilai:</div>
                                        <div class="data-value">
                                            <?= number_format($dataInput['jumlah'] ?? 0, 2) ?>
                                            <?= htmlspecialchars($dataInput['satuan'] ?? getCategoryUnit($kat['kode_kategori'])) ?>
                                        </div>
                                    </div>

                                    <div class="data-item">
                                        <div class="data-label">Deskripsi Program/Kegiatan:</div>
                                        <div class="data-value"><?= nl2br(htmlspecialchars($dataInput['deskripsi'] ?? '-')) ?></div>
                                    </div>

                                    <?php if (!empty($dataInput['target_rencana'])): ?>
                                        <div class="data-item">
                                            <div class="data-label">Target/Rencana:</div>
                                            <div class="data-value"><?= nl2br(htmlspecialchars($dataInput['target_rencana'])) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($dataInput['catatan'])): ?>
                                        <div class="data-item">
                                            <div class="data-label">Catatan:</div>
                                            <div class="data-value"><?= nl2br(htmlspecialchars($dataInput['catatan'])) ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Backward compatibility display -->
                                    <?php if (isset($dataInput['nilai_numerik']) && !isset($dataInput['jumlah'])): ?>
                                        <div class="data-item">
                                            <div class="data-label">Nilai Numerik (Legacy):</div>
                                            <div class="data-value">
                                                <?= number_format($dataInput['nilai_numerik'], 2) ?>
                                                <?= getCategoryUnit($kat['kode_kategori']) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Belum ada data yang diinput oleh Admin Unit
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Review dari Admin Pusat -->
                            <div class="data-section">
                                <div class="section-title">
                                    <i class="fas fa-clipboard-check"></i>
                                    Review Admin Pusat
                                </div>

                                <form class="review-form" data-kategori-id="<?= $kat['id'] ?>">
                                    <div class="form-group">
                                        <label class="form-label">Status Review:</label>
                                        <select class="form-control" name="status_review">
                                            <option value="pending" <?= $review['status_review'] === 'pending' ? 'selected' : '' ?>>Belum Review</option>
                                            <option value="disetujui" <?= $review['status_review'] === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                                            <option value="perlu_revisi" <?= $review['status_review'] === 'perlu_revisi' ? 'selected' : '' ?>>Perlu Revisi</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Catatan Review:</label>
                                        <textarea class="form-control" name="catatan_review" rows="4" placeholder="Berikan catatan atau saran untuk kategori ini..."><?= htmlspecialchars($review['catatan_review'] ?? '') ?></textarea>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Skor Review (Opsional):</label>
                                        <input type="number" class="form-control" name="skor_review" step="0.1" min="0" max="100"
                                            value="<?= isset($review['skor_review']) && $review['skor_review'] !== null ? number_format($review['skor_review'], 1) : '' ?>"
                                            placeholder="0-100">
                                        <small class="text-muted">Kosongkan jika tidak ingin memberikan skor numerik</small>
                                    </div>

                                    <div class="review-actions">
                                        <button type="button" class="btn btn-approve btn-sm btn-save-review" data-kategori-id="<?= $kat['id'] ?>">
                                            <i class="fas fa-save"></i>
                                            Simpan Review
                                        </button>
                                    </div>
                                </form>

                                <?php if (!empty($review['tanggal_review'])): ?>
                                    <div class="alert alert-info">
                                        <small>
                                            <i class="fas fa-clock"></i>
                                            Direview: <?= date('d/m/Y H:i', strtotime($review['tanggal_review'])) ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal Konfirmasi Revisi -->
    <div id="revisionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Kembalikan untuk Revisi</h3>
                <button type="button" class="close" onclick="closeModal('revisionModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Catatan Umum untuk Revisi:</label>
                    <textarea id="catatan-revisi" class="form-control" rows="4" placeholder="Berikan catatan umum mengapa data perlu direvisi..."></textarea>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn" onclick="closeModal('revisionModal')" style="background: #6c757d; color: white;">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmRevision()">
                        <i class="fas fa-undo"></i> Kembalikan untuk Revisi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const BASE_URL = '<?= base_url() ?>';
        const PENGIRIMAN_ID = <?= $pengiriman['id'] ?>;

        // Helper functions
        function getCategoryIcon(kodeKategori) {
            const icons = {
                'SI': 'building',
                'EC': 'bolt',
                'WS': 'recycle',
                'WR': 'tint',
                'TR': 'car',
                'ED': 'graduation-cap'
            };
            return icons[kodeKategori] || 'leaf';
        }

        function getCategoryUnit(kodeKategori) {
            const units = {
                'SI': 'm²',
                'EC': 'kWh',
                'WS': 'kg',
                'WR': 'liter',
                'TR': 'unit',
                'ED': 'program'
            };
            return units[kodeKategori] || 'unit';
        }

        function showToast(message, type = 'info') {
            // Simple toast notification
            const toast = $(`
                <div class="alert alert-${type}" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                </div>
            `);

            $('body').append(toast);

            setTimeout(() => {
                toast.fadeOut(() => toast.remove());
            }, 3000);
        }

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        $(document).ready(function() {
            // Simpan review kategori
            $('.btn-save-review').on('click', function() {
                const kategoriId = $(this).data('kategori-id');
                const form = $(`.review-form[data-kategori-id="${kategoriId}"]`);
                const button = $(this);

                const statusReview = form.find('[name="status_review"]').val();
                const catatanReview = form.find('[name="catatan_review"]').val();
                const skorReview = form.find('[name="skor_review"]').val();

                // Validasi input
                if (!statusReview) {
                    showToast('Status review harus dipilih', 'warning');
                    return;
                }

                if (statusReview === 'perlu_revisi' && !catatanReview.trim()) {
                    showToast('Catatan review wajib diisi untuk status "Perlu Revisi"', 'warning');
                    return;
                }

                const formData = {
                    pengiriman_id: PENGIRIMAN_ID,
                    indikator_id: kategoriId,
                    status_review: statusReview,
                    catatan_review: catatanReview.trim() || ''
                };

                // Hanya tambahkan skor_review jika ada nilai dan valid
                if (skorReview && skorReview.trim() !== '' && !isNaN(parseFloat(skorReview))) {
                    formData.skor_review = parseFloat(skorReview);
                }

                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: BASE_URL + '/admin-pusat/update-review',
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');

                            // Update status visual
                            const statusElement = $(`.category-review[data-kategori="${kategoriId}"] .review-status`);
                            const status = formData.status_review;
                            const statusText = status.replace('_', ' ');
                            const statusIcon = status === 'disetujui' ? 'check-circle' : (status === 'perlu_revisi' ? 'times-circle' : 'clock');

                            statusElement.removeClass('status-pending status-approved status-rejected')
                                .addClass(`status-${status}`)
                                .html(`<i class="fas fa-${statusIcon}"></i> ${statusText.charAt(0).toUpperCase() + statusText.slice(1)}`);

                            // Refresh page after 2 seconds to update stats
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showToast(response.message, 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        showToast('Terjadi kesalahan saat menyimpan review', 'danger');
                    },
                    complete: function() {
                        button.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Review');
                    }
                });
            });

            // Finalisasi pengiriman
            $('#btn-finalize').on('click', function() {
                if (!confirm('Yakin ingin memfinalisasi dan menyetujui semua data? Tindakan ini tidak dapat dibatalkan.')) {
                    return;
                }

                const button = $(this);
                button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memfinalisasi...');

                $.ajax({
                    url: BASE_URL + '/admin-pusat/finalize-pengiriman',
                    method: 'POST',
                    data: {
                        pengiriman_id: PENGIRIMAN_ID
                    },
                    success: function(response) {
                        if (response.success) {
                            showToast(response.message, 'success');
                            setTimeout(() => {
                                window.location.href = BASE_URL + '/admin-pusat/monitoring';
                            }, 2000);
                        } else {
                            showToast(response.message, 'danger');
                            button.prop('disabled', false).html('<i class="fas fa-check-double"></i> Setujui Semua & Finalisasi');
                        }
                    },
                    error: function() {
                        showToast('Terjadi kesalahan saat memfinalisasi', 'danger');
                        button.prop('disabled', false).html('<i class="fas fa-check-double"></i> Setujui Semua & Finalisasi');
                    }
                });
            });

            // Kembalikan untuk revisi
            $('#btn-return-revision').on('click', function() {
                openModal('revisionModal');
            });
        });

        function confirmRevision() {
            const catatanRevisi = document.getElementById('catatan-revisi').value;

            if (!catatanRevisi.trim()) {
                showToast('Harap berikan catatan untuk revisi', 'warning');
                return;
            }

            $.ajax({
                url: BASE_URL + '/admin-pusat/return-for-revision',
                method: 'POST',
                data: {
                    pengiriman_id: PENGIRIMAN_ID,
                    catatan_umum: catatanRevisi
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');
                        closeModal('revisionModal');
                        setTimeout(() => {
                            window.location.href = BASE_URL + '/admin-pusat/monitoring';
                        }, 2000);
                    } else {
                        showToast(response.message, 'danger');
                    }
                },
                error: function() {
                    showToast('Terjadi kesalahan saat mengembalikan data', 'danger');
                }
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.getElementsByClassName('modal');
            for (let modal of modals) {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            }
        }
    </script>
</body>

</html>

<?php
// Helper functions for category icons and units
if (!function_exists('getCategoryIcon')) {
    function getCategoryIcon($kodeKategori)
    {
        $icons = [
            'SI' => 'building',
            'EC' => 'bolt',
            'WS' => 'recycle',
            'WR' => 'tint',
            'TR' => 'car',
            'ED' => 'graduation-cap'
        ];
        return $icons[$kodeKategori] ?? 'leaf';
    }
}

if (!function_exists('getCategoryUnit')) {
    function getCategoryUnit($kodeKategori)
    {
        $units = [
            'SI' => 'm²',
            'EC' => 'kWh',
            'WS' => 'kg',
            'WR' => 'liter',
            'TR' => 'unit',
            'ED' => 'program'
        ];
        return $units[$kodeKategori] ?? 'unit';
    }
}
?>