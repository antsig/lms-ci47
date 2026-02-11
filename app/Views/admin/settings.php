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
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">System Name</label>
                    <input type="text" class="form-control" name="settings[system_name]" 
                           value="<?= esc($settings['system_name'] ?? 'LMS') ?>">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">System Title</label>
                    <input type="text" class="form-control" name="settings[system_title]" 
                           value="<?= esc($settings['system_title'] ?? 'Learning Management System') ?>">
                </div>
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

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" class="form-control" name="settings[phone]" 
                           value="<?= esc($settings['phone'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Admin Revenue Percentage (%)</label>
                     <input type="number" class="form-control" name="settings[admin_revenue_percentage]" 
                           value="<?= esc($settings['admin_revenue_percentage'] ?? '20') ?>">
                </div>
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



            <hr class="my-4">
            
            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="otp_enabled" name="settings[otp_enabled]" value="yes" 
                    <?= (isset($settings['otp_enabled']) && $settings['otp_enabled'] == 'yes') ? 'checked' : '' ?>>
                <label class="form-check-label" for="otp_enabled">Enable Two-Factor Authentication (OTP)</label>
                <div class="form-text">If enabled, users will receive an email with a 6-digit code to login.</div>
            </div>

            <hr class="my-4">

            <h5 class="mb-3 fw-bold text-primary"><i class="fas fa-envelope me-2"></i>SMTP Settings</h5>
            
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label">SMTP Host</label>
                    <input type="text" class="form-control" name="settings[smtp_host]" placeholder="e.g., smtp.gmail.com"
                           value="<?= esc($settings['smtp_host'] ?? '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                     <label class="form-label">SMTP Port</label>
                    <input type="number" class="form-control" name="settings[smtp_port]" id="smtp_port" placeholder="e.g., 587"
                           value="<?= esc($settings['smtp_port'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">SMTP Encryption</label>
                    <select class="form-select" name="settings[smtp_crypto]" id="smtp_crypto">
                        <option value="tls" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'tls') ? 'selected' : '' ?>>TLS (Recommended)</option>
                        <option value="ssl" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'ssl') ? 'selected' : '' ?>>SSL</option>
                        <option value="" <?= (empty($settings['smtp_crypto'])) ? 'selected' : '' ?>>None</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">SMTP Username</label>
                    <input type="text" class="form-control" name="settings[smtp_user]" 
                           value="<?= esc($settings['smtp_user'] ?? '') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">SMTP Password</label>
                    <input type="password" class="form-control" name="settings[smtp_pass]" 
                           value="<?= esc($settings['smtp_pass'] ?? '') ?>">
                </div>
            </div>



            <script>
                document.getElementById('smtp_port').addEventListener('input', function(e) {
                    const port = e.target.value;
                    const cryptoSelect = document.getElementById('smtp_crypto');
                    
                    if (port == 465) {
                        cryptoSelect.value = 'ssl';
                    } else if (port == 587) {
                        cryptoSelect.value = 'tls';
                    } else if (port == 25) {
                        cryptoSelect.value = '';
                    }
                });
            </script>

            <hr>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
