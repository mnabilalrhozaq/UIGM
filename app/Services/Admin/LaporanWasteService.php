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

    public function getLaporanData(array $filters, array $pages, int $perPage = 10): array
    {
        try {
            $db = \Config\Database::connect();
            
            // Get data dari laporan_waste dengan pagination per section
            $dataDisetujui = $this->getDataByStatus('approved', $filters, $db, $pages['disetujui'], $perPage);
            $dataDitolak = $this->getDataByStatus('rejected', $filters, $db, $pages['ditolak'], $perPage);
            
            // Rekap per jenis sampah dengan pagination
            $rekapJenis = $this->getRekapPerJenis($filters, $db, $pages['rekap_jenis'], $perPage);
            
            // Rekap per unit dengan pagination
            $rekapUnit = $this->getRekapPerUnit($filters, $db, $pages['rekap_unit'], $perPage);
            
            // Get all units for filter
            $units = $this->unitModel->findAll();
            
            // Summary
            $summary = $this->getSummary($filters, $db);
            
            // Count totals for pagination
            $totalDisetujui = $this->countDataByStatus('approved', $filters, $db);
            $totalDitolak = $this->countDataByStatus('rejected', $filters, $db);
            $totalRekapJenis = $this->countRekapPerJenis($filters, $db);
            $totalRekapUnit = $this->countRekapPerUnit($filters, $db);

            return [
                'data_disetujui' => $dataDisetujui,
                'data_ditolak' => $dataDitolak,
                'rekap_jenis' => $rekapJenis,
                'rekap_unit' => $rekapUnit,
                'units' => $units,
                'summary' => $summary,
                'pagination' => [
                    'pages' => $pages,
                    'per_page' => $perPage,
                    'total_disetujui' => $totalDisetujui,
                    'total_ditolak' => $totalDitolak,
                    'total_rekap_jenis' => $totalRekapJenis,
                    'total_rekap_unit' => $totalRekapUnit,
                    'total_pages_disetujui' => ceil($totalDisetujui / $perPage),
                    'total_pages_ditolak' => ceil($totalDitolak / $perPage),
                    'total_pages_rekap_jenis' => ceil($totalRekapJenis / $perPage),
                    'total_pages_rekap_unit' => ceil($totalRekapUnit / $perPage)
                ]
            ];
        } catch (\Exception $e) {
            log_message('error', 'Get Laporan Data Error: ' . $e->getMessage());
            
            return [
                'data_disetujui' => [],
                'data_ditolak' => [],
                'rekap_jenis' => [],
                'rekap_unit' => [],
                'units' => [],
                'summary' => [],
                'pagination' => [
                    'pages' => ['disetujui' => 1, 'ditolak' => 1, 'rekap_jenis' => 1, 'rekap_unit' => 1],
                    'per_page' => 10,
                    'total_disetujui' => 0,
                    'total_ditolak' => 0,
                    'total_rekap_jenis' => 0,
                    'total_rekap_unit' => 0,
                    'total_pages_disetujui' => 0,
                    'total_pages_ditolak' => 0,
                    'total_pages_rekap_jenis' => 0,
                    'total_pages_rekap_unit' => 0
                ]
            ];
        }
    }

    private function getDataByStatus(string $status, array $filters, $db, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
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

        return $builder->orderBy('laporan_waste.reviewed_at', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();
    }
    
    private function countDataByStatus(string $status, array $filters, $db): int
    {
        $builder = $db->table('laporan_waste')
            ->where('status', $status);

        if (!empty($filters['start_date'])) {
            $builder->where('DATE(tanggal_input) >=', $filters['start_date']);
        }
        
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(tanggal_input) <=', $filters['end_date']);
        }
        
        if (!empty($filters['unit_id'])) {
            $builder->where('unit_id', $filters['unit_id']);
        }
        
        if (!empty($filters['jenis_sampah'])) {
            $builder->where('jenis_sampah', $filters['jenis_sampah']);
        }

        return $builder->countAllResults();
    }

    private function getRekapPerJenis(array $filters, $db, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
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
        
        $sql .= " GROUP BY jenis_sampah ORDER BY total_berat_disetujui DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }
    
    private function countRekapPerJenis(array $filters, $db): int
    {
        $sql = "SELECT COUNT(DISTINCT jenis_sampah) as total
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
        $result = $query->getRow();
        return $result ? (int)$result->total : 0;
    }

    private function getRekapPerUnit(array $filters, $db, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        
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
        
        $sql .= " GROUP BY u.id, u.nama_unit ORDER BY total_berat_disetujui DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        $query = $db->query($sql, $params);
        return $query->getResultArray();
    }
    
    private function countRekapPerUnit(array $filters, $db): int
    {
        $sql = "SELECT COUNT(DISTINCT u.id) as total
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
        
        $query = $db->query($sql, $params);
        $result = $query->getRow();
        return $result ? (int)$result->total : 0;
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

    public function exportPdf(array $filters): array
    {
        try {
            // Untuk export PDF, ambil semua data tanpa pagination
            // Set pages ke 1 dan perPage ke nilai besar untuk ambil semua data
            $pages = [
                'disetujui' => 1,
                'ditolak' => 1,
                'jenis' => 1,
                'unit' => 1
            ];
            $perPage = 10000; // Ambil semua data
            
            $data = $this->getLaporanData($filters, $pages, $perPage);
            
            // Load view untuk PDF
            $html = view('admin_pusat/laporan_waste_pdf_new', [
                'data' => $data,
                'filters' => $filters,
                'generated_at' => date('d/m/Y H:i:s')
            ]);

            // Generate PDF menggunakan Dompdf
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new \Dompdf\Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            // Save to temp file
            $filename = 'laporan_waste_' . date('Y-m-d_H-i-s') . '.pdf';
            $filePath = WRITEPATH . 'uploads/' . $filename;
            
            if (!is_dir(WRITEPATH . 'uploads/')) {
                mkdir(WRITEPATH . 'uploads/', 0755, true);
            }
            
            file_put_contents($filePath, $dompdf->output());

            return [
                'success' => true,
                'file_path' => $filePath,
                'filename' => $filename
            ];

        } catch (\Exception $e) {
            log_message('error', 'Export PDF Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat export PDF: ' . $e->getMessage()];
        }
    }

    public function exportCsv(array $filters): array
    {
        // Sama dengan exportLaporan
        return $this->exportLaporan($filters);
    }
}
