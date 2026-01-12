<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Services\User\WasteService;

class Waste extends BaseController
{
    protected $wasteService;

    public function __construct()
    {
        $this->wasteService = new WasteService();
    }

    public function index()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $data = $this->wasteService->getWasteData();
            
            // Fallback: if categories empty, get directly from database
            if (empty($data['categories'])) {
                log_message('warning', 'User Waste - Categories empty from service, trying direct query');
                $db = \Config\Database::connect();
                $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
                $data['categories'] = $query->getResultArray();
                log_message('info', 'User Waste - Direct query got ' . count($data['categories']) . ' categories');
            }
            
            $viewData = [
                'title' => 'Manajemen Sampah User',
                'user' => $data['user'],
                'unit' => $data['unit'],
                'waste_list' => $data['waste_list'],
                'categories' => $data['categories'],
                'stats' => $data['stats']
            ];

            return view('user/waste', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Error: ' . $e->getMessage());
            
            // Last resort: get categories directly
            $categories = [];
            try {
                $db = \Config\Database::connect();
                $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
                $categories = $query->getResultArray();
            } catch (\Exception $dbError) {
                log_message('error', 'User Waste - Even direct query failed: ' . $dbError->getMessage());
            }
            
            return view('user/waste', [
                'title' => 'Manajemen Sampah User',
                'user' => session()->get('user'),
                'unit' => null,
                'waste_list' => [],
                'categories' => $categories,
                'stats' => [],
                'error' => 'Terjadi kesalahan saat memuat data sampah'
            ]);
        }
    }

    public function get($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $wasteModel = new \App\Models\WasteModel();
            $waste = $wasteModel->find($id);
            
            if (!$waste) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
            
            // Check if user owns this data or has permission
            $user = session()->get('user');
            $canEdit = false;
            
            // User can edit their own data
            if (isset($waste['user_id']) && $waste['user_id'] == $user['id']) {
                $canEdit = true;
            }
            
            // User from same unit can edit
            if (isset($waste['unit_id']) && isset($user['unit_id']) && $waste['unit_id'] == $user['unit_id']) {
                $canEdit = true;
            }
            
            // Admin can edit all
            if (in_array($user['role'], ['admin_pusat', 'super_admin'])) {
                $canEdit = true;
            }
            
            if (!$canEdit) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke data ini'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $waste
            ]);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Get Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function save()
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->wasteService->saveWaste($this->request->getPost());
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Save Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ]);
        }
    }

    public function edit($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->wasteService->updateWaste($id, $this->request->getPost());
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Edit Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->wasteService->deleteWaste($id);
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Delete Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }

    public function export()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            if (!isFeatureEnabled('export_data', 'user')) {
                return redirect()->back()->with('error', 'Fitur export tidak tersedia');
            }

            $result = $this->wasteService->exportWaste();
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data');
        }
    }

    private function validateSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['id'], $user['role'], $user['unit_id']) &&
               $user['role'] === 'user';
    }
}