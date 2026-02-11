<?php

namespace App\Libraries;

use App\Models\UserModel;

class Auth
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
        helper(['cookie', 'text']);
    }

    /**
     * Login user
     */
    public function login($email, $password, $remember = false)
    {
        $user = $this->userModel->verifyLogin($email, $password);

        if ($user) {
            // Check if user is active
            if (isset($user['status']) && $user['status'] == 0) {
                return [
                    'success' => false,
                    'message' => 'Your account has been deactivated. Please contact administrator.'
                ];
            }

            // --- OTP FLOW START ---
            // Generate and Send OTP
            $otpSent = $this->sendOTP($user);

            if ($otpSent) {
                // Store temporary session for OTP verification
                $this->session->set('temp_otp_user_id', $user['id']);
                $this->session->set('temp_remember', $remember);

                $msg = 'Please enter the OTP sent to your email.';
                if (ENVIRONMENT === 'development' && is_string($otpSent)) {
                    $msg .= " (Dev Mode OTP: $otpSent)";
                }

                return [
                    'success' => true,
                    'otp_required' => true,
                    'message' => $msg
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to send OTP. Please check email configuration.'
                ];
            }
            // --- OTP FLOW END ---
        }

        return [
            'success' => false,
            'message' => 'Invalid email or password'
        ];
    }

    /**
     * Generate OTP
     */
    public function generateOTP($userId)
    {
        $otp = random_string('numeric', 6);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        $this->userModel->update($userId, [
            'otp_code' => $otp,
            'otp_expires_at' => $expiresAt
        ]);

        return $otp;
    }

    /**
     * Send OTP Email
     */
    public function sendOTP($user)
    {
        $otp = $this->generateOTP($user['id']);
        $email = \Config\Services::email();

        $email->setTo($user['email']);
        $email->setSubject('Login OTP - LMS');
        $email->setMessage("Your Login OTP is: <strong>$otp</strong>.<br>It expires in 10 minutes.");

        if ($email->send()) {
            return $otp;
        } else {
            log_message('error', 'Email Send Error: ' . $email->printDebugger(['headers']));
            // Dev Mode: Return OTP even if email fails
            if (ENVIRONMENT === 'development') {
                return $otp;
            }
            return false;
        }
    }

    /**
     * Verify OTP and Login
     */
    public function verifyOTP($otp)
    {
        $userId = $this->session->get('temp_otp_user_id');
        $remember = $this->session->get('temp_remember');

        if (!$userId) {
            return [
                'success' => false,
                'message' => 'Session expired. Please login again.'
            ];
        }

        $user = $this->userModel->find($userId);

        if ($user && $user['otp_code'] == $otp && strtotime($user['otp_expires_at']) > time()) {
            // Clear OTP
            $this->userModel->update($userId, ['otp_code' => null, 'otp_expires_at' => null]);

            // Clear temp session
            $this->session->remove(['temp_otp_user_id', 'temp_remember']);

            // --- ORIGINAL LOGIN LOGIC ---
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role_id' => $user['role_id'],
                'is_instructor' => $user['is_instructor'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'logged_in' => true
            ];

            $this->session->set($sessionData);

            // Handle remember me
            if ($remember) {
                $this->setRememberMe($user['id']);
            }

            return [
                'success' => true,
                'user' => $user
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid or expired OTP.'
        ];
    }

    /**
     * Verify OTP by Email (For Password Reset)
     */
    public function verifyOTPByEmail($email, $otp)
    {
        $user = $this->userModel->where('email', $email)->first();

        if ($user && $user['otp_code'] == $otp && strtotime($user['otp_expires_at']) > time()) {
            return $user;
        }

        return false;
    }

    /**
     * Reset Password (Actual update)
     */
    public function resetPasswordWithOTP($email, $otp, $newPassword)
    {
        $user = $this->verifyOTPByEmail($email, $otp);

        if ($user) {
            // Update password and clear OTP
            $this->userModel->update($user['id'], [
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'otp_code' => null,
                'otp_expires_at' => null
            ]);

            return [
                'success' => true,
                'message' => 'Password reset successfully. You can now login.'
            ];
        }

        return [
            'success' => false,
            'message' => 'Invalid or expired OTP.'
        ];
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $this->session->destroy();
        $this->clearRememberMe();
        return true;
    }

    /**
     * Check if user is logged in
     */
    public function isLoggedIn()
    {
        return $this->session->get('logged_in') === true;
    }

    /**
     * Get current user ID
     */
    public function getUserId()
    {
        return $this->session->get('user_id');
    }

    /**
     * Get current user data
     */
    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        $userId = $this->getUserId();
        return $this->userModel->find($userId);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->session->get('role_id') == 1;
    }

    /**
     * Check if user is instructor
     */
    public function isInstructor()
    {
        return $this->session->get('is_instructor') == 1;
    }

    /**
     * Check if user is student
     */
    public function isStudent()
    {
        return $this->session->get('role_id') == 2 && $this->session->get('is_instructor') == 0;
    }

    /**
     * Get user role
     */
    public function getRole()
    {
        if ($this->isAdmin()) {
            return 'admin';
        } elseif ($this->isInstructor()) {
            return 'instructor';
        } elseif ($this->isStudent()) {
            return 'student';
        }
        return 'guest';
    }

    /**
     * Register new user
     */
    public function register($data)
    {
        // Check if email already exists
        if ($this->userModel->emailExists($data['email'])) {
            return [
                'success' => false,
                'message' => 'Email already exists'
            ];
        }

        // Set default values
        $data['role_id'] = 2;  // Student role
        $data['is_instructor'] = 0;
        $data['status'] = 1;  // Active

        // Create user
        $userId = $this->userModel->createUser($data);

        if ($userId) {
            return [
                'success' => true,
                'message' => 'Registration successful',
                'user_id' => $userId
            ];
        }

        return [
            'success' => false,
            'message' => 'Registration failed. Please try again.'
        ];
    }

    /**
     * Generate verification code
     */
    public function generateVerificationCode($userId)
    {
        $code = rand(100000, 999999);
        $this->userModel->update($userId, ['verification_code' => $code]);
        return $code;
    }

    /**
     * Verify code
     */
    public function verifyCode($userId, $code)
    {
        $user = $this->userModel->find($userId);

        if ($user && $user['verification_code'] == $code) {
            // Clear verification code
            $this->userModel->update($userId, ['verification_code' => '']);
            return true;
        }

        return false;
    }

    /**
     * Set remember me cookie
     */
    private function setRememberMe($userId)
    {
        $token = bin2hex(random_bytes(32));
        $cookie = [
            'name' => 'remember_token',
            'value' => $token,
            'expire' => 60 * 60 * 24 * 30,  // 30 days
            'secure' => false,
            'httponly' => true
        ];

        set_cookie($cookie);

        // Store token in database (you may want to create a separate table for this)
        $this->userModel->update($userId, ['sessions' => json_encode(['remember_token' => $token])]);
    }

    /**
     * Clear remember me cookie
     */
    private function clearRememberMe()
    {
        delete_cookie('remember_token');
    }

    /**
     * Check remember me
     */
    public function checkRememberMe()
    {
        $token = get_cookie('remember_token');

        if ($token) {
            // Find user with this token
            $users = $this->userModel->findAll();

            foreach ($users as $user) {
                if (!empty($user['sessions'])) {
                    $sessions = json_decode($user['sessions'], true);
                    if (isset($sessions['remember_token']) && $sessions['remember_token'] === $token) {
                        // Auto login
                        $sessionData = [
                            'user_id' => $user['id'],
                            'email' => $user['email'],
                            'role_id' => $user['role_id'],
                            'is_instructor' => $user['is_instructor'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'logged_in' => true
                        ];

                        $this->session->set($sessionData);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Change password
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        if (!password_verify($oldPassword, $user['password'])) {
            return [
                'success' => false,
                'message' => 'Current password is incorrect'
            ];
        }

        $this->userModel->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);

        return [
            'success' => true,
            'message' => 'Password changed successfully'
        ];
    }

    /**
     * Reset password
     */
    public function resetPassword($email)
    {
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email not found'
            ];
        }

        // Generate verification code
        $code = $this->generateVerificationCode($user['id']);

        // Send email (you'll implement this later)
        // $this->sendPasswordResetEmail($email, $code);

        return [
            'success' => true,
            'message' => 'Password reset code sent to your email',
            'code' => $code  // Remove this in production
        ];
    }

    /**
     * Set new password with verification code
     */
    public function setNewPassword($email, $code, $newPassword)
    {
        $user = $this->userModel->getUserByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Email not found'
            ];
        }

        if (!$this->verifyCode($user['id'], $code)) {
            return [
                'success' => false,
                'message' => 'Invalid verification code'
            ];
        }

        $this->userModel->update($user['id'], ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);

        return [
            'success' => true,
            'message' => 'Password reset successful'
        ];
    }
}
