<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="card shadow-lg border-0 rounded-lg mt-5">
    <div class="card-header justify-content-center">
        <h3 class="font-weight-light my-4">Reset Password</h3>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/login/process-reset-password') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="form-floating mb-3">
                <input class="form-control" id="email" type="email" name="email" value="<?= esc($email) ?>" placeholder="name@example.com" required readonly>
                <label for="email">Email address</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="code" type="text" name="code" placeholder="Enter 6-digit OTP" required maxlength="6" pattern="\d*">
                <label for="code">OTP Code</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="password" type="password" name="password" placeholder="New Password" required minlength="6">
                <label for="password">New Password</label>
            </div>

            <div class="form-floating mb-3">
                <input class="form-control" id="confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
                <label for="confirm_password">Confirm Password</label>
            </div>

            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                <a class="small" href="<?= base_url('/login') ?>">Return to login</a>
                <button type="submit" class="btn btn-primary">Reset Password</button>
            </div>
        </form>
    </div>
    <div class="card-footer text-center py-3">
        <div class="small">Enter the OTP sent to your email and your new password.</div>
    </div>
</div>

<?= $this->endSection() ?>
