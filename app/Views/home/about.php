<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-4">About Us</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="mb-3">Welcome to Our Learning Platform</h3>
                    <p class="lead">We are dedicated to providing high-quality online education to learners worldwide.</p>
                    
                    <p>Our platform connects expert instructors with eager students, creating a vibrant learning community where knowledge is shared and skills are developed.</p>
                    
                    <h4 class="mt-4 mb-3">Our Mission</h4>
                    <p>To make quality education accessible to everyone, anywhere, at any time. We believe that learning should be flexible, affordable, and engaging.</p>
                    
                    <h4 class="mt-4 mb-3">What We Offer</h4>
                    <ul>
                        <li>Thousands of courses across various categories</li>
                        <li>Expert instructors from around the world</li>
                        <li>Flexible learning at your own pace</li>
                        <li>Certificates of completion</li>
                        <li>Lifetime access to course materials</li>
                        <li>Mobile and desktop compatibility</li>
                    </ul>
                    
                    <h4 class="mt-4 mb-3">Join Our Community</h4>
                    <p>Whether you're looking to advance your career, learn a new skill, or explore a passion, we have the right course for you.</p>
                    
                    <div class="mt-4">
                        <a href="<?= base_url('/courses') ?>" class="btn btn-primary me-2">Browse Courses</a>
                        <a href="<?= base_url('/register/instructor') ?>" class="btn btn-outline-primary">Become an Instructor</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
