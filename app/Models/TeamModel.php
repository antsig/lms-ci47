<?php

namespace App\Models;

class TeamModel extends BaseModel
{
    protected $table = 'team_members';
    protected $primaryKey = 'id';
    protected $allowCallbacks = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'int';

    protected $allowedFields = [
        'user_id',
        'name',
        'role',
        'image',
        'biography',
        'social_links',
        'display_order',
        'status',
        'created_at',
        'updated_at'
    ];

    /** Get all active team members for display */

    /**
     * Get all active team members for display
     */
    public function getTeamForDisplay()
    {
        // 1. Get Manual Members
        $members = $this
            ->where('status', 1)
            ->orderBy('display_order', 'ASC')
            ->findAll();

        $userModel = new UserModel();
        $existingUserIds = [];

        // Process manual members
        foreach ($members as &$member) {
            if (!empty($member['user_id'])) {
                $existingUserIds[] = $member['user_id'];
                $user = $userModel->find($member['user_id']);
                if ($user) {
                    // Pull data from user if not overridden in team member record
                    if (empty($member['name'])) {
                        $member['name'] = $user['first_name'] . ' ' . $user['last_name'];
                    }
                    if (empty($member['image'])) {
                        $member['image'] = $user['image'];
                    }
                    if (empty($member['biography'])) {
                        $member['biography'] = $user['biography'];
                    }
                    if (empty($member['social_links'])) {
                        $member['social_links'] = $user['social_links'];
                    }
                    if (empty($member['role'])) {
                        $member['role'] = !empty($user['title']) ? $user['title'] : 'Instructor';
                    }
                }
            }
        }
        unset($member);

        // 2. Fetch Admins and Instructors (Auto-include)
        // Get users who are Admins OR Instructors
        $autoUsers = $userModel
            ->groupStart()
            ->where('role_id', 1)
            ->orWhere('is_instructor', 1)
            ->groupEnd()
            ->where('status', 1)
            ->findAll();

        foreach ($autoUsers as $user) {
            // Skip if already in manual list
            if (in_array($user['id'], $existingUserIds)) {
                continue;
            }

            // Determine Role Label
            $roleLabel = 'Instructor';
            if ($user['role_id'] == 1) {
                $roleLabel = 'Administrator';
            }
            if (!empty($user['title'])) {
                $roleLabel = $user['title'];
            }

            // Add to members list
            $members[] = [
                'id' => null,  // No team_member ID
                'user_id' => $user['id'],
                'name' => $user['first_name'] . ' ' . $user['last_name'],
                'role' => $roleLabel,
                'image' => $user['image'] ?? null,
                'biography' => $user['biography'] ?? null,
                'social_links' => $user['social_links'] ?? null,
                'display_order' => 999,  // Appended at the end
                'status' => 1
            ];

            $existingUserIds[] = $user['id'];
        }

        return $members;
    }
}
