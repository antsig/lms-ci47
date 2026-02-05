<?php

namespace App\Models;

class CourseModel extends BaseModel
{
    protected $table = 'courses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'title', 'short_description', 'description', 'outcomes', 'faqs',
        'language', 'category_id', 'sub_category_id', 'section', 'requirements',
        'price', 'discount_flag', 'discounted_price', 'level', 'user_id',
        'thumbnail', 'video_url', 'date_added', 'last_modified', 'course_type',
        'is_top_course', 'is_admin', 'status', 'course_overview_provider',
        'meta_keywords', 'meta_description', 'is_free_course', 'multi_instructor',
        'enable_drip_content', 'creator', 'expiry_period', 'certificate_id'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Get all active courses
     */
    public function getActiveCourses($limit = null, $categoryId = null, $level = null)
    {
        $builder = $this->where('status', 'active');

        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }

        if ($level) {
            $builder->where('level', $level);
        }

        if ($limit) {
            $builder->limit($limit);
        }

        $courses = $builder->orderBy('date_added', 'DESC')->findAll();

        return $this->enrichCoursesData($courses);
    }

    /**
     * Get latest courses
     */
    public function getLatestCourses($limit = 8)
    {
        $courses = $this
            ->where('status', 'active')
            ->limit($limit)
            ->orderBy('date_added', 'DESC')
            ->findAll();

        return $this->enrichCoursesData($courses);
    }

    /**
     * Get courses by category
     */
    public function getCoursesByCategory($categoryId, $limit = null)
    {
        $builder = $this
            ->where('category_id', $categoryId)
            ->where('status', 'active');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->orderBy('date_added', 'DESC')->findAll();
    }

    /**
     * Get courses by instructor
     */
    public function getCoursesByInstructor($userId, $status = null)
    {
        $builder = $this->where('FIND_IN_SET(' . $userId . ', user_id) >', 0);

        if ($status) {
            $builder->where('status', $status);
        }

        return $builder->orderBy('date_added', 'DESC')->findAll();
    }

    /**
     * Get top courses
     */
    public function getTopCourses($limit = 10)
    {
        $courses = $this
            ->where('is_top_course', 1)
            ->where('status', 'active')
            ->limit($limit)
            ->orderBy('date_added', 'DESC')
            ->findAll();

        return $this->enrichCoursesData($courses);
    }

    /**
     * Enrich courses with category and instructor names
     */
    private function enrichCoursesData($courses)
    {
        if (empty($courses)) {
            return $courses;
        }

        $categoryModel = new CategoryModel();
        $userModel = new UserModel();
        $enrollmentModel = new EnrollmentModel();
        $ratingModel = new \App\Models\RatingModel();

        foreach ($courses as &$course) {
            // Get category name
            $category = $categoryModel->find($course['category_id']);
            $course['category_name'] = $category['name'] ?? '';

            // Get instructor name
            $instructorIds = explode(',', $course['user_id']);
            $instructorNames = [];
            foreach ($instructorIds as $instructorId) {
                $instructor = $userModel->find(trim($instructorId));
                if ($instructor) {
                    $instructorNames[] = $instructor['first_name'] . ' ' . $instructor['last_name'];
                }
            }
            $course['instructor_name'] = implode(', ', $instructorNames);

            // Get enrollment count
            $course['enrollment_count'] = $enrollmentModel->where('course_id', $course['id'])->countAllResults();

            // Get rating
            $course['average_rating'] = $ratingModel->getAverageRating($course['id']);
            $course['rating_count'] = $ratingModel->getTotalRatings($course['id']);
        }

        return $courses;
    }

    /**
     * Get free courses
     */
    public function getFreeCourses($limit = null)
    {
        $builder = $this
            ->where('is_free_course', 1)
            ->where('status', 'active');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->orderBy('date_added', 'DESC')->findAll();
    }

    /**
     * Search courses
     */
    public function searchCourses($keyword, $categoryId = null, $priceRange = null)
    {
        $builder = $this->where('status', 'active');

        if (!empty($keyword)) {
            $builder
                ->groupStart()
                ->like('title', $keyword)
                ->orLike('short_description', $keyword)
                ->orLike('description', $keyword)
                ->groupEnd();
        }

        if ($categoryId) {
            $builder->where('category_id', $categoryId);
        }

        if ($priceRange == 'free') {
            $builder->where('is_free_course', 1);
        } elseif ($priceRange == 'paid') {
            $builder->where('is_free_course', 0);
        }

        return $builder->orderBy('date_added', 'DESC')->findAll();
    }

    /**
     * Get course with details (category, instructor, etc.)
     */
    public function getCourseDetails($courseId)
    {
        $course = $this->find($courseId);

        if (!$course) {
            return null;
        }

        // Get category
        $categoryModel = new CategoryModel();
        $category = $categoryModel->find($course['category_id']);
        $course['category_name'] = $category['name'] ?? '';

        // Get instructor(s)
        $userModel = new UserModel();
        $instructorIds = explode(',', $course['user_id']);
        $course['instructors'] = [];
        $instructorNames = [];

        foreach ($instructorIds as $instructorId) {
            $instructor = $userModel->find(trim($instructorId));
            if ($instructor) {
                $course['instructors'][] = $instructor;
                $instructorNames[] = $instructor['first_name'] . ' ' . $instructor['last_name'];
            }
        }

        $course['instructor_name'] = implode(', ', $instructorNames);
        $course['instructor_image'] = $course['instructors'][0]['image'] ?? 'default.jpg';

        // Get sections and lessons
        $sectionModel = new SectionModel();
        $lessonModel = new LessonModel();

        $sections = $sectionModel
            ->where('course_id', $courseId)
            ->orderBy('order', 'ASC')
            ->findAll();

        // Get lessons for each section
        $quizModel = new \App\Models\QuizModel();
        $assignmentModel = new \App\Models\AssignmentModel();

        foreach ($sections as &$section) {
            $section['lessons'] = $lessonModel
                ->where('section_id', $section['id'])
                ->orderBy('order', 'ASC')
                ->findAll();

            $section['quizzes'] = $quizModel->getQuizzesBySectionId($section['id']);
            $section['assignments'] = $assignmentModel->getAssignmentsBySectionId($section['id']);
        }

        $course['sections'] = $sections;
        $course['total_lessons'] = $lessonModel->where('course_id', $courseId)->countAllResults();

        // Get enrollment count
        $enrollmentModel = new EnrollmentModel();
        $course['enrollment_count'] = $enrollmentModel->where('course_id', $courseId)->countAllResults();

        // Get average rating
        $ratingModel = new \App\Models\RatingModel();
        $course['average_rating'] = $ratingModel->getAverageRating($courseId);
        $course['rating_count'] = $ratingModel->getTotalRatings($courseId);

        return $course;
    }

    /**
     * Create course
     */
    public function createCourse($data)
    {
        $data['date_added'] = time();
        $data['last_modified'] = time();
        $data['status'] = $data['status'] ?? 'pending';

        return $this->insert($data);
    }

    /**
     * Update course
     */
    public function updateCourse($id, $data)
    {
        $data['last_modified'] = time();
        return $this->update($id, $data);
    }

    /**
     * Get pending courses (for admin approval)
     */
    public function getPendingCourses()
    {
        return $this
            ->where('status', 'pending')
            ->orderBy('date_added', 'DESC')
            ->findAll();
    }

    /**
     * Approve course
     */
    public function approveCourse($courseId)
    {
        return $this->update($courseId, ['status' => 'active']);
    }

    /**
     * Get course price (considering discount)
     */
    public function getCoursePrice($courseId)
    {
        $course = $this->find($courseId);

        if (!$course) {
            return 0;
        }

        if ($course['is_free_course'] == 1) {
            return 0;
        }

        if ($course['discount_flag'] == 1 && $course['discounted_price'] > 0) {
            return $course['discounted_price'];
        }

        return $course['price'];
    }
}
