<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\AssignmentModel;
use App\Models\AssignmentSubmissionModel;
use App\Models\CourseModel;

class AssignmentController extends BaseController
{
    protected $auth;
    protected $assignmentModel;
    protected $submissionModel;
    protected $courseModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->assignmentModel = new AssignmentModel();
        $this->submissionModel = new AssignmentSubmissionModel();
        $this->courseModel = new CourseModel();
        helper(['form', 'url']);
    }

    /**
     * Add Assignment
     */
    public function add_assignment($courseId, $sectionId)
    {
        $rules = ['title' => 'required'];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Validation failed');
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'course_id' => $courseId,
            'section_id' => $sectionId,
            'description' => $this->request->getPost('description'),
            'deadline' => strtotime($this->request->getPost('deadline') ?? ''),
        ];

        // Handle attachment
        $attachment = $this->request->getFile('attachment');
        if ($attachment && $attachment->isValid()) {
            $newName = $attachment->getRandomName();
            $attachment->move(WRITEPATH . '../public/uploads/assignment_files', $newName);
            $data['attachment_url'] = $newName;
        }

        $this->assignmentModel->createAssignment($data);

        return redirect()->back()->with('success', 'Assignment added successfully');
    }

    /**
     * Edit Assignment
     */
    public function edit_assignment($assignmentId)
    {
        $assignment = $this->assignmentModel->getAssignmentDetails($assignmentId);
        if (!$assignment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $course = $this->courseModel->find($assignment['course_id']);

        $data = [
            'title' => 'Edit Assignment: ' . $assignment['title'],
            'assignment' => $assignment,
            'course' => $course
        ];

        if ($this->auth->isAdmin()) {
            return view('admin/edit_assignment', $data);
        }
        return view('instructor/edit_assignment', $data);
    }

    /**
     * Update Assignment
     */
    public function update_assignment($assignmentId)
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'deadline' => strtotime($this->request->getPost('deadline') ?? '')
        ];

        // Handle attachment
        $attachment = $this->request->getFile('attachment');
        if ($attachment && $attachment->isValid()) {
            $newName = $attachment->getRandomName();
            $attachment->move(WRITEPATH . '../public/uploads/assignment_files', $newName);
            $data['attachment_url'] = $newName;
        }

        $this->assignmentModel->updateAssignment($assignmentId, $data);

        return redirect()->back()->with('success', 'Assignment updated successfully');
    }

    /**
     * Delete Assignment
     */
    public function delete_assignment($assignmentId)
    {
        $this->assignmentModel->delete($assignmentId);
        return redirect()->back()->with('success', 'Assignment deleted');
    }
}
