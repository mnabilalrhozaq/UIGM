<?php

namespace App\Services\User;

use App\Models\WasteModel;
use App\Models\UnitModel;
use App\Models\HargaSampahModel;

class WasteService
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

    public function getWasteData(): array
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
                'waste_list' => $this->getWasteList($unitId),
                'categories' => $this->getCategories(),
                'stats' => $this->getWasteStats($unitId)
            ];
        } catch (\Exception $e) {
            log_message('error', 'User Waste Service Error: ' . $e->getMessage());
            
            return [
                'user' => session()->get('user'),
                'unit' => null,
                'waste_list' => [],
                'categories' => [],
                'stats' => $this->getDefaultStats()
            ];
        }
    }

    public function saveWaste(array $data): array
    {
        try {
            log_message('info', 'User Save Waste - Data received: ' . json_encode($data));
            
            $validation = $this->validateWasteData($data);
            if (!$validation['valid']) {
                log_message('error', 'User Save Waste - Validation failed: ' . $validation['message']);
                return ['success' => false, 'message' => $validation['message']];
            }

            $user = session()->get('user');
            
            // Get category info
            $category = $this->hargaModel->find($data['kategori_id']);
            
            if (!$category) {
                return ['success' => false, 'message' => 'Kategori sampah tidak ditemukan'];
            }
            
            // Determine status based on action
            $status = 'draft';
            if (isset($data['status_action']) && $data['status_action'] === 'kirim') {
                $status = 'dikirim';
            }
            
            $wasteData = [
                'unit_id' => $user['unit_id'],
                'berat_kg' => $data['berat_kg'],
                'tanggal' => date('Y-m-d'),
                'jenis_sampah' => $category['jenis_sampah'],
                'satuan' => 'kg',
                'jumlah' => $data['berat_kg'],
                'gedung' => 'User Unit',
                'kategori_sampah' => $category['dapat_dijual'] ? 'bisa_dijual' : 'tidak_dijual',
                'status' => $status
            ];
            
            // Add nilai_rupiah if can be sold
            if ($category['dapat_dijual']) {
                $wasteData['nilai_rupiah'] = $data['berat_kg'] * $category['harga_per_satuan'];
            }

            log_message('info', 'User Save Waste - Prepared data: ' . json_encode($wasteData));

            $result = $this->wasteModel->insert($wasteData);
            
            if ($result) {
                log_message('info', 'User Save Waste - Success, ID: ' . $result);
                $message = $status === 'dikirim' ? 'Data sampah berhasil disimpan dan dikirim' : 'Data sampah berhasil disimpan sebagai draft';
                return ['success' => true, 'message' => $message];
            }

            log_message('error', 'User Save Waste - Insert failed');
            return ['success' => false, 'message' => 'Gagal menyimpan data sampah'];

        } catch (\Exception $e) {
            log_message('error', 'Save User Waste Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()];
        }
    }

    public function updateWaste(int $id, array $data): array
    {
        try {
            log_message('info', 'User Update Waste - Data received: ' . json_encode($data));
            
            $validation = $this->validateWasteData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            $user = session()->get('user');
            
            // Check if waste belongs to this user's unit
            $waste = $this->wasteModel->find($id);
            if (!$waste || $waste['unit_id'] != $user['unit_id']) {
                return ['success' => false, 'message' => 'Data sampah tidak ditemukan atau bukan milik unit Anda'];
            }

            // Check if waste can be edited
            if (!in_array($waste['status'], ['draft', 'perlu_revisi'])) {
                return ['success' => false, 'message' => 'Data yang sudah disubmit tidak dapat diedit'];
            }

            // Get category info
            $category = $this->hargaModel->find($data['kategori_id']);
            
            if (!$category) {
                return ['success' => false, 'message' => 'Kategori sampah tidak ditemukan'];
            }
            
            // Determine status based on action
            $status = 'draft';
            if (isset($data['status_action']) && $data['status_action'] === 'kirim') {
                $status = 'dikirim';
            }
            
            $wasteData = [
                'berat_kg' => $data['berat_kg'],
                'jenis_sampah' => $category['jenis_sampah'],
                'jumlah' => $data['berat_kg'],
                'kategori_sampah' => $category['dapat_dijual'] ? 'bisa_dijual' : 'tidak_dijual',
                'status' => $status
            ];
            
            // Update nilai_rupiah if can be sold
            if ($category['dapat_dijual']) {
                $wasteData['nilai_rupiah'] = $data['berat_kg'] * $category['harga_per_satuan'];
            } else {
                $wasteData['nilai_rupiah'] = null;
            }

            $result = $this->wasteModel->update($id, $wasteData);
            
            if ($result) {
                $message = $status === 'dikirim' ? 'Data sampah berhasil diupdate dan dikirim' : 'Data sampah berhasil diupdate sebagai draft';
                return ['success' => true, 'message' => $message];
            }

            return ['success' => false, 'message' => 'Gagal mengupdate data sampah'];

        } catch (\Exception $e) {
            log_message('error', 'Update User Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function deleteWaste(int $id): array
    {
        try {
            $user = session()->get('user');
            
            // Check if waste belongs to this user's unit
            $waste = $this->wasteModel->find($id);
            if (!$waste || $waste['unit_id'] != $user['unit_id']) {
                return ['success' => false, 'message' => 'Data sampah tidak ditemukan atau bukan milik unit Anda'];
            }

            // Check if waste can be deleted
            if (!in_array($waste['status'], ['draft', 'perlu_revisi'])) {
                return ['success' => false, 'message' => 'Data yang sudah disubmit tidak dapat dihapus'];
            }

            $result = $this->wasteModel->delete($id);
            
            if ($result) {
                return ['success' => true, 'message' => 'Data sampah berhasil dihapus'];
            }

            return ['success' => false, 'message' => 'Gagal menghapus data sampah'];

        } catch (\Exception $e) {
            log_message('error', 'Delete User Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function exportWaste(): array
    {
        try {
            $user = session()->get('user');
            $unitId = $user['unit_id'];

            $wasteList = $this->getWasteList($unitId);
            
            if (empty($wasteList)) {
                return ['success' => false, 'message' => 'Tidak ada data untuk diekspor'];
            }

            // Create CSV content
            $csvContent = "Tanggal,Kategori,Berat (kg),Status,Keterangan\n";
            
            foreach ($wasteList as $waste) {
                $csvContent .= sprintf(
                    "%s,%s,%s,%s,%s\n",
                    $waste['created_at'],
                    $waste['kategori'] ?? 'N/A',
                    $waste['berat_kg'],
                    $waste['status'],
                    str_replace(['"', ',', "\n"], ['""', ';', ' '], $waste['keterangan'] ?? '')
                );
            }

            // Save to temp file
            $filename = 'waste_export_' . $user['unit_id'] . '_' . date('Y-m-d_H-i-s') . '.csv';
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
            log_message('error', 'Export User Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan saat export data'];
        }
    }

    private function getWasteList(int $unitId): array
    {
        try {
            return $this->wasteModel
                ->where('unit_id', $unitId)
                ->orderBy('created_at', 'DESC')
                ->findAll();
        } catch (\Exception $e) {
            log_message('error', 'Error getting waste list: ' . $e->getMessage());
            return [];
        }
    }

    private function getCategories(): array
    {
        try {
            // Use direct database query instead of model
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM master_harga_sampah WHERE status_aktif = 1 ORDER BY jenis_sampah ASC");
            $categories = $query->getResultArray();
            
            log_message('info', 'User Categories found: ' . count($categories));
            
            return $categories;
        } catch (\Exception $e) {
            log_message('error', 'Error getting categories: ' . $e->getMessage());
            return [];
        }
    }

    private function getWasteStats(int $unitId): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m');

        try {
            return [
                'total_entries' => $this->wasteModel->where('unit_id', $unitId)->countAllResults(),
                'pending_count' => $this->wasteModel->where('unit_id', $unitId)->where('status', 'pending')->countAllResults(),
                'approved_count' => $this->wasteModel->where('unit_id', $unitId)->where('status', 'approved')->countAllResults(),
                'rejected_count' => $this->wasteModel->where('unit_id', $unitId)->where('status', 'rejected')->countAllResults(),
                'total_weight' => $this->wasteModel
                    ->selectSum('berat_kg')
                    ->where('unit_id', $unitId)
                    ->get()
                    ->getRow()
                    ->berat_kg ?? 0,
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
        } catch (\Exception $e) {
            log_message('error', 'Error getting waste stats: ' . $e->getMessage());
            return $this->getDefaultStats();
        }
    }

    private function validateWasteData(array $data): array
    {
        if (empty($data['kategori_id'])) {
            return ['valid' => false, 'message' => 'Kategori sampah harus dipilih'];
        }

        if (empty($data['berat_kg']) || !is_numeric($data['berat_kg'])) {
            return ['valid' => false, 'message' => 'Berat sampah harus berupa angka'];
        }

        if ($data['berat_kg'] <= 0) {
            return ['valid' => false, 'message' => 'Berat sampah harus lebih dari 0'];
        }

        // Check if category exists and is active
        $category = $this->hargaModel->find($data['kategori_id']);
        if (!$category || !$category['status_aktif']) {
            return ['valid' => false, 'message' => 'Kategori sampah tidak valid atau tidak aktif'];
        }

        return ['valid' => true, 'message' => ''];
    }

    private function getDefaultStats(): array
    {
        return [
            'total_entries' => 0,
            'pending_count' => 0,
            'approved_count' => 0,
            'rejected_count' => 0,
            'total_weight' => 0,
            'weight_today' => 0,
            'weight_month' => 0
        ];
    }
}