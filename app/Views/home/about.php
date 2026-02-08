<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Header Section -->
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold">About Our Learning Platform</h1>
            <p class="lead text-muted">Discover our mission, vision, and the team dedicated to empowering learners everywhere.</p>
        </div>
    </div>

    <!-- Our Mission Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2 class="fw-bold mb-3">Our Mission</h2>
            <p>To make quality education accessible to everyone, anywhere, at any time. We believe in a world where anyone can build their future through flexible, affordable, and engaging learning.</p>
            <p>Our platform connects expert instructors with eager students, creating a vibrant community where knowledge is shared and skills are honed for the future.</p>
            <a href="<?= base_url('/courses') ?>" class="btn btn-primary mt-3">
                <i class="fas fa-search me-2"></i>Explore Courses
            </a>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://via.placeholder.com/400x300.png?text=Our+Mission" class="img-fluid rounded-3 shadow" alt="Our Mission">
        </div>
    </div>

    <!-- What We Offer Section -->
    <div class="bg-light p-5 rounded-3 mb-5">
        <h2 class="text-center fw-bold mb-4">What We Offer</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Expert-Led Courses</h5>
                        <p class="card-text">Learn from industry professionals and passionate instructors.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-infinity fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Lifetime Access</h5>
                        <p class="card-text">Enroll once and access your course content forever, on any device.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-certificate fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Completion Certificates</h5>
                        <p class="card-text">Earn a certificate to showcase your new skills and achievements.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Meet the Team Section -->
    <div class="row text-center justify-content-center mb-5">
        <h2 class="fw-bold mb-4">Meet Our Team</h2>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0">
                <img src="https://via.placeholder.com/150.png?text=User" class="rounded-circle mx-auto d-block" alt="Team Member 1" style="width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Alex Doe</h5>
                    <p class="card-text text-muted">Lead Instructor</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0">
                <img src="https://via.placeholder.com/150.png?text=User" class="rounded-circle mx-auto d-block" alt="Team Member 2" style="width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Jane Smith</h5>
                    <p class="card-text text-muted">Platform Manager</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0">
                <img src="https://via.placeholder.com/150.png?text=User" class="rounded-circle mx-auto d-block" alt="Team Member 3" style="width: 150px;">
                <div class="card-body text-center">
                    <h5 class="card-title">Sam Wilson</h5>
                    <p class="card-text text-muted">Community Support</p>
                </div>
            </div>
        </div>
    </div>

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