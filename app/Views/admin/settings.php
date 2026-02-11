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
            
            <!-- Global Setting -->
            <div class="card bg-light border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold">Global OTP Setting</h6>
                    <div class="form-check form-switch">
                        <input type="hidden" name="settings[otp_enabled]" value="no">
                        <input class="form-check-input" type="checkbox" id="otp_enabled" name="settings[otp_enabled]" value="yes" 
                            <?= (isset($settings['otp_enabled']) && $settings['otp_enabled'] == 'yes') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="otp_enabled">Enable One-Time Password (OTP) System-Wide</label>
                        <div class="form-text">When enabled, users without an Authenticator App will receive an Email OTP.</div>
                    </div>
                </div>
            </div>

            <!-- Personal Authenticator Setting -->
            <div class="card border mb-3">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0">Your Personal Authenticator App</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($user['authenticator_secret'])): ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-0 text-muted">Protect your account with Google/Microsoft Authenticator.</p>
                            </div>
                            <button type="button" class="btn btn-success" id="btn-setup-auth">
                                <i class="fas fa-mobile-alt me-2"></i> Setup App
                            </button>
                        </div>

                        <!-- Setup Area -->
                        <div id="auth-setup-area" class="mt-4 border-top pt-4 d-none">
                            <div class="row">
                                <div class="col-md-4 text-center border-end">
                                    <h6 class="fw-bold">Step 1: Scan QR</h6>
                                    <img id="auth-qr-img" src="" alt="QR Code" class="img-fluid border p-2" style="width: 150px; height: 150px;">
                                    <div class="mt-2">
                                        <small class="text-muted">Or enter code:</small>
                                        <div class="user-select-all font-monospace fw-bold" id="auth-secret-text"></div>
                                    </div>
                                </div>
                                <div class="col-md-8 ps-md-4">
                                    <h6 class="fw-bold">Step 2: Verify</h6>
                                    <p class="small text-muted">Enter the 6-digit code from your app.</p>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="verify-auth-code" class="form-control me-2" style="width: 150px;" placeholder="123456" maxlength="6">
                                        <button type="button" class="btn btn-primary" id="btn-verify-auth">Enable</button>
                                    </div>
                                    <div id="auth-msg" class="mt-2"></div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="text-success fw-bold">
                                <i class="fas fa-check-circle me-2"></i> Authenticator App is Active.
                            </div>
                            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#disableAuthModal">
                                Disable
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
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

        <!-- Disable Confirmation Modal -->
        <div class="modal fade" id="disableAuthModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Disable Two-Factor Authentication</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted">To verify it's you, please enter the code from your Authenticator App.</p>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Verification Code</label>
                            <input type="text" id="disable-auth-code" class="form-control form-control-lg text-center letter-spacing-lg" placeholder="123456" maxlength="6">
                        </div>
                        
                        <div class="text-center mb-3">
                            <a href="#" id="btn-send-disable-email" class="text-decoration-none small">
                                <i class="fas fa-envelope me-1"></i> Lost phone? Send code to email
                            </a>
                            <div id="email-sent-msg" class="text-success small mt-1 d-none"></div>
                        </div>
                        
                        <div id="disable-msg" class="text-danger fw-bold text-center"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="btn-confirm-disable">Disable 2FA</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Authenticator Logic ---
        const btnSetup = document.getElementById('btn-setup-auth');
        if (btnSetup) {
            btnSetup.addEventListener('click', function() {
                // Fetch secret and QR
                fetch('<?= base_url('admin/setup-authenticator') ?>')
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('auth-qr-img').src = data.qr_image;
                        document.getElementById('auth-secret-text').innerText = data.secret;
                        document.getElementById('auth-setup-area').classList.remove('d-none');
                        btnSetup.classList.add('d-none');
                    });
            });
        }

        const btnVerify = document.getElementById('btn-verify-auth');
        if (btnVerify) {
            btnVerify.addEventListener('click', function() {
                const code = document.getElementById('verify-auth-code').value;
                const msgDiv = document.getElementById('auth-msg');
                
                fetch('<?= base_url('admin/verify-authenticator') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: 'code=' + code
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        msgDiv.innerHTML = '<span class="text-success fw-bold">' + data.message + '</span>';
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        msgDiv.innerHTML = '<span class="text-danger fw-bold">' + data.message + '</span>';
                    }
                });
            });
        }
        
        // --- Disable Logic ---
        const btnSendEmail = document.getElementById('btn-send-disable-email');
        if (btnSendEmail) {
            btnSendEmail.addEventListener('click', function(e) {
                e.preventDefault();
                const msgDiv = document.getElementById('email-sent-msg');
                msgDiv.innerText = 'Sending...';
                msgDiv.classList.remove('d-none');
                
                fetch('<?= base_url('admin/send-disable-otp') ?>')
                    .then(response => response.json())
                    .then(data => {
                        msgDiv.innerText = data.message;
                        msgDiv.classList.remove('d-none');
                        if(!data.success) msgDiv.classList.replace('text-success', 'text-danger');
                    });
            });
        }
        
        const btnConfirmDisable = document.getElementById('btn-confirm-disable');
        if (btnConfirmDisable) {
            btnConfirmDisable.addEventListener('click', function() {
                const code = document.getElementById('disable-auth-code').value;
                const msgDiv = document.getElementById('disable-msg');
                
                fetch('<?= base_url('admin/disable-authenticator') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                    },
                    body: 'code=' + code
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        msgDiv.className = 'text-success fw-bold text-center';
                        msgDiv.innerText = data.message;
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        msgDiv.innerText = data.message;
                    }
                });
            });
        }
    });
</script>

<?= $this->endSection() ?>
