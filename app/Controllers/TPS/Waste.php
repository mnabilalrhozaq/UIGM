<?php

namespace App\Controllers\TPS;

use App\Controllers\BaseController;
use App\Services\TPS\WasteService;

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
                log_message('warning', 'TPS Waste - Categories empty from service, trying direct query');
                $db = \Config\Database::connect();
                $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
                $data['categories'] = $query->getResultArray();
                log_message('info', 'TPS Waste - Direct query got ' . count($data['categories']) . ' categories');
            }
            
            $viewData = [
                'title' => 'Manajemen Sampah TPS',
                'waste_list' => $data['waste_list'],
                'categories' => $data['categories'],
                'tps_info' => $data['tps_info']
            ];

            return view('pengelola_tps/waste', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'TPS Waste Error: ' . $e->getMessage());
            
            // Last resort: get categories directly
            $categories = [];
            try {
                $db = \Config\Database::connect();
                $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
                $categories = $query->getResultArray();
            } catch (\Exception $dbError) {
                log_message('error', 'TPS Waste - Even direct query failed: ' . $dbError->getMessage());
            }
            
            return view('pengelola_tps/waste', [
                'title' => 'Manajemen Sampah TPS',
                'waste_list' => [],
                'categories' => $categories,
                'tps_info' => null,
                'error' => 'Terjadi kesalahan saat memuat data sampah'
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
            log_message('error', 'TPS Waste Save Error: ' . $e->getMessage());
            
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
            log_message('error', 'TPS Waste Edit Error: ' . $e->getMessage());
            
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
            log_message('error', 'TPS Waste Delete Error: ' . $e->getMessage());
            
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

            if (!isFeatureEnabled('export_data', 'pengelola_tps')) {
                return redirect()->back()->with('error', 'Fitur export tidak tersedia');
            }

            $result = $this->wasteService->exportWaste();
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'TPS Waste Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data');
        }
    }

    private function validateSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['id'], $user['role'], $user['unit_id']) &&
               $user['role'] === 'pengelola_tps';
    }
}