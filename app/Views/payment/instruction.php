<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="bg-primary p-4 text-center text-white relative">
                    <div class="mb-2">
                        <?php if ($payment['payment_status'] == 'paid'): ?>
                            <i class="fas fa-check-circle fa-3x"></i>
                        <?php elseif ($payment['payment_status'] == 'verification_pending'): ?>
                             <i class="fas fa-hourglass-half fa-3x opacity-75"></i>
                        <?php else: ?>
                            <i class="fas fa-clock fa-3x opacity-75"></i>
                        <?php endif; ?>
                    </div>
                    <h4 class="fw-bold mb-1">
                        <?php
                        if ($payment['payment_status'] == 'paid')
                            echo 'Payment Successful';
                        elseif ($payment['payment_status'] == 'verification_pending')
                            echo 'Payment Under Review';
                        else
                            echo 'Awaiting Payment';
                        ?>
                    </h4>
                    <p class="mb-0 opacity-75 small">Order #<?= esc($payment['transaction_id']) ?></p>
                </div>

                <!-- Amount -->
                <div class="text-center py-4 bg-light border-bottom">
                    <small class="text-muted text-uppercase fw-bold ls-1">Total Amount</small>
                    <h2 class="display-5 fw-bold text-primary mb-0">Rp <?= number_format($payment['amount']) ?></h2>
                    <small class="text-danger small"><i class="fas fa-exclamation-circle"></i> Transfer the exact amount</small>
                </div>

                <div class="card-body p-4">
                    <!-- Step 1: Transfer -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary rounded-circle p-2 me-2" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">1</span>
                            <h6 class="fw-bold mb-0">Make a Transfer</h6>
                        </div>

                        <?php if ($payment['payment_method'] == 'manual'): ?>
                            <div class="bg-white border rounded p-3 mb-3 shadow-sm hover-shadow transition">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" alt="BCA" style="height: 20px;" class="me-auto">
                                    <small class="text-muted">BCA</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 fw-bold mb-0 font-monospace">123 456 7890</span>
                                    <button class="btn btn-sm btn-light text-primary" onclick="copyToClipboard('1234567890')"><i class="far fa-copy"></i></button>
                                </div>
                                <small class="text-muted d-block mt-1">a.n. LMS Admin</small>
                            </div>

                            <div class="bg-white border rounded p-3 shadow-sm hover-shadow transition">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" alt="Mandiri" style="height: 20px;" class="me-auto">
                                    <small class="text-muted">MANDIRI</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 fw-bold mb-0 font-monospace">098 765 4321</span>
                                    <button class="btn btn-sm btn-light text-primary" onclick="copyToClipboard('0987654321')"><i class="far fa-copy"></i></button>
                                </div>
                                <small class="text-muted d-block mt-1">a.n. LMS Admin</small>
                            </div>

                        <?php else: ?>
                            <div class="text-center border rounded p-4 bg-light">
                                <div class="bg-white p-2 d-inline-block rounded shadow-sm mb-2">
                                    <i class="fas fa-qrcode fa-5x"></i>
                                </div>
                                <p class="mb-0 fw-bold">Scan QRIS</p>
                                <small class="text-muted">Use any e-wallet app</small>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Step 2: Upload Proof -->
                    <div class="mt-5">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary rounded-circle p-2 me-2" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">2</span>
                            <h6 class="fw-bold mb-0">Payment Proof</h6>
                        </div>

                        <?php if (!empty($payment['proof_file'])): ?>
                            <div class="mb-4 text-center">
                                <div class="bg-light p-3 rounded border mb-2">
                                    <img src="<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>" 
                                         alt="Proof" class="img-fluid rounded shadow-sm" style="max-height: 300px;">
                                </div>
                                <small class="text-muted"><i class="fas fa-file-image"></i> Uploaded Proof</small>
                            </div>
                        <?php endif; ?>

                        <?php if ($payment['payment_status'] == 'pending'): ?>
                            <form action="<?= base_url('payment/submit-proof') ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">

                                <div class="mb-3">
                                    <label class="form-label">Upload New Proof</label>
                                    <input type="file" class="form-control form-control-lg fs-6" name="proof_file" accept="image/*" required>
                                    <div class="form-text small">Accepted: JPG, PNG. Max 2MB.</div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                                    Submit Payment Proof <i class="fas fa-upload ms-2"></i>
                                </button>
                            </form>
                        <?php elseif ($payment['payment_status'] == 'verification_pending'): ?>
                            <div class="alert alert-info text-center">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h5 class="alert-heading">Waiting for Confirmation</h5>
                                <p class="mb-0">You have submitted the payment proof. Please wait for the admin to verify your payment.</p>
                            </div>
                        <?php elseif ($payment['payment_status'] == 'paid'): ?>
                            <div class="alert alert-success text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5 class="alert-heading">Payment Successful</h5>
                                <p class="mb-0">Your payment has been verified and approved.</p>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?= base_url('/student/course-player/' . $payment['course_id']) ?>" class="btn btn-success fw-bold">
                                    Start Learning <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= base_url('/dashboard') ?>" class="text-muted text-decoration-none small"><i class="fas fa-arrow-left me-1"></i> I'll do this later</a>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Copied!',
                showConfirmButton: false,
                timer: 1000
            });
        });
    }
</script>

<style>
    .hover-shadow:hover {
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition {
        transition: all 0.3s ease;
    }
    .fs-7 { font-size: 0.9rem; }
    .ls-1 { letter-spacing: 1px; }
</style>

<?= $this->endSection() ?>
