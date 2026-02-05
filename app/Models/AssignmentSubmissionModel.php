<?php

namespace App\Models;

class AssignmentSubmissionModel extends BaseModel
{
    protected $table = 'assignment_submissions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'assignment_id', 'user_id', 'file_url',
        'note', 'grade', 'feedback', 'status', 'date_submitted'
    ];

    public function submitAssignment($data)
    {
        $data['date_submitted'] = time();
        $this->insert($data);
        return $this->insertID();
    }

    public function getSubmission($assignmentId, $userId)
    {
        return $this
            ->where('assignment_id', $assignmentId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getSubmissionsByAssignment($assignmentId)
    {
        return $this
            ->select('assignment_submissions.*, users.first_name, users.last_name, users.email')
            ->join('users', 'users.id = assignment_submissions.user_id')
            ->where('assignment_id', $assignmentId)
            ->findAll();
    }
}
