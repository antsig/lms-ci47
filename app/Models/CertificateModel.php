<?php

namespace App\Models;

use CodeIgniter\Model;

class CertificateModel extends Model
{
    protected $table = 'certificates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'background_image', 'template_data', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getCertificates()
    {
        return $this->findAll();
    }
}
