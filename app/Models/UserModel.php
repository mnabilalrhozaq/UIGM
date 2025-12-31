<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password',
        'nama_lengkap',
        'role',
        'unit_id',
        'status_aktif',
        'last_login'
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
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'nama_lengkap' => 'required|max_length[255]',
        'role' => 'required|in_list[admin_pusat,admin_unit,super_admin]',
    ];
    protected $validationMessages   = [
        'username' => [
            'required' => 'Username harus diisi',
            'min_length' => 'Username minimal 3 karakter',
            'is_unique' => 'Username sudah digunakan'
        ],
        'email' => [
            'required' => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique' => 'Email sudah digunakan'
        ],
        'password' => [
            'required' => 'Password harus diisi',
            'min_length' => 'Password minimal 8 karakter'
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
     * Get users by role
     */
    public function getUsersByRole(string $role)
    {
        return $this->where('role', $role)
            ->where('status_aktif', true)
            ->findAll();
    }

    /**
     * Get admin pusat users
     */
    public function getAdminPusat()
    {
        return $this->getUsersByRole('admin_pusat');
    }

    /**
     * Get admin unit users
     */
    public function getAdminUnit()
    {
        return $this->getUsersByRole('admin_unit');
    }

    /**
     * Get user with unit information
     */
    public function getUserWithUnit(int $userId)
    {
        return $this->select('users.*, unit.nama_unit, unit.kode_unit')
            ->join('unit', 'unit.id = users.unit_id', 'left')
            ->find($userId);
    }

    /**
     * Update last login
     */
    public function updateLastLogin(int $userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Verify password (plain text comparison)
     */
    public function verifyPassword(string $password, string $storedPassword): bool
    {
        return $password === $storedPassword;
    }
}
