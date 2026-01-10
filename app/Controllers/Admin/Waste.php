<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\WasteService;

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

            log_message('info', 'Admin Waste Controller - Starting index...');
            
            // Direct query for debugging
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM waste_management ORDER BY created_at DESC LIMIT 100");
            $directData = $query->getResultArray();
            
            log_message('info', 'Admin Waste Controller - Direct query found: ' . count($directData) . ' records');
            
            $data = $this->wasteService->getWasteData();
            
            log_message('info', 'Admin Waste Controller - Service returned: ' . count($data['waste_list']) . ' records');
            
            // Use direct data if service returns empty
            if (empty($data['waste_list']) && !empty($directData)) {
                log_message('warning', 'Admin Waste Controller - Using direct query data instead of service');
                $data['waste_list'] = $directData;
            }
            
            $viewData = [
                'title' => 'Review Data Sampah',
                'waste_list' => $data['waste_list'],
                'summary' => $data['summary'],
                'filters' => $data['filters'] ?? [],
                'statistics' => $data['statistics'] ?? []
            ];

            return view('admin_pusat/waste_management', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Admin Waste Controller Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return view('admin_pusat/waste_management', [
                'title' => 'Review Data Sampah',
                'waste_list' => [],
                'summary' => [],
                'error' => 'Terjadi kesalahan saat memuat data sampah: ' . $e->getMessage()
            ]);
        }
    }

    public function export()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            if (!isFeatureEnabled('export_data', 'admin_pusat')) {
                return redirect()->back()->with('error', 'Fitur export tidak tersedia');
            }

            $result = $this->wasteService->exportWaste();
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Admin Waste Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export data');
        }
    }

    public function approve($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            log_message('info', 'Admin - Approving waste ID: ' . $id);

            $result = $this->wasteService->approveWaste($id, $this->request->getPost());
            
            log_message('info', 'Admin - Approve result: ' . json_encode($result));
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'Admin Waste Approve Error: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyetujui data: ' . $e->getMessage()
                ]);
        }
    }

    public function reject($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            log_message('info', 'Admin - Rejecting waste ID: ' . $id);

            $result = $this->wasteService->rejectWaste($id, $this->request->getPost());
            
            log_message('info', 'Admin - Reject result: ' . json_encode($result));
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'Admin Waste Reject Error: ' . $e->getMessage());
            
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menolak data: ' . $e->getMessage()
                ]);
        }
    }

    private function validateSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['id'], $user['role'], $user['unit_id']) &&
               in_array($user['role'], ['admin_pusat', 'super_admin']);
    }
}