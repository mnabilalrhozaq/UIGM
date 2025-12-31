<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Login - UIGM Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }

        .test-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .test-form {
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .form-group {
            margin: 15px 0;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            background: #5c8cbf;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #4a7ba7;
        }

        .credentials {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .result {
            margin: 20px 0;
            padding: 15px;
            border-radius: 5px;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="test-container">
        <h1>Test Login Dashboard UIGM</h1>
        <p>Halaman ini untuk testing login dengan berbagai akun yang tersedia.</p>

        <div class="credentials">
            <h3>Akun Testing Tersedia:</h3>
            <ul>
                <li><strong>Admin Pusat:</strong> admin_pusat / adminpusat123</li>
                <li><strong>Super Admin:</strong> super_admin / superadmin123</li>
                <li><strong>Admin Unit FTE:</strong> admin_fte / adminunit123</li>
                <li><strong>Admin Unit FTMD:</strong> admin_ftmd / adminunit123</li>
                <li><strong>Admin Unit FTSL:</strong> admin_ftsl / adminunit123</li>
                <li><strong>Admin Unit FRI:</strong> admin_fri / adminunit123</li>
                <li><strong>Admin Unit FIF:</strong> admin_fif / adminunit123</li>
            </ul>
        </div>

        <div class="test-form">
            <h3>Test Login</h3>
            <form action="/auth/process-login" method="POST">
                <div class="form-group">
                    <label for="quick-select">Pilih Akun Cepat:</label>
                    <select id="quick-select" onchange="fillCredentials()">
                        <option value="">-- Pilih Akun --</option>
                        <option value="admin_pusat|adminpusat123">Admin Pusat</option>
                        <option value="super_admin|superadmin123">Super Admin</option>
                        <option value="admin_fte|adminunit123">Admin Unit FTE</option>
                        <option value="admin_ftmd|adminunit123">Admin Unit FTMD</option>
                        <option value="admin_ftsl|adminunit123">Admin Unit FTSL</option>
                        <option value="admin_fri|adminunit123">Admin Unit FRI</option>
                        <option value="admin_fif|adminunit123">Admin Unit FIF</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="login">Username/Email:</label>
                    <input type="text" id="login" name="login" required>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <button type="submit">Test Login</button>
            </form>
        </div>

        <div class="test-form">
            <h3>Link Langsung ke Dashboard</h3>
            <p>Setelah login berhasil, Anda akan diarahkan ke dashboard sesuai role:</p>
            <ul>
                <li><a href="/admin-pusat/dashboard">Dashboard Admin Pusat</a></li>
                <li><a href="/admin-unit/dashboard">Dashboard Admin Unit</a></li>
                <li><a href="/super-admin/dashboard">Dashboard Super Admin</a></li>
            </ul>
            <p><em>Catatan: Link di atas akan redirect ke login jika belum login.</em></p>
        </div>

        <div class="test-form">
            <h3>Status Database</h3>
            <p>✅ Database terhubung: <strong>uigm_polban</strong></p>
            <p>✅ Password menggunakan: <strong>Plain Text (tidak di-hash)</strong></p>
            <p>✅ Data sample telah diisi</p>
            <p>✅ Semua controller telah dibuat</p>
        </div>
    </div>

    <script>
        function fillCredentials() {
            const select = document.getElementById('quick-select');
            const loginInput = document.getElementById('login');
            const passwordInput = document.getElementById('password');

            if (select.value) {
                const [username, password] = select.value.split('|');
                loginInput.value = username;
                passwordInput.value = password;
            } else {
                loginInput.value = '';
                passwordInput.value = '';
            }
        }

        // Show any flash messages
        <?php if (session()->getFlashdata('error')): ?>
            const errorDiv = document.createElement('div');
            errorDiv.className = 'result error';
            errorDiv.innerHTML = '<strong>Error:</strong> <?= session()->getFlashdata('error') ?>';
            document.querySelector('.test-container').insertBefore(errorDiv, document.querySelector('.test-form'));
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            const successDiv = document.createElement('div');
            successDiv.className = 'result success';
            successDiv.innerHTML = '<strong>Success:</strong> <?= session()->getFlashdata('success') ?>';
            document.querySelector('.test-container').insertBefore(successDiv, document.querySelector('.test-form'));
        <?php endif; ?>
    </script>
</body>

</html>