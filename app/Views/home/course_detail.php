<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row align-items-start">
        <!-- Course Content -->
        <div class="col-lg-8">
            <!-- Course Header -->
            <div class="card mb-4">
                <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                     class="card-img-top" alt="<?= esc($course['title']) ?>"
                     onerror="this.onerror=null;this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge bg-primary"><?= esc($course['category_name'] ?? 'General') ?></span>
                        <span class="badge bg-secondary"><?= esc($course['level'] ?? 'All Levels') ?></span>
                        <span class="badge bg-info"><?= esc($course['language'] ?? 'English') ?></span>
                    </div>
                    
                    <h1 class="fw-bold mb-3"><?= esc($course['title']) ?></h1>
                    
                    <p class="lead"><?= esc($course['short_description']) ?></p>
                    
                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="rating">
                            <?php
                            $rating = $course['average_rating'] ?? 0;
                            for ($i = 1; $i <= 5; $i++):
                                echo $i <= $rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                            endfor;
                            ?>
                            <span class="ms-2"><?= number_format($rating, 1) ?> (<?= $course['rating_count'] ?? 0 ?> ratings)</span>
                        </div>
                        <div>
                            <i class="fas fa-users"></i> <?= $course['enrollment_count'] ?? 0 ?> students
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <img src="<?= base_url('/uploads/user_images/' . ($course['instructor_image'] ?? 'default.jpg')) ?>" 
                             class="rounded-circle me-2" width="40" height="40"
                             onerror="this.src='https://via.placeholder.com/40/4F46E5/ffffff?text=I'">
                        <div>
                            <strong>Instructor:</strong> 
                            <a href="<?= base_url('/instructor/' . $course['user_id']) ?>" class="text-decoration-none">
                                <?= esc($course['instructor_name'] ?? 'Instructor') ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Course Description -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">About This Course</h4>
                    <div><?= $course['description'] ?></div>
                </div>
            </div>
            
            <!-- What You'll Learn -->
            <?php
            $outcomes = is_string($course['outcomes']) ? json_decode($course['outcomes'], true) : $course['outcomes'];
            if (!empty($outcomes) && is_array($outcomes)):
                ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">What You'll Learn</h4>
                    <div class="row">
                        <?php foreach ($outcomes as $outcome): ?>
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-check-circle text-success"></i> <?= esc($outcome) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Requirements -->
            <?php
            $requirements = is_string($course['requirements']) ? json_decode($course['requirements'], true) : $course['requirements'];
            if (!empty($requirements) && is_array($requirements)):
                ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Requirements</h4>
                    <ul>
                        <?php foreach ($requirements as $req): ?>
                            <li><?= esc($req) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Course Content -->
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="fw-bold mb-3">Course Content</h4>
                    <div class="accordion" id="courseContent">
                        <?php if (!empty($course['sections'])): ?>
                            <?php foreach ($course['sections'] as $index => $section): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#section<?= $section['id'] ?>">
                                            <strong><?= esc($section['title']) ?></strong>
                                            <span class="ms-auto me-3 text-muted small"><?= count($section['lessons'] ?? []) ?> lessons</span>
                                        </button>
                                    </h2>
                                    <div id="section<?= $section['id'] ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" 
                                         data-bs-parent="#courseContent">
                                        <div class="accordion-body">
                                            <?php if (!empty($section['lessons'])): ?>
                                                <ul class="list-unstyled">
                                                    <?php foreach ($section['lessons'] as $lesson): ?>
                                                        <li class="mb-2 d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <i class="fas fa-play-circle text-primary"></i>
                                                                <?= esc($lesson['title']) ?>
                                                                <?php if ($lesson['is_free']): ?>
                                                                    <span class="badge bg-success ms-2">Free Preview</span>
                                                                <?php endif; ?>
                                                            </div>
                                                            <span class="text-muted small"><?= $lesson['duration'] ?? '00:00' ?></span>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php else: ?>
                                                <p class="text-muted">No lessons yet</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">Course content will be added soon.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php if ($course['is_free_course']): ?>
                            <h2 class="fw-bold text-success">FREE</h2>
                        <?php else: ?>
                            <?php if ($course['discount_flag']): ?>
                                <h2 class="fw-bold text-primary">Rp <?= number_format($course['discounted_price']) ?></h2>
                                <p class="text-muted"><del>Rp <?= number_format($course['price']) ?></del></p>
                            <?php else: ?>
                                <h2 class="fw-bold text-primary">Rp <?= number_format($course['price']) ?></h2>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    $auth = new \App\Libraries\Auth();
                    if ($auth->isLoggedIn()):
                        // Prevent instructor from enrolling in their own course
                        if ($auth->isInstructor() && $auth->getUserId() == $course['user_id']) :
                    ?>
                        <a href="#" class="btn btn-secondary w-100 mb-3 disabled" aria-disabled="true">
                            <i class="fas fa-chalkboard-teacher"></i> You are the Instructor
                        </a>
                    <?php else:
                        // Check if already enrolled
                        $enrollmentModel = new \App\Models\EnrollmentModel();
                        $isEnrolled = $enrollmentModel->isEnrolled($auth->getUserId(), $course['id']);

                        if ($isEnrolled):
                            ?>
                        <a href="<?= base_url('/student/course-player/' . $course['id']) ?>" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-play"></i> Continue Learning
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/payment/checkout/' . $course['id']) ?>" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-shopping-cart"></i> Enroll Now
                        </a>
                    <?php endif; ?>
                    <?php // Only show wishlist for non-instructors (i.e., students and admins)
                        if (!$auth->isInstructor()): 
                        
                        // Further check if item is already in wishlist
                        $wishlistModel = new \App\Models\WishlistModel();
                        $isWishlisted = $wishlistModel->isWishlisted($auth->getUserId(), $course['id']);

                        if ($isWishlisted):
                    ?>
                        <a href="<?= base_url('/student/wishlist/remove/' . $course['id']) ?>" class="btn btn-danger w-100 mb-3">
                            <i class="fas fa-heart-broken"></i> Remove from Wishlist
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('/student/wishlist/add/' . $course['id']) ?>" class="btn btn-outline-primary w-100 mb-3">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </a>
                    <?php endif; endif; ?>
                    <?php endif; // End of instructor check ?>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt"></i> Login to Enroll
                        </a>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <h6 class="fw-bold mb-3">This course includes:</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-video text-primary"></i> <?= count($course['sections'] ?? []) ?> sections</li>
                        <li class="mb-2"><i class="fas fa-file text-primary"></i> <?= $course['total_lessons'] ?? 0 ?> lessons</li>
                        <li class="mb-2"><i class="fas fa-infinity text-primary"></i> Lifetime access</li>
                        <li class="mb-2"><i class="fas fa-mobile-alt text-primary"></i> Access on mobile and desktop</li>
                        <li class="mb-2"><i class="fas fa-certificate text-primary"></i> Certificate of completion</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
