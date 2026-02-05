<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Edit User</h5>
        <a href="<?= base_url('/admin/users') ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/update-user/' . $user['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
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

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" name="phone" value="<?= esc($user['phone'] ?? '') ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select class="form-select" name="role_id" required>
                        <option value="2" <?= $user['role_id'] == 2 ? 'selected' : '' ?>>Student</option>
                        <option value="1" <?= $user['role_id'] == 1 ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="is_instructor" value="1" id="isInstructor" <?= $user['is_instructor'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="isInstructor">
                        Is Instructor?
                    </label>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label class="form-label">Change Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                <input type="password" class="form-control" name="password" minlength="6">
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
