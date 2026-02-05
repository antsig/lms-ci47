<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use App\Models\UserModel;

class Student extends BaseController
{
    protected $auth;
    protected $quizModel;
    protected $assignmentModel;
    protected $questionModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->courseModel = new CourseModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->lessonModel = new LessonModel();
        $this->quizModel = new \App\Models\QuizModel();
        $this->assignmentModel = new \App\Models\AssignmentModel();
        $this->questionModel = new \App\Models\QuestionModel();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    // ... (index and my_courses omitted for brevity if unchanged)

    /**
     * Course player
     * URL patterns:
     * course-player/$courseId -> Load first item
     * course-player/$courseId/$lessonId -> Load lesson (Legacy/Numeric)
     * course-player/$courseId/$type/$id -> Load specific item
     */
    public function course_player($courseId, $param2 = null, $param3 = null)
    {
        $userId = $this->auth->getUserId();

        // Determine type and ID
        $type = 'lesson';
        $id = 0;

        if (is_numeric($param2) && $param3 === null) {
            $type = 'lesson';
            $id = $param2;
        } elseif (!empty($param2) && !empty($param3)) {
            $type = $param2;
            $id = $param3;
        }

        // Check if enrolled
        if (!$this->enrollmentModel->isEnrolled($userId, $courseId)) {
            return redirect()->to('/courses')->with('error', 'You are not enrolled in this course');
        }

        $course = $this->courseModel->getCourseDetails($courseId);

        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Auto-select first item if no specific item requested
        if ($id == 0 && !empty($course['sections'])) {
            // Priority: Lesson -> Quiz -> Assignment in first section
            $firstSection = $course['sections'][0];
            if (!empty($firstSection['lessons'])) {
                $type = 'lesson';
                $id = $firstSection['lessons'][0]['id'];
            } elseif (!empty($firstSection['quizzes'])) {
                $type = 'quiz';
                $id = $firstSection['quizzes'][0]['id'];
            } elseif (!empty($firstSection['assignments'])) {
                $type = 'assignment';
                $id = $firstSection['assignments'][0]['id'];
            }
        }

        $currentItem = null;
        // Fetch item details based on type
        if ($type == 'lesson') {
            $currentItem = $this->lessonModel->getLessonWithQuiz($id);  // method name might need checking
            if ($currentItem)
                $currentItem['item_type'] = 'lesson';
        } elseif ($type == 'quiz') {
            $currentItem = $this->quizModel->getQuizDetails($id);
            if ($currentItem) {
                $currentItem['item_type'] = 'quiz';
                $currentItem['questions'] = $this->questionModel->getQuestionsByQuizId($id);
            }
        } elseif ($type == 'assignment') {
            $currentItem = $this->assignmentModel->getAssignmentDetails($id);
            if ($currentItem)
                $currentItem['item_type'] = 'assignment';
        }

        $data = [
            'title' => $course['title'],
            'course' => $course,  // Contains full structure with quizzes/assignments now
            'current_item' => $currentItem,
            'current_type' => $type,
            'current_id' => $id,
            // Next/Prev logic needs update for mixed content, simple implementation for now:
            'next_lesson' => null,
            'prev_lesson' => null
        ];

        return view('student/course_player', $data);
    }

    /**
     * Student dashboard
     */
    public function index()
    {
        $userId = $this->auth->getUserId();
        $enrolledCourses = $this->enrollmentModel->getUserCourses($userId);

        $data = [
            'title' => 'My Dashboard',
            'enrolled_courses' => $enrolledCourses,
            'total_courses' => count($enrolledCourses),
            'user' => $this->auth->getUser()
        ];

        return view('student/dashboard', $data);
    }

    /**
     * My courses
     */
    public function my_courses()
    {
        $userId = $this->auth->getUserId();
        $enrolledCourses = $this->enrollmentModel->getUserCourses($userId);

        $data = [
            'title' => 'My Courses',
            'courses' => $enrolledCourses
        ];

        return view('student/my_courses', $data);
    }

    /**
     * Profile
     */
    public function profile()
    {
        $data = [
            'title' => 'My Profile',
            'user' => $this->auth->getUser(),
            'validation' => \Config\Services::validation()
        ];

        return view('student/profile', $data);
    }

    /**
     * Update profile
     */
    public function update_profile()
    {
        $userId = $this->auth->getUserId();

        $rules = [
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'phone' => 'permit_empty'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'biography' => $this->request->getPost('biography')
        ];

        // Handle profile image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $newName = $image->getRandomName();
            $image->move(WRITEPATH . '../public/uploads/user_images', $newName);
            $data['image'] = $newName;
        }

        $this->userModel->updateUser($userId, $data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Change password
     */
    public function change_password()
    {
        $data = [
            'title' => 'Change Password',
            'validation' => \Config\Services::validation()
        ];

        return view('student/change_password', $data);
    }

    /**
     * Process change password
     */
    public function process_change_password()
    {
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $userId = $this->auth->getUserId();
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        $result = $this->auth->changePassword($userId, $currentPassword, $newPassword);

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Wishlist
     */
    public function wishlist()
    {
        $userId = $this->auth->getUserId();
        $wishlistIds = $this->userModel->getWishlist($userId);

        $courses = [];
        if (!empty($wishlistIds)) {
            $courses = $this->courseModel->whereIn('id', $wishlistIds)->findAll();
        }

        $data = [
            'title' => 'My Wishlist',
            'courses' => $courses
        ];

        return view('student/wishlist', $data);
    }

    /**
     * Add to wishlist
     */
    public function add_to_wishlist($courseId)
    {
        $userId = $this->auth->getUserId();
        $this->userModel->addToWishlist($userId, $courseId);

        return redirect()->back()->with('success', 'Course added to wishlist');
    }

    /**
     * Remove from wishlist
     */
    public function remove_from_wishlist($courseId)
    {
        $userId = $this->auth->getUserId();
        $this->userModel->removeFromWishlist($userId, $courseId);

        return redirect()->back()->with('success', 'Course removed from wishlist');
    }

    /**
     * Submit Quiz
     */
    public function submit_quiz($quizId)
    {
        $userId = $this->auth->getUserId();
        $userAnswers = $this->request->getPost('answers');  // Array [question_id => answer_index]

        $questions = $this->questionModel->getQuestionsByQuizId($quizId);

        $correctCount = 0;
        $totalQuestions = count($questions);

        $storedAnswers = [];

        foreach ($questions as $question) {
            $qId = $question['id'];
            $correctIndices = json_decode($question['correct_answers'] ?? '[]', true);

            // Check if user answered
            if (isset($userAnswers[$qId])) {
                $userAns = (int) $userAnswers[$qId];
                $storedAnswers[$qId] = $userAns;

                if (in_array($userAns, $correctIndices)) {
                    $correctCount++;
                }
            } else {
                $storedAnswers[$qId] = null;
            }
        }

        $score = ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0;

        $data = [
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'user_answers' => json_encode($storedAnswers),
            'correct_answers_count' => $correctCount,
            'total_questions' => $totalQuestions,
            'score' => $score
        ];

        $resultModel = new \App\Models\QuizResultModel();
        $resultModel->submitResult($data);

        return redirect()->back()->with('success', "Quiz Submitted! You scored: {$score}%");
    }

    /**
     * Submit Assignment
     */
    public function submit_assignment($assignmentId)
    {
        $userId = $this->auth->getUserId();

        $rules = [
            'submission_file' => 'uploaded[submission_file]|max_size[submission_file,5120]|ext_in[submission_file,pdf,zip,doc,docx,jpg,png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', 'Invalid file type or size.');
        }

        $file = $this->request->getFile('submission_file');
        $fileName = '';

        if ($file->isValid() && !$file->hasMoved()) {
            $fileName = $file->getRandomName();
            $file->move(WRITEPATH . '../public/uploads/assignment_submissions', $fileName);
        }

        $data = [
            'assignment_id' => $assignmentId,
            'user_id' => $userId,
            'file_url' => $fileName,
            'note' => $this->request->getPost('note'),
            'status' => 'pending'
        ];

        $submissionModel = new \App\Models\AssignmentSubmissionModel();
        $submissionModel->submitAssignment($data);

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }
}
