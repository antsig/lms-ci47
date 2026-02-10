<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Logo Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/settings/update-logo') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-bold">System Logo</label>
                    <input type="file" class="form-control mb-2" name="system_logo" accept="image/*">
                    <div class="form-text mb-3">Recommended size: 200x50px. Max size: 2MB.</div>
                    
                    <?php if (!empty($settings['system_logo'])): ?>
                        <div class="p-3 border rounded bg-light d-inline-block">
                            <img src="<?= base_url('uploads/system/' . $settings['system_logo']) ?>" alt="System Logo" style="max-height: 50px;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Favicon</label>
                    <input type="file" class="form-control mb-2" name="favicon" accept="image/x-icon,image/png">
                    <div class="form-text mb-3">Recommended size: 16x16px or 32x32px. Max size: 1MB.</div>

                    <?php if (!empty($settings['favicon'])): ?>
                        <div class="p-3 border rounded bg-light d-inline-block">
                             <img src="<?= base_url('uploads/system/' . $settings['favicon']) ?>" alt="Favicon" style="width: 32px; height: 32px;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Logo Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
