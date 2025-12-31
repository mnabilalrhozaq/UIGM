<?php

namespace App\Controllers;

use App\Models\UnitModel;
use App\Models\TahunPenilaianModel;
use App\Models\PengirimanUnitModel;
use App\Models\ReviewKategoriModel;
use App\Models\IndikatorModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class SuperAdmin extends BaseController
{
    protected $unitModel;
    protected $tahunModel;
    protected $pengirimanModel;
    protected $reviewModel;
    protected $indikatorModel;
    protected $notifikasiModel;
    protected $userModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->tahunModel = new TahunPenilaianModel();
        $this->pengirimanModel = new PengirimanUnitModel();
        $this->reviewModel = new ReviewKategoriModel();
        $this->indikatorModel = new IndikatorModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->userModel = new UserModel();
    }

    /**
     * Dashboard utama Super Admin
     */
    public function index()
    {
        // Ambil user dari session
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'super_admin') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak. Anda harus login sebagai Super Admin.');
        }

        // Ambil tahun penilaian aktif
        $tahunAktif = $this->tahunModel->getActiveYear();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun penilaian aktif');
        }

        // Statistik sistem
        $systemStats = $this->getSystemStats();

        // Aktivitas terbaru
        $recentActivities = $this->getRecentActivities();

        $data = [
            'title' => 'Dashboard Super Admin UIGM',
            'user' => $user,
            'tahun' => $tahunAktif,
            'stats' => $systemStats,
            'activities' => $recentActivities
        ];

        return view('super_admin/dashboard', $data);
    }

    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        $totalUsers = $this->userModel->where('status_aktif', true)->countAllResults();
        $totalUnits = $this->unitModel->where('status_aktif', true)->countAllResults();
        $totalSubmissions = $this->pengirimanModel->countAllResults();
        $totalCategories = $this->indikatorModel->where('status_aktif', true)->countAllResults();

        // User statistics by role
        $usersByRole = $this->userModel
            ->select('role, COUNT(*) as jumlah')
            ->where('status_aktif', true)
            ->groupBy('role')
            ->findAll();

        $roleStats = ['admin_pusat' => 0, 'admin_unit' => 0, 'super_admin' => 0];
        foreach ($usersByRole as $stat) {
            $roleStats[$stat['role']] = $stat['jumlah'];
        }

        return [
            'total_users' => $totalUsers,
            'total_units' => $totalUnits,
            'total_submissions' => $totalSubmissions,
            'total_categories' => $totalCategories,
            'role_stats' => $roleStats
        ];
    }

    /**
     * Manajemen Users
     */
    public function users()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'super_admin') {
            return redirect()->to('/auth/login')->with('error', 'Akses ditolak.');
        }

        $users = $this->userModel
            ->select('users.*, unit.nama_unit')
            ->join('unit', 'unit.id = users.unit_id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();

        $units = $this->unitModel->where('status_aktif', true)->findAll();

        $data = [
            'title' => 'Manajemen Users - Super Admin',
            'user' => $user,
            'users' => $users,
            'units' => $units
        ];

        return view('super_admin/users', $data);
    }

    /**
     * Create User
     */
    public function createUser()
    {
        $request = $this->request;

        $rules = [
            'username' => 'required|min_length[3]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'nama_lengkap' => 'required',
            'role' => 'required|in_list[admin_pusat,admin_unit,super_admin]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $userData = [
                'username' => $request->getPost('username'),
                'email' => $request->getPost('email'),
                'password' => $request->getPost('password'),
                'nama_lengkap' => $request->getPost('nama_lengkap'),
                'role' => $request->getPost('role'),
                'unit_id' => $request->getPost('unit_id') ?: null,
                'status_aktif' => true
            ];

            $this->userModel->insert($userData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update User
     */
    public function updateUser()
    {
        $request = $this->request;
        $userId = $request->getPost('user_id');

        $rules = [
            'username' => "required|min_length[3]|is_unique[users.username,id,{$userId}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'nama_lengkap' => 'required',
            'role' => 'required|in_list[admin_pusat,admin_unit,super_admin]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $userData = [
                'username' => $request->getPost('username'),
                'email' => $request->getPost('email'),
                'nama_lengkap' => $request->getPost('nama_lengkap'),
                'role' => $request->getPost('role'),
                'unit_id' => $request->getPost('unit_id') ?: null,
                'status_aktif' => $request->getPost('status_aktif') ? true : false
            ];

            // Update password jika diisi
            if ($request->getPost('password')) {
                $userData['password'] = $request->getPost('password');
            }

            $this->userModel->update($userId, $userData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal update user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Delete User
     */
    public function deleteUser($userId)
    {
        try {
            $this->userModel->delete($userId);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'User berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menghapus user: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Manajemen Units
     */
    public function units()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'super_admin') {
            return redirect()->to('/auth/login')->with('error', 'Akses ditolak.');
        }

        $units = $this->unitModel
            ->select('unit.*, users.nama_lengkap as admin_name')
            ->join('users', 'users.id = unit.admin_unit_id', 'left')
            ->orderBy('unit.nama_unit')
            ->findAll();

        $data = [
            'title' => 'Manajemen Unit - Super Admin',
            'user' => $user,
            'units' => $units
        ];

        return view('super_admin/units', $data);
    }

    /**
     * Create Unit
     */
    public function createUnit()
    {
        $request = $this->request;

        $rules = [
            'nama_unit' => 'required',
            'kode_unit' => 'required|is_unique[unit.kode_unit]',
            'tipe_unit' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $unitData = [
                'nama_unit' => $request->getPost('nama_unit'),
                'kode_unit' => $request->getPost('kode_unit'),
                'tipe_unit' => $request->getPost('tipe_unit'),
                'parent_id' => $request->getPost('parent_id') ?: null,
                'status_aktif' => true
            ];

            $this->unitModel->insert($unitData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Unit berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat unit: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update Unit
     */
    public function updateUnit()
    {
        $request = $this->request;
        $unitId = $request->getPost('unit_id');

        $rules = [
            'nama_unit' => 'required',
            'kode_unit' => "required|is_unique[unit.kode_unit,id,{$unitId}]",
            'tipe_unit' => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            $unitData = [
                'nama_unit' => $request->getPost('nama_unit'),
                'kode_unit' => $request->getPost('kode_unit'),
                'tipe_unit' => $request->getPost('tipe_unit'),
                'parent_id' => $request->getPost('parent_id') ?: null,
                'status_aktif' => $request->getPost('status_aktif') ? true : false
            ];

            $this->unitModel->update($unitId, $unitData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Unit berhasil diupdate'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal update unit: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Manajemen Tahun Penilaian
     */
    public function tahunPenilaian()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'super_admin') {
            return redirect()->to('/auth/login')->with('error', 'Akses ditolak.');
        }

        $tahunList = $this->tahunModel->orderBy('tahun', 'DESC')->findAll();

        $data = [
            'title' => 'Manajemen Tahun Penilaian - Super Admin',
            'user' => $user,
            'tahunList' => $tahunList
        ];

        return view('super_admin/tahun_penilaian', $data);
    }

    /**
     * Create Tahun Penilaian
     */
    public function createTahunPenilaian()
    {
        $request = $this->request;

        $rules = [
            'tahun' => 'required|integer|is_unique[tahun_penilaian.tahun]',
            'nama_periode' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        try {
            // Nonaktifkan tahun lain jika ini akan diaktifkan
            if ($request->getPost('status_aktif')) {
                $this->tahunModel->where('status_aktif', true)->set(['status_aktif' => false])->update();
            }

            $tahunData = [
                'tahun' => $request->getPost('tahun'),
                'nama_periode' => $request->getPost('nama_periode'),
                'tanggal_mulai' => $request->getPost('tanggal_mulai'),
                'tanggal_selesai' => $request->getPost('tanggal_selesai'),
                'status_aktif' => $request->getPost('status_aktif') ? true : false
            ];

            $this->tahunModel->insert($tahunData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Tahun penilaian berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal membuat tahun penilaian: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get recent system activities
     */
    private function getRecentActivities()
    {
        // Get recent submissions
        $recentSubmissions = $this->pengirimanModel
            ->select('pengiriman_unit.*, unit.nama_unit, users.nama_lengkap')
            ->join('unit', 'unit.id = pengiriman_unit.unit_id')
            ->join('users', 'users.unit_id = unit.id AND users.role = "admin_unit"', 'left')
            ->orderBy('pengiriman_unit.updated_at', 'DESC')
            ->limit(10)
            ->findAll();

        return $recentSubmissions;
    }
}
