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

    /**
     * Get all active team members for display
     */
    public function getTeamForDisplay()
    {
        $members = $this
            ->where('status', 1)
            ->orderBy('display_order', 'ASC')
            ->findAll();

        // Process members to merge user data if user_id is set
        $userModel = new UserModel();
        foreach ($members as &$member) {
            if (!empty($member['user_id'])) {
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
                    // Role is usually specific to the "Team" display (e.g. "CEO" vs "Instructor"), so we might keep the team_member role.
                    // But if empty, we could default to 'Instructor'.
                    if (empty($member['role'])) {
                        $member['role'] = 'Instructor';
                    }
                }
            }
        }

        return $members;
    }
}
