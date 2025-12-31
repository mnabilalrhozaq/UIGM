<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Halaman login
     */
    public function login()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (session()->get('isLoggedIn')) {
            return $this->redirectToDashboard(session()->get('user')['role']);
        }

        return view('auth/login');
    }

    /**
     * Proses login
     */
    public function processLogin()
    {
        $rules = [
            'login' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Email atau username harus diisi'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        try {
            // Cari user berdasarkan email atau username
            $user = $this->userModel
                ->where('email', $login)
                ->orWhere('username', $login)
                ->where('status_aktif', true)
                ->first();

            if (!$user) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email/username atau password salah');
            }

            // Verifikasi password
            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Email/username atau password salah');
            }

            // Validasi role
            $allowedRoles = ['admin_pusat', 'admin_unit', 'super_admin'];
            if (!in_array($user['role'], $allowedRoles)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Akun Anda tidak memiliki akses ke sistem ini');
            }

            // Set session
            $sessionData = [
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'nama_lengkap' => $user['nama_lengkap'],
                    'role' => $user['role'],
                    'unit_id' => $user['unit_id']
                ],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            // Update last login
            $this->userModel->updateLastLogin($user['id']);

            // Redirect ke dashboard sesuai role
            return $this->redirectToDashboard($user['role']);
        } catch (\Exception $e) {
            log_message('error', 'Login Error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Redirect ke dashboard sesuai role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin_pusat':
                return redirect()->to('/admin-pusat/dashboard')
                    ->with('success', 'Selamat datang di Dashboard Admin Pusat');

            case 'admin_unit':
                return redirect()->to('/admin-unit/dashboard')
                    ->with('success', 'Selamat datang di Dashboard Admin Unit');

            case 'super_admin':
                return redirect()->to('/super-admin/dashboard')
                    ->with('success', 'Selamat datang di Dashboard Super Admin');

            default:
                session()->destroy();
                return redirect()->to('/auth/login')
                    ->with('error', 'Role tidak dikenali. Silakan hubungi administrator.');
        }
    }

    /**
     * Middleware untuk cek login
     */
    public function checkAuth()
    {
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login')
                ->with('error', 'Silakan login terlebih dahulu');
        }
    }

    /**
     * Middleware untuk cek role
     */
    public function checkRole($allowedRoles = [])
    {
        $this->checkAuth();

        $userRole = session()->get('user')['role'] ?? null;

        if (!in_array($userRole, $allowedRoles)) {
            return redirect()->to('/auth/login')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }
}
