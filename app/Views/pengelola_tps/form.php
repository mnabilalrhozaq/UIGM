<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Form Input Sampah TPS' ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?= $this->include('partials/sidebar_pengelola_tps') ?>
    
    <div class="main-content">
        <div class="page-header">
            <div class="header-content">
                <h1><i class="fas fa-plus-circle"></i> Form Input Data Sampah TPS</h1>
                <p>Input data sampah yang diterima TPS dari berbagai gedung</p>
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

        <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-2">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
                <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Form Input Sampah TPS -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-recycle text-success"></i>
                    Form Input Data Sampah TPS
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('/pengelola-tps/waste/save-tps') ?>" id="tpsWasteForm">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <!-- Tanggal -->
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">
                                    <i class="fas fa-calendar"></i> Tanggal <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                       value="<?= old('tanggal', date('Y-m-d')) ?>" required>
                                <div class="form-text">Tanggal penerimaan sampah di TPS</div>
                            </div>

                            <!-- Pengirim Gedung -->
                            <div class="mb-3">
                                <label for="pengirim_gedung" class="form-label">
                                    <i class="fas fa-building"></i> Gedung Pengirim <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="pengirim_gedung" name="pengirim_gedung" 
                                       value="<?= old('pengirim_gedung') ?>" placeholder="Contoh: Gedung A, Kantin, Lab Komputer" required>
                                <div class="form-text">Nama gedung atau lokasi asal sampah</div>
                            </div>

                            <!-- Jenis Sampah -->
                            <div class="mb-3">
                                <label for="jenis_sampah" class="form-label">
                                    <i class="fas fa-trash-alt"></i> Jenis Sampah <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="jenis_sampah" name="jenis_sampah" required onchange="updateKategoriSampah()">
                                    <option value="">-- Pilih Jenis Sampah --</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= htmlspecialchars($category['nama_jenis']) ?>" 
                                                    data-kategori="<?= htmlspecialchars($category['jenis_sampah']) ?>"
                                                    data-harga="<?= $category['harga_per_satuan'] ?>"
                                                    data-dapat-dijual="<?= $category['dapat_dijual'] ?>"
                                                    <?= old('jenis_sampah') === $category['nama_jenis'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['nama_jenis']) ?> (<?= htmlspecialchars($category['jenis_sampah']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Fallback jika tidak ada data -->
                                        <option value="Plastik">Plastik</option>
                                        <option value="Kertas">Kertas</option>
                                        <option value="Logam">Logam</option>
                                        <option value="Organik">Organik</option>
                                        <option value="Residu">Residu</option>
                                    <?php endif; ?>
                                </select>
                                <div class="form-text">Pilih jenis sampah yang diterima</div>
                            </div>

                            <!-- Jumlah dan Satuan -->
                            <div class="row">
                                <div class="col-8">
                                    <div class="mb-3">
                                        <label for="jumlah" class="form-label">
                                            <i class="fas fa-weight"></i> Jumlah <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                               value="<?= old('jumlah') ?>" step="0.01" min="0.01" placeholder="0.00" required
                                               onchange="calculateNilaiRupiah()">
                                        <div class="form-text">Berat sampah yang diterima</div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="mb-3">
                                        <label for="satuan" class="form-label">Satuan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="satuan" name="satuan" required onchange="calculateNilaiRupiah()">
                                            <option value="kg" <?= old('satuan') === 'kg' ? 'selected' : '' ?>>kg</option>
                                            <option value="ton" <?= old('satuan') === 'ton' ? 'selected' : '' ?>>ton</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <!-- Kategori Sampah -->
                            <div class="mb-3">
                                <label for="kategori_sampah" class="form-label">
                                    <i class="fas fa-tags"></i> Kategori Sampah <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="kategori_sampah" name="kategori_sampah" required onchange="toggleNilaiRupiah()">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="bisa_dijual" <?= old('kategori_sampah') === 'bisa_dijual' ? 'selected' : '' ?>>Bisa Dijual</option>
                                    <option value="tidak_bisa_dijual" <?= old('kategori_sampah') === 'tidak_bisa_dijual' ? 'selected' : '' ?>>Tidak Bisa Dijual</option>
                                </select>
                                <div class="form-text">Apakah sampah ini memiliki nilai ekonomis?</div>
                            </div>

                            <!-- Nilai Rupiah Preview (Read-only) -->
                            <div class="mb-3" id="nilai_rupiah_preview" style="display: none;">
                                <label class="form-label">
                                    <i class="fas fa-money-bill-wave"></i> Nilai Rupiah (Otomatis)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" id="preview_nilai" readonly 
                                           placeholder="0" style="background-color: #f8f9fa;">
                                </div>
                                <div class="form-text text-success">
                                    <i class="fas fa-info-circle"></i> 
                                    Nilai dihitung otomatis berdasarkan harga pasar
                                </div>
                            </div>

                            <!-- Harga Per Kg Info -->
                            <div class="mb-3" id="harga_info" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Harga Pasar Saat Ini:</strong>
                                    <div id="harga_detail"></div>
                                </div>
                            </div>

                            <!-- Catatan (Opsional) -->
                            <div class="mb-3">
                                <label for="catatan" class="form-label">
                                    <i class="fas fa-sticky-note"></i> Catatan (Opsional)
                                </label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3" 
                                          placeholder="Catatan tambahan tentang sampah ini..."><?= old('catatan') ?></textarea>
                                <div class="form-text">Informasi tambahan jika diperlukan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="<?= base_url('/pengelola-tps/dashboard') ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" name="action" value="draft" class="btn btn-outline-primary me-2">
                                        <i class="fas fa-save"></i> Simpan sebagai Draft
                                    </button>
                                    <button type="submit" name="action" value="kirim" class="btn btn-success">
                                        <i class="fas fa-paper-plane"></i> Kirim ke Admin
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Tips Input Data</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Pastikan tanggal sesuai dengan waktu penerimaan sampah</li>
                            <li>Tulis nama gedung dengan jelas dan konsisten</li>
                            <li>Timbang sampah dengan akurat sebelum input</li>
                            <li>Pilih kategori "Bisa Dijual" hanya untuk sampah yang layak jual</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Perhatian</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Data yang sudah dikirim tidak dapat diedit</li>
                            <li>Pastikan data sudah benar sebelum mengirim</li>
                            <li>Draft dapat diedit kapan saja</li>
                            <li>Admin akan review data yang dikirim</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Harga per kg untuk setiap jenis sampah
    const hargaPerKg = {
        'Plastik': 2000,
        'Kertas': 1500,
        'Logam': 5000,
        'Organik': 0,
        'Residu': 0
    };

    // Jenis sampah yang bisa dijual
    const bisaDijual = ['Plastik', 'Kertas', 'Logam'];

    function updateKategoriSampah() {
        const jenisSampah = document.getElementById('jenis_sampah').value;
        const kategoriSelect = document.getElementById('kategori_sampah');
        
        if (jenisSampah) {
            if (bisaDijual.includes(jenisSampah)) {
                // Bisa dijual - enable both options
                kategoriSelect.innerHTML = `
                    <option value="">-- Pilih Kategori --</option>
                    <option value="bisa_dijual">Bisa Dijual</option>
                    <option value="tidak_bisa_dijual">Tidak Bisa Dijual</option>
                `;
                
                // Show harga info
                showHargaInfo(jenisSampah);
            } else {
                // Tidak bisa dijual - only one option
                kategoriSelect.innerHTML = `
                    <option value="tidak_bisa_dijual" selected>Tidak Bisa Dijual</option>
                `;
                
                hideHargaInfo();
            }
        } else {
            kategoriSelect.innerHTML = `
                <option value="">-- Pilih Kategori --</option>
                <option value="bisa_dijual">Bisa Dijual</option>
                <option value="tidak_bisa_dijual">Tidak Bisa Dijual</option>
            `;
            hideHargaInfo();
        }
        
        toggleNilaiRupiah();
    }

    function showHargaInfo(jenisSampah) {
        const hargaInfo = document.getElementById('harga_info');
        const hargaDetail = document.getElementById('harga_detail');
        
        hargaDetail.innerHTML = `${jenisSampah}: Rp ${hargaPerKg[jenisSampah].toLocaleString()}/kg`;
        hargaInfo.style.display = 'block';
    }

    function hideHargaInfo() {
        document.getElementById('harga_info').style.display = 'none';
    }

    function toggleNilaiRupiah() {
        const jenisSampah = document.getElementById('jenis_sampah').value;
        const kategori = document.getElementById('kategori_sampah').value;
        const nilaiPreview = document.getElementById('nilai_rupiah_preview');
        
        if (kategori === 'bisa_dijual' && bisaDijual.includes(jenisSampah)) {
            nilaiPreview.style.display = 'block';
            calculateNilaiRupiah();
        } else {
            nilaiPreview.style.display = 'none';
            document.getElementById('preview_nilai').value = '';
        }
    }

    function calculateNilaiRupiah() {
        const jenisSampah = document.getElementById('jenis_sampah').value;
        const kategori = document.getElementById('kategori_sampah').value;
        const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
        const satuan = document.getElementById('satuan').value;
        
        if (kategori === 'bisa_dijual' && bisaDijual.includes(jenisSampah) && jumlah > 0) {
            let jumlahKg = jumlah;
            
            // Convert to kg if needed
            if (satuan === 'ton') {
                jumlahKg = jumlah * 1000;
            }
            
            const harga = hargaPerKg[jenisSampah] || 0;
            const nilaiTotal = jumlahKg * harga;
            
            document.getElementById('preview_nilai').value = nilaiTotal.toLocaleString();
        } else {
            document.getElementById('preview_nilai').value = '';
        }
    }

    // Form validation
    document.getElementById('tpsWasteForm').addEventListener('submit', function(e) {
        const jumlah = parseFloat(document.getElementById('jumlah').value);
        
        if (jumlah <= 0) {
            e.preventDefault();
            alert('Jumlah sampah harus lebih dari 0');
            return false;
        }
        
        // Confirm before submit
        const action = e.submitter.value;
        const message = action === 'kirim' ? 
            'Apakah Anda yakin ingin mengirim data ini ke Admin? Data yang sudah dikirim tidak dapat diedit.' :
            'Apakah Anda yakin ingin menyimpan data ini sebagai draft?';
            
        if (!confirm(message)) {
            e.preventDefault();
            return false;
        }
    });

    // Initialize form
    document.addEventListener('DOMContentLoaded', function() {
        updateKategoriSampah();
    });
    </script>
</body>
</html>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f8f9fa;
}

.main-content {
    margin-left: 250px;
    padding: 20px;
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    margin-bottom: 30px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.page-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
}

.page-header p {
    margin: 10px 0 0 0;
    opacity: 0.9;
}

.card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-header {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
    border-radius: 10px 10px 0 0 !important;
    padding: 15px 20px;
}

.card-body {
    padding: 25px;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.form-control, .form-select {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 12px 15px;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn {
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.alert {
    border: none;
    border-radius: 8px;
    padding: 15px 20px;
}

.text-danger {
    color: #dc3545 !important;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 5px;
}

.input-group-text {
    background-color: #e9ecef;
    border: 2px solid #e9ecef;
    border-right: none;
}

@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
        padding: 15px;
    }
    
    .page-header {
        padding: 20px;
    }
    
    .page-header h1 {
        font-size: 1.5rem;
    }
}
</style>