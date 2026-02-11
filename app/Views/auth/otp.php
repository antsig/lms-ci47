<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 overflow-hidden rounded-4">
                    <div class="row g-0">
                        <!-- Left Side: OTP Form -->
                        <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center order-2 order-md-1">
                            <div class="text-center mb-4">
                                <?php
                                $settings = model('App\Models\BaseModel')->get_settings();
                                if (!empty($settings['system_logo'])):
                                    ?>
                                    <img src="<?= base_url('uploads/system/' . $settings['system_logo']) ?>" alt="Logo" class="img-fluid mb-3" style="max-height: 50px;">
                                <?php else: ?>
                                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                                <?php endif; ?>
                                <h2 class="fw-bold text-dark">Two-Factor Authentication</h2>
                                <p class="text-muted">Enter the code sent to your email address.</p>
                            </div>

                            <?php if (session()->getFlashdata('error')): ?>
                                <div class="alert alert-danger shadow-sm border-0">
                                    <ul class="mb-0 small">
                                        <li><?= session()->getFlashdata('error') ?></li>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if (session()->getFlashdata('info')): ?>
                                <div class="alert alert-info shadow-sm border-0">
                                    <ul class="mb-0 small">
                                        <li><?= session()->getFlashdata('info') ?></li>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form action="<?= base_url('/login/process-verify-otp') ?>" method="post">
                                <?= csrf_field() ?>
                                
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="otp" type="text" name="otp" placeholder="Enter 6-digit OTP" required maxlength="6" pattern="\d*" autofocus>
                                    <label for="otp">Enter 6-Digit OTP</label>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-pill">
                                    <i class="fas fa-check-circle me-2"></i> Verify Login
                                </button>
                            </form>
                            
                            <hr class="my-4 text-muted opacity-25">

                            <div class="text-center">
                                <a href="<?= base_url('/login') ?>" class="text-decoration-none small text-muted">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Login
                                </a>
                            </div>
                        </div>

                        <!-- Right Side: Image/Banner -->
                        <div class="col-md-6 d-none d-md-block position-relative order-1 order-md-2" style="background-color: #f8f9fa;">
                            <?php
                            $bannerImg = !empty($settings['login_banner']) ? base_url('uploads/system/' . $settings['login_banner']) : 'https://images.unsplash.com/photo-1614064641938-3e8529437213?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1374&q=80';
                            ?>
                            <img src="<?= $bannerImg ?>" alt="Login Banner" class="w-100 h-100 object-fit-cover position-absolute top-0 start-0">
                            <div class="position-absolute bottom-0 start-0 w-100 p-5 text-white" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);">
                                <h3 class="fw-bold mb-2">Secure Your Account</h3>
                                <p class="mb-0 small opacity-75">Two-factor authentication adds an extra layer of security to your account.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
