<?php

namespace App\Models;

class EnrollmentModel extends BaseModel
{
    protected $table = 'enrollments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id',
        'course_id',
        'gifted_by',
        'expiry_date',
        'completed_lessons',  // Added
        'progress',  // Added
        'date_added',
        'last_modified'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Check if user is enrolled in course
     */
    public function isEnrolled($userId, $courseId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->countAllResults() > 0;
    }

    /**
     * Enroll user in course
     */
    public function enrollUser($userId, $courseId, $giftedBy = 0, $expiryPeriod = null)
    {
        // Check if already enrolled
        if ($this->isEnrolled($userId, $courseId)) {
            return false;
        }

        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'gifted_by' => $giftedBy,
            'date_added' => time(),
            'last_modified' => time()
        ];

        // Calculate expiry date if expiry period is set
        if ($expiryPeriod) {
            $data['expiry_date'] = strtotime('+' . $expiryPeriod . ' days');
        }

        return $this->insert($data);
    }

    /**
     * Get user's enrolled courses
     */
    public function getUserCourses($userId)
    {
        $db = \Config\Database::connect();

        return $db
            ->table($this->table . ' e')
            ->select('e.*, c.*')
            ->join('courses c', 'c.id = e.course_id')
            ->where('e.user_id', $userId)
            ->orderBy('e.date_added', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get enrolled students for a course
     */
    public function getCourseStudents($courseId)
    {
        $db = \Config\Database::connect();

        return $db
            ->table($this->table . ' e')
            ->select('e.*, u.*')
            ->join('users u', 'u.id = e.user_id')
            ->where('e.course_id', $courseId)
            ->orderBy('e.date_added', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get enrollment count for course
     */
    public function getCourseEnrollmentCount($courseId)
    {
        return $this->where('course_id', $courseId)->countAllResults();
    }

    /**
     * Get enrollment count for instructor
     */
    public function getInstructorEnrollmentCount($instructorId)
    {
        $db = \Config\Database::connect();

        return $db
            ->table($this->table . ' e')
            ->join('courses c', 'c.id = e.course_id')
            ->where('FIND_IN_SET(' . $instructorId . ', c.user_id) >', 0)
            ->countAllResults();
    }

    /**
     * Get recent enrollments
     */
    public function getRecentEnrollments($limit = 10, $instructorId = null)
    {
        $db = \Config\Database::connect();
        $builder = $db
            ->table($this->table . ' e')
            ->select('e.*, c.title as course_title, u.first_name, u.last_name, u.email')
            ->join('courses c', 'c.id = e.course_id')
            ->join('users u', 'u.id = e.user_id');

        if ($instructorId) {
            $builder->where('FIND_IN_SET(' . $instructorId . ', c.user_id) >', 0);
        }

        return $builder
            ->orderBy('e.date_added', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Get all enrollment history
     */
    public function getEnrollmentHistory()
    {
        $db = \Config\Database::connect();
        return $db
            ->table($this->table . ' e')
            ->select('e.*, u.first_name, u.last_name, u.email, c.title as course_title')
            ->join('users u', 'u.id = e.user_id')
            ->join('courses c', 'c.id = e.course_id')
            ->orderBy('e.date_added', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Unenroll user from course
     */
    public function unenrollUser($userId, $courseId)
    {
        return $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->delete();
    }

    /**
     * Check if enrollment is expired
     */
    public function isEnrollmentExpired($userId, $courseId)
    {
        $enrollment = $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return true;
        }

        if ($enrollment['expiry_date'] && $enrollment['expiry_date'] < time()) {
            return true;
        }

        return false;
    }

    /**
     * Get enrollment statistics
     */
    public function getEnrollmentStats($startDate = null, $endDate = null)
    {
        $builder = $this->builder();

        if ($startDate) {
            $builder->where('date_added >=', $startDate);
        }

        if ($endDate) {
            $builder->where('date_added <=', $endDate);
        }

        return [
            'total' => $builder->countAllResults(false),
            'by_date' => $builder
                ->select('FROM_UNIXTIME(date_added, "%Y-%m-%d") as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->getResultArray()
        ];
    }

    /**
     * Update user progress
     */
    public function updateProgress($userId, $courseId, $itemId, $type = 'lesson')
    {
        $enrollment = $this
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return false;
        }

        $completedItems = [];
        if (!empty($enrollment['completed_lessons'])) {
            $completedItems = json_decode($enrollment['completed_lessons'], true) ?? [];
        }

        // Normalize item ID (e.g., "lesson_1", "quiz_5")
        $newItemKey = "{$type}_{$itemId}";

        // Add if not already completed (handle mixed types and legacy numeric IDs)
        $exists = false;
        foreach ($completedItems as $k => $item) {
            if ($item === $newItemKey) {
                $exists = true;
                break;
            }
            // Backward compatibility: legacy numeric IDs assumed to be lessons
            if (is_numeric($item) && $type == 'lesson' && $item == $itemId) {
                $completedItems[$k] = $newItemKey;  // Upgrade to new format
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            $completedItems[] = $newItemKey;
        }

        // Calculate progress
        $db = \Config\Database::connect();

        $totalLessons = $db
            ->table('lessons')
            ->join('sections', 'sections.id = lessons.section_id')
            ->where('sections.course_id', $courseId)
            ->countAllResults();

        $totalQuizzes = $db
            ->table('quizzes')
            ->join('sections', 'sections.id = quizzes.section_id')
            ->where('sections.course_id', $courseId)
            ->countAllResults();

        $totalAssignments = $db
            ->table('assignments')
            ->join('sections', 'sections.id = assignments.section_id')
            ->where('sections.course_id', $courseId)
            ->countAllResults();

        $totalItems = $totalLessons + $totalQuizzes + $totalAssignments;

        // Count unique completed items
        $uniqueCompleted = count(array_unique($completedItems));

        $progress = ($totalItems > 0) ? ($uniqueCompleted / $totalItems) * 100 : 0;
        $progress = min(100, round($progress));

        // Update enrollment
        return $this->update($enrollment['id'], [
            'completed_lessons' => json_encode(array_values($completedItems)),
            'progress' => $progress,
            'last_modified' => time()
        ]);
    }
}
