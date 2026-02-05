<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/student') ?>">
    <i class="fas fa-tachometer-alt"></i> My Dashboard
</a>
<a href="<?= base_url('/student/my-courses') ?>">
    <i class="fas fa-book"></i> Enrolled Courses
</a>
<a href="<?= base_url('/student/wishlist') ?>">
    <i class="fas fa-heart"></i> Wishlist
</a>
<a href="<?= base_url('/student/profile') ?>">
    <i class="fas fa-user"></i> My Profile
</a>
<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Change Password</h5>
                <a href="<?= base_url('/student/profile') ?>" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/student/process_change_password') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" required minlength="6">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required minlength="6">
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i> Password must be at least 6 characters long.
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
