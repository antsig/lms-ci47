<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\BaseModel;
use App\Models\CategoryModel;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\LessonModel;
use App\Models\PaymentModel;
use App\Models\SectionModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    protected $auth;
    protected $userModel;
    protected $courseModel;
    protected $categoryModel;
    protected $enrollmentModel;
    protected $paymentModel;
    protected $baseModel;
    protected $sectionModel;
    protected $lessonModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->userModel = new UserModel();
        $this->courseModel = new CourseModel();
        $this->categoryModel = new CategoryModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->paymentModel = new PaymentModel();
        $this->baseModel = new BaseModel();
        $this->sectionModel = new SectionModel();
        $this->lessonModel = new LessonModel();
        helper(['form', 'url']);
    }

    /**
     * Admin dashboard
     */
    public function index()
    {
        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $this->userModel->countAll(),
            'total_students' => $this->userModel->where('role_id', 2)->where('is_instructor', 0)->countAllResults(),
            'total_instructors' => $this->userModel->where('is_instructor', 1)->countAllResults(),
            'total_courses' => $this->courseModel->countAll(),
            'active_courses' => $this->courseModel->where('status', 'active')->countAllResults(),
            'pending_courses' => $this->courseModel->where('status', 'pending')->countAllResults(),
            'pending_payments' => $this->paymentModel->whereIn('payment_status', ['pending', 'verification_pending'])->countAllResults(),
            'total_enrollments' => $this->enrollmentModel->countAll(),
            'revenue_stats' => $this->paymentModel->getRevenueStats(),
            'recent_enrollments' => $this->enrollmentModel->getRecentEnrollments(10),
            'pending_courses_list' => $this->courseModel->getPendingCourses(),
            'pending_payments_list' => $this
                ->paymentModel
                ->select('payments.*, users.first_name, users.last_name, courses.title as course_title')
                ->join('users', 'users.id = payments.user_id')
                ->join('courses', 'courses.id = payments.course_id')
                ->whereIn('payment_status', ['pending', 'verification_pending'])
                ->orderBy('date_added', 'ASC')
                ->findAll()
        ];

        return view('admin/dashboard', $data);
    }

    /**
     * Admin Profile
     */
    public function profile()
    {
        $data = [
            'title' => 'My Profile',
            'user' => $this->auth->getUser(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/profile', $data);
    }

    /**
     * Update Admin Profile
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
        }

        // Handle signature upload
        $signature = $this->request->getFile('signature');
        if ($signature && $signature->isValid()) {
            $newSigName = $signature->getRandomName();
            $signature->move(FCPATH . 'uploads/signatures', $newSigName);
            $data['signature'] = $newSigName;

            // Delete old signature if it exists
            $user = $this->userModel->find($userId);
            if (!empty($user['signature']) && file_exists(FCPATH . 'uploads/signatures/' . $user['signature'])) {
                unlink(FCPATH . 'uploads/signatures/' . $user['signature']);
            }
        }

        $this->userModel->updateUser($userId, $data);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    // ==================== USER MANAGEMENT ====================

    /**
     * Manage users
     */
    public function users($role = 'all')
    {
        if ($role == 'students') {
            $users = $this->userModel->getStudents();
        } elseif ($role == 'instructors') {
            $users = $this->userModel->getInstructors();
        } elseif ($role == 'admins') {
            $users = $this->userModel->getAdmins();
        } else {
            $users = $this->userModel->findAll();
        }

        $title = 'All Users';
        if ($role == 'students') {
            $title = 'Manage Students';
        } elseif ($role == 'instructors') {
            $title = 'Manage Instructors';
        } elseif ($role == 'admins') {
            $title = 'Manage Admins';
        }

        $data = [
            'title' => $title,
            'users' => $users,
            'role_filter' => $role
        ];

        return view('admin/users', $data);
    }

    /**
     * Add user
     */
    public function add_user()
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'role_id' => $this->request->getPost('role_id'),
            'is_instructor' => $this->request->getPost('is_instructor') ? 1 : 0,
            'phone' => $this->request->getPost('phone'),
            'status' => 1
        ];

        $userId = $this->userModel->createUser($data);

        if ($userId) {
            return redirect()->back()->with('success', 'User added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add user');
    }

    /**
     * Edit user
     */
    public function edit_user($userId)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit User',
            'user' => $user,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/edit_user', $data);
    }

    /**
     * Update user
     */
    public function update_user($userId)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]"
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'role_id' => $this->request->getPost('role_id'),
            'is_instructor' => $this->request->getPost('is_instructor') ? 1 : 0,
            'phone' => $this->request->getPost('phone'),
            'status' => $this->request->getPost('status')
        ];

        // Update password if provided
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $this->userModel->updateUser($userId, $data);

        return redirect()->back()->with('success', 'User updated successfully');
    }

    /**
     * Delete user
     */
    public function delete_user($userId)
    {
        $this->userModel->delete($userId);
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    // ==================== CATEGORY MANAGEMENT ====================

    /**
     * Manage categories
     */
    public function categories()
    {
        $data = [
            'title' => 'Manage Categories',
            'categories' => $this->categoryModel->getCategoryHierarchy(),
            'parent_categories' => $this->categoryModel->getParentCategories(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/categories', $data);
    }

    /**
     * Add category
     */
    public function add_category()
    {
        $rules = [
            'name' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'parent' => $this->request->getPost('parent') ?: 0,
            'code' => strtoupper(substr($this->request->getPost('name'), 0, 3)),
            'font_awesome_class' => $this->request->getPost('font_awesome_class')
        ];

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/category_thumbnails', $newName);
            $data['thumbnail'] = $newName;
        }

        $categoryId = $this->categoryModel->createCategory($data);

        if ($categoryId) {
            return redirect()->back()->with('success', 'Category added successfully');
        }

        return redirect()->back()->with('error', 'Failed to add category');
    }

    /**
     * Update category
     */
    public function update_category($categoryId)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'parent' => $this->request->getPost('parent') ?: 0,
            'font_awesome_class' => $this->request->getPost('font_awesome_class')
        ];

        // Handle thumbnail
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail && $thumbnail->isValid()) {
            $newName = $thumbnail->getRandomName();
            $thumbnail->move(FCPATH . 'uploads/category_thumbnails', $newName);
            $data['thumbnail'] = $newName;

            // Delete old thumbnail
            $oldCategory = $this->categoryModel->find($categoryId);
            if (!empty($oldCategory['thumbnail']) && file_exists(FCPATH . 'uploads/category_thumbnails/' . $oldCategory['thumbnail'])) {
                unlink(FCPATH . 'uploads/category_thumbnails/' . $oldCategory['thumbnail']);
            }
        }

        $this->categoryModel->updateCategory($categoryId, $data);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    /**
     * Delete category
     */
    public function delete_category($categoryId)
    {
        $this->categoryModel->deleteCategory($categoryId);
        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    // ==================== COURSE MANAGEMENT ====================

    /**
     * Manage courses
     */
    public function courses($status = 'all')
    {
        if ($status == 'active') {
            $courses = $this->courseModel->where('status', 'active')->findAll();
        } elseif ($status == 'pending') {
            $courses = $this->courseModel->getPendingCourses();
        } elseif ($status == 'inactive') {
            $courses = $this->courseModel->where('status', 'inactive')->findAll();
        } else {
            $courses = $this->courseModel->findAll();
        }

        $data = [
            'title' => 'Manage Courses',
            'courses' => $courses,
            'status_filter' => $status
        ];

        return view('admin/courses', $data);
    }

    /**
     * Approve course
     */
    public function approve_course($courseId)
    {
        $this->courseModel->approveCourse($courseId);
        return redirect()->back()->with('success', 'Course approved successfully');
    }

    /**
     * Change course status
     */
    public function change_course_status($courseId, $status)
    {
        $this->courseModel->update($courseId, ['status' => $status]);
        return redirect()->back()->with('success', 'Course status updated successfully');
    }

    /**
     * Delete course
     */
    public function delete_course($courseId)
    {
        $this->courseModel->delete($courseId);
        return redirect()->back()->with('success', 'Course deleted successfully');
    }

    // ==================== ENROLLMENT MANAGEMENT ====================

    /**
     * Enrollment history
     */
    public function enrollments()
    {
        $data = [
            'title' => 'Enrollment History',
            'enrollments' => $this->enrollmentModel->getEnrollmentHistory(),
            'students' => $this->userModel->where('role_id', 2)->findAll(),
            'courses' => $this->courseModel->findAll()
        ];

        return view('admin/enrollments', $data);
    }

    /**
     * Enroll student manually
     */
    public function enroll_student()
    {
        $userId = $this->request->getPost('user_id');
        $courseId = $this->request->getPost('course_id');

        $result = $this->enrollmentModel->enrollUser($userId, $courseId);

        if ($result) {
            return redirect()->back()->with('success', 'Student enrolled successfully');
        }

        return redirect()->back()->with('error', 'Failed to enroll student or already enrolled');
    }

    // ==================== PAYMENT & REVENUE ====================

    /**
     * Revenue report
     */
    public function revenue()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        if ($startDate) {
            $startDate = strtotime($startDate);
        }
        if ($endDate) {
            $endDate = strtotime($endDate);
        }

        $stats = $this->paymentModel->getRevenueStats($startDate, $endDate);
        $stats = $this->paymentModel->getRevenueStats($startDate, $endDate);

        // Fix: Join users and courses to get names and titles
        $payments = $this
            ->paymentModel
            ->select('payments.*, users.first_name, users.last_name, courses.title as course_title')
            ->join('users', 'users.id = payments.user_id')
            ->join('courses', 'courses.id = payments.course_id')
            ->orderBy('payments.date_added', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Revenue Report',
            'stats' => $stats,
            'payments' => $payments
        ];

        return view('admin/revenue', $data);
    }

    // ==================== SETTINGS ====================

    /**
     * System settings
     */
    public function settings()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'System Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/settings', $data);
    }

    /**
     * Update settings
     */
    public function update_settings()
    {
        $settings = $this->request->getPost('settings');

        if ($settings) {
            foreach ($settings as $key => $value) {
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    /**
     * Page settings (Meta, Titles, etc.)
     */
    public function page_settings()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Page Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/page_settings', $data);
    }

    /**
     * Update page settings
     */
    public function update_page_settings()
    {
        $settings = $this->request->getPost('settings');

        if ($settings) {
            foreach ($settings as $key => $value) {
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Page settings updated successfully');
    }

    /**
     * Layout settings
     */
    public function layouting()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Layout Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/layouting', $data);
    }

    /** Update layout settings */

    /**
     * Update layout settings
     */
    public function update_layout()
    {
        $settings = $this->request->getPost('settings');

        // Checkboxes handling: if unchecked, they are not sent. We must forcefully set them to 'no' if missing.
        $checkboxes = ['show_hero', 'show_stats', 'show_top_courses', 'show_latest_courses', 'show_categories', 'show_cta'];

        if ($settings) {
            foreach ($checkboxes as $check) {
                if (!isset($settings[$check])) {
                    $settings[$check] = 'no';
                }
            }

            foreach ($settings as $key => $value) {
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Layout settings updated successfully');
    }

    /**
     * Icon settings
     */
    public function icons()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Manage Icons',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/icons', $data);
    }

    /**
     * Update icon settings
     */
    public function update_icons()
    {
        // This might just be an info page or a way to select an icon pack if supported.
        // For now, let's allow storing a CDN URL for custom icons.

        $settings = $this->request->getPost('settings');

        if ($settings) {
            foreach ($settings as $key => $value) {
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Icon settings updated successfully');
    }

    /**
     * Logo settings
     */
    public function logo()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Logo Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/logo', $data);
    }

    /** Update logo settings */

    /**
     * Update logo settings
     */
    public function update_logo()
    {
        $this->_handle_upload('system_logo', 'system_logo', 'uploads/system');
        $this->_handle_upload('favicon', 'favicon', 'uploads/system');

        return redirect()->back()->with('success', 'Logo settings updated successfully');
    }

    /**
     * Banner settings
     */
    public function banner()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Banner Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/banner', $data);
    }

    /**
     * Contact Page Settings
     */
    public function contact_settings()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'Contact Page Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/settings/contact', $data);
    }

    /**
     * Update Contact Settings
     */
    public function update_contact_settings()
    {
        $settings = $this->request->getPost('settings');

        if ($settings) {
            foreach ($settings as $key => $value) {
                // Ensure map iframe is cleaned if needed, but for now we trust admin
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'Contact settings updated successfully');
    }

    /**
     * Messages Inbox
     */
    public function messages()
    {
        $contactModel = new \App\Models\ContactModel();

        $data = [
            'title' => 'Inbox',
            'messages' => $contactModel->orderBy('created_at', 'DESC')->paginate(10),
            'pager' => $contactModel->pager
        ];

        return view('admin/messages/index', $data);
    }

    /**
     * Delete Message
     */
    public function delete_message($id)
    {
        $contactModel = new \App\Models\ContactModel();
        $contactModel->delete($id);
        return redirect()->back()->with('success', 'Message deleted successfully');
    }

    // ==================== FEATURES MANAGEMENT ====================

    /**
     * Features Management
     */
    public function features()
    {
        $featureModel = new \App\Models\FeatureModel();

        $data = [
            'title' => 'Website Features',
            'features' => $featureModel->orderBy('display_order', 'ASC')->findAll()
        ];

        return view('admin/features/index', $data);
    }

    public function create_feature()
    {
        if ($this->request->getMethod() == 'post') {
            $featureModel = new \App\Models\FeatureModel();

            $featureModel->save([
                'icon' => $this->request->getPost('icon'),
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'display_order' => $this->request->getPost('display_order')
            ]);

            return redirect()->to('/admin/features')->with('success', 'Feature created successfully');
        }

        $data = [
            'title' => 'Add Feature',
            'validation' => \Config\Services::validation()
        ];

        return view('admin/features/create', $data);
    }

    public function edit_feature($id)
    {
        $featureModel = new \App\Models\FeatureModel();
        $feature = $featureModel->find($id);

        if (!$feature) {
            return redirect()->to('/admin/features')->with('error', 'Feature not found');
        }

        if ($this->request->getMethod() == 'post') {
            $featureModel->save([
                'id' => $id,
                'icon' => $this->request->getPost('icon'),
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'display_order' => $this->request->getPost('display_order')
            ]);

            return redirect()->to('/admin/features')->with('success', 'Feature updated successfully');
        }

        $data = [
            'title' => 'Edit Feature',
            'feature' => $feature,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/features/edit', $data);
    }

    public function delete_feature($id)
    {
        $featureModel = new \App\Models\FeatureModel();
        $featureModel->delete($id);
        return redirect()->to('/admin/features')->with('success', 'Feature deleted successfully');
    }

    /**
     * Update banner settings
     */
    public function update_banner()
    {
        $this->_handle_upload('login_banner', 'login_banner', 'uploads/system');
        $this->_handle_upload('home_banner', 'home_banner', 'uploads/system');

        return redirect()->back()->with('success', 'Banner settings updated successfully');
    }

    /**
     * About Page settings
     */
    public function about()
    {
        $settings = $this->baseModel->get_settings();

        $data = [
            'title' => 'About Page Settings',
            'settings' => $settings,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/about', $data);
    }

    /**
     * Update about page settings
     */
    public function update_about()
    {
        $this->_handle_upload('about_us_image', 'about_us_image', 'uploads/system');

        $settings = $this->request->getPost('settings');
        if ($settings) {
            foreach ($settings as $key => $value) {
                $this->baseModel->update_settings($key, $value);
            }
        }

        return redirect()->back()->with('success', 'About page settings updated successfully');
    }

    // ==================== TEAM MANAGEMENT ====================

    /**
     * List Team Members
     */
    public function team()
    {
        $teamModel = new \App\Models\TeamModel();

        $data = [
            'title' => 'Team Management',
            'team_members' => $teamModel->orderBy('display_order', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/team/index', $data);
    }

    /**
     * Create Team Member Form
     */
    public function create_team_member()
    {
        $data = [
            'title' => 'Add Team Member',
            'instructors' => $this->userModel->getInstructors(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/team/create', $data);
    }

    /**
     * Store Team Member
     */
    public function store_team_member()
    {
        $rules = [
            'type' => 'required',  // 'instructor' or 'manual'
        ];

        if ($this->request->getPost('type') == 'instructor') {
            $rules['user_id'] = 'required|numeric';
        } else {
            $rules['name'] = 'required|min_length[3]';
            $rules['role'] = 'required';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'user_id' => $this->request->getPost('user_id') ?: null,
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'biography' => $this->request->getPost('biography'),
            'display_order' => $this->request->getPost('display_order') ?: 0,
            'status' => 1,
            'created_at' => time(),
            'updated_at' => time()
        ];

        // If instructor selected, try to pull data if fields are empty?
        // Logic: The View should probably handle pre-filling via JS, OR we just save the ID and let the Model handle display merging.
        // Let's rely on Model merging for display, but allow overrides if entered.

        // Handle image upload for manual entry or override
        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/team', $newName);
            $data['image'] = $newName;
        }

        $teamModel = new \App\Models\TeamModel();
        $teamModel->insert($data);

        return redirect()->to('/admin/team')->with('success', 'Team member added successfully');
    }

    /**
     * Edit Team Member
     */
    public function edit_team_member($id)
    {
        $teamModel = new \App\Models\TeamModel();
        $member = $teamModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/team')->with('error', 'Member not found');
        }

        $data = [
            'title' => 'Edit Team Member',
            'member' => $member,
            'instructors' => $this->userModel->getInstructors(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/team/edit', $data);
    }

    /**
     * Update Team Member
     */
    public function update_team_member($id)
    {
        $teamModel = new \App\Models\TeamModel();

        $data = [
            'user_id' => $this->request->getPost('user_id') ?: null,
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
            'biography' => $this->request->getPost('biography'),
            'display_order' => $this->request->getPost('display_order') ?: 0,
            'status' => $this->request->getPost('status'),
            'updated_at' => time()
        ];

        $image = $this->request->getFile('image');
        if ($image && $image->isValid()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/team', $newName);
            $data['image'] = $newName;

            // Delete old image if not external/user link (how to check? manually uploaded images are in 'uploads/team')
            $oldMember = $teamModel->find($id);
            if (!empty($oldMember['image']) && file_exists(FCPATH . 'uploads/team/' . $oldMember['image'])) {
                unlink(FCPATH . 'uploads/team/' . $oldMember['image']);
            }
        }

        $teamModel->update($id, $data);

        return redirect()->to('/admin/team')->with('success', 'Team member updated successfully');
    }

    /**
     * Delete Team Member
     */
    public function delete_team_member($id)
    {
        $teamModel = new \App\Models\TeamModel();
        $member = $teamModel->find($id);

        if ($member) {
            if (!empty($member['image']) && file_exists(FCPATH . 'uploads/team/' . $member['image'])) {
                unlink(FCPATH . 'uploads/team/' . $member['image']);
            }
            $teamModel->delete($id);
        }

        return redirect()->back()->with('success', 'Team member deleted successfully');
    }

    /**
     * Helper to handle file upload and delete old file
     */
    private function _handle_upload($inputName, $settingKey, $uploadPath)
    {
        $file = $this->request->getFile($inputName);

        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Get old file name
            $oldFile = $this->baseModel->get_settings($settingKey);

            // Generate new name
            $newName = $file->getRandomName();

            // Move new file
            if ($file->move(FCPATH . $uploadPath, $newName)) {
                // Update database
                $this->baseModel->update_settings($settingKey, $newName);

                // Delete old file if exists and not empty
                if (!empty($oldFile) && file_exists(FCPATH . $uploadPath . '/' . $oldFile)) {
                    unlink(FCPATH . $uploadPath . '/' . $oldFile);
                }
            }
        }
    }

    // ==================== COURSE CREATION (ADMIN) ====================

    /**
     * Create course form
     */
    public function create_course()
    {
        // Fetch certificates
        $certificateModel = new \App\Models\CertificateModel();
        $certificates = $certificateModel->findAll();

        $data = [
            'title' => 'Create New Course',
            'categories' => $this->categoryModel->getCategoryHierarchy(),
            'certificates' => $certificates,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/create_course', $data);
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

        // Admin creates course as themselves (User ID from Auth)
        $userId = $this->auth->getUserId();

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
            'certificate_id' => $this->request->getPost('certificate_id'),
            'user_id' => $userId,
            'status' => 'active'  // Admin created courses are active by default? Or pending? Let's say active.
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
            return redirect()->to('/admin/edit_course/' . $courseId)->with('success', 'Course created successfully.');
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

        // Admin can edit ANY course, no ownership check needed.

        // Fetch certificates
        $certificateModel = new \App\Models\CertificateModel();
        $certificates = $certificateModel->findAll();

        $data = [
            'title' => 'Edit Course',
            'course' => $course,
            'categories' => $this->categoryModel->getCategoryHierarchy(),
            'certificates' => $certificates,
            'validation' => \Config\Services::validation()
        ];

        return view('admin/edit_course', $data);
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
            'requirements' => $this->request->getPost('requirements'),
            'certificate_id' => $this->request->getPost('certificate_id')
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
            'summary' => $this->request->getPost('summary'),
            'video_progression' => $this->request->getPost('video_progression') ?? 0,
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
     * Delete section
     */
    public function delete_section($sectionId)
    {
        // Optional: Check if section has lessons, etc. Model usually handles generic delete.
        if ($this->sectionModel->delete($sectionId)) {
            return redirect()->back()->with('success', 'Section deleted successfully');
        }
        return redirect()->back()->with('error', 'Failed to delete section');
    }

    /**
     * Delete lesson
     */
    public function delete_lesson($lessonId)
    {
        if ($this->lessonModel->delete($lessonId)) {
            return redirect()->back()->with('success', 'Lesson deleted successfully');
        }
        return redirect()->back()->with('error', 'Failed to delete lesson');
    }

    /**
     * Get lesson details (AJAX)
     */
    public function get_lesson($lessonId)
    {
        $lesson = $this->lessonModel->find($lessonId);
        if ($lesson) {
            return $this->response->setJSON($lesson);
        }
        return $this->response->setStatusCode(404)->setJSON(['error' => 'Lesson not found']);
    }

    /**
     * Update lesson
     */
    public function update_lesson($lessonId)
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
            'lesson_type' => $this->request->getPost('lesson_type'),
            'video_type' => $this->request->getPost('video_type'),
            'video_url' => $this->request->getPost('video_url'),
            'duration' => $this->request->getPost('duration'),
            'summary' => $this->request->getPost('summary'),
            // 'drip_days' is now 'video_progression' (percentage)
            'video_progression' => $this->request->getPost('video_progression') ?? 0,
            'is_free' => $this->request->getPost('is_free') ? 1 : 0
        ];

        // Handle attachment upload
        $attachment = $this->request->getFile('attachment');
        if ($attachment && $attachment->isValid()) {
            $newName = $attachment->getRandomName();
            $attachment->move(FCPATH . 'uploads/lesson_files', $newName);
            $data['attachment'] = $newName;

            // Delete old attachment
            $oldLesson = $this->lessonModel->find($lessonId);
            if (!empty($oldLesson['attachment']) && file_exists(FCPATH . 'uploads/lesson_files/' . $oldLesson['attachment'])) {
                unlink(FCPATH . 'uploads/lesson_files/' . $oldLesson['attachment']);
            }
        }

        if ($this->lessonModel->updateLesson($lessonId, $data)) {
            return redirect()->back()->with('success', 'Lesson updated successfully');
        }

        return redirect()->back()->with('error', 'Failed to update lesson');
    }

    // ==================== PAYMENT VERIFICATION ====================

    /**
     * Pending Payment Requests
     */
    public function payment_requests()
    {
        $data = [
            'title' => 'Payment Requests',
            'payments' => $this
                ->paymentModel
                ->select('payments.*, users.first_name, users.last_name, users.email, courses.title as course_title')
                ->join('users', 'users.id = payments.user_id')
                ->join('courses', 'courses.id = payments.course_id')
                ->whereIn('payment_status', ['pending', 'verification_pending'])  // Show both
                ->orderBy('date_added', 'ASC')
                ->findAll()
        ];

        return view('admin/payment_requests', $data);
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

        // 1. Update Payment Status
        $this->paymentModel->update($paymentId, [
            'payment_status' => 'paid',
            'last_modified' => time()
        ]);

        // 2. Enroll Student
        // Check if already enrolled to avoid duplicates
        if (!$this->enrollmentModel->isEnrolled($payment['user_id'], $payment['course_id'])) {
            $this->enrollmentModel->enrollUser($payment['user_id'], $payment['course_id']);
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

        // Update Payment Status to cancelled or failed
        $this->paymentModel->update($paymentId, [
            'payment_status' => 'failed',  // or cancelled
            'last_modified' => time()
        ]);

        return redirect()->back()->with('success', 'Payment rejected.');
    }
}
