<?php

namespace App\Services\Admin;

use App\Models\WasteModel;
use App\Models\UserModel;
use App\Models\HargaSampahModel;

class DashboardService
{
    protected $wasteModel;
    protected $userModel;
    protected $hargaModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->userModel = new UserModel();
        $this->hargaModel = new HargaSampahModel();
    }

    public function getDashboardData(): array
    {
        try {
            return [
                'stats' => $this->getStats(),
                'recentSubmissions' => $this->getRecentSubmissions(),
                'recentPriceChanges' => $this->getRecentPriceChanges(),
                'wasteByType' => $this->getWasteByType()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Dashboard Service Error: ' . $e->getMessage());
            
            return [
                'stats' => $this->getDefaultStats(),
                'recentSubmissions' => [],
                'recentPriceChanges' => [],
                'wasteByType' => []
            ];
        }
    }

    private function getStats(): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        return [
            'total_users' => $this->userModel->where('status_aktif', 1)->countAllResults(),
            'menunggu_review' => $this->wasteModel->where('status', 'pending')->countAllResults(),
            'disetujui' => $this->wasteModel->where('status', 'approved')->countAllResults(),
            'perlu_revisi' => $this->wasteModel->where('status', 'rejected')->countAllResults(),
            'total_berat' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('status', 'approved')
                ->get()
                ->getRow()
                ->berat_kg ?? 0,
            'total_nilai' => $this->calculateTotalValue()
        ];
    }

    private function getRecentSubmissions(): array
    {
        return $this->wasteModel
            ->where('waste_management.status', 'pending')
            ->orderBy('waste_management.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getRecentPriceChanges(): array
    {
        $logModel = new \App\Models\HargaLogModel();
        
        return $logModel
            ->select('harga_log.*, master_harga_sampah.jenis_sampah, users.nama_lengkap as admin_nama')
            ->join('master_harga_sampah', 'master_harga_sampah.id = harga_log.harga_id', 'left')
            ->join('users', 'users.id = harga_log.admin_id', 'left')
            ->orderBy('harga_log.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getWasteByType(): array
    {
        return $this->wasteModel
            ->select('jenis_sampah, COUNT(*) as total_records, SUM(berat_kg) as total_berat, SUM(nilai_rupiah) as total_nilai')
            ->groupBy('jenis_sampah')
            ->orderBy('total_berat', 'DESC')
            ->findAll();
    }

    private function calculateTotalValue(): float
    {
        $result = $this->wasteModel
            ->selectSum('nilai_rupiah', 'total_nilai')
            ->where('status', 'approved')
            ->get()
            ->getRow();

        return $result->total_nilai ?? 0;
    }

    private function getDefaultStats(): array
    {
        return [
            'total_users' => 0,
            'menunggu_review' => 0,
            'disetujui' => 0,
            'perlu_revisi' => 0,
            'total_berat' => 0,
            'total_nilai' => 0
        ];
    }
}