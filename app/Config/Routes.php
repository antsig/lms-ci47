<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// ==================== PUBLIC ROUTES ====================
$routes->get('/', 'HomeController::index');
$routes->get('/courses', 'HomeController::courses');
$routes->get('/course/(:num)', 'HomeController::course/$1');
$routes->get('/instructor/(:num)', 'HomeController::instructor/$1');
$routes->get('/search', 'HomeController::search');
$routes->get('/about', 'HomeController::about');
$routes->get('/contact', 'HomeController::contact');
$routes->post('/contact/submit', 'HomeController::process_contact');

// ==================== AUTHENTICATION ROUTES ====================
$routes->group('login', function ($routes) {
    $routes->get('/', 'Login::index');
    $routes->post('process', 'Login::process');
    $routes->get('logout', 'Login::logout');
    $routes->get('forgot-password', 'Login::forgot_password');
    $routes->post('forgot-password/process', 'Login::process_forgot_password');
    $routes->get('reset-password', 'Login::reset_password');
    $routes->post('reset-password/process', 'Login::process_reset_password');
});

$routes->group('register', function ($routes) {
    $routes->get('/', 'Register::index');
    $routes->post('process', 'Register::process');
    $routes->get('instructor', 'Register::instructor');
    $routes->post('instructor/process', 'Register::process_instructor');
});

// ==================== STUDENT ROUTES ====================
$routes->group('student', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Student::index');
    $routes->get('dashboard', 'Student::index');
    $routes->get('my-courses', 'Student::my_courses');
    $routes->get('course-player/(:num)', 'Student::course_player/$1');
    $routes->get('course-player/(:num)/(:num)', 'Student::course_player/$1/$2');
    $routes->get('course-player/(:num)/(:segment)/(:num)', 'Student::course_player/$1/$2/$3');
    $routes->get('profile', 'Student::profile');
    $routes->post('profile/update', 'Student::update_profile');
    $routes->get('change-password', 'Student::change_password');
    $routes->post('change-password/process', 'Student::process_change_password');
    $routes->get('wishlist', 'Student::wishlist');
    $routes->get('wishlist/add/(:num)', 'Student::add_to_wishlist/$1');
    $routes->get('wishlist/remove/(:num)', 'Student::remove_from_wishlist/$1');
    $routes->post('quiz/submit/(:num)', 'Student::submit_quiz/$1');
    $routes->post('assignment/submit/(:num)', 'Student::submit_assignment/$1');
    $routes->get('mark-complete/(:num)/(:num)', 'Student::mark_complete/$1/$2');
    $routes->get('certificate/(:num)', 'Student::certificate/$1');
});

// ==================== INSTRUCTOR ROUTES ====================
$routes->group('instructor', ['filter' => 'role:instructor'], function ($routes) {
    $routes->get('/', 'Instructor::index');
    $routes->get('dashboard', 'Instructor::index');
    $routes->get('courses', 'Instructor::courses');
    $routes->get('create-course', 'Instructor::create_course');
    $routes->post('store-course', 'Instructor::store_course');
    $routes->get('edit-course/(:num)', 'Instructor::edit_course/$1');
    $routes->post('update-course/(:num)', 'Instructor::update_course/$1');
    $routes->post('add-section/(:num)', 'Instructor::add_section/$1');
    $routes->post('add-lesson/(:num)/(:num)', 'Instructor::add_lesson/$1/$2');
    $routes->get('students', 'Instructor::students');
    $routes->get('revenue', 'Instructor::revenue');

    // Quiz Management
    $routes->post('add-quiz/(:num)/(:num)', 'QuizController::add_quiz/$1/$2');
    $routes->get('edit-quiz/(:num)', 'QuizController::edit_quiz/$1');
    $routes->post('update-quiz/(:num)', 'QuizController::update_quiz/$1');
    $routes->get('delete-quiz/(:num)', 'QuizController::delete_quiz/$1');
    $routes->post('quiz/add-question/(:num)', 'QuizController::add_question/$1');
    $routes->get('quiz/delete-question/(:num)', 'QuizController::delete_question/$1');

    // Assignment Management
    $routes->post('add-assignment/(:num)/(:num)', 'AssignmentController::add_assignment/$1/$2');
    $routes->get('edit-assignment/(:num)', 'AssignmentController::edit_assignment/$1');
    $routes->post('update-assignment/(:num)', 'AssignmentController::update_assignment/$1');
    $routes->get('delete-assignment/(:num)', 'AssignmentController::delete_assignment/$1');
});

// ==================== PAYMENT ROUTES ====================
$routes->group('payment', ['filter' => 'auth'], function ($routes) {
    $routes->get('checkout/(:num)', 'PaymentController::checkout/$1');
    $routes->post('process', 'PaymentController::process_checkout');
    $routes->get('instruction/(:num)', 'PaymentController::instruction/$1');
    $routes->post('submit-proof', 'PaymentController::submit_proof');
    $routes->get('success', 'PaymentController::success');
});

// ==================== ADMIN ROUTES ====================
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Admin::index');
    $routes->get('dashboard', 'Admin::index');

    // User Management
    $routes->get('users', 'Admin::users');
    $routes->get('users/(:alpha)', 'Admin::users/$1');
    $routes->post('add-user', 'Admin::add_user');
    $routes->get('edit-user/(:num)', 'Admin::edit_user/$1');
    $routes->post('update-user/(:num)', 'Admin::update_user/$1');
    $routes->get('delete-user/(:num)', 'Admin::delete_user/$1');

    // Category Management
    $routes->get('categories', 'Admin::categories');
    $routes->post('add-category', 'Admin::add_category');
    $routes->post('update-category/(:num)', 'Admin::update_category/$1');
    $routes->get('delete-category/(:num)', 'Admin::delete_category/$1');

    // Course Management
    $routes->get('courses', 'Admin::courses');
    $routes->get('courses/(:alpha)', 'Admin::courses/$1');
    $routes->get('create-course', 'Admin::create_course');
    $routes->post('store-course', 'Admin::store_course');
    $routes->get('edit-course/(:num)', 'Admin::edit_course/$1');
    $routes->post('update-course/(:num)', 'Admin::update_course/$1');
    $routes->post('add-section/(:num)', 'Admin::add_section/$1');
    $routes->post('add-lesson/(:num)/(:num)', 'Admin::add_lesson/$1/$2');
    $routes->get('delete-section/(:num)', 'Admin::delete_section/$1');
    $routes->get('delete-lesson/(:num)', 'Admin::delete_lesson/$1');
    $routes->get('approve-course/(:num)', 'Admin::approve_course/$1');
    $routes->get('course-status/(:num)/(:alpha)', 'Admin::change_course_status/$1/$2');
    $routes->get('delete-course/(:num)', 'Admin::delete_course/$1');

    // Enrollment Management
    $routes->get('enrollments', 'Admin::enrollments');
    $routes->post('enroll-student', 'Admin::enroll_student');

    // Revenue & Reports
    $routes->get('revenue', 'Admin::revenue');
    $routes->get('payment-requests', 'Admin::payment_requests');
    $routes->get('approve-payment/(:num)', 'Admin::approve_payment/$1');

    // Settings
    $routes->get('settings', 'Admin::settings');
    $routes->post('update-settings', 'Admin::update_settings');

    // Certificate Management
    $routes->get('certificates', 'CertificateController::index');
    $routes->get('certificates/create', 'CertificateController::create');
    $routes->post('certificates/store', 'CertificateController::store');
    $routes->get('certificates/edit/(:num)', 'CertificateController::edit/$1');
    $routes->post('certificates/update/(:num)', 'CertificateController::update/$1');
    $routes->get('certificates/delete/(:num)', 'CertificateController::delete/$1');

    // Quiz Management
    $routes->post('add-quiz/(:num)/(:num)', 'QuizController::add_quiz/$1/$2');
    $routes->get('edit-quiz/(:num)', 'QuizController::edit_quiz/$1');
    $routes->post('update-quiz/(:num)', 'QuizController::update_quiz/$1');
    $routes->get('delete-quiz/(:num)', 'QuizController::delete_quiz/$1');
    $routes->post('quiz/add-question/(:num)', 'QuizController::add_question/$1');
    $routes->get('quiz/delete-question/(:num)', 'QuizController::delete_question/$1');

    // Assignment Management
    $routes->post('add-assignment/(:num)/(:num)', 'AssignmentController::add_assignment/$1/$2');
    $routes->get('edit-assignment/(:num)', 'AssignmentController::edit_assignment/$1');
    $routes->post('update-assignment/(:num)', 'AssignmentController::update_assignment/$1');
    $routes->get('delete-assignment/(:num)', 'AssignmentController::delete_assignment/$1');
});
