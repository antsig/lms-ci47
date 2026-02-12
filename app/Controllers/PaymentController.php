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

        $course = $this->courseModel->find($courseId);
        if (!$course) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Check if instructor is enrolling in their own course
        if ($this->auth->getRole() === 'instructor' && in_array($userId, explode(',', $course['user_id']))) {
            return redirect()->back()->with('error', 'Anda tidak dapat mendaftar pada kursus Anda sendiri.');
        }

        // 1. Check if already enrolled
        if ($this->enrollmentModel->isEnrolled($userId, $courseId)) {
            return redirect()->to('/course/' . $courseId)->with('info', 'You are already enrolled in this course.');
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
        $serviceFee = 0;

        // QRIS 1.5% Fee Logic
        if ($paymentMethod == 'qris') {
            $adminFee = $price * 0.015;
        }

        // Service Fee Logic
        if ($paymentMethod != 'manual') {
            // Assuming Midtrans/Gateway fee.
            // This should ideally be dynamic or from settings.
            // For now, let's set a default or retrieve from settings if available.
            // $serviceFee = model('App\Models\BaseModel')->get_settings('midtrans_service_fee') ?: 0;
            // For this task, I'll initialize it to 0 and let the model/webhook handle exacts,
            // OR set a placeholder if requested. User asked to "deduct service fee from income".
            // Let's assume a fixed cost for now for calculation if not manual.
            $serviceFee = 0;  // Will be calculated in Model or via Webhook update in real scenario
        }

        $totalAmount = $price + $adminFee;  // Total user pays

        // Revenue Sharing Logic is handled in Model now

        $transactionId = 'TRANS-' . strtoupper(uniqid());

        $data = [
            'user_id' => $userId,
            'course_id' => $courseId,
            'amount' => $totalAmount,
            'admin_fee' => $adminFee,
            'service_fee' => $serviceFee,
            'tax' => 0,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'transaction_id' => $transactionId,
            'payment_date' => time(),
            'date_added' => time()
        ];

        // Use createPayment to handle revenue split and service fee logic
        $paymentId = $this->paymentModel->createPayment($data);

        return redirect()->to('/payment/instruction/' . $paymentId);
    }

    public function instruction($paymentId)
    {
        $payment = $this->paymentModel->find($paymentId);
        if (!$payment || ($payment['user_id'] != $this->auth->getUserId() && !$this->auth->isAdmin())) {
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
            $uploadPath = FCPATH . 'uploads/payment_proofs';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $file->getRandomName();
            $file->move($uploadPath, $newName);

            // Delete old file if exists
            if (!empty($payment['proof_file']) && file_exists(FCPATH . 'uploads/payment_proofs/' . $payment['proof_file'])) {
                unlink(FCPATH . 'uploads/payment_proofs/' . $payment['proof_file']);
            }

            $this->paymentModel->update($paymentId, [
                'proof_file' => $newName,
                'payment_status' => 'verification_pending',
                'last_modified' => time()
            ]);

            // --- NOTIFICATION LOGIC START ---
            $notificationModel = new \App\Models\NotificationModel();

            // Notify Admins
            $admins = $this->userModel->where('role_id', 1)->findAll();
            foreach ($admins as $admin) {
                $notificationModel->add(
                    $admin['id'],
                    'New Payment Proof',
                    'Transaction #' . $payment['transaction_id'] . ' submitted proof.',
                    'payment',
                    'admin/revenue'
                );
            }
            // --- NOTIFICATION LOGIC END ---

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

    public function history()
    {
        $userId = $this->auth->getUserId();

        // Fetch payments with course details explicitly manually or via model join if added
        // Since model has getUserPayments, let's use it or modify it to join
        $payments = $this
            ->paymentModel
            ->select('payments.*, courses.title as course_title, courses.thumbnail')
            ->join('courses', 'courses.id = payments.course_id')
            ->where('payments.user_id', $userId)
            ->orderBy('payments.date_added', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Transaction History',
            'payments' => $payments
        ];

        return view('payment/history', $data);
    }

    // --- Admin Transaction History ---
    public function admin_history()
    {
        // Ensure admin
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/');
        }

        $payments = $this
            ->paymentModel
            ->select('payments.*, courses.title as course_title, users.first_name, users.last_name, users.email')
            ->join('courses', 'courses.id = payments.course_id')
            ->join('users', 'users.id = payments.user_id')
            ->orderBy('payments.date_added', 'DESC')
            ->findAll();

        $data = [
            'title' => 'All Transactions',
            'payments' => $payments
        ];

        return view('admin/payment_history', $data);
    }

    public function admin_detail($paymentId)
    {
        if (!$this->auth->isAdmin()) {
            return redirect()->to('/');
        }

        $payment = $this
            ->paymentModel
            ->select('payments.*, courses.title as course_title, users.first_name, users.last_name, users.email')
            ->join('courses', 'courses.id = payments.course_id')
            ->join('users', 'users.id = payments.user_id')
            ->where('payments.id', $paymentId)
            ->first();

        if (!$payment) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Transaction Detail',
            'payment' => $payment
        ];

        return view('admin/payment_detail', $data);
    }
}
