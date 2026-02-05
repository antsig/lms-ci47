<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $DBGroup = 'default';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [];
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';
    protected $deletedField = 'deleted_at';
    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get settings value by key
     */
    public function get_settings($key = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        if ($key != '') {
            $result = $builder->where('key', $key)->get()->getRowArray();
            return $result ? $result['value'] : '';
        }

        $results = $builder->get()->getResultArray();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }

    /**
     * Update settings
     */
    public function update_settings($key, $value)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('settings');

        $existing = $builder->where('key', $key)->get()->getRowArray();

        if ($existing) {
            return $builder->where('key', $key)->update(['value' => $value]);
        } else {
            return $builder->insert(['key' => $key, 'value' => $value]);
        }
    }

    /**
     * Get frontend settings
     */
    public function get_frontend_settings($key = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('frontend_settings');

        if ($key != '') {
            $result = $builder->where('key', $key)->get()->getRowArray();
            return $result ? $result['value'] : '';
        }

        $results = $builder->get()->getResultArray();
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['key']] = $row['value'];
        }
        return $settings;
    }
}
