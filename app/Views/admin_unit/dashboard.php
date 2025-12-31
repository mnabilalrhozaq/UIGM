<?= $this->extend('layouts/admin_unit') ?>

<?= $this->section('content') ?>

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

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error:</strong> <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<!-- Progress Section -->
<div class="progress-container">
    <!-- Debug Info (remove in production) -->
    <?php if (ENVIRONMENT === 'development'): ?>
        <div class="alert alert-info" style="margin-bottom: 15px;">
            <strong>DEBUG INFO:</strong><br>
            Can Edit: <?= isset($canEdit) ? ($canEdit ? 'TRUE' : 'FALSE') : 'NOT SET' ?><br>
            Pengiriman Status: <?= isset($pengiriman['status_pengiriman']) ? $pengiriman['status_pengiriman'] : 'NOT SET' ?><br>
            Pengiriman ID: <?= isset($pengiriman['id']) ? $pengiriman['id'] : 'NOT SET' ?>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Progress Pengisian Data</h4>
        <span class="badge bg-primary fs-6 progress-text"><?= isset($progress) ? number_format($progress, 0) : '0' ?>%</span>
    </div>

    <div class="progress-bar-custom">
        <div class="progress-fill" style="width: <?= isset($progress) ? $progress : 0 ?>%"></div>
    </div>

    <div class="mt-3">
        <?php
        $currentProgress = isset($progress) ? $progress : 0;
        $completedCategories = 0;
        $totalCategories = isset($kategori) ? count($kategori) : 6;

        if (isset($reviewData) && is_array($reviewData)) {
            foreach ($reviewData as $review) {
                if (!empty($review['data_input'])) {
                    $dataInput = json_decode($review['data_input'], true);
                    if (is_array($dataInput)) {
                        // Check new required fields
                        $requiredFields = ['tanggal_input', 'gedung', 'jumlah', 'satuan', 'deskripsi'];
                        $isComplete = true;

                        foreach ($requiredFields as $field) {
                            if (empty($dataInput[$field])) {
                                $isComplete = false;
                                break;
                            }
                        }

                        // Additional validation
                        if ($isComplete) {
                            if (!is_numeric($dataInput['jumlah']) || floatval($dataInput['jumlah']) <= 0) {
                                $isComplete = false;
                            }
                            if (strlen(trim($dataInput['deskripsi'])) < 10) {
                                $isComplete = false;
                            }
                        }

                        if ($isComplete) {
                            $completedCategories++;
                        }
                    }
                }
            }
        }
        ?>

        <?php if ($currentProgress < 100): ?>
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Lengkapi seluruh kategori sebelum mengirim data ke Admin Pusat.</strong>
                Progress saat ini: <?= $completedCategories ?> dari <?= $totalCategories ?> kategori.
            </div>
        <?php else: ?>
            <div class="alert alert-success mb-0">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Semua kategori sudah lengkap!</strong> Data siap untuk dikirim ke Admin Pusat.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Category Cards Grid -->
<div class="category-grid">
    <?php if (isset($kategori) && is_array($kategori)): ?>
        <?php foreach ($kategori as $index => $kat): ?>
            <?php
            $review = isset($reviewData[$kat['id']]) ? $reviewData[$kat['id']] : [];
            $dataInput = !empty($review['data_input']) ? json_decode($review['data_input'], true) : [];

            // Check completion with new required fields
            $isComplete = false;
            if (is_array($dataInput)) {
                $requiredFields = ['tanggal_input', 'gedung', 'jumlah', 'satuan', 'deskripsi'];
                $isComplete = true;

                foreach ($requiredFields as $field) {
                    if (empty($dataInput[$field])) {
                        $isComplete = false;
                        break;
                    }
                }

                // Additional validation
                if ($isComplete) {
                    if (!is_numeric($dataInput['jumlah']) || floatval($dataInput['jumlah']) <= 0) {
                        $isComplete = false;
                    }
                    if (strlen(trim($dataInput['deskripsi'])) < 10) {
                        $isComplete = false;
                    }
                }
            }

            $statusClass = $isComplete ? 'success' : 'secondary';
            $statusText = $isComplete ? 'Lengkap' : 'Belum Diisi';
            $statusIcon = $isComplete ? 'check-circle' : 'circle';
            $canEditCategory = isset($canEdit) ? $canEdit : false;

            // Debug output (remove in production)
            if (ENVIRONMENT === 'development') {
                echo "<!-- DEBUG: canEdit = " . ($canEdit ? 'true' : 'false') . ", status = " . ($pengiriman['status_pengiriman'] ?? 'unknown') . " -->";
            }
            ?>

            <div class="category-card" data-kategori="<?= $kat['id'] ?>">
                <!-- Category Header -->
                <div class="category-header" style="background: linear-gradient(135deg, <?= isset($kat['warna']) ? $kat['warna'] : '#5c8cbf' ?> 0%, <?= isset($kat['warna']) ? $kat['warna'] : '#5c8cbf' ?>dd 100%);">
                    <div class="category-icon">
                        <i class="fas fa-<?= getCategoryIcon($kat['kode_kategori'] ?? '') ?>"></i>
                    </div>
                    <div class="category-info">
                        <h3><?= htmlspecialchars($kat['nama_kategori'] ?? 'Kategori', ENT_QUOTES, 'UTF-8') ?></h3>
                        <div class="category-code"><?= htmlspecialchars($kat['kode_kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?> - Bobot: <?= isset($kat['bobot']) ? $kat['bobot'] : '0' ?>%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-<?= $statusIcon ?> me-1"></i>
                            <?= $statusText ?>
                        </span>
                    </div>
                </div>

                <!-- Category Body -->
                <div class="category-body">
                    <form class="category-form" data-kategori-id="<?= $kat['id'] ?>">
                        <!-- Tanggal Input -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-calendar me-1"></i>
                                Tanggal Input Data
                            </label>
                            <input
                                type="date"
                                class="form-control"
                                name="tanggal_input"
                                value="<?= htmlspecialchars($dataInput['tanggal_input'] ?? date('Y-m-d'), ENT_QUOTES, 'UTF-8') ?>"
                                <?= !$canEditCategory ? 'readonly' : '' ?>>
                        </div>

                        <!-- Gedung/Lokasi -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-building me-1"></i>
                                Gedung/Lokasi
                            </label>
                            <select class="form-control" name="gedung" <?= !$canEditCategory ? 'disabled' : '' ?>>
                                <option value="">Pilih Gedung/Lokasi</option>
                                <option value="Gedung A" <?= (($dataInput['gedung'] ?? '') === 'Gedung A') ? 'selected' : '' ?>>Gedung A</option>
                                <option value="Gedung B" <?= (($dataInput['gedung'] ?? '') === 'Gedung B') ? 'selected' : '' ?>>Gedung B</option>
                                <option value="Gedung C" <?= (($dataInput['gedung'] ?? '') === 'Gedung C') ? 'selected' : '' ?>>Gedung C</option>
                                <option value="Gedung D" <?= (($dataInput['gedung'] ?? '') === 'Gedung D') ? 'selected' : '' ?>>Gedung D</option>
                                <option value="Gedung E" <?= (($dataInput['gedung'] ?? '') === 'Gedung E') ? 'selected' : '' ?>>Gedung E</option>
                                <option value="Laboratorium" <?= (($dataInput['gedung'] ?? '') === 'Laboratorium') ? 'selected' : '' ?>>Laboratorium</option>
                                <option value="Perpustakaan" <?= (($dataInput['gedung'] ?? '') === 'Perpustakaan') ? 'selected' : '' ?>>Perpustakaan</option>
                                <option value="Kantin" <?= (($dataInput['gedung'] ?? '') === 'Kantin') ? 'selected' : '' ?>>Kantin</option>
                                <option value="Asrama" <?= (($dataInput['gedung'] ?? '') === 'Asrama') ? 'selected' : '' ?>>Asrama</option>
                                <option value="Area Parkir" <?= (($dataInput['gedung'] ?? '') === 'Area Parkir') ? 'selected' : '' ?>>Area Parkir</option>
                                <option value="Taman/Outdoor" <?= (($dataInput['gedung'] ?? '') === 'Taman/Outdoor') ? 'selected' : '' ?>>Taman/Outdoor</option>
                                <option value="Lainnya" <?= (($dataInput['gedung'] ?? '') === 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>

                        <!-- Jenis Sampah (untuk kategori WS - Waste) -->
                        <?php if (($kat['kode_kategori'] ?? '') === 'WS'): ?>
                            <div class="form-group">
                                <label class="form-label">
                                    <i class="fas fa-recycle me-1"></i>
                                    Jenis Sampah
                                </label>
                                <select class="form-control" name="jenis_sampah" id="jenis_sampah_<?= $kat['id'] ?>" <?= !$canEditCategory ? 'disabled' : '' ?>>
                                    <option value="">Pilih Jenis Sampah</option>
                                    <option value="Organik" <?= (($dataInput['jenis_sampah'] ?? '') === 'Organik') ? 'selected' : '' ?>>Sampah Organik</option>
                                    <option value="Anorganik" <?= (($dataInput['jenis_sampah'] ?? '') === 'Anorganik') ? 'selected' : '' ?>>Sampah Anorganik</option>
                                    <option value="Kertas" <?= (($dataInput['jenis_sampah'] ?? '') === 'Kertas') ? 'selected' : '' ?>>Kertas</option>
                                    <option value="Plastik" <?= (($dataInput['jenis_sampah'] ?? '') === 'Plastik') ? 'selected' : '' ?>>Plastik</option>
                                    <option value="Logam" <?= (($dataInput['jenis_sampah'] ?? '') === 'Logam') ? 'selected' : '' ?>>Logam</option>
                                    <option value="Kaca" <?= (($dataInput['jenis_sampah'] ?? '') === 'Kaca') ? 'selected' : '' ?>>Kaca</option>
                                    <option value="Elektronik" <?= (($dataInput['jenis_sampah'] ?? '') === 'Elektronik') ? 'selected' : '' ?>>Sampah Elektronik</option>
                                    <option value="B3" <?= (($dataInput['jenis_sampah'] ?? '') === 'B3') ? 'selected' : '' ?>>Bahan Berbahaya dan Beracun (B3)</option>
                                    <option value="Medis" <?= (($dataInput['jenis_sampah'] ?? '') === 'Medis') ? 'selected' : '' ?>>Sampah Medis</option>
                                    <option value="Campuran" <?= (($dataInput['jenis_sampah'] ?? '') === 'Campuran') ? 'selected' : '' ?>>Sampah Campuran</option>
                                </select>
                            </div>

                            <!-- Area Sampah Organik (muncul ketika pilih Organik) -->
                            <div class="form-group" id="area_sampah_group_<?= $kat['id'] ?>" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    Area Sampah Organik
                                </label>
                                <select class="form-control" name="area_sampah" id="area_sampah_<?= $kat['id'] ?>" <?= !$canEditCategory ? 'disabled' : '' ?>>
                                    <option value="">Pilih Area Sampah</option>
                                </select>
                            </div>

                            <!-- Detail Sampah Organik (muncul ketika pilih area) -->
                            <div class="form-group" id="detail_sampah_group_<?= $kat['id'] ?>" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-list-ul me-1"></i>
                                    Detail Sampah Organik
                                </label>
                                <select class="form-control" name="detail_sampah" id="detail_sampah_<?= $kat['id'] ?>" <?= !$canEditCategory ? 'disabled' : '' ?>>
                                    <option value="">Pilih Detail Sampah</option>
                                </select>
                            </div>
                        <?php endif; ?>

                        <!-- Satuan dan Jumlah -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calculator me-1"></i>
                                        Jumlah/Nilai
                                    </label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="jumlah"
                                        step="0.01"
                                        placeholder="Masukkan jumlah/nilai"
                                        value="<?= htmlspecialchars($dataInput['jumlah'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        <?= !$canEditCategory ? 'readonly' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-balance-scale me-1"></i>
                                        Satuan
                                    </label>
                                    <select class="form-control" name="satuan" <?= !$canEditCategory ? 'disabled' : '' ?>>
                                        <option value="">Pilih Satuan</option>
                                        <?php
                                        $defaultUnit = getCategoryUnit($kat['kode_kategori'] ?? '');
                                        $units = [
                                            'kg' => 'Kilogram (kg)',
                                            'ton' => 'Ton',
                                            'liter' => 'Liter',
                                            'm³' => 'Meter Kubik (m³)',
                                            'm²' => 'Meter Persegi (m²)',
                                            'kWh' => 'Kilowatt Hour (kWh)',
                                            'unit' => 'Unit/Buah',
                                            'program' => 'Program',
                                            'kegiatan' => 'Kegiatan',
                                            'orang' => 'Orang',
                                            'hari' => 'Hari',
                                            'bulan' => 'Bulan',
                                            'tahun' => 'Tahun',
                                            'persen' => 'Persen (%)'
                                        ];
                                        foreach ($units as $value => $label):
                                            $selected = (($dataInput['satuan'] ?? $defaultUnit) === $value) ? 'selected' : '';
                                        ?>
                                            <option value="<?= $value ?>" <?= $selected ?>><?= $label ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-align-left me-1"></i>
                                Deskripsi Program/Kegiatan
                            </label>
                            <textarea
                                class="form-control"
                                name="deskripsi"
                                rows="3"
                                placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori <?= htmlspecialchars($kat['nama_kategori'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                <?= !$canEditCategory ? 'readonly' : '' ?>><?= htmlspecialchars($dataInput['deskripsi'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- Target/Rencana -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-bullseye me-1"></i>
                                Target/Rencana Kedepan
                            </label>
                            <textarea
                                class="form-control"
                                name="target_rencana"
                                rows="2"
                                placeholder="Jelaskan target atau rencana pengembangan"
                                <?= !$canEditCategory ? 'readonly' : '' ?>><?= htmlspecialchars($dataInput['target_rencana'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- Dokumen Pendukung -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-paperclip me-1"></i>
                                Dokumen Pendukung
                            </label>
                            <?php if ($canEditCategory): ?>
                                <input
                                    type="file"
                                    class="form-control"
                                    name="dokumen"
                                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                    multiple>
                                <small class="text-muted">Format: PDF, DOC, XLS, JPG, PNG (Max: 5MB per file)</small>
                            <?php endif; ?>

                            <?php if (!empty($dataInput['dokumen']) && is_array($dataInput['dokumen'])): ?>
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="fas fa-check me-1"></i>
                                        <?= count($dataInput['dokumen']) ?> dokumen telah diupload
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>
                                Catatan Tambahan
                            </label>
                            <textarea
                                class="form-control"
                                name="catatan"
                                rows="2"
                                placeholder="Catatan atau informasi tambahan (opsional)"
                                <?= !$canEditCategory ? 'readonly' : '' ?>><?= htmlspecialchars($dataInput['catatan'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <?php if ($canEditCategory): ?>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary-custom flex-fill btn-simpan" data-kategori-id="<?= $kat['id'] ?>">
                                    <i class="fas fa-save me-1"></i>
                                    Simpan
                                </button>
                                <button type="button" class="btn btn-outline-primary-custom btn-reset" data-kategori-id="<?= $kat['id'] ?>">
                                    <i class="fas fa-undo me-1"></i>
                                    Reset
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-lock me-2"></i>
                                Data tidak dapat diedit karena sudah dikirim atau sedang dalam review.
                            </div>
                        <?php endif; ?>

                        <!-- Review Feedback (jika ada) -->
                        <?php if (!empty($review['catatan_review']) && isset($pengiriman['status_pengiriman']) && $pengiriman['status_pengiriman'] === 'perlu_revisi'): ?>
                            <div class="alert alert-danger mt-3">
                                <h6><i class="fas fa-exclamation-triangle me-1"></i>Catatan Review:</h6>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($review['catatan_review'], ENT_QUOTES, 'UTF-8')) ?></p>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Data kategori tidak tersedia.</strong> Silakan hubungi administrator sistem.
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Action Buttons -->
<div class="action-buttons">
    <?php if (isset($canEdit) && $canEdit): ?>
        <button type="button" class="btn btn-outline-primary-custom btn-lg" id="btn-simpan-semua">
            <i class="fas fa-save me-2"></i>
            Simpan Semua Draft
        </button>

        <button
            type="button"
            class="btn btn-primary-custom btn-lg"
            id="btn-kirim-data"
            <?= (isset($progress) && $progress < 100) ? 'disabled' : '' ?>>
            <i class="fas fa-paper-plane me-2"></i>
            Kirim ke Admin Pusat
        </button>
    <?php else: ?>
        <div class="alert alert-info text-center mb-0">
            <i class="fas fa-info-circle me-2"></i>
            Status: <strong><?= isset($pengiriman['status_pengiriman']) ? ucfirst(str_replace('_', ' ', $pengiriman['status_pengiriman'])) : 'Draft' ?></strong>
            <?php if (isset($pengiriman['status_pengiriman'])): ?>
                <?php if ($pengiriman['status_pengiriman'] === 'dikirim'): ?>
                    - Menunggu review dari Admin Pusat
                <?php elseif ($pengiriman['status_pengiriman'] === 'disetujui'): ?>
                    - Data telah disetujui
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts') ?>
<script>
    // Use global variables set in layout
    const BASE_URL = window.BASE_URL;
    const PENGIRIMAN_ID = window.PENGIRIMAN_ID;
    const CAN_EDIT = window.CAN_EDIT;

    // Debug logging
    console.log('Dashboard: CAN_EDIT =', CAN_EDIT, '(type:', typeof CAN_EDIT, ')');
    console.log('Dashboard: PENGIRIMAN_ID =', PENGIRIMAN_ID);
    console.log('Dashboard: Pengiriman Status =', '<?= isset($pengiriman['status_pengiriman']) ? $pengiriman['status_pengiriman'] : 'unknown' ?>');
    console.log('Dashboard: canEdit PHP value =', <?= json_encode(isset($canEdit) ? $canEdit : false) ?>);

    $(document).ready(function() {
        // Debug: Log initial state
        console.log('DEBUG: Page loaded, initial CAN_EDIT =', CAN_EDIT);
        console.log('DEBUG: typeof CAN_EDIT =', typeof CAN_EDIT);
        console.log('DEBUG: CAN_EDIT === true?', CAN_EDIT === true);
        console.log('DEBUG: CAN_EDIT === false?', CAN_EDIT === false);

        // Check if required variables are available
        if (PENGIRIMAN_ID === null) {
            showToast('Data pengiriman tidak tersedia', 'warning');
            return;
        }

        // Initialize dropdown bertingkat untuk jenis sampah organik
        initializeJenisSampahDropdown();

        // Simpan data kategori
        $('.btn-simpan').on('click', function() {
            const kategoriId = $(this).data('kategori-id');
            const form = $(`.category-form[data-kategori-id="${kategoriId}"]`);
            const button = $(this);

            console.log('DEBUG: Save button clicked, CAN_EDIT =', CAN_EDIT, 'type =', typeof CAN_EDIT);

            if (!CAN_EDIT) {
                console.log('DEBUG: CAN_EDIT adalah false, menampilkan pesan error');
                showToast('Data tidak dapat diedit', 'warning');
                return;
            }

            console.log('DEBUG: Validasi CAN_EDIT berhasil, melanjutkan penyimpanan');

            if (!kategoriId) {
                showToast('ID kategori tidak valid', 'danger');
                return;
            }

            // Collect form data with validation
            const formData = new FormData();
            formData.append('pengiriman_id', PENGIRIMAN_ID);
            formData.append('indikator_id', kategoriId);

            // Add CSRF token if available
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (csrfToken) {
                formData.append('<?= csrf_token() ?>', csrfToken);
            }

            // Validate required fields
            const tanggalInput = form.find('[name="tanggal_input"]').val();
            const gedung = form.find('[name="gedung"]').val();
            const jumlah = form.find('[name="jumlah"]').val();
            const satuan = form.find('[name="satuan"]').val();
            const deskripsi = form.find('[name="deskripsi"]').val();

            // Basic validation
            if (!tanggalInput) {
                showToast('Tanggal input harus diisi', 'warning');
                button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                return;
            }

            if (!gedung) {
                showToast('Gedung/lokasi harus dipilih', 'warning');
                button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                return;
            }

            if (!jumlah || parseFloat(jumlah) <= 0) {
                showToast('Jumlah/nilai harus diisi dengan angka yang valid', 'warning');
                button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                return;
            }

            if (!satuan) {
                showToast('Satuan harus dipilih', 'warning');
                button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                return;
            }

            if (!deskripsi || deskripsi.trim().length < 10) {
                showToast('Deskripsi harus diisi minimal 10 karakter', 'warning');
                button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                return;
            }

            const dataInput = {
                tanggal_input: tanggalInput,
                gedung: gedung,
                jenis_sampah: form.find('[name="jenis_sampah"]').val() || '',
                area_sampah: form.find('[name="area_sampah"]').val() || '',
                detail_sampah: form.find('[name="detail_sampah"]').val() || '',
                jumlah: parseFloat(jumlah),
                satuan: satuan,
                deskripsi: deskripsi.trim(),
                target_rencana: form.find('[name="target_rencana"]').val() || '',
                catatan: form.find('[name="catatan"]').val() || '',
                dokumen: [],
                // Keep backward compatibility
                nilai_numerik: parseFloat(jumlah)
            };

            formData.append('data_input', JSON.stringify(dataInput));

            // Show loading
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...');

            $.ajax({
                url: BASE_URL + '/admin-unit/simpan-kategori',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    try {
                        if (response && response.success) {
                            showToast(response.message || 'Data berhasil disimpan', 'success');

                            if (response.progress !== undefined) {
                                updateProgressBar(response.progress);
                            }

                            // Update status badge
                            const card = form.closest('.category-card');
                            const badge = card.find('.badge');
                            badge.html('<i class="fas fa-check-circle me-1"></i>Lengkap');

                            // Enable/disable kirim button
                            if (response.progress >= 100) {
                                $('#btn-kirim-data').prop('disabled', false);
                            }
                        } else {
                            showToast(response.message || 'Gagal menyimpan data', 'danger');
                        }
                    } catch (e) {
                        showToast('Terjadi kesalahan dalam memproses respons', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showToast('Terjadi kesalahan saat menyimpan data', 'danger');
                },
                complete: function() {
                    button.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Simpan');
                }
            });
        });

        // Reset form
        $('.btn-reset').on('click', function() {
            const kategoriId = $(this).data('kategori-id');
            const form = $(`.category-form[data-kategori-id="${kategoriId}"]`);

            if (!kategoriId) {
                showToast('ID kategori tidak valid', 'danger');
                return;
            }

            if (confirm('Yakin ingin mereset data kategori ini?')) {
                form[0].reset();

                // Reset status badge
                const card = form.closest('.category-card');
                const badge = card.find('.badge');
                badge.html('<i class="fas fa-circle me-1"></i>Belum Diisi');

                showToast('Data kategori direset', 'info');
            }
        });

        // Simpan semua draft
        $('#btn-simpan-semua').on('click', function() {
            if (!CAN_EDIT) {
                showToast('Data tidak dapat diedit', 'warning');
                return;
            }

            const buttons = $('.btn-simpan');

            if (buttons.length === 0) {
                showToast('Tidak ada kategori untuk disimpan', 'warning');
                return;
            }

            showToast('Menyimpan semua kategori...', 'info');

            buttons.each(function(index) {
                // Add delay to prevent server overload
                setTimeout(() => {
                    $(this).trigger('click');
                }, index * 500);
            });
        });

        // Kirim data ke Admin Pusat
        $('#btn-kirim-data').on('click', function() {
            const button = $(this);

            if (!CAN_EDIT) {
                showToast('Data tidak dapat dikirim', 'warning');
                return;
            }

            if (!confirm('Yakin ingin mengirim data ke Admin Pusat? Data tidak dapat diedit setelah dikirim.')) {
                return;
            }

            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...');

            $.ajax({
                url: BASE_URL + '/admin-unit/kirim-data',
                method: 'POST',
                data: {
                    pengiriman_id: PENGIRIMAN_ID
                },
                success: function(response) {
                    try {
                        if (response && response.success) {
                            showToast(response.message || 'Data berhasil dikirim', 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showToast(response.message || 'Gagal mengirim data', 'danger');
                            button.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Kirim ke Admin Pusat');
                        }
                    } catch (e) {
                        showToast('Terjadi kesalahan dalam memproses respons', 'danger');
                        button.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Kirim ke Admin Pusat');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    showToast('Terjadi kesalahan saat mengirim data', 'danger');
                    button.prop('disabled', false).html('<i class="fas fa-paper-plane me-2"></i>Kirim ke Admin Pusat');
                }
            });
        });

        // Auto-save on input change (debounced)
        let autoSaveTimeout;
        $('.category-form input, .category-form textarea').on('input', function() {
            if (!CAN_EDIT) return;

            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                const form = $(this).closest('.category-form');
                const kategoriId = form.data('kategori-id');

                if (kategoriId) {
                    // Optional: Auto-save after 5 seconds of no input
                    // const saveButton = $(`.btn-simpan[data-kategori-id="${kategoriId}"]`);
                    // saveButton.trigger('click');
                }
            }, 5000);
        });
    });

    // Helper function to update progress bar
    function updateProgressBar(progress) {
        const progressFill = document.querySelector('.progress-fill');
        const progressText = document.querySelector('.progress-text');

        if (progressFill) {
            progressFill.style.width = progress + '%';
        }

        if (progressText) {
            progressText.textContent = Math.round(progress) + '%';
        }

        // Update alert message
        const alertContainer = document.querySelector('.progress-container .alert');
        if (alertContainer) {
            if (progress >= 100) {
                alertContainer.className = 'alert alert-success mb-0';
                alertContainer.innerHTML = '<i class="fas fa-check-circle me-2"></i><strong>Semua kategori sudah lengkap!</strong> Data siap untuk dikirim ke Admin Pusat.';
            } else {
                const completedCount = Math.floor((progress / 100) * 6);
                alertContainer.className = 'alert alert-info mb-0';
                alertContainer.innerHTML = `<i class="fas fa-info-circle me-2"></i><strong>Lengkapi seluruh kategori sebelum mengirim data ke Admin Pusat.</strong> Progress saat ini: ${completedCount} dari 6 kategori.`;
            }
        }
    }

    // Helper function to show toast notifications
    function showToast(message, type = 'info') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification alert alert-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideInRight 0.3s ease;
        `;

        // Set colors based on type
        let bgColor, textColor, icon;
        switch (type) {
            case 'success':
                bgColor = '#d4edda';
                textColor = '#155724';
                icon = 'check-circle';
                break;
            case 'danger':
            case 'error':
                bgColor = '#f8d7da';
                textColor = '#721c24';
                icon = 'times-circle';
                break;
            case 'warning':
                bgColor = '#fff3cd';
                textColor = '#856404';
                icon = 'exclamation-triangle';
                break;
            default:
                bgColor = '#d1ecf1';
                textColor = '#0c5460';
                icon = 'info-circle';
        }

        toast.style.backgroundColor = bgColor;
        toast.style.color = textColor;
        toast.style.border = `1px solid ${textColor}20`;

        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-${icon}"></i>
                <span style="flex: 1;">${message}</span>
                <button onclick="this.parentElement.parentElement.remove()" 
                        style="background: none; border: none; color: ${textColor}; font-size: 18px; cursor: pointer; padding: 0; margin-left: 10px;">
                    &times;
                </button>
            </div>
        `;

        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
        `;
        document.head.appendChild(style);

        document.body.appendChild(toast);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }

    // Function untuk initialize dropdown jenis sampah
    function initializeJenisSampahDropdown() {
        // Handle perubahan jenis sampah
        $('select[name="jenis_sampah"]').on('change', function() {
            const kategoriId = $(this).closest('.category-form').data('kategori-id');
            const selectedValue = $(this).val();

            console.log('Jenis sampah changed:', selectedValue, 'for kategori:', kategoriId);

            // Reset dropdown area dan detail
            resetAreaSampahDropdown(kategoriId);
            resetDetailSampahDropdown(kategoriId);

            if (selectedValue === 'Organik') {
                showAreaSampahDropdown(kategoriId);
                loadAreaSampah(kategoriId);
            } else {
                hideAreaSampahDropdown(kategoriId);
                hideDetailSampahDropdown(kategoriId);
            }
        });

        // Handle perubahan area sampah
        $('select[name="area_sampah"]').on('change', function() {
            const kategoriId = $(this).closest('.category-form').data('kategori-id');
            const selectedAreaId = $(this).val();

            console.log('Area sampah changed:', selectedAreaId, 'for kategori:', kategoriId);

            // Reset dropdown detail
            resetDetailSampahDropdown(kategoriId);

            if (selectedAreaId) {
                showDetailSampahDropdown(kategoriId);
                loadDetailSampah(kategoriId, selectedAreaId);
            } else {
                hideDetailSampahDropdown(kategoriId);
            }
        });

        // Load existing data jika ada
        loadExistingJenisSampahData();
    }

    // Function untuk load area sampah
    function loadAreaSampah(kategoriId) {
        const areaSelect = $(`#area_sampah_${kategoriId}`);

        // Show loading
        areaSelect.html('<option value="">Loading...</option>');

        // Get organik category ID (hardcoded karena kita tahu struktur)
        const organikId = 1; // ID kategori organik dari database

        $.ajax({
            url: `${BASE_URL}/api/jenis-sampah/area/${organikId}`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    let options = '<option value="">Pilih Area Sampah</option>';
                    response.data.forEach(function(area) {
                        options += `<option value="${area.id}">${area.nama}</option>`;
                    });
                    areaSelect.html(options);
                } else {
                    areaSelect.html('<option value="">Gagal memuat data</option>');
                    showToast('Gagal memuat data area sampah', 'warning');
                }
            },
            error: function() {
                areaSelect.html('<option value="">Error memuat data</option>');
                showToast('Error saat memuat area sampah', 'danger');
            }
        });
    }

    // Function untuk load detail sampah
    function loadDetailSampah(kategoriId, areaId) {
        const detailSelect = $(`#detail_sampah_${kategoriId}`);

        // Show loading
        detailSelect.html('<option value="">Loading...</option>');

        $.ajax({
            url: `${BASE_URL}/api/jenis-sampah/detail/${areaId}`,
            method: 'GET',
            success: function(response) {
                if (response.success && response.data) {
                    let options = '<option value="">Pilih Detail Sampah</option>';
                    response.data.forEach(function(detail) {
                        options += `<option value="${detail.id}">${detail.nama}</option>`;
                    });
                    detailSelect.html(options);
                } else {
                    detailSelect.html('<option value="">Gagal memuat data</option>');
                    showToast('Gagal memuat data detail sampah', 'warning');
                }
            },
            error: function() {
                detailSelect.html('<option value="">Error memuat data</option>');
                showToast('Error saat memuat detail sampah', 'danger');
            }
        });
    }

    // Function untuk show/hide dropdown
    function showAreaSampahDropdown(kategoriId) {
        $(`#area_sampah_group_${kategoriId}`).slideDown(300);
    }

    function hideAreaSampahDropdown(kategoriId) {
        $(`#area_sampah_group_${kategoriId}`).slideUp(300);
    }

    function showDetailSampahDropdown(kategoriId) {
        $(`#detail_sampah_group_${kategoriId}`).slideDown(300);
    }

    function hideDetailSampahDropdown(kategoriId) {
        $(`#detail_sampah_group_${kategoriId}`).slideUp(300);
    }

    // Function untuk reset dropdown
    function resetAreaSampahDropdown(kategoriId) {
        $(`#area_sampah_${kategoriId}`).html('<option value="">Pilih Area Sampah</option>');
    }

    function resetDetailSampahDropdown(kategoriId) {
        $(`#detail_sampah_${kategoriId}`).html('<option value="">Pilih Detail Sampah</option>');
    }

    // Function untuk load existing data
    function loadExistingJenisSampahData() {
        // Check setiap form yang ada untuk restore state
        $('.category-form').each(function() {
            const form = $(this);
            const kategoriId = form.data('kategori-id');
            const jenisSampah = form.find('select[name="jenis_sampah"]').val();

            if (jenisSampah === 'Organik') {
                showAreaSampahDropdown(kategoriId);
                loadAreaSampah(kategoriId);

                // Check if area sampah sudah dipilih
                setTimeout(() => {
                    const areaSampah = form.find('select[name="area_sampah"]').val();
                    if (areaSampah) {
                        showDetailSampahDropdown(kategoriId);
                        loadDetailSampah(kategoriId, areaSampah);
                    }
                }, 1000);
            }
        });
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>