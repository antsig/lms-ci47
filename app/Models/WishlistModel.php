<?php

namespace App\Models;

class WishlistModel extends BaseModel
{
    protected $table = 'wishlists';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'course_id',
        'date_added',
    ];

    // Dates
    protected $useTimestamps = false;

    public function isWishlisted($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->countAllResults() > 0;
    }

    public function addToWishlist($userId, $courseId)
    {
        if ($this->isWishlisted($userId, $courseId)) {
            return true; // Already in wishlist
        }

        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'date_added' => time(),
        ];

        return $this->insert($data);
    }

    public function removeFromWishlist($userId, $courseId)
    {
        return $this->where('user_id', $userId)
                    ->where('course_id', $courseId)
                    ->delete();
    }

    public function getWishlist($userId)
    {
        return $this->select('course_id')
                    ->where('user_id', $userId)
                    ->findAll();
    }

    public function getWishlistedCourses($userId)
    {
        return $this->select('courses.*, categories.name as category_name')
            ->join('courses', 'courses.id = wishlists.course_id')
            ->join('categories', 'categories.id = courses.category_id', 'left')
            ->where('wishlists.user_id', $userId)
            ->findAll();
    }
}
