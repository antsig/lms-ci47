<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\UserModel;

class Register extends BaseController
{
    protected $auth;
    protected $userModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->userModel = new UserModel();
        helper(['form', 'url']);
    }

    /**
     * Registration page
     */
    public function index()
    {
        // If already logged in, redirect
        if ($this->auth->isLoggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function process()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'terms' => 'required'
        ];

        $errors = [
            'email' => [
                'is_unique' => 'This email is already registered'
            ]
        ];

        if (!$this->validate($rules, $errors)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone'),
        ];

        $result = $this->auth->register($data);

        if ($result['success']) {
            // Auto login after registration
            $loginResult = $this->auth->login($data['email'], $this->request->getPost('password'));

            if ($loginResult['success']) {
                return redirect()->to('/student')->with('success', 'Registration successful! Welcome to our platform.');
            }

            return redirect()->to('/login')->with('success', 'Registration successful! Please login.');
        }

        return redirect()->back()->withInput()->with('error', $result['message']);
    }

    /**
     * Instructor registration page
     */
    public function instructor()
    {
        // If already logged in, redirect
        if ($this->auth->isLoggedIn()) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Become an Instructor',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register_instructor', $data);
    }

    /**
     * Process instructor registration
     */
    public function process_instructor()
    {
        $rules = [
            'first_name' => 'required|min_length[2]|max_length[50]',
            'last_name' => 'required|min_length[2]|max_length[50]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'phone' => 'required',
            'biography' => 'required|min_length[50]',
            'terms' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'phone' => $this->request->getPost('phone'),
            'biography' => $this->request->getPost('biography'),
            'skills' => $this->request->getPost('skills'),
            'role_id' => 2,
            'is_instructor' => 1,
            'status' => 0  // Pending approval
        ];

        $userId = $this->userModel->createUser($data);

        if ($userId) {
            return redirect()->to('/login')->with('success', 'Your instructor application has been submitted. Please wait for admin approval.');
        }

        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }
}
