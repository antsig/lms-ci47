<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">About Page Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/settings/update-about') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Page Title</label>
                <input type="text" class="form-control" name="settings[about_us_title]" value="<?= esc($settings['about_us_title'] ?? 'About Our Learning Platform') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Page Tagline (Lead Text)</label>
                <input type="text" class="form-control" name="settings[about_us_tagline]" value="<?= esc($settings['about_us_tagline'] ?? 'Discover our mission, vision, and the team dedicated to empowering learners everywhere.') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Main Content</label>

                <textarea class="form-control" name="settings[about_us_content]" rows="6"><?= esc($settings['about_us_content'] ?? 'Discover our mission, vision, and the team dedicated to empowering learners everywhere.') ?></textarea>
                <small class="text-muted">You can use basic HTML tags.</small>
            </div>

            <div class="mb-3 form-check form-switch">
                <input type="hidden" name="settings[about_us_show_team]" value="0">
                <input class="form-check-input" type="checkbox" id="showTeam" name="settings[about_us_show_team]" value="1" <?= ($settings['about_us_show_team'] ?? '1') == '1' ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold" for="showTeam">Show "Meet the Team" Section</label>
            </div>

            <div class="mb-3 form-check form-switch">
                <input type="hidden" name="settings[about_us_show_features]" value="0">
                <input class="form-check-input" type="checkbox" id="showFeatures" name="settings[about_us_show_features]" value="1" <?= ($settings['about_us_show_features'] ?? '1') == '1' ? 'checked' : '' ?>>
                <label class="form-check-label fw-bold" for="showFeatures">Show "What We Offer" Section</label>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Representative Image</label>
                <div class="input-group">
                    <input type="file" class="form-control" name="about_us_image" accept="image/*">
                </div>
                <small class="text-muted">Recommended size: 800x600px</small>
                
                <?php if (!empty($settings['about_us_image'])): ?>
                    <div class="mt-2">
                        <label class="form-label d-block">Current Image:</label>
                        <img src="<?= base_url('uploads/system/' . $settings['about_us_image']) ?>" alt="About Image" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
