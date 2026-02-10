<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Banner Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/settings/update-banner') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Login Banner</label>
                    <input type="file" class="form-control mb-2" name="login_banner" accept="image/*">
                    <div class="form-text mb-3">Image displayed on the login page. Recommended size: 800x600px+</div>
                    
                    <?php if (!empty($settings['login_banner'])): ?>
                        <div class="p-3 border rounded bg-light d-inline-block">
                            <img src="<?= base_url('uploads/system/' . $settings['login_banner']) ?>" alt="Login Banner" style="max-height: 150px; max-width: 100%;">
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="col-md-6">
                    <label class="form-label fw-bold">Home Banner</label>
                    <input type="file" class="form-control mb-2" name="home_banner" accept="image/*">
                    <div class="form-text mb-3">Main hero image for the landing page. Recommended size: 1920x600px</div>

                    <?php if (!empty($settings['home_banner'])): ?>
                        <div class="p-3 border rounded bg-light d-inline-block">
                             <img src="<?= base_url('uploads/system/' . $settings['home_banner']) ?>" alt="Home Banner" style="max-height: 150px; max-width: 100%;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <hr>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Banner Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
