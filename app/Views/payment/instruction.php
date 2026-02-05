<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm text-center mb-4">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-4x text-warning mb-3"></i>
                        <h2 class="fw-bold">Waiting for Payment</h2>
                        <p class="text-muted">Please complete your payment to finalize enrollment.</p>
                    </div>

                    <h5 class="mb-3">Total Amount</h5>
                    <div class="display-4 fw-bold text-primary mb-4">
                        Rp <?= number_format($payment['amount']) ?>
                    </div>
                    
                    <div class="alert alert-info d-inline-block">
                        Transaction ID: <strong><?= esc($payment['transaction_id']) ?></strong>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Payment Instructions</h5>
                </div>
                <div class="card-body">
                    <?php if ($payment['payment_method'] == 'manual'): ?>
                        <h6 class="fw-bold mb-3">Bank Transfer</h6>
                        <p>Please transfer the <strong>exact amount</strong> to one of the following accounts:</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-university fa-2x me-3 text-secondary"></i>
                                        <h6 class="mb-0">Bank Central Asia (BCA)</h6>
                                    </div>
                                    <p class="mb-0 h5 fw-bold copy-text" role="button" title="Click to copy">1234567890</p>
                                    <smal>a/n LMS Admin</smal>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-light">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-university fa-2x me-3 text-secondary"></i>
                                        <h6 class="mb-0">Bank Mandiri</h6>
                                    </div>
                                    <p class="mb-0 h5 fw-bold copy-text" role="button" title="Click to copy">0987-654-321</p>
                                    <smal>a/n LMS Admin</smal>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <h6 class="fw-bold mb-3">QRIS Payment</h6>
                        <p>Scan the following QR code with your favorite e-wallet or banking app:</p>
                        
                        <div class="text-center p-4 border rounded bg-light mb-3">
                            <!-- Placeholder QRIS - Replace with actual dynamic/static QR -->
                            <div style="width: 200px; height: 200px; background-color: white; margin: 0 auto; display: flex; align-items: center; justify-content: center; border: 1px dashed #ccc;">
                                <div class="text-center">
                                    <i class="fas fa-qrcode fa-4x text-dark"></i>
                                    <p class="small mt-2 mb-0">QR CODE PLACEHOLDER</p>
                                    <small class="text-muted">(Upload actual QR in assets)</small>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 fw-bold">Scan & Pay: Rp <?= number_format($payment['amount']) ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Confirm Payment</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('payment/submit-proof') ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="payment_id" value="<?= $payment['id'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Upload Payment Proof</label>
                            <input type="file" class="form-control" name="proof_file" accept="image/*" required>
                            <div class="form-text">Allowed formats: JPG, PNG. Max size: 2MB.</div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check-circle me-2"></i> I Have Paid
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
