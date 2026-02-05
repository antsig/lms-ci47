<?php

namespace App\Models;

class SectionModel extends BaseModel
{
    protected $table = 'sections';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'title',
        'course_id',
        'start_date',
        'end_date',
        'restricted_by',
        'order'
    ];

    /**
     * Get sections by course
     */
    public function getCourseSections($courseId)
    {
        return $this
            ->where('course_id', $courseId)
            ->orderBy('order', 'ASC')
            ->findAll();
    }

    /**
     * Get section with lessons
     */
    public function getSectionWithLessons($sectionId)
    {
        $section = $this->find($sectionId);

        if ($section) {
            $lessonModel = new LessonModel();
            $section['lessons'] = $lessonModel
                ->where('section_id', $sectionId)
                ->orderBy('order', 'ASC')
                ->findAll();
        }

        return $section;
    }

    /**
     * Create section
     */
    public function createSection($data)
    {
        // Get the highest order number for this course
        $maxOrder = $this
            ->where('course_id', $data['course_id'])
            ->selectMax('order')
            ->first();

        $data['order'] = ($maxOrder['order'] ?? 0) + 1;

        return $this->insert($data);
    }

    /**
     * Update section order
     */
    public function updateOrder($sectionId, $newOrder)
    {
        return $this->update($sectionId, ['order' => $newOrder]);
    }

    /**
     * Delete section and its lessons
     */
    public function deleteSection($sectionId)
    {
        // Delete all lessons in this section
        $lessonModel = new LessonModel();
        $lessonModel->where('section_id', $sectionId)->delete();

        // Delete the section
        return $this->delete($sectionId);
    }
}
