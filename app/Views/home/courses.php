<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Filters</h5>
                    
                    <form method="GET" action="<?= base_url('/courses') ?>">
                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Category</label>
                            <select name="category" class="form-select" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= esc($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <!-- Level Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Level</label>
                            <select name="level" class="form-select" onchange="this.form.submit()">
                                <option value="">All Levels</option>
                                <option value="beginner" <?= (isset($_GET['level']) && $_GET['level'] == 'beginner') ? 'selected' : '' ?>>Beginner</option>
                                <option value="intermediate" <?= (isset($_GET['level']) && $_GET['level'] == 'intermediate') ? 'selected' : '' ?>>Intermediate</option>
                                <option value="advanced" <?= (isset($_GET['level']) && $_GET['level'] == 'advanced') ? 'selected' : '' ?>>Advanced</option>
                            </select>
                        </div>
                        
                        <!-- Price Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Price</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" value="" id="priceAll" 
                                       <?= !isset($_GET['price']) ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label" for="priceAll">All</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" value="free" id="priceFree" 
                                       <?= (isset($_GET['price']) && $_GET['price'] == 'free') ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label" for="priceFree">Free</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="price" value="paid" id="pricePaid" 
                                       <?= (isset($_GET['price']) && $_GET['price'] == 'paid') ? 'checked' : '' ?> onchange="this.form.submit()">
                                <label class="form-check-label" for="pricePaid">Paid</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <a href="<?= base_url('/courses') ?>" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Courses List -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">All Courses</h2>
                <div>
                    <span class="text-muted"><?= count($courses) ?> courses found</span>
                </div>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (!empty($courses)): ?>
                    <?php foreach ($courses as $course): ?>
                        <div class="col">
                            <div class="card course-card h-100">
                                <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                     class="card-img-top" alt="<?= esc($course['title']) ?>"
                                     onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
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
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <?php if ($course['is_free_course']): ?>
                                                <span class="fw-bold text-success">FREE</span>
                                            <?php else: ?>
                                                <span class="fw-bold text-primary">Rp <?= number_format($course['price']) ?></span>
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
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> No courses found matching your criteria.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
