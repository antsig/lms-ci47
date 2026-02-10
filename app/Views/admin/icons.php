<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Manage Icons</h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> This system currently uses <strong>FontAwesome 5 (Free)</strong>.
        </div>

        <form action="<?= base_url('/admin/settings/update-icons') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">FontAwesome CDN URL</label>
                <input type="text" class="form-control" name="settings[fa_cdn_url]" 
                       value="<?= esc($settings['fa_cdn_url'] ?? '') ?>" 
                       placeholder="Leave empty to use default local/CDN version">
                <div class="form-text">If you have a Pro kit, paste your Kit Code script URL here.</div>
            </div>

            <hr>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Icon Settings
            </button>
        </form>
        
        <div class="mt-4">
            <h6>Icon Preview</h6>
            <div class="p-3 border rounded bg-light">
                <i class="fas fa-home fa-2x me-2"></i>
                <i class="fas fa-user fa-2x me-2"></i>
                <i class="fas fa-cog fa-2x me-2"></i>
                <i class="fas fa-check-circle fa-2x me-2 text-success"></i>
                <i class="fas fa-exclamation-triangle fa-2x me-2 text-warning"></i>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
