<?php

namespace App\Models;

class AssignmentModel extends BaseModel
{
    protected $table = 'assignments';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'title', 'course_id', 'section_id', 'description',
        'attachment_url', 'deadline',
        'date_added', 'last_modified'
    ];

    public function createAssignment($data)
    {
        $data['date_added'] = time();
        $this->insert($data);
        return $this->insertID();
    }

    public function updateAssignment($id, $data)
    {
        $data['last_modified'] = time();
        return $this->update($id, $data);
    }

    public function getAssignmentDetails($id)
    {
        return $this->find($id);
    }

    public function getAssignmentsBySectionId($sectionId)
    {
        return $this->where('section_id', $sectionId)->findAll();
    }
}
