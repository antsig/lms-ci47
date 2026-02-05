<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/student') ?>">
    <i class="fas fa-tachometer-alt"></i> My Dashboard
</a>
<a href="<?= base_url('/student/my-courses') ?>">
    <i class="fas fa-book"></i> Enrolled Courses
</a>
<a href="<?= base_url('/student/wishlist') ?>" class="active">
    <i class="fas fa-heart"></i> Wishlist
</a>
<a href="<?= base_url('/student/profile') ?>">
    <i class="fas fa-user"></i> My Profile
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
        <h5 class="mb-0 fw-bold">My Wishlist</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($courses)): ?>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            <a href="<?= base_url('/course/' . $course['id']) ?>">
                                <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                     class="card-img-top" alt="<?= esc($course['title']) ?>"
                                     onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-bold">
                                    <a href="<?= base_url('/course/' . $course['id']) ?>" class="text-decoration-none text-dark">
                                        <?= esc($course['title']) ?>
                                    </a>
                                </h5>
                                <div class="mb-2">
                                    <span class="badge bg-primary"><?= esc($course['level']) ?></span>
                                </div>
                                <div class="fw-bold mb-3">
                                    <?php if ($course['is_free_course']): ?>
                                        <span class="text-success">Free</span>
                                    <?php else: ?>
                                        Rp <?= number_format($course['discount_flag'] ? $course['discounted_price'] : $course['price']) ?>
                                    <?php endif; ?>
                                </div>
                                <hr class="mt-auto">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('/course/' . $course['id']) ?>" class="btn btn-primary btn-sm">
                                        Enroll Now
                                    </a>
                                    <a href="<?= base_url('/student/wishlist/remove/' . $course['id']) ?>" class="btn btn-outline-danger btn-sm">
                                        Remove
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                <h5>Your wishlist is empty</h5>
                <p class="text-muted">Browse courses and bookmark your favorites.</p>
                <a href="<?= base_url('/courses') ?>" class="btn btn-primary">
                    Browse Courses
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
