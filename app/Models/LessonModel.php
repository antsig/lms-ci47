<?php

namespace App\Models;

class LessonModel extends BaseModel
{
    protected $table = 'lessons';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'title', 'duration', 'course_id', 'section_id', 'video_type',
        'cloud_video_id', 'video_url', 'date_added', 'last_modified',
        'lesson_type', 'attachment', 'attachment_type', 'caption', 'summary',
        'is_free', 'order', 'quiz_attempt', 'video_type_for_mobile_application',
        'video_url_for_mobile_application', 'duration_for_mobile_application',
        'video_progression'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Get lessons by course
     */
    public function getCourseLessons($courseId)
    {
        return $this
            ->where('course_id', $courseId)
            ->orderBy('section_id', 'ASC')
            ->orderBy('order', 'ASC')
            ->findAll();
    }

    /**
     * Get lessons by section
     */
    public function getSectionLessons($sectionId)
    {
        return $this
            ->where('section_id', $sectionId)
            ->orderBy('order', 'ASC')
            ->findAll();
    }

    /**
     * Get free lessons for a course
     */
    public function getFreeLessons($courseId)
    {
        return $this
            ->where('course_id', $courseId)
            ->where('is_free', 1)
            ->orderBy('section_id', 'ASC')
            ->orderBy('order', 'ASC')
            ->findAll();
    }

    /**
     * Create lesson
     */
    public function createLesson($data)
    {
        // Get the highest order number for this section
        $maxOrder = $this
            ->where('section_id', $data['section_id'])
            ->selectMax('order')
            ->first();

        $data['order'] = ($maxOrder['order'] ?? 0) + 1;
        $data['date_added'] = time();
        $data['last_modified'] = time();

        return $this->insert($data);
    }

    /**
     * Update lesson
     */
    public function updateLesson($id, $data)
    {
        $data['last_modified'] = time();
        return $this->update($id, $data);
    }

    /**
     * Update lesson order
     */
    public function updateOrder($lessonId, $newOrder)
    {
        return $this->update($lessonId, ['order' => $newOrder]);
    }

    /**
     * Get total duration of course
     */
    public function getCourseTotalDuration($courseId)
    {
        $lessons = $this->where('course_id', $courseId)->findAll();
        $totalSeconds = 0;

        foreach ($lessons as $lesson) {
            if (!empty($lesson['duration'])) {
                // Convert duration to seconds (assuming format like "00:05:30")
                $parts = explode(':', $lesson['duration']);
                if (count($parts) == 3) {
                    $totalSeconds += ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
                }
            }
        }

        return $totalSeconds;
    }

    /**
     * Get lesson with quiz (if applicable)
     */
    public function getLessonWithQuiz($lessonId)
    {
        $lesson = $this->find($lessonId);

        if ($lesson && $lesson['lesson_type'] == 'quiz') {
            // Get quiz details
            $db = \Config\Database::connect();
            $lesson['quiz'] = $db
                ->table('questions')
                ->where('quiz_id', $lessonId)
                ->orderBy('order', 'ASC')
                ->get()
                ->getResultArray();
        }

        return $lesson;
    }

    /**
     * Get next lesson
     */
    public function getNextLesson($currentLessonId)
    {
        $currentLesson = $this->find($currentLessonId);

        if (!$currentLesson) {
            return null;
        }

        // Try to get next lesson in same section
        $nextLesson = $this
            ->where('section_id', $currentLesson['section_id'])
            ->where('order >', $currentLesson['order'])
            ->orderBy('order', 'ASC')
            ->first();

        if ($nextLesson) {
            return $nextLesson;
        }

        // Get next section's first lesson
        $sectionModel = new SectionModel();
        $currentSection = $sectionModel->find($currentLesson['section_id']);

        if ($currentSection) {
            $nextSection = $sectionModel
                ->where('course_id', $currentSection['course_id'])
                ->where('order >', $currentSection['order'])
                ->orderBy('order', 'ASC')
                ->first();

            if ($nextSection) {
                return $this
                    ->where('section_id', $nextSection['id'])
                    ->orderBy('order', 'ASC')
                    ->first();
            }
        }

        return null;
    }

    /**
     * Get previous lesson
     */
    public function getPreviousLesson($currentLessonId)
    {
        $currentLesson = $this->find($currentLessonId);

        if (!$currentLesson) {
            return null;
        }

        // Try to get previous lesson in same section
        $prevLesson = $this
            ->where('section_id', $currentLesson['section_id'])
            ->where('order <', $currentLesson['order'])
            ->orderBy('order', 'DESC')
            ->first();

        if ($prevLesson) {
            return $prevLesson;
        }

        // Get previous section's last lesson
        $sectionModel = new SectionModel();
        $currentSection = $sectionModel->find($currentLesson['section_id']);

        if ($currentSection) {
            $prevSection = $sectionModel
                ->where('course_id', $currentSection['course_id'])
                ->where('order <', $currentSection['order'])
                ->orderBy('order', 'DESC')
                ->first();

            if ($prevSection) {
                return $this
                    ->where('section_id', $prevSection['id'])
                    ->orderBy('order', 'DESC')
                    ->first();
            }
        }

        return null;
    }
}
