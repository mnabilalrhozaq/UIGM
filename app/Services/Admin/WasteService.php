<?php

namespace App\Services\Admin;

use App\Models\WasteModel;
use App\Models\HargaSampahModel;
use App\Models\UnitModel;

class WasteService
{
    protected $wasteModel;
    protected $hargaModel;
    protected $unitModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->hargaModel = new HargaSampahModel();
        $this->unitModel = new UnitModel();
    }

    public function getWasteData(): array
    {
        try {
            log_message('info', 'Admin - Starting getWasteData...');
            
            // Debug: cek total data
            $totalData = $this->wasteModel->countAllResults(false);
            log_message('info', 'Admin - Total data in waste_management: ' . $totalData);
            
            // Debug: cek data per status
            $statusCounts = [];
            foreach (['draft', 'dikirim', 'review', 'disetujui', 'perlu_revisi'] as $status) {
                $count = $this->wasteModel->where('status', $status)->countAllResults(false);
                $statusCounts[$status] = $count;
                log_message('info', "Admin - Status '$status': $count records");
            }
            
            return [
                'waste_list' => $this->getWasteList(),
                'summary' => $this->getWasteSummary(),
                'filters' => $this->getFilterOptions(),
                'statistics' => $this->getWasteStatistics()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Admin Waste Service Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            
            return [
                'waste_list' => [],
                'summary' => $this->getDefaultSummary(),
                'filters' => [],
                'statistics' => []
            ];
        }
    }

    public function exportWaste(): array
    {
        try {
            $wasteList = $this->getWasteList();
            
            if (empty($wasteList)) {
                return ['success' => false, 'message' => 'Tidak ada data untuk diekspor'];
            }

            // Create CSV content
            $csvContent = "Data Waste Management - Admin Export\n";
            $csvContent .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $csvContent .= "ID,Tanggal,Unit,Jenis Sampah,Berat (kg),Nilai Rupiah,Status,Gedung\n";
            
            foreach ($wasteList as $waste) {
                $csvContent .= sprintf(
                    "%s,%s,%s,%s,%s,%s,%s,%s\n",
                    $waste['id'],
                    $waste['tanggal'],
                    $waste['nama_unit'] ?? 'N/A',
                    $waste['jenis_sampah'] ?? 'N/A',
                    $waste['berat_kg'],
                    $waste['nilai_rupiah'] ?? 0,
                    $waste['status'],
                    $waste['gedung'] ?? 'N/A'
                );
            }

            // Save to temp file
            $filename = 'admin_waste_export_' . date('Y-m-d_H-i-s') . '.csv';
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
            log_message('error', 'Export Admin Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat export data'];
        }
    }

    public function approveWaste(int $id, array $data): array
    {
        try {
            $waste = $this->wasteModel->find($id);
            
            if (!$waste) {
                return ['success' => false, 'message' => 'Data sampah tidak ditemukan'];
            }

            if (!in_array($waste['status'], ['dikirim', 'review'])) {
                return ['success' => false, 'message' => 'Data sampah tidak dapat disetujui'];
            }

            $updateData = [
                'status' => 'disetujui',
                'catatan_admin' => $data['catatan'] ?? null
            ];

            $result = $this->wasteModel->update($id, $updateData);
            
            if ($result) {
                return ['success' => true, 'message' => 'Data sampah berhasil disetujui'];
            }

            return ['success' => false, 'message' => 'Gagal menyetujui data sampah'];

        } catch (\Exception $e) {
            log_message('error', 'Approve Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function rejectWaste(int $id, array $data): array
    {
        try {
            $waste = $this->wasteModel->find($id);
            
            if (!$waste) {
                return ['success' => false, 'message' => 'Data sampah tidak ditemukan'];
            }

            if (!in_array($waste['status'], ['dikirim', 'review'])) {
                return ['success' => false, 'message' => 'Data sampah tidak dapat ditolak'];
            }

            if (empty($data['catatan'])) {
                return ['success' => false, 'message' => 'Catatan penolakan harus diisi'];
            }

            $updateData = [
                'status' => 'perlu_revisi',
                'catatan_admin' => $data['catatan']
            ];

            $result = $this->wasteModel->update($id, $updateData);
            
            if ($result) {
                return ['success' => true, 'message' => 'Data sampah berhasil ditolak'];
            }

            return ['success' => false, 'message' => 'Gagal menolak data sampah'];

        } catch (\Exception $e) {
            log_message('error', 'Reject Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    private function getWasteList(): array
    {
        try {
            log_message('info', 'Admin - Getting waste list - SIMPLE QUERY...');
            
            // Most simple query possible
            $result = $this->wasteModel->findAll(100);
            
            log_message('info', 'Admin - Simple findAll found ' . count($result) . ' records');
            
            if (!empty($result)) {
                log_message('info', 'Admin - First record: ' . json_encode($result[0]));
            } else {
                log_message('warning', 'Admin - No records found in waste_management table');
            }
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'Error in getWasteList: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return [];
        }
    }

    private function getWasteSummary(): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');
        $thisYear = date('Y');

        return [
            'total_today' => $this->wasteModel
                ->where('tanggal', $today)
                ->countAllResults(),
            
            'total_month' => $this->wasteModel
                ->where('DATE_FORMAT(tanggal, "%Y-%m")', $thisMonth)
                ->countAllResults(),
            
            'total_year' => $this->wasteModel
                ->where('YEAR(tanggal)', $thisYear)
                ->countAllResults(),
            
            'weight_today' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('tanggal', $today)
                ->get()
                ->getRow()
                ->berat_kg ?? 0,
            
            'weight_month' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('DATE_FORMAT(tanggal, "%Y-%m")', $thisMonth)
                ->get()
                ->getRow()
                ->berat_kg ?? 0,
            
            'weight_year' => $this->wasteModel
                ->selectSum('berat_kg')
                ->where('YEAR(tanggal)', $thisYear)
                ->get()
                ->getRow()
                ->berat_kg ?? 0,

            'dikirim_count' => $this->wasteModel->where('status', 'dikirim')->countAllResults(),
            'disetujui_count' => $this->wasteModel->where('status', 'disetujui')->countAllResults(),
            'perlu_revisi_count' => $this->wasteModel->where('status', 'perlu_revisi')->countAllResults()
        ];
    }

    private function getFilterOptions(): array
    {
        return [
            'categories' => $this->hargaModel->findAll(),
            'units' => $this->unitModel->where('status_aktif', 1)->findAll(),
            'statuses' => [
                'pending' => 'Pending',
                'approved' => 'Disetujui',
                'rejected' => 'Ditolak',
                'draft' => 'Draft'
            ]
        ];
    }

    private function getWasteStatistics(): array
    {
        // Get waste by category
        $categoryStats = $this->wasteModel
            ->select('harga_sampah.kategori, COUNT(waste.id) as count, SUM(waste.berat_kg) as total_weight')
            ->join('harga_sampah', 'harga_sampah.id = waste.kategori_id', 'left')
            ->groupBy('waste.kategori_id')
            ->orderBy('total_weight', 'DESC')
            ->findAll();

        // Get waste by unit
        $unitStats = $this->wasteModel
            ->select('units.nama_unit, COUNT(waste.id) as count, SUM(waste.berat_kg) as total_weight')
            ->join('units', 'units.id = waste.unit_id', 'left')
            ->groupBy('waste.unit_id')
            ->orderBy('total_weight', 'DESC')
            ->limit(10)
            ->findAll();

        return [
            'by_category' => $categoryStats,
            'by_unit' => $unitStats
        ];
    }

    private function getDefaultSummary(): array
    {
        return [
            'total_today' => 0,
            'total_month' => 0,
            'total_year' => 0,
            'weight_today' => 0,
            'weight_month' => 0,
            'weight_year' => 0,
            'pending_count' => 0,
            'approved_count' => 0,
            'rejected_count' => 0
        ];
    }
}