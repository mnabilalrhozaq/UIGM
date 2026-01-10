<?php

namespace App\Services\Admin;

use App\Models\WasteModel;
use App\Models\UnitModel;
use App\Models\HargaSampahModel;

class LaporanWasteService
{
    protected $wasteModel;
    protected $unitModel;
    protected $hargaModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->unitModel = new UnitModel();
        $this->hargaModel = new HargaSampahModel();
    }

    public function getLaporanWasteData(): array
    {
        try {
            return [
                'waste_summary' => $this->getWasteSummary(),
                'monthly_data' => $this->getMonthlyWasteData(),
                'category_data' => $this->getCategoryWasteData(),
                'tps_data' => $this->getTpsWasteData()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Laporan Waste Service Error: ' . $e->getMessage());
            
            return [
                'waste_summary' => $this->getDefaultSummary(),
                'monthly_data' => [],
                'category_data' => [],
                'tps_data' => []
            ];
        }
    }

    public function exportLaporanWaste(): array
    {
        try {
            $data = $this->getLaporanWasteData();
            
            // Create CSV content
            $csvContent = "Laporan Waste Management\n";
            $csvContent .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            // Summary
            $csvContent .= "RINGKASAN\n";
            $csvContent .= "Total Entries," . $data['waste_summary']['total_entries'] . "\n";
            $csvContent .= "Total Weight (kg)," . $data['waste_summary']['total_weight'] . "\n";
            $csvContent .= "Approved," . $data['waste_summary']['approved'] . "\n";
            $csvContent .= "Pending," . $data['waste_summary']['pending'] . "\n";
            $csvContent .= "Rejected," . $data['waste_summary']['rejected'] . "\n\n";
            
            // Monthly data
            $csvContent .= "DATA BULANAN\n";
            $csvContent .= "Bulan,Jumlah Entry,Total Berat (kg)\n";
            foreach ($data['monthly_data'] as $month) {
                $csvContent .= $month['month'] . "," . $month['count'] . "," . $month['total_weight'] . "\n";
            }
            
            // Save to temp file
            $filename = 'laporan_waste_' . date('Y-m-d_H-i-s') . '.csv';
            $filePath = WRITEPATH . 'uploads/' . $filename;
            
            if (!is_dir(WRITEPATH . 'uploads/')) {
                mkdir(WRITEPATH . 'uploads/', 0755, true);
            }
            
            file_put_contents($filePath, $csvContent);

            return [
                'success' => true,
                'file_path' => $filePath,
                'filename' => $filename
            ];

        } catch (\Exception $e) {
            log_message('error', 'Export Laporan Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat export laporan'];
        }
    }

    private function getWasteSummary(): array
    {
        $currentYear = date('Y');
        
        return [
            'total_entries' => $this->wasteModel->countAllResults(),
            'total_weight' => $this->wasteModel->selectSum('berat_kg')->get()->getRow()->berat_kg ?? 0,
            'approved' => $this->wasteModel->where('status', 'approved')->countAllResults(),
            'pending' => $this->wasteModel->where('status', 'pending')->countAllResults(),
            'rejected' => $this->wasteModel->where('status', 'rejected')->countAllResults(),
            'this_year' => $this->wasteModel->where('YEAR(created_at)', $currentYear)->countAllResults()
        ];
    }

    private function getMonthlyWasteData(): array
    {
        $currentYear = date('Y');
        
        return $this->wasteModel
            ->select('MONTH(created_at) as month, MONTHNAME(created_at) as month_name, COUNT(*) as count, SUM(berat_kg) as total_weight')
            ->where('YEAR(created_at)', $currentYear)
            ->groupBy('MONTH(created_at)')
            ->orderBy('MONTH(created_at)', 'ASC')
            ->findAll();
    }

    private function getCategoryWasteData(): array
    {
        return $this->wasteModel
            ->select('harga_sampah.kategori, COUNT(waste.id) as count, SUM(waste.berat_kg) as total_weight')
            ->join('harga_sampah', 'harga_sampah.id = waste.kategori_id', 'left')
            ->groupBy('waste.kategori_id')
            ->orderBy('total_weight', 'DESC')
            ->findAll();
    }

    private function getTpsWasteData(): array
    {
        return $this->wasteModel
            ->select('units.nama_unit, COUNT(waste.id) as count, SUM(waste.berat_kg) as total_weight')
            ->join('units', 'units.id = waste.tps_id', 'left')
            ->where('units.jenis_unit', 'TPS')
            ->groupBy('waste.tps_id')
            ->orderBy('total_weight', 'DESC')
            ->findAll();
    }

    private function getDefaultSummary(): array
    {
        return [
            'total_entries' => 0,
            'total_weight' => 0,
            'approved' => 0,
            'pending' => 0,
            'rejected' => 0,
            'this_year' => 0
        ];
    }
}