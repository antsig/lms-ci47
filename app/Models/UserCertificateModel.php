<?php

namespace App\Models;

use CodeIgniter\Model;

class UserCertificateModel extends Model
{
    protected $table = 'user_certificates';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'course_id', 'certificate_id', 'certificate_code', 'issued_at'];
    protected $useTimestamps = false;  // We manage issued_at manually

    public function issueCertificate($userId, $courseId, $certId = null)
    {
        $code = strtoupper(uniqid('CERT-'));

        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'certificate_id' => $certId,
            'certificate_code' => $code,
            'issued_at' => date('Y-m-d H:i:s')
        ];

        $this->insert($data);
        return $code;
    }
}
