<?php

namespace App\Models;

class RatingModel extends BaseModel
{
    protected $table = 'ratings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'rating',
        'user_id',
        'course_id',
        'date_added',
        'last_modified',
        'review'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Add or update rating
     */
    public function addRating($userId, $courseId, $rating, $review = '')
    {
        // Check if user already rated
        $existing = $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        $data = [
            'rating' => $rating,
            'review' => $review,
            'last_modified' => time()
        ];

        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            $data['user_id'] = $userId;
            $data['course_id'] = $courseId;
            $data['date_added'] = time();
            return $this->insert($data);
        }
    }

    /**
     * Get average rating
     */
    public function getAverageRating($courseId)
    {
        $result = $this
            ->where('course_id', $courseId)
            ->selectAvg('rating')
            ->first();

        return round($result['rating'] ?? 0, 1);
    }

    /**
     * Get total ratings count
     */
    public function getTotalRatings($courseId)
    {
        return $this
            ->where('course_id', $courseId)
            ->countAllResults();
    }

    /**
     * Get ratings with user details
     */
    public function getRatingsWithUsers($courseId, $limit = null)
    {
        $db = \Config\Database::connect();
        $builder = $db
            ->table($this->table . ' r')
            ->select('r.*, u.first_name, u.last_name, u.image')
            ->join('users u', 'u.id = r.user_id')
            ->where('r.course_id', $courseId)
            ->orderBy('r.date_added', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistribution($courseId)
    {
        $distribution = [
            5 => 0,
            4 => 0,
            3 => 0,
            2 => 0,
            1 => 0
        ];

        $results = $this
            ->where('course_id', $courseId)
            ->select('FLOOR(rating) as star, COUNT(*) as count')
            ->groupBy('star')
            ->findAll();

        foreach ($results as $result) {
            $star = (int) $result['star'];
            if (isset($distribution[$star])) {
                $distribution[$star] = (int) $result['count'];
            }
        }

        return $distribution;
    }

    /**
     * Check if user has rated
     */
    public function hasUserRated($userId, $courseId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->countAllResults() > 0;
    }

    /**
     * Get user's rating
     */
    public function getUserRating($userId, $courseId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();
    }

    /**
     * Delete rating
     */
    public function deleteRating($ratingId)
    {
        return $this->delete($ratingId);
    }
}
