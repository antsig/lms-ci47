<?php

namespace App\Models;

class QuizModel extends BaseModel
{
    protected $table = 'quizzes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'course_id', 'section_id', 'summary',
        'duration', 'total_marks',
        'date_added', 'last_modified'
    ];

    public function createQuiz($data)
    {
        $data['date_added'] = time();
        $this->insert($data);
        return $this->insertID();
    }

    public function updateQuiz($id, $data)
    {
        $data['last_modified'] = time();
        return $this->update($id, $data);
    }

    public function getQuizDetails($id)
    {
        return $this->find($id);
    }

    public function getQuizzesByCourseId($courseId)
    {
        return $this->where('course_id', $courseId)->findAll();
    }

    public function getQuizzesBySectionId($sectionId)
    {
        return $this->where('section_id', $sectionId)->findAll();
    }
}
