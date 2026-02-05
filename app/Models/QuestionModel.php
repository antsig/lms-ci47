<?php

namespace App\Models;

class QuestionModel extends BaseModel
{
    protected $table = 'questions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'quiz_id', 'title', 'type', 'number_of_options',
        'options', 'correct_answers', 'order'
    ];

    public function createQuestion($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    public function getQuestionsByQuizId($quizId)
    {
        return $this
            ->where('quiz_id', $quizId)
            ->orderBy('order', 'ASC')
            ->findAll();
    }

    public function updateQuestion($id, $data)
    {
        return $this->update($id, $data);
    }
}
