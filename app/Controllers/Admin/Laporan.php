<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\LaporanService;

class Laporan extends BaseController
{
    protected $laporanService;

    public function __construct()
    {
        $this->laporanService = new LaporanService();
    }

    public function index()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $data = $this->laporanService->getLaporanData();
            
            // Get all units for filter dropdown
            $unitModel = new \App\Models\UnitModel();
            $allUnits = $unitModel->where('status_aktif', 1)->findAll();
            
            // Get filter parameters
            $filters = [
                'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
                'end_date' => $this->request->getGet('end_date') ?? date('Y-m-d'),
                'tahun' => $this->request->getGet('tahun') ?? date('Y'),
                'bulan' => $this->request->getGet('bulan') ?? date('m'),
                'unit_id' => $this->request->getGet('unit_id') ?? '',
                'unit' => $this->request->getGet('unit') ?? '',
                'kategori' => $this->request->getGet('kategori') ?? '',
                'status' => $this->request->getGet('status') ?? ''
            ];
            
            $viewData = [
                'title' => 'Laporan & Monitoring',
                'monthly_report' => $data['monthly_report'] ?? [],
                'yearly_report' => $data['yearly_report'] ?? [],
                'tps_report' => $data['tps_report'] ?? [],
                'summary_stats' => $data['summary_stats'] ?? [],
                'progressUnit' => $data['progress_unit'] ?? [],
                'wasteByType' => $data['waste_by_type'] ?? [],
                'rekapWaste' => $data['rekap_waste'] ?? [],
                'summary' => [
                    'total_data' => $data['summary_stats']['total_data'] ?? 0,
                    'disetujui' => $data['summary_stats']['disetujui'] ?? 0,
                    'pending' => $data['summary_stats']['pending'] ?? 0,
                    'menunggu_review' => $data['summary_stats']['menunggu_review'] ?? 0,
                    'ditolak' => $data['summary_stats']['ditolak'] ?? 0,
                    'perlu_revisi' => $data['summary_stats']['perlu_revisi'] ?? 0
                ],
                'allUnits' => $allUnits,
                'filters' => $filters
            ];

            return view('admin_pusat/laporan', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Admin Laporan Error: ' . $e->getMessage());
            
            // Get units even in error case
            try {
                $unitModel = new \App\Models\UnitModel();
                $allUnits = $unitModel->where('status_aktif', 1)->findAll();
            } catch (\Exception $unitError) {
                $allUnits = [];
            }
            
            return view('admin_pusat/laporan', [
                'title' => 'Laporan & Monitoring',
                'monthly_report' => [],
                'yearly_report' => [],
                'tps_report' => [],
                'summary_stats' => [],
                'progressUnit' => [],
                'wasteByType' => [],
                'rekapWaste' => [],
                'summary' => [
                    'total_data' => 0,
                    'disetujui' => 0,
                    'pending' => 0,
                    'menunggu_review' => 0,
                    'ditolak' => 0,
                    'perlu_revisi' => 0
                ],
                'allUnits' => $allUnits,
                'filters' => [
                    'start_date' => date('Y-m-01'),
                    'end_date' => date('Y-m-d'),
                    'tahun' => date('Y'),
                    'bulan' => date('m'),
                    'unit_id' => '',
                    'unit' => '',
                    'kategori' => '',
                    'status' => ''
                ],
                'error' => 'Terjadi kesalahan saat memuat laporan: ' . $e->getMessage()
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

            $result = $this->laporanService->exportLaporan();
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Admin Laporan Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export laporan');
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