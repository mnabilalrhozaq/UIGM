<?php

namespace App\Services\Admin;

use App\Models\WasteModel;
use App\Models\UnitModel;

class LaporanWasteService
{
    protected $wasteModel;
    protected $unitModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->unitModel = new UnitModel();
    }

    public function getLaporanData(array $filters): array
    {
        try {
            $db = \Config\Database::connect();
            
            // Get data dari laporan_waste (bukan waste_management)
            $dataDisetujui = $this->getDataByStatus('approved', $filters, $db);
            $dataDitolak = $this->getDataByStatus('rejected', $filters, $db);
            
            // Rekap per jenis sampah
            $rekapJenis = $this->getRekapPerJenis($filters, $db);
            
            // Rekap per unit
            $rekapUnit = $this->getRekapPerUnit($filters, $db);
            
            // Get all units for filter
            $units = $this->unitModel->findAll();
            
            // Summary
            $summary = $this->getSummary($filters, $db);

            return [
                'data_disetujui' => $dataDisetujui,
                'data_ditolak' => $dataDitolak,
                'rekap_jenis' => $rekapJenis,
                'rekap_unit' => $rekapUnit,
                'units' => $units,
                'summary' => $summary
            ];
        } catch (\Exception $e) {
            log_message('error', 'Get Laporan Data Error: ' . $e->getMessage());
            
            return [
                'data_disetujui' => [],
                'data_ditolak' => [],
                'rekap_jenis' => [],
                'rekap_unit' => [],
                'units' => [],
                'summary' => []
            ];
        }
    }

    private function getDataByStatus(string $status, array $filters, $db): array
    {
        // Query dari laporan_waste, bukan waste_management
        $builder = $db->table('laporan_waste')
            ->select('laporan_waste.*, units.nama_unit, users.nama_lengkap as created_by_name, reviewer.nama_lengkap as reviewed_by_name')
            ->join('units', 'units.id = laporan_waste.unit_id', 'left')
            ->join('users', 'users.id = laporan_waste.created_by', 'left')
            ->join('users as reviewer', 'reviewer.id = laporan_waste.reviewed_by', 'left')
            ->where('laporan_waste.status', $status);

        // Apply filters
        if (!empty($filters['start_date'])) {
            $builder->where('DATE(laporan_waste.tanggal_input) >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(laporan_waste.tanggal_input) <=', $filters['end_date']);
        }
        
        if (!empty($filters['unit_id'])) {
            $builder->where('laporan_waste.unit_id', $filters['unit_id']);
        }
        
        if (!empty($filters['jenis_sampah'])) {
            $builder->where('laporan_waste.jenis_sampah', $filters['jenis_sampah']);
        }

        return $builder->orderBy('laporan_waste.reviewed_at', 'DESC')->get()->getResultArray();
    }

    private function getRekapPerJenis(array $filters, $db): array
    {
        $sql = "SELECT 
                    jenis_sampah,
                    COUNT(*) as total_transaksi,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as total_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as total_ditolak,
                    SUM(CASE WHEN status = 'approved' THEN berat_kg ELSE 0 END) as total_berat_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN berat_kg ELSE 0 END) as total_berat_ditolak,
                    SUM(CASE WHEN status = 'approved' THEN nilai_rupiah ELSE 0 END) as total_nilai_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN nilai_rupiah ELSE 0 END) as total_nilai_ditolak
                FROM laporan_waste
                WHERE status IN ('approved', 'rejected')";
        
        $params = [];
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND DATE(tanggal_input) >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND DATE(tanggal_input) <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['unit_id'])) {
            $sql .= " AND unit_id = ?";
            $params[] = $filters['unit_id'];
        }
        
        $sql .= " GROUP BY jenis_sampah ORDER BY total_berat_disetujui DESC";
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }

    private function getRekapPerUnit(array $filters, $db): array
    {
        $sql = "SELECT 
                    u.id as unit_id,
                    u.nama_unit,
                    COUNT(lw.id) as total_transaksi,
                    SUM(CASE WHEN lw.status = 'approved' THEN 1 ELSE 0 END) as total_disetujui,
                    SUM(CASE WHEN lw.status = 'rejected' THEN 1 ELSE 0 END) as total_ditolak,
                    SUM(CASE WHEN lw.status = 'approved' THEN lw.berat_kg ELSE 0 END) as total_berat_disetujui,
                    SUM(CASE WHEN lw.status = 'rejected' THEN lw.berat_kg ELSE 0 END) as total_berat_ditolak,
                    SUM(CASE WHEN lw.status = 'approved' THEN lw.nilai_rupiah ELSE 0 END) as total_nilai_disetujui,
                    SUM(CASE WHEN lw.status = 'rejected' THEN lw.nilai_rupiah ELSE 0 END) as total_nilai_ditolak
                FROM unit u
                LEFT JOIN laporan_waste lw ON lw.unit_id = u.id AND lw.status IN ('approved', 'rejected')";
        
        $params = [];
        $whereConditions = [];
        
        if (!empty($filters['start_date'])) {
            $whereConditions[] = "DATE(lw.tanggal_input) >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $whereConditions[] = "DATE(lw.tanggal_input) <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['unit_id'])) {
            $whereConditions[] = "u.id = ?";
            $params[] = $filters['unit_id'];
        }
        
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= " GROUP BY u.id, u.nama_unit ORDER BY total_berat_disetujui DESC";
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }

    private function getSummary(array $filters, $db): array
    {
        $sql = "SELECT 
                    COUNT(*) as total_transaksi,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as total_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as total_ditolak,
                    SUM(CASE WHEN status = 'approved' THEN berat_kg ELSE 0 END) as total_berat_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN berat_kg ELSE 0 END) as total_berat_ditolak,
                    SUM(CASE WHEN status = 'approved' THEN nilai_rupiah ELSE 0 END) as total_nilai_disetujui,
                    SUM(CASE WHEN status = 'rejected' THEN nilai_rupiah ELSE 0 END) as total_nilai_ditolak
                FROM laporan_waste
                WHERE status IN ('approved', 'rejected')";
        
        $params = [];
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND DATE(tanggal_input) >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND DATE(tanggal_input) <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['unit_id'])) {
            $sql .= " AND unit_id = ?";
            $params[] = $filters['unit_id'];
        }
        
        $query = $db->query($sql, $params);
        $result = $query->getRowArray();
        
        return $result ?: [
            'total_transaksi' => 0,
            'total_disetujui' => 0,
            'total_ditolak' => 0,
            'total_berat_disetujui' => 0,
            'total_berat_ditolak' => 0,
            'total_nilai_disetujui' => 0,
            'total_nilai_ditolak' => 0
        ];
    }

    public function exportLaporan(array $filters): array
    {
        try {
            $data = $this->getLaporanData($filters);
            
            // Create CSV content
            $csvContent = "LAPORAN WASTE - UI GREENMETRIC POLBAN\n";
            $csvContent .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            // Summary
            $csvContent .= "=== RINGKASAN ===\n";
            $csvContent .= "Total Transaksi," . $data['summary']['total_transaksi'] . "\n";
            $csvContent .= "Total Disetujui," . $data['summary']['total_disetujui'] . "\n";
            $csvContent .= "Total Ditolak," . $data['summary']['total_ditolak'] . "\n";
            $csvContent .= "Total Berat Disetujui (kg)," . number_format($data['summary']['total_berat_disetujui'], 2) . "\n";
            $csvContent .= "Total Berat Ditolak (kg)," . number_format($data['summary']['total_berat_ditolak'], 2) . "\n";
            $csvContent .= "Total Nilai Disetujui (Rp)," . number_format($data['summary']['total_nilai_disetujui'], 0) . "\n";
            $csvContent .= "Total Nilai Ditolak (Rp)," . number_format($data['summary']['total_nilai_ditolak'], 0) . "\n\n";
            
            // Rekap per Jenis
            $csvContent .= "=== REKAP PER JENIS SAMPAH ===\n";
            $csvContent .= "Jenis Sampah,Total Transaksi,Disetujui,Ditolak,Berat Disetujui (kg),Berat Ditolak (kg),Nilai Disetujui (Rp),Nilai Ditolak (Rp)\n";
            foreach ($data['rekap_jenis'] as $item) {
                $csvContent .= sprintf(
                    "%s,%d,%d,%d,%s,%s,%s,%s\n",
                    $item['jenis_sampah'],
                    $item['total_transaksi'],
                    $item['total_disetujui'],
                    $item['total_ditolak'],
                    number_format($item['total_berat_disetujui'], 2),
                    number_format($item['total_berat_ditolak'], 2),
                    number_format($item['total_nilai_disetujui'], 0),
                    number_format($item['total_nilai_ditolak'], 0)
                );
            }
            
            $csvContent .= "\n=== REKAP PER UNIT ===\n";
            $csvContent .= "Unit,Total Transaksi,Disetujui,Ditolak,Berat Disetujui (kg),Berat Ditolak (kg),Nilai Disetujui (Rp),Nilai Ditolak (Rp)\n";
            foreach ($data['rekap_unit'] as $item) {
                $csvContent .= sprintf(
                    "%s,%d,%d,%d,%s,%s,%s,%s\n",
                    $item['nama_unit'],
                    $item['total_transaksi'],
                    $item['total_disetujui'],
                    $item['total_ditolak'],
                    number_format($item['total_berat_disetujui'], 2),
                    number_format($item['total_berat_ditolak'], 2),
                    number_format($item['total_nilai_disetujui'], 0),
                    number_format($item['total_nilai_ditolak'], 0)
                );
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
            log_message('error', 'Export Laporan Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat export laporan'];
        }
    }
}
