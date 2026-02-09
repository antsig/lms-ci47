<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\CategoryModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use App\Models\PaymentModel;
use App\Models\SectionModel;
use App\Models\UserModel;

class Instructor extends BaseController
{
    protected $auth;
    protected $courseModel;
    protected $categoryModel;
    protected $sectionModel;
    protected $lessonModel;
    protected $enrollmentModel;
    protected $paymentModel;
    protected $userModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->sectionModel = new SectionModel();
        $this->lessonModel = new LessonModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->paymentModel = new PaymentModel();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * Instructor dashboard
     */
    public function index()
    {
        $instructorId = $this->auth->getUserId();
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);

        $totalStudents = $this->enrollmentModel->getInstructorEnrollmentCount($instructorId);
        $revenueStats = $this->paymentModel->getRevenueStats(null, null, $instructorId);

        $user = $this->userModel->find($instructorId);

        $data = [
            'title' => 'Instructor Dashboard',
            'user' => $user,
            'courses' => $courses,
            'total_courses' => count($courses),
            'total_students' => $totalStudents,
            'total_revenue' => $revenueStats['total_revenue'] ?? 0,
            'recent_enrollments' => $this->enrollmentModel->getRecentEnrollments(10, $instructorId)
        ];

        return view('instructor/dashboard', $data);
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

        return view('instructor/profile', $data);
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
            'biography' => $this->request->getPost('biography')
        ];

        // Handle profile image upload
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/user_images', $newName);
            $data['image'] = $newName;

            // Delete old image if it exists
            $user = $this->auth->getUser();
            if (!empty($user['image']) && file_exists(FCPATH . 'uploads/user_images/' . $user['image'])) {
                unlink(FCPATH . 'uploads/user_images/' . $user['image']);
            }
        }

        // Handle signature upload
        $signature = $this->request->getFile('signature');
        if ($signature && $signature->isValid()) {
            $newSigName = $signature->getRandomName();
            $signature->move(FCPATH . 'uploads/signatures', $newSigName);
            $data['signature'] = $newSigName;

            // Delete old signature if it exists
            $user = $this->auth->getUser();
            if (!empty($user['signature']) && file_exists(FCPATH . 'uploads/signatures/' . $user['signature'])) {
                unlink(FCPATH . 'uploads/signatures/' . $user['signature']);
            }
        }

        $this->userModel->update($userId, $data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    /**
     * Change password form
     */
    public function change_password()
    {
        $data = [
            'title' => 'Change Password',
            'validation' => \Config\Services::validation()
        ];

        return view('instructor/change_password', $data);
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
     * My courses
     */
    public function courses()
    {
        $instructorId = $this->auth->getUserId();
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);

        $data = [
            'title' => 'My Courses',
            'courses' => $courses
        ];

        return view('instructor/courses', $data);
    }

    /**
     * Create course form
     */
    public function create_course()
    {
        $data = [
            'title' => 'Create New Course',
            'categories' => $this->categoryModel->getCategoryHierarchy(),
            'validation' => \Config\Services::validation()
        ];

        return view('instructor/create_course', $data);
    }

    /**
     * Store new course
     */
    public function store_course()
    {
        $rules = [
            'title' => 'required|min_length[5]',
            'category_id' => 'required|numeric',
            'level' => 'required',
            'language' => 'required',
            'price' => 'required|numeric',
            'short_description' => 'required|min_length[20]',
            'description' => 'required|min_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $instructorId = $this->auth->getUserId();

        $data = [
            'title' => $this->request->getPost('title'),
            'category_id' => $this->request->getPost('category_id'),
            'sub_category_id' => $this->request->getPost('sub_category_id'),
            'level' => $this->request->getPost('level'),
            'language' => $this->request->getPost('language'),
            'price' => $this->request->getPost('price'),
            'discount_flag' => $this->request->getPost('discount_flag') ? 1 : 0,
            'discounted_price' => $this->request->getPost('discounted_price'),
            'is_free_course' => $this->request->getPost('is_free_course') ? 1 : 0,
            'short_description' => $this->request->getPost('short_description'),
            'description' => $this->request->getPost('description'),
            'outcomes' => $this->request->getPost('outcomes'),
            'requirements' => $this->request->getPost('requirements'),
            'user_id' => $instructorId,
            'status' => 'pending'
        ];

        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $courseId = $this->courseModel->createCourse($data);

        if ($courseId) {
            return redirect()->to('/instructor/edit_course/' . $courseId)->with('success', 'Course created successfully. Now add sections and lessons.');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create course');
    }

    /**
     * Edit course
     */
    public function edit_course($courseId)
    {
        $course = $this->courseModel->getCourseDetails($courseId);

        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check ownership
        $instructorId = $this->auth->getUserId();
        if (!in_array($instructorId, explode(',', $course['user_id']))) {
            return redirect()->to('/instructor/courses')->with('error', 'Unauthorized access');
        }

        $data = [
            'title' => 'Edit Course',
            'course' => $course,
            'categories' => $this->categoryModel->getCategoryHierarchy(),
            'validation' => \Config\Services::validation()
        ];

        return view('instructor/edit_course', $data);
    }

    /**
     * Update course
     */
    public function update_course($courseId)
    {
        $rules = [
            'title' => 'required|min_length[5]',
            'category_id' => 'required|numeric',
            'level' => 'required',
            'language' => 'required',
            'price' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'category_id' => $this->request->getPost('category_id'),
            'sub_category_id' => $this->request->getPost('sub_category_id'),
            'level' => $this->request->getPost('level'),
            'language' => $this->request->getPost('language'),
            'price' => $this->request->getPost('price'),
            'discount_flag' => $this->request->getPost('discount_flag') ? 1 : 0,
            'discounted_price' => $this->request->getPost('discounted_price'),
            'is_free_course' => $this->request->getPost('is_free_course') ? 1 : 0,
            'short_description' => $this->request->getPost('short_description'),
            'description' => $this->request->getPost('description'),
            'outcomes' => $this->request->getPost('outcomes'),
            'requirements' => $this->request->getPost('requirements')
        ];

        // Handle thumbnail upload
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;

            // Delete old thumbnail
            $oldCourse = $this->courseModel->find($courseId);
            if (!empty($oldCourse['thumbnail']) && file_exists(FCPATH . 'uploads/thumbnails/' . $oldCourse['thumbnail'])) {
                unlink(FCPATH . 'uploads/thumbnails/' . $oldCourse['thumbnail']);
            }
        }

        $this->courseModel->updateCourse($courseId, $data);

        return redirect()->back()->with('success', 'Course updated successfully');
    }

    /**
     * Delete course
     */
    public function delete_course($courseId)
    {
        $course = $this->courseModel->find($courseId);

        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }

        // Check ownership
        $instructorId = $this->auth->getUserId();
        if (!in_array($instructorId, explode(',', $course['user_id']))) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        // Delete course (Hard delete as per model settings)
        // Model is set to useSoftDeletes = false, so this permanently removes it
        // We might need to manually delete related data (sections, lessons) if cascading is not set in DB
        // For CodeIgniter model delete(), we trust it deletes the row.

        // Explicitly delete related content if needed, but assuming DB cascade or simple Model usage:
        $this->courseModel->delete($courseId);

        return redirect()->to('/instructor/courses')->with('success', 'Course deleted successfully');
    }

    /**
     * Add section
     */
    public function add_section($courseId)
    {
        $data = [
            'title' => $this->request->getPost('title'),
            'course_id' => $courseId
        ];

        $sectionId = $this->sectionModel->createSection($data);

        if ($sectionId) {
            return redirect()->back()->with('success', 'Section added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add section');
    }

    /**
     * Add lesson
     */
    public function add_lesson($courseId, $sectionId)
    {
        $rules = [
            'title' => 'required',
            'lesson_type' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'title' => $this->request->getPost('title'),
            'course_id' => $courseId,
            'section_id' => $sectionId,
            'lesson_type' => $this->request->getPost('lesson_type'),
            'video_type' => $this->request->getPost('video_type'),
            'video_url' => $this->request->getPost('video_url'),
            'duration' => $this->request->getPost('duration'),
            'summary' => $this->request->getPost('summary'),
            'is_free' => $this->request->getPost('is_free') ? 1 : 0
        ];

        // Handle attachment upload
        $attachment = $this->request->getFile('attachment');
        if ($attachment && $attachment->isValid()) {
            $newName = $attachment->getRandomName();
            $attachment->move(FCPATH . 'uploads/lesson_files', $newName);
            $data['attachment'] = $newName;
        }

        $lessonId = $this->lessonModel->createLesson($data);

        if ($lessonId) {
            return redirect()->back()->with('success', 'Lesson added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add lesson');
    }

    /**
     * Courses the instructor is enrolled in
     */
    public function my_learning()
    {
        $userId = $this->auth->getUserId();
        $enrolledCourses = $this->enrollmentModel->getUserCourses($userId);

        $data = [
            'title' => 'My Learning',
            'courses' => $enrolledCourses
        ];

        return view('instructor/my_learning', $data);
    }

    /**
     * Students enrolled in instructor's courses
     */
    public function students()
    {
        $instructorId = $this->auth->getUserId();
        $courses = $this->courseModel->getCoursesByInstructor($instructorId);

        $allStudents = [];
        foreach ($courses as $course) {
            $students = $this->enrollmentModel->getCourseStudents($course['id']);
            foreach ($students as $student) {
                $student['course_title'] = $course['title'];
                $allStudents[] = $student;
            }
        }

        $data = [
            'title' => 'My Students',
            'students' => $allStudents
        ];

        return view('instructor/students', $data);
    }

    /**
     * Revenue report
     */
    public function revenue()
    {
        $instructorId = $this->auth->getUserId();
        $payments = $this->paymentModel->getInstructorPayments($instructorId);
        $stats = $this->paymentModel->getRevenueStats(null, null, $instructorId);

        $data = [
            'title' => 'Revenue Report',
            'payments' => $payments,
            'stats' => $stats
        ];

        return view('instructor/revenue', $data);
    }

    /**
     * Approve Payment
     */
    public function approve_payment($paymentId)
    {
        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }

        // Verify ownership
        $course = $this->courseModel->find($payment['course_id']);
        if ($course['user_id'] != $this->auth->getUserId()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Update Payment Status
        $this->paymentModel->update($paymentId, [
            'payment_status' => 'paid',
            'last_modified' => time()
        ]);

        // Create Enrollment if not exists
        $enrollmentModel = new \App\Models\EnrollmentModel();
        if (!$enrollmentModel->isEnrolled($payment['user_id'], $payment['course_id'])) {
            $enrollmentModel->enrollUser($payment['user_id'], $payment['course_id']);
        }

        return redirect()->back()->with('success', 'Payment approved and student enrolled.');
    }

    /**
     * Reject Payment
     */
    public function reject_payment($paymentId)
    {
        $payment = $this->paymentModel->find($paymentId);
        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }

        // Verify ownership
        $course = $this->courseModel->find($payment['course_id']);
        if ($course['user_id'] != $this->auth->getUserId()) {
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        // Update Payment Status
        $this->paymentModel->update($paymentId, [
            'payment_status' => 'failed',
            'last_modified' => time()
        ]);

        return redirect()->back()->with('success', 'Payment rejected.');
    }
}
