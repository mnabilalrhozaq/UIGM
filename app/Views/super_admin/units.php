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

        .badge-warning {
            background: #fff3cd;
            color: #856404;
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
                <h1><i class="fas fa-building"></i> Manajemen Unit</h1>
                <p>Kelola unit/fakultas dalam sistem UIGM</p>
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
            <a href="/super-admin/units" class="nav-link active">
                <i class="fas fa-building"></i> Units
            </a>
            <a href="/super-admin/tahun-penilaian" class="nav-link">
                <i class="fas fa-calendar"></i> Tahun Penilaian
            </a>
        </div>

        <!-- Units Management -->
        <div class="card">
            <div class="card-header">
                <span><i class="fas fa-building"></i> Daftar Unit</span>
                <button class="btn btn-primary" onclick="openCreateModal()">
                    <i class="fas fa-plus"></i> Tambah Unit
                </button>
            </div>
            <div class="card-body">
                <div id="alert-container"></div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Unit</th>
                            <th>Kode Unit</th>
                            <th>Tipe Unit</th>
                            <th>Admin Unit</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($units as $unit): ?>
                            <tr>
                                <td><?= $unit['nama_unit'] ?></td>
                                <td><span class="badge badge-primary"><?= $unit['kode_unit'] ?></span></td>
                                <td><?= ucfirst($unit['tipe_unit']) ?></td>
                                <td><?= $unit['admin_name'] ?: '-' ?></td>
                                <td>
                                    <span class="badge <?= $unit['status_aktif'] ? 'badge-success' : 'badge-danger' ?>">
                                        <?= $unit['status_aktif'] ? 'Aktif' : 'Nonaktif' ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editUnit(<?= htmlspecialchars(json_encode($unit)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Unit Modal -->
    <div id="unitModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Unit</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="unitForm">
                    <input type="hidden" id="unitId" name="unit_id">

                    <div class="form-group">
                        <label for="nama_unit">Nama Unit</label>
                        <input type="text" id="nama_unit" name="nama_unit" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="kode_unit">Kode Unit</label>
                        <input type="text" id="kode_unit" name="kode_unit" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="tipe_unit">Tipe Unit</label>
                        <select id="tipe_unit" name="tipe_unit" class="form-control" required>
                            <option value="">Pilih Tipe</option>
                            <option value="fakultas">Fakultas</option>
                            <option value="jurusan">Jurusan</option>
                            <option value="program_studi">Program Studi</option>
                            <option value="unit_kerja">Unit Kerja</option>
                        </select>
                    </div>

                    <div class="form-group" id="statusGroup" style="display: none;">
                        <label>
                            <input type="checkbox" id="status_aktif" name="status_aktif" value="1" checked>
                            Status Aktif
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveUnit()">Simpan</button>
                <button type="button" class="btn" onclick="closeModal()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;

        function openCreateModal() {
            isEditMode = false;
            document.getElementById('modalTitle').textContent = 'Tambah Unit';
            document.getElementById('unitForm').reset();
            document.getElementById('unitId').value = '';
            document.getElementById('statusGroup').style.display = 'none';
            document.getElementById('unitModal').style.display = 'block';
        }

        function editUnit(unitData) {
            isEditMode = true;
            document.getElementById('modalTitle').textContent = 'Edit Unit';
            document.getElementById('unitId').value = unitData.id;
            document.getElementById('nama_unit').value = unitData.nama_unit;
            document.getElementById('kode_unit').value = unitData.kode_unit;
            document.getElementById('tipe_unit').value = unitData.tipe_unit;
            document.getElementById('status_aktif').checked = unitData.status_aktif;
            document.getElementById('statusGroup').style.display = 'block';

            document.getElementById('unitModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('unitModal').style.display = 'none';
        }

        function saveUnit() {
            const form = document.getElementById('unitForm');
            const formData = new FormData(form);

            const url = isEditMode ? '/super-admin/units/update' : '/super-admin/units/create';

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
            const modal = document.getElementById('unitModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>