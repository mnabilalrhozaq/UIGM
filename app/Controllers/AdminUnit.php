<?php

namespace App\Controllers;

use App\Models\UnitModel;
use App\Models\TahunPenilaianModel;
use App\Models\PengirimanUnitModel;
use App\Models\ReviewKategoriModel;
use App\Models\IndikatorModel;
use App\Models\NotifikasiModel;
use App\Models\RiwayatVersiModel;

class AdminUnit extends BaseController
{
    protected $unitModel;
    protected $tahunModel;
    protected $pengirimanModel;
    protected $reviewModel;
    protected $indikatorModel;
    protected $notifikasiModel;
    protected $riwayatModel;

    public function __construct()
    {
        $this->unitModel = new UnitModel();
        $this->tahunModel = new TahunPenilaianModel();
        $this->pengirimanModel = new PengirimanUnitModel();
        $this->reviewModel = new ReviewKategoriModel();
        $this->indikatorModel = new IndikatorModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->riwayatModel = new RiwayatVersiModel();
    }

    /**
     * Dashboard utama Admin Unit
     */
    public function index()
    {
        // Ambil user dari session
        $user = session()->get('user');
        if (!$user || $user['role'] !== 'admin_unit') {
            return redirect()->to('/auth/login')
                ->with('error', 'Akses ditolak. Anda harus login sebagai Admin Unit.');
        }

        // Ambil data unit
        $unit = $this->unitModel->find($user['unit_id']);
        if (!$unit) {
            return redirect()->to('/auth/login')->with('error', 'Unit tidak ditemukan');
        }

        // Ambil tahun penilaian aktif
        $tahunAktif = $this->tahunModel->getActiveYear();
        if (!$tahunAktif) {
            return redirect()->back()->with('error', 'Tidak ada tahun penilaian aktif');
        }

        // Ambil atau buat pengiriman untuk tahun ini
        $pengiriman = $this->pengirimanModel->where('unit_id', $unit['id'])
            ->where('tahun_penilaian_id', $tahunAktif['id'])
            ->first();

        if (!$pengiriman) {
            // Buat pengiriman baru
            $pengirimanId = $this->pengirimanModel->insert([
                'unit_id' => $unit['id'],
                'tahun_penilaian_id' => $tahunAktif['id'],
                'status_pengiriman' => 'draft',
                'progress_persen' => 0.00,
                'versi' => 1
            ]);
            $pengiriman = $this->pengirimanModel->find($pengirimanId);

            // Log pembuatan pengiriman
            $this->riwayatModel->logSubmissionCreate($pengirimanId, $user['id'], $pengiriman);
        }

        // Ambil kategori UIGM
        $kategori = $this->indikatorModel->getUIGMCategories();

        // Ambil review data untuk setiap kategori
        $reviewData = [];
        foreach ($kategori as $kat) {
            $review = $this->reviewModel->where('pengiriman_id', $pengiriman['id'])
                ->where('indikator_id', $kat['id'])
                ->first();

            if (!$review) {
                // Buat review kosong
                $this->reviewModel->insert([
                    'pengiriman_id' => $pengiriman['id'],
                    'indikator_id' => $kat['id'],
                    'status_review' => 'pending'
                ]);
                $review = [
                    'pengiriman_id' => $pengiriman['id'],
                    'indikator_id' => $kat['id'],
                    'status_review' => 'pending',
                    'catatan_review' => null,
                    'data_input' => null
                ];
            }

            $reviewData[$kat['id']] = $review;
        }

        // Hitung progress
        $progress = $this->hitungProgress($reviewData);

        // Ambil notifikasi terbaru
        $notifikasi = $this->notifikasiModel->getUnreadNotifications($user['id']);

        $canEdit = in_array($pengiriman['status_pengiriman'], ['draft', 'perlu_revisi']);

        // Debug logging
        log_message('debug', 'AdminUnit Dashboard - Pengiriman Status: ' . $pengiriman['status_pengiriman']);
        log_message('debug', 'AdminUnit Dashboard - Can Edit: ' . ($canEdit ? 'true' : 'false'));
        log_message('debug', 'AdminUnit Dashboard - Allowed statuses: draft, perlu_revisi');
        log_message('debug', 'AdminUnit Dashboard - Status check result: ' . (in_array($pengiriman['status_pengiriman'], ['draft', 'perlu_revisi']) ? 'PASS' : 'FAIL'));

        $data = [
            'title' => 'Dashboard Input Data UIGM',
            'user' => $user,
            'unit' => $unit,
            'tahun' => $tahunAktif,
            'pengiriman' => $pengiriman,
            'kategori' => $kategori,
            'reviewData' => $reviewData,
            'progress' => $progress,
            'notifikasi' => $notifikasi,
            'canEdit' => $canEdit
        ];

        return view('admin_unit/dashboard', $data);
    }

    /**
     * Simpan data kategori
     */
    public function simpanKategori()
    {
        // Log semua data yang masuk
        log_message('debug', 'simpanKategori called');
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'Session user: ' . json_encode(session()->get('user')));

        $request = $this->request;
        $pengirimanId = $request->getPost('pengiriman_id');
        $indikatorId = $request->getPost('indikator_id');
        $dataInputJson = $request->getPost('data_input');
        $userId = session()->get('user')['id'] ?? 1;

        log_message('debug', 'Extracted data - pengirimanId: ' . $pengirimanId . ', indikatorId: ' . $indikatorId);
        log_message('debug', 'Data input JSON: ' . $dataInputJson);

        // Validasi basic
        if (!$pengirimanId || !$indikatorId || !$dataInputJson) {
            log_message('error', 'Validasi basic gagal - data tidak lengkap');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak lengkap'
            ]);
        }

        // Parse data input
        $dataInput = json_decode($dataInputJson, true);
        if (!$dataInput) {
            log_message('error', 'JSON decode gagal: ' . json_last_error_msg());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format data tidak valid'
            ]);
        }

        log_message('debug', 'Parsed data input: ' . json_encode($dataInput));

        // Validasi field yang diperlukan
        $requiredFields = ['tanggal_input', 'gedung', 'jumlah', 'satuan', 'deskripsi'];
        foreach ($requiredFields as $field) {
            if (empty($dataInput[$field])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => "Kolom {$field} harus diisi"
                ]);
            }
        }

        // Validasi tanggal
        if (!strtotime($dataInput['tanggal_input'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Format tanggal tidak valid'
            ]);
        }

        // Validasi jumlah
        if (!is_numeric($dataInput['jumlah']) || floatval($dataInput['jumlah']) <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jumlah harus berupa angka positif'
            ]);
        }

        // Validasi deskripsi minimal 10 karakter
        if (strlen(trim($dataInput['deskripsi'])) < 10) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Deskripsi harus minimal 10 karakter'
            ]);
        }

        // Cek apakah masih bisa diedit
        $pengiriman = $this->pengirimanModel->find($pengirimanId);
        if (!$pengiriman) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan'
            ]);
        }

        if (!in_array($pengiriman['status_pengiriman'], ['draft', 'perlu_revisi'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak dapat diedit karena sudah dikirim'
            ]);
        }

        try {
            log_message('debug', 'Mulai proses penyimpanan data');

            // Ambil data sebelumnya untuk audit
            $reviewLama = $this->reviewModel->where('pengiriman_id', $pengirimanId)
                ->where('indikator_id', $indikatorId)
                ->first();

            log_message('debug', 'Review lama: ' . json_encode($reviewLama));

            // Sanitize dan format data
            $cleanDataInput = [
                'tanggal_input' => date('Y-m-d', strtotime($dataInput['tanggal_input'])),
                'gedung' => trim($dataInput['gedung']),
                'jenis_sampah' => isset($dataInput['jenis_sampah']) ? trim($dataInput['jenis_sampah']) : '',
                'jumlah' => floatval($dataInput['jumlah']),
                'satuan' => trim($dataInput['satuan']),
                'deskripsi' => trim($dataInput['deskripsi']),
                'target_rencana' => isset($dataInput['target_rencana']) ? trim($dataInput['target_rencana']) : '',
                'catatan' => isset($dataInput['catatan']) ? trim($dataInput['catatan']) : '',
                'dokumen' => isset($dataInput['dokumen']) ? $dataInput['dokumen'] : [],
                // Keep backward compatibility
                'nilai_numerik' => floatval($dataInput['jumlah']),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Clean data input: ' . json_encode($cleanDataInput));

            // Update atau insert review
            $reviewData = [
                'pengiriman_id' => $pengirimanId,
                'indikator_id' => $indikatorId,
                'status_review' => 'pending',
                'data_input' => json_encode($cleanDataInput)
            ];

            log_message('debug', 'Review data to save: ' . json_encode($reviewData));

            if ($reviewLama) {
                log_message('debug', 'Updating existing review with ID: ' . $reviewLama['id']);
                $result = $this->reviewModel->update($reviewLama['id'], $reviewData);
                log_message('debug', 'Update result: ' . ($result ? 'success' : 'failed'));
            } else {
                log_message('debug', 'Inserting new review');
                $result = $this->reviewModel->insert($reviewData);
                log_message('debug', 'Insert result: ' . ($result ? 'success (ID: ' . $result . ')' : 'failed'));
            }

            // Update progress
            $progress = $this->updateProgress($pengirimanId);
            log_message('debug', 'Updated progress: ' . $progress);

            // Log aktivitas
            $indikator = $this->indikatorModel->find($indikatorId);
            $this->riwayatModel->logActivity(
                $pengirimanId,
                'update',
                $userId,
                "Data kategori {$indikator['nama_kategori']} diperbarui",
                $reviewData,
                $reviewLama
            );

            log_message('debug', 'Penyimpanan berhasil');

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'progress' => $progress,
                'data' => $cleanDataInput
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in simpanKategori: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Kirim data ke Admin Pusat
     */
    public function kirimData()
    {
        $pengirimanId = $this->request->getPost('pengiriman_id');
        $userId = session()->get('user')['id'] ?? 1;

        if (!$pengirimanId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'ID pengiriman tidak valid'
            ]);
        }

        $pengiriman = $this->pengirimanModel->find($pengirimanId);
        if (!$pengiriman) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pengiriman tidak ditemukan'
            ]);
        }

        // Cek kelengkapan data
        $progress = $this->updateProgress($pengirimanId);
        if ($progress < 100) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data belum lengkap. Pastikan semua kategori sudah diisi.'
            ]);
        }

        try {
            // Update status pengiriman
            $this->pengirimanModel->update($pengirimanId, [
                'status_pengiriman' => 'dikirim',
                'tanggal_kirim' => date('Y-m-d H:i:s')
            ]);

            // Log aktivitas
            $this->riwayatModel->logSubmissionSubmit($pengirimanId, $userId);

            // Kirim notifikasi ke Admin Pusat
            $unit = $this->unitModel->find($pengiriman['unit_id']);
            $adminPusat = model('UserModel')->where('role', 'admin_pusat')->first();
            if ($adminPusat) {
                $this->notifikasiModel->notifyDataSubmission(
                    $adminPusat['id'],
                    $unit['id'],
                    $unit['nama_unit']
                );
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Data berhasil dikirim ke Admin Pusat'
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengirim data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hitung progress berdasarkan kelengkapan data
     */
    private function hitungProgress($reviewData)
    {
        $totalKategori = 6;
        $kategoriLengkap = 0;

        foreach ($reviewData as $review) {
            if (!empty($review['data_input'])) {
                $dataInput = json_decode($review['data_input'], true);
                if ($this->isKategoriLengkap($dataInput)) {
                    $kategoriLengkap++;
                }
            }
        }

        return ($kategoriLengkap / $totalKategori) * 100;
    }

    /**
     * Update progress pengiriman
     */
    private function updateProgress($pengirimanId)
    {
        $reviewData = $this->reviewModel->where('pengiriman_id', $pengirimanId)->findAll();
        $reviewDataIndexed = [];
        foreach ($reviewData as $review) {
            $reviewDataIndexed[$review['indikator_id']] = $review;
        }

        $progress = $this->hitungProgress($reviewDataIndexed);

        $this->pengirimanModel->update($pengirimanId, [
            'progress_persen' => $progress
        ]);

        return $progress;
    }

    /**
     * Cek apakah kategori sudah lengkap
     */
    private function isKategoriLengkap($dataInput)
    {
        if (!is_array($dataInput)) {
            return false;
        }

        // Cek field wajib baru
        $requiredFields = ['tanggal_input', 'gedung', 'jumlah', 'satuan', 'deskripsi'];

        foreach ($requiredFields as $field) {
            if (empty($dataInput[$field])) {
                return false;
            }
        }

        // Validasi tambahan
        if (!is_numeric($dataInput['jumlah']) || floatval($dataInput['jumlah']) <= 0) {
            return false;
        }

        if (strlen(trim($dataInput['deskripsi'])) < 10) {
            return false;
        }

        return true;
    }
}
