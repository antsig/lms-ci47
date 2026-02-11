<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'subject', 'message', 'read_status', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';  // No updated field needed for now, or match existing pattern
    // If using INT timestamps like other models
    protected $dateFormat = 'int';

    public function getUnreadCount()
    {
        return $this->where('read_status', 0)->countAllResults();
    }
}
