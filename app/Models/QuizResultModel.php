<?php

namespace App\Models;

class QuizResultModel extends BaseModel
{
    protected $table = 'quiz_results';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'quiz_id', 'user_id', 'user_answers',
        'correct_answers_count', 'total_questions',
        'score', 'date_submitted'
    ];

    public function submitResult($data)
    {
        $data['date_submitted'] = time();
        $this->insert($data);
        return $this->insertID();
    }

    public function getResult($quizId, $userId)
    {
        return $this
            ->where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getResultsByQuiz($quizId)
    {
        return $this
            ->select('quiz_results.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = quiz_results.user_id')
            ->where('quiz_id', $quizId)
            ->findAll();
    }
}
