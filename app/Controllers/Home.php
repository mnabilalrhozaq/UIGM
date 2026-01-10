<?php

namespace App\Controllers;

class Home extends BaseController
{
    /**
     * Home page - redirects to appropriate dashboard based on user role
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function index()
    {
        // Check if user is logged in
        $user = session()->get('user');
        
        if (session()->get('isLoggedIn') && $user) {
            $role = $user['role'] ?? null;
            
            // Redirect to appropriate dashboard based on role using match expression
            return match ($role) {
                'admin_pusat', 'super_admin' => redirect()->to('/admin-pusat/dashboard'),
                'user' => redirect()->to('/user/dashboard'),
                'pengelola_tps' => redirect()->to('/pengelola-tps/dashboard'),
                default => redirect()->to('/auth/login')
            };
        }
        
        // If not logged in, redirect to login
        return redirect()->to('/auth/login');
    }
}