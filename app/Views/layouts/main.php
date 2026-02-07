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
    
    <link rel="stylesheet" href="<?= base_url('css/main.css') ?>">
    
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
                        // Defensive check: if user is null (deleted?), fallback to 'User' or logout
                        $firstName = $user['first_name'] ?? 'User';
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> <?= esc($firstName) ?>
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

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global Flash Messages to SweetAlert2
        <?php if (session()->has('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= esc(session('success')) ?>',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= esc(session('error')) ?>',
            });
        <?php endif; ?>
        
        <?php if (session()->has('errors')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<?= implode('<br>', array_map('esc', session('errors'))) ?>',
            });
        <?php endif; ?>
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
