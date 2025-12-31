<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Login - UIGM Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #5c8cbf;
            margin: 0;
            font-size: 28px;
        }

        .header p {
            color: #666;
            margin: 10px 0 0 0;
        }

        .credentials {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .credentials h3 {
            color: #5c8cbf;
            margin-top: 0;
            margin-bottom: 15px;
        }

        .credential-item {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            border-left: 4px solid #5c8cbf;
        }

        .credential-item strong {
            color: #333;
        }

        .credential-item .role {
            color: #5c8cbf;
            font-weight: bold;
        }

        .login-btn {
            display: inline-block;
            background: #5c8cbf;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px auto;
            display: block;
            width: fit-content;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #4a7ba7;
        }

        .note {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 20px 0;
        }

        .note strong {
            color: #1976d2;
        }
    </style>
</head>

<body>
    <div class="info-container">
        <div class="header">
            <h1>Dashboard Admin UIGM</h1>
            <p>Informasi Login untuk Testing</p>
        </div>

        <div class="note">
            <strong>Catatan:</strong> Password sekarang menggunakan plain text (tidak di-hash) sesuai permintaan.
        </div>

        <div class="credentials">
            <h3>Akun Testing yang Tersedia:</h3>

            <div class="credential-item">
                <div class="role">Admin Pusat</div>
                <strong>Username:</strong> admin_pusat<br>
                <strong>Password:</strong> adminpusat123<br>
                <strong>Email:</strong> admin.pusat@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Super Admin</div>
                <strong>Username:</strong> super_admin<br>
                <strong>Password:</strong> superadmin123<br>
                <strong>Email:</strong> super.admin@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Admin Unit - Fakultas Teknik Elektro</div>
                <strong>Username:</strong> admin_fte<br>
                <strong>Password:</strong> adminunit123<br>
                <strong>Email:</strong> admin_fte@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Admin Unit - Fakultas Teknik Mesin dan Dirgantara</div>
                <strong>Username:</strong> admin_ftmd<br>
                <strong>Password:</strong> adminunit123<br>
                <strong>Email:</strong> admin_ftmd@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Admin Unit - Fakultas Teknik Sipil dan Lingkungan</div>
                <strong>Username:</strong> admin_ftsl<br>
                <strong>Password:</strong> adminunit123<br>
                <strong>Email:</strong> admin_ftsl@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Admin Unit - Fakultas Rekayasa Industri</div>
                <strong>Username:</strong> admin_fri<br>
                <strong>Password:</strong> adminunit123<br>
                <strong>Email:</strong> admin_fri@polban.ac.id
            </div>

            <div class="credential-item">
                <div class="role">Admin Unit - Fakultas Informatika</div>
                <strong>Username:</strong> admin_fif<br>
                <strong>Password:</strong> adminunit123<br>
                <strong>Email:</strong> admin_fif@polban.ac.id
            </div>
        </div>

        <a href="/auth/login" class="login-btn">Masuk ke Halaman Login</a>

        <div class="note">
            <strong>Fitur yang Sudah Tersedia:</strong><br>
            ✓ Database terhubung dengan data sample<br>
            ✓ Login dengan role-based access control<br>
            ✓ Dashboard Admin Unit dengan 6 kategori UIGM<br>
            ✓ Form input data dan progress tracking<br>
            ✓ Sistem notifikasi dan review<br>
            ✓ Password plain text (tidak di-hash)
        </div>
    </div>
</body>

</html>