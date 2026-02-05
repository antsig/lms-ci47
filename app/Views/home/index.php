<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Learn Without Limits</h1>
                <p class="lead mb-4">Discover thousands of courses from expert instructors. Start learning today and achieve your goals.</p>
                <div class="d-flex gap-3">
                    <a href="<?= base_url('/courses') ?>" class="btn btn-light btn-lg">Browse Courses</a>
                    <a href="<?= base_url('/register') ?>" class="btn btn-outline-light btn-lg">Get Started</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=Online+Learning" alt="Hero" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-primary"><?= number_format($stats['total_courses'] ?? 0) ?></h2>
                    <p class="text-muted">Courses Available</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-primary"><?= number_format($stats['total_students'] ?? 0) ?></h2>
                    <p class="text-muted">Active Students</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-primary"><?= number_format($stats['total_instructors'] ?? 0) ?></h2>
                    <p class="text-muted">Expert Instructors</p>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="stat-item">
                    <h2 class="display-4 fw-bold text-primary"><?= number_format($stats['total_enrollments'] ?? 0) ?></h2>
                    <p class="text-muted">Course Enrollments</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top Courses -->
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Top Rated Courses</h2>
            <a href="<?= base_url('/courses') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (!empty($top_courses)): ?>
                <?php foreach ($top_courses as $course): ?>
                    <div class="col">
                        <div class="card course-card h-100">
                            <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                 class="card-img-top" alt="<?= esc($course['title']) ?>"
                                 onerror="this.src='https://via.placeholder.com/400x200/4F46E5/ffffff?text=Course'">
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge bg-primary"><?= esc($course['category_name'] ?? 'General') ?></span>
                                    <span class="badge bg-secondary"><?= esc($course['level'] ?? 'All Levels') ?></span>
                                </div>
                                <h5 class="card-title course-title"><?= esc($course['title']) ?></h5>
                                <p class="card-text course-instructor">
                                    <i class="fas fa-user"></i> <?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                </p>
                                
                                <div class="course-meta">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="rating">
                                            <?php
                                            $rating = $course['average_rating'] ?? 0;
                                            for ($i = 1; $i <= 5; $i++):
                                                echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                            endfor;
                                            ?>
                                            <span class="text-muted small">(<?= $course['rating_count'] ?? 0 ?>)</span>
                                        </div>
                                        <div>
                                            <i class="fas fa-users text-muted"></i>
                                            <span class="text-muted small"><?= $course['enrollment_count'] ?? 0 ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if ($course['is_free_course']): ?>
                                            <span class="course-price">FREE</span>
                                        <?php else: ?>
                                            <span class="course-price">Rp <?= number_format($course['price']) ?></span>
                                        <?php endif; ?>
                                        <a href="<?= base_url('/course/' . $course['id']) ?>" class="btn btn-sm btn-primary">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No courses available yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Latest Courses -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Latest Courses</h2>
            <a href="<?= base_url('/courses') ?>" class="btn btn-outline-primary">View All</a>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (!empty($latest_courses)): ?>
                <?php foreach ($latest_courses as $course): ?>
                    <div class="col">
                        <div class="card course-card h-100">
                            <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                 class="card-img-top" alt="<?= esc($course['title']) ?>"
                                 onerror="this.src='https://via.placeholder.com/300x150/4F46E5/ffffff?text=Course'">
                            <div class="card-body">
                                <h6 class="card-title course-title"><?= esc($course['title']) ?></h6>
                                <p class="text-muted small course-instructor">
                                    <i class="fas fa-user"></i> <?= esc($course['instructor_name'] ?? 'Instructor') ?>
                                </p>
                                <div class="course-meta">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if ($course['is_free_course']): ?>
                                            <span class="fw-bold text-success">FREE</span>
                                        <?php else: ?>
                                            <span class="fw-bold text-primary">Rp <?= number_format($course['price']) ?></span>
                                        <?php endif; ?>
                                        <a href="<?= base_url('/course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info">No courses available yet.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-4 text-center">Browse by Category</h2>
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach (array_slice($categories, 0, 8) as $category): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <a href="<?= base_url('/courses?category=' . $category['id']) ?>" class="text-decoration-none">
                            <div class="card text-center h-100 category-card">
                                <div class="card-body">
                                    <i class="<?= esc($category['font_awesome_class'] ?? 'fas fa-book') ?> fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title"><?= esc($category['name']) ?></h5>
                                    <p class="text-muted small"><?= $category['course_count'] ?? 0 ?> courses</p>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Ready to Start Teaching?</h2>
        <p class="lead mb-4">Join thousands of instructors and share your knowledge with students worldwide.</p>
        <a href="<?= base_url('/register/instructor') ?>" class="btn btn-light btn-lg">Become an Instructor</a>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .category-card {
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    
    .category-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
    }
</style>
<?= $this->endSection() ?>
