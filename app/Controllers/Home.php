<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('welcome_message');
    }

    // --- Notification API ---
    public function getNotifications()
    {
        $auth = new \App\Libraries\Auth();
        if (!$auth->isLoggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $userId = $auth->getUserId();
        $model = new \App\Models\NotificationModel();

        // Fetch unread only for dropdown
        $notifications = $model
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->findAll();

        $unreadCount = $model->countUnread($userId);

        return $this->response->setJSON([
            'count' => $unreadCount,
            'notifications' => $notifications
        ]);
    }

    public function markNotificationRead($id)
    {
        $auth = new \App\Libraries\Auth();
        if (!$auth->isLoggedIn()) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $model = new \App\Models\NotificationModel();
        $notif = $model->find($id);

        if ($notif && $notif['user_id'] == $auth->getUserId()) {
            $model->update($id, ['is_read' => 1]);
            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['error' => 'Invalid ID']);
    }
}
