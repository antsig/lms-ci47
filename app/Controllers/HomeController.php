<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\CategoryModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\UserModel;

class HomeController extends BaseController
{
    protected $courseModel;
    protected $categoryModel;
    protected $userModel;
    protected $enrollmentModel;
    protected $auth;

    public function __construct()
    {
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->auth = new Auth();
        helper(['form', 'url']);
    }

    /**
     * Homepage
     */
    public function index()
    {
        $topCourses = $this->courseModel->getTopCourses(6);
        $latestCourses = $this->courseModel->getLatestCourses(8);
        $categories = $this->categoryModel->getCategoryHierarchy();

        // Get stats
        $stats = [
            'total_courses' => $this->courseModel->countAll(),
            'total_students' => $this->userModel->where('role_id', 2)->where('is_instructor', 0)->countAllResults(),
            'total_instructors' => $this->userModel->where('is_instructor', 1)->countAllResults(),
            'total_enrollments' => $this->enrollmentModel->countAll()
        ];

        $data = [
            'title' => 'Home',
            'top_courses' => $topCourses,
            'latest_courses' => $latestCourses,
            'categories' => $categories,
            'stats' => $stats
        ];

        return view('home/index', $data);
    }

    /**
     * Course listing page
     */
    public function courses()
    {
        $category = $this->request->getGet('category');
        $level = $this->request->getGet('level');
        $price = $this->request->getGet('price');

        $courses = $this->courseModel->getActiveCourses(100, $category, $level);

        // Filter by price
        if ($price == 'free') {
            $courses = array_filter($courses, function ($course) {
                return $course['is_free_course'] == 1;
            });
        } elseif ($price == 'paid') {
            $courses = array_filter($courses, function ($course) {
                return $course['is_free_course'] == 0;
            });
        }

        $data = [
            'title' => 'Courses',
            'courses' => $courses,
            'categories' => $this->categoryModel->getCategoryHierarchy()
        ];

        return view('home/courses', $data);
    }

    /**
     * Course detail page
     */
    public function course($courseId)
    {
        $course = $this->courseModel->getCourseDetails($courseId);

        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $course['title'],
            'course' => $course
        ];

        return view('home/course_detail', $data);
    }

    /**
     * Instructor profile page
     */
    public function instructor($instructorId)
    {
        $instructor = $this->userModel->find($instructorId);

        if (!$instructor || $instructor['is_instructor'] != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $instructor['first_name'] . ' ' . $instructor['last_name'],
            'instructor' => $instructor,
            'courses' => $this->courseModel->getCoursesByInstructor($instructorId, 'active'),
            'total_students' => $this->enrollmentModel->getInstructorEnrollmentCount($instructorId)
        ];

        return view('home/instructor_profile', $data);
    }

    /**
     * Search courses
     */
    public function search()
    {
        $keyword = $this->request->getGet('q');

        if (empty($keyword)) {
            return redirect()->to('/courses');
        }

        $courses = $this->courseModel->searchCourses($keyword);

        $data = [
            'title' => 'Search Results for: ' . $keyword,
            'courses' => $courses,
            'search_query' => $keyword,
            'categories' => $this->categoryModel->getCategoryHierarchy()
        ];

        return view('home/courses', $data);
    }

    /**
     * About page
     */
    public function about()
    {
        $teamModel = new \App\Models\TeamModel();
        $featureModel = new \App\Models\FeatureModel();

        $data = [
            'title' => 'About Us',
            'team_members' => $teamModel->getTeamForDisplay(),
            'features' => $featureModel->getFeatures()
        ];

        return view('home/about', $data);
    }

    /**
     * Contact page
     */
    public function contact()
    {
        $settings = model('App\Models\BaseModel')->get_settings();

        $data = [
            'title' => 'Contact Us',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('home/contact', $data);
    }

    /**
     * Process contact form
     */
    public function process_contact()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'subject' => 'required',
            'message' => 'required|min_length[10]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Save to database
        $contactModel = new \App\Models\ContactModel();
        $contactModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'subject' => $this->request->getPost('subject'),
            'message' => $this->request->getPost('message'),
            'read_status' => 0,
            'created_at' => time()  // Model uses timestamps but let's be explicit or let model handle it.
            // Since I set useTimestamps=true in Model, I can omit this or pass it.
            // Model config: protected $dateFormat = 'int';
            // So CI4 should handle it if I don't pass it, but let's depend on standard save.
        ]);

        return redirect()->back()->with('success', 'Thank you for contacting us. We will get back to you soon.');
    }

    /**
     * Dashboard redirect
     */
    public function dashboard()
    {
        if (!$this->auth->isLoggedIn()) {
            return redirect()->to('/login');
        }

        if ($this->auth->isAdmin()) {
            return redirect()->to('/admin');
        } elseif ($this->auth->isInstructor()) {
            return redirect()->to('/instructor');
        } else {
            return redirect()->to('/student');
        }
    }
}
