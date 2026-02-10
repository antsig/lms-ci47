<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Home Page Sections</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/settings/update-layout') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold d-block">Hero Section</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_hero]" value="yes" <?= ($settings['show_hero'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Hero Banner</label>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                     <label class="form-label fw-bold d-block">Stats Section</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_stats]" value="yes" <?= ($settings['show_stats'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Statistics (Counts)</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                     <label class="form-label fw-bold d-block">Top Courses</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_top_courses]" value="yes" <?= ($settings['show_top_courses'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Top Rated Courses</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                     <label class="form-label fw-bold d-block">Latest Courses</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_latest_courses]" value="yes" <?= ($settings['show_latest_courses'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Latest Courses</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                     <label class="form-label fw-bold d-block">Categories</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_categories]" value="yes" <?= ($settings['show_categories'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Course Categories</label>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                     <label class="form-label fw-bold d-block">Call to Action</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[show_cta]" value="yes" <?= ($settings['show_cta'] ?? 'yes') == 'yes' ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Instructor CTA</label>
                    </div>
                </div>
            </div>

            <hr>
            
            <h5 class="mb-3 fw-bold">Admin Dashboard Layout</h5>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Layout Mode</label>
                    <select class="form-select" name="settings[layout_mode]">
                        <option value="fluid" <?= ($settings['layout_mode'] ?? '') == 'fluid' ? 'selected' : '' ?>>Fluid (Full Width)</option>
                        <option value="boxed" <?= ($settings['layout_mode'] ?? '') == 'boxed' ? 'selected' : '' ?>>Boxed</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Sidebar Style</label>
                    <select class="form-select" name="settings[sidebar_style]">
                        <option value="light" <?= ($settings['sidebar_style'] ?? '') == 'light' ? 'selected' : '' ?>>Light</option>
                        <option value="dark" <?= ($settings['sidebar_style'] ?? '') == 'dark' ? 'selected' : '' ?>>Dark</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Navbar Color</label>
                     <select class="form-select" name="settings[navbar_color]">
                        <option value="white" <?= ($settings['navbar_color'] ?? '') == 'white' ? 'selected' : '' ?>>White</option>
                        <option value="light" <?= ($settings['navbar_color'] ?? '') == 'light' ? 'selected' : '' ?>>Light Gray</option>
                        <option value="dark" <?= ($settings['navbar_color'] ?? '') == 'dark' ? 'selected' : '' ?>>Dark</option>
                        <option value="primary" <?= ($settings['navbar_color'] ?? '') == 'primary' ? 'selected' : '' ?>>Primary Color</option>
                    </select>
                </div>
            </div>
            
            <hr>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Layout Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
