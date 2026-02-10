<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Page Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/settings/update-page') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">Meta Keywords</label>
                <textarea class="form-control" name="settings[meta_keywords]" rows="3" placeholder="e.g. lms, online course, education"><?= esc($settings['meta_keywords'] ?? '') ?></textarea>
                <div class="form-text">Comma separated keywords for SEO.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Description</label>
                <textarea class="form-control" name="settings[meta_description]" rows="3" placeholder="Brief description of your site for search engines."><?= esc($settings['meta_description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Author</label>
                <input type="text" class="form-control" name="settings[meta_author]" value="<?= esc($settings['meta_author'] ?? '') ?>">
            </div>

            <hr>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Page Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
