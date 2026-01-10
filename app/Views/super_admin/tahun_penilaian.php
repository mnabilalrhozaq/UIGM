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
            background-color: #f5f7fa;
            color: #333;
        }

        .header {
            background: linear-gradient(135deg, #5c8cbf 0%, #4a7ba7 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }

        .header-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .nav-link {
            background: white;
            color: #5c8cbf;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #5c8cbf;
            color: white;
            transform: translateY(-2px);
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header {
            background: #5c8cbf;
            color: white;
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 20px;
        }

        .btn {
            padding: 10px 20px;
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

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
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

        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-primary {
            background: #d1ecf1;
            color: #0c5460;
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
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: #5c8cbf;
            color: white;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            opacity: 0.7;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #5c8cbf;
            box-shadow: 0 0 0 2px rgba(92, 140, 191, 0.2);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

            .modal-content {
                width: 95%;
                margin: 10% auto;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1><i class="fas fa-calendar-alt"></i> Manajemen Tahun Penilaian</h1>
                <p>Kelola periode penilaian UI GreenMetric</p>
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
            <a href="/super-admin/dashboard" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="/super-admin/users" class="nav-link">
                <i class="fas fa-users"></i> Users
            </a>
            <a href="/super-admin/units" class="nav-link">
                <i class="fas fa-building"></i> Units
            </a>
            <a href="/super-admin/tahun-penilaian" class="nav-link active">
                <i class="fas fa-calendar"></i> Tahun Penilaian
            </a>
        </div>

        <!-- Tahun Penilaian Management -->
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-calendar-alt"></i> Daftar Tahun Penilaian</span>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Tambah Tahun Penilaian
                </button>
            </div>
            <div class="card-body">
                <div id="alert-container"></div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Tahun</th>
                            <th>Nama Periode</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tahunList as $tahun): ?>
                            <tr>
                                <td><strong><?= $tahun['tahun'] ?></strong></td>
                                <td><?= $tahun['nama_periode'] ?></td>
                                <td><?= date('d/m/Y', strtotime($tahun['tanggal_mulai'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($tahun['tanggal_selesai'])) ?></td>
                                <td>
                                    <span class="badge <?= $tahun['status_aktif'] ? 'badge-success' : 'badge-danger' ?>">
                                        <?= $tahun['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!$tahun['status_aktif']): ?>
                                        <button class="btn btn-success btn-sm" onclick="activateTahun(<?= $tahun['id'] ?>)">
                                            <i class="fas fa-play"></i> Aktifkan
                                        </button>
                                    <?php endif; ?>
                                    <button class="btn btn-warning btn-sm" onclick="editTahun(<?= htmlspecialchars(json_encode($tahun)) ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Tahun Modal -->
    <div id="tahunModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Tahun Penilaian</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="tahunForm">
                    <input type="hidden" id="tahunId" name="tahun_id">

                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" id="tahun" name="tahun" class="form-control" min="2020" max="2030" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_periode">Nama Periode</label>
                        <input type="text" id="nama_periode" name="nama_periode" class="form-control" 
                               placeholder="Contoh: Periode Penilaian 2024" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai</label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                    </div>

                    <div class="form-group" id="statusGroup" style="display: none;">
                        <label>
                            <input type="checkbox" id="status_aktif" name="status_aktif" value="1">
                            Aktifkan tahun ini (akan menonaktifkan tahun lain)
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveTahun()">Simpan</button>
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('modalTitle').textContent = 'Tambah Tahun Penilaian';
            document.getElementById('tahunForm').reset();
            document.getElementById('tahunId').value = '';
            document.getElementById('statusGroup').style.display = 'none';
            
            // Set default values
            const currentYear = new Date().getFullYear();
            document.getElementById('tahun').value = currentYear;
            document.getElementById('nama_periode').value = `Periode Penilaian ${currentYear}`;
            
            document.getElementById('tahunModal').style.display = 'block';
        }

        function editTahun(tahunData) {
            isEditMode = true;
            document.getElementById('modalTitle').textContent = 'Edit Tahun Penilaian';
            document.getElementById('tahunId').value = tahunData.id;
            document.getElementById('tahun').value = tahunData.tahun;
            document.getElementById('nama_periode').value = tahunData.nama_periode;
            document.getElementById('tanggal_mulai').value = tahunData.tanggal_mulai;
            document.getElementById('tanggal_selesai').value = tahunData.tanggal_selesai;
            document.getElementById('status_aktif').checked = tahunData.status_aktif;
            document.getElementById('statusGroup').style.display = 'block';

            document.getElementById('tahunModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('tahunModal').style.display = 'none';
        }

        function saveTahun() {
            const form = document.getElementById('tahunForm');
            const formData = new FormData(form);

            const url = '/super-admin/tahun-penilaian/create';

            fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        closeModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    showAlert('danger', 'Terjadi kesalahan: ' + error.message);
                });
        }

        function activateTahun(tahunId) {
            if (confirm('Apakah Anda yakin ingin mengaktifkan tahun ini? Tahun aktif sebelumnya akan dinonaktifkan.')) {
                const formData = new FormData();
                formData.append('tahun_id', tahunId);
                formData.append('status_aktif', '1');

                fetch('/admin-pusat/update-tahun-aktif', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            setTimeout(() => location.reload(), 1000);
                        } else {
                            showAlert('danger', data.message);
                        }
                    })
                    .catch(error => {
                        showAlert('danger', 'Terjadi kesalahan: ' + error.message);
                    });
            }
        }

        function showAlert(type, message) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

            alertContainer.innerHTML = `
                <div class="alert ${alertClass}">
                    ${message}
                </div>
            `;

            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('tahunModal');
            if (event.target === modal) {
                closeModal();
            }
        }

        // Auto-fill nama periode when tahun changes
        document.getElementById('tahun').addEventListener('change', function() {
            const tahun = this.value;
            if (tahun && !isEditMode) {
                document.getElementById('nama_periode').value = `Periode Penilaian ${tahun}`;
            }
        });
    </script>
</body>

</html>