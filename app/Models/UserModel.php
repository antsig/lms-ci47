<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'skills',
        'social_links',
        'biography',
        'role_id',
        'created_at',
        'updated_at',
        'wishlist',
        'title',
        'payment_keys',
        'verification_code',
        'status',
        'is_instructor',
        'image',
        'signature',
        'sessions'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';
    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    /**
     * Get user by email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    /**
     * Get user by ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        return $this->where('role_id', $roleId)->findAll();
    }

    /**
     * Get all students
     */
    public function getStudents()
    {
        return $this->where('role_id', 2)->where('is_instructor', 0)->findAll();
    }

    /**
     * Get all instructors
     */
    public function getInstructors()
    {
        return $this->where('is_instructor', 1)->findAll();
    }

    /**
     * Get all admins
     */
    public function getAdmins()
    {
        return $this->where('role_id', 1)->findAll();
    }

    /**
     * Create new user
     */
    public function createUser($data)
    {
        $data['date_added'] = time();
        $data['last_modified'] = time();

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        return $this->insert($data);
    }

    /**
     * Update user
     */
    public function updateUser($id, $data)
    {
        $data['last_modified'] = time();

        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        return $this->update($id, $data);
    }

    /**
     * Verify login credentials
     */
    public function verifyLogin($email, $password)
    {
        $user = $this->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Check if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $builder = $this->where('email', $email);

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Get user's wishlist
     */
    public function getWishlist($userId)
    {
        $user = $this->find($userId);

        if ($user && !empty($user['wishlist'])) {
            return json_decode($user['wishlist'], true);
        }

        return [];
    }

    /**
     * Add to wishlist
     */
    public function addToWishlist($userId, $courseId)
    {
        $wishlist = $this->getWishlist($userId);

        if (!in_array($courseId, $wishlist)) {
            $wishlist[] = $courseId;
            return $this->update($userId, ['wishlist' => json_encode($wishlist)]);
        }

        return true;
    }

    /**
     * Remove from wishlist
     */
    public function removeFromWishlist($userId, $courseId)
    {
        $wishlist = $this->getWishlist($userId);

        $key = array_search($courseId, $wishlist);
        if ($key !== false) {
            unset($wishlist[$key]);
            return $this->update($userId, ['wishlist' => json_encode(array_values($wishlist))]);
        }

        return true;
    }
}
