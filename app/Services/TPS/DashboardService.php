<?php

namespace App\Services\TPS;

use App\Models\WasteModel;
use App\Models\UnitModel;

class DashboardService
{
    protected $wasteModel;
    protected $unitModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->unitModel = new UnitModel();
    }

    public function getDashboardData(): array
    {
        try {
            $user = session()->get('user');
            
            // Validate user session
            if (!$user || !isset($user['unit_id'])) {
                throw new \Exception('Invalid user session');
            }
            
            $tpsId = $user['unit_id'];

            // Get TPS info
            $tpsInfo = $this->unitModel->find($tpsId);
            if (!$tpsInfo) {
                throw new \Exception('TPS tidak ditemukan');
            }

            return [
                'user' => $user,
                'tps_info' => $tpsInfo,
                'stats' => $this->getStats($tpsId),
                'recent_waste' => $this->getRecentWaste($tpsId),
                'monthly_summary' => $this->getMonthlySummary($tpsId)
            ];
        } catch (\Exception $e) {
            log_message('error', 'TPS Dashboard Service Error: ' . $e->getMessage());
            
            $user = session()->get('user');
            
            return [
                'user' => $user,
                'tps_info' => [
                    'nama_unit' => $user['nama_unit'] ?? 'TPS',
                    'id' => $user['unit_id'] ?? 0
                ],
                'stats' => $this->getDefaultStats(),
                'recent_waste' => [],
                'monthly_summary' => []
            ];
        }
    }

    private function getStats(int $tpsId): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        try {
            return [
                'total_waste_today' => $this->wasteModel
                    ->where('tps_id', $tpsId)
                    ->where('DATE(created_at)', $today)
                    ->countAllResults(),
                
                'total_waste_month' => $this->wasteModel
                    ->where('tps_id', $tpsId)
                    ->where('DATE_FORMAT(created_at, "%Y-%m")', $thisMonth)
                    ->countAllResults(),
                
                'total_weight_today' => $this->wasteModel
                    ->selectSum('berat_kg')
                    ->where('tps_id', $tpsId)
                    ->where('DATE(created_at)', $today)
                    ->get()
                    ->getRow()
                    ->berat_kg ?? 0,
                
                'total_weight_month' => $this->wasteModel
                    ->selectSum('berat_kg')
                    ->where('tps_id', $tpsId)
                    ->where('DATE_FORMAT(created_at, "%Y-%m")', $thisMonth)
                    ->get()
                    ->getRow()
                    ->berat_kg ?? 0
            ];
        } catch (\Exception $e) {
            log_message('error', 'Error getting stats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getRecentWaste(int $tpsId): array
    {
        try {
            return $this->wasteModel
                ->select('waste_management.*, master_harga_sampah.jenis_sampah as kategori, master_harga_sampah.harga_per_satuan as harga_per_kg')
                ->join('master_harga_sampah', 'master_harga_sampah.id = waste_management.kategori_id', 'left')
                ->where('waste_management.tps_id', $tpsId)
                ->orderBy('waste_management.created_at', 'DESC')
                ->limit(10)
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting recent waste: ' . $e->getMessage());
            // Fallback without join
            return $this->wasteModel
                ->where('tps_id', $tpsId)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->findAll();
        }
    }

    private function getMonthlySummary(int $tpsId): array
    {
        $currentYear = date('Y');
        
        try {
            return $this->wasteModel
                ->select('MONTH(created_at) as month, COUNT(*) as count, SUM(berat_kg) as total_weight')
                ->where('tps_id', $tpsId)
                ->where('YEAR(created_at)', $currentYear)
                ->groupBy('MONTH(created_at)')
                ->orderBy('MONTH(created_at)', 'ASC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting monthly summary: ' . $e->getMessage());
            return [];
        }
    }

    private function getDefaultStats(): array
    {
        return [
            'total_waste_today' => 0,
            'total_waste_month' => 0,
            'total_weight_today' => 0,
            'total_weight_month' => 0
        ];
    }
}