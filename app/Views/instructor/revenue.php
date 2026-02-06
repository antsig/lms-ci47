<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor') ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/instructor/courses') ?>">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/instructor/create-course') ?>">
    <i class="fas fa-plus-circle"></i> Create Course
</a>
<a href="<?= base_url('/instructor/students') ?>">
    <i class="fas fa-users"></i> Students
</a>
<a href="<?= base_url('/instructor/revenue') ?>" class="active">
    <i class="fas fa-dollar-sign"></i> Revenue
</a>
<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Total Earnings</h6>
                <h2 class="fw-bold">Rp <?= number_format($stats['instructor_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <h6 class="card-title">Pending Revenue</h6>
                <h2 class="fw-bold">Rp 0</h2>
                <small>Payment is being processed</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Payment History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Your Share</th>
                        <th>Date</th>
                        <th>Student Status</th>
                        <th>Payout Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment['id'] ?></td>
                                <td><?= esc($payment['course_title']) ?></td>
                                <td>Rp <?= number_format($payment['amount']) ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($payment['instructor_revenue']) ?></td>
                                <td><?= date('M d, Y', $payment['date_added']) ?></td>
                                <td>
                                    <?php
                                    $pStatus = $payment['payment_status'];
                                    $badge = 'bg-secondary';
                                    if ($pStatus == 'paid')
                                        $badge = 'bg-success';
                                    elseif ($pStatus == 'pending')
                                        $badge = 'bg-warning text-dark';
                                    elseif ($pStatus == 'verification_pending')
                                        $badge = 'bg-info text-dark';
                                    elseif ($pStatus == 'failed')
                                        $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= ucfirst($pStatus) ?></span>
                                </td>
                                <td>
                                    <?php if ($payment['instructor_payment_status']): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payment['payment_status'] == 'verification_pending' || $payment['payment_status'] == 'pending'): ?>
                                        <a href="<?= base_url('instructor/approve-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('Approve this payment?');"
                                           title="Approve">
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <a href="<?= base_url('instructor/reject-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Reject this payment?');"
                                           title="Reject">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No payments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
