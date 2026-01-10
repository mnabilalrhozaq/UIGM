<?php

namespace App\Services\Admin;

use App\Models\WasteModel;
use App\Models\UnitModel;
use App\Models\UserModel;

class ReviewService
{
    protected $wasteModel;
    protected $unitModel;
    protected $userModel;

    public function __construct()
    {
        $this->wasteModel = new WasteModel();
        $this->unitModel = new UnitModel();
        $this->userModel = new UserModel();
    }

    public function getReviewData(): array
    {
        try {
            return [
                'pending_reviews' => $this->getPendingReviews(),
                'recent_reviews' => $this->getRecentReviews(),
                'stats' => $this->getReviewStats(),
                'queue_summary' => $this->getQueueSummary()
            ];
        } catch (\Exception $e) {
            log_message('error', 'Admin Review Service Error: ' . $e->getMessage());
            
            return [
                'pending_reviews' => [],
                'recent_reviews' => [],
                'stats' => $this->getDefaultStats(),
                'queue_summary' => []
            ];
        }
    }

    public function approveWaste(int $id, array $data = []): array
    {
        try {
            $waste = $this->wasteModel->find($id);
            if (!$waste) {
                return ['success' => false, 'message' => 'Data waste tidak ditemukan'];
            }

            if ($waste['status'] !== 'pending') {
                return ['success' => false, 'message' => 'Data waste tidak dalam status pending'];
            }

            $updateData = [
                'status' => 'approved',
                'reviewed_by' => session()->get('user')['id'],
                'reviewed_at' => date('Y-m-d H:i:s'),
                'review_notes' => $data['notes'] ?? 'Disetujui oleh admin'
            ];

            $result = $this->wasteModel->update($id, $updateData);
            
            if ($result) {
                // Send notification to user (if notification system exists)
                $this->sendApprovalNotification($waste);
                
                return ['success' => true, 'message' => 'Data waste berhasil disetujui'];
            }

            return ['success' => false, 'message' => 'Gagal menyetujui data waste'];

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
                return ['success' => false, 'message' => 'Data waste tidak ditemukan'];
            }

            if ($waste['status'] !== 'pending') {
                return ['success' => false, 'message' => 'Data waste tidak dalam status pending'];
            }

            if (empty($data['notes'])) {
                return ['success' => false, 'message' => 'Alasan penolakan harus diisi'];
            }

            $updateData = [
                'status' => 'rejected',
                'reviewed_by' => session()->get('user')['id'],
                'reviewed_at' => date('Y-m-d H:i:s'),
                'review_notes' => $data['notes']
            ];

            $result = $this->wasteModel->update($id, $updateData);
            
            if ($result) {
                // Send notification to user (if notification system exists)
                $this->sendRejectionNotification($waste, $data['notes']);
                
                return ['success' => true, 'message' => 'Data waste berhasil ditolak'];
            }

            return ['success' => false, 'message' => 'Gagal menolak data waste'];

        } catch (\Exception $e) {
            log_message('error', 'Reject Waste Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    public function getWasteDetail(int $id): array
    {
        try {
            $waste = $this->wasteModel
                ->select('waste.*, harga_sampah.kategori, harga_sampah.harga_per_kg, units.nama_unit, users.nama_lengkap as created_by_name, reviewer.nama_lengkap as reviewed_by_name')
                ->join('harga_sampah', 'harga_sampah.id = waste.kategori_id', 'left')
                ->join('units', 'units.id = waste.unit_id', 'left')
                ->join('users', 'users.id = waste.created_by', 'left')
                ->join('users as reviewer', 'reviewer.id = waste.reviewed_by', 'left')
                ->find($id);

            if (!$waste) {
                return ['success' => false, 'message' => 'Data waste tidak ditemukan'];
            }

            return ['success' => true, 'data' => $waste];

        } catch (\Exception $e) {
            log_message('error', 'Get Waste Detail Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    private function getPendingReviews(): array
    {
        return $this->wasteModel
            ->select('waste.*, harga_sampah.kategori, units.nama_unit, users.nama_lengkap as created_by_name')
            ->join('harga_sampah', 'harga_sampah.id = waste.kategori_id', 'left')
            ->join('units', 'units.id = waste.unit_id', 'left')
            ->join('users', 'users.id = waste.created_by', 'left')
            ->where('waste.status', 'pending')
            ->orderBy('waste.created_at', 'ASC') // Oldest first for review queue
            ->findAll();
    }

    private function getRecentReviews(): array
    {
        return $this->wasteModel
            ->select('waste.*, harga_sampah.kategori, units.nama_unit, users.nama_lengkap as created_by_name, reviewer.nama_lengkap as reviewed_by_name')
            ->join('harga_sampah', 'harga_sampah.id = waste.kategori_id', 'left')
            ->join('units', 'units.id = waste.unit_id', 'left')
            ->join('users', 'users.id = waste.created_by', 'left')
            ->join('users as reviewer', 'reviewer.id = waste.reviewed_by', 'left')
            ->whereIn('waste.status', ['approved', 'rejected'])
            ->orderBy('waste.reviewed_at', 'DESC')
            ->limit(20)
            ->findAll();
    }

    private function getReviewStats(): array
    {
        return [
            'pending_count' => $this->wasteModel->where('status', 'pending')->countAllResults(),
            'approved_count' => $this->wasteModel->where('status', 'approved')->countAllResults(),
            'rejected_count' => $this->wasteModel->where('status', 'rejected')->countAllResults(),
            'total_count' => $this->wasteModel->countAllResults(),
            'today_reviewed' => $this->wasteModel
                ->whereIn('status', ['approved', 'rejected'])
                ->where('DATE(reviewed_at)', date('Y-m-d'))
                ->countAllResults()
        ];
    }

    private function getQueueSummary(): array
    {
        $today = date('Y-m-d');
        $urgentDate = date('Y-m-d', strtotime('-3 days'));

        return [
            'urgent_count' => $this->wasteModel
                ->where('status', 'pending')
                ->where('DATE(created_at) <=', $urgentDate)
                ->countAllResults(),
            
            'today_count' => $this->wasteModel
                ->where('status', 'pending')
                ->where('DATE(created_at)', $today)
                ->countAllResults(),
            
            'average_review_time' => $this->getAverageReviewTime()
        ];
    }

    private function getAverageReviewTime(): float
    {
        try {
            $result = $this->wasteModel
                ->select('AVG(TIMESTAMPDIFF(HOUR, created_at, reviewed_at)) as avg_hours')
                ->whereIn('status', ['approved', 'rejected'])
                ->where('reviewed_at IS NOT NULL')
                ->where('DATE(reviewed_at) >=', date('Y-m-d', strtotime('-30 days')))
                ->get()
                ->getRow();

            return $result ? round($result->avg_hours, 1) : 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function sendApprovalNotification(array $waste): void
    {
        // Implementation for sending approval notification
        // This would integrate with your notification system
        log_message('info', "Waste ID {$waste['id']} approved - notification should be sent to user {$waste['created_by']}");
    }

    private function sendRejectionNotification(array $waste, string $reason): void
    {
        // Implementation for sending rejection notification
        // This would integrate with your notification system
        log_message('info', "Waste ID {$waste['id']} rejected - notification should be sent to user {$waste['created_by']} with reason: {$reason}");
    }

    private function getDefaultStats(): array
    {
        return [
            'pending_count' => 0,
            'approved_count' => 0,
            'rejected_count' => 0,
            'total_count' => 0,
            'today_reviewed' => 0
        ];
    }
}