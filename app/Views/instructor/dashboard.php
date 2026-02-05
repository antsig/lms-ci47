<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor') ?>" class="active">
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
<a href="<?= base_url('/instructor/revenue') ?>">
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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_courses ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Courses</p>
                </div>
                <div class="icon primary">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_students ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Students</p>
                </div>
                <div class="icon success">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">Rp <?= number_format($total_revenue ?? 0) ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
                <div class="icon warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">4.5</h3>
                    <p class="text-muted mb-0">Avg Rating</p>
                </div>
                <div class="icon danger">
                    <i class="fas fa-star"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- My Courses -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">My Courses</h5>
                    <a href="<?= base_url('/instructor/create-course') ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Create Course
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (!empty($courses)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($courses, 0, 5) as $course): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                                     width="50" height="50" class="rounded me-2"
                                                     onerror="this.src='https://via.placeholder.com/50/4F46E5/ffffff?text=C'">
                                                <div>
                                                    <strong><?= esc($course['title']) ?></strong>
                                                    <br><small class="text-muted"><?= esc($course['category_name'] ?? '') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= $course['enrollment_count'] ?? 0 ?></td>
                                        <td>
                                            <?php if ($course['status'] == 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif ($course['status'] == 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('/instructor/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="<?= base_url('/instructor/courses') ?>" class="btn btn-sm btn-outline-primary">View All Courses</a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-book fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No courses created yet</h6>
                        <a href="<?= base_url('/instructor/create-course') ?>" class="btn btn-primary mt-2">
                            <i class="fas fa-plus"></i> Create Your First Course
                        </a>
                    </div>
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
                        <?php foreach ($recent_enrollments as $enrollment): ?>
                            <li class="mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <strong><?= esc($enrollment['student_name'] ?? 'Student') ?></strong>
                                        <br><small class="text-muted"><?= esc($enrollment['course_title'] ?? '') ?></small>
                                        <br><small class="text-muted"><?= date('M d, Y', $enrollment['date_added'] ?? time()) ?></small>
                                    </div>
                                </div>
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

<?= $this->endSection() ?>
