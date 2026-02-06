<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_users ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Users</p>
                </div>
                <div class="icon primary">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_courses ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Courses</p>
                </div>
                <div class="icon success">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_enrollments ?? 0 ?></h3>
                    <p class="text-muted mb-0">Enrollments</p>
                </div>
                <div class="icon warning">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($revenue_stats['total_revenue'] ?? 0) ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
                <div class="icon danger">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Students</h6>
                <h4 class="fw-bold"><?= $total_students ?? 0 ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Instructors</h6>
                <h4 class="fw-bold"><?= $total_instructors ?? 0 ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending Courses</h6>
                <h4 class="fw-bold text-warning"><?= $pending_courses ?? 0 ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pending Payments</h6>
                <h4 class="fw-bold text-danger"><?= $pending_payments ?? 0 ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pending Courses -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Pending Course Approvals</h5>
                    <a href="<?= base_url('/admin/courses/pending') ?>" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($pending_courses_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Instructor</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($pending_courses_list, 0, 5) as $course): ?>
                                    <tr>
                                        <td>
                                            <strong><?= esc($course['title']) ?></strong>
                                            <br><small class="text-muted"><?= esc($course['category_name'] ?? '') ?></small>
                                        </td>
                                        <td><?= esc($course['instructor_name'] ?? 'Instructor') ?></td>
                                        <td><?= date('M d, Y', $course['date_added'] ?? time()) ?></td>
                                        <td>
                                            <a href="<?= base_url('/admin/approve-course/' . $course['id']) ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Approve
                                            </a>
                                            <a href="<?= base_url('/course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No pending courses</p>
                <?php endif; ?>
            </div>
        </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-danger">Pending Payment Approvals</h5>
                    <a href="<?= base_url('/admin/payment-requests') ?>" class="btn btn-sm btn-outline-danger">View All</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($pending_payments_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($pending_payments_list, 0, 5) as $pay): ?>
                                    <tr>
                                        <td>
                                            <span class="font-monospace small bg-light px-2 py-1 rounded">#<?= esc($pay['transaction_id']) ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($pay['first_name'] . ' ' . $pay['last_name']) ?></div>
                                        </td>
                                        <td><?= esc($pay['course_title']) ?></td>
                                        <td class="fw-bold">Rp <?= number_format($pay['amount']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/payment-detail/' . $pay['id']) ?>" class="btn btn-sm btn-primary">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-4">No pending payments</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Enrollments -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Recent Enrollments</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_enrollments)): ?>
                    <ul class="list-unstyled">
                        <?php foreach (array_slice($recent_enrollments, 0, 8) as $enrollment): ?>
                            <li class="mb-3 pb-3 border-bottom">
                                <strong><?= esc($enrollment['student_name'] ?? 'Student') ?></strong>
                                <br><small class="text-muted"><?= esc(substr($enrollment['course_title'] ?? '', 0, 30)) ?></small>
                                <br><small class="text-muted"><?= date('M d, Y', $enrollment['date_added'] ?? time()) ?></small>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted text-center">No recent enrollments</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/admin/users') ?>" class="btn btn-outline-primary w-100">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <br>Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/admin/categories') ?>" class="btn btn-outline-success w-100">
                            <i class="fas fa-folder fa-2x mb-2"></i>
                            <br>Manage Categories
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/admin/courses') ?>" class="btn btn-outline-warning w-100">
                            <i class="fas fa-book fa-2x mb-2"></i>
                            <br>Manage Courses
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('/admin/settings') ?>" class="btn btn-outline-danger w-100">
                            <i class="fas fa-cog fa-2x mb-2"></i>
                            <br>System Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
