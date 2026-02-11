<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class SecurityController extends BaseController
{
    use ResponseTrait;

    protected $userModel;
    protected $auth;

    public function __construct()
    {
        $this->userModel = new \App\Models\UserModel();
        $this->auth = new \App\Libraries\Auth();
    }

    /**
     * Setup Authenticator (Returns QR Code)
     */
    public function setup_authenticator()
    {
        // Must be logged in
        if (!$this->auth->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = $this->auth->getUserId();
        $user = $this->userModel->find($userId);

        $totp = new \App\Libraries\TOTP();
        $secret = $totp->generateSecret();

        // Store secret in session temporarily for verification
        session()->set('temp_totp_secret', $secret);

        $systemName = $this->baseModel->get_settings('system_name') ?? 'LMS';
        $qrCodeUrl = $totp->getProvisioningUri($secret, $user['email'], $systemName);

        // Generate QR Code Image URL (using goqr.me API for simplicity)
        $qrImage = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrCodeUrl);

        return $this->response->setJSON([
            'secret' => $secret,
            'qr_image' => $qrImage,
            'manual_uri' => $qrCodeUrl
        ]);
    }

    /**
     * Verify Authenticator Setup
     */
    public function verify_authenticator()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $code = $this->request->getPost('code');
        $secret = session()->get('temp_totp_secret');

        if (!$secret) {
            return $this->response->setJSON(['success' => false, 'message' => 'Session expired. Please refresh.']);
        }

        $totp = new \App\Libraries\TOTP();
        if ($totp->verifyCode($secret, $code)) {
            // Success! Save secret to user
            $userId = $this->auth->getUserId();
            $this->userModel->update($userId, ['authenticator_secret' => $secret]);
            session()->remove('temp_totp_secret');

            return $this->response->setJSON(['success' => true, 'message' => 'Authenticator set up successfully!']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid code. Please try again.']);
    }

    /**
     * Disable Authenticator (Secure)
     */
    public function disable_authenticator()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $code = $this->request->getPost('code');
        $userId = $this->auth->getUserId();
        $user = $this->userModel->find($userId);

        if (!$code) {
            return $this->response->setJSON(['success' => false, 'message' => 'Please enter the code.']);
        }

        $isValid = false;

        // 1. Check Authenticator Code
        if (!empty($user['authenticator_secret'])) {
            $totp = new \App\Libraries\TOTP();
            if ($totp->verifyCode($user['authenticator_secret'], $code)) {
                $isValid = true;
            }
        }

        // 2. Check Email OTP (Fallback)
        $sessionOtp = session()->get('disable_auth_otp');
        if (!$isValid && $sessionOtp && $sessionOtp == $code) {
            $isValid = true;
        }

        if ($isValid) {
            // Disable it
            $this->userModel->update($userId, ['authenticator_secret' => null]);
            session()->remove('disable_auth_otp');

            return $this->response->setJSON(['success' => true, 'message' => 'Two-Factor Authentication disabled successfully.']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid code. Please try again.']);
    }

    /**
     * Send OTP for Disabling Authenticator
     */
    public function send_disable_otp()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $userId = $this->auth->getUserId();
        $user = $this->userModel->find($userId);

        // Generate random OTP
        $otp = random_string('numeric', 6);
        session()->set('disable_auth_otp', $otp);

        // Send Email
        $emailService = \Config\Services::email();
        $settings = $this->baseModel->get_settings();

        if (!empty($settings['smtp_host'])) {
            $config = [
                'protocol' => 'smtp',
                'SMTPHost' => $settings['smtp_host'],
                'SMTPUser' => $settings['smtp_user'] ?? '',
                'SMTPPass' => $settings['smtp_pass'] ?? '',
                'SMTPPort' => (int) ($settings['smtp_port'] ?? 587),
                'SMTPCrypto' => $settings['smtp_crypto'] ?? 'tls',
                'mailType' => 'html',
                'charset' => 'utf-8',
                'newline' => ("\r\n"),
                'wordWrap' => true
            ];
            $emailService->initialize($config);
        }

        $emailService->setTo($user['email']);
        $emailService->setFrom($settings['system_email'] ?? 'no-reply@lms.com', $settings['system_name'] ?? 'LMS');
        $emailService->setSubject('Security Verification Code');
        $emailService->setMessage("Your verification code to disable 2FA is: <strong>$otp</strong>");

        if ($emailService->send()) {
            $msg = 'Code sent to email.';
            if (ENVIRONMENT === 'development')
                $msg .= " (Dev: $otp)";
            return $this->response->setJSON(['success' => true, 'message' => $msg]);
        }

        // Return success in dev mode even if fail
        if (ENVIRONMENT === 'development') {
            return $this->response->setJSON(['success' => true, 'message' => "Email failed but Dev Code: $otp"]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to send email.']);
    }
}
