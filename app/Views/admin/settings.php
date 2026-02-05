<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">System Settings</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/admin/update-settings') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label">System Name</label>
                <input type="text" class="form-control" name="settings[system_name]" 
                       value="<?= esc($settings['system_name'] ?? 'LMS') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">System Title</label>
                <input type="text" class="form-control" name="settings[system_title]" 
                       value="<?= esc($settings['system_title'] ?? 'Learning Management System') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">System Email</label>
                <input type="email" class="form-control" name="settings[system_email]" 
                       value="<?= esc($settings['system_email'] ?? 'admin@lms.com') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="settings[address]"><?= esc($settings['address'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="settings[phone]" 
                       value="<?= esc($settings['phone'] ?? '') ?>">
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Currency</label>
                    <input type="text" class="form-control" name="settings[currency]" 
                           value="<?= esc($settings['currency'] ?? 'Rp') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Language</label>
                    <input type="text" class="form-control" name="settings[language]" 
                           value="<?= esc($settings['language'] ?? 'english') ?>">
                </div>
            </div>

             <div class="mb-3">
                <label class="form-label">Admin Revenue Percentage (%)</label>
                 <input type="number" class="form-control" name="settings[admin_revenue_percentage]" 
                       value="<?= esc($settings['admin_revenue_percentage'] ?? '20') ?>">
            </div>

            <hr>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
