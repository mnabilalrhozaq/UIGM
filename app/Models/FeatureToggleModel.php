<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureToggleModel extends Model
{
    protected $table = 'feature_toggles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'feature_key',
        'feature_name',
        'description',
        'is_enabled',
        'role_config',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'feature_key' => 'required|max_length[100]|is_unique[feature_toggles.feature_key,id,{id}]',
        'feature_name' => 'required|max_length[255]',
        'is_enabled' => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'feature_key' => [
            'required' => 'Feature key harus diisi',
            'max_length' => 'Feature key maksimal 100 karakter',
            'is_unique' => 'Feature key sudah ada'
        ],
        'feature_name' => [
            'required' => 'Nama feature harus diisi',
            'max_length' => 'Nama feature maksimal 255 karakter'
        ],
        'is_enabled' => [
            'required' => 'Status enabled harus diisi',
            'in_list' => 'Status enabled harus 0 atau 1'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by'])) {
            $user = session()->get('user');
            $data['data']['created_by'] = $user['id'] ?? null;
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        if (!isset($data['data']['updated_by'])) {
            $user = session()->get('user');
            $data['data']['updated_by'] = $user['id'] ?? null;
        }
        return $data;
    }

    /**
     * Get feature by key
     */
    public function getByKey(string $key): ?array
    {
        return $this->where('feature_key', $key)->first();
    }

    /**
     * Get enabled features for role
     */
    public function getEnabledForRole(string $role): array
    {
        $features = $this->where('is_enabled', 1)->findAll();
        $enabledFeatures = [];

        foreach ($features as $feature) {
            $roleConfig = json_decode($feature['role_config'], true);
            if ($roleConfig && isset($roleConfig[$role]) && $roleConfig[$role]) {
                $enabledFeatures[] = $feature;
            }
        }

        return $enabledFeatures;
    }

    /**
     * Toggle feature status
     */
    public function toggleFeature(string $key): bool
    {
        $feature = $this->getByKey($key);
        if (!$feature) {
            return false;
        }

        $newStatus = $feature['is_enabled'] ? 0 : 1;
        return $this->update($feature['id'], ['is_enabled' => $newStatus]);
    }

    /**
     * Update role configuration
     */
    public function updateRoleConfig(string $key, array $roleConfig): bool
    {
        $feature = $this->getByKey($key);
        if (!$feature) {
            return false;
        }

        return $this->update($feature['id'], ['role_config' => json_encode($roleConfig)]);
    }
}