<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
$settings = model('App\Models\BaseModel')->get_settings();
?>

<div class="container py-5">
    <!-- Header Section -->
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold"><?= esc($settings['about_us_title'] ?? 'About Our Learning Platform') ?></h1>
            <p class="lead text-muted"><?= esc($settings['about_us_tagline'] ?? 'Discover our mission, vision, and the team dedicated to empowering learners everywhere.') ?></p>
        </div>
    </div>

    <!-- Our Mission Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-3">Our Mission</h2>
            <div>
                <?= $settings['about_us_content'] ?? '<p>To make quality education accessible to everyone, anywhere, at any time. We believe in a world where anyone can build their future through flexible, affordable, and engaging learning.</p><p>Our platform connects expert instructors with eager students, creating a vibrant community where knowledge is shared and skills are honed for the future.</p>' ?>
            </div>
            <a href="<?= base_url('/courses') ?>" class="btn btn-primary mt-3">
                <i class="fas fa-search me-2"></i>Explore Courses
            </a>
        </div>
        <div class="col-md-6 text-center">
            <?php if (!empty($settings['about_us_image'])): ?>
                <img src="<?= base_url('uploads/system/' . $settings['about_us_image']) ?>" class="img-fluid rounded-3 shadow" alt="About Us">
            <?php else: ?>
                <img src="https://via.placeholder.com/400x300.png?text=Our+Mission" class="img-fluid rounded-3 shadow" alt="Our Mission">
            <?php endif; ?>
        </div>
    </div>

    <!-- What We Offer Section -->
    <?php if (($settings['about_us_show_features'] ?? '1') == '1'): ?>
    <div class="bg-light p-5 rounded-3 mb-5">
        <h2 class="text-center fw-bold mb-4">What We Offer</h2>
        <div class="row text-center">
            <?php if (empty($features)): ?>
                <div class="col-12 text-center text-muted">
                    <p>Features content coming soon.</p>
                </div>
            <?php else: ?>
                <?php foreach ($features as $feature): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                <i class="<?= esc($feature['icon']) ?> fa-3x text-primary mb-3"></i>
                                <h5 class="card-title fw-bold"><?= esc($feature['title']) ?></h5>
                                <p class="card-text text-muted"><?= esc($feature['description']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Meet the Team Section -->
    <?php if (($settings['about_us_show_team'] ?? '1') == '1'): ?>
    <div class="row text-center justify-content-center mb-5">
        <h2 class="fw-bold mb-4">Meet Our Team</h2>
        
        <?php if (empty($team_members)): ?>
            <p class="text-muted">Our team is growing! check back soon.</p>
        <?php else: ?>
            <?php foreach ($team_members as $member): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0">
                        <?php
                        $imgSrc = base_url('uploads/system/placeholder.png');  // Default fallback?
                        // Use logic similar to Admin but user data is already merged by Model
                        if (!empty($member['image'])) {
                            if (file_exists(FCPATH . 'uploads/team/' . $member['image'])) {
                                $imgSrc = base_url('uploads/team/' . $member['image']);
                            } elseif (file_exists(FCPATH . 'uploads/user_images/' . $member['image'])) {
                                $imgSrc = base_url('uploads/user_images/' . $member['image']);
                            } else {
                                // Fallback if file missing but name exists in DB
                                $imgSrc = 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random';
                            }
                        } else {
                            // Fallback if no image at all
                            $imgSrc = 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random';
                        }
                        ?>
                        <img src="<?= $imgSrc ?>" class="rounded-circle mx-auto d-block object-fit-cover" alt="<?= esc($member['name']) ?>" style="width: 150px; height: 150px;">
                        <div class="card-body text-center">
                            <h5 class="card-title fw-bold unselectable"><?= esc($member['name']) ?></h5>

                            <?php if (!empty($member['biography'])): ?>
                                <p class="card-text small text-muted d-none d-md-block"><?= character_limiter(strip_tags($member['biography']), 60) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Join Our Community Section -->
    <div class="row justify-content-center text-center">
        <div class="col-lg-8">
            <h2 class="fw-bold">Ready to Start Learning?</h2>
            <p class="lead mb-4">Join thousands of learners and instructors in our growing community. Whether you're advancing your career or exploring a new passion, we have the right course for you.</p>
            <a href="<?= base_url('/register') ?>" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-user-plus me-2"></i>Sign Up Now
            </a>
            <a href="<?= base_url('/register/instructor') ?>" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-chalkboard me-2"></i>Become an Instructor
            </a>
        </div>
    </div>
</div>

<?= $this->endSection() ?>