<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Dashboard Admin Unit UIGM</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #5c8cbf;
            --primary-dark: #4a7ba7;
            --primary-light: #e8f0f8;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --card-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #333;
            line-height: 1.6;
        }

        /* Header Styles */
        .header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--card-shadow);
        }

        .header h1 {
            font-weight: 600;
            margin: 0;
            font-size: 1.5rem;
        }

        .header .subtitle {
            opacity: 0.9;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        /* Status Badge */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-draft {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        /* Progress Bar */
        .progress-container {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .progress-bar-custom {
            height: 12px;
            border-radius: 6px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: 6px;
            transition: width 0.3s ease;
        }

        /* Category Cards */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .category-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .category-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .category-header {
            padding: 1.25rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .category-info h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .category-info .category-code {
            font-size: 0.8rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        .category-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            font-size: 0.9rem;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(92, 140, 191, 0.25);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(92, 140, 191, 0.4);
        }

        .btn-outline-primary-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-primary-custom:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Action Buttons */
        .action-buttons {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Category specific colors */
        .category-si {
            background: linear-gradient(135deg, #2E8B57 0%, #228B22 100%);
        }

        .category-ec {
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        }

        .category-ws {
            background: linear-gradient(135deg, #8B4513 0%, #A0522D 100%);
        }

        .category-wr {
            background: linear-gradient(135deg, #1E90FF 0%, #0066CC 100%);
        }

        .category-tr {
            background: linear-gradient(135deg, #DC143C 0%, #B22222 100%);
        }

        .category-ed {
            background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .category-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 1.25rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1><i class="fas fa-leaf me-2"></i>Dashboard Input Data UIGM</h1>
                    <div class="subtitle">Fakultas Teknik Elektro - Tahun 2025</div>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <div class="d-flex align-items-center justify-content-md-end gap-3">
                        <!-- Status Badge -->
                        <div class="status-badge status-draft">
                            <i class="fas fa-circle"></i>
                            Draft
                        </div>

                        <!-- User Info -->
                        <div class="text-white">
                            <i class="fas fa-user-circle me-1"></i>
                            Admin Fakultas Teknik Elektro
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-4">
        <!-- Progress Section -->
        <div class="progress-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Progress Pengisian Data</h4>
                <span class="badge bg-primary fs-6">33%</span>
            </div>

            <div class="progress-bar-custom">
                <div class="progress-fill" style="width: 33%"></div>
            </div>

            <div class="mt-3">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Lengkapi seluruh kategori sebelum mengirim data ke Admin Pusat.</strong>
                    Progress saat ini: 2 dari 6 kategori.
                </div>
            </div>
        </div>

        <!-- Category Cards Grid -->
        <div class="category-grid">
            <!-- Setting & Infrastructure -->
            <div class="category-card">
                <div class="category-header category-si">
                    <div class="category-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="category-info">
                        <h3>Setting & Infrastructure</h3>
                        <div class="category-code">SI - Bobot: 15%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-check-circle me-1"></i>
                            Lengkap
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3" placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori Setting & Infrastructure">Kampus POLBAN memiliki area seluas 45 hektar dengan 30% ruang terbuka hijau. Terdapat 25 gedung dengan sertifikasi green building dan sistem pengelolaan energi terintegrasi.</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" value="450000" step="0.01">
                            <span class="input-group-text">m²</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2">Meningkatkan ruang terbuka hijau menjadi 40% pada tahun 2026 dan menambah 5 gedung bersertifikat green building.</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Energy & Climate Change -->
            <div class="category-card">
                <div class="category-header category-ec">
                    <div class="category-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="category-info">
                        <h3>Energy & Climate Change</h3>
                        <div class="category-code">EC - Bobot: 21%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-check-circle me-1"></i>
                            Lengkap
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3">Implementasi sistem solar panel dengan kapasitas 500 kWp, program penghematan energi, dan penggunaan LED untuk seluruh penerangan kampus.</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" value="2500000" step="0.01">
                            <span class="input-group-text">kWh</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2">Mencapai 50% energi terbarukan pada 2027 dan mengurangi emisi karbon sebesar 30%.</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Waste -->
            <div class="category-card">
                <div class="category-header category-ws">
                    <div class="category-icon">
                        <i class="fas fa-recycle"></i>
                    </div>
                    <div class="category-info">
                        <h3>Waste</h3>
                        <div class="category-code">WS - Bobot: 18%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-circle me-1"></i>
                            Belum Diisi
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3" placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori Waste"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" step="0.01" placeholder="Masukkan nilai numerik">
                            <span class="input-group-text">kg</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2" placeholder="Jelaskan target atau rencana pengembangan"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Water -->
            <div class="category-card">
                <div class="category-header category-wr">
                    <div class="category-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="category-info">
                        <h3>Water</h3>
                        <div class="category-code">WR - Bobot: 10%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-circle me-1"></i>
                            Belum Diisi
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3" placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori Water"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" step="0.01" placeholder="Masukkan nilai numerik">
                            <span class="input-group-text">liter</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2" placeholder="Jelaskan target atau rencana pengembangan"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Transportation -->
            <div class="category-card">
                <div class="category-header category-tr">
                    <div class="category-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="category-info">
                        <h3>Transportation</h3>
                        <div class="category-code">TR - Bobot: 18%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-circle me-1"></i>
                            Belum Diisi
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3" placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori Transportation"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" step="0.01" placeholder="Masukkan nilai numerik">
                            <span class="input-group-text">unit</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2" placeholder="Jelaskan target atau rencana pengembangan"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Education & Research -->
            <div class="category-card">
                <div class="category-header category-ed">
                    <div class="category-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="category-info">
                        <h3>Education & Research</h3>
                        <div class="category-code">ED - Bobot: 18%</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-circle me-1"></i>
                            Belum Diisi
                        </span>
                    </div>
                </div>
                <div class="category-body">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Deskripsi Program/Kegiatan
                        </label>
                        <textarea class="form-control" rows="3" placeholder="Jelaskan program atau kegiatan yang dilakukan untuk kategori Education & Research"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calculator me-1"></i>
                            Nilai/Data Numerik
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control" step="0.01" placeholder="Masukkan nilai numerik">
                            <span class="input-group-text">program</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-bullseye me-1"></i>
                            Target/Rencana Kedepan
                        </label>
                        <textarea class="form-control" rows="2" placeholder="Jelaskan target atau rencana pengembangan"></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary-custom flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Simpan
                        </button>
                        <button type="button" class="btn btn-outline-primary-custom">
                            <i class="fas fa-undo me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <button type="button" class="btn btn-outline-primary-custom btn-lg">
                <i class="fas fa-save me-2"></i>
                Simpan Semua Draft
            </button>

            <button type="button" class="btn btn-primary-custom btn-lg" disabled>
                <i class="fas fa-paper-plane me-2"></i>
                Kirim ke Admin Pusat
            </button>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // Demo interactions
        $(document).ready(function() {
            // Show toast notification
            function showToast(message, type = 'info') {
                const toastHtml = `
                    <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                `;

                let toastContainer = document.getElementById('toast-container');
                if (!toastContainer) {
                    toastContainer = document.createElement('div');
                    toastContainer.id = 'toast-container';
                    toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                    toastContainer.style.zIndex = '9999';
                    document.body.appendChild(toastContainer);
                }

                toastContainer.insertAdjacentHTML('beforeend', toastHtml);
                const toastElement = toastContainer.lastElementChild;
                const toast = new bootstrap.Toast(toastElement);
                toast.show();

                toastElement.addEventListener('hidden.bs.toast', () => {
                    toastElement.remove();
                });
            }

            // Demo button interactions
            $('.btn-primary-custom').on('click', function() {
                if ($(this).text().includes('Simpan')) {
                    showToast('Data berhasil disimpan sebagai draft', 'success');
                } else if ($(this).text().includes('Kirim')) {
                    showToast('Lengkapi semua kategori terlebih dahulu', 'warning');
                }
            });

            $('.btn-outline-primary-custom').on('click', function() {
                if ($(this).text().includes('Reset')) {
                    showToast('Form direset', 'info');
                } else if ($(this).text().includes('Simpan Semua')) {
                    showToast('Menyimpan semua kategori...', 'info');
                }
            });
        });
    </script>
</body>

</html>