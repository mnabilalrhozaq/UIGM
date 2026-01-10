<?php

namespace App\Services\User;

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
            $unitId = $user['unit_id'];

            // Validate unit exists
            $unit = $this->unitModel->find($unitId);
            if (!$unit) {
                throw new \Exception('Unit tidak ditemukan');
            }

            return [
                'user' => $user,
                'unit' => $unit,
                'stats' => $this->getWasteStats($unitId),
                'recent_activities' => $this->getRecentActivities($user['id']),
                'feature_data' => $this->getFeatureData()
            ];
        } catch (\Exception $e) {
            log_message('error', 'User Dashboard Service Error: ' . $e->getMessage());
            
            return [
                'user' => session()->get('user'),
                'unit' => null,
                'stats' => $this->getDefaultStats(),
                'recent_activities' => [],
                'feature_data' => []
            ];
        }
    }

    public function getApiStats(): array
    {
        try {
            $user = session()->get('user');
            $unitId = $user['unit_id'];

            return [
                'waste_stats' => $this->getWasteStats($unitId),
                'overall_stats' => $this->getOverallStats($unitId)
            ];
        } catch (\Exception $e) {
            log_message('error', 'User API Stats Error: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function getWasteStats(int $unitId): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total_today' => $this->wasteModel
                ->where('unit_id', $unitId)
                ->where('DATE(created_at)', $today)
                ->countAllResults(),
            
            'total_month' => $this->wasteModel
                ->where('unit_id', $unitId)
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $thisMonth)
                ->countAllResults(),
            
            'approved_count' => $this->wasteModel
                ->where('unit_id', $unitId)
                ->where('status', 'approved')
                ->countAllResults(),
            
            'pending_count' => $this->wasteModel
                ->where('unit_id', $unitId)
                ->where('status', 'pending')
                ->countAllResults(),
            
            'weight_today' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('unit_id', $unitId)
                ->where('DATE(created_at)', $today)
                ->get()
                ->getRow()
                ->berat_kg ?? 0,
            
            'weight_month' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('unit_id', $unitId)
                ->where('DATE_FORMAT(created_at, "%Y-%m")', $thisMonth)
                ->get()
                ->getRow()
                ->berat_kg ?? 0
        ];
    }

    private function getOverallStats(int $unitId): array
    {
        return [
            'total_entries' => $this->wasteModel->where('unit_id', $unitId)->countAllResults(),
            'total_weight' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('unit_id', $unitId)
                ->get()
                ->getRow()
                ->berat_kg ?? 0
        ];
    }

    private function getRecentActivities(int $userId): array
    {
        if (!isFeatureEnabled('dashboard_recent_activity', 'user')) {
            return [];
        }

        try {
            $maxItems = 5; // Default limit
            
            $recentWaste = $this->wasteModel
                ->select('waste_management.*, harga_sampah.kategori')
                ->join('harga_sampah', 'harga_sampah.id = waste_management.kategori_id', 'left')
                ->where('waste_management.created_by', $userId)
                ->orderBy('waste_management.updated_at', 'DESC')
                ->limit($maxItems)
                ->findAll();
            
            $activities = [];
            foreach ($recentWaste as $waste) {
                $activities[] = [
                    'icon' => $this->getStatusIcon($waste['status']),
                    'message' => $this->getActivityMessage($waste),
                    'time' => $this->timeAgo($waste['updated_at'])
                ];
            }
            
            return $activities;
            
        } catch (\Exception $e) {
            log_message('error', 'Error getting recent activities: ' . $e->getMessage());
            return [];
        }
    }

    private function getFeatureData(): array
    {
        $data = [];
        
        // Real-time updates
        if (isFeatureEnabled('real_time_updates', 'user')) {
            $data['real_time_enabled'] = true;
            $data['refresh_interval'] = 30; // seconds
        }
        
        // Export functionality
        if (isFeatureEnabled('export_data', 'user')) {
            $data['export_enabled'] = true;
        }
        
        return $data;
    }

    private function getStatusIcon(string $status): string
    {
        switch ($status) {
            case 'draft':
                return 'edit';
            case 'pending':
                return 'clock';
            case 'approved':
                return 'check-circle';
            case 'rejected':
                return 'x-circle';
            default:
                return 'circle';
        }
    }

    private function getActivityMessage(array $waste): string
    {
        $kategori = $waste['kategori'] ?? 'Sampah';
        $berat = $waste['berat_kg'] ?? 0;
        
        switch ($waste['status']) {
            case 'draft':
                return "Data {$kategori} {$berat}kg disimpan sebagai draft";
            case 'pending':
                return "Data {$kategori} {$berat}kg dikirim untuk review";
            case 'approved':
                return "Data {$kategori} {$berat}kg disetujui";
            case 'rejected':
                return "Data {$kategori} {$berat}kg ditolak";
            default:
                return "Data {$kategori} {$berat}kg diperbarui";
        }
    }

    private function timeAgo(string $datetime): string
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'Baru saja';
        if ($time < 3600) return floor($time/60) . ' menit yang lalu';
        if ($time < 86400) return floor($time/3600) . ' jam yang lalu';
        if ($time < 2592000) return floor($time/86400) . ' hari yang lalu';
        
        return date('d/m/Y', strtotime($datetime));
    }

    private function getDefaultStats(): array
    {
        return [
            'total_today' => 0,
            'total_month' => 0,
            'approved_count' => 0,
            'pending_count' => 0,
            'weight_today' => 0,
            'weight_month' => 0
        ];
    }
}