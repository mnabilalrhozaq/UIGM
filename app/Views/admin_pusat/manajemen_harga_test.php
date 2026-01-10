<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h4><i class="fas fa-check-circle"></i> SUCCESS!</h4>
            <p><?= $message ?></p>
            <hr>
            <p><strong>User:</strong> <?= $user['nama_lengkap'] ?> (<?= $user['role'] ?>)</p>
            <a href="<?= base_url('/admin-pusat/dashboard') ?>" class="btn btn-primary">Kembali ke Dashboard</a>
            <a href="<?= base_url('/admin-pusat/manajemen-harga') ?>" class="btn btn-success">Coba Manajemen Harga Asli</a>
        </div>
    </div>
</body>
</html>