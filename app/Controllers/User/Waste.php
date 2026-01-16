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
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $wasteModel = new \App\Models\WasteModel();
            $waste = $wasteModel->find($id);
            
            if (!$waste) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON([
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
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses ke data ini'
                    ]);
            }

            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'success' => true,
                    'data' => $waste
                ]);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Get Error: ' . $e->getMessage());
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    public function save()
    {
        try {
            if (!$this->validateSession()) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->wasteService->saveWaste($this->request->getPost());
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Save Error: ' . $e->getMessage());
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data'
                ]);
        }
    }

    public function edit($id)
    {
        try {
            log_message('info', '=== User Waste Edit START ===');
            log_message('info', 'ID: ' . $id);
            log_message('info', 'POST: ' . json_encode($this->request->getPost()));
            
            if (!$this->validateSession()) {
                log_message('warning', 'Session invalid');
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            // Get POST data
            $data = $this->request->getPost();
            
            // Validate
            if (empty($data['kategori_id'])) {
                log_message('warning', 'kategori_id empty');
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Kategori sampah harus dipilih']);
            }
            
            if (empty($data['berat_kg']) || $data['berat_kg'] <= 0) {
                log_message('warning', 'berat_kg invalid: ' . ($data['berat_kg'] ?? 'null'));
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Jumlah/berat harus diisi']);
            }
            
            // Get user
            $user = session()->get('user');
            log_message('info', 'User ID: ' . ($user['id'] ?? 'null'));
            
            // Get waste
            $wasteModel = new \App\Models\WasteModel();
            $waste = $wasteModel->find($id);
            log_message('info', 'Waste found: ' . ($waste ? 'yes' : 'no'));
            
            if (!$waste) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Data tidak ditemukan']);
            }
            
            if ($waste['unit_id'] != $user['unit_id']) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Bukan milik unit Anda']);
            }
            
            if (!in_array($waste['status'], ['draft', 'perlu_revisi'])) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Data sudah disubmit tidak dapat diedit']);
            }
            
            // Get category
            $hargaModel = new \App\Models\HargaSampahModel();
            $category = $hargaModel->find($data['kategori_id']);
            log_message('info', 'Category found: ' . ($category ? 'yes' : 'no'));
            
            if (!$category) {
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Kategori tidak ditemukan']);
            }
            
            // Prepare update data
            $status = (isset($data['status_action']) && $data['status_action'] === 'kirim') ? 'dikirim' : 'draft';
            
            // berat_kg sudah dalam kg dari frontend (sudah dikonversi)
            $beratKg = $data['berat_kg'];
            $satuan = $data['satuan'] ?? $waste['satuan'] ?? 'kg';
            
            $updateData = [
                'berat_kg' => $beratKg,
                'jumlah' => $beratKg,
                'satuan' => $satuan,
                'jenis_sampah' => $category['jenis_sampah'],
                'kategori_sampah' => $category['dapat_dijual'] ? 'bisa_dijual' : 'tidak_dijual',
                'nilai_rupiah' => $category['dapat_dijual'] ? ($beratKg * $category['harga_per_satuan']) : 0,
                'status' => $status
            ];
            
            log_message('info', 'Update data: ' . json_encode($updateData));
            
            // Update
            $result = $wasteModel->update($id, $updateData);
            log_message('info', 'Update result: ' . ($result ? 'success' : 'failed'));
            
            if ($result) {
                $message = $status === 'dikirim' ? 'Data berhasil diupdate dan dikirim' : 'Data berhasil diupdate sebagai draft';
                log_message('info', '=== User Waste Edit SUCCESS ===');
                return $this->response
                    ->setContentType('application/json')
                    ->setJSON(['success' => true, 'message' => $message]);
            }
            
            log_message('error', '=== User Waste Edit FAILED ===');
            return $this->response
                ->setContentType('application/json')
                ->setJSON(['success' => false, 'message' => 'Gagal mengupdate data']);

        } catch (\Throwable $e) {
            log_message('error', '=== User Waste Edit EXCEPTION ===');
            log_message('error', 'Message: ' . $e->getMessage());
            log_message('error', 'File: ' . $e->getFile() . ':' . $e->getLine());
            log_message('error', 'Trace: ' . $e->getTraceAsString());
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    public function delete($id)
    {
        try {
            log_message('info', 'User Delete Waste - ID: ' . $id);
            
            if (!$this->validateSession()) {
                log_message('warning', 'User Delete Waste - Session invalid');
                return $this->response
                    ->setStatusCode(401)
                    ->setContentType('application/json')
                    ->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            log_message('info', 'User Delete Waste - Calling service...');
            $result = $this->wasteService->deleteWaste($id);
            log_message('info', 'User Delete Waste - Result: ' . json_encode($result));
            
            return $this->response
                ->setContentType('application/json')
                ->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'User Waste Delete Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return $this->response
                ->setStatusCode(500)
                ->setContentType('application/json')
                ->setJSON([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
                ]);
        }
    }

    public function export()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            // Check if feature toggle function exists
            if (function_exists('isFeatureEnabled')) {
                if (!isFeatureEnabled('export_data', 'user')) {
                    return redirect()->back()->with('error', 'Fitur export tidak tersedia');
                }
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