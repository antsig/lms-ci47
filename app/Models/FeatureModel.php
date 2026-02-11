<?php

namespace App\Models;

use CodeIgniter\Model;

class FeatureModel extends Model
{
    protected $table = 'website_features';
    protected $primaryKey = 'id';
    protected $allowedFields = ['icon', 'title', 'description', 'display_order'];

    public function getFeatures()
    {
        return $this->orderBy('display_order', 'ASC')->findAll();
    }
}
