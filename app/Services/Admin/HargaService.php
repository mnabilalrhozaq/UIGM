<?php

namespace App\Services\Admin;

use App\Models\HargaSampahModel;
use App\Models\HargaLogModel;

class HargaService
{
    protected $hargaModel;
    protected $logModel;

    public function __construct()
    {
        $this->hargaModel = new HargaSampahModel();
        $this->logModel = new HargaLogModel();
    }

    public function getHargaData(): array
    {
        try {
            return [
                'harga_list' => $this->hargaModel->findAll(),
                'categories' => $this->getCategories()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Harga Service Error: ' . $e->getMessage());
            
            return [
                'harga_list' => [],
                'categories' => []
            ];
        }
    }

    public function createHarga(array $data): array
    {
        try {
            $validation = $this->validateHargaData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            $hargaData = [
                'jenis_sampah' => $data['jenis_sampah'],
                'nama_jenis' => $data['nama_jenis'] ?? $data['jenis_sampah'],
                'harga_per_satuan' => $data['harga_per_satuan'],
                'harga_per_kg' => $data['harga_per_kg'] ?? $data['harga_per_satuan'],
                'satuan' => $data['satuan'] ?? 'kg',
                'dapat_dijual' => $data['dapat_dijual'] ?? 1,
                'status_aktif' => 1,
                'deskripsi' => $data['deskripsi'] ?? null,
                'tanggal_berlaku' => date('Y-m-d')
            ];

            $result = $this->hargaModel->insert($hargaData);
            
            if ($result) {
                // Log the change
                $userId = session()->get('user')['id'] ?? 1;
                $this->logModel->logPriceChange(
                    $result,
                    $hargaData['jenis_sampah'],
                    null,
                    $hargaData['harga_per_satuan'],
                    $userId,
                    'Harga baru ditambahkan'
                );
                
                return ['success' => true, 'message' => 'Harga berhasil ditambahkan'];
            }

            return ['success' => false, 'message' => 'Gagal menambahkan harga'];

        } catch (\Exception $e) {
            log_message('error', 'Create Harga Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function updateHarga(int $id, array $data): array
    {
        try {
            $validation = $this->validateHargaData($data);
            if (!$validation['valid']) {
                return ['success' => false, 'message' => $validation['message']];
            }

            $oldData = $this->hargaModel->find($id);
            if (!$oldData) {
                return ['success' => false, 'message' => 'Data harga tidak ditemukan'];
            }

            $hargaData = [
                'jenis_sampah' => $data['jenis_sampah'],
                'nama_jenis' => $data['nama_jenis'] ?? $data['jenis_sampah'],
                'harga_per_satuan' => $data['harga_per_satuan'],
                'harga_per_kg' => $data['harga_per_kg'] ?? $data['harga_per_satuan'],
                'satuan' => $data['satuan'] ?? 'kg',
                'dapat_dijual' => $data['dapat_dijual'] ?? 1,
                'deskripsi' => $data['deskripsi'] ?? null,
                'tanggal_berlaku' => date('Y-m-d')
            ];

            $result = $this->hargaModel->update($id, $hargaData);
            
            if ($result) {
                // Log the change
                $userId = session()->get('user')['id'] ?? 1;
                $this->logModel->logPriceChange(
                    $id,
                    $hargaData['jenis_sampah'],
                    $oldData['harga_per_satuan'],
                    $hargaData['harga_per_satuan'],
                    $userId,
                    'Harga diupdate'
                );
                
                return ['success' => true, 'message' => 'Harga berhasil diupdate'];
            }

            return ['success' => false, 'message' => 'Gagal mengupdate harga'];

        } catch (\Exception $e) {
            log_message('error', 'Update Harga Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function toggleStatus(int $id): array
    {
        try {
            $harga = $this->hargaModel->find($id);
            if (!$harga) {
                return ['success' => false, 'message' => 'Data harga tidak ditemukan'];
            }

            $newStatus = $harga['status_aktif'] ? 0 : 1;
            
            $result = $this->hargaModel->update($id, [
                'status_aktif' => $newStatus
            ]);

            if ($result) {
                // Log the change
                $userId = session()->get('user')['id'] ?? 1;
                $this->logModel->logPriceChange(
                    $id,
                    $harga['jenis_sampah'],
                    $harga['harga_per_satuan'],
                    $harga['harga_per_satuan'],
                    $userId,
                    'Status diubah menjadi ' . ($newStatus ? 'aktif' : 'nonaktif')
                );
                
                return ['success' => true, 'message' => 'Status berhasil diubah'];
            }

            return ['success' => false, 'message' => 'Gagal mengubah status'];

        } catch (\Exception $e) {
            log_message('error', 'Toggle Harga Status Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function deleteHarga(int $id): array
    {
        try {
            $harga = $this->hargaModel->find($id);
            if (!$harga) {
                return ['success' => false, 'message' => 'Data harga tidak ditemukan'];
            }

            // Log before delete (jika logModel tersedia)
            try {
                if (isset($this->logModel)) {
                    $userId = session()->get('user')['id'] ?? 1;
                    $this->logModel->logPriceChange(
                        $id,
                        $harga['jenis_sampah'],
                        $harga['harga_per_satuan'],
                        0,
                        $userId,
                        'Harga dihapus'
                    );
                }
            } catch (\Exception $logError) {
                // Log error tapi tetap lanjut hapus
                log_message('warning', 'Failed to log price change: ' . $logError->getMessage());
            }
            
            $result = $this->hargaModel->delete($id);
            
            if ($result) {
                return ['success' => true, 'message' => 'Harga berhasil dihapus'];
            }

            return ['success' => false, 'message' => 'Gagal menghapus harga'];

        } catch (\Exception $e) {
            log_message('error', 'Delete Harga Error: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
        }
    }

    public function getHargaLogs(): array
    {
        try {
            return [
                'logs' => $this->logModel->getRecentChanges(100)
            ];
        } catch (\Exception $e) {
            log_message('error', 'Harga Logs Error: ' . $e->getMessage());
            return ['logs' => []];
        }
    }

    private function validateHargaData(array $data): array
    {
        if (empty($data['jenis_sampah'])) {
            return ['valid' => false, 'message' => 'Jenis sampah harus diisi'];
        }

        if (empty($data['harga_per_satuan']) || !is_numeric($data['harga_per_satuan'])) {
            return ['valid' => false, 'message' => 'Harga per satuan harus berupa angka'];
        }

        if ($data['harga_per_satuan'] < 0) {
            return ['valid' => false, 'message' => 'Harga per satuan tidak boleh negatif'];
        }

        return ['valid' => true, 'message' => ''];
    }

    private function getCategories(): array
    {
        return [
            'Plastik',
            'Kertas',
            'Logam',
            'Organik',
            'Residu'
        ];
    }
}