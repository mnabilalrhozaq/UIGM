<?php

namespace App\Controllers;

use App\Models\UnitModel;
use App\Models\TahunPenilaianModel;
use App\Models\PengirimanUnitModel;
use App\Models\ReviewKategoriModel;
use App\Models\IndikatorModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class AdminPusat extends BaseController
{
    protected $unitModel;
    protected $tahunModel;
    protected $pengirimanModel;
    protected $reviewModel;
    protected $indikatorModel;
    protected $notifikasiModel;
    protected $userModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->tahunModel = new TahunPenilaianModel();
        $this->pengirimanModel = new PengirimanUnitModel();
        $this->reviewModel = new ReviewKategoriModel();
        $this->indikatorModel = new IndikatorModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->userModel = new UserModel();
    }

    /**
     * Dashboard utama Admin Pusat
     */
    public function index()
    {
        // Ambil user dari session
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'admin_pusat') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak. Anda harus login sebagai Admin Pusat.');
        }

        // Ambil filter dari request
        $tahunFilter = $this->request->getGet('tahun');
        $unitFilter = $this->request->getGet('unit');
        $statusFilter = $this->request->getGet('status');

        // Ambil tahun penilaian aktif atau yang dipilih
        if ($tahunFilter) {
            $tahunAktif = $this->tahunModel->find($tahunFilter);
        } else {
            $tahunAktif = $this->tahunModel->getActiveYear();
        }

        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun penilaian aktif');
        }

        // Ambil semua tahun untuk filter
        $allTahun = $this->tahunModel->findAll();

        // Ambil semua unit untuk filter
        $allUnits = $this->unitModel->where('status_aktif', true)->findAll();

        // Statistik dashboard dengan filter
        $stats = $this->getDashboardStats($tahunAktif['id'], $unitFilter, $statusFilter);

        // Pengiriman dengan filter
        $pengirimanQuery = $this->pengirimanModel
            ->select('pengiriman_unit.*, unit.nama_unit, unit.kode_unit, unit.tipe_unit')
            ->join('unit', 'unit.id = pengiriman_unit.unit_id')
            ->where('pengiriman_unit.tahun_penilaian_id', $tahunAktif['id']);

        if ($unitFilter) {
            $pengirimanQuery->where('pengiriman_unit.unit_id', $unitFilter);
        }

        if ($statusFilter) {
            $pengirimanQuery->where('pengiriman_unit.status_pengiriman', $statusFilter);
        } else {
            $pengirimanQuery->whereIn('pengiriman_unit.status_pengiriman', ['dikirim', 'review', 'perlu_revisi']);
        }

        $pengirimanPending = $pengirimanQuery
            ->orderBy('pengiriman_unit.tanggal_kirim', 'DESC')
            ->limit(10)
            ->findAll();

        // Notifikasi terbaru
        $notifikasi = $this->notifikasiModel->getUnreadNotifications($user['id']);

        // Progress institusi keseluruhan
        $institutionalProgress = $this->getInstitutionalProgress($tahunAktif['id']);

        $data = [
            'title' => 'Dashboard Admin Pusat UIGM',
            'user' => $user,
            'tahun' => $tahunAktif,
            'allTahun' => $allTahun,
            'allUnits' => $allUnits,
            'stats' => $stats,
            'pengirimanPending' => $pengirimanPending,
            'notifikasi' => $notifikasi,
            'institutionalProgress' => $institutionalProgress,
            'filters' => [
                'tahun' => $tahunFilter,
                'unit' => $unitFilter,
                'status' => $statusFilter
            ]
        ];

        return view('admin_pusat/dashboard', $data);
    }

    /**
     * Halaman monitoring semua unit
     */
    public function monitoring()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'admin_pusat') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak.');
        }

        $tahunAktif = $this->tahunModel->getActiveYear();

        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun penilaian aktif');
        }

        try {
            // Ambil semua unit dengan status pengiriman
            $units = $this->unitModel
                ->select('unit.id, unit.nama_unit, unit.kode_unit, unit.tipe_unit, unit.status_aktif,
                         pengiriman_unit.status_pengiriman, 
                         pengiriman_unit.progress_persen, 
                         pengiriman_unit.tanggal_kirim, 
                         pengiriman_unit.id as pengiriman_id')
                ->join(
                    'pengiriman_unit',
                    'pengiriman_unit.unit_id = unit.id AND pengiriman_unit.tahun_penilaian_id = ' . $tahunAktif['id'],
                    'left'
                )
                ->where('unit.status_aktif', true)
                ->orderBy('unit.nama_unit', 'ASC')
                ->findAll();

            // Pastikan data tidak null dan berikan default values
            foreach ($units as &$unit) {
                $unit['status_pengiriman'] = $unit['status_pengiriman'] ?? 'draft';
                $unit['progress_persen'] = $unit['progress_persen'] ?? 0.0;
                $unit['tanggal_kirim'] = $unit['tanggal_kirim'] ?? null;
                $unit['pengiriman_id'] = $unit['pengiriman_id'] ?? null;
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in monitoring: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data monitoring');
        }

        $data = [
            'title' => 'Monitoring Unit - Admin Pusat',
            'user' => $user,
            'tahun' => $tahunAktif,
            'units' => $units
        ];

        return view('admin_pusat/monitoring', $data);
    }

    /**
     * Halaman review detail pengiriman
     */
    public function review($pengirimanId)
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'admin_pusat') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak.');
        }

        // Ambil data pengiriman
        $pengiriman = $this->pengirimanModel
            ->select('pengiriman_unit.*, unit.nama_unit, unit.kode_unit, unit.tipe_unit, tahun_penilaian.tahun, tahun_penilaian.nama_periode')
            ->join('unit', 'unit.id = pengiriman_unit.unit_id')
            ->join('tahun_penilaian', 'tahun_penilaian.id = pengiriman_unit.tahun_penilaian_id')
            ->find($pengirimanId);

        if (!$pengiriman) {
            return redirect()->to('/admin-pusat/monitoring')
                ->with('error', 'Data pengiriman tidak ditemukan');
        }

        // Ambil kategori dan review
        $kategori = $this->indikatorModel->getUIGMCategories();
        $reviewData = [];

        foreach ($kategori as $kat) {
            $review = $this->reviewModel
                ->where('pengiriman_id', $pengirimanId)
                ->where('indikator_id', $kat['id'])
                ->first();

            if (!$review) {
                // Buat review kosong jika belum ada
                $this->reviewModel->insert([
                    'pengiriman_id' => $pengirimanId,
                    'indikator_id' => $kat['id'],
                    'status_review' => 'pending',
                    'skor_review' => null,
                    'catatan_review' => null
                ]);
                $review = [
                    'pengiriman_id' => $pengirimanId,
                    'indikator_id' => $kat['id'],
                    'status_review' => 'pending',
                    'catatan_review' => null,
                    'data_input' => null,
                    'skor_review' => null
                ];
            }

            $reviewData[$kat['id']] = $review;
        }

        // Hitung statistik review
        $reviewStats = $this->getReviewStats($pengirimanId);

        $data = [
            'title' => 'Review Data UIGM - ' . $pengiriman['nama_unit'],
            'user' => $user,
            'pengiriman' => $pengiriman,
            'kategori' => $kategori,
            'reviewData' => $reviewData,
            'reviewStats' => $reviewStats,
            'canFinalize' => $this->canFinalizePengiriman($pengirimanId)
        ];

        return view('admin_pusat/review_detail', $data);
    }

    /**
     * Finalisasi pengiriman (setujui semua)
     */
    public function finalizePengiriman()
    {
        $pengirimanId = $this->request->getPost('pengiriman_id');
        $userId = session()->get('user')['id'];

        if (!$pengirimanId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pengiriman tidak valid'
            ]);
        }

        try {
            // Cek apakah bisa difinalisasi
            if (!$this->canFinalizePengiriman($pengirimanId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Pengiriman tidak dapat difinalisasi. Pastikan semua kategori sudah direview.'
                ]);
            }

            // Update status pengiriman
            $this->pengirimanModel->update($pengirimanId, [
                'status_pengiriman' => 'disetujui',
                'tanggal_disetujui' => date('Y-m-d H:i:s'),
                'disetujui_oleh' => $userId
            ]);

            // Kirim notifikasi ke Admin Unit
            $pengiriman = $this->pengirimanModel->find($pengirimanId);
            $unit = $this->unitModel->find($pengiriman['unit_id']);
            $adminUnit = $this->userModel->where('unit_id', $unit['id'])->where('role', 'admin_unit')->first();

            if ($adminUnit) {
                $this->notifikasiModel->insert([
                    'user_id' => $adminUnit['id'],
                    'judul' => 'Data UIGM Disetujui',
                    'pesan' => "Data UIGM unit {$unit['nama_unit']} telah disetujui oleh Admin Pusat.",
                    'tipe_notifikasi' => 'approval',
                    'is_read' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil difinalisasi dan disetujui'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memfinalisasi data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Kembalikan untuk revisi (semua kategori)
     */
    public function returnForRevision()
    {
        $pengirimanId = $this->request->getPost('pengiriman_id');
        $catatanUmum = $this->request->getPost('catatan_umum');
        $userId = session()->get('user')['id'];

        if (!$pengirimanId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pengiriman tidak valid'
            ]);
        }

        try {
            // Update status pengiriman
            $this->pengirimanModel->update($pengirimanId, [
                'status_pengiriman' => 'perlu_revisi',
                'catatan_revisi' => $catatanUmum
            ]);

            // Update semua kategori yang belum disetujui menjadi perlu_revisi
            $this->reviewModel
                ->where('pengiriman_id', $pengirimanId)
                ->where('status_review !=', 'disetujui')
                ->set([
                    'status_review' => 'perlu_revisi',
                    'catatan_review' => $catatanUmum,
                    'reviewer_id' => $userId,
                    'tanggal_review' => date('Y-m-d H:i:s')
                ])
                ->update();

            // Kirim notifikasi ke Admin Unit
            $pengiriman = $this->pengirimanModel->find($pengirimanId);
            $unit = $this->unitModel->find($pengiriman['unit_id']);
            $adminUnit = $this->userModel->where('unit_id', $unit['id'])->where('role', 'admin_unit')->first();

            if ($adminUnit) {
                $this->notifikasiModel->insert([
                    'user_id' => $adminUnit['id'],
                    'judul' => 'Data UIGM Perlu Revisi',
                    'pesan' => "Data UIGM unit {$unit['nama_unit']} perlu direvisi. Silakan periksa catatan review.",
                    'tipe_notifikasi' => 'rejection',
                    'is_read' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data dikembalikan untuk revisi'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengembalikan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update review kategori
     */
    public function updateReview()
    {
        $request = $this->request;
        $pengirimanId = $request->getPost('pengiriman_id');
        $indikatorId = $request->getPost('indikator_id');
        $statusReview = $request->getPost('status_review');
        $catatanReview = $request->getPost('catatan_review');
        $skorReview = $request->getPost('skor_review');
        $userId = session()->get('user')['id'];

        // Validasi input
        if (!$pengirimanId || !$indikatorId || !$statusReview) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        try {
            // Update atau insert review
            $existingReview = $this->reviewModel
                ->where('pengiriman_id', $pengirimanId)
                ->where('indikator_id', $indikatorId)
                ->first();

            // Siapkan data review dengan penanganan null yang benar
            $reviewData = [
                'pengiriman_id' => (int)$pengirimanId,
                'indikator_id' => (int)$indikatorId,
                'status_review' => $statusReview,
                'catatan_review' => !empty($catatanReview) ? $catatanReview : null,
                'reviewer_id' => (int)$userId,
                'tanggal_review' => date('Y-m-d H:i:s')
            ];

            // Handle skor_review dengan benar - hanya tambahkan jika ada nilai
            if (!empty($skorReview) && is_numeric($skorReview)) {
                $reviewData['skor_review'] = (float)$skorReview;
            } else {
                // Jangan sertakan field skor_review jika kosong untuk update
                if (!$existingReview) {
                    $reviewData['skor_review'] = null;
                }
            }

            if ($existingReview) {
                // Untuk update, hanya update field yang diperlukan
                $updateData = $reviewData;
                if (!empty($skorReview) && is_numeric($skorReview)) {
                    $updateData['skor_review'] = (float)$skorReview;
                } elseif (empty($skorReview)) {
                    $updateData['skor_review'] = null;
                }
                $this->reviewModel->update($existingReview['id'], $updateData);
            } else {
                $this->reviewModel->insert($reviewData);
            }

            // Update status pengiriman jika semua kategori sudah direview
            $this->updatePengirimanStatus($pengirimanId);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Review berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error updating review: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan review: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Halaman notifikasi
     */
    public function notifikasi()
    {
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'admin_pusat') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak.');
        }

        // Get filter parameters
        $statusFilter = $this->request->getGet('status');
        $tipeFilter = $this->request->getGet('tipe');
        $searchFilter = $this->request->getGet('search');

        // Build query
        $query = $this->notifikasiModel->where('user_id', $user['id']);

        // Apply filters
        if ($statusFilter) {
            if ($statusFilter === 'unread') {
                $query->where('is_read', false);
            } elseif ($statusFilter === 'read') {
                $query->where('is_read', true);
            }
        }

        if ($tipeFilter) {
            $query->where('tipe_notifikasi', $tipeFilter);
        }

        if ($searchFilter) {
            $query->groupStart()
                ->like('judul', $searchFilter)
                ->orLike('pesan', $searchFilter)
                ->groupEnd();
        }

        $notifikasi = $query->orderBy('created_at', 'DESC')->paginate(20);

        $data = [
            'title' => 'Notifikasi - Admin Pusat',
            'user' => $user,
            'notifikasi' => $notifikasi,
            'pager' => $this->notifikasiModel->pager,
            'filters' => [
                'status' => $statusFilter,
                'tipe' => $tipeFilter,
                'search' => $searchFilter
            ]
        ];

        return view('admin_pusat/notifikasi', $data);
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats($tahunId, $unitFilter = null, $statusFilter = null)
    {
        $totalUnit = $this->unitModel->where('status_aktif', true)->countAllResults();

        $pengirimanQuery = $this->pengirimanModel->where('tahun_penilaian_id', $tahunId);

        if ($unitFilter) {
            $pengirimanQuery->where('unit_id', $unitFilter);
        }

        if ($statusFilter) {
            $pengirimanQuery->where('status_pengiriman', $statusFilter);
        }

        $pengirimanStats = $pengirimanQuery
            ->select('status_pengiriman, COUNT(*) as jumlah')
            ->groupBy('status_pengiriman')
            ->findAll();

        $stats = [
            'total_unit' => $totalUnit,
            'draft' => 0,
            'dikirim' => 0,
            'review' => 0,
            'perlu_revisi' => 0,
            'disetujui' => 0
        ];

        foreach ($pengirimanStats as $stat) {
            $stats[$stat['status_pengiriman']] = $stat['jumlah'];
        }

        $stats['belum_kirim'] = $totalUnit - array_sum(array_slice($stats, 1));
        $stats['menunggu_review'] = $stats['dikirim'] + $stats['review'];
        $stats['progress_keseluruhan'] = $totalUnit > 0 ?
            round((($stats['disetujui'] + $stats['review'] + $stats['dikirim']) / $totalUnit) * 100, 1) : 0;

        return $stats;
    }

    /**
     * Get institutional progress across all categories
     */
    private function getInstitutionalProgress($tahunId)
    {
        // Get all submissions for the year
        $submissions = $this->pengirimanModel
            ->select('pengiriman_unit.id, pengiriman_unit.unit_id, pengiriman_unit.status_pengiriman, unit.nama_unit')
            ->join('unit', 'unit.id = pengiriman_unit.unit_id')
            ->where('pengiriman_unit.tahun_penilaian_id', $tahunId)
            ->where('unit.status_aktif', true)
            ->findAll();

        $categoryProgress = [];
        $categories = $this->indikatorModel->getUIGMCategories();

        foreach ($categories as $category) {
            $categoryStats = [
                'nama_kategori' => $category['nama_kategori'],
                'kode_kategori' => $category['kode_kategori'],
                'total_unit' => count($submissions),
                'completed' => 0,
                'in_review' => 0,
                'needs_revision' => 0,
                'approved' => 0
            ];

            foreach ($submissions as $submission) {
                $review = $this->reviewModel
                    ->where('pengiriman_id', $submission['id'])
                    ->where('indikator_id', $category['id'])
                    ->first();

                if ($review) {
                    switch ($review['status_review']) {
                        case 'disetujui':
                            $categoryStats['approved']++;
                            break;
                        case 'perlu_revisi':
                            $categoryStats['needs_revision']++;
                            break;
                        case 'pending':
                            if (in_array($submission['status_pengiriman'], ['dikirim', 'review'])) {
                                $categoryStats['in_review']++;
                            }
                            break;
                    }
                }
            }

            $categoryStats['completed'] = $categoryStats['approved'] + $categoryStats['needs_revision'];
            $categoryStats['progress_percent'] = $categoryStats['total_unit'] > 0 ?
                round(($categoryStats['completed'] / $categoryStats['total_unit']) * 100, 1) : 0;

            $categoryProgress[] = $categoryStats;
        }

        return $categoryProgress;
    }

    /**
     * Update status pengiriman berdasarkan review
     */
    private function updatePengirimanStatus($pengirimanId)
    {
        // Hitung status review
        $reviewStats = $this->reviewModel
            ->select('status_review, COUNT(*) as jumlah')
            ->where('pengiriman_id', $pengirimanId)
            ->groupBy('status_review')
            ->findAll();

        $stats = ['pending' => 0, 'disetujui' => 0, 'perlu_revisi' => 0];
        foreach ($reviewStats as $stat) {
            $stats[$stat['status_review']] = $stat['jumlah'];
        }

        // Tentukan status pengiriman
        $newStatus = 'review';
        if ($stats['pending'] == 0) {
            if ($stats['perlu_revisi'] > 0) {
                $newStatus = 'perlu_revisi';
            } else {
                $newStatus = 'disetujui';
            }
        }

        // Update pengiriman
        $updateData = ['status_pengiriman' => $newStatus];
        if ($newStatus == 'disetujui') {
            $updateData['tanggal_disetujui'] = date('Y-m-d H:i:s');
        }

        $this->pengirimanModel->update($pengirimanId, $updateData);

        // Kirim notifikasi ke admin unit
        $pengiriman = $this->pengirimanModel->find($pengirimanId);
        $unit = $this->unitModel->find($pengiriman['unit_id']);
        $adminUnit = $this->userModel->where('unit_id', $unit['id'])->where('role', 'admin_unit')->first();

        if ($adminUnit) {
            if ($newStatus == 'disetujui') {
                $this->notifikasiModel->insert([
                    'user_id' => $adminUnit['id'],
                    'judul' => 'Data UIGM Disetujui',
                    'pesan' => "Data UIGM unit {$unit['nama_unit']} telah disetujui.",
                    'tipe_notifikasi' => 'approval',
                    'is_read' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            } elseif ($newStatus == 'perlu_revisi') {
                $this->notifikasiModel->insert([
                    'user_id' => $adminUnit['id'],
                    'judul' => 'Data UIGM Perlu Revisi',
                    'pesan' => "Data UIGM unit {$unit['nama_unit']} perlu direvisi.",
                    'tipe_notifikasi' => 'rejection',
                    'is_read' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
    }

    /**
     * Get review statistics for a pengiriman
     */
    private function getReviewStats($pengirimanId)
    {
        $stats = $this->reviewModel
            ->select('status_review, COUNT(*) as jumlah')
            ->where('pengiriman_id', $pengirimanId)
            ->groupBy('status_review')
            ->findAll();

        $result = ['pending' => 0, 'disetujui' => 0, 'perlu_revisi' => 0, 'total' => 0];
        foreach ($stats as $stat) {
            $result[$stat['status_review']] = $stat['jumlah'];
            $result['total'] += $stat['jumlah'];
        }

        return $result;
    }

    /**
     * Check if pengiriman can be finalized
     */
    private function canFinalizePengiriman($pengirimanId)
    {
        $stats = $this->getReviewStats($pengirimanId);
        return $stats['pending'] == 0 && $stats['perlu_revisi'] == 0 && $stats['disetujui'] > 0;
    }
}
