<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/student') ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/student/my-courses') ?>" class="active">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/student/wishlist') ?>">
    <i class="fas fa-heart"></i> Wishlist
</a>
<a href="<?= base_url('/student/profile') ?>">
    <i class="fas fa-user"></i> Profile
</a>
<a href="<?= base_url('/student/change-password') ?>">
    <i class="fas fa-key"></i> Change Password
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

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">My Courses</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($courses)): ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                 class="card-img-top" alt="<?= esc($course['title']) ?>"
                                 style="height: 180px; object-fit: cover;"
                                 onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= esc($course['category_name'] ?? 'General') ?></span>
                                <h6 class="card-title"><?= esc($course['title']) ?></h6>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-user"></i> <?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                </p>
                                
                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Progress</small>
                                        <small class="text-muted">0%</small>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('/student/course-player/' . $course['id']) ?>" class="btn btn-primary btn-sm flex-grow-1">
                                        <i class="fas fa-play"></i> Continue
                                    </a>
                                    <a href="<?= base_url('/course/' . $course['id']) ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No courses enrolled yet</h5>
                <p class="text-muted">Start your learning journey by enrolling in a course</p>
                <a href="<?= base_url('/courses') ?>" class="btn btn-primary">
                    <i class="fas fa-search"></i> Browse Courses
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
