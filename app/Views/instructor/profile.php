<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('instructor/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">My Profile</h5>
        <a href="<?= base_url('/instructor/change-password') ?>" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-key"></i> Change Password
        </a>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/instructor/profile/update') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="row align-items-center mb-4">
                <div class="col-md-2 text-center">
                    <img src="<?= base_url('/uploads/user_images/' . ($user['image'] ?? 'default.jpg')) ?>" 
                         class="rounded-circle img-fluid" style="width: 100px; height: 100px; object-fit: cover;"
                         onerror="this.src='<?= base_url('/uploads/thumbnails/default.jpg') ?>'">
                </div>
                <div class="col-md-10">
                    <label class="form-label">Profile Photo</label>
                    <input type="file" class="form-control" name="image">
                    <small class="text-muted">Upload a new photo to update.</small>
                    
                    <hr class="my-3">

                    <label class="form-label">Digital Signature</label>
                    <div class="mb-2">
                        <?php if (!empty($user['signature'])): ?>
                            <img src="<?= base_url('/uploads/signatures/' . $user['signature']) ?>" 
                                 alt="Signature" style="max-height: 60px; border: 1px solid #ccc; padding: 5px;">
                        <?php else: ?>
                            <span class="text-muted small">No signature uploaded.</span>
                        <?php endif; ?>
                    </div>
                    <input type="file" class="form-control" name="signature" accept="image/*">
                    <small class="text-muted">Upload an image of your signature (PNG transparent recommended).</small>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?= esc($user['first_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?= esc($user['last_name']) ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" value="<?= esc($user['email']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="tel" class="form-control" name="phone" value="<?= esc($user['phone'] ?? '') ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Biography</label>
                <textarea class="form-control" name="biography" rows="4"><?= esc($user['biography'] ?? '') ?></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
        
        <hr class="my-4">

        <!-- Personal Authenticator Setting -->
        <div class="card border mb-3">
            <div class="card-header bg-white">
                <h6 class="fw-bold mb-0">Two-Factor Authentication</h6>
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
                fetch('<?= base_url('security/setup-authenticator') ?>')
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
                
                fetch('<?= base_url('security/verify-authenticator') ?>', {
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
                
                fetch('<?= base_url('security/send-disable-otp') ?>')
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
                
                fetch('<?= base_url('security/disable-authenticator') ?>', {
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
