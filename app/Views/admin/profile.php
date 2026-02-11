<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">My Profile</h5>
        <!-- Admin doesn't have change password here usually, or maybe they do? 
             Let's keep it consistent with student for now if they have a shared change password route or similar.
             Actually admin has no 'change-password' route in the group I saw. 
             Wait, looking at routes, Admin does NOT have a change password route in their group. 
             They might be using the dashboard one or it's missing. 
             For now, I'll omit the change password button to avoid broken links, or I should check if there is a common one.
             The 'Auth' library has changePassword, but no route was seen in Admin group. 
             Let's stick to just profile update for now. 
        -->
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Profile Details</button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabsContent">
            <!-- Profile Details Tab -->
            <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                <form action="<?= base_url('/admin/profile/update') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="row align-items-center mb-4">
                        <div class="col-md-2 text-center">
                            <img src="<?= base_url('/uploads/user_images/' . ($user['image'] ?? 'default.jpg')) ?>" 
                                 class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;"
                                 onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                        </div>
                        <div class="col-md-10">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" name="image">
                            <small class="text-muted">Upload a new photo to update.</small>
                            
                            <hr class="my-3">

                            <label class="form-label">Digital Signature</label>
                            <div class="mb-2">
                                <?php if (!empty($user['signature'])): ?>
                                    <img src="<?= base_url('/uploads/signatures/' . $user['signature']) ?>" 
                                         alt="Signature" style="max-height: 60px; border: 1px solid #ccc; padding: 5px;">
                                <?php else: ?>
                                    <span class="text-muted small">No signature uploaded.</span>
                                <?php endif; ?>
                            </div>
                            <input type="file" class="form-control" name="signature" accept="image/*">
                            <small class="text-muted">Upload an image of your signature (PNG transparent recommended).</small>
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
    </div>
</div>

<?= $this->endSection() ?>
