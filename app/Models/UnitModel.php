<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitModel extends Model
{
    protected $table            = 'unit';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_unit',
        'kode_unit',
        'tipe_unit',
        'parent_id',
        'admin_unit_id',
        'status_aktif'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'status_aktif' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nama_unit' => 'required|max_length[255]',
        'kode_unit' => 'required|max_length[20]|is_unique[unit.kode_unit,id,{id}]',
        'tipe_unit' => 'required|in_list[fakultas,jurusan,unit_kerja,lembaga]',
    ];
    protected $validationMessages   = [
        'nama_unit' => [
            'required' => 'Nama unit harus diisi',
            'max_length' => 'Nama unit maksimal 255 karakter'
        ],
        'kode_unit' => [
            'required' => 'Kode unit harus diisi',
            'is_unique' => 'Kode unit sudah digunakan'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get units with submission status for specific year
     */
    public function getUnitsWithSubmissionStatus(int $tahunId)
    {
        return $this->select('unit.*, 
                             pengiriman_unit.status_pengiriman,
                             pengiriman_unit.progress_persen,
                             pengiriman_unit.tanggal_kirim,
                             pengiriman_unit.id as pengiriman_id,
                             users.nama_lengkap as admin_name')
            ->join('pengiriman_unit', 'pengiriman_unit.unit_id = unit.id', 'left')
            ->join('users', 'users.id = unit.admin_unit_id', 'left')
            ->where('unit.status_aktif', true)
            ->where('pengiriman_unit.tahun_penilaian_id', $tahunId)
            ->orderBy('pengiriman_unit.tanggal_kirim', 'DESC')
            ->findAll();
    }

    /**
     * Get unit statistics for dashboard
     */
    public function getUnitStatistics(int $tahunId)
    {
        $totalUnits = $this->where('status_aktif', true)->countAllResults();

        $submittedUnits = $this->db->table('pengiriman_unit')
            ->where('tahun_penilaian_id', $tahunId)
            ->whereIn('status_pengiriman', ['dikirim', 'review', 'perlu_revisi', 'disetujui', 'final'])
            ->countAllResults();

        $pendingReview = $this->db->table('pengiriman_unit')
            ->where('tahun_penilaian_id', $tahunId)
            ->whereIn('status_pengiriman', ['dikirim', 'review'])
            ->countAllResults();

        $notSubmitted = $totalUnits - $submittedUnits;

        return [
            'total_unit' => $totalUnits,
            'unit_sudah_kirim' => $submittedUnits,
            'unit_belum_kirim' => $notSubmitted,
            'data_menunggu_review' => $pendingReview
        ];
    }

    /**
     * Get units by type
     */
    public function getUnitsByType(string $tipeUnit)
    {
        return $this->where('tipe_unit', $tipeUnit)
            ->where('status_aktif', true)
            ->findAll();
    }

    /**
     * Get child units
     */
    public function getChildUnits(int $parentId)
    {
        return $this->where('parent_id', $parentId)
            ->where('status_aktif', true)
            ->findAll();
    }

    /**
     * Get unit hierarchy
     */
    public function getUnitHierarchy()
    {
        return $this->select('unit.*, parent.nama_unit as parent_name')
            ->join('unit as parent', 'parent.id = unit.parent_id', 'left')
            ->where('unit.status_aktif', true)
            ->orderBy('unit.tipe_unit')
            ->orderBy('unit.nama_unit')
            ->findAll();
    }

    /**
     * Get unit with admin information
     */
    public function getUnitWithAdmin(int $unitId)
    {
        return $this->select('unit.*, users.nama_lengkap as admin_name, users.email as admin_email')
            ->join('users', 'users.id = unit.admin_unit_id', 'left')
            ->find($unitId);
    }
}
