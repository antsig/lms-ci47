<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Edit Team Member</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/team/update/' . $member['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <?php if (!empty($member['user_id'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-link me-2"></i> Linked to Instructor ID: <strong><?= $member['user_id'] ?></strong>
                            <input type="hidden" name="user_id" value="<?= $member['user_id'] ?>">
                        </div>
                    <?php else: ?>
                         <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" value="<?= esc($member['name']) ?>" required>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role / Title</label>
                            <input type="text" class="form-control" name="role" value="<?= esc($member['role']) ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="<?= esc($member['display_order']) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biography</label>
                        <textarea class="form-control" name="biography" rows="3"><?= esc($member['biography']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="1" <?= $member['status'] == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= $member['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Custom Image</label>
                        <?php if (!empty($member['image']) && file_exists(FCPATH . 'uploads/team/' . $member['image'])): ?>
                            <div class="mb-2">
                                <img src="<?= base_url('uploads/team/' . $member['image']) ?>" width="100" class="rounded">
                            </div>
                        <?php endif; ?>
                        <input type="file" class="form-control" name="image" accept="image/*">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
