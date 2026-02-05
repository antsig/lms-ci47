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

        $data = [
            'title' => 'Instructor Dashboard',
            'courses' => $courses,
            'total_courses' => count($courses),
            'total_students' => $totalStudents,
            'total_revenue' => $revenueStats['total_revenue'] ?? 0,
            'recent_enrollments' => $this->enrollmentModel->getRecentEnrollments(10, $instructorId)
        ];

        return view('instructor/dashboard', $data);
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
            $thumbnail->move(WRITEPATH . '../public/uploads/thumbnails', $newName);
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
            $thumbnail->move(WRITEPATH . '../public/uploads/thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $this->courseModel->updateCourse($courseId, $data);

        return redirect()->back()->with('success', 'Course updated successfully');
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
            $attachment->move(WRITEPATH . '../public/uploads/lesson_files', $newName);
            $data['attachment'] = $newName;
        }

        $lessonId = $this->lessonModel->createLesson($data);

        if ($lessonId) {
            return redirect()->back()->with('success', 'Lesson added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add lesson');
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
}
