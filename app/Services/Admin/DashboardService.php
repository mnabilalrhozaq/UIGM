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
            ->select('waste_management.*, harga_sampah.kategori as jenis_sampah, harga_sampah.harga_per_kg, users.nama_lengkap, users.username, (waste_management.berat_kg * harga_sampah.harga_per_kg) as nilai_jual')
            ->join('harga_sampah', 'harga_sampah.id = waste_management.kategori_id', 'left')
            ->join('users', 'users.id = waste_management.created_by', 'left')
            ->where('waste_management.status', 'pending')
            ->orderBy('waste_management.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getRecentPriceChanges(): array
    {
        $logModel = new \App\Models\HargaLogModel();
        
        return $logModel
            ->select('harga_log.*, harga_sampah.kategori as jenis_sampah, users.nama_lengkap as admin_nama')
            ->join('harga_sampah', 'harga_sampah.id = harga_log.harga_id', 'left')
            ->join('users', 'users.id = harga_log.admin_id', 'left')
            ->orderBy('harga_log.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getWasteByType(): array
    {
        return $this->wasteModel
            ->select('harga_sampah.kategori as jenis_sampah, COUNT(*) as total_records, SUM(waste_management.berat_kg) as total_berat, SUM(waste_management.berat_kg * harga_sampah.harga_per_kg) as total_nilai, SUM(CASE WHEN waste_management.status = "approved" THEN 1 ELSE 0 END) as disetujui, SUM(CASE WHEN waste_management.status = "pending" THEN 1 ELSE 0 END) as menunggu_review')
            ->join('harga_sampah', 'harga_sampah.id = waste_management.kategori_id', 'left')
            ->groupBy('waste_management.kategori_id')
            ->orderBy('total_berat', 'DESC')
            ->findAll();
    }

    private function calculateTotalValue(): float
    {
        $result = $this->wasteModel
            ->select('SUM(waste_management.berat_kg * harga_sampah.harga_per_kg) as total_nilai')
            ->join('harga_sampah', 'harga_sampah.id = waste_management.kategori_id', 'left')
            ->where('waste_management.status', 'approved')
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