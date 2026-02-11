<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Contact Page Settings</h5>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/admin/settings/update-contact') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <input type="text" class="form-control" name="settings[contact_address]" value="<?= esc($settings['contact_address'] ?? '') ?>" placeholder="e.g. 123 Learning St, Education City">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" class="form-control" name="settings[contact_phone]" value="<?= esc($settings['contact_phone'] ?? '') ?>" placeholder="e.g. +1 (234) 567-890">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control" name="settings[contact_email]" value="<?= esc($settings['contact_email'] ?? '') ?>" placeholder="e.g. support@lms.com">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Google Maps Embed Code (Iframe)</label>
                        <textarea class="form-control" name="settings[contact_map_iframe]" rows="4" placeholder='<iframe src="https://www.google.com/maps/embed?..." ...></iframe>'><?= $settings['contact_map_iframe'] ?? '' ?></textarea>
                        <small class="text-muted">Paste the full iframe code from Google Maps here.</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
