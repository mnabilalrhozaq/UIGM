<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login - Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Test Login - Debug Mode</h3>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <!-- Test Credentials -->
                        <div class="alert alert-info">
                            <h5>Test Credentials:</h5>
                            <ul class="mb-0">
                                <li><strong>Admin:</strong> admin / admin123</li>
                                <li><strong>Super Admin:</strong> superadmin / super123</li>
                                <li><strong>TPS:</strong> pengelolatps / password123</li>
                                <li><strong>User:</strong> userjti / user123</li>
                                <li><strong>User 2:</strong> Nabila / password123</li>
                            </ul>
                        </div>

                        <!-- Login Form -->
                        <form action="<?= base_url('/auth/process-login') ?>" method="POST">
                            <div class="mb-3">
                                <label for="login" class="form-label">Username/Email</label>
                                <input type="text" class="form-control" id="login" name="login" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <hr>

                        <!-- Quick Login Buttons -->
                        <h5>Quick Login:</h5>
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" onclick="quickLogin('admin', 'admin123')">
                                Login as Admin
                            </button>
                            <button class="btn btn-outline-success" onclick="quickLogin('superadmin', 'super123')">
                                Login as Super Admin
                            </button>
                            <button class="btn btn-outline-warning" onclick="quickLogin('pengelolatps', 'password123')">
                                Login as TPS Manager
                            </button>
                            <button class="btn btn-outline-info" onclick="quickLogin('userjti', 'user123')">
                                Login as User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quickLogin(username, password) {
            document.getElementById('login').value = username;
            document.getElementById('password').value = password;
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>