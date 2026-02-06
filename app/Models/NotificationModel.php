<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'type', 'title', 'message', 'url', 'is_read', 'created_at'];
    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'created_at';

    // Helper to add notification
    public function add($userId, $title, $message, $type = 'info', $url = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'url' => $url,
            'is_read' => 0,
            'created_at' => time()
        ]);
    }

    // Get unread count
    public function countUnread($userId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->countAllResults();
    }
}
