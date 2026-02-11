<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<style>
    .otp-input {
        letter-spacing: 1.5rem;
        text-align: center;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .otp-input::placeholder {
        letter-spacing: normal;
        font-size: 1rem;
        font-weight: normal;
    }
</style>

<div class="container-fluid bg-light min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <?php
                            $settings = model('App\Models\BaseModel')->get_settings();
                            if (!empty($settings['system_logo'])):
                                ?>
                                <img src="<?= base_url('uploads/system/' . $settings['system_logo']) ?>" alt="Logo" class="img-fluid mb-4" style="max-height: 60px;">
                            <?php else: ?>
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                    <i class="fas fa-shield-alt fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h2 class="fw-bold text-dark mb-2">Verification Required</h2>
                            <p class="text-muted">We've sent a 6-digit code to your email.<br>Please enter it below to continue.</p>
                        </div>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                                <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                                <div><?= session()->getFlashdata('error') ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('info')): ?>
                            <div class="alert alert-info shadow-sm border-0 d-flex align-items-center rounded-3 mb-4">
                                <i class="fas fa-info-circle me-2 fs-5"></i>
                                <div><?= session()->getFlashdata('info') ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('/login/process-verify-otp') ?>" method="post">
                            <?= csrf_field() ?>
                            
                            <div class="mb-4">
                                <label for="otp" class="form-label text-muted small fw-bold text-uppercase">One-Time Password (OTP)</label>
                                <input class="form-control form-control-lg otp-input rounded-3 py-3" 
                                       id="otp" type="text" name="otp" 
                                       placeholder="123456" required maxlength="6" pattern="\d*" autofocus autocomplete="one-time-code">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm transition-hover">
                                <i class="fas fa-arrow-right me-2"></i> Verify & Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-3">Didn't receive the code?</p>
                            <a href="<?= base_url('/login') ?>" class="text-decoration-none fw-semibold text-primary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted small">
                    &copy; <?= date('Y') ?> <?= $settings['system_name'] ?? 'LMS' ?>. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

