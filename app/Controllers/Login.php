<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\WishlistModel;

class Login extends BaseController
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        helper(['form', 'url']);
    }

    /**
     * Login page
     */
    public function index()
    {
        // If already logged in, redirect based on role
        if ($this->auth->isLoggedIn()) {
            return $this->redirectBasedOnRole();
        }

        // Check remember me
        $this->auth->checkRememberMe();
        if ($this->auth->isLoggedIn()) {
            return $this->redirectBasedOnRole();
        }

        $data = [
            'title' => 'Login',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function process()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') ? true : false;

        $result = $this->auth->login($email, $password, $remember);

        if ($result['success']) {
            // --- Wishlist Redirect Logic ---
            $wishlistCourseId = session()->get('wishlist_redirect');
            if ($wishlistCourseId) {
                $userId = $this->auth->getUserId();
                $wishlistModel = new WishlistModel();
                $wishlistModel->addToWishlist($userId, $wishlistCourseId);
                session()->remove('wishlist_redirect');
            }
            // --- End Wishlist Redirect ---

            // Check for redirect URL
            $redirectUrl = session()->get('redirect_url');
            session()->remove('redirect_url');

            if ($redirectUrl) {
                $message = $wishlistCourseId ? 'Login successful! The course has been added to your wishlist.' : 'Login successful!';
                return redirect()->to($redirectUrl)->with('success', $message);
            }

            return $this->redirectBasedOnRole();
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->auth->logout();
        return redirect()->to('/login')->with('success', 'You have been logged out successfully');
    }

    /**
     * Redirect based on user role
     */
    private function redirectBasedOnRole()
    {
        if ($this->auth->isAdmin()) {
            return redirect()->to('/admin');
        } elseif ($this->auth->isInstructor()) {
            return redirect()->to('/instructor');
        } else {
            return redirect()->to('/student');
        }
    }

    /**
     * Forgot password page
     */
    public function forgot_password()
    {
        $data = [
            'title' => 'Forgot Password',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Process forgot password
     */
    public function process_forgot_password()
    {
        $rules = [
            'email' => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $result = $this->auth->resetPassword($email);

        if ($result['success']) {
            session()->set('reset_email', $email);
            return redirect()->to('/login/reset_password')->with('success', $result['message']);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Reset password page
     */
    public function reset_password()
    {
        $data = [
            'title' => 'Reset Password',
            'validation' => \Config\Services::validation(),
            'email' => session()->get('reset_email')
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Process reset password
     */
    public function process_reset_password()
    {
        $rules = [
            'email' => 'required|valid_email',
            'code' => 'required|numeric',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $code = $this->request->getPost('code');
        $password = $this->request->getPost('password');

        $result = $this->auth->setNewPassword($email, $code, $password);

        if ($result['success']) {
            session()->remove('reset_email');
            return redirect()->to('/login')->with('success', $result['message']);
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }
}
