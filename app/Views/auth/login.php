<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                    <div class="row g-0">
                        <!-- Left Side: Login Form -->
                        <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center order-2 order-md-1">
                            <div class="text-center mb-4">
                                <?php
                                $settings = model('App\Models\BaseModel')->get_settings();
                                if (!empty($settings['system_logo'])):
                                    ?>
                                    <img src="<?= base_url('uploads/system/' . $settings['system_logo']) ?>" alt="Logo" class="img-fluid mb-3" style="max-height: 50px;">
                                <?php else: ?>
                                    <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
                                <?php endif; ?>
                                <h2 class="fw-bold text-dark">Welcome Back!</h2>
                                <p class="text-muted">Please login to your account to continue.</p>
                            </div>

                            <?php if (session()->has('errors')): ?>
                                <div class="alert alert-danger shadow-sm border-0">
                                    <ul class="mb-0 small">
                                        <?php foreach (session('errors') as $error): ?>
                                            <li><?= esc($error) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('/login/process') ?>" method="POST">
                                <?= csrf_field() ?>
                                
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required placeholder="name@example.com">
                                    <label for="email">Email Address</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                                    <label for="password">Password</label>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                                        <label class="form-check-label text-muted small" for="remember">Remember me</label>
                                    </div>
                                    <a href="<?= base_url('/login/forgot-password') ?>" class="text-decoration-none small fw-bold">Forgot Password?</a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                                    <i class="fas fa-sign-in-alt me-2"></i> Secure Login
                                </button>
                            </form>
                            
                            <hr class="my-4 text-muted opacity-25">

                            <div class="text-center">
                                <p class="text-muted mb-2">Don't have an account?</p>
                                <a href="<?= base_url('/register') ?>" class="btn btn-outline-primary w-100 rounded-pill fw-bold">Create Account</a>
                                
                                <div class="mt-3">
                                    <a href="<?= base_url('/register/instructor') ?>" class="text-decoration-none small text-muted">
                                        Become an Instructor
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Image/Banner -->
                        <div class="col-md-6 d-none d-md-block position-relative order-1 order-md-2" style="background-color: #f8f9fa;">
                            <?php
                            $bannerImg = !empty($settings['login_banner']) ? base_url('uploads/system/' . $settings['login_banner']) : 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80';
                            ?>
                            <img src="<?= $bannerImg ?>" alt="Login Banner" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0">
                            <div class="position-absolute bottom-0 start-0 w-100 p-5 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h3 class="fw-bold mb-2">Learn Without Limits</h3>
                                <p class="mb-0 small opacity-75">Join our community and start your learning journey today.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
