<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Total Revenue</h6>
                <h2 class="fw-bold">Rp <?= number_format($stats['total_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Admin Income</h6>
                <h2 class="fw-bold">Rp <?= number_format($stats['admin_revenue'] ?? 0) ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <h6 class="card-title">Instructor Income</h6>
                <h2 class="fw-bold">Rp <?= number_format($stats['instructor_revenue'] ?? 0) ?></h2>
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
                        <th>User</th>
                        <th>Course</th>
                        <th>Amount</th>
                        <th>Admin Split</th>
                        <th>Instructor Split</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?= $payment['id'] ?></td>
                                <td><?= esc($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
                                <td><?= esc($payment['course_title']) ?></td>
                                <td>Rp <?= number_format($payment['amount']) ?></td>
                                <td class="text-success">Rp <?= number_format($payment['admin_revenue']) ?></td>
                                <td class="text-info">Rp <?= number_format($payment['instructor_revenue']) ?></td>
                                <td><?= date('M d, Y', $payment['date_added']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No payments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
