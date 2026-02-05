<?php

namespace App\Models;

class PaymentModel extends BaseModel
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'user_id', 'payment_type', 'course_id', 'amount', 'date_added',
        'last_modified', 'admin_revenue', 'instructor_revenue', 'tax',
        'instructor_payment_status', 'transaction_id', 'session_id', 'coupon'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'int';
    protected $createdField = 'date_added';
    protected $updatedField = 'last_modified';

    /**
     * Create payment record
     */
    public function createPayment($data)
    {
        $data['date_added'] = time();
        $data['last_modified'] = time();

        // Calculate revenue split
        if (!isset($data['admin_revenue']) || !isset($data['instructor_revenue'])) {
            $instructorPercentage = $this->get_settings('instructor_revenue') ?: 70;
            $adminPercentage = 100 - $instructorPercentage;

            $data['instructor_revenue'] = ($data['amount'] * $instructorPercentage) / 100;
            $data['admin_revenue'] = ($data['amount'] * $adminPercentage) / 100;
        }

        return $this->insert($data);
    }

    /**
     * Get payments by user
     */
    public function getUserPayments($userId)
    {
        return $this
            ->where('user_id', $userId)
            ->orderBy('date_added', 'DESC')
            ->findAll();
    }

    /**
     * Get payments for instructor
     */
    public function getInstructorPayments($instructorId, $status = null)
    {
        $db = \Config\Database::connect();
        $builder = $db
            ->table($this->table . ' p')
            ->select('p.*, c.title as course_title, u.first_name, u.last_name')
            ->join('courses c', 'c.id = p.course_id')
            ->join('users u', 'u.id = p.user_id')
            ->where('FIND_IN_SET(' . $instructorId . ', c.user_id) >', 0);

        if ($status !== null) {
            $builder->where('p.instructor_payment_status', $status);
        }

        return $builder
            ->orderBy('p.date_added', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get revenue statistics
     */
    public function getRevenueStats($startDate = null, $endDate = null, $instructorId = null)
    {
        $builder = $this->builder();

        if ($startDate) {
            $builder->where('date_added >=', $startDate);
        }

        if ($endDate) {
            $builder->where('date_added <=', $endDate);
        }

        if ($instructorId) {
            $db = \Config\Database::connect();
            $builder = $db
                ->table($this->table . ' p')
                ->join('courses c', 'c.id = p.course_id')
                ->where('FIND_IN_SET(' . $instructorId . ', c.user_id) >', 0);

            if ($startDate) {
                $builder->where('p.date_added >=', $startDate);
            }

            if ($endDate) {
                $builder->where('p.date_added <=', $endDate);
            }

            return [
                'total_revenue' => $builder->selectSum('p.instructor_revenue')->get()->getRow()->instructor_revenue ?? 0,
                'total_sales' => $builder->countAllResults(false),
                'by_date' => $builder
                    ->select('FROM_UNIXTIME(p.date_added, "%Y-%m-%d") as date, SUM(p.instructor_revenue) as revenue, COUNT(*) as sales')
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->get()
                    ->getResultArray()
            ];
        }

        return [
            'total_revenue' => $builder->selectSum('amount')->get()->getRow()->amount ?? 0,
            'admin_revenue' => $builder->selectSum('admin_revenue')->get()->getRow()->admin_revenue ?? 0,
            'instructor_revenue' => $builder->selectSum('instructor_revenue')->get()->getRow()->instructor_revenue ?? 0,
            'total_sales' => $builder->countAllResults(false),
            'by_date' => $builder
                ->select('FROM_UNIXTIME(date_added, "%Y-%m-%d") as date, SUM(amount) as revenue, COUNT(*) as sales')
                ->groupBy('date')
                ->orderBy('date', 'ASC')
                ->get()
                ->getResultArray()
        ];
    }

    /**
     * Mark instructor payment as paid
     */
    public function markInstructorPaid($paymentId)
    {
        return $this->update($paymentId, ['instructor_payment_status' => 1]);
    }
}
