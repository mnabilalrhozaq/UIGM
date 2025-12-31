<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        // Redirect ke login jika belum login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/auth/login');
        }

        // Redirect ke dashboard sesuai role jika sudah login
        $user = session()->get('user');
        switch ($user['role']) {
            case 'admin_pusat':
                return redirect()->to('/admin-pusat/dashboard');
            case 'admin_unit':
                return redirect()->to('/admin-unit/dashboard');
            case 'super_admin':
                return redirect()->to('/super-admin/dashboard');
            default:
                return redirect()->to('/auth/login');
        }
    }
}
