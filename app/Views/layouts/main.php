<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'LMS') ?> - Learning Management System</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
            --dark-color: #1F2937;
            --text-color: #374151;
            --text-muted: #6B7280;
            --light-color: #F9FAFB;
            --border-color: #E5E7EB;
        }
        
        * {
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: var(--light-color);
            color: var(--text-color);
            font-size: 15px;
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-color);
            font-weight: 600;
            line-height: 1.3;
        }
        
        h1 { font-size: 2.25rem; }
        h2 { font-size: 1.875rem; }
        h3 { font-size: 1.5rem; }
        h4 { font-size: 1.25rem; }
        h5 { font-size: 1.125rem; }
        h6 { font-size: 1rem; }
        
        p, .text-muted {
            color: var(--text-muted);
            font-size: 0.9375rem;
        }
        
        .lead {
            font-size: 1.125rem;
            font-weight: 400;
            color: var(--text-color);
        }
        
        .navbar {
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            padding: 0.75rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.375rem;
        }
        
        .nav-link {
            color: var(--text-color) !important;
            font-weight: 500;
            font-size: 0.9375rem;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }
        
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        
        .dropdown-item {
            font-size: 0.9375rem;
            color: var(--text-color);
            padding: 0.5rem 1rem;
        }
        
        .dropdown-item i {
            width: 20px;
            margin-right: 8px;
            font-size: 0.875rem;
        }
        
        .btn {
            font-size: 0.9375rem;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
        }
        
        .btn-sm {
            font-size: 0.875rem;
            padding: 0.375rem 1rem;
        }
        
        .btn-lg {
            font-size: 1rem;
            padding: 0.75rem 1.75rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
        }
        
        .hero-section h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .hero-section .lead {
            color: rgba(255,255,255,0.95);
            font-size: 1.125rem;
        }
        
        .card {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }
        
        .card-body {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        
        .card-title {
            font-size: 1.0625rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            min-height: 3.2rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
        }
        
        .card-text {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 0.75rem;
        }
        
        .card-footer {
            background: transparent;
            border-top: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            margin-top: auto;
        }
        
        .course-card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        .course-card .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .course-card .course-meta {
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-color);
        }
        
        .course-card .course-title {
            min-height: 3.2rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .course-card .course-instructor {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            min-height: 1.3rem;
        }
        
        .course-card .course-price {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .badge {
            padding: 0.375em 0.75em;
            font-weight: 500;
            font-size: 0.75rem;
            border-radius: 4px;
        }
        
        .course-card img {
            height: 180px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        
        .course-price {
            font-size: 1.375rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .rating {
            color: #FFA500;
            font-size: 0.875rem;
        }
        
        .rating .text-muted {
            font-size: 0.8125rem;
        }
        
        .footer {
            background-color: var(--dark-color);
            color: #D1D5DB;
            padding: 40px 0 20px;
            margin-top: 80px;
            font-size: 0.9375rem;
        }
        
        .footer h5 {
            color: white;
            font-size: 1.0625rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .footer .text-muted {
            color: #9CA3AF !important;
            font-size: 0.875rem;
        }
        
        .footer a {
            font-size: 0.875rem;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
            font-size: 0.9375rem;
        }
        
        .form-label {
            font-size: 0.9375rem;
            font-weight: 500;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            font-size: 0.9375rem;
            border-color: var(--border-color);
            padding: 0.5rem 0.75rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }
        
        small, .small {
            font-size: 0.8125rem;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            font-weight: 700;
        }
        
        .stat-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
        }
    </style>
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="fas fa-graduation-cap"></i> LMS
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/courses') ?>">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/about') ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/contact') ?>">Contact</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php
                    $auth = new \App\Libraries\Auth();
                    if ($auth->isLoggedIn()):
                        $user = $auth->getUser();
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?= esc($user['first_name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <?php if ($auth->isAdmin()): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('/admin') ?>"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a></li>
                                <?php elseif ($auth->isInstructor()): ?>
                                    <li><a class="dropdown-item" href="<?= base_url('/instructor') ?>"><i class="fas fa-chalkboard-teacher"></i> Instructor Dashboard</a></li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?= base_url('/student') ?>"><i class="fas fa-book-reader"></i> My Dashboard</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= base_url('/student/profile') ?>"><i class="fas fa-user"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('/login/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="<?= base_url('/register') ?>">Sign Up</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (session()->has('success')): ?>
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (session()->has('error')): ?>
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="fas fa-graduation-cap"></i> LMS</h5>
                    <p class="text-muted">Your gateway to quality online education. Learn from the best instructors worldwide.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('/courses') ?>" class="text-muted text-decoration-none">Browse Courses</a></li>
                        <li><a href="<?= base_url('/about') ?>" class="text-muted text-decoration-none">About Us</a></li>
                        <li><a href="<?= base_url('/contact') ?>" class="text-muted text-decoration-none">Contact</a></li>
                        <li><a href="<?= base_url('/register/instructor') ?>" class="text-muted text-decoration-none">Become an Instructor</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Contact Info</h5>
                    <p class="text-muted">
                        <i class="fas fa-envelope"></i> info@lms.com<br>
                        <i class="fas fa-phone"></i> +62 123 456 789<br>
                        <i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia
                    </p>
                </div>
            </div>
            <hr class="bg-light">
            <div class="text-center text-muted">
                <p>&copy; <?= date('Y') ?> LMS. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (optional, for AJAX) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
