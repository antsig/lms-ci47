<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row">
        <!-- Instructor Details -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="<?= base_url('/uploads/user_images/' . ($instructor['image'] ?? 'default.jpg')) ?>" 
                         class="rounded-circle mb-3" width="150" height="150"
                         onerror="this.src='https://via.placeholder.com/150/4F46E5/ffffff?text=Instructor'">
                    
                    <h4 class="fw-bold mb-1"><?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?></h4>
                    <p class="text-muted mb-3">Instructor</p>
                    
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <?php if (!empty($instructor['facebook'])): ?>
                            <a href="<?= esc($instructor['facebook']) ?>" class="text-primary"><i class="fab fa-facebook fa-lg"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($instructor['twitter'])): ?>
                            <a href="<?= esc($instructor['twitter']) ?>" class="text-info"><i class="fab fa-twitter fa-lg"></i></a>
                        <?php endif; ?>
                        
                        <?php if (!empty($instructor['linkedin'])): ?>
                            <a href="<?= esc($instructor['linkedin']) ?>" class="text-primary"><i class="fab fa-linkedin fa-lg"></i></a>
                        <?php endif; ?>
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="fw-bold mb-0"><?= count($courses) ?></h5>
                            <small class="text-muted">Courses</small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold mb-0"><?= $total_students ?? 0 ?></h5>
                            <small class="text-muted">Students</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Skills & Bio -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">About Me</h5>
                    <p><?= nl2br(esc($instructor['biography'] ?? 'No biography available.')) ?></p>
                    
                    <?php if (!empty($instructor['skills'])): ?>
                    <h5 class="fw-bold mt-4 mb-3">Skills</h5>
                    <div>
                        <?php
                        $skills = explode(',', $instructor['skills']);
                        foreach ($skills as $skill):
                            ?>
                            <span class="badge bg-light text-dark border me-1 mb-1"><?= trim(esc($skill)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Courses List -->
        <div class="col-lg-8">
            <h3 class="fw-bold mb-4">My Courses</h3>
            
            <?php if (!empty($courses)): ?>
                <div class="row">
                    <?php foreach ($courses as $course): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 shadow-sm border-0">
                                <a href="<?= base_url('/course/' . $course['id']) ?>">
                                    <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                         class="card-img-top" alt="<?= esc($course['title']) ?>"
                                         onerror="this.src='https://via.placeholder.com/600x400/4F46E5/ffffff?text=Course'">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title fw-bold">
                                        <a href="<?= base_url('/course/' . $course['id']) ?>" class="text-decoration-none text-dark">
                                            <?= esc($course['title']) ?>
                                        </a>
                                    </h5>
                                    <div class="mb-2">
                                        <span class="badge bg-primary"><?= esc($course['level']) ?></span>
                                        <span class="badge bg-secondary"><?= esc($course['category_name'] ?? 'General') ?></span>
                                    </div>
                                    <hr class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <span class="fw-bold text-dark"><?= $course['average_rating'] ?? 0 ?></span>
                                            <span class="text-muted small">(<?= $course['rating_count'] ?? 0 ?>)</span>
                                        </div>
                                        <div class="fw-bold">
                                            <?php if ($course['is_free_course']): ?>
                                                <span class="text-success">Free</span>
                                            <?php else: ?>
                                                Rp <?= number_format($course['discount_flag'] ? $course['discounted_price'] : $course['price']) ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    This instructor has not published any courses yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
