<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('student/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0"><?= $total_courses ?? 0 ?></h3>
                    <p class="text-muted mb-0">Enrolled Courses</p>
                </div>
                <div class="icon primary">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">0</h3>
                    <p class="text-muted mb-0">Completed</p>
                </div>
                <div class="icon success">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0">0</h3>
                    <p class="text-muted mb-0">Certificates</p>
                </div>
                <div class="icon warning">
                    <i class="fas fa-certificate"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Courses -->
<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">My Courses</h5>
            <a href="<?= base_url('/courses') ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Browse Courses
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($enrolled_courses)): ?>
            <div class="row">
                <?php foreach ($enrolled_courses as $course): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                 class="card-img-top" alt="<?= esc($course['title']) ?>"
                                 style="height: 150px; object-fit: cover;"
                                 onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                            <div class="card-body">
                                <h6 class="card-title"><?= esc($course['title']) ?></h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-user"></i> <?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                </p>
                                
                                <!-- Progress Bar -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Progress</small>
                                        <small>0%</small>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>
                                
                                <a href="<?= base_url('/student/course-player/' . $course['id']) ?>" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-play"></i> Continue Learning
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No courses enrolled yet</h5>
                <p class="text-muted">Start learning by enrolling in a course</p>
                <a href="<?= base_url('/courses') ?>" class="btn btn-primary">Browse Courses</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
