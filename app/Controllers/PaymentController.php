<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Models\CourseModel;
use App\Models\EnrollmentModel;
use App\Models\PaymentModel;
use App\Models\UserModel;

class PaymentController extends BaseController
{
    protected $auth;
    protected $courseModel;
    protected $paymentModel;
    protected $enrollmentModel;
    protected $userModel;

    public function __construct()
    {
        $this->auth = new Auth();
        $this->courseModel = new CourseModel();
        $this->paymentModel = new PaymentModel();
        $this->enrollmentModel = new EnrollmentModel();
        $this->userModel = new UserModel();
        helper(['form', 'url', 'number']);
    }

    public function checkout($courseId)
    {
        $userId = $this->auth->getUserId();

        // 1. Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            return redirect()->to('/course/' . $courseId)->with('info', 'You are already enrolled in this course.');
        }

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // 2. If free course, direct enroll
        if ($course['is_free_course']) {
            return $this->processFreeEnrollment($userId, $courseId);
        }

        $data = [
            'title' => 'Checkout - ' . $course['title'],
            'course' => $course,
            'user' => $this->auth->getUser()
        ];

        return view('payment/checkout', $data);
    }

    private function processFreeEnrollment($userId, $courseId)
    {
        // Add enrollment
        $this->enrollmentModel->enrollUser($userId, $courseId);
        return redirect()->to('/student/course-player/' . $courseId)->with('success', 'Enrolled successfully!');
    }

    public function process_checkout()
    {
        $courseId = $this->request->getPost('course_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $userId = $this->auth->getUserId();

        $course = $this->courseModel->find($courseId);

        // Price Calculation
        $price = $course['discount_flag'] ? $course['discounted_price'] : $course['price'];
        $adminFee = 0;

        // QRIS 1.5% Fee Logic
        if ($paymentMethod == 'qris') {
            $adminFee = $price * 0.015;
        }

        $totalAmount = $price + $adminFee;  // Total user pays

        // Revenue Sharing Logic (Example: 20% Admin, 80% Instructor)
        // Admin Revenue = (Price * 20%) + Admin Fee
        // Instructor Revenue = Price * 80%

        // Get settings for percentage (default 20 if not set)
        $adminPercentage = 20;  // TODO: Get from settings
        $instructorPercentage = 100 - $adminPercentage;

        $adminShareFromPrice = ($price * $adminPercentage) / 100;
        $instructorRevenue = ($price * $instructorPercentage) / 100;

        $totalAdminRevenue = $adminShareFromPrice + $adminFee;

        $transactionId = 'TRANS-' . strtoupper(uniqid());

        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'amount' => $totalAmount,  // Total transaction amount
            'admin_revenue' => $totalAdminRevenue,
            'instructor_revenue' => $instructorRevenue,
            'admin_fee' => $adminFee,
            'tax' => 0,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'transaction_id' => $transactionId,
            'payment_date' => time(),
            'date_added' => time()
        ];

        $this->paymentModel->insert($data);
        $paymentId = $this->paymentModel->getInsertID();

        return redirect()->to('/payment/instruction/' . $paymentId);
    }

    public function instruction($paymentId)
    {
        $payment = $this->paymentModel->find($paymentId);
        if (!$payment || $payment['user_id'] != $this->auth->getUserId()) {
            return redirect()->to('/')->with('error', 'Invalid payment record.');
        }

        // Get Course Info
        $course = $this->courseModel->find($payment['course_id']);

        $data = [
            'title' => 'Payment Instruction',
            'payment' => $payment,
            'course' => $course,
            'validation' => \Config\Services::validation()
        ];

        return view('payment/instruction', $data);
    }

    public function submit_proof()
    {
        $paymentId = $this->request->getPost('payment_id');
        $payment = $this->paymentModel->find($paymentId);

        if (!$payment || $payment['user_id'] != $this->auth->getUserId()) {
            return redirect()->back()->with('error', 'Invalid request.');
        }

        $validationRule = [
            'proof_file' => [
                'label' => 'Payment Proof',
                'rules' => 'uploaded[proof_file]|is_image[proof_file]|max_size[proof_file,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('proof_file');
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(WRITEPATH . '../public/uploads/payment_proofs', $newName);

            $this->paymentModel->update($paymentId, [
                'proof_file' => $newName,
                'payment_status' => 'verification_pending',
                'last_modified' => time()
            ]);

            return redirect()->to('/payment/success');
        }

        return redirect()->back()->with('error', 'Failed to upload file.');
    }

    public function success()
    {
        $data = [
            'title' => 'Payment Submitted'
        ];
        return view('payment/success', $data);
    }
}
