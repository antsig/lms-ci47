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
<a href="<?= base_url('/student/profile') ?>" class="active">
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

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">My Profile</h5>
        <a href="<?= base_url('/student/change-password') ?>" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-key"></i> Change Password
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/student/update_profile') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row align-items-center mb-4">
                <div class="col-md-2 text-center">
                    <img src="<?= base_url('/uploads/user_images/' . ($user['image'] ?? 'default.jpg')) ?>" 
                         class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;"
                         onerror="this.src='https://via.placeholder.com/100/4F46E5/ffffff?text=User'">
                </div>
                <div class="col-md-10">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" class="form-control" name="image">
                    <small class="text-muted">Upload a new photo to update.</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?= esc($user['first_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?= esc($user['last_name']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="tel" class="form-control" name="phone" value="<?= esc($user['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Biography</label>
                <textarea class="form-control" name="biography" rows="4"><?= esc($user['biography'] ?? '') ?></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
