<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\LaporanWasteService;

class LaporanWaste extends BaseController
{
    protected $laporanWasteService;

    public function __construct()
    {
        $this->laporanWasteService = new LaporanWasteService();
    }

    public function index()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            // Get approved waste data
            $wasteModel = new \App\Models\WasteModel();
            $unitModel = new \App\Models\UnitModel();
            
            // Get filter parameters
            $filters = [
                'start_date' => $this->request->getGet('start_date') ?? date('Y-m-01'),
                'end_date' => $this->request->getGet('end_date') ?? date('Y-m-d'),
                'tahun' => $this->request->getGet('tahun') ?? date('Y'),
                'bulan' => $this->request->getGet('bulan') ?? date('m'),
                'unit_id' => $this->request->getGet('unit_id') ?? '',
                'jenis_sampah' => $this->request->getGet('jenis_sampah') ?? ''
            ];
            
            // Get all units for filter
            $allUnits = $unitModel->where('status_aktif', 1)->findAll();
            
            // Build query for approved waste only
            $builder = $wasteModel
                ->select('waste.*, unit.nama_unit, users.nama_lengkap as user_name')
                ->join('unit', 'unit.id = waste.unit_id', 'left')
                ->join('users', 'users.id = waste.user_id', 'left')
                ->where('waste.status', 'disetujui');
            
            // Apply filters
            if (!empty($filters['unit_id'])) {
                $builder->where('waste.unit_id', $filters['unit_id']);
            }
            
            if (!empty($filters['jenis_sampah'])) {
                $builder->where('waste.jenis_sampah', $filters['jenis_sampah']);
            }
            
            if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                $builder->where('waste.tanggal >=', $filters['start_date']);
                $builder->where('waste.tanggal <=', $filters['end_date']);
            }
            
            $approvedWaste = $builder->orderBy('waste.tanggal', 'DESC')->findAll();
            
            // Calculate summary statistics
            $totalBerat = 0;
            $totalNilai = 0;
            $byJenis = [];
            $byUnit = [];
            $unitSet = [];
            $trendBulanan = [];
            
            foreach ($approvedWaste as $waste) {
                $totalBerat += $waste['berat'];
                $totalNilai += $waste['nilai_ekonomis'] ?? 0;
                
                // Track unique units
                if ($waste['nama_unit']) {
                    $unitSet[$waste['nama_unit']] = true;
                }
                
                // Trend bulanan
                $bulan = date('M', strtotime($waste['tanggal']));
                if (!isset($trendBulanan[$bulan])) {
                    $trendBulanan[$bulan] = 0;
                }
                $trendBulanan[$bulan]++;
                
                // Group by jenis
                if (!isset($byJenis[$waste['jenis_sampah']])) {
                    $byJenis[$waste['jenis_sampah']] = [
                        'total_data' => 0,
                        'total_berat' => 0,
                        'total_nilai' => 0,
                        'total_unit' => 0,
                        'units' => []
                    ];
                }
                $byJenis[$waste['jenis_sampah']]['total_data']++;
                $byJenis[$waste['jenis_sampah']]['total_berat'] += $waste['berat'];
                $byJenis[$waste['jenis_sampah']]['total_nilai'] += $waste['nilai_ekonomis'] ?? 0;
                if ($waste['nama_unit'] && !isset($byJenis[$waste['jenis_sampah']]['units'][$waste['nama_unit']])) {
                    $byJenis[$waste['jenis_sampah']]['units'][$waste['nama_unit']] = true;
                    $byJenis[$waste['jenis_sampah']]['total_unit']++;
                }
                
                // Group by unit
                if (!isset($byUnit[$waste['nama_unit']])) {
                    $byUnit[$waste['nama_unit']] = [
                        'nama_unit' => $waste['nama_unit'],
                        'total_data' => 0,
                        'total_berat' => 0,
                        'jenis_count' => [],
                        'last_update' => null
                    ];
                }
                $byUnit[$waste['nama_unit']]['total_data']++;
                $byUnit[$waste['nama_unit']]['total_berat'] += $waste['berat'];
                
                // Track jenis for finding most common
                if (!isset($byUnit[$waste['nama_unit']]['jenis_count'][$waste['jenis_sampah']])) {
                    $byUnit[$waste['nama_unit']]['jenis_count'][$waste['jenis_sampah']] = 0;
                }
                $byUnit[$waste['nama_unit']]['jenis_count'][$waste['jenis_sampah']]++;
                
                // Track last update
                if (!$byUnit[$waste['nama_unit']]['last_update'] || 
                    strtotime($waste['tanggal']) > strtotime($byUnit[$waste['nama_unit']]['last_update'])) {
                    $byUnit[$waste['nama_unit']]['last_update'] = $waste['tanggal'];
                }
            }
            
            // Find most common jenis for each unit
            foreach ($byUnit as $unitName => &$unitData) {
                if (!empty($unitData['jenis_count'])) {
                    arsort($unitData['jenis_count']);
                    $unitData['jenis_terbanyak'] = array_key_first($unitData['jenis_count']);
                } else {
                    $unitData['jenis_terbanyak'] = '-';
                }
            }
            
            // Sort trend bulanan by month order
            $monthOrder = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $sortedTrend = [];
            foreach ($monthOrder as $month) {
                $sortedTrend[$month] = $trendBulanan[$month] ?? 0;
            }
            
            $viewData = [
                'title' => 'Laporan Waste Management',
                'approvedWaste' => $approvedWaste,
                'wasteData' => $approvedWaste, // Alias
                'summary' => [
                    'total_data' => count($approvedWaste),
                    'total_disetujui' => count($approvedWaste),
                    'total_berat' => $totalBerat,
                    'total_nilai' => $totalNilai,
                    'total_unit' => count($unitSet)
                ],
                'byJenis' => $byJenis,
                'rekapJenis' => $byJenis, // Alias for byJenis
                'byUnit' => array_values($byUnit),
                'rekapUnit' => array_values($byUnit), // Alias for byUnit
                'trendBulanan' => $sortedTrend,
                'allUnits' => $allUnits,
                'allJenis' => array_keys($byJenis),
                'filters' => $filters,
                'jenisOptions' => array_keys($byJenis)
            ];

            return view('admin_pusat/laporan_waste', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Admin Laporan Waste Error: ' . $e->getMessage());
            
            // Get units even in error case
            try {
                $unitModel = new \App\Models\UnitModel();
                $allUnits = $unitModel->where('status_aktif', 1)->findAll();
            } catch (\Exception $unitError) {
                $allUnits = [];
            }
            
            return view('admin_pusat/laporan_waste', [
                'title' => 'Laporan Waste Management',
                'approvedWaste' => [],
                'wasteData' => [],
                'summary' => [
                    'total_data' => 0,
                    'total_disetujui' => 0,
                    'total_berat' => 0,
                    'total_nilai' => 0,
                    'total_unit' => 0
                ],
                'byJenis' => [],
                'rekapJenis' => [],
                'byUnit' => [],
                'rekapUnit' => [],
                'trendBulanan' => ['Jan' => 0, 'Feb' => 0, 'Mar' => 0, 'Apr' => 0, 'May' => 0, 'Jun' => 0, 
                                   'Jul' => 0, 'Aug' => 0, 'Sep' => 0, 'Oct' => 0, 'Nov' => 0, 'Dec' => 0],
                'allUnits' => $allUnits,
                'allJenis' => [],
                'filters' => [
                    'start_date' => date('Y-m-01'),
                    'end_date' => date('Y-m-d'),
                    'tahun' => date('Y'),
                    'bulan' => date('m'),
                    'unit_id' => '',
                    'unit' => '',
                    'jenis_sampah' => '',
                    'jenis' => ''
                ],
                'jenisOptions' => [],
                'error' => 'Terjadi kesalahan saat memuat laporan waste: ' . $e->getMessage()
            ]);
        }
    }

    public function export()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $result = $this->laporanWasteService->exportLaporanWaste();
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Admin Laporan Waste Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export laporan');
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