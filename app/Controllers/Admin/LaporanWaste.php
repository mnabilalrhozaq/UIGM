<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\Admin\LaporanWasteService;

class LaporanWaste extends BaseController
{
    protected $laporanService;

    public function __construct()
    {
        $this->laporanService = new LaporanWasteService();
    }

    public function index()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            // Get filter parameters
            $filters = [
                'start_date' => $this->request->getGet('start_date'),
                'end_date' => $this->request->getGet('end_date'),
                'status' => $this->request->getGet('status'),
                'unit_id' => $this->request->getGet('unit_id')
            ];
            
            // Get page for each section
            $section = $this->request->getGet('section') ?? 'disetujui';
            $page = (int)($this->request->getGet('page') ?? 1);
            
            // Set page untuk section yang aktif, section lain page 1
            $pages = [
                'disetujui' => $section === 'disetujui' ? $page : 1,
                'ditolak' => $section === 'ditolak' ? $page : 1,
                'rekap_jenis' => $section === 'rekap_jenis' ? $page : 1,
                'rekap_unit' => $section === 'rekap_unit' ? $page : 1
            ];

            $data = $this->laporanService->getLaporanData($filters, $pages, 10);
            
            $viewData = [
                'title' => 'Laporan Waste',
                'rekap_jenis' => $data['rekap_jenis'],
                'rekap_unit' => $data['rekap_unit'],
                'data_disetujui' => $data['data_disetujui'],
                'data_ditolak' => $data['data_ditolak'],
                'units' => $data['units'],
                'filters' => $filters,
                'summary' => $data['summary'],
                'pagination' => $data['pagination'],
                'active_section' => $section,
                'current_page' => $page
            ];

            return view('admin_pusat/laporan_waste/index', $viewData);

        } catch (\Exception $e) {
            log_message('error', 'Laporan Waste Error: ' . $e->getMessage());
            
            return view('admin_pusat/laporan_waste/index', [
                'title' => 'Laporan Waste',
                'rekap_jenis' => [],
                'rekap_unit' => [],
                'data_disetujui' => [],
                'data_ditolak' => [],
                'units' => [],
                'filters' => [],
                'summary' => [],
                'error' => 'Terjadi kesalahan saat memuat laporan'
            ]);
        }
    }

    public function export()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $filters = [
                'start_date' => $this->request->getGet('start_date'),
                'end_date' => $this->request->getGet('end_date'),
                'status' => $this->request->getGet('status'),
                'unit_id' => $this->request->getGet('unit_id')
            ];

            $result = $this->laporanService->exportLaporan($filters);
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Export Laporan Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export laporan');
        }
    }

    public function exportPdf()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $filters = [
                'start_date' => $this->request->getGet('start_date'),
                'end_date' => $this->request->getGet('end_date'),
                'status' => $this->request->getGet('status'),
                'unit_id' => $this->request->getGet('unit_id')
            ];

            $result = $this->laporanService->exportPdf($filters);
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Export PDF Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF');
        }
    }

    public function exportCsv()
    {
        try {
            if (!$this->validateSession()) {
                return redirect()->to('/auth/login');
            }

            $filters = [
                'start_date' => $this->request->getGet('start_date'),
                'end_date' => $this->request->getGet('end_date'),
                'status' => $this->request->getGet('status'),
                'unit_id' => $this->request->getGet('unit_id')
            ];

            $result = $this->laporanService->exportCsv($filters);
            
            if ($result['success']) {
                return $this->response->download($result['file_path'], null)->setFileName($result['filename']);
            }

            return redirect()->back()->with('error', $result['message']);

        } catch (\Exception $e) {
            log_message('error', 'Export CSV Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export CSV');
        }
    }

    private function validateSession(): bool
    {
        $session = session();
        $user = $session->get('user');
        
        return $session->get('isLoggedIn') && 
               isset($user['id'], $user['role']) &&
               $user['role'] === 'admin_pusat';
    }
}
