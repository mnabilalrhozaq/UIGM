<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\HargaService;

class Harga extends BaseController
{
    protected $hargaService;

    public function __construct()
    {
        $this->hargaService = new HargaService();
    }

    public function index()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            // Get data from database directly
            $hargaModel = new \App\Models\HargaSampahModel();
            $allHarga = $hargaModel->findAll();
            $hargaList = $hargaModel->where('status_aktif', 1)->findAll();
            
            // Get log statistics (with error handling)
            $logChangesToday = 0;
            $logChangesTotal = 0;
            $recentChanges = [];
            
            try {
                $logModel = new \App\Models\HargaLogModel();
                $today = date('Y-m-d');
                $logChangesToday = $logModel->where('DATE(created_at)', $today)->countAllResults();
                $logChangesTotal = $logModel->countAllResults();
                
                if (method_exists($logModel, 'getRecentChanges')) {
                    $recentChanges = $logModel->getRecentChanges(5);
                }
            } catch (\Exception $logError) {
                log_message('warning', 'Harga Log Model Error: ' . $logError->getMessage());
            }
            
            // Calculate statistics
            $totalHarga = count($allHarga);
            $activeHarga = count($hargaList);
            $inactiveHarga = $totalHarga - $activeHarga;
            $sellableHarga = 0;
            
            foreach ($allHarga as $harga) {
                if ($harga['dapat_dijual']) {
                    $sellableHarga++;
                }
            }
            
            $viewData = [
                'title' => 'Manajemen Harga Sampah',
                'harga_list' => $hargaList,
                'hargaSampah' => $hargaList, // Alias untuk view
                'statistics' => [
                    'total' => $totalHarga,
                    'active' => $activeHarga,
                    'aktif' => $activeHarga,
                    'inactive' => $inactiveHarga,
                    'tidak_aktif' => $inactiveHarga,
                    'sellable' => $sellableHarga,
                    'dapat_dijual' => $sellableHarga,
                    'bisa_dijual' => $sellableHarga,
                    'last_updated' => date('Y-m-d H:i:s')
                ],
                'logStatistics' => [
                    'changes_today' => $logChangesToday,
                    'changes_total' => $logChangesTotal,
                    'last_change' => date('Y-m-d H:i:s')
                ],
                'recentChanges' => $recentChanges,
                'categories' => []
            ];

            return view('admin_pusat/manajemen_harga/index', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Error: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            
            return view('admin_pusat/manajemen_harga/index', [
                'title' => 'Manajemen Harga Sampah',
                'harga_list' => [],
                'hargaSampah' => [],
                'statistics' => [
                    'total' => 0,
                    'active' => 0,
                    'aktif' => 0,
                    'inactive' => 0,
                    'tidak_aktif' => 0,
                    'sellable' => 0,
                    'dapat_dijual' => 0,
                    'bisa_dijual' => 0,
                    'last_updated' => date('Y-m-d H:i:s')
                ],
                'logStatistics' => [
                    'changes_today' => 0,
                    'changes_total' => 0,
                    'last_change' => date('Y-m-d H:i:s')
                ],
                'recentChanges' => [],
                'categories' => [],
                'error' => 'Terjadi kesalahan saat memuat data harga: ' . $e->getMessage()
            ]);
        }
    }

    public function get($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $hargaModel = new \App\Models\HargaSampahModel();
            $harga = $hargaModel->find($id);
            
            if (!$harga) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data harga tidak ditemukan'
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $harga
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Get Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->hargaService->createHarga($this->request->getPost());
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Store Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data'
            ]);
        }
    }

    public function update($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $hargaModel = new \App\Models\HargaSampahModel();
            
            // Get current data
            $currentData = $hargaModel->find($id);
            if (!$currentData) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data harga tidak ditemukan'
                ]);
            }
            
            // Prepare update data
            $data = [
                'jenis_sampah' => $this->request->getPost('jenis_sampah'),
                'nama_jenis' => $this->request->getPost('nama_jenis'),
                'harga_per_satuan' => $this->request->getPost('harga_per_satuan'),
                'satuan' => $this->request->getPost('satuan'),
                'dapat_dijual' => $this->request->getPost('dapat_dijual') ? 1 : 0,
                'status_aktif' => $this->request->getPost('status_aktif') ? 1 : 0,
                'deskripsi' => $this->request->getPost('deskripsi')
            ];
            
            // Update
            if ($hargaModel->update($id, $data)) {
                // Save log if price changed
                $oldPrice = $currentData['harga_per_satuan'];
                $newPrice = $data['harga_per_satuan'];
                
                if ($oldPrice != $newPrice) {
                    try {
                        $logModel = new \App\Models\HargaLogModel();
                        $session = session();
                        $user = $session->get('user');
                        
                        // Use model's logPriceChange method
                        $logModel->logPriceChange(
                            $id,
                            $data['jenis_sampah'],
                            $oldPrice,
                            $newPrice,
                            $user['id'] ?? 0,
                            'Update harga dari Rp ' . number_format($oldPrice, 0, ',', '.') . ' ke Rp ' . number_format($newPrice, 0, ',', '.')
                        );
                        
                        log_message('info', "Price change logged: {$data['jenis_sampah']} from {$oldPrice} to {$newPrice}");
                    } catch (\Exception $logError) {
                        log_message('error', 'Failed to save price change log: ' . $logError->getMessage());
                    }
                }
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Harga berhasil diupdate'
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengupdate harga'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Update Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleStatus($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->hargaService->toggleStatus($id);
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Toggle Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengubah status'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            if (!$this->validateSession()) {
                return $this->response->setJSON(['success' => false, 'message' => 'Session invalid']);
            }

            $result = $this->hargaService->deleteHarga($id);
            
            return $this->response->setJSON($result);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Delete Error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ]);
        }
    }

    public function logs()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            // Get logs from database
            $logModel = new \App\Models\HargaLogModel();
            $logs = $logModel->getRecentChanges(100); // Get more logs
            
            // Calculate statistics
            $today = date('Y-m-d');
            $weekStart = date('Y-m-d', strtotime('-7 days'));
            
            $todayLogs = 0;
            $weekLogs = 0;
            
            foreach ($logs as $log) {
                $logDate = date('Y-m-d', strtotime($log['created_at']));
                
                if ($logDate === $today) {
                    $todayLogs++;
                }
                
                if ($logDate >= $weekStart) {
                    $weekLogs++;
                }
            }
            
            $viewData = [
                'title' => 'Log Perubahan Harga',
                'logs' => $logs,
                'stats' => [
                    'total_logs' => count($logs),
                    'today_logs' => $todayLogs,
                    'week_logs' => $weekLogs
                ]
            ];

            return view('admin_pusat/manajemen_harga/logs', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Admin Harga Logs Error: ' . $e->getMessage());
            
            return view('admin_pusat/manajemen_harga/logs', [
                'title' => 'Log Perubahan Harga',
                'logs' => [],
                'stats' => [
                    'total_logs' => 0,
                    'today_logs' => 0,
                    'week_logs' => 0
                ],
                'error' => 'Terjadi kesalahan saat memuat log'
            ]);
        }
    }

    private function validateSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['role']) &&
               in_array($user['role'], ['admin_pusat', 'super_admin']);
    }
}